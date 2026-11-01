<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    
    .page-head { font-family: 'Outfit', sans-serif; margin-top: 10px; }
    .page-head h1 { font-size: 34px; font-weight: 800; color: #1a1a1a; letter-spacing: -0.5px; }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 99px;
        font-weight: 700;
        font-size: 14px;
        background: #f1f5f9;
        color: #475569;
    }
    .status-1 { background: #fef9c3; color: #ca8a04; } /* Chờ xác nhận */
    .status-2 { background: #dbeafe; color: #2563eb; } /* Chờ lấy hàng */
    .status-3 { background: #e0e7ff; color: #4f46e5; } /* Đang giao */
    .status-4 { background: #dcfce7; color: #16a34a; } /* Hoàn thành */
    .status-5 { background: #fee2e2; color: #dc2626; } /* Đã hủy */

    .panel {
        font-family: 'Outfit', sans-serif;
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        border-radius: 24px !important;
        padding: 30px !important;
        margin-bottom: 30px !important;
        overflow: hidden;
    }
    
    .panel h2 {
        font-size: 22px;
        font-weight: 800;
        color: #1e293b;
        margin-top: 0;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 15px;
    }

    .cart-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .cart-table th {
        background: #f8fafc; color: #475569; font-size: 14px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1px; padding: 16px; border-bottom: none;
    }
    .cart-table th:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .cart-table th:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
    .cart-table td { padding: 20px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 16px; }

    .summary-line { font-size: 18px; color: #475569; margin-top: 15px; display: flex; justify-content: space-between; }
    .summary-total {
        font-size: 26px; color: #e31d2b; margin-top: 20px; padding-top: 20px;
        border-top: 2px dashed #cbd5e1; font-weight: 800;
    }
    
    .delivery-info {
        background: #f8fafc;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }
    .delivery-info p { margin: 0; font-size: 16px; line-height: 1.6; color: #334155; }
    .delivery-info strong { color: #0f172a; font-size: 18px; }

    .button {
        font-family: 'Outfit', sans-serif;
        border-radius: 14px !important; font-weight: 700 !important;
        padding: 14px 24px !important; transition: all 0.3s !important;
    }
    .button.secondary { background: #f1f5f9 !important; color: #475569 !important; }
    .button.secondary:hover { background: #e2e8f0 !important; transform: translateY(-2px); }
    .button.danger { background: #fee2e2 !important; color: #ef4444 !important; }
    .button.danger:hover { background: #fecaca !important; transform: translateY(-2px); }
    .order-confirm-actions { display:flex; justify-content:flex-end; gap:12px; flex-wrap:wrap; margin: 18px 0 40px; }
    .button.success { background:#16a34a !important; color:#fff !important; }
    .button.success:hover { background:#15803d !important; transform:translateY(-2px); }
    .order-review-panel>p{color:#b5c0c9;margin:-8px 0 20px}
    .order-review-card{padding:20px;margin-top:16px;border:1px solid #35404a;border-radius:14px;background:#141a1f}
    .order-review-card h3{font-size:18px;margin:0 0 6px;color:#f4f6f8}
    .order-review-card p{margin:0 0 12px;color:#aeb9c3}
    .order-review-card label{display:block;margin:12px 0 6px;color:#dbe1e5;font-weight:700}
    .order-review-card select{max-width:260px}
    .order-review-card textarea{min-height:105px;resize:vertical}
    .order-review-card .button{margin-top:14px}
    .order-review-card .review-stars{color:#e6c13f;font-size:21px;letter-spacing:2px}
    .order-review-card .review-comment{white-space:pre-wrap;overflow-wrap:anywhere;color:#e2e7eb}
    .order-review-notice{padding:12px 15px;border:1px solid #6c5a1d;border-radius:10px;background:#302b1c;color:#f2d878;margin:0 0 16px}
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<?php 
$order=$data['order'];
$orderItems=$order['Items'];
$customerReviews=$data['customerReviews'] ?? [];
$escapeReview=static function($value){return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');};
$reviewStars=static function($score){$score=max(0,min(5,(int)$score));return str_repeat('★',$score).str_repeat('☆',5-$score);};
$states=[
    1=>['Chờ xác nhận', 'fa-clock', 'status-1'],
    2=>['Chờ lấy hàng', 'fa-box', 'status-2'],
    3=>['Đang giao', 'fa-truck', 'status-3'],
    4=>['Hoàn thành', 'fa-check-circle', 'status-4'],
    5=>['Đã hủy', 'fa-times-circle', 'status-5']
]; 
$statusInfo = $states[(int)$order['TrangThai']] ?? ['Đang xử lý', 'fa-spinner', 'status-1'];
?>

<div class="page-head">
    <div>
        <h1>Chi tiết Đơn hàng #<?php echo (int)$order['MaHD']; ?></h1>
        <div id="customer-order-status" data-status="<?php echo (int)$order['TrangThai']; ?>" style="margin-top: 15px;">
            <span class="status-badge <?php echo $statusInfo[2]; ?>">
                <i class="fas <?php echo $statusInfo[1]; ?>"></i> <?php echo $statusInfo[0]; ?>
            </span>
        </div>
    </div>
    <a class="button secondary" href="store.php?url=CustomerOrders/Get_data"><i class="fas fa-list-ul"></i> Về Danh sách</a>
</div>

<div class="panel">
    <h2><i class="fas fa-shopping-bag" style="color:#e31d2b;"></i> Sản phẩm đã đặt</h2>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orderItems as $item) { ?>
                <tr>
                    <td style="font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($item['TenSP'] ?? $item['MaSP'],ENT_QUOTES,'UTF-8'); ?></td>
                    <td><span style="background: #f1f5f9; padding: 4px 12px; border-radius: 8px; font-weight: 700;"><?php echo (int)$item['SoLuong']; ?></span></td>
                    <td style="color: #64748b;"><?php echo number_format((float)$item['DonGia'],0,',','.'); ?> &#8363;</td>
                    <td><strong style="color: #e31d2b; font-size: 18px;"><?php echo number_format((float)$item['DonGia']*(int)$item['SoLuong'],0,',','.'); ?> &#8363;</strong></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <div style="max-width: 400px; margin-left: auto; margin-top: 30px; background: #f8fafc; padding: 20px; border-radius: 16px;">
        <div class="summary-line">
            <span>Mã giảm giá</span>
            <strong style="color: #059669;">- <?php echo number_format((float)$order['GiamGia'],0,',','.'); ?> &#8363;</strong>
        </div>
        <div class="summary-line summary-total">
            <span style="color: #1e293b;">Tổng thanh toán</span>
            <span><?php echo number_format((float)$order['TongTien'],0,',','.'); ?> &#8363;</span>
        </div>
    </div>
</div>

<div id="order-review-section">
    <?php if ((int)$order['TrangThai']===4) { ?>
    <section class="panel order-review-panel" id="danh-gia-san-pham" aria-labelledby="order-review-title">
        <h2 id="order-review-title"><i class="fas fa-star" style="color:#e6c13f;"></i> Đánh giá sản phẩm đã mua</h2>
        <p>Đơn hàng đã hoàn thành. Mỗi sản phẩm chỉ được đánh giá một lần.</p>
        <?php if (!empty($data['reviewNotice'])) { ?><div class="order-review-notice" role="status"><?php echo $escapeReview($data['reviewNotice']); ?></div><?php } ?>
        <?php foreach ($orderItems as $item) {
            $itemId=(string)$item['MaSP'];
            $existing=$customerReviews[$itemId] ?? null;
        ?>
        <article class="order-review-card">
            <h3><?php echo $escapeReview($item['TenSP'] ?? $itemId); ?></h3>
            <?php if ($existing) { ?>
            <p>Bạn đã đánh giá sản phẩm này.</p>
            <div class="review-stars" aria-label="<?php echo (int)$existing['SoSao']; ?> trên 5 sao"><?php echo $reviewStars($existing['SoSao']); ?></div>
            <p class="review-comment"><?php echo $escapeReview($existing['NoiDung']); ?></p>
            <?php } else { ?>
            <p>Chia sẻ trải nghiệm của bạn về sản phẩm này.</p>
            <form method="post" action="store.php?url=CustomerOrders/DanhGia/<?php echo (int)$order['MaHD']; ?>/<?php echo rawurlencode($itemId); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $escapeReview($_SESSION['shop_csrf']); ?>">
                <label>Số sao
                    <select name="stars" required>
                        <option value="">Chọn số sao</option>
                        <?php for ($value=5;$value>=1;$value--) { ?>
                        <option value="<?php echo $value; ?>"><?php echo $reviewStars($value); ?> — <?php echo $value; ?> sao</option>
                        <?php } ?>
                    </select>
                </label>
                <label>Nhận xét
                    <textarea name="comment" maxlength="1000" required placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
                </label>
                <button class="button" type="submit">Lưu đánh giá</button>
            </form>
            <?php } ?>
        </article>
        <?php } ?>
    </section>
    <?php } ?>
</div>

<div class="panel">
    <h2><i class="fas fa-map-marker-alt" style="color:#e31d2b;"></i> Thông tin giao hàng</h2>
    <div class="delivery-info">
        <p><strong><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($order['TenNguoiNhan'] ?? '',ENT_QUOTES,'UTF-8'); ?></strong></p>
        <p style="margin-top: 8px;"><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($order['DienThoaiNhan'] ?? '',ENT_QUOTES,'UTF-8'); ?></p>
        <p style="margin-top: 8px;"><i class="fas fa-home"></i> <?php echo htmlspecialchars($order['DiaChiGiaoHang'] ?? '',ENT_QUOTES,'UTF-8'); ?></p>
    </div>
</div>

<div id="customer-order-actions">
    <?php if((int)$order['TrangThai']===1 || (int)$order['TrangThai']===3) { ?>
    <div class="order-confirm-actions">
        <?php if((int)$order['TrangThai']===3) { ?>
            <form method="post" action="store.php?url=CustomerOrders/Received/<?php echo (int)$order['MaHD']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
                <button class="button success" type="submit" onclick="return confirm('Xác nhận bạn đã nhận đủ hàng?');"><i class="fas fa-check-circle"></i> Đã nhận được hàng</button>
            </form>
        <?php } ?>
        <?php if((int)$order['TrangThai']===1) { ?>
        <form method="post" action="store.php?url=CustomerOrders/Cancel/<?php echo (int)$order['MaHD']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
            <button class="button danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');"><i class="fas fa-ban"></i> Hủy đơn hàng</button>
        </form>
        <?php } ?>
    </div>
    <?php } ?>
</div>

<script>
(() => {
    let refreshing = false;
    window.setInterval(async () => {
        if (document.hidden || refreshing) return;
        refreshing = true;
        try {
            const response = await fetch(window.location.href, {
                cache: 'no-store',
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) return;
            const html = await response.text();
            const nextDocument = new DOMParser().parseFromString(html, 'text/html');
            const currentStatus = document.getElementById('customer-order-status');
            const nextStatus = nextDocument.getElementById('customer-order-status');
            const currentActions = document.getElementById('customer-order-actions');
            const nextActions = nextDocument.getElementById('customer-order-actions');
            const currentReviews = document.getElementById('order-review-section');
            const nextReviews = nextDocument.getElementById('order-review-section');
            if (currentStatus && nextStatus && currentActions && nextActions && currentStatus.dataset.status !== nextStatus.dataset.status) {
                currentStatus.replaceWith(nextStatus.cloneNode(true));
                currentActions.replaceWith(nextActions.cloneNode(true));
                if (currentReviews && nextReviews) currentReviews.replaceWith(nextReviews.cloneNode(true));
            }
        } catch (error) {
            // Keep the current order view if a background status refresh fails.
        } finally {
            refreshing = false;
        }
    }, 10000);
})();
</script>
