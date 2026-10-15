<?php
class SupportChat extends controller {
    private $chat;

    public function __construct() { $this->chat = $this->model('SupportChatModel'); }

    private function respond($payload, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }

    private function isCustomerRequest() {
        // Customer chat is routed through store.php; admin chat is routed through index.php.
        // A browser session can contain both login keys, so the request entry point must
        // decide which identity is used instead of preferring the admin session globally.
        $script = (string)($_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '');
        return basename($script) === 'store.php';
    }

    private function requireCustomer() {
        if (empty($_SESSION['shop_customer']['MaKH'])) $this->respond(['ok'=>false, 'error'=>'Vui lòng đăng nhập để nhắn tin với quản trị viên.'], 401);
    }

    private function requireAdmin() {
        if (empty($_SESSION['user_login'])) $this->respond(['ok'=>false, 'error'=>'Bạn cần đăng nhập tài khoản quản trị.'], 401);
    }

    private function requireCsrf() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->respond(['ok'=>false, 'error'=>'Phương thức không được hỗ trợ.'], 405);
        $expected = (string)($_SESSION['shop_csrf'] ?? '');
        $provided = (string)($_POST['csrf_token'] ?? '');
        if ($expected === '' || !hash_equals($expected, $provided)) $this->respond(['ok'=>false, 'error'=>'Phiên làm việc hết hạn. Hãy tải lại trang.'], 403);
    }

    private function issueToken($identity, $role, $name) {
        $secret = (string)(getenv('CHAT_JWT_SECRET') ?: '');
        if (strlen($secret) < 32) $this->respond(['ok'=>false, 'error'=>'Dịch vụ chat chưa được cấu hình.'], 503);
        $now = time();
        $encode = static function ($value) { return rtrim(strtr(base64_encode($value), '+/', '-_'), '='); };
        $header = $encode(json_encode(['alg'=>'HS256', 'typ'=>'JWT']));
        $payload = $encode(json_encode([
            'iss'=>'DailyMart', 'aud'=>'dailymart-chat', 'sub'=>(string)$identity,
            'role'=>$role, 'name'=>mb_substr((string)$name, 0, 150), 'iat'=>$now, 'exp'=>$now + 900
        ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE));
        $signature = $encode(hash_hmac('sha256', $header . '.' . $payload, $secret, true));
        return $header . '.' . $payload . '.' . $signature;
    }

    public function Token() {
        if ($this->isCustomerRequest()) {
            $this->requireCustomer();
            $user = $_SESSION['shop_customer'];
            $id = (string)$user['MaKH'];
            $name = (string)($user['TenKH'] ?? 'Khách hàng');
            $role = 'customer';
        } else {
            $this->requireAdmin();
            $user = $_SESSION['user_login'];
            $id = (string)($user['username'] ?? 'admin');
            $name = (string)($user['hoten'] ?? 'Quản trị viên');
            $role = 'admin';
        }
        $this->respond(['ok'=>true, 'token'=>$this->issueToken($id, $role, $name), 'role'=>$role, 'expiresAt'=>time() + 900]);
    }

    public function Open() {
        $this->requireCustomer();
        $this->requireCsrf();
        $customer = $_SESSION['shop_customer'];
        $conversation = $this->chat->OpenOrCreate((string)$customer['MaKH'], (string)($customer['TenKH'] ?? 'Khách hàng'));
        if (!$conversation) $this->respond(['ok'=>false, 'error'=>'Không thể mở cuộc trò chuyện lúc này.'], 500);
        $this->chat->MarkRead((int)$conversation['id'], 'customer');
        $this->respond(['ok'=>true, 'conversation'=>$conversation, 'messages'=>$this->chat->GetMessages((int)$conversation['id'])]);
    }

    public function History($conversationId = 0) {
        $conversationId = (int)$conversationId;
        if ($this->isCustomerRequest()) {
            $this->requireCustomer();
            $conversation = $this->chat->GetCustomerConversation($conversationId, (string)$_SESSION['shop_customer']['MaKH']);
            $role = 'customer';
        } else {
            $this->requireAdmin();
            $conversation = $this->chat->GetConversation($conversationId);
            $role = 'admin';
        }
        if (!$conversation) $this->respond(['ok'=>false, 'error'=>'Không tìm thấy cuộc trò chuyện.'], 404);
        $this->chat->MarkRead($conversationId, $role);
        $unreadCount = $role === 'admin' ? $this->chat->GetAdminUnreadCount() : (int)$this->chat->GetCustomerUnreadState((string)$_SESSION['shop_customer']['MaKH'])['unread_count'];
        $this->respond(['ok'=>true, 'conversation'=>$conversation, 'messages'=>$this->chat->GetMessages($conversationId), 'unreadCount'=>$unreadCount]);
    }

    public function Unread() {
        if ($this->isCustomerRequest()) {
            $this->requireCustomer();
            $state = $this->chat->GetCustomerUnreadState((string)$_SESSION['shop_customer']['MaKH']);
            $this->respond(['ok'=>true, 'conversationId'=>$state['conversation_id'], 'unreadCount'=>(int)$state['unread_count']]);
        }
        $this->requireAdmin();
        $this->respond(['ok'=>true, 'unreadCount'=>$this->chat->GetAdminUnreadCount()]);
    }

    public function Inbox() {
        $this->requireAdmin();
        $this->view('Master', ['page'=>'SupportChat_V', 'conversations'=>$this->chat->GetInbox()]);
    }
}
