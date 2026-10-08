<?php
class StorefrontModel extends connectDB {
    private function bindValues($stmt, $types, &$values) {
        if ($types === '') return true;
        $args = [$types];
        foreach ($values as $index => &$value) {
            $args[] = &$value;
        }
        return call_user_func_array([$stmt, 'bind_param'], $args);
    }

    public function GetCategories() {
        return mysqli_query($this->con, 'SELECT MaDM, TenDM FROM Danhmuc ORDER BY TenDM');
    }

    public function GetProducts($filters = []) {
        $where = [];
        $types = '';
        $values = [];

        $keyword = trim((string)($filters['q'] ?? ''));
        if ($keyword !== '') {
            $where[] = '(p.TenSP LIKE ? OR p.MaSP LIKE ?)';
            $pattern = '%' . $keyword . '%';
            $types .= 'ss';
            $values[] = $pattern;
            $values[] = $pattern;
        }

        $category = filter_var($filters['category'] ?? '', FILTER_VALIDATE_INT);
        if ($category !== false && $category !== null && $category > 0) {
            $where[] = 'p.MaDM = ?';
            $types .= 'i';
            $values[] = $category;
        }

        foreach (['min_price' => '>=', 'max_price' => '<='] as $key => $operator) {
            $raw = $filters[$key] ?? '';
            if ($raw !== '' && is_numeric($raw) && (float)$raw >= 0) {
                $where[] = 'p.GiaBan ' . $operator . ' ?';
                $types .= 'd';
                $values[] = (float)$raw;
            }
        }

        $sortOptions = [
            'price_asc' => 'p.GiaBan ASC, p.TenSP ASC',
            'price_desc' => 'p.GiaBan DESC, p.TenSP ASC',
            'name' => 'p.TenSP ASC'
        ];
        $sort = $sortOptions[$filters['sort'] ?? ''] ?? 'p.TenSP ASC';
        $sql = 'SELECT p.MaSP, p.TenSP, p.MaDM, p.GiaBan, p.SoLuongTon, p.HinhAnh, p.HanSuDung, d.TenDM
                FROM Sanpham p
                LEFT JOIN Danhmuc d ON d.MaDM = p.MaDM';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY ' . $sort;

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt || !$this->bindValues($stmt, $types, $values) || !mysqli_stmt_execute($stmt)) return false;
        return mysqli_stmt_get_result($stmt);
    }

    public function GetProductById($id) {
        $stmt = mysqli_prepare($this->con, 'SELECT p.MaSP, p.TenSP, p.MaDM, p.GiaBan, p.SoLuongTon, p.HinhAnh, p.HanSuDung, d.TenDM
            FROM Sanpham p LEFT JOIN Danhmuc d ON d.MaDM = p.MaDM WHERE p.MaSP = ? LIMIT 1');
        if (!$stmt) return null;
        $id = (string)$id;
        mysqli_stmt_bind_param($stmt, 's', $id);
        if (!mysqli_stmt_execute($stmt)) return null;
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
    }

    public function GetCartProducts($cart) {
        $items = [];
        foreach ($cart as $id => $quantity) {
            $product = $this->GetProductById($id);
            if (!$product) continue;
            $quantity = max(0, (int)$quantity);
            $product['CartQuantity'] = $quantity;
            $product['LineTotal'] = (int)$product['GiaBan'] * $quantity;
            $items[] = $product;
        }
        return $items;
    }
}
?>