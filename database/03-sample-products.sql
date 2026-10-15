SET NAMES utf8mb4;
ALTER TABLE danhmuc CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE nhacungcap CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE sanpham CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Cập nhật danh mục chuẩn Tiếng Việt UTF-8 (thêm N phía trước chuỗi tiếng Việt)
INSERT INTO danhmuc (MaDM, MaCode, TenDM, MoTa) VALUES
(1, 'DM01', N'Thực phẩm tươi sống', N'Rau củ, trái cây, thịt cá tươi sống'),
(2, 'DM02', N'Đồ uống & Giải khát', N'Nước ngọt, nước suối, sữa, trà, cà phê'),
(3, 'DM03', N'Bánh kẹo & Ăn vặt', N'Bánh quy, kẹo, snack các loại'),
(4, 'DM04', N'Gia vị & Nấu nướng', N'Muối, đường, nước mắm, dầu ăn, gạo'),
(5, 'DM05', N'Đồ dùng gia đình', N'Chổi, cây lau nhà, bát đĩa, chất tẩy rửa')
ON DUPLICATE KEY UPDATE 
    MaCode = VALUES(MaCode),
    TenDM = VALUES(TenDM),
    MoTa = VALUES(MoTa);

-- Cập nhật nhà cung cấp chuẩn
INSERT INTO nhacungcap (MaNCC, MaCode, TenNCC, DienThoai, DiaChi) VALUES
(1, 'NCC01', N'Công ty Cổ phần Sữa Việt Nam (Vinamilk)', '02854155555', N'Số 10 Tân Trào, P. Tân Phú, Quận 7, TP.HCM'),
(2, 'NCC02', N'Tập đoàn Masan Consumer', '02862563862', N'Tầng 12 Central Plaza, 17 Lê Duẩn, Quận 1, TP.HCM'),
(3, 'NCC03', N'Công ty TNHH Quốc tế Unilever Việt Nam', '02854135686', N'Khu Công nghiệp Tây Bắc Củ Chi, TP.HCM'),
(4, 'NCC04', N'Công ty CP Acecook Việt Nam', '02838154064', N'Lô II-3, Đường số 11, KCN Tân Bình, TP.HCM'),
(5, 'NCC05', N'Công ty TNHH Nestlé Việt Nam', '02839113737', N'Lầu 5 Empress Tower, 138-142 Hai Bà Trưng, Quận 1, TP.HCM')
ON DUPLICATE KEY UPDATE 
    MaCode = VALUES(MaCode),
    TenNCC = VALUES(TenNCC),
    DienThoai = VALUES(DienThoai),
    DiaChi = VALUES(DiaChi);

-- Cập nhật chuẩn hóa 10 sản phẩm ban đầu
INSERT INTO sanpham (MaSP, TenSP, MaDM, MaNCC, GiaNhap, GiaBan, SoLuongTon, DonViTinh, HinhAnh, HanSuDung) VALUES
('SP001', N'Sữa tươi tiệt trùng Vinamilk 100% 1L', 2, 1, 28000, 35000, 120, N'Hộp', 'vinamilk.jpg', '2027-01-15'),
('SP002', N'Nước mắm Nam Ngư Đệ Nhị 500ml', 4, 2, 19000, 25000, 200, N'Chai', 'nuocmam.jpg', '2027-06-20'),
('SP003', N'Mì tôm Hảo Hảo tôm chua cay (Lốc 5 gói)', 3, 4, 16000, 20000, 300, N'Lốc', 'haohao.jpg', '2026-12-31'),
('SP004', N'Sữa đậu nành Fami nguyên chất 200ml (Lốc 6)', 2, 1, 23000, 29000, 150, N'Lốc', 'fami.jpg', '2026-11-20'),
('SP005', N'Dầu thực vật Tường An Cooking Oil 1L', 4, 2, 34000, 42000, 100, N'Chai', 'tuongan.jpg', '2027-08-10'),
('SP006', N'Nước tinh khiết Aquafina 500ml (Lốc 6 chai)', 2, 2, 18000, 24000, 250, N'Lốc', 'aquafina.jpg', '2028-01-01'),
('SP007', N'Bánh quy kẹp kem sôcôla Oreo 133g', 3, 5, 17000, 22000, 80, N'Gói', 'oreo.jpg', '2027-05-15'),
('SP008', N'Bột giặt OMO Comfort Hương Tinh Dầu 4.5kg', 5, 3, 115000, 145000, 60, N'Túi', 'omo.jpg', '2028-12-31'),
('SP009', N'Xúc xích tiệt trùng Vissan dinh dưỡng (Gói 5)', 1, 2, 25000, 32000, 180, N'Gói', 'vissan.jpg', '2026-10-30'),
('SP010', N'Trà xanh Không Độ vị Chanh 500ml', 2, 2, 9000, 12000, 400, N'Chai', 'khongdo.jpg', '2027-03-25')
ON DUPLICATE KEY UPDATE 
    TenSP = VALUES(TenSP),
    MaDM = VALUES(MaDM),
    MaNCC = VALUES(MaNCC),
    GiaNhap = VALUES(GiaNhap),
    GiaBan = VALUES(GiaBan),
    SoLuongTon = VALUES(SoLuongTon),
    DonViTinh = VALUES(DonViTinh),
    HinhAnh = VALUES(HinhAnh),
    HanSuDung = VALUES(HanSuDung);

