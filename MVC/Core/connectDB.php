<?php
class connectDB {
    public $con;

    public function __construct() {
        $this->con = mysqli_connect('127.0.0.1', 'root', '', 'baitaplon', 3306);
        if (!$this->con) {
            throw new RuntimeException('Không thể kết nối CSDL baitaplon trên localhost:3306.');
        }
        mysqli_set_charset($this->con, 'utf8mb4');
    }
}
?>