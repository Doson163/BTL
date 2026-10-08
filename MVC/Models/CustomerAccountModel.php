<?php
class CustomerAccountModel extends connectDB {
    public function Register($name, $phone, $email, $passwordHash, $customerId) {
        mysqli_begin_transaction($this->con);
        try {
            $customer = mysqli_prepare($this->con, 'INSERT INTO Khachhang (MaKH, TenKH, DienThoai, DiemTichLuy) VALUES (?, ?, ?, 0)');
            $account = mysqli_prepare($this->con, 'INSERT INTO TaiKhoanKhachHang (MaKH, Email, DienThoai, MatKhauHash) VALUES (?, ?, ?, ?)');
            if (!$customer || !$account) throw new Exception('Cannot prepare registration.');
            mysqli_stmt_bind_param($customer, 'sss', $customerId, $name, $phone);
            if (!mysqli_stmt_execute($customer)) throw new Exception('Cannot create customer.');
            mysqli_stmt_bind_param($account, 'ssss', $customerId, $email, $phone, $passwordHash);
            if (!mysqli_stmt_execute($account)) throw new Exception('Cannot create account.');
            mysqli_commit($this->con);
            return true;
        } catch (Throwable $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }

    public function Authenticate($identity) {
        $email = strtolower(trim((string)$identity));
        $phoneDigits = preg_replace('/\D+/', '', (string)$identity);
        $phoneLocal = $phoneDigits;
        if (substr($phoneLocal, 0, 2) === '84' && strlen($phoneLocal) === 11) $phoneLocal = '0' . substr($phoneLocal, 2);
        $stmt = mysqli_prepare($this->con, "SELECT a.MaKH, a.Email, a.DienThoai, a.MatKhauHash, k.TenKH FROM TaiKhoanKhachHang a JOIN Khachhang k ON k.MaKH = a.MaKH WHERE LOWER(a.Email) = ? OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.DienThoai, '+', ''), ' ', ''), '-', ''), '(', ''), ')', ''), '.', '') IN (?, ?) LIMIT 1");
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, 'sss', $email, $phoneDigits, $phoneLocal);
        if (!mysqli_stmt_execute($stmt)) return null;
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
    }

    public function GetProfile($customerId) {
        $stmt = mysqli_prepare($this->con, 'SELECT k.MaKH, k.TenKH, k.DienThoai, k.DiemTichLuy, k.DiaChi AS DiaChiMacDinh, a.Email, a.TenNguoiNhan, a.DienThoaiNhan FROM Khachhang k JOIN TaiKhoanKhachHang a ON a.MaKH = k.MaKH WHERE k.MaKH = ? LIMIT 1');
        if (!$stmt) return null;
        $customerId = (string)$customerId;
        mysqli_stmt_bind_param($stmt, 's', $customerId);
        if (!mysqli_stmt_execute($stmt)) return null;
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
    }

    public function UpdateProfile($customerId, $name, $phone, $email, $recipient, $recipientPhone, $address) {
        mysqli_begin_transaction($this->con);
        try {
            $customer = mysqli_prepare($this->con, 'UPDATE Khachhang SET TenKH = ?, DienThoai = ?, DiaChi = ? WHERE MaKH = ?');
            $account = mysqli_prepare($this->con, 'UPDATE TaiKhoanKhachHang SET Email = ?, DienThoai = ?, TenNguoiNhan = ?, DienThoaiNhan = ? WHERE MaKH = ?');
            if (!$customer || !$account) throw new Exception('Cannot prepare profile update.');
            mysqli_stmt_bind_param($customer, 'ssss', $name, $phone, $address, $customerId);
            if (!mysqli_stmt_execute($customer)) throw new Exception('Cannot update customer.');
            mysqli_stmt_bind_param($account, 'sssss', $email, $phone, $recipient, $recipientPhone, $customerId);
            if (!mysqli_stmt_execute($account)) throw new Exception('Cannot update account.');
            mysqli_commit($this->con);
            return true;
        } catch (Throwable $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }
}
?>