-- Thêm 12 sản phẩm mới đa dạng, đẹp mắt (thêm N phía trước chuỗi tiếng Việt)
INSERT INTO sanpham (MaSP, TenSP, MaDM, MaNCC, GiaNhap, GiaBan, SoLuongTon, DonViTinh, HinhAnh, HanSuDung) VALUES
('SP011', N'Sữa chua men sống Probi Vinamilk 130ml (Lốc 4)', 2, 1, 22000, 28000, 150, N'Lốc', 'probi.jpg', '2026-12-15'),
('SP012', N'Trà Ô long TEA+ Plus 455ml thơm ngon', 2, 2, 8500, 12000, 220, N'Chai', 'tra_olong.jpg', '2027-07-20'),
('SP013', N'Bột giặt Ariel Khử mùi ẩm mốc Chuyên sâu 3.5kg', 5, 3, 155000, 195000, 75, N'Túi', 'ariel.jpg', '2028-06-30'),
('SP014', N'Nước rửa chén Sunlight Thiên Nhiên Trà Xanh 750g', 5, 3, 25000, 32000, 130, N'Chai', 'sunlight.jpg', '2028-09-15'),
('SP015', N'Mì trộn Omachi Xốt bò hầm Phô mai (Lốc 5)', 3, 2, 38000, 48000, 160, N'Lốc', 'omachi.jpg', '2027-04-10'),
('SP016', N'Gạo sạch đặc sản ST25 Thượng hạng (Túi 5kg)', 4, 2, 165000, 215000, 95, N'Túi', 'gao_st25.jpg', '2027-11-30'),
('SP017', N'Dầu ăn thượng hạng Neptune Gold 2L', 4, 2, 95000, 125000, 110, N'Chai', 'neptune.jpg', '2028-02-15'),
('SP018', N'Bánh Chocopie Orion Đậm vị Sô-cô-la (Hộp 12 cái)', 3, 5, 45000, 58000, 140, N'Hộp', 'chocopie.jpg', '2027-09-05'),
('SP019', N'Bộ cây lau nhà tự vắt thông minh 360 độ Inox', 5, 3, 130000, 189000, 45, N'Bộ', 'caylaunha.png', '2030-01-01'),
('SP020', N'Bộ bát ăn cơm sứ tráng men hoa sen cao cấp (6 chiếc)', 5, 3, 75000, 115000, 60, N'Hộp', 'bat.jpg', '2030-01-01'),
('SP021', N'Chổi quét nhà sợi cước mềm quét bụi cán Inox bền đẹp', 5, 3, 28000, 45000, 85, N'Cây', 'choiquetnha.png', '2030-01-01'),
('SP022', N'Cà phê sữa đá Trung Nguyên G7 3in1 (Hộp 18 gói)', 2, 2, 43000, 56000, 175, N'Hộp', 'cafe_g7.jpg', '2027-12-25')
ON DUPLICATE KEY UPDATE 
    TenSP = VALUES(TenSP),
    MaDM = VALUES(MaDM),
    MaNCC = VALUES(MaNCC),
    GiaNhap = VALUES(GiaNhap),
    GiaBan = VALUES(GiaBan),
    SoLuongTon = VALUES(SoLuongTon),
    DonViTinh = VALUES(DonViTinh),
    HinhAnh = VALUES(HinhAnh),
    HanSuDung = VALUES(HanSuDung);
