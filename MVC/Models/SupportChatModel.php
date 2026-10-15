<?php
class SupportChatModel extends connectDB {
    private static $schemaReady = false;

    public function __construct() {
        parent::__construct();
        if (self::$schemaReady) return;
        $schema = file_get_contents(__DIR__ . '/../../chat-service/schema.sql');
        if (!is_string($schema) || !mysqli_multi_query($this->con, $schema)) {
            throw new RuntimeException('Không thể khởi tạo cơ sở dữ liệu chat hỗ trợ.');
        }
        do {
            if ($result = mysqli_store_result($this->con)) mysqli_free_result($result);
        } while (mysqli_more_results($this->con) && mysqli_next_result($this->con));
        self::$schemaReady = true;
    }

    public function OpenOrCreate($customerId, $customerName) {
        $sql = "INSERT INTO support_conversations (customer_id, customer_name, status, created_at, updated_at)
                VALUES (?, ?, 'open', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), customer_name = VALUES(customer_name), status = 'open', updated_at = CURRENT_TIMESTAMP";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'ss', $customerId, $customerName);
        if (!mysqli_stmt_execute($stmt)) { mysqli_stmt_close($stmt); return false; }
        $id = (int)mysqli_insert_id($this->con);
        mysqli_stmt_close($stmt);
        return $this->GetConversation($id);
    }

    public function GetConversation($id) {
        $stmt = mysqli_prepare($this->con, 'SELECT id, customer_id, customer_name, status, created_at, updated_at FROM support_conversations WHERE id = ? LIMIT 1');
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $row ?: null;
    }

    public function GetCustomerConversation($id, $customerId) {
        $stmt = mysqli_prepare($this->con, 'SELECT id, customer_id, customer_name, status, created_at, updated_at FROM support_conversations WHERE id = ? AND customer_id = ? LIMIT 1');
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, 'is', $id, $customerId);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $row ?: null;
    }

    public function GetInbox() {
        $sql = "SELECT c.id, c.customer_id, c.customer_name, c.status, c.updated_at,
                       (SELECT m.body FROM support_messages m WHERE m.conversation_id = c.id ORDER BY m.id DESC LIMIT 1) AS last_message,
                       (SELECT m.sender_type FROM support_messages m WHERE m.conversation_id = c.id ORDER BY m.id DESC LIMIT 1) AS last_sender_type,
                       (SELECT COUNT(*) FROM support_messages m WHERE m.conversation_id = c.id AND m.sender_type = 'customer' AND m.id > c.last_admin_read_message_id) AS unread_count
                FROM support_conversations c
                ORDER BY c.updated_at DESC, c.id DESC";
        $result = mysqli_query($this->con, $sql);
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public function GetCustomerUnreadState($customerId) {
        $sql = "SELECT c.id AS conversation_id,
                       COUNT(m.id) AS unread_count
                FROM support_conversations c
                LEFT JOIN support_messages m
                    ON m.conversation_id = c.id
                   AND m.sender_type = 'admin'
                   AND m.id > c.last_customer_read_message_id
                WHERE c.customer_id = ?
                GROUP BY c.id
                LIMIT 1";
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return ['conversation_id'=>null, 'unread_count'=>0];
        mysqli_stmt_bind_param($stmt, 's', $customerId);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $row ?: ['conversation_id'=>null, 'unread_count'=>0];
    }

    public function GetAdminUnreadCount() {
        $sql = "SELECT COUNT(*) AS unread_count
                FROM support_messages m
                INNER JOIN support_conversations c ON c.id = m.conversation_id
                WHERE m.sender_type = 'customer'
                  AND m.id > c.last_admin_read_message_id";
        $result = mysqli_query($this->con, $sql);
        $row = $result ? mysqli_fetch_assoc($result) : null;
        return (int)($row['unread_count'] ?? 0);
    }

    public function GetMessages($conversationId, $limit = 100) {
        $limit = max(1, min(200, (int)$limit));
        $result = mysqli_query($this->con, 'SELECT id, sender_type, sender_id, sender_name, body, created_at FROM support_messages WHERE conversation_id = ' . (int)$conversationId . ' ORDER BY id DESC LIMIT ' . $limit);
        if (!$result) return [];
        $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return array_reverse($messages);
    }

    public function MarkRead($conversationId, $role) {
        $column = $role === 'admin' ? 'last_admin_read_message_id' : 'last_customer_read_message_id';
        $stmt = mysqli_prepare($this->con, 'SELECT COALESCE(MAX(id), 0) AS last_id FROM support_messages WHERE conversation_id = ?');
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'i', $conversationId);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        $lastId = (int)($row['last_id'] ?? 0);
        $stmt = mysqli_prepare($this->con, "UPDATE support_conversations SET {$column} = GREATEST({$column}, ?) WHERE id = ?");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'ii', $lastId, $conversationId);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}
