<?php
class DanhmucModel extends connectDB {
    
    // 1. Lấy danh sách (Tìm kiếm theo Tên hoặc Mã)
    public function DanhSach($keyword = "") {
        $sql = "SELECT * FROM Danhmuc";
        if($keyword != "") {
            $sql .= " WHERE TenDM LIKE '%$keyword%' OR MaCode LIKE '%$keyword%'";
        }
        return mysqli_query($this->con, $sql);
    }

    // 2. Thêm mới (Có Mã Code)
    public function Them($macode, $ten, $mota) {
        $sql = "INSERT INTO Danhmuc (MaCode, TenDM, MoTa) VALUES ('$macode', '$ten', '$mota')";
        return mysqli_query($this->con, $sql);
    }

    // 3. Cập nhật (Có Mã Code)
    public function Sua($id, $macode, $ten, $mota) {
        $sql = "UPDATE Danhmuc SET MaCode='$macode', TenDM='$ten', MoTa='$mota' WHERE MaDM='$id'";
        return mysqli_query($this->con, $sql);
    }

    // ... (Các hàm Xoa, GetByID giữ nguyên) ...
    public function CoSanPham($id) {
        $stmt = mysqli_prepare($this->con, 'SELECT COUNT(*) AS SoLuong FROM Sanpham WHERE MaDM = ?');
        if (!$stmt) return true;
        $id = (int)$id;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if (!mysqli_stmt_execute($stmt)) return true;
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return !$row || (int)$row['SoLuong'] > 0;
    }

    public function Xoa($id) {
        $stmt = mysqli_prepare($this->con, 'DELETE FROM Danhmuc WHERE MaDM = ?');
        if (!$stmt) return false;
        $id = (int)$id;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return mysqli_stmt_execute($stmt);
    }
    public function GetByID($id) {
        return mysqli_query($this->con, "SELECT * FROM Danhmuc WHERE MaDM='$id'");
    }
    
    // Check trùng mã danh mục (Nếu cần kỹ hơn)
    public function CheckTrungMa($macode, $exceptId = 0) {
        $stmt = mysqli_prepare($this->con, 'SELECT MaDM FROM Danhmuc WHERE MaCode = ? AND MaDM <> ?');
        if (!$stmt) return true;
        $exceptId = (int)$exceptId;
        mysqli_stmt_bind_param($stmt, 'si', $macode, $exceptId);
        if (!mysqli_stmt_execute($stmt)) return true;
        return mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
    }

    public function CheckTrungTen($ten, $exceptId = 0) {
        $stmt = mysqli_prepare($this->con, 'SELECT MaDM FROM Danhmuc WHERE TenDM = ? AND MaDM <> ?');
        if (!$stmt) return true;
        $exceptId = (int)$exceptId;
        mysqli_stmt_bind_param($stmt, 'si', $ten, $exceptId);
        if (!mysqli_stmt_execute($stmt)) return true;
        return mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
    }
}
?>
