<?php
class ShoppingAdvisor {
    private function normalize($value) {
        $value = mb_strtolower((string)$value, 'UTF-8');
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        return strtolower($ascii !== false ? $ascii : $value);
    }

    private function parseBudget($text) {
        if (preg_match('/([0-9][0-9., ]*)\s*(?:vnd|dong|d)\b/', $text, $match)) {
            $value = (float)str_replace([',', '.', ' '], '', $match[1]);
            return $value > 0 ? $value : null;
        }
        if (preg_match('/([0-9]+(?:[.,][0-9]+)?)\s*(k|nghin|ngan)\b/', $text, $match)) {
            return (float)str_replace(',', '.', $match[1]) * 1000;
        }
        if (preg_match('/([0-9]+(?:[.,][0-9]+)?)\s*(trieu|tr)\b/', $text, $match)) {
            return (float)str_replace(',', '.', $match[1]) * 1000000;
        }
        if (preg_match('/(?:ngan sach|toi co|toi da|duoi|khoang)\s*([0-9][0-9., ]*)\b/', $text, $match)) {
            $value = (float)str_replace([',', '.', ' '], '', $match[1]);
            return $value > 0 ? $value : null;
        }
        return null;
    }

    private function requestQuantity($text, $position) {
        $prefix = substr($text, 0, $position);
        if (preg_match('/([0-9]+)\s*(?:(?:cai|chiec|bo|cay|hop|goi|chai|kg|gram)\s*)?$/', $prefix, $match)) {
            return max(1, min(99, (int)$match[1]));
        }
        return 1;
    }

    private function findRequests($text, $products) {
        $matches = [];
        foreach ($products as $product) {
            $name = trim($this->normalize($product['TenSP'] ?? ''));
            if (strlen($name) < 2) continue;
            $offset = 0;
            while (($position = strpos($text, $name, $offset)) !== false) {
                $matches[$position][] = ['name' => $name, 'product' => $product];
                $offset = $position + strlen($name);
            }
        }
        if (!$matches) return [];

        ksort($matches);
        $requests = [];
        $coveredUntil = -1;
        foreach ($matches as $position => $atPosition) {
            if ($position < $coveredUntil) continue;
            $longest = max(array_map(static function($item) { return strlen($item['name']); }, $atPosition));
            $chosen = array_values(array_filter($atPosition, static function($item) use ($longest) { return strlen($item['name']) === $longest; }));
            $name = $chosen[0]['name'];
            $exactProducts = [];
            foreach ($products as $product) {
                if ($this->normalize($product['TenSP'] ?? '') === $name) $exactProducts[] = $product;
            }
            if (!$exactProducts) $exactProducts = array_column($chosen, 'product');
            $requests[] = [
                'name' => $name,
                'quantity' => $this->requestQuantity($text, (int)$position),
                'products' => $exactProducts
            ];
            $coveredUntil = (int)$position + $longest;
        }
        return $requests;
    }

    private function optionsForRequest($request, $products) {
        $tokens = array_values(array_filter(explode(' ', $request['name']), static function($token) {
            return strlen($token) > 1 && !in_array($token, ['cai', 'chiec', 'loai'], true);
        }));
        $baseCategory = $request['products'][0]['MaDM'] ?? null;
        $ranked = [];
        foreach ($products as $product) {
            $name = $this->normalize($product['TenSP'] ?? '');
            $score = 0;
            foreach ($tokens as $token) if (strpos($name, $token) !== false) $score++;
            $isExact = $name === $request['name'];
            if ($isExact) $score += 100;
            elseif ($score === 0 || ($baseCategory !== null && (string)$product['MaDM'] !== (string)$baseCategory)) continue;
            $product['_score'] = $score;
            $ranked[] = $product;
        }
        usort($ranked, static function($a, $b) {
            if ($a['_score'] !== $b['_score']) return $b['_score'] <=> $a['_score'];
            return (float)$a['GiaBan'] <=> (float)$b['GiaBan'];
        });
        return array_slice($ranked, 0, 8);
    }

    private function publicItem($product, $quantity) {
        return [
            'id' => (string)$product['MaSP'],
            'name' => (string)$product['TenSP'],
            'category' => (string)($product['TenDM'] ?? 'Sản phẩm'),
            'price' => (float)$product['GiaBan'],
            'stock' => (int)$product['SoLuongTon'],
            'quantity' => (int)$quantity,
            'image' => (string)($product['HinhAnh'] ?? '')
        ];
    }

