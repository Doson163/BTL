'use strict';

const fs = require('node:fs');
const http = require('node:http');
const crypto = require('node:crypto');
const mysql = require('mysql2/promise');
const { Server } = require('socket.io');

const port = Number(process.env.PORT || 3000);
const secret = process.env.CHAT_JWT_SECRET || '';
if (Buffer.byteLength(secret) < 32) throw new Error('CHAT_JWT_SECRET must contain at least 32 bytes.');

const pool = mysql.createPool({
    host: process.env.DB_HOST || 'db',
    port: Number(process.env.DB_PORT || 3306),
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME || 'baitaplon',
    waitForConnections: true,
    connectionLimit: 10,
    charset: 'utf8mb4'
});

function decodeSegment(segment) {
    return Buffer.from(segment, 'base64url').toString('utf8');
}

function verifyToken(token) {
    if (typeof token !== 'string' || token.length > 4096) throw new Error('invalid token');
    const parts = token.split('.');
    if (parts.length !== 3) throw new Error('invalid token');
    const [encodedHeader, encodedPayload, signature] = parts;
    const header = JSON.parse(decodeSegment(encodedHeader));
    const payload = JSON.parse(decodeSegment(encodedPayload));
    if (header.alg !== 'HS256' || payload.iss !== 'DailyMart' || payload.aud !== 'dailymart-chat') throw new Error('invalid token');
    const expected = crypto.createHmac('sha256', secret).update(`${encodedHeader}.${encodedPayload}`).digest();
    const actual = Buffer.from(signature, 'base64url');
    if (actual.length !== expected.length || !crypto.timingSafeEqual(actual, expected)) throw new Error('invalid token');
    const now = Math.floor(Date.now() / 1000);
    if (!Number.isInteger(payload.exp) || payload.exp <= now || payload.iat > now + 30) throw new Error('expired token');
    if (!['customer', 'admin'].includes(payload.role) || typeof payload.sub !== 'string' || payload.sub.length > 100) throw new Error('invalid identity');
    return payload;
}

async function migrate() {
    const sql = fs.readFileSync(`${__dirname}/schema.sql`, 'utf8');
    const statements = sql.split(';').map(statement => statement.trim()).filter(Boolean);
    for (const statement of statements) await pool.query(statement);
}

const server = http.createServer((request, response) => {
    if (request.url === '/health') {
        response.writeHead(200, { 'Content-Type': 'application/json' });
        response.end('{"ok":true}');
        return;
    }
    response.writeHead(404);
    response.end('Not found');
});

const io = new Server(server, {
    path: '/socket.io/',
    serveClient: true,
    maxHttpBufferSize: 8192,
    pingInterval: 25000,
    pingTimeout: 20000
});

io.use((socket, next) => {
    try {
        socket.data.identity = verifyToken(socket.handshake.auth && socket.handshake.auth.token);
        next();
    } catch (_) {
        next(new Error('Phiên chat hết hạn hoặc không hợp lệ. Hãy tải lại trang.'));
    }
});

function conversationRoom(id) {
    return `support:conversation:${id}`;
}

function customerRoom(customerId) {
    return `support:customer:${customerId}`;
}

let lastPendingOrderCount = null;
async function publishPendingOrderCount() {
    try {
        const [rows] = await pool.execute('SELECT COUNT(*) AS total FROM donhang WHERE MaNV IS NULL AND TrangThai = 1');
        const count = Number(rows[0] && rows[0].total || 0);
        if (lastPendingOrderCount === null) {
            lastPendingOrderCount = count;
            return;
        }
        if (count !== lastPendingOrderCount) {
            lastPendingOrderCount = count;
            io.to('admin:notifications').emit('admin:order-count', count);
        }
    } catch (error) {
        console.error('order notification poll failed', error.message);
    }
}

