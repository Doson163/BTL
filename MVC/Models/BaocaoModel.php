<?php
class BaocaoModel extends connectDB {

    // 1. Top sản phẩm bán chạy (Đã sửa MaDH -> MaHD)
    public function GetTopBanChay($tungay, $denngay) {
        $sql = "SELECT Sanpham.TenSP, 
                       SUM(ChitietDonhang.SoLuong) as SoLuongBan, 
                       -- Tính doanh số = Số lượng * Đơn giá (vì không có cột ThanhTien)
                       SUM(ChitietDonhang.SoLuong * ChitietDonhang.DonGia) as DoanhSo
                FROM ChitietDonhang
                -- SỬA Ở ĐÂY: Dùng MaHD cho cả 2 bảng
                JOIN Donhang ON ChitietDonhang.MaHD = Donhang.MaHD
                JOIN Sanpham ON ChitietDonhang.MaSP = Sanpham.MaSP
                WHERE Donhang.TrangThai <> 5 AND Donhang.NgayLap BETWEEN '$tungay 00:00:00' AND '$denngay 23:59:59'
                GROUP BY ChitietDonhang.MaSP
                ORDER BY SoLuongBan DESC 
                LIMIT 10"; 
        return mysqli_query($this->con, $sql);
    }

}
?>
