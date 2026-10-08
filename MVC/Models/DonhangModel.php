<?php
class DonhangModel extends connectDB {
    
    // 1. Lấy danh sách đơn hàng (CÓ TÌM KIẾM)
    public function DanhSach($keyword = "") {
        // Kết nối bảng Donhang với Khachhang và Nhanvien để lấy tên
        $sql = "SELECT Donhang.*, Khachhang.TenKH, Nhanvien.HoTen
                FROM Donhang
                LEFT JOIN Khachhang ON Donhang.MaKH = Khachhang.MaKH
                LEFT JOIN Nhanvien ON Donhang.MaNV = Nhanvien.MaNV";
        
        // Nếu có từ khóa tìm kiếm thì nối thêm điều kiện WHERE
        if($keyword != "") {
            $sql .= " WHERE Donhang.MaHD LIKE '%$keyword%' OR Khachhang.TenKH LIKE '%$keyword%'";
        }
        
        $sql .= " ORDER BY NgayLap DESC"; // Đơn mới nhất lên đầu
        return mysqli_query($this->con, $sql);
    }

    // 2. Lấy thông tin chi tiết của 1 đơn hàng (để xem món gì bên trong)
    public function ChiTiet($mahd) {
        $sql = "SELECT ChitietDonhang.*, Sanpham.TenSP, Sanpham.HinhAnh
                FROM ChitietDonhang
                LEFT JOIN Sanpham ON ChitietDonhang.MaSP = Sanpham.MaSP
                WHERE MaHD = '$mahd'";
        return mysqli_query($this->con, $sql);
    }

    // 3. Lấy thông tin 1 đơn hàng cụ thể (để in hóa đơn)
// File: DonhangModel.php

public function GetDonhangByID($mahd) {
    // Thêm Khachhang.DiaChi vào danh sách cột cần lấy (SELECT)
    $sql = "SELECT Donhang.*, Khachhang.TenKH, Khachhang.DienThoai, Khachhang.DiaChi, Nhanvien.HoTen, Phuongthucthanhtoan.TenPT AS TenPhuongThuc
            FROM Donhang
            LEFT JOIN Khachhang ON Donhang.MaKH = Khachhang.MaKH
            LEFT JOIN Nhanvien ON Donhang.MaNV = Nhanvien.MaNV
            LEFT JOIN Phuongthucthanhtoan ON Donhang.MaPT = Phuongthucthanhtoan.MaPT
            WHERE MaHD = '$mahd'";
    return mysqli_query($this->con, $sql);
}

    public function HuyDon($mahd) {
        mysqli_begin_transaction($this->con);
        try {
            $statusStmt = mysqli_prepare($this->con, 'SELECT TrangThai FROM Donhang WHERE MaHD = ? FOR UPDATE');
            $detailStmt = mysqli_prepare($this->con, 'SELECT MaSP, SoLuong FROM ChitietDonhang WHERE MaHD = ?');
            $stockStmt = mysqli_prepare($this->con, 'UPDATE Sanpham SET SoLuongTon = SoLuongTon + ? WHERE MaSP = ?');
            $cancelStmt = mysqli_prepare($this->con, 'UPDATE Donhang SET TrangThai = 5 WHERE MaHD = ? AND TrangThai IN (1, 2)');
            if (!$statusStmt || !$detailStmt || !$stockStmt || !$cancelStmt) throw new Exception('Unable to prepare cancellation.');

            $mahd = (int)$mahd;
            mysqli_stmt_bind_param($statusStmt, 'i', $mahd);
            if (!mysqli_stmt_execute($statusStmt)) throw new Exception('Unable to read order status.');
            $order = mysqli_fetch_assoc(mysqli_stmt_get_result($statusStmt));
            if (!$order || !in_array((int)$order['TrangThai'], [1, 2], true)) {
                mysqli_rollback($this->con);
                return false;
            }

            mysqli_stmt_bind_param($detailStmt, 'i', $mahd);
            if (!mysqli_stmt_execute($detailStmt)) throw new Exception('Unable to read order items.');
            $items = mysqli_stmt_get_result($detailStmt);
            while ($item = mysqli_fetch_assoc($items)) {
                $quantity = (int)$item['SoLuong'];
                $productId = (string)$item['MaSP'];
                mysqli_stmt_bind_param($stockStmt, 'is', $quantity, $productId);
                if (!mysqli_stmt_execute($stockStmt) || mysqli_stmt_affected_rows($stockStmt) !== 1) throw new Exception('Unable to restore product stock.');
            }

            mysqli_stmt_bind_param($cancelStmt, 'i', $mahd);
            if (!mysqli_stmt_execute($cancelStmt) || mysqli_stmt_affected_rows($cancelStmt) !== 1) throw new Exception('Order status changed.');
            mysqli_commit($this->con);
            return true;
        } catch (Throwable $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }

    public function CapNhatTrangThai($mahd, $trangthai) {
        $trangthai = (int)$trangthai;
        if (!in_array($trangthai, [1, 2, 3, 4], true)) return false;
        
        // Cho phép Admin cập nhật trạng thái tự do (trừ khi đơn đã hủy = 5)
        $stmt = mysqli_prepare($this->con, 'UPDATE Donhang SET TrangThai = ? WHERE MaHD = ? AND TrangThai != 5');
        if (!$stmt) return false;
        
        $mahd = (int)$mahd;
        mysqli_stmt_bind_param($stmt, 'ii', $trangthai, $mahd);
        
        // Chạy câu lệnh update và trả về true nếu thành công (kể cả chọn lại trạng thái cũ)
        return mysqli_stmt_execute($stmt);
    }
}
?>