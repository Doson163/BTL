<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Siêu Thị</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="/Baitaplon/Public/Css/giaodien.css?v=<?php echo filemtime(__DIR__ . '/../../Public/Css/giaodien.css'); ?>">
</head>
<body>

<?php
$currentPage = (string)($data['page'] ?? '');
$activeClass = function ($pages) use ($currentPage) {
    if (!is_array($pages)) {
        $pages = [$pages];
    }
    return in_array($currentPage, $pages, true) ? 'active' : '';
};
?>
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-shopping-basket" style="margin-right:10px; color: #4fc3f7"></i> 
            DailyMart
        </div>
        
        <ul class="menu">
            <li><a href="/Baitaplon/Home" class="<?php echo $activeClass(['Home_V', '']); ?>"><i class="fas fa-home"></i> Tổng quan</a></li>

            <div class="menu-title">HOẠT ĐỘNG BÁN HÀNG</div>
            <li><a href="/Baitaplon/Banhang" class="<?php echo $activeClass('Banhang_V'); ?>"><i class="fas fa-desktop"></i> Bán hàng </a></li>
            <li><a href="/Baitaplon/Donhang" class="<?php echo $activeClass(['Donhang_V', 'ChitietDonhang_V']); ?>"><i class="fas fa-file-invoice-dollar"></i> Đơn hàng <span id="admin-order-count" class="admin-support-unread" aria-label="Đơn khách chờ xác nhận" hidden></span></a></li>

            <div class="menu-title">QUẢN LÝ HÀNG HÓA</div>
            <li><a href="/Baitaplon/Sanpham" class="<?php echo $activeClass('Sanpham_V'); ?>"><i class="fas fa-box"></i> Sản phẩm</a></li>
            <li><a href="/Baitaplon/Danhmuc" class="<?php echo $activeClass('Danhmuc_V'); ?>"><i class="fas fa-list"></i> Danh mục</a></li>
            <li><a href="/Baitaplon/Khohang" class="<?php echo $activeClass('Khohang_V'); ?>"><i class="fas fa-warehouse"></i> Phiếu Nhập Kho</a></li>
            <li><a href="/Baitaplon/Khohang/KiemKe" class="<?php echo $activeClass('Kiemkho_V'); ?>"><i class="fas fa-tasks"></i> Kiểm kê / Điều chỉnh</a></li>

            <div class="menu-title">HỆ THỐNG</div>
            <li><a href="/Baitaplon/Khuyenmai" class="<?php echo $activeClass('Khuyenmai_V'); ?>"><i class="fas fa-tags"></i> Khuyến mãi</a></li>
            <li><a href="/Baitaplon/Baocao" class="<?php echo $activeClass('Baocao_V'); ?>"><i class="fas fa-wallet"></i> Báo cáo</a></li>
            <li><a href="/Baitaplon/SupportChat/Inbox" class="<?php echo $activeClass('SupportChat_V'); ?>"><i class="fas fa-comments"></i> Chat hỗ trợ <span id="admin-support-unread" class="admin-support-unread" aria-label="Tin nhắn chưa đọc" hidden></span></a></li>
            
            
            <li style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px;">
                <a href="/Baitaplon/Login/Logout" style="color: #ff6b6b;">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <div class="page-title">
                <h3></h3> </div>
