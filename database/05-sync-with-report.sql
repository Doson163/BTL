SET NAMES utf8mb4;

-- 1. Bổ sung Nhân viên (thêm tiền tố N cho chuỗi tiếng Việt)
ALTER TABLE nhanvien CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
INSERT INTO nhanvien (MaNV, HoTen, Email, ChucVu) VALUES
('NV01', N'Trần Anh Vũ', 'anhvu.tran@winmart.vn', N'Cửa hàng trưởng'),
('NV02', N'Nguyễn Hồng Mạnh', 'hongmanh.nguyen@winmart.vn', N'Quản lý kho'),
('NV03', N'Nguyễn Phúc Ánh', 'phucanh.nguyen@winmart.vn', N'Nhân viên thu ngân'),
('NV04', N'Đỗ Khắc Sơn', 'khacson.do@winmart.vn', N'Nhân viên kiểm kê')
ON DUPLICATE KEY UPDATE
    HoTen = VALUES(HoTen),
    Email = VALUES(Email),
    ChucVu = VALUES(ChucVu);

-- 2. Bổ sung Tài khoản quản trị & nhân viên vào CSDL (bảng taikhoan)
ALTER TABLE taikhoan CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
INSERT INTO taikhoan (id, username, password, hoten, role) VALUES
(1, 'admin', 'Admin@123456', N'Quản trị viên hệ thống', 1),
(2, 'nv_kho', '123456', N'Nguyễn Hồng Mạnh', 0),
(3, 'nv_thungan', '123456', N'Nguyễn Phúc Ánh', 0)
ON DUPLICATE KEY UPDATE
    username = VALUES(username),
    password = VALUES(password),
    hoten = VALUES(hoten),
    role = VALUES(role);

-- 3. Bổ sung Khách hàng mẫu (bảng khachhang)
ALTER TABLE khachhang CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
INSERT INTO khachhang (MaKH, TenKH, DienThoai, DiaChi, DiemTichLuy) VALUES
('KH001', N'Khách vãng lai (Tại quầy)', '0900000000', N'Mua tại cửa hàng', 0),
('KH002', N'Lê Thị Thu Thảo', '0912345678', N'Số 12 Chùa Bộc, Đống Đa, Hà Nội', 150),
('KH003', N'Phạm Hoàng Nam', '0987654321', N'Số 45 Triều Khúc, Thanh Xuân, Hà Nội', 320),
('KH004', N'Hoàng Minh Tuấn', '0978112233', N'Khu đô thị Văn Quán, Hà Đông, Hà Nội', 80)
ON DUPLICATE KEY UPDATE
    TenKH = VALUES(TenKH),
    DienThoai = VALUES(DienThoai),
    DiaChi = VALUES(DiaChi),
    DiemTichLuy = VALUES(DiemTichLuy);

-- 4. Bổ sung chính xác các mã Khuyến mãi dùng trong Test Case của Báo cáo
INSERT INTO khuyenmai (TenMa, SoTienGiam, SoLuong, TrangThai) VALUES
('SALE50K', 50000, 100, 1),
('HETHAN', 30000, 0, 0),
('GIAM20K', 20000, 80, 1)
ON DUPLICATE KEY UPDATE
    SoTienGiam = VALUES(SoTienGiam),
    SoLuong = VALUES(SoLuong),
    TrangThai = VALUES(TrangThai);

-- 5. Bổ sung đầy đủ các Phương thức thanh toán theo đúng đặc tả Báo cáo (Bảng 3.22)
ALTER TABLE phuongthucthanhtoan CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
INSERT INTO phuongthucthanhtoan (MaPT, TenPT, TrangThai, HinhAnh) VALUES
(1, N'Thanh toán khi nhận hàng (COD)', 1, ''),
(2, N'Ví điện tử MoMo (Quét mã QR)', 1, 'QRmomo.jpg'),
(3, N'Chuyển khoản Ngân hàng / VNPay', 1, 'QRmb.jpg')
ON DUPLICATE KEY UPDATE
    TenPT = VALUES(TenPT),
    TrangThai = VALUES(TrangThai),
    HinhAnh = VALUES(HinhAnh);

-- 6. Tạo một số đơn hàng mẫu trong 7 ngày gần nhất để biểu đồ Dashboard hiển thị đẹp mắt
INSERT INTO donhang (MaHD, MaKH, MaNV, NgayLap, TongTien, TrangThai, GiamGia, MaPT, TenNguoiNhan, DienThoaiNhan, DiaChiGiaoHang, MaKM) VALUES
(1001, 'KH002', 'NV03', NOW() - INTERVAL 5 DAY, 145000, 4, 20000, 1, N'Lê Thị Thu Thảo', '0912345678', N'Số 12 Chùa Bộc, Đống Đa, Hà Nội', 1),
(1002, 'KH003', 'NV03', NOW() - INTERVAL 4 DAY, 320000, 4, 50000, 2, N'Phạm Hoàng Nam', '0987654321', N'Số 45 Triều Khúc, Thanh Xuân, Hà Nội', 2),
(1003, 'KH004', 'NV03', NOW() - INTERVAL 3 DAY, 215000, 4, 0, 1, N'Hoàng Minh Tuấn', '0978112233', N'Khu đô thị Văn Quán, Hà Đông, Hà Nội', NULL),
(1004, 'KH002', 'NV03', NOW() - INTERVAL 2 DAY, 189000, 3, 15000, 3, N'Lê Thị Thu Thảo', '0912345678', N'Số 12 Chùa Bộc, Đống Đa, Hà Nội', 3),
(1005, 'KH003', 'NV03', NOW() - INTERVAL 1 DAY, 115000, 2, 0, 1, N'Phạm Hoàng Nam', '0987654321', N'Số 45 Triều Khúc, Thanh Xuân, Hà Nội', NULL),
(1006, 'KH004', 'NV03', NOW(), 285000, 1, 0, 1, N'Hoàng Minh Tuấn', '0978112233', N'Khu đô thị Văn Quán, Hà Đông, Hà Nội', NULL)
ON DUPLICATE KEY UPDATE
    TongTien = VALUES(TongTien),
    TrangThai = VALUES(TrangThai);

-- Chi tiết đơn hàng mẫu tương ứng
INSERT INTO chitietdonhang (MaHD, MaSP, SoLuong, DonGia) VALUES
(1001, 'SP001', 2, 35000),
(1001, 'SP003', 4, 20000),
(1002, 'SP013', 1, 195000),
(1002, 'SP017', 1, 125000),
(1003, 'SP016', 1, 215000),
(1004, 'SP019', 1, 189000),
(1005, 'SP020', 1, 115000),
(1006, 'SP018', 2, 58000),
(1006, 'SP022', 3, 56000)
ON DUPLICATE KEY UPDATE
    SoLuong = VALUES(SoLuong),
    DonGia = VALUES(DonGia);
