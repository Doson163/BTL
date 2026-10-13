<?php
class DonhangModel extends connectDB {

    // 1. Lấy danh sách đơn hàng (CÓ TÌM KIẾM)
    public function DanhSach($keyword = "") {
        // Kết nối bảng donhang với khachhang và nhanvien để lấy tên
        $sql = "SELECT donhang.*, khachhang.TenKH, nhanvien.HoTen
                FROM donhang
                LEFT JOIN khachhang ON donhang.MaKH = khachhang.MaKH
                LEFT JOIN nhanvien ON donhang.MaNV = nhanvien.MaNV";

        // Nếu có từ khóa tìm kiếm thì nối thêm điều kiện WHERE
        if($keyword != "") {
            $sql .= " WHERE donhang.MaHD LIKE '%$keyword%' OR khachhang.TenKH LIKE '%$keyword%'";
        }

        // MaHD tự tăng theo thời điểm tạo; không để NgayLap cũ/lệch giờ ghim đơn lên đầu.
        $sql .= " ORDER BY donhang.MaHD DESC";
        return mysqli_query($this->con, $sql);
    }

    // 2. Lấy thông tin chi tiết của 1 đơn hàng (để xem món gì bên trong)
    public function ChiTiet($mahd) {
        $sql = "SELECT chitietdonhang.*, sanpham.TenSP, sanpham.HinhAnh
                FROM chitietdonhang
                LEFT JOIN sanpham ON chitietdonhang.MaSP = sanpham.MaSP
                WHERE MaHD = '$mahd'";
        return mysqli_query($this->con, $sql);
    }

    // 3. Lấy thông tin 1 đơn hàng cụ thể (để in hóa đơn)
// File: DonhangModel.php

public function GetDonhangByID($mahd) {
    // Thêm khachhang.DiaChi vào danh sách cột cần lấy (SELECT)
    $sql = "SELECT donhang.*, khachhang.TenKH, khachhang.DienThoai, khachhang.DiaChi, nhanvien.HoTen, phuongthucthanhtoan.TenPT AS TenPhuongThuc
            FROM donhang
            LEFT JOIN khachhang ON donhang.MaKH = khachhang.MaKH
            LEFT JOIN nhanvien ON donhang.MaNV = nhanvien.MaNV
            LEFT JOIN phuongthucthanhtoan ON donhang.MaPT = phuongthucthanhtoan.MaPT
            WHERE MaHD = '$mahd'";
    return mysqli_query($this->con, $sql);
}

    public function HuyDon($mahd) {
        mysqli_begin_transaction($this->con);
        try {
            $statusStmt = mysqli_prepare($this->con, 'SELECT TrangThai FROM donhang WHERE MaHD = ? FOR UPDATE');
            $detailStmt = mysqli_prepare($this->con, 'SELECT MaSP, SoLuong FROM chitietdonhang WHERE MaHD = ?');
            $stockStmt = mysqli_prepare($this->con, 'UPDATE sanpham SET SoLuongTon = SoLuongTon + ? WHERE MaSP = ?');
            $cancelStmt = mysqli_prepare($this->con, 'UPDATE donhang SET TrangThai = 5 WHERE MaHD = ? AND TrangThai IN (1, 2)');
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
        if (!in_array($trangthai, [2, 3], true)) return false;

        // Admin chuyển đơn đến chờ lấy hàng hoặc đang giao; khách xác nhận hoàn thành.
        $stmt = mysqli_prepare($this->con, 'UPDATE donhang SET TrangThai = ? WHERE MaHD = ? AND TrangThai IN (1, 2, 3)');
        if (!$stmt) return false;

        $mahd = (int)$mahd;
        mysqli_stmt_bind_param($stmt, 'ii', $trangthai, $mahd);

        // Chạy câu lệnh update và trả về true nếu thành công (kể cả chọn lại trạng thái cũ)
        return mysqli_stmt_execute($stmt);
    }
}
?>
