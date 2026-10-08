<?php
class CustomerOrderModel extends connectDB {
    public function GetPaymentMethods() {
        $result = mysqli_query($this->con, 'SELECT MaPT, TenPT FROM Phuongthucthanhtoan WHERE TrangThai = 1 ORDER BY MaPT');
        $methods = [];
        if ($result) while ($row = mysqli_fetch_assoc($result)) {
            $name = mb_strtolower($row['TenPT'], 'UTF-8');
            if (strpos($name, 'cod') === false && strpos($name, 'ti') !== 0) continue;
            $methods[] = $row;
        }
        return $methods;
    }

    public function GetProfile($customerId) {
        $stmt = mysqli_prepare($this->con, 'SELECT k.TenKH, k.DienThoai, k.DiaChi, a.TenNguoiNhan, a.DienThoaiNhan FROM Khachhang k JOIN TaiKhoanKhachHang a ON a.MaKH=k.MaKH WHERE k.MaKH=? LIMIT 1');
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, 's', $customerId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
    }

    public function GetOrderList($customerId) {
        $stmt = mysqli_prepare($this->con, 'SELECT MaHD, NgayLap, TongTien, GiamGia, TrangThai FROM Donhang WHERE MaKH=? ORDER BY NgayLap DESC, MaHD DESC');
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 's', $customerId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function GetOrder($customerId, $orderId) {
        $stmt = mysqli_prepare($this->con, 'SELECT MaHD, MaKH, NgayLap, TongTien, GiamGia, TrangThai, TenNguoiNhan, DienThoaiNhan, DiaChiGiaoHang FROM Donhang WHERE MaKH=? AND MaHD=? LIMIT 1');
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, 'si', $customerId, $orderId);
        mysqli_stmt_execute($stmt);
        $order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        if (!$order) return null;
        $detail = mysqli_prepare($this->con, 'SELECT c.MaSP, c.SoLuong, c.DonGia, s.TenSP FROM ChitietDonhang c LEFT JOIN Sanpham s ON s.MaSP=c.MaSP WHERE c.MaHD=?');
        mysqli_stmt_bind_param($detail, 'i', $orderId);
        mysqli_stmt_execute($detail);
        $order['Items'] = mysqli_stmt_get_result($detail);
        return $order;
    }

