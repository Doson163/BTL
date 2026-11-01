-- Apply once to an existing database after backing up these four tables.
-- Product and employee identifiers are strings in sanpham and nhanvien.
ALTER TABLE phieunhap MODIFY MaNV varchar(20) NOT NULL;
ALTER TABLE phieukiem MODIFY MaNV varchar(20) NOT NULL;
ALTER TABLE chitietphieunhap MODIFY MaSP varchar(20) NOT NULL;
ALTER TABLE chitietkiem MODIFY MaSP varchar(20) NOT NULL;

-- Previous integer columns stored codes like '01' as 1. Restore the original
-- product code where its numeric value identifies exactly one product.
UPDATE chitietphieunhap AS detail
JOIN sanpham AS product
  ON product.MaSP REGEXP '^[0-9]+$'
 AND CAST(product.MaSP AS UNSIGNED) = CAST(detail.MaSP AS UNSIGNED)
JOIN (
  SELECT CAST(MaSP AS UNSIGNED) AS numeric_code
  FROM sanpham
  WHERE MaSP REGEXP '^[0-9]+$'
  GROUP BY CAST(MaSP AS UNSIGNED)
  HAVING COUNT(*) = 1
) AS unique_product ON unique_product.numeric_code = CAST(detail.MaSP AS UNSIGNED)
SET detail.MaSP = product.MaSP
WHERE detail.MaSP REGEXP '^[0-9]+$';

UPDATE chitietkiem AS detail
JOIN sanpham AS product
  ON product.MaSP REGEXP '^[0-9]+$'
 AND CAST(product.MaSP AS UNSIGNED) = CAST(detail.MaSP AS UNSIGNED)
JOIN (
  SELECT CAST(MaSP AS UNSIGNED) AS numeric_code
  FROM sanpham
  WHERE MaSP REGEXP '^[0-9]+$'
  GROUP BY CAST(MaSP AS UNSIGNED)
  HAVING COUNT(*) = 1
) AS unique_product ON unique_product.numeric_code = CAST(detail.MaSP AS UNSIGNED)
SET detail.MaSP = product.MaSP
WHERE detail.MaSP REGEXP '^[0-9]+$';
