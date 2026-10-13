<?php
     session_start();

      if(!isset($_SESSION['user_login'])){
    // Đuổi về trang đăng nhập của WinmartMVC
    header("Location: /Baitaplon/Admin");
    exit;
}
     include_once './MVC/bridge.php';
     $myapp=new app();
?>