    public function CreateOrder($customerId, $recipient, $phone, $address, $paymentId, $cart, $promoCode = "") {
        if (!$cart || !$recipient || !$phone || !$address) return false;
        mysqli_begin_transaction($this->con);
        try {
            $method = mysqli_prepare($this->con, 'SELECT MaPT, TenPT FROM Phuongthucthanhtoan WHERE MaPT=? AND TrangThai=1 LIMIT 1');
            mysqli_stmt_bind_param($method, 'i', $paymentId);
            mysqli_stmt_execute($method);
            $payment = mysqli_fetch_assoc(mysqli_stmt_get_result($method));
            if (!$payment || (stripos($payment['TenPT'], 'cod') === false && strpos(mb_strtolower($payment['TenPT'],'UTF-8'),'ti') !== 0)) throw new Exception('Unsupported payment method.');

            $profileStmt = mysqli_prepare($this->con, 'SELECT MaKH FROM Khachhang WHERE MaKH=? LIMIT 1');
            mysqli_stmt_bind_param($profileStmt, 's', $customerId);
            mysqli_stmt_execute($profileStmt);
            if (!mysqli_fetch_assoc(mysqli_stmt_get_result($profileStmt))) throw new Exception('Tai khoan khach hang khong ton tai.');

            $stockStmt = mysqli_prepare($this->con, 'SELECT TenSP, GiaBan, SoLuongTon FROM Sanpham WHERE MaSP=? FOR UPDATE');
            $detailStmt = mysqli_prepare($this->con, 'INSERT INTO ChitietDonhang (MaHD, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)');
            $updateStmt = mysqli_prepare($this->con, 'UPDATE Sanpham SET SoLuongTon=SoLuongTon-? WHERE MaSP=? AND SoLuongTon>=?');
            $items = [];
            $total = 0;
            foreach ($cart as $productId => $qty) {
                $qty = filter_var($qty, FILTER_VALIDATE_INT);
                if ($qty === false || $qty < 1) throw new Exception('So luong san pham khong hop le.');
                $productId = (string)$productId;
                mysqli_stmt_bind_param($stockStmt, 's', $productId);
                mysqli_stmt_execute($stockStmt);
                $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stockStmt));
                if (!$product || (int)$product['SoLuongTon'] < $qty) throw new Exception('San pham het hang hoac khong du ton kho.');
                $price = (int)$product['GiaBan'];
                $total += $price * $qty;
                $items[] = ['id' => $productId, 'qty' => $qty, 'price' => $price];
            }
            $discount=0; $promoId=null;
            if ($promoCode !== '') {
                $promoStmt=mysqli_prepare($this->con,'SELECT MaKM, SoTienGiam FROM Khuyenmai WHERE TenMa=? AND TrangThai=1 AND SoLuong>0 LIMIT 1 FOR UPDATE');
                mysqli_stmt_bind_param($promoStmt,'s',$promoCode); mysqli_stmt_execute($promoStmt); $promo=mysqli_fetch_assoc(mysqli_stmt_get_result($promoStmt));
                if (!$promo) throw new Exception('Invalid promotion code.');
                $promoId=(int)$promo['MaKM']; $discount=min($total,max(0,(int)$promo['SoTienGiam']));
                $promoUpdate=mysqli_prepare($this->con,'UPDATE Khuyenmai SET SoLuong=SoLuong-1 WHERE MaKM=? AND TrangThai=1 AND SoLuong>0');
                mysqli_stmt_bind_param($promoUpdate,'i',$promoId); if(!mysqli_stmt_execute($promoUpdate)||mysqli_stmt_affected_rows($promoUpdate)!==1) throw new Exception('Promotion code is exhausted.');
            }
            $total=max(0,$total-$discount); $now=date('Y-m-d H:i:s');
            $order=mysqli_prepare($this->con,'INSERT INTO Donhang (MaKH, MaNV, NgayLap, TongTien, TrangThai, GiamGia, MaPT, TenNguoiNhan, DienThoaiNhan, DiaChiGiaoHang, MaKM) VALUES (?, NULL, ?, ?, 1, ?, ?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($order,'ssiiisssi',$customerId,$now,$total,$discount,$paymentId,$recipient,$phone,$address,$promoId);
            if (!mysqli_stmt_execute($order)) throw new Exception('Khong the tao don hang.');
            $orderId = mysqli_insert_id($this->con);
            foreach ($items as $item) {
                mysqli_stmt_bind_param($detailStmt, 'isii', $orderId, $item['id'], $item['qty'], $item['price']);
                if (!mysqli_stmt_execute($detailStmt)) throw new Exception('Khong the luu chi tiet don hang.');
                mysqli_stmt_bind_param($updateStmt, 'isi', $item['qty'], $item['id'], $item['qty']);
                if (!mysqli_stmt_execute($updateStmt) || mysqli_stmt_affected_rows($updateStmt) !== 1) throw new Exception('Ton kho vua thay doi.');
            }
            mysqli_commit($this->con);
            return $orderId;
        } catch (Throwable $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }

    public function CancelOrder($customerId, $orderId) {
        mysqli_begin_transaction($this->con);
        try {
            $order = mysqli_prepare($this->con, 'SELECT TrangThai FROM Donhang WHERE MaKH=? AND MaHD=? FOR UPDATE');
            mysqli_stmt_bind_param($order, 'si', $customerId, $orderId);
            mysqli_stmt_execute($order);
            $row = mysqli_fetch_assoc(mysqli_stmt_get_result($order));
            if (!$row || (int)$row['TrangThai'] !== 1) throw new Exception('Chi don hang cho xac nhan moi duoc huy.');
            $lines = mysqli_prepare($this->con, 'SELECT MaSP, SoLuong FROM ChitietDonhang WHERE MaHD=?');
            mysqli_stmt_bind_param($lines, 'i', $orderId);
            mysqli_stmt_execute($lines);
            $result = mysqli_stmt_get_result($lines);
            $restore = mysqli_prepare($this->con, 'UPDATE Sanpham SET SoLuongTon=SoLuongTon+? WHERE MaSP=?');
            while ($line = mysqli_fetch_assoc($result)) {
                mysqli_stmt_bind_param($restore, 'is', $line['SoLuong'], $line['MaSP']);
                if (!mysqli_stmt_execute($restore)) throw new Exception('Khong the hoan ton kho.');
            }
            $update = mysqli_prepare($this->con, 'UPDATE Donhang SET TrangThai=5 WHERE MaKH=? AND MaHD=? AND TrangThai=1');
            mysqli_stmt_bind_param($update, 'si', $customerId, $orderId);
            if (!mysqli_stmt_execute($update) || mysqli_stmt_affected_rows($update) !== 1) throw new Exception('Don hang vua duoc cap nhat.');
            mysqli_commit($this->con);
            return true;
        } catch (Throwable $e) {
            mysqli_rollback($this->con);
            return false;
        }
    }
}
?>

