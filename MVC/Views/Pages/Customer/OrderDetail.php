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
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<?php 
$order=$data['order']; 
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
        <div style="margin-top: 15px;">
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
            <?php while($item=mysqli_fetch_assoc($order['Items'])) { ?>
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

<div class="panel">
    <h2><i class="fas fa-map-marker-alt" style="color:#e31d2b;"></i> Thông tin giao hàng</h2>
    <div class="delivery-info">
        <p><strong><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($order['TenNguoiNhan'] ?? '',ENT_QUOTES,'UTF-8'); ?></strong></p>
        <p style="margin-top: 8px;"><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($order['DienThoaiNhan'] ?? '',ENT_QUOTES,'UTF-8'); ?></p>
        <p style="margin-top: 8px;"><i class="fas fa-home"></i> <?php echo htmlspecialchars($order['DiaChiGiaoHang'] ?? '',ENT_QUOTES,'UTF-8'); ?></p>
    </div>
</div>

<?php if((int)$order['TrangThai']===1) { ?>
    <div style="text-align: right; margin-bottom: 40px;">
        <form method="post" action="store.php?url=CustomerOrders/Cancel/<?php echo (int)$order['MaHD']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
            <button class="button danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');"><i class="fas fa-ban"></i> Hủy đơn hàng</button>
        </form>
    </div>
<?php } ?>
