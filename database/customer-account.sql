-- Customer login credentials and default delivery recipient.
-- Customer name, phone and address remain in the existing Khachhang table.
CREATE TABLE IF NOT EXISTS TaiKhoanKhachHang (
    MaKH VARCHAR(50) NOT NULL,
    Email VARCHAR(190) NULL,
    DienThoai VARCHAR(20) NOT NULL,
    MatKhauHash VARCHAR(255) NOT NULL,
    TenNguoiNhan VARCHAR(150) NULL,
    DienThoaiNhan VARCHAR(20) NULL,
    NgayTao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (MaKH),
    UNIQUE KEY uq_taikhoankhach_email (Email),
    UNIQUE KEY uq_taikhoankhach_phone (DienThoai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;