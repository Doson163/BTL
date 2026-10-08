<?php
class KhuyenmaiModel extends connectDB {
    
    // 1. Lấy danh sách (Có tìm kiếm theo Tên Mã)
    public function DanhSach($keyword = "") {
        $sql = "SELECT * FROM Khuyenmai";
        if($keyword != "") {
            $sql .= " WHERE TenMa LIKE '%$keyword%'";
        }
        $sql .= " ORDER BY MaKM DESC"; 
        return mysqli_query($this->con, $sql);
    }

    // 2. Thêm mới
    public function ThemMoi($ten, $tien, $soluong, $trangthai = 1) {
        $sql = "INSERT INTO Khuyenmai (TenMa, SoTienGiam, SoLuong, TrangThai) 
                VALUES ('$ten', '$tien', '$soluong', '$trangthai')";
        return mysqli_query($this->con, $sql);
    }

    // 3. Cập nhật (Sửa)
    public function Sua($id, $ten, $tien, $soluong, $trangthai = 1) {
        $sql = "UPDATE Khuyenmai SET TenMa='$ten', SoTienGiam='$tien', SoLuong='$soluong', TrangThai='$trangthai' WHERE MaKM='$id'";
        return mysqli_query($this->con, $sql);
    }

    // 4. Lấy thông tin 1 mã (Để đổ vào form sửa)
    public function GetByID($id) {
        return mysqli_query($this->con, "SELECT * FROM Khuyenmai WHERE MaKM='$id'");
    }

    // 5. Xóa (Giữ nguyên)
    public function XoaMa($id) {
        return mysqli_query($this->con, "DELETE FROM Khuyenmai WHERE MaKM='$id'");
    }

    // 6. Check trùng tên mã (Tránh tạo 2 mã giống nhau)
    public function CheckTrung($ten, $exceptId = 0) {
        $stmt = mysqli_prepare($this->con, 'SELECT MaKM FROM Khuyenmai WHERE TenMa = ? AND MaKM <> ?');
        if (!$stmt) return true;
        $exceptId = (int)$exceptId;
        mysqli_stmt_bind_param($stmt, 'si', $ten, $exceptId);
        if (!mysqli_stmt_execute($stmt)) return true;
        return mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
    }

    public function GetActiveCodes() {
        // Chỉ lấy những mã còn số lượng > 0
        $sql = "SELECT * FROM Khuyenmai WHERE TrangThai = 1 AND SoLuong > 0 ORDER BY SoTienGiam DESC";
        return mysqli_query($this->con, $sql);
    }

    // --- BỔ SUNG: CÁC HÀM CẦN THIẾT CHO BÁN HÀNG (Nếu chưa có) ---
    // Hàm kiểm tra mã (Dùng khi bấm Áp dụng)
    public function CheckCode($code) {
        $stmt = mysqli_prepare($this->con, 'SELECT * FROM Khuyenmai WHERE TenMa = ? AND TrangThai = 1 AND SoLuong > 0');
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 's', $code);
        if (!mysqli_stmt_execute($stmt)) return false;
        return mysqli_stmt_get_result($stmt);
    }

    // Hàm trừ số lượng khi thanh toán thành công
    public function TruSoLuong($makm) {
        $stmt = mysqli_prepare($this->con, 'UPDATE Khuyenmai SET SoLuong = SoLuong - 1 WHERE MaKM = ? AND TrangThai = 1 AND SoLuong > 0');
        if (!$stmt) return false;
        $makm = (int)$makm;
        mysqli_stmt_bind_param($stmt, 'i', $makm);
        return mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) === 1;
    }
}
?>
