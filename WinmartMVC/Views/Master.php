<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinMart Clone</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        
        body { font-family: 'Outfit', sans-serif; margin: 0; background-color: #f8fafc; }
        
        /* PREMIUM HEADER */
        header { 
            background: linear-gradient(135deg, #e31d2b 0%, #d81b26 100%); 
            padding: 16px 0; 
            color: white; 
            box-shadow: 0 4px 24px rgba(227, 29, 43, 0.25);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .container { width: 1200px; max-width: 95%; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        
        .logo { font-size: 32px; font-weight: 800; display: flex; align-items: center; letter-spacing: -0.5px; text-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .logo span { font-weight: 300; opacity: 0.9; }
        .logo img { height: 40px; margin-right: 10px; }
        
        .search-box { flex: 1; margin: 0 40px; position: relative; }
        .search-box input { 
            width: 100%; 
            padding: 14px 50px 14px 24px; 
            border-radius: 30px; 
            border: 2px solid transparent; 
            outline: none; 
            font-family: inherit;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }
        .search-box input:focus {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(-1px);
        }
        .search-box button { 
            position: absolute; 
            right: 8px; 
            top: 50%; 
            transform: translateY(-50%); 
            background: #e31d2b; 
            border: none; 
            cursor: pointer; 
            color: white; 
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .search-box button:hover {
            background: #b91c1c;
        }
        
        .header-actions { display: flex; gap: 24px; font-size: 15px; font-weight: 500; }
        .action-item { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            cursor: pointer; 
            padding: 8px 12px;
            border-radius: 12px;
            transition: background 0.2s;
        }
        .action-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .action-item i { font-size: 20px; }
        
        /* BANNER VOUCHER */
        .voucher-section { display: flex; gap: 10px; padding: 20px 0; justify-content: center; background: white; margin-bottom: 20px; }
        .voucher-card { border: 1px dashed #e31d2b; padding: 10px; border-radius: 5px; width: 250px; position: relative; }
        .btn-layngay { background: #e31d2b; color: white; border: none; padding: 5px 15px; border-radius: 5px; float: right; cursor: pointer;}
        
        /* FLASH SALE */
        .flash-sale { background: white; width: 1200px; margin: 0 auto; border-radius: 10px; overflow: hidden; }
        .fs-header { background: linear-gradient(90deg, #ff512f, #dd2476); padding: 15px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .fs-title { font-size: 20px; font-weight: bold; text-transform: uppercase; }
        
        .product-grid { display: flex; padding: 20px; gap: 15px; }
        .product-card { border: 1px solid #eee; width: 25%; padding: 10px; text-align: center; position: relative; border-radius: 8px; transition: 0.3s; }
        .product-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .product-card img { width: 100%; height: 180px; object-fit: contain; }
        .p-name { font-size: 14px; margin: 10px 0; height: 40px; overflow: hidden; color: #333; }
        .p-price { color: #d0021b; font-weight: bold; font-size: 18px; }
        .btn-add { background: white; border: 1px solid #e31d2b; color: #e31d2b; padding: 5px 10px; border-radius: 20px; margin-top: 10px; cursor: pointer; }
        .btn-add:hover { background: #e31d2b; color: white; }

        /* Style cho nút Danh mục cha */
        .menu-danhmuc {
            margin-left: 20px; 
            cursor: pointer; 
            position: relative;
            display: flex; 
            align-items: center; 
            gap: 8px;
            padding: 10px 16px;
            background: rgba(0,0,0,0.15);
            border-radius: 20px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .menu-danhmuc:hover { background: rgba(0,0,0,0.25); }

        /* Menu con thả xuống */
        .dropdown-content {
            display: none;
            position: absolute;
            top: calc(100% + 15px);
            left: 0;
            background-color: white;
            min-width: 220px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 100;
            border-radius: 12px;
            border-top: 4px solid #e31d2b;
            overflow: hidden;
            animation: slideUp 0.2s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-content::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 0;
            width: 100%;
            height: 20px;
            background: transparent;
        }

        .dropdown-content a {
            color: #333;
            padding: 14px 20px;
            text-decoration: none;
            display: block;
            font-size: 15px;
            font-weight: 500;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s;
        }
        .dropdown-content a:last-child { border-bottom: none; }

        .dropdown-content a:hover {
            background-color: #fef2f2;
            color: #e31d2b;
            padding-left: 25px;
        }

        .menu-danhmuc:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <a href="<?php echo BASE_URL ?>Home" class="logo" style="text-decoration: none; color: white;">
            Win<span>Mart</span>
        </a>

        <?php if ($data['Page'] !== 'Login_v') { ?>
        <div class="menu-danhmuc">
            <i class="fas fa-bars"></i> Danh mục
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL ?>Home">Tất cả sản phẩm</a>
                <a href="<?php echo BASE_URL ?>Home/Loc/1">Gia vị</a>
                <a href="<?php echo BASE_URL ?>Home/Loc/2">Đồ uống</a>
                <a href="<?php echo BASE_URL ?>Home/Loc/3">Thực phẩm tươi</a>
            </div>
        </div>

        <div class="search-box">
            <form action="<?php echo BASE_URL ?>Home/TimKiem" method="POST" style="display: flex; width: 100%;">
                <input type="text" name="keyword" placeholder="Tìm kiếm hàng ngàn sản phẩm..." required>
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <?php } ?>

        <div class="header-actions">
            <?php if ($data['Page'] !== 'Login_v') { ?>
            <a href="<?php echo BASE_URL ?>Cart" style="text-decoration: none; color: white;">
                <div class="action-item">
                    <i class="fas fa-shopping-cart"></i> 
                    Giỏ hàng (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0; ?>)
                </div>
            </a>
            <?php } ?>
            
            <div class="action-item">
                <i class="fas fa-user-circle"></i> 
                <?php if(isset($_SESSION['user_login'])) { ?>
                    <span style="display: inline-block; line-height: 1.4;">
                        Xin chào, <b><?php echo $_SESSION['user_login']['hoten'] ?></b>
                        <br>
                        <a href="<?php echo BASE_URL ?>MVC/" style="color: #ffcccc; font-size: 12px; text-decoration: none;">[Admin]</a>
                        <a href="<?php echo BASE_URL ?>Login/Logout" style="color: #fff; font-size: 12px; font-weight: bold; text-decoration: underline; margin-left: 8px;">Đăng xuất</a>
                    </span>
                <?php } elseif ($data['Page'] !== 'Login_v') { ?>
                    <a href="<?php echo BASE_URL ?>Login" style="text-decoration: none; color: white;">Đăng nhập</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

    <div style="padding-bottom: 50px;">
        <?php 
            // Load nội dung trang con (Pages)
            if(file_exists("./Views/Pages/".$data["Page"].".php")){
                require_once "./Views/Pages/".$data["Page"].".php";
            }
        ?>
    </div>

</body>
</html>