<div class="user-info">
                <span>Xin chào, <b><?php echo htmlspecialchars((string)($_SESSION['user_login']['hoten'] ?? 'Admin'), ENT_QUOTES, 'UTF-8'); ?></b></span>
                
                <a href="/Baitaplon/Login/Logout" title="Đăng xuất" style="margin-left: 15px; color: #333; font-size: 18px;">
                    <i class="fas fa-power-off" style="color: #e31d2b;"></i>
                </a>

                <div class="user-avatar" style="margin-left: 10px;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </header>

        <div class="page-wrapper">
            <?php if (!empty($_SESSION['flash_notice'])) { $flashNotice = $_SESSION['flash_notice']; unset($_SESSION['flash_notice']); ?>
                <div class="admin-flash admin-flash-<?php echo $flashNotice['type'] === 'error' ? 'error' : 'success'; ?>" role="status" aria-live="polite">
                    <i class="fas <?php echo $flashNotice['type'] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'; ?>" aria-hidden="true"></i>
                    <span><?php echo htmlspecialchars($flashNotice['message'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <button type="button" aria-label="Đóng thông báo" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php } ?>
            <?php 
                // Đây là nơi phép màu xảy ra: Nội dung các trang con sẽ được nhúng vào đây
                if(isset($data['page'])){
                    // Kiểm tra file có tồn tại không trước khi require để tránh lỗi
                    if(file_exists("./MVC/Views/Pages/".$data['page'].".php")){
                        require_once "./MVC/Views/Pages/".$data['page'].".php";
                    } else {
                        echo "<h3 style='color:red'>Không tìm thấy file giao diện: ".$data['page']."</h3>";
                    }
                }
            ?>
        </div>
    </div>

<style>
.admin-flash{display:flex;align-items:center;gap:10px;margin:0 0 16px;padding:12px 16px;border:1px solid;border-radius:8px;font-weight:600}
.admin-flash-success{background:#24351f;border-color:#56863c;color:#c9eea9}
.admin-flash-error{background:#3a2424;border-color:#a64a4a;color:#ffc7c7}
.admin-flash button{margin-left:auto;border:0;background:transparent;color:inherit;font-size:22px;line-height:1;cursor:pointer}
.admin-support-unread{display:inline-grid;min-width:19px;height:19px;margin-left:7px;padding:0 5px;place-items:center;border-radius:999px;background:#ed3b4b;color:#fff;font:800 11px/1 Arial,sans-serif;vertical-align:middle}
.admin-support-unread[hidden]{display:none}
.sidebar .menu li a.active{
    background:#1b2228 !important;
    color:#f2d15a !important;
    border-left:3px solid #dfb936 !important;
    font-weight:600 !important;
}
.sidebar .menu li a.active i{
    color:#f2d15a !important;
    transform:scale(1.1);
}

/* Custom SweetAlert2 Bảng thông báo DailyMart */
div.swal2-container {
    z-index: 100000 !important;
    backdrop-filter: blur(4px);
    background: rgba(10, 16, 24, 0.45) !important;
}
div.swal2-popup.dailymart-alert {
    border-radius: 18px !important;
    padding: 28px 24px 24px !important;
    background: #ffffff !important;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.22) !important;
    border: 1px solid rgba(220, 226, 235, 0.8) !important;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif !important;
}
div.swal2-popup.dailymart-alert .swal2-title {
    font-size: 21px !important;
    font-weight: 700 !important;
    color: #1a237e !important;
    margin-bottom: 8px !important;
    padding: 0 !important;
}
div.swal2-popup.dailymart-alert .swal2-html-container {
    font-size: 15px !important;
    color: #374151 !important;
    line-height: 1.55 !important;
    margin: 10px 0 20px !important;
}
div.swal2-popup.dailymart-alert .swal2-actions {
    margin-top: 15px !important;
    gap: 12px !important;
}
div.swal2-popup.dailymart-alert .swal2-confirm {
    background: #1a237e !important;
    color: #ffffff !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    border-radius: 10px !important;
    padding: 10px 26px !important;
    box-shadow: 0 4px 14px rgba(26, 35, 126, 0.25) !important;
    transition: all 0.2s ease !important;
}
div.swal2-popup.dailymart-alert .swal2-confirm:hover {
    background: #283593 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 18px rgba(26, 35, 126, 0.35) !important;
}
div.swal2-popup.dailymart-alert .swal2-cancel {
    background: #f1f3f7 !important;
    color: #4b5563 !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    border-radius: 10px !important;
    padding: 10px 24px !important;
    transition: all 0.2s ease !important;
}
div.swal2-popup.dailymart-alert .swal2-cancel:hover {
    background: #e5e7eb !important;
    color: #1f2937 !important;
}
div.swal2-popup.dailymart-alert .swal2-timer-progress-bar {
    background: #1a237e !important;
    height: 3px !important;
}
</style>
<script src="/Baitaplon/Public/Js/sweetalert2.all.min.js"></script>
<script src="/socket.io/socket.io.js"></script>
<script>
(function(){
    // Thay thế alert mặc định bằng bảng thông báo đẹp
    if (window.Swal) {
        window.alert = function(msg) {
            return Swal.fire({
                icon: 'info',
                title: 'Thông báo',
                text: String(msg),
                confirmButtonText: 'Đồng ý',
                confirmButtonColor: '#1a237e',
                customClass: { popup: 'dailymart-alert' }
            });
        };
    }

    // Xử lý xác nhận xóa / hủy đơn qua bảng thông báo đẹp
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('button[data-swal-confirm], a[data-swal-confirm]');
        if (!btn) return;
        e.preventDefault();
        const message = btn.getAttribute('data-swal-confirm') || 'Bạn có chắc chắn muốn thực hiện thao tác này?';
        const title = btn.getAttribute('data-swal-title') || 'Xác nhận';
        const isDanger = btn.classList.contains('btn-cancel') || btn.classList.contains('btn-red') || btn.getAttribute('data-swal-danger') === 'true';

        if (window.Swal) {
            Swal.fire({
                title: title,
                text: message,
                icon: isDanger ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
                confirmButtonColor: isDanger ? '#d32f2f' : '#1a237e',
                cancelButtonColor: '#6c757d',
                customClass: { popup: 'dailymart-alert' }
            }).then(function(result) {
                if (result.isConfirmed) {
                    if (btn.tagName === 'A') {
                        window.location.href = btn.href;
                    } else if (btn.tagName === 'BUTTON' && btn.form) {
                        btn.form.submit();
                    }
                }
            });
        } else {
            if (confirm(message)) {
                if (btn.tagName === 'A') window.location.href = btn.href;
                else if (btn.form) btn.form.submit();
            }
        }
    });

    const chatBadge=document.getElementById('admin-support-unread');
    const orderBadge=document.getElementById('admin-order-count');
    const tokenUrl='/Baitaplon/SupportChat/Token',unreadUrl='/Baitaplon/SupportChat/Unread',orderCountUrl='/Baitaplon/Donhang/SoDonMoi';
    function render(badge,count){if(!badge)return;count=Math.max(0,Number(count)||0);badge.textContent=count>99?'99+':String(count);badge.hidden=count===0;}
    async function refreshChat(){if(!chatBadge)return;try{const response=await fetch(unreadUrl,{credentials:'same-origin',cache:'no-store'});const result=await response.json();if(response.ok&&result.ok)render(chatBadge,result.unreadCount);}catch(_){}}
    async function refreshOrders(){if(!orderBadge)return;try{const response=await fetch(orderCountUrl,{credentials:'same-origin',cache:'no-store'});const result=await response.json();if(response.ok&&result.ok)render(orderBadge,result.count);}catch(_){}}
    window.addEventListener('support:unread-refresh',refreshChat);
    refreshChat();
    refreshOrders();
    window.setInterval(refreshOrders,15000);
    window.addEventListener('focus',refreshOrders);
    document.addEventListener('visibilitychange',()=>{if(!document.hidden)refreshOrders();});
    // Tự động làm sáng nút ngay khi click để phản hồi tức thì
    document.querySelectorAll('.sidebar .menu li a').forEach(function(link) {
        link.addEventListener('click', function() {
            var href = this.getAttribute('href');
            if (href && !href.startsWith('#') && !href.includes('Logout')) {
                document.querySelectorAll('.sidebar .menu li a').forEach(function(l) {
                    l.classList.remove('active');
                });
                this.classList.add('active');
            }
        });
    });

    if(!window.io)return;
    const socket=window.io({path:'/socket.io/',transports:['websocket','polling'],auth:async callback=>{try{const response=await fetch(tokenUrl,{credentials:'same-origin',cache:'no-store'});const result=await response.json();callback({token:response.ok&&result.ok?result.token:''});}catch(_){callback({token:''});}},reconnection:true});
    socket.on('support:inbox-update',refreshChat);
    socket.on('support:unread-count',count=>render(chatBadge,count));
    socket.on('admin:order-count',count=>render(orderBadge,count));
})();
</script>

<?php if (!empty($_SESSION['flash_message'])): 
    $flash = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.Swal) {
        Swal.fire({
            icon: <?php echo json_encode($flash['type'] ?? 'info'); ?>,
            title: <?php echo json_encode($flash['title'] ?? 'Thông báo'); ?>,
            text: <?php echo json_encode($flash['message'] ?? ''); ?>,
            confirmButtonText: 'Đồng ý',
            confirmButtonColor: '#1a237e',
            customClass: { popup: 'dailymart-alert' },
            timer: 4000,
            timerProgressBar: true
        });
    }
});
</script>
<?php endif; ?>
</body>
</html>
