<?php
class DB {
    public $con;
    function __construct(){
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        $database = getenv('DB_NAME') ?: 'baitaplon';
        $port = (int)(getenv('DB_PORT') ?: 3306);
        $this->con = mysqli_connect($host, $username, $password, $database, $port);
        if (!$this->con) {
            throw new RuntimeException('Không thể kết nối cơ sở dữ liệu. Hãy kiểm tra cấu hình DB_HOST/DB_NAME.');
        }
        mysqli_set_charset($this->con, 'utf8mb4');
    }
}
?>