io.on('connection', socket => {
    const identity = socket.data.identity;
    if (identity.role === 'admin') {
        socket.join('support:admins');
        socket.join('admin:notifications');
    }
    else socket.join(customerRoom(identity.sub));

    socket.on('support:join', async (rawId, acknowledge = () => {}) => {
        try {
            const id = Number(rawId);
            if (!Number.isSafeInteger(id) || id < 1) return acknowledge({ ok: false, error: 'Cuộc trò chuyện không hợp lệ.' });
            const [rows] = identity.role === 'admin'
                ? await pool.execute('SELECT id, status FROM support_conversations WHERE id = ? LIMIT 1', [id])
                : await pool.execute('SELECT id, status FROM support_conversations WHERE id = ? AND customer_id = ? LIMIT 1', [id, identity.sub]);
            if (!rows.length) return acknowledge({ ok: false, error: 'Bạn không có quyền truy cập cuộc trò chuyện này.' });
            if (socket.data.conversationId) socket.leave(conversationRoom(socket.data.conversationId));
            socket.data.conversationId = id;
            socket.join(conversationRoom(id));
            if (rows[0].status === 'open') {
                io.to(conversationRoom(id)).emit('support:status', { conversationId: id, status: 'open' });
                io.to('support:admins').emit('support:inbox-update', { conversationId: id, status: 'open' });
            }
            acknowledge({ ok: true, status: rows[0].status });
        } catch (error) {
            console.error('support:join failed', error.message);
            acknowledge({ ok: false, error: 'Không thể mở cuộc trò chuyện.' });
        }
    });

    socket.on('support:send', async (payload, acknowledge = () => {}) => {
        try {
            const id = Number(payload && payload.conversationId);
            const body = String(payload && payload.message || '').trim();
            if (!Number.isSafeInteger(id) || id < 1 || socket.data.conversationId !== id) return acknowledge({ ok: false, error: 'Hãy mở lại cuộc trò chuyện.' });
            if (!body || body.length > 2000) return acknowledge({ ok: false, error: 'Tin nhắn phải có từ 1 đến 2.000 ký tự.' });
            const [rows] = identity.role === 'admin'
                ? await pool.execute("SELECT id, customer_id FROM support_conversations WHERE id = ? AND status = 'open' LIMIT 1", [id])
                : await pool.execute("SELECT id, customer_id FROM support_conversations WHERE id = ? AND customer_id = ? AND status = 'open' LIMIT 1", [id, identity.sub]);
            if (!rows.length) return acknowledge({ ok: false, error: 'Cuộc trò chuyện đã đóng. Hãy mở lại khung chat để tiếp tục.' });

            const senderName = String(identity.name || (identity.role === 'admin' ? 'Quản trị viên' : 'Khách hàng')).slice(0, 150);
            const senderId = identity.sub;
            const [result] = await pool.execute(
                'INSERT INTO support_messages (conversation_id, sender_type, sender_id, sender_name, body) VALUES (?, ?, ?, ?, ?)',
                [id, identity.role, senderId, senderName, body]
            );
            await pool.execute('UPDATE support_conversations SET updated_at = CURRENT_TIMESTAMP WHERE id = ?', [id]);
            const message = {
                id: result.insertId,
                conversationId: id,
                senderType: identity.role,
                senderId,
                senderName,
                body,
                createdAt: new Date().toISOString()
            };
            if (identity.role === 'admin') {
                io.to(conversationRoom(id)).to(customerRoom(rows[0].customer_id)).emit('support:message', message);
            } else {
                io.to(conversationRoom(id)).emit('support:message', message);
            }
            io.to('support:admins').emit('support:inbox-update', { conversationId: id, message });
            acknowledge({ ok: true, id: message.id });
        } catch (error) {
            console.error('support:send failed', error.message);
            acknowledge({ ok: false, error: 'Không gửi được tin nhắn. Vui lòng thử lại.' });
        }
    });

    socket.on('support:read', async (rawId, acknowledge = () => {}) => {
        try {
            const id = Number(rawId);
            if (!Number.isSafeInteger(id) || socket.data.conversationId !== id) return acknowledge({ ok: false });
            const [rows] = identity.role === 'admin'
                ? await pool.execute('SELECT id FROM support_conversations WHERE id = ? LIMIT 1', [id])
                : await pool.execute('SELECT id FROM support_conversations WHERE id = ? AND customer_id = ? LIMIT 1', [id, identity.sub]);
            if (!rows.length) return acknowledge({ ok: false });
            const column = identity.role === 'admin' ? 'last_admin_read_message_id' : 'last_customer_read_message_id';
            const [lastRows] = await pool.execute('SELECT COALESCE(MAX(id), 0) AS last_id FROM support_messages WHERE conversation_id = ?', [id]);
            await pool.execute(`UPDATE support_conversations SET ${column} = GREATEST(${column}, ?) WHERE id = ?`, [lastRows[0].last_id, id]);
            if (identity.role === 'admin') {
                const [countRows] = await pool.execute(
                    "SELECT COUNT(*) AS unread_count FROM support_messages m INNER JOIN support_conversations c ON c.id = m.conversation_id WHERE m.sender_type = 'customer' AND m.id > c.last_admin_read_message_id"
                );
                io.to('support:admins').emit('support:unread-count', Number(countRows[0].unread_count || 0));
            } else {
                const [countRows] = await pool.execute(
                    "SELECT COUNT(*) AS unread_count FROM support_messages m INNER JOIN support_conversations c ON c.id = m.conversation_id WHERE c.customer_id = ? AND m.sender_type = 'admin' AND m.id > c.last_customer_read_message_id",
                    [identity.sub]
                );
                io.to(customerRoom(identity.sub)).emit('support:customer-unread-count', Number(countRows[0].unread_count || 0));
            }
            acknowledge({ ok: true });
        } catch (error) {
            console.error('support:read failed', error.message);
            acknowledge({ ok: false });
        }
    });

});

migrate().then(() => {
    publishPendingOrderCount();
    setInterval(publishPendingOrderCount, 5000);
    server.listen(port, '0.0.0.0', () => console.log(`DailyMart chat service listening on ${port}`));
}).catch(error => {
    console.error('Chat service startup failed', error);
    process.exit(1);
});
