<?php
class connectDB {
    public $con;

    public function __construct() {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        $database = getenv('DB_NAME') ?: 'baitaplon';
        $port = (int)(getenv('DB_PORT') ?: 3306);
        $this->con = mysqli_connect($host, $user, $password, $database, $port);
        if (!$this->con) {
            throw new RuntimeException('Không thể kết nối cơ sở dữ liệu. Hãy kiểm tra DB_HOST, DB_NAME và thông tin đăng nhập.');
        }
        mysqli_set_charset($this->con, 'utf8mb4');
    }
}
?>
