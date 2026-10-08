<?php
class SanphamModel extends connectDB {

    // 1. Lấy danh sách (Giữ nguyên)
    public function GetSanpham($keyword = "") {
        $sql = "SELECT Sanpham.*, Danhmuc.TenDM, Nhacungcap.TenNCC 
                FROM Sanpham 
                LEFT JOIN Danhmuc ON Sanpham.MaDM = Danhmuc.MaDM
                LEFT JOIN Nhacungcap ON Sanpham.MaNCC = Nhacungcap.MaNCC";
        
        if($keyword != "") {
            $sql .= " WHERE Sanpham.TenSP LIKE '%$keyword%' OR Sanpham.MaSP LIKE '%$keyword%'";
        }
        
        $sql .= " ORDER BY MaSP DESC";
        return mysqli_query($this->con, $sql);
    }

    // Check trùng mã (Giữ nguyên)
    public function CheckTrungMa($masp) {
        $sql = "SELECT * FROM Sanpham WHERE MaSP = '$masp'";
        $kq = mysqli_query($this->con, $sql);
        return mysqli_num_rows($kq) > 0;
    }

    // 2. THÊM MỚI (CẬP NHẬT: Thêm $hsd)
    public function InsertSanpham($masp, $tensp, $madm, $mancc, $gia, $soluong, $hinhanh, $hsd) {
        // Xử lý nếu ngày rỗng thì để NULL
        $hsdValue = empty($hsd) ? "NULL" : "'$hsd'";
        
        $sql = "INSERT INTO Sanpham (MaSP, TenSP, MaDM, MaNCC, GiaBan, SoLuongTon, HinhAnh, HanSuDung) 
                VALUES ('$masp', '$tensp', '$madm', '$mancc', '$gia', '$soluong', '$hinhanh', $hsdValue)";
        return mysqli_query($this->con, $sql);
    }

    // Xóa (Giữ nguyên)
    public function CoDonHang($masp) {
        $stmt = mysqli_prepare($this->con, 'SELECT COUNT(*) AS SoLuong FROM ChitietDonhang WHERE MaSP = ?');
        if (!$stmt) return true;
        $masp = (string)$masp;
        mysqli_stmt_bind_param($stmt, 's', $masp);
        if (!mysqli_stmt_execute($stmt)) return true;
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        return !$row || (int)$row['SoLuong'] > 0;
    }

    public function DeleteSanpham($masp) {
        $stmt = mysqli_prepare($this->con, 'DELETE FROM Sanpham WHERE MaSP = ?');
        if (!$stmt) return false;
        $masp = (string)$masp;
        mysqli_stmt_bind_param($stmt, 's', $masp);
        return mysqli_stmt_execute($stmt);
    }

    // Lấy theo ID (Giữ nguyên)
    public function GetSanphamByID($masp) {
        $sql = "SELECT * FROM Sanpham WHERE MaSP='$masp'";
        return mysqli_query($this->con, $sql);
    }

    // 3. CẬP NHẬT (SỬA: Thêm $hsd)
    public function UpdateSanpham($masp, $tensp, $madm, $mancc, $gia, $soluong, $hinhanh, $hsd) {
        // Xử lý ngày
        $hsdValue = empty($hsd) ? "NULL" : "'$hsd'";

        if ($hinhanh == "") {
            $sql = "UPDATE Sanpham SET TenSP='$tensp', MaDM='$madm', MaNCC='$mancc', GiaBan='$gia',  HanSuDung=$hsdValue 
                    WHERE MaSP='$masp'";
        } else {
            $sql = "UPDATE Sanpham SET TenSP='$tensp', MaDM='$madm', MaNCC='$mancc', GiaBan='$gia',  HinhAnh='$hinhanh', HanSuDung=$hsdValue 
                    WHERE MaSP='$masp'";
        }
        return mysqli_query($this->con, $sql);
    }
}
?>
