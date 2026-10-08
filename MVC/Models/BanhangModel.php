<?php
class BanhangModel extends connectDB {
    
    // SỬA: Thêm tham số $keyword
    public function GetAllSanpham($keyword = "") {
        $sql = "SELECT * FROM Sanpham WHERE SoLuongTon > 0";
        if($keyword != "") {
            $sql .= " AND (TenSP LIKE '%$keyword%' OR MaSP LIKE '%$keyword%')";
        }
        return mysqli_query($this->con, $sql);
    }

    // Các hàm khác giữ nguyên
    public function GetAllKhachhang() { return mysqli_query($this->con, "SELECT * FROM Khachhang"); }
    public function GetAllNhanvien() { return mysqli_query($this->con, "SELECT * FROM Nhanvien"); }
    public function GetSanphamByID($id) { return mysqli_query($this->con, "SELECT * FROM Sanpham WHERE MaSP='$id'"); }
    public function GetAllPhuongthuc() { return mysqli_query($this->con, "SELECT * FROM phuongthucthanhtoan WHERE TrangThai = 1"); }
    
    public function TaoDonHang($makh, $manv, $tongtien, $giohang, $tiengiam, $mapt, $makm = null) {
        if (empty($giohang) || !is_array($giohang)) return false;

        mysqli_begin_transaction($this->con);
        try {
            $stockStmt = mysqli_prepare($this->con, "SELECT SoLuongTon, GiaBan FROM Sanpham WHERE MaSP = ? FOR UPDATE");
            $orderStmt = mysqli_prepare($this->con, "INSERT INTO Donhang (MaKH, MaNV, NgayLap, TongTien, GiamGia, MaPT, TrangThai) VALUES (?, ?, ?, ?, ?, ?, 4)");
            $detailStmt = mysqli_prepare($this->con, "INSERT INTO ChitietDonhang (MaHD, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)");
            $updateStmt = mysqli_prepare($this->con, "UPDATE Sanpham SET SoLuongTon = SoLuongTon - ? WHERE MaSP = ? AND SoLuongTon >= ?");
            if (!$stockStmt || !$orderStmt || !$detailStmt || !$updateStmt) throw new Exception('Không thể khởi tạo truy vấn đặt hàng.');

            $normalizedCart = [];
            $grossTotal = 0;
            foreach ($giohang as $item) {
                $masp = (string)($item['id'] ?? '');
                $sl = filter_var($item['soluong'] ?? null, FILTER_VALIDATE_INT);
                if ($masp === '' || $sl === false || $sl < 1) throw new Exception('Số lượng sản phẩm không hợp lệ.');
                mysqli_stmt_bind_param($stockStmt, 's', $masp);
                if (!mysqli_stmt_execute($stockStmt)) throw new Exception('Không thể kiểm tra tồn kho.');
                $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stockStmt));
                if (!$product || (int)$product['SoLuongTon'] < $sl) throw new Exception('Sản phẩm không đủ tồn kho.');
                $price = (int)$product['GiaBan'];
                $grossTotal += $price * $sl;
                $normalizedCart[] = ['id' => $masp, 'soluong' => $sl, 'gia' => $price];
            }

            if ($makm !== null) {
                $promoReadStmt = mysqli_prepare($this->con, 'SELECT SoTienGiam, SoLuong, TrangThai FROM Khuyenmai WHERE MaKM = ? FOR UPDATE');
                if (!$promoReadStmt) throw new Exception('Unable to verify promotion code.');
                mysqli_stmt_bind_param($promoReadStmt, 'i', $makm);
                if (!mysqli_stmt_execute($promoReadStmt)) throw new Exception('Unable to verify promotion code.');
                $promo = mysqli_fetch_assoc(mysqli_stmt_get_result($promoReadStmt));
                if (!$promo || (int)$promo['TrangThai'] !== 1 || (int)$promo['SoLuong'] < 1) throw new Exception('Promotion code has no remaining uses.');
                $tiengiam = (int)$promo['SoTienGiam'];
            } else {
                $tiengiam = 0;
            }
            $tiengiam = min($grossTotal, max(0, (int)$tiengiam));
            $tongtien = max(0, $grossTotal - $tiengiam);
            $ngaylap = date('Y-m-d H:i:s');
            mysqli_stmt_bind_param($orderStmt, 'sssiii', $makh, $manv, $ngaylap, $tongtien, $tiengiam, $mapt);
            if (!mysqli_stmt_execute($orderStmt)) throw new Exception('Không thể lưu đơn hàng.');
            $mahd = mysqli_insert_id($this->con);

            foreach ($normalizedCart as $item) {
                $masp = $item['id']; $sl = $item['soluong']; $gia = $item['gia'];
                mysqli_stmt_bind_param($detailStmt, 'isii', $mahd, $masp, $sl, $gia);
                if (!mysqli_stmt_execute($detailStmt)) throw new Exception('Không thể lưu chi tiết đơn hàng.');
                mysqli_stmt_bind_param($updateStmt, 'isi', $sl, $masp, $sl);
                if (!mysqli_stmt_execute($updateStmt) || mysqli_stmt_affected_rows($updateStmt) !== 1) throw new Exception('Tồn kho vừa thay đổi, vui lòng thử lại.');
            }

            if ($makm !== null) {
                $promoStmt = mysqli_prepare($this->con, 'UPDATE Khuyenmai SET SoLuong = SoLuong - 1 WHERE MaKM = ? AND SoLuong > 0');
                if (!$promoStmt) throw new Exception('Unable to verify promotion code.');
                mysqli_stmt_bind_param($promoStmt, 'i', $makm);
                if (!mysqli_stmt_execute($promoStmt) || mysqli_stmt_affected_rows($promoStmt) !== 1) throw new Exception('Promotion code has no remaining uses.');
            }

            mysqli_commit($this->con);
            return $tongtien;
        } catch (Throwable $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }
}
?>
