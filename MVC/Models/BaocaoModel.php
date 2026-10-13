<?php
class BaocaoModel extends connectDB {

    // 1. Top sản phẩm bán chạy (Đã sửa MaDH -> MaHD)
    public function GetTopBanChay($tungay, $denngay) {
        $sql = "SELECT sanpham.TenSP,
                       SUM(chitietdonhang.SoLuong) as SoLuongBan,
                       -- Tính doanh số = Số lượng * Đơn giá (vì không có cột ThanhTien)
                       SUM(chitietdonhang.SoLuong * chitietdonhang.DonGia) as DoanhSo
                FROM chitietdonhang
                -- SỬA Ở ĐÂY: Dùng MaHD cho cả 2 bảng
                JOIN donhang ON chitietdonhang.MaHD = donhang.MaHD
                JOIN sanpham ON chitietdonhang.MaSP = sanpham.MaSP
                WHERE donhang.TrangThai <> 5 AND donhang.NgayLap BETWEEN '$tungay 00:00:00' AND '$denngay 23:59:59'
                GROUP BY chitietdonhang.MaSP
                ORDER BY SoLuongBan DESC
                LIMIT 10";
        return mysqli_query($this->con, $sql);
    }

}
?>
