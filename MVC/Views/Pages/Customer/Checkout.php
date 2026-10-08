<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    
    .page-head { font-family: 'Outfit', sans-serif; margin-top: 10px; }
    .page-head h1 { font-size: 34px; font-weight: 800; color: #1a1a1a; letter-spacing: -0.5px; }
    .page-head .muted { font-size: 16px; color: #6b7280; margin-top: 5px; }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
        align-items: start;
        font-family: 'Outfit', sans-serif;
    }

    .panel {
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        border-radius: 24px !important;
        padding: 30px !important;
        margin-bottom: 30px !important;
    }
    
    .panel h2 {
        font-size: 22px; font-weight: 800; color: #1e293b;
        margin-top: 0; margin-bottom: 24px;
        display: flex; align-items: center; gap: 10px;
        border-bottom: 2px solid #f1f5f9; padding-bottom: 15px;
    }

    label { font-weight: 600; color: #475569; font-size: 15px; margin-bottom: 8px; display: block; }
    input[type="text"], input[type="number"], textarea, select, input:not([type="radio"]):not([type="checkbox"]) {
        font-family: 'Outfit', sans-serif;
        border-radius: 12px !important; border: 2px solid #e2e8f0 !important;
        padding: 14px 16px !important; font-size: 16px !important;
        transition: all 0.3s; background: #f8fafc !important; color: #1e293b;
        width: 100%; box-sizing: border-box; margin-bottom: 20px;
    }
    input:focus, textarea:focus {
        border-color: #e31d2b !important; background: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(227, 29, 43, 0.1) !important; outline: none;
    }

    .payment-option {
        display: flex; align-items: center; gap: 12px;
        padding: 16px 20px; border: 2px solid #e2e8f0;
        border-radius: 16px; margin-bottom: 15px;
        cursor: pointer; transition: all 0.2s; background: #f8fafc;
        font-weight: 600; color: #1e293b;
    }
    .payment-option:hover { border-color: #cbd5e1; background: #f1f5f9; }
    .payment-option:has(input:checked) {
        border-color: #e31d2b; background: #fff1f2; color: #e31d2b;
    }
    .payment-option input[type="radio"] { width: 20px; height: 20px; accent-color: #e31d2b; margin: 0; }

    .summary-line { font-size: 16px; color: #475569; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; }
    .summary-line span { max-width: 70%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .summary-total {
        font-size: 24px; color: #e31d2b; margin-top: 20px; padding-top: 20px;
        border-top: 2px dashed #cbd5e1; font-weight: 800;
    }

    .button {
        font-family: 'Outfit', sans-serif;
        border-radius: 14px !important; font-weight: 700 !important;
        padding: 16px 24px !important; transition: all 0.3s !important;
    }
    .button.secondary { background: #f1f5f9 !important; color: #475569 !important; padding: 12px 20px !important; }
    .button.secondary:hover { background: #e2e8f0 !important; transform: translateY(-2px); }
    
    .btn-place-order {
        background: linear-gradient(135deg, #e31d2b 0%, #ff4b2b 100%) !important;
        color: white !important; font-size: 18px !important;
        width: 100%; display: flex; justify-content: center; align-items: center; gap: 10px;
        box-shadow: 0 8px 20px rgba(227, 29, 43, 0.3) !important; margin-top: 10px;
    }
    .btn-place-order:hover:not(:disabled) { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(227, 29, 43, 0.4) !important; }
    .btn-place-order:disabled { background: #cbd5e1 !important; box-shadow: none !important; cursor: not-allowed; }

    @media (max-width: 768px) { .checkout-grid { grid-template-columns: 1fr; } }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="page-head">
    <div>
        <h1>Thanh toán <i class="fas fa-credit-card" style="color: #e31d2b; margin-left: 10px;"></i></h1>
        <div class="muted">Chỉ còn một bước nữa để hoàn tất đơn hàng của bạn.</div>
    </div>
    <a class="button secondary" href="store.php?url=Cart/Get_data"><i class="fas fa-arrow-left"></i> Quay lại giỏ hàng</a>
</div>

<div class="checkout-grid">
    <section class="panel">
        <h2><i class="fas fa-map-marker-alt" style="color: #e31d2b;"></i> Thông tin giao hàng</h2>
        <form method="post" action="store.php?url=CustomerOrders/Place">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
            
            <label for="recipient">Họ và tên người nhận</label>
            <input id="recipient" name="recipient" required placeholder="Nhập tên người nhận hàng" value="<?php echo htmlspecialchars($data['profile']['TenNguoiNhan'] ?: $data['profile']['TenKH'],ENT_QUOTES,'UTF-8'); ?>">
            
            <label for="phone">Số điện thoại liên hệ</label>
            <input id="phone" name="phone" required placeholder="Nhập số điện thoại" value="<?php echo htmlspecialchars($data['profile']['DienThoaiNhan'] ?: $data['profile']['DienThoai'],ENT_QUOTES,'UTF-8'); ?>">
            
            <label for="address">Địa chỉ nhận hàng (Chi tiết)</label>
            <textarea id="address" name="address" rows="3" required placeholder="Số nhà, đường, phường/xã, quận/huyện..."><?php echo htmlspecialchars($data['profile']['DiaChi'] ?? '',ENT_QUOTES,'UTF-8'); ?></textarea>
            
            <h2 style="margin-top: 30px;"><i class="fas fa-ticket-alt" style="color: #e31d2b;"></i> Mã khuyến mãi</h2>
            <label for="promo_code">Nhập mã giảm giá (nếu có)</label>
            <input id="promo_code" name="promo_code" maxlength="50" placeholder="Ví dụ: WINMART100K">
            
            <h2 style="margin-top: 30px;"><i class="fas fa-wallet" style="color: #e31d2b;"></i> Phương thức thanh toán</h2>
            <?php if (empty($data['methods'])) { ?>
                <div style="padding: 16px; background: #fee2e2; color: #dc2626; border-radius: 12px; font-weight: 600;"><i class="fas fa-exclamation-triangle"></i> Cửa hàng tạm thời không hỗ trợ thanh toán.</div>
            <?php } else { 
                foreach($data['methods'] as $index=>$method) { ?>
                <label class="payment-option">
                    <input type="radio" name="payment" value="<?php echo (int)$method['MaPT']; ?>" <?php echo $index===0?'required checked':''; ?>> 
                    <span><?php echo htmlspecialchars($method['TenPT'],ENT_QUOTES,'UTF-8'); ?></span>
                    <?php if((int)$method['MaPT'] === 1) { ?>
                        <i class="fas fa-money-bill-wave" style="margin-left: auto; color: #059669; font-size: 20px;"></i>
                    <?php } else { ?>
                        <i class="fas fa-university" style="margin-left: auto; color: #2563eb; font-size: 20px;"></i>
                    <?php } ?>
                </label>
            <?php } } ?>
            
            <button class="button btn-place-order" type="submit" <?php echo empty($data['methods'])?'disabled':''; ?>>
                Hoàn tất đặt hàng <i class="fas fa-check-circle"></i>
            </button>
        </form>
    </section>
    
    <aside class="panel" style="position: sticky; top: 100px;">
        <h2><i class="fas fa-box-open" style="color: #e31d2b;"></i> Đơn hàng của bạn</h2>
        <div style="background: #f8fafc; border-radius: 16px; padding: 20px; border: 1px solid #e2e8f0;">
            <?php foreach($data['items'] as $item) { ?>
                <div class="summary-line">
                    <span title="<?php echo htmlspecialchars($item['TenSP'],ENT_QUOTES,'UTF-8'); ?>"><strong><?php echo (int)$item['CartQuantity']; ?>x</strong> <?php echo htmlspecialchars($item['TenSP'],ENT_QUOTES,'UTF-8'); ?></span>
                    <strong style="color: #1e293b;"><?php echo number_format((float)$item['LineTotal'],0,',','.'); ?> &#8363;</strong>
                </div>
            <?php } ?>
            
            <div class="summary-line summary-total">
                <span style="color: #1e293b;">Tổng cộng</span>
                <span><?php echo number_format((float)$data['total'],0,',','.'); ?> &#8363;</span>
            </div>
        </div>
        <p style="text-align: center; font-size: 14px; color: #94a3b8; margin-top: 20px;"><i class="fas fa-shield-alt"></i> Thông tin của bạn được bảo mật tuyệt đối.</p>
    </aside>
</div>
