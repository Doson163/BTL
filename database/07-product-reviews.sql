CREATE TABLE IF NOT EXISTS `danhgiasanpham` (
  `MaDG` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `MaSP` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MaKH` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SoSao` tinyint(3) unsigned NOT NULL,
  `NoiDung` varchar(1000) NOT NULL,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp(),
  `NgayCapNhat` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`MaDG`),
  UNIQUE KEY `uq_danhgia_sanpham_khachhang` (`MaSP`, `MaKH`),
  KEY `idx_danhgia_sanpham_ngay` (`MaSP`, `NgayCapNhat`),
  KEY `idx_danhgia_khachhang` (`MaKH`),
  CONSTRAINT `fk_danhgia_sanpham` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`) ON DELETE CASCADE,
  CONSTRAINT `fk_danhgia_khachhang` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`) ON DELETE CASCADE,
  CONSTRAINT `chk_danhgia_sosao` CHECK (`SoSao` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
