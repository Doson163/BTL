<?php
class KhohangModel extends connectDB {
    
    
    public function GetAllNCC() { return mysqli_query($this->con, "SELECT * FROM Nhacungcap"); }
    public function GetAllNhanvien() { return mysqli_query($this->con, "SELECT MaNV, HoTen FROM Nhanvien ORDER BY HoTen"); }
    public function GetAllSP() { return mysqli_query($this->con, "SELECT * FROM Sanpham"); }
    public function GetSP($id) { return mysqli_query($this->con, "SELECT * FROM Sanpham WHERE MaSP='$id'"); }
    
    
    public function GetMaSPByTen($ten) {
        $ten = trim($ten);
        $sql = "SELECT MaSP FROM Sanpham WHERE TenSP = '$ten' LIMIT 1";
        $kq = mysqli_query($this->con, $sql);
        if(mysqli_num_rows($kq) > 0) {
            $row = mysqli_fetch_array($kq);
            return $row['MaSP'];
        }
        return null;
    }
    public function GetMaNCCByTen($ten) { /* ... (Giữ nguyên code cũ) ... */ 
        $sql = "SELECT MaNCC FROM Nhacungcap WHERE TenNCC LIKE '%$ten%' LIMIT 1";
        $kq = mysqli_query($this->con, $sql);
        if(mysqli_num_rows($kq) > 0) { $row = mysqli_fetch_array($kq); return $row['MaNCC']; }
        return null;
    }

    
    public function NhapHang($mancc, $manv, $tongtien, $giohang_nhap) {
        if (empty($giohang_nhap) || !is_array($giohang_nhap)) return false;
        mysqli_begin_transaction($this->con);
        try {
            $ngaynhap = date('Y-m-d H:i:s');
            $headerStmt = mysqli_prepare($this->con, 'INSERT INTO Phieunhap (MaNCC, MaNV, NgayNhap, TongTien) VALUES (?, ?, ?, ?)');
            $detailStmt = mysqli_prepare($this->con, 'INSERT INTO ChitietPhieunhap (MaPN, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)');
            $stockStmt = mysqli_prepare($this->con, 'UPDATE Sanpham SET SoLuongTon = SoLuongTon + ? WHERE MaSP = ?');
            if (!$headerStmt || !$detailStmt || !$stockStmt) throw new Exception('Unable to prepare stock receipt.');

            $mancc = (int)$mancc;
            $tongtien = (int)$tongtien;
            mysqli_stmt_bind_param($headerStmt, 'issi', $mancc, $manv, $ngaynhap, $tongtien);
            if (!mysqli_stmt_execute($headerStmt)) throw new Exception('Unable to save stock receipt.');
            $mapn = mysqli_insert_id($this->con);

            foreach ($giohang_nhap as $item) {
                $masp = (string)($item['id'] ?? '');
                $sl = filter_var($item['soluong'] ?? null, FILTER_VALIDATE_INT);
                $gia = filter_var($item['gia'] ?? null, FILTER_VALIDATE_INT);
                if ($masp === '' || $sl === false || $sl < 1 || $gia === false || $gia < 1) throw new Exception('Invalid stock receipt item.');
                mysqli_stmt_bind_param($detailStmt, 'isii', $mapn, $masp, $sl, $gia);
                if (!mysqli_stmt_execute($detailStmt)) throw new Exception('Unable to save stock receipt item.');
                mysqli_stmt_bind_param($stockStmt, 'is', $sl, $masp);
                if (!mysqli_stmt_execute($stockStmt) || mysqli_stmt_affected_rows($stockStmt) !== 1) throw new Exception('Unable to update product stock.');
            }

            mysqli_commit($this->con);
            return true;
        } catch (Throwable $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }

    public function GetLichSuNhap($keyword = "") {
        $searchID = preg_replace('/[^0-9]/', '', $keyword);
        $sql = "SELECT Phieunhap.MaPN, Phieunhap.NgayNhap, Phieunhap.TongTien, Nhacungcap.TenNCC,
                       GROUP_CONCAT(CONCAT('<div style=\"border-bottom:1px dashed #eee; padding:3px 0;\">','<b>', Sanpham.TenSP, '</b>',' - SL: <b style=\"color:blue\">', ChitietPhieunhap.SoLuong, '</b>',' - Giá: ', FORMAT(ChitietPhieunhap.DonGia, 0), 'đ','</div>') SEPARATOR '') as ChiTietNhap
                FROM Phieunhap
                LEFT JOIN Nhacungcap ON Phieunhap.MaNCC = Nhacungcap.MaNCC
                LEFT JOIN ChitietPhieunhap ON Phieunhap.MaPN = ChitietPhieunhap.MaPN
                LEFT JOIN Sanpham ON ChitietPhieunhap.MaSP = Sanpham.MaSP";
        if($keyword != "") $sql .= " WHERE Phieunhap.MaPN LIKE '%$searchID%' OR Nhacungcap.TenNCC LIKE '%$keyword%'";
        $sql .= " GROUP BY Phieunhap.MaPN ORDER BY Phieunhap.NgayNhap DESC";
        return mysqli_query($this->con, $sql);
    }

    public function ImportPhieuLichSuFull($mancc, $manv, $ngay, $tongtien, $chitiet_array) {
        $sql = "INSERT INTO Phieunhap (MaNCC, MaNV, NgayNhap, TongTien) VALUES ('$mancc', '$manv', '$ngay', '$tongtien')";
        if(mysqli_query($this->con, $sql)) {
            $mapn = mysqli_insert_id($this->con);
            foreach($chitiet_array as $item) {
                $masp = $item['masp']; $sl = $item['sl']; $gia = $item['gia'];
                mysqli_query($this->con, "INSERT INTO ChitietPhieunhap VALUES ('$mapn', '$masp', '$sl', '$gia')");

                
                
                mysqli_query($this->con, "UPDATE Sanpham SET SoLuongTon = SoLuongTon + $sl WHERE MaSP = '$masp'");
            }
            return true;
        } return false;
    }

    
    public function LuuKiemKho($manv, $ghichu, $giohang_kiem) {
        if (empty($giohang_kiem) || !is_array($giohang_kiem)) return false;
        mysqli_begin_transaction($this->con);
        try {
            $ngay = date('Y-m-d H:i:s');
            $headerStmt = mysqli_prepare($this->con, 'INSERT INTO phieukiem (MaNV, NgayKiem, GhiChu) VALUES (?, ?, ?)');
            $detailStmt = mysqli_prepare($this->con, 'INSERT INTO chitietkiem (MaPK, MaSP, TonMay, TonThuc, LyDo) VALUES (?, ?, ?, ?, ?)');
            $stockStmt = mysqli_prepare($this->con, 'UPDATE Sanpham SET SoLuongTon = ? WHERE MaSP = ?');
            if (!$headerStmt || !$detailStmt || !$stockStmt) throw new Exception('Unable to prepare stock count.');
            mysqli_stmt_bind_param($headerStmt, 'sss', $manv, $ngay, $ghichu);
            if (!mysqli_stmt_execute($headerStmt)) throw new Exception('Unable to save stock count.');
            $mapk = mysqli_insert_id($this->con);

            foreach ($giohang_kiem as $item) {
                $masp = (string)($item['id'] ?? '');
                $tonMay = filter_var($item['tonmay'] ?? null, FILTER_VALIDATE_INT);
                $tonThuc = filter_var($item['tonthuc'] ?? null, FILTER_VALIDATE_INT);
                $lydo = trim((string)($item['lydo'] ?? ''));
                if ($masp === '' || $tonMay === false || $tonMay < 0 || $tonThuc === false || $tonThuc < 0 || ($tonMay !== $tonThuc && $lydo === '')) throw new Exception('Invalid stock count item.');
                mysqli_stmt_bind_param($detailStmt, 'isiis', $mapk, $masp, $tonMay, $tonThuc, $lydo);
                if (!mysqli_stmt_execute($detailStmt)) throw new Exception('Unable to save stock count item.');
                if ($tonMay !== $tonThuc) {
                    mysqli_stmt_bind_param($stockStmt, 'is', $tonThuc, $masp);
                    if (!mysqli_stmt_execute($stockStmt) || mysqli_stmt_affected_rows($stockStmt) !== 1) throw new Exception('Unable to adjust product stock.');
                }
            }

            mysqli_commit($this->con);
            return true;
        } catch (Throwable $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }

    
    public function GetLichSuKiemKe($keyword = "") {
        $searchID = preg_replace('/[^0-9]/', '', $keyword);
        
        $sql = "SELECT phieukiem.MaPK, phieukiem.NgayKiem, phieukiem.GhiChu, Nhanvien.HoTen,
                       GROUP_CONCAT(
                           CONCAT(
                               '<div style=\"border-bottom:1px dashed #eee; padding:3px 0;\">',
                               '<b>', Sanpham.TenSP, '</b>',
                               ' | Máy: ', chitietkiem.TonMay,
                               ' -> <b style=\"color:blue\">Thực: ', chitietkiem.TonThuc, '</b>',
                               ' <i style=\"color:#777\">(', chitietkiem.LyDo, ')</i>',
                               '</div>'
                           ) 
                       SEPARATOR '') as ChiTietKiem,

                       GROUP_CONCAT(
                       CONCAT(
                           '<div style=\"border-bottom:1px dashed #eee; padding:5px 0; height:25px; overflow:hidden; color:#666;\">',
                           IFNULL(chitietkiem.LyDo, '--'),
                           '</div>'
                       ) 
                        SEPARATOR '') as CotLyDo
                FROM phieukiem
                LEFT JOIN Nhanvien ON phieukiem.MaNV = Nhanvien.MaNV
                LEFT JOIN chitietkiem ON phieukiem.MaPK = chitietkiem.MaPK
                LEFT JOIN Sanpham ON chitietkiem.MaSP = Sanpham.MaSP";
        
        if($keyword != "") {
            $sql .= " WHERE phieukiem.MaPK LIKE '%$searchID%' OR phieukiem.GhiChu LIKE '%$keyword%'";
        }
        
        $sql .= " GROUP BY phieukiem.MaPK ORDER BY phieukiem.NgayKiem DESC";
        return mysqli_query($this->con, $sql);
    }

    
    public function ImportPhieuKiemFull($manv, $ngay, $ghichu, $chitiet_array) {
        $sql = "INSERT INTO phieukiem (MaNV, NgayKiem, GhiChu) VALUES ('$manv', '$ngay', '$ghichu')";
        if(mysqli_query($this->con, $sql)) {
            $mapk = mysqli_insert_id($this->con);
            foreach($chitiet_array as $item) {
                $masp = $item['masp'];
                $tonmay = $item['may'];
                $tonthuc = $item['thuc'];
                $lydo = $item['lydo'];
                mysqli_query($this->con, "INSERT INTO chitietkiem VALUES ('$mapk', '$masp', '$tonmay', '$tonthuc', '$lydo')");
            }
            return true;
        }
        return false;
    }
}
?>
