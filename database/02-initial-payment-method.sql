INSERT INTO phuongthucthanhtoan (MaPT, TenPT, TrangThai, HinhAnh)
SELECT 1, N'Thanh toán khi nhận hàng (COD)', 1, ''
WHERE NOT EXISTS (
    SELECT 1 FROM phuongthucthanhtoan WHERE TenPT = N'Thanh toán khi nhận hàng (COD)'
);
