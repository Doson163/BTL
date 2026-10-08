<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    
    .page-head { font-family: 'Outfit', sans-serif; margin-top: 10px; }
    .page-head h1 { font-size: 34px; font-weight: 800; color: #1a1a1a; letter-spacing: -0.5px; }
    .page-head .muted { font-size: 16px; color: #6b7280; margin-top: 5px; }

    .panel {
        font-family: 'Outfit', sans-serif;
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.04);
        border-radius: 24px !important;
        padding: 30px !important;
        margin-bottom: 30px !important;
        overflow: hidden;
    }

    .cart-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .cart-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 16px;
        border-bottom: none;
    }
    .cart-table th:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .cart-table th:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
    
    .cart-table td {
        padding: 24px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 16px;
    }
    .cart-table tr:last-child td { border-bottom: none; }

    .cart-product { gap: 20px; }
    .cart-product img {
        width: 80px; height: 80px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        padding: 4px;
    }
    .cart-product a {
        font-weight: 700; color: #1e293b; font-size: 18px;
        transition: color 0.2s;
    }
    .cart-product a:hover { color: #e31d2b; }

    .qty-form { display: flex; gap: 10px; align-items: center; }
    .qty-form input {
        width: 80px; text-align: center; font-weight: 600;
        border-radius: 12px !important; padding: 10px !important;
        border: 2px solid #e2e8f0 !important;
    }
    .qty-form input:focus { border-color: #e31d2b !important; box-shadow: 0 0 0 4px rgba(227, 29, 43, 0.1) !important; }

    .button {
        font-family: 'Outfit', sans-serif;
        border-radius: 14px !important; font-weight: 700 !important;
        padding: 14px 24px !important; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .button.secondary { background: #f1f5f9 !important; color: #475569 !important; }
    .button.secondary:hover { background: #e2e8f0 !important; color: #1e293b !important; transform: translateY(-2px); }
    .button.danger { background: #fee2e2 !important; color: #ef4444 !important; }
    .button.danger:hover { background: #fecaca !important; color: #dc2626 !important; transform: translateY(-2px); }
    
    .cart-summary {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 30px;
        max-width: 420px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    .summary-line { font-size: 18px; margin-bottom: 15px; color: #475569; }
    .summary-total {
        font-size: 26px; color: #e31d2b; margin-top: 20px; padding-top: 20px;
        border-top: 2px dashed #cbd5e1;
    }
    
    .btn-checkout {
        background: linear-gradient(135deg, #e31d2b 0%, #ff4b2b 100%) !important;
        color: white !important; font-size: 18px !important;
        width: 100%; justify-content: center; margin-top: 20px;
        box-shadow: 0 8px 20px rgba(227, 29, 43, 0.3) !important;
    }
    .btn-checkout:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(227, 29, 43, 0.4) !important; }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="page-head">
    <div>
        <h1>Giỏ hàng của bạn <i class="fas fa-shopping-cart" style="color: #e31d2b; margin-left: 10px;"></i></h1>
        <div class="muted">Kiểm tra lại sản phẩm và tiến hành thanh toán nhé.</div>
    </div>
    <a class="button secondary" href="store.php?url=Storefront/Get_data"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
</div>

<?php if(empty($data['items'])) { ?>
    <div class="empty" style="padding: 80px 20px; background: #fff; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); text-align: center;">
        <i class="fas fa-box-open" style="font-size: 64px; color: #cbd5e1; margin-bottom: 20px;"></i>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 24px; color: #1e293b; margin: 0 0 10px;">Giỏ hàng trống!</h3>
        <p style="font-family: 'Outfit', sans-serif; color: #64748b; margin: 0 0 24px;">Bạn chưa chọn sản phẩm nào. Hãy dạo quanh cửa hàng nhé!</p>
        <a class="button" style="background: #e31d2b; color: white;" href="store.php?url=Storefront/Get_data">Mua sắm ngay</a>
    </div>
<?php } else { ?>
    <div class="panel">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['items'] as $item) { ?>
                    <tr>
                        <td>
                            <div class="cart-product">
                                <?php if($item['HinhAnh']) { ?>
                                    <img src="Public/Images/<?php echo rawurlencode($item['HinhAnh']); ?>" alt="">
                                <?php } else { ?>
                                    <div style="width:80px;height:80px;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#cbd5e1;"><i class="fas fa-image fa-2x"></i></div>
                                <?php } ?>
                                <a href="store.php?url=Storefront/ChiTiet/<?php echo rawurlencode($item['MaSP']); ?>"><?php echo htmlspecialchars($item['TenSP'],ENT_QUOTES,'UTF-8'); ?></a>
                            </div>
                        </td>
                        <td style="font-weight:600;color:#64748b;"><?php echo number_format((float)$item['GiaBan'],0,',','.'); ?> &#8363;</td>
                        <td>
                            <form class="qty-form" method="post" action="store.php?url=Cart/Update/<?php echo rawurlencode($item['MaSP']); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
                                <input type="number" name="quantity" min="0" max="<?php echo (int)$item['SoLuongTon']; ?>" value="<?php echo (int)$item['CartQuantity']; ?>">
                                <button class="button secondary" type="submit" style="padding: 10px 16px !important;"><i class="fas fa-sync-alt"></i></button>
                            </form>
                            <small class="muted" style="display:block;margin-top:8px;">Tồn: <?php echo (int)$item['SoLuongTon']; ?> &middot; Nhập 0 để xóa</small>
                        </td>
                        <td><strong style="font-size:20px;color:#e31d2b;"><?php echo number_format((float)$item['LineTotal'],0,',','.'); ?> &#8363;</strong></td>
                        <td>
                            <form method="post" action="store.php?url=Cart/Remove/<?php echo rawurlencode($item['MaSP']); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
                                <button class="button danger" type="submit" style="padding: 12px 16px !important;"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px;">
        <form method="post" action="store.php?url=Cart/Clear">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
            <button class="button danger" type="submit"><i class="fas fa-trash"></i> Xóa toàn bộ giỏ hàng</button>
        </form>

        <div class="cart-summary">
            <div class="summary-line">
                <span><i class="fas fa-receipt"></i> Tạm tính</span>
                <strong><?php echo number_format((float)$data['total'],0,',','.'); ?> &#8363;</strong>
            </div>
            <div class="summary-line summary-total">
                <span style="color:#1e293b;font-size:20px;font-weight:800;">Tổng cộng</span>
                <span style="font-weight:800;"><?php echo number_format((float)$data['total'],0,',','.'); ?> &#8363;</span>
            </div>
            <a class="button btn-checkout" href="store.php?url=CustomerOrders/Checkout">Tiến hành Đặt hàng <i class="fas fa-arrow-right" style="margin-left:8px;"></i></a>
        </div>
    </div>
<?php } ?>
