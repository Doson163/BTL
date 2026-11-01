<?php
class ProductReviewModel extends connectDB {
    private static $schemaReady = false;

    public function __construct() {
        parent::__construct();
        if (self::$schemaReady) return;

        // Compose init scripts only run for a new volume. This also creates the
        // table on an existing DailyMart database without resetting its data.
        $schema = file_get_contents(__DIR__ . '/../../database/07-product-reviews.sql');
        if (!is_string($schema) || !mysqli_query($this->con, $schema)) {
            throw new RuntimeException('Không thể khởi tạo bảng đánh giá sản phẩm.');
        }
        self::$schemaReady = true;
    }

    public function GetSummary($productId) {
        $stmt = mysqli_prepare($this->con, 'SELECT COUNT(*) AS total, COALESCE(AVG(SoSao), 0) AS average FROM danhgiasanpham WHERE MaSP = ?');
        if (!$stmt) return ['total' => 0, 'average' => 0];
        mysqli_stmt_bind_param($stmt, 's', $productId);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $row ?: ['total' => 0, 'average' => 0];
    }

    public function GetReviews($productId, $page, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = mysqli_prepare($this->con, 'SELECT SoSao, NoiDung, NgayCapNhat FROM danhgiasanpham WHERE MaSP = ? ORDER BY NgayCapNhat DESC, MaDG DESC LIMIT ? OFFSET ?');
        if (!$stmt) return [];
        mysqli_stmt_bind_param($stmt, 'sii', $productId, $perPage, $offset);
        mysqli_stmt_execute($stmt);
        $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $rows;
    }

    public function GetCustomerReview($productId, $customerId) {
        $stmt = mysqli_prepare($this->con, 'SELECT SoSao, NoiDung FROM danhgiasanpham WHERE MaSP = ? AND MaKH = ? LIMIT 1');
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, 'ss', $productId, $customerId);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $row ?: null;
    }

    public function GetAdminSummary($fromDate, $toDate) {
        $stmt = mysqli_prepare($this->con, 'SELECT COUNT(*) AS total, COALESCE(AVG(SoSao), 0) AS average FROM danhgiasanpham WHERE NgayTao >= ? AND NgayTao < DATE_ADD(?, INTERVAL 1 DAY)');
        if (!$stmt) return ['total' => 0, 'average' => 0];
        mysqli_stmt_bind_param($stmt, 'ss', $fromDate, $toDate);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $row ?: ['total' => 0, 'average' => 0];
    }

    public function GetAdminReviews($fromDate, $toDate, $limit, $offset) {
        $sql = 'SELECT r.SoSao, r.NoiDung, r.NgayTao, r.MaSP,
                       COALESCE(s.TenSP, r.MaSP) AS TenSP,
                       COALESCE(NULLIF(k.TenKH, \'\'), r.MaKH) AS TenKH
                FROM danhgiasanpham r
                LEFT JOIN sanpham s ON s.MaSP = r.MaSP
                LEFT JOIN khachhang k ON k.MaKH = r.MaKH
                WHERE r.NgayTao >= ? AND r.NgayTao < DATE_ADD(?, INTERVAL 1 DAY)
                ORDER BY r.NgayTao DESC, r.MaDG DESC
                LIMIT ? OFFSET ?';
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return [];
        mysqli_stmt_bind_param($stmt, 'ssii', $fromDate, $toDate, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $rows;
    }

    public function CanReviewOrder($orderId, $productId, $customerId) {
        $stmt = mysqli_prepare($this->con, 'SELECT 1 FROM donhang d INNER JOIN chitietdonhang ct ON ct.MaHD = d.MaHD WHERE d.MaHD = ? AND d.MaKH = ? AND ct.MaSP = ? AND d.TrangThai = 4 LIMIT 1');
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'iss', $orderId, $customerId, $productId);
        mysqli_stmt_execute($stmt);
        $allowed = (bool)mysqli_fetch_row(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $allowed;
    }

    public function SaveForOrder($orderId, $productId, $customerId, $stars, $comment) {
        // Eligibility is checked in the INSERT itself, so a crafted POST
        // cannot write a review for a product the customer has not received.
        $sql = 'INSERT INTO danhgiasanpham (MaSP, MaKH, SoSao, NoiDung)
                SELECT ?, ?, ?, ? FROM DUAL
                WHERE EXISTS (
                    SELECT 1 FROM donhang d
                    INNER JOIN chitietdonhang ct ON ct.MaHD = d.MaHD
                    WHERE d.MaHD = ? AND d.MaKH = ? AND ct.MaSP = ? AND d.TrangThai = 4
                )';
        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'ssisiss', $productId, $customerId, $stars, $comment, $orderId, $customerId, $productId);
        try {
            $executed = mysqli_stmt_execute($stmt);
        } catch (mysqli_sql_exception $exception) {
            // The unique product/customer key blocks a second review, including concurrent requests.
            $executed = false;
        }
        $inserted = $executed && mysqli_stmt_affected_rows($stmt) === 1;
        mysqli_stmt_close($stmt);
        return $inserted;
    }
}
