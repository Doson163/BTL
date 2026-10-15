<style>
    /* CSS Card & Table hiện đại */
    .card-custom {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }
    .card-header-custom {
        padding: 15px 20px;
        background: linear-gradient(to right, #1a237e, #3949ab); /* Xanh đậm sang trọng */
        color: white;
        font-weight: bold;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* --- THANH TÌM KIẾM (Đã sửa lỗi dính nhau) --- */
    .search-box {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #eee;
    }
    .search-form {
        display: flex; 
        align-items: center; /* Căn giữa dọc */
        gap: 15px; /* Khoảng cách giữa các phần tử */
    }
    .input-wrapper {
        flex: 1; 
        position: relative;
    }
    .search-icon {
        position: absolute; 
        left: 15px; 
        top: 50%; 
        transform: translateY(-50%); /* Căn giữa icon */
        color: #999;
    }
    .form-control-search {
        width: 100%;
        height: 42px;
        padding: 0 15px 0 45px; /* Padding trái để chừa chỗ cho icon */
        border: 1px solid #ddd;
        border-radius: 6px;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .form-control-search:focus {
        border-color: #1a237e;
        box-shadow: 0 0 5px rgba(26, 35, 126, 0.2);
    }
    .btn-search {
        height: 42px;
        padding: 0 25px;
        background: #1a237e;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-search:hover { opacity: 0.9; }
    
    .btn-reset {
        height: 42px;
        padding: 0 20px;
        color: #666;
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        display: flex;
        align-items: center;
        text-decoration: none;
        white-space: nowrap;
        font-weight: 500;
        box-sizing: border-box;
    }
    .btn-reset:hover { background: #f1f1f1; }
    
    /* Table styles */
    .table-order { width: 100%; border-collapse: collapse; }
    .table-order th { background: #f1f1f1; padding: 15px; text-align: left; color: #555; font-size: 13px; text-transform: uppercase; border-bottom: 2px solid #ddd;}
    .table-order td { padding: 15px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; }
    .table-order tr:hover { background: #f0f4ff; }
    
    .badge-id { background: #e8eaf6; color: #1a237e; padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 13px; }
    .price-tag { color: #d32f2f; font-weight: bold; font-size: 15px; }
    
    .btn-view {
        padding: 6px 12px;
        background: #0288d1;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    .btn-view:hover { background: #0277bd; transform: translateY(-2px); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .order-status-form { display:flex; align-items:center; gap:8px; margin-top:8px; }
    .order-status-form select { flex:1; width:auto; min-width:145px; height:38px; padding:0 10px; border:1px solid #d5dbea; border-radius:7px; background:#fff; }
    .order-status-form button, .btn-cancel { min-height:36px; padding:0 13px; border:0; border-radius:6px; background:#1a237e; color:#fff; font-weight:700; cursor:pointer; white-space:nowrap; }
    .order-status-form button:hover { background:#303f9f; }
    .order-actions { display:flex; flex-direction:column; justify-content:center; align-items:center; gap:8px; min-width:150px; }
    .order-actions form { margin:0; }
    .btn-cancel { background:#fee2e2; color:#b42330; border:1px solid #fecaca; }
    .btn-cancel:hover { background:#fecaca; }
    .order-refresh-note { margin-left:auto; color:rgba(255,255,255,.82); font-size:12px; font-weight:400; }
    @media (max-width: 640px) { .order-refresh-note { display:none; } }
</style>

<div class="card-custom">
    <div class="card-header-custom">
        <i class="fas fa-history"></i> Lịch Sử Đơn Hàng
        <?php if (empty($data['keyword'])) { ?><span class="order-refresh-note">Tự cập nhật mỗi 15 giây</span><?php } ?>
    </div>
    
    <div class="search-box">
        <form action="/Baitaplon/Donhang" method="POST" class="search-form">
            
            <div class="input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="txtTimKiem" class="form-control-search" 
                       placeholder="Nhập mã hóa đơn hoặc tên khách hàng..." 
                       value="<?php echo (isset($data['keyword']) && $data['keyword']!='0') ? $data['keyword'] : '' ?>">
            </div>
            
            <button type="submit" name="btnTimKiem" class="btn-search">
                Tìm kiếm
            </button>
            
            <a href="/Baitaplon/Donhang" class="btn-reset" title="Tải lại danh sách">
                <i class="fas fa-sync-alt" style="margin-right: 5px;"></i> Tất cả
            </a>
        </form>
    </div>

    <div style="padding: 0; overflow-x: auto;">
        <table class="table-order">
            <thead>
                <tr>
                    <th style="padding-left: 20px;">Mã HĐ</th>
                    <th>Ngày Lập</th>
                    <th>Khách Hàng</th>
                    <th>Người Bán</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng thái</th>
                    <th style="text-align: center;">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                    while($row = mysqli_fetch_array($data['dulieu'])){
                ?>
                    <tr>
                        <td style="padding-left: 20px;">
                            <span class="badge-id">#<?php echo $row['MaHD']; ?></span>
                        </td>
                        <td style="color: #555;">
                            <i class="far fa-clock" style="margin-right:5px"></i> 
                            <?php echo date('d/m/Y H:i', strtotime($row['NgayLap'])); ?>
                        </td>
                        <td style="font-weight: 600; color: #333;">
                            <?php echo $row['TenKH']; ?>
                        </td>
                        <td style="color: #666;">
                            <?php echo $row['HoTen']; ?>
                        </td>
                        <td>
                            <span class="price-tag"><?php echo number_format($row['TongTien']); ?> đ</span>
                        </td>
                        <td>
                            <?php
                            $status = (int)$row['TrangThai'];
                            $statusNames = [1 => 'Chờ xác nhận', 2 => 'Chờ lấy hàng', 3 => 'Đang giao', 4 => 'Hoàn thành', 5 => 'Đã hủy'];
                            echo htmlspecialchars($statusNames[$status] ?? 'Không xác định', ENT_QUOTES, 'UTF-8');
                            if (in_array($status, [1, 2, 3], true)) {
                            ?>
                                <form class="order-status-form" method="post" action="/Baitaplon/Donhang/CapNhatTrangThai/<?php echo (int)$row['MaHD']; ?>">
                                    <select name="ddlTrangThai" aria-label="Cập nhật trạng thái" required>
                                        <?php if ($status === 1) { ?><option value="" selected disabled>Chờ xác nhận</option><?php } ?>
                                        <option value="2" <?php echo $status === 2 ? 'selected' : ''; ?>>Chờ lấy hàng</option>
                                        <option value="3" <?php echo $status === 3 ? 'selected' : ''; ?>>Đang giao</option>
                                    </select>
                                    <button type="submit">Cập nhật</button>
                                </form>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="order-actions">
                            <a href="/Baitaplon/Donhang/Chitiet/<?php echo $row['MaHD']; ?>" class="btn-view" title="Xem chi tiết hóa đơn">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                            <?php if (in_array((int)$row['TrangThai'], [1, 2], true)) { ?>
                                <form method="post" action="/Baitaplon/Donhang/Huy/<?php echo (int)$row['MaHD']; ?>">
                                    <button class="btn-cancel" type="button" data-swal-confirm="Bạn có chắc chắn muốn hủy đơn hàng này và hoàn lại tồn kho?" data-swal-title="Xác nhận hủy đơn">Hủy đơn</button>
                                </form>
                            <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center; padding: 50px; color: #999;'>
                        <i class='fas fa-search' style='font-size:40px; margin-bottom:15px; display:block; color:#eee;'></i>
                        Không tìm thấy đơn hàng nào phù hợp!
                    </td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (empty($data['keyword'])) { ?>
<script>
(() => {
    const intervalMs = 15000;
    const currentBody = document.querySelector('.table-order tbody');
    if (!currentBody) return;

    window.setInterval(async () => {
        const active = document.activeElement;
        if (document.hidden || (active && active.matches('input, select, textarea, button'))) return;

        try {
            const response = await fetch(window.location.pathname, {
                cache: 'no-store',
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) return;
            const html = await response.text();
            const nextDocument = new DOMParser().parseFromString(html, 'text/html');
            const nextBody = nextDocument.querySelector('.table-order tbody');
            if (nextBody && currentBody.innerHTML !== nextBody.innerHTML) {
                currentBody.innerHTML = nextBody.innerHTML;
            }
        } catch (error) {
            // Keep the currently displayed list if a background refresh fails.
        }
    }, intervalMs);
})();
</script>
<?php } ?>
