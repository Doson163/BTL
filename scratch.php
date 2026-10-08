<?php
require 'WinmartMVC/Core/connectDB.php';
class Test extends DB {
    public function getError() {
        $customer = mysqli_prepare($this->con, "INSERT INTO Khachhang (MaKH, TenKH, DienThoai, DiemTichLuy) VALUES ('KH9999', 'Test', '0123456789', 0)");
        if (!$customer) return "Customer Error: " . mysqli_error($this->con);
        if (!mysqli_stmt_execute($customer)) return "Customer Exec Error: " . mysqli_stmt_error($customer);
        
        $account = mysqli_prepare($this->con, "INSERT INTO TaiKhoanKhachHang (MaKH, Email, DienThoai, MatKhauHash) VALUES ('KH9999', 'test@example.com', '0123456789', 'hash')");
        if (!$account) return "Account Error: " . mysqli_error($this->con);
        if (!mysqli_stmt_execute($account)) return "Account Exec Error: " . mysqli_stmt_error($account);
        return 'Success';
    }
}
$t = new Test();
echo $t->getError();
?>
