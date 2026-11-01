<?php
$product = $data['product'];
$summary = $data['reviewSummary'];
$reviewCount = (int)$summary['total'];
$average = (float)$summary['average'];
$roundedStars = (int)round($average);
$productId = rawurlencode((string)$product['MaSP']);
$escape = static function ($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); };
$stars = static function ($score) {
    $score = max(0, min(5, (int)$score));
    return str_repeat('★', $score) . str_repeat('☆', 5 - $score);
};
?>
<style>
    .rating-summary{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin:13px 0 20px;color:#cdd5dd}
    .rating-summary .review-stars{font-size:22px}
    .rating-summary a{color:#e6c13f;text-decoration:underline;text-underline-offset:3px}
    .review-stars{color:#e8c342;letter-spacing:2px;white-space:nowrap}
    .reviews-section{margin:42px 0 0;scroll-margin-top:105px}
    .reviews-head{display:flex;align-items:baseline;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:18px}
    .reviews-head h2{margin:0;font-size:24px}
    .reviews-head p{margin:0;color:#aab6c0}
    .review-item,.review-empty{background:#191e24;border:1px solid #303740;border-radius:14px;padding:20px}
    .review-list{display:grid;gap:13px}
    .review-item-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
    .review-item-head strong{color:#f2f4f5}
    .review-item time{color:#99a6b2;font-size:13px}
    .review-item p{color:#e2e7eb;white-space:pre-wrap;overflow-wrap:anywhere;margin:10px 0 0}
    .review-empty{color:#aeb9c3}
    .review-pages{display:flex;align-items:center;justify-content:center;gap:14px;margin-top:18px}
    .review-pages a{color:#e6c13f;text-decoration:underline;text-underline-offset:3px}
    @media(max-width:640px){.reviews-section{margin-top:30px}.review-item,.review-empty{padding:16px}}
</style>

<div class="page-head">
    <a class="muted" href="store.php?url=Storefront/Get_data">&larr; Tiếp tục mua sắm</a>
</div>
<section class="detail">
    <div class="detail-image">
        <?php if (!empty($product['HinhAnh'])) { ?>
            <img src="Public/Images/<?php echo rawurlencode($product['HinhAnh']); ?>" alt="<?php echo $escape($product['TenSP']); ?>">
        <?php } else { ?>
            <span class="muted">Chưa có hình ảnh</span>
        <?php } ?>
    </div>
    <div class="detail-copy">
        <div class="muted"><?php echo $escape($product['TenDM'] ?? 'Chưa phân loại'); ?></div>
        <h1><?php echo $escape($product['TenSP']); ?></h1>
        <div class="muted">Mã sản phẩm: <?php echo $escape($product['MaSP']); ?></div>
        <div class="rating-summary" aria-label="<?php echo $reviewCount ? $escape(number_format($average, 1, ',', '.') . ' trên 5 sao từ ' . $reviewCount . ' đánh giá') : 'Chưa có đánh giá'; ?>">
            <span class="review-stars" aria-hidden="true"><?php echo $stars($roundedStars); ?></span>
            <?php if ($reviewCount) { ?>
                <span><?php echo number_format($average, 1, ',', '.'); ?>/5</span>
                <a href="#danh-gia"><?php echo $reviewCount; ?> đánh giá</a>
            <?php } else { ?>
                <a href="#danh-gia">Chưa có đánh giá</a>
            <?php } ?>
        </div>
        <p class="price"><?php echo number_format((float)$product['GiaBan'], 0, ',', '.'); ?> ₫</p>
        <p><?php echo (int)$product['SoLuongTon'] > 0 ? 'Còn ' . (int)$product['SoLuongTon'] . ' sản phẩm trong kho.' : 'Sản phẩm hiện tạm hết hàng.'; ?></p>
        <?php if (!empty($product['HanSuDung']) && $product['HanSuDung'] !== '0000-00-00') { ?>
            <p>Hạn sử dụng: <?php echo $escape(date('d/m/Y', strtotime($product['HanSuDung']))); ?></p>
        <?php } ?>
        <?php if (!empty($_SESSION['shop_customer']['MaKH'])) { ?>
            <form method="post" action="store.php?url=Cart/Add/<?php echo $productId; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $escape($_SESSION['shop_csrf']); ?>">
                <button class="button" type="submit" <?php echo (int)$product['SoLuongTon'] < 1 ? 'disabled' : ''; ?>>Thêm vào giỏ hàng</button>
            </form>
        <?php } else { ?>
            <a class="button" href="/Baitaplon/User">Đăng nhập để mua</a>
        <?php } ?>
    </div>
</section>

<section class="reviews-section" id="danh-gia" aria-labelledby="reviews-title">
    <div class="reviews-head">
        <h2 id="reviews-title">Đánh giá từ người đã mua</h2>
        <p><?php echo $reviewCount; ?> đánh giá<?php echo $reviewCount ? ' · Trung bình ' . number_format($average, 1, ',', '.') . '/5 sao' : ''; ?></p>
    </div>

    <?php if ($reviewCount) { ?>
        <div class="review-list">
            <?php foreach ($data['reviews'] as $review) { ?>
                <article class="review-item">
                    <div class="review-item-head">
                        <div><strong>Khách đã mua</strong> <span class="review-stars" aria-label="<?php echo (int)$review['SoSao']; ?> trên 5 sao"><?php echo $stars($review['SoSao']); ?></span></div>
                        <time datetime="<?php echo $escape(date('Y-m-d', strtotime($review['NgayCapNhat']))); ?>"><?php echo $escape(date('d/m/Y', strtotime($review['NgayCapNhat']))); ?></time>
                    </div>
                    <p><?php echo $escape($review['NoiDung']); ?></p>
                </article>
            <?php } ?>
        </div>
        <?php if ($data['reviewPages'] > 1) { ?>
            <nav class="review-pages" aria-label="Trang đánh giá">
                <?php if ($data['reviewPage'] > 1) { ?><a href="store.php?url=Storefront/ChiTiet/<?php echo $productId; ?>&amp;review_page=<?php echo $data['reviewPage'] - 1; ?>#danh-gia">&larr; Trang trước</a><?php } ?>
                <span>Trang <?php echo $data['reviewPage']; ?>/<?php echo $data['reviewPages']; ?></span>
                <?php if ($data['reviewPage'] < $data['reviewPages']) { ?><a href="store.php?url=Storefront/ChiTiet/<?php echo $productId; ?>&amp;review_page=<?php echo $data['reviewPage'] + 1; ?>#danh-gia">Trang sau &rarr;</a><?php } ?>
            </nav>
        <?php } ?>
    <?php } else { ?>
        <div class="review-empty">Chưa có đánh giá nào cho sản phẩm này.</div>
    <?php } ?>
</section>
