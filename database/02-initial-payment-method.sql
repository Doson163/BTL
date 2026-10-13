INSERT INTO Phuongthucthanhtoan (MaPT, TenPT, TrangThai, HinhAnh)
SELECT 1, 'Thanh toán khi nhận hàng (COD)', 1, ''
WHERE NOT EXISTS (
    SELECT 1 FROM Phuongthucthanhtoan WHERE TenPT = 'Thanh toán khi nhận hàng (COD)'
);