    public function recommend($message, $products) {
        $text = $this->normalize($message);
        $budget = $this->parseBudget($text);
        $requests = $this->findRequests($text, $products);

        if (!$products) {
            return ['reply' => 'Hiện cửa hàng chưa có sản phẩm còn hàng để tư vấn. Bạn thử lại sau nhé.', 'items' => []];
        }
        if (!$requests) {
            $terms = array_values(array_unique(array_filter(explode(' ', $text), static function($token) {
                return strlen($token) > 2 && !in_array($token, ['toi', 'muon', 'mua', 'cho', 'minh', 'co', 'ngan', 'sach', 'hang', 'san', 'pham', 'loai', 'gia', 'bao', 'nhieu', 'voi', 'va'], true);
            })));
            $matches = [];
            foreach ($products as $product) {
                $name = $this->normalize($product['TenSP'] ?? '');
                $score = 0;
                foreach ($terms as $term) if (strpos($name, $term) !== false) $score++;
                if ($score > 0) { $product['_score'] = $score; $matches[] = $product; }
            }
            usort($matches, static function($a, $b) {
                if ($a['_score'] !== $b['_score']) return $b['_score'] <=> $a['_score'];
                return (float)$a['GiaBan'] <=> (float)$b['GiaBan'];
            });
            $items = [];
            foreach (array_slice($matches ?: $products, 0, 4) as $product) $items[] = $this->publicItem($product, 1);
            return ['reply' => $budget === null
                ? 'Bạn muốn tìm sản phẩm nào và dự kiến ngân sách bao nhiêu? Ví dụ: “Mình có 200.000đ, cần 1 cây lau nhà và 3 cái bát”.'
                : 'Mình chưa nhận ra tên sản phẩm cụ thể trong câu hỏi. Đây là một vài sản phẩm đang còn hàng; bạn cho mình biết thêm tên món cần mua nhé.', 'items' => $items];
        }

        $lines = [];
        foreach ($requests as $request) {
            $options = $this->optionsForRequest($request, $products);
            if (!$options) continue;
            $request['options'] = $options;
            $lines[] = $request;
        }
        if (!$lines) return ['reply' => 'Mình chưa tìm được sản phẩm phù hợp đang còn hàng. Bạn thử tên sản phẩm khác nhé.', 'items' => []];

        if ($budget === null) {
            $items = [];
            foreach ($lines as $line) $items[] = $this->publicItem($line['options'][0], min($line['quantity'], (int)$line['options'][0]['SoLuongTon']));
            return ['reply' => 'Mình đã tìm thấy các sản phẩm bạn nhắc tới. Bạn cho mình biết ngân sách để tính tổ hợp phù hợp nhé.', 'items' => $items];
        }

        $minimum = 0;
        $stockShortage = false;
        foreach ($lines as $line) {
            $available = (int)$line['options'][0]['SoLuongTon'];
            if ($available < $line['quantity']) $stockShortage = true;
            $minimum += (float)$line['options'][0]['GiaBan'] * min($line['quantity'], $available);
        }
        $selected = [];
        $remaining = $budget;
        foreach ($lines as $line) {
            $product = $line['options'][0];
            $unitPrice = (float)$product['GiaBan'];
            $wanted = min($line['quantity'], (int)$product['SoLuongTon']);
            if ($unitPrice <= 0 || $remaining < $unitPrice) continue;
            $quantity = min($wanted, (int)floor($remaining / $unitPrice));
            $selected[] = $this->publicItem($product, $quantity);
            $remaining -= $quantity * $unitPrice;
        }
        $total = $budget - $remaining;
        $formattedBudget = number_format($budget, 0, ',', '.') . 'đ';
        $formattedTotal = number_format($total, 0, ',', '.') . 'đ';
        if ($minimum <= $budget) {
            $reply = 'Các món bạn yêu cầu có thể nằm trong ngân sách ' . $formattedBudget . '. Phương án tiết kiệm mình tìm được có tổng khoảng ' . $formattedTotal . ', còn dư ' . number_format($remaining, 0, ',', '.') . 'đ.';
        } else {
            $reply = 'Nếu mua đủ số lượng bạn nêu, phương án rẻ nhất hiện khoảng ' . number_format($minimum, 0, ',', '.') . 'đ, vượt ngân sách ' . number_format($minimum - $budget, 0, ',', '.') . 'đ. Mình đã chọn số lượng vừa ngân sách gần nhất bên dưới; có thể cần giảm số lượng hoặc chọn món khác.';
        }
        if ($stockShortage) $reply .= ' Lưu ý: tồn kho hiện tại không đủ cho toàn bộ số lượng bạn yêu cầu.';
        if (!$selected) $reply = 'Ngân sách ' . $formattedBudget . ' hiện chưa đủ mua món rẻ nhất trong danh sách bạn yêu cầu. Bạn có thể tăng ngân sách hoặc cho mình biết sản phẩm thay thế muốn tìm.';
        return ['reply' => $reply, 'items' => $selected];
    }
}
