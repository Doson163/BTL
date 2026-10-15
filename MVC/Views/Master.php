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

    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-shopping-basket" style="margin-right:10px; color: #4fc3f7"></i> 
            DailyMart
        </div>
        
        <ul class="menu">
            <li><a href="/Baitaplon/Home" class="active"><i class="fas fa-home"></i> Tổng quan</a></li>

            <div class="menu-title">HOẠT ĐỘNG BÁN HÀNG</div>
            <li><a href="/Baitaplon/Banhang"><i class="fas fa-desktop"></i> Bán hàng </a></li>
            <li><a href="/Baitaplon/Donhang"><i class="fas fa-file-invoice-dollar"></i> Đơn hàng</a></li>

            <div class="menu-title">QUẢN LÝ HÀNG HÓA</div>
            <li><a href="/Baitaplon/Sanpham"><i class="fas fa-box"></i> Sản phẩm</a></li>
            <li><a href="/Baitaplon/Danhmuc"><i class="fas fa-list"></i> Danh mục</a></li>
            <li><a href="/Baitaplon/Khohang"><i class="fas fa-warehouse"></i> Phiếu Nhập Kho</a></li>
            <li><a href="/Baitaplon/Khohang/KiemKe"><i class="fas fa-tasks"></i> Kiểm kê / Điều chỉnh</a></li>

            <div class="menu-title">HỆ THỐNG</div>
            <li><a href="/Baitaplon/Khuyenmai"><i class="fas fa-tags"></i> Khuyến mãi</a></li>
            <li><a href="/Baitaplon/Baocao"><i class="fas fa-wallet"></i> Báo cáo</a></li>
            <li><a href="/Baitaplon/SupportChat/Inbox"><i class="fas fa-comments"></i> Chat hỗ trợ <span id="admin-support-unread" class="admin-support-unread" aria-label="Tin nhắn chưa đọc" hidden></span></a></li>
            
            
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
.admin-support-unread{display:inline-grid;min-width:19px;height:19px;margin-left:7px;padding:0 5px;place-items:center;border-radius:999px;background:#ed3b4b;color:#fff;font:800 11px/1 Arial,sans-serif;vertical-align:middle}
.admin-support-unread[hidden]{display:none}
</style>
<script src="/socket.io/socket.io.js"></script>
<script>
(function(){
    const badge=document.getElementById('admin-support-unread');
    if(!badge)return;
    const tokenUrl='/Baitaplon/SupportChat/Token',unreadUrl='/Baitaplon/SupportChat/Unread';
    function render(count){count=Math.max(0,Number(count)||0);badge.textContent=count>99?'99+':String(count);badge.hidden=count===0;}
    async function refresh(){try{const response=await fetch(unreadUrl,{credentials:'same-origin',cache:'no-store'});const result=await response.json();if(response.ok&&result.ok)render(result.unreadCount);}catch(_){}}
    window.addEventListener('support:unread-refresh',refresh);
    refresh();
    if(!window.io)return;
    const socket=window.io({path:'/socket.io/',transports:['websocket','polling'],auth:async callback=>{try{const response=await fetch(tokenUrl,{credentials:'same-origin',cache:'no-store'});const result=await response.json();callback({token:response.ok&&result.ok?result.token:''});}catch(_){callback({token:''});}},reconnection:true});
    socket.on('support:inbox-update',refresh);
    socket.on('support:unread-count',render);
})();
</script>
</body>
</html>
