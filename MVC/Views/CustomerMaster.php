<?php
$cartCount = isset($data['cartCount'])
    ? (int)$data['cartCount']
    : (int)array_sum($_SESSION['shop_cart'] ?? []);
$escape = static function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};
$isCustomerSignedIn = !empty($_SESSION['shop_customer']['MaKH']);
$currentPage = (string)($data['page'] ?? '');
$isAuthPage = in_array($currentPage, ['Customer/Login', 'Customer/Register'], true);
$showShoppingTools = !$isAuthPage;
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>DailyMart | Mua sắm tiện lợi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{--brand:#ddb52d;--accent:#f2c94c;--ink:#f6f7f8;--muted:#94a3b8;--line:#282e35;--surface:#191e24;--page:#101417}
        *{box-sizing:border-box}
        body{margin:0;background:var(--page);color:var(--ink);font:15px/1.55 'Outfit',Arial,sans-serif}
        a{color:inherit;text-decoration:none}
        .container{width:min(1180px,calc(100% - 40px));margin:auto}
        .topbar{background:rgba(13,17,20,.96);color:#f8fafc;border-top:2px solid #d4ad2d;border-bottom:1px solid #252b31;box-shadow:0 10px 30px rgba(0,0,0,.28);position:sticky;top:0;z-index:999;backdrop-filter:blur(14px)}
        .topbar-inner{min-height:70px;display:flex;align-items:center;justify-content:space-between;gap:22px}
        .brand{font-size:21px;line-height:1.08;font-weight:800;letter-spacing:-.5px;white-space:nowrap;display:flex;align-items:center;gap:10px}
        .brand-mark{width:36px;height:36px;display:grid;place-items:center;background:linear-gradient(145deg,#f5d86e,#c99d18);color:#171a1d;border-radius:11px;font-size:17px;box-shadow:0 4px 14px rgba(221,181,45,.2)}
        .brand-copy{display:flex;flex-direction:column}.brand-copy span{color:#e3bd39;font-weight:700;font-size:13px;letter-spacing:.35px}
        .nav{display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap}
        .nav a{font-weight:600;padding:9px 12px;border-radius:10px;transition:background .2s,color .2s;white-space:nowrap;color:#d8dee5;font-size:14px}
        .nav a:hover{background:#20262d;color:#f1cb4b}
        .nav form{display:inline;margin:0}
        .nav-logout{border:0;background:transparent;color:#d8dee5;font-weight:600;cursor:pointer;padding:9px 12px;border-radius:10px;transition:background .2s;font:inherit;white-space:nowrap;font-size:14px}
        .nav-logout:hover{background:#20262d;color:#f1cb4b}
        .nav-search{display:flex;align-items:center;gap:7px;background:#191e24;border:1px solid #303740;border-radius:999px;padding:4px 7px 4px 13px;width:min(245px,22vw)}
        .nav form.nav-search{display:flex;flex-wrap:nowrap;align-items:center;min-width:0}
        .nav-search input{flex:1 1 auto;width:0;min-width:0}
        .nav-search button{flex:0 0 auto;display:grid;place-items:center;line-height:1;white-space:nowrap}
        .nav-search input{border:0!important;background:transparent!important;color:#f4f6f8!important;padding:5px 2px!important;margin:0!important;font-size:12px!important;box-shadow:none!important}
        .nav-search input::placeholder{color:#8993a0}.nav-search button{border:0;background:transparent;color:#aab3bd;cursor:pointer;padding:4px 6px}
        .cart-link{background:#1b2128!important;color:#f3cb47!important;padding:9px 13px!important;border-radius:999px;border:1px solid #353b43;display:flex;align-items:center;gap:8px}
        .cart-link:hover{background:#252b32!important}
        main{padding:30px 0 70px;min-height:64vh}
        .notice{margin:0 0 18px;padding:12px 16px;background:#17362c;border:1px solid #235542;color:#a8ebc9;border-radius:10px}
        .page-head{display:flex;align-items:end;justify-content:space-between;gap:16px;margin-bottom:20px}
        .page-head h1{margin:0;font-size:30px}
        .muted{color:var(--muted)}
        .filter-panel,.panel{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:18px;margin-bottom:20px}
        .filters{display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:10px}
        input,select,textarea,button{font:inherit}
        input,select,textarea{min-width:0;width:100%;padding:11px 12px;border:1px solid #d6dee8;border-radius:8px;background:#fff}
        label{display:block;margin:12px 0 6px}
        .button{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:8px;padding:11px 16px;background:var(--brand);color:white;font-weight:700;cursor:pointer}
        .button.secondary{background:#e8eef7;color:var(--brand)}
        .button.danger{background:#fff0f0;color:#b42330}
        .button:disabled{opacity:.5;cursor:not-allowed}
        .products{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
        .product-card{background:#fff;border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column}
        .product-image{height:190px;background:#fff;display:grid;place-items:center}
        .product-image img{width:100%;height:100%;object-fit:contain;padding:12px}
        .product-copy{padding:14px;display:flex;flex:1;flex-direction:column}
        .product-copy h2{font-size:17px;line-height:1.35;margin:5px 0 8px}
        .price{font-size:19px;font-weight:800;color:var(--accent)}
        .stock{font-size:13px;color:var(--muted);margin:3px 0 12px}
        .product-actions{display:flex;gap:8px;margin-top:auto}
        .product-actions>*{flex:1}
        .empty{padding:38px;text-align:center;color:var(--muted);background:white;border-radius:14px;border:1px dashed #cbd5e1}
        .detail{display:grid;grid-template-columns:minmax(260px,1fr) 1.2fr;gap:30px}
        .detail-image{background:#fff;border:1px solid var(--line);border-radius:14px;min-height:360px;display:grid;place-items:center}
        .detail-image img{max-width:100%;max-height:420px;object-fit:contain}
        .detail-copy h1{font-size:30px;margin:4px 0}
        .detail-copy .price{font-size:26px}
        .detail-copy p{color:var(--muted)}
        .cart-table{width:100%;border-collapse:collapse}
        .cart-table th,.cart-table td{text-align:left;padding:12px;border-bottom:1px solid var(--line);vertical-align:middle}
        .cart-table th{color:var(--muted);font-size:13px}
        .cart-product{display:flex;align-items:center;gap:12px}
        .cart-product img{width:62px;height:62px;object-fit:contain;background:#fff;border-radius:8px}
        .qty-form{display:flex;gap:8px;align-items:center}
        .qty-form input{width:90px}
        .cart-summary{margin-left:auto;max-width:360px;display:grid;gap:12px}
        .summary-line{display:flex;justify-content:space-between;gap:12px}
        .summary-total{font-size:22px;font-weight:800}
        .checkout-grid{display:grid;grid-template-columns:1.3fr 1fr;gap:18px}
        .payment-option{display:flex;align-items:center;gap:10px;padding:10px 0}
        .checkout-grid hr{border:0;border-top:1px solid var(--line);margin:14px 0}
        footer{padding:36px 0;border-top:1px solid #252b31;color:#8793a1;background:#090c0f}
        @media(max-width:900px){.filters{grid-template-columns:repeat(3,1fr)}.products{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(max-width:640px){.checkout-grid{grid-template-columns:1fr}.topbar-inner{height:auto;flex-wrap:wrap;padding:12px 0}.nav{justify-content:flex-start;gap:4px;font-size:13px}.nav a,.nav-logout{padding:8px}.page-head{align-items:start;flex-direction:column}.filters{grid-template-columns:1fr 1fr}.products{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.product-image{height:150px}.product-copy{padding:11px}.detail{grid-template-columns:1fr}.detail-image{min-height:250px}.cart-table thead{display:none}.cart-table,.cart-table tbody,.cart-table tr,.cart-table td{display:block;width:100%}.cart-table tr{padding:10px 0}.cart-table td{border:0;padding:5px 0}.cart-summary{max-width:none}}
    </style>
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="/Baitaplon/store.php?url=Storefront/Get_data"><span class="brand-mark"><i class="fas fa-shopping-basket"></i></span><span class="brand-copy">DailyMart<span>Premium</span></span></a>
        <?php if ($showShoppingTools) { ?>
        <nav class="nav" aria-label="Điều hướng khách hàng">
            <a href="/Baitaplon/store.php?url=Storefront/Get_data">Trang chủ</a>
            <form class="nav-search" method="get" action="/Baitaplon/store.php" role="search">
                <input type="hidden" name="url" value="Storefront/Get_data">
                <input type="search" name="q" placeholder="Tìm kiếm thực phẩm..." aria-label="Tìm kiếm sản phẩm" value="<?php echo $escape($data['filters']['q'] ?? ''); ?>">
                <button type="submit" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
            </form>
            <?php if ($isCustomerSignedIn) { ?>
                <a href="/Baitaplon/store.php?url=CustomerOrders/Get_data">Đơn hàng</a>
                <a href="/Baitaplon/store.php?url=CustomerAuth/Profile">Tài khoản</a>
                <form method="post" action="/Baitaplon/User/Logout">
                    <input type="hidden" name="csrf_token" value="<?php echo $escape($_SESSION['shop_csrf'] ?? ''); ?>">
                    <button class="nav-logout" type="submit">Đăng xuất</button>
                </form>
                <a class="cart-link" href="/Baitaplon/store.php?url=Cart/Get_data" aria-label="Giỏ hàng, <?php echo $cartCount; ?> sản phẩm"><i class="fas fa-shopping-cart"></i><span><?php echo $cartCount; ?></span></a>
            <?php } else { ?>
                <a href="/Baitaplon/User">Đăng nhập</a>
                <a href="/Baitaplon/User/Register">Đăng ký</a>
                <a class="cart-link" href="/Baitaplon/User" aria-label="Đăng nhập để xem giỏ hàng"><i class="fas fa-shopping-cart"></i></a>
            <?php } ?>
        </nav>
        <?php } ?>
    </div>
</header>
<main class="container">
    <?php if (!empty($data['notice'])) { ?><div class="notice"><?php echo $escape($data['notice']); ?></div><?php } ?>
    <?php
    $pageFile = __DIR__ . '/Pages/' . $currentPage . '.php';
    if ($currentPage !== '' && is_file($pageFile)) require $pageFile;
    ?>
</main>
<?php if ($showShoppingTools) require __DIR__ . '/Pages/Customer/ChatWidget.php'; ?>
<?php if ($showShoppingTools && $isCustomerSignedIn) require __DIR__ . '/Pages/Customer/SupportWidget.php'; ?>
<style>
    /* Premium dark storefront theme: presentation only; page routes and forms remain untouched. */
    body{background:#101417!important;color:#f4f6f8!important;font-family:'Outfit',Arial,sans-serif!important}
    main.container{width:min(1180px,calc(100% - 40px));padding-top:32px}
    main h1,main h2,main h3,main h4,main strong{color:#f4f6f8}
    main .muted{color:#98a4b2!important}
    main .page-head h1,main .section-title,main .panel h2,main .panel h3,main .checkout-grid h2,main .product-copy h2,main .detail-copy h1{color:#fff!important}
    main .panel,main .filter-panel,main .cart-summary,main .empty,main .detail-image,main .login-card,main .register-card{background:#191e24!important;color:#edf0f3!important;border:1px solid #292f37!important;box-shadow:0 16px 42px rgba(0,0,0,.22)!important}
    main input,main select,main textarea,main .floating-input{background:#11161a!important;color:#f5f6f7!important;border-color:#333b44!important}
    main input::placeholder,main textarea::placeholder{color:#74808d!important}
    main input:focus,main select:focus,main textarea:focus,main .floating-input:focus{border-color:#d7b234!important;box-shadow:0 0 0 3px rgba(221,181,45,.12)!important}
    main label,main .floating-label{color:#c5cbd2!important}
    main .button,main .btn-login,main .btn-register,main .btn-place-order,main .product-actions form .button,main .filters .button{background:linear-gradient(135deg,#f0cf56,#c99e1d)!important;color:#15181b!important;border:1px solid #e5c64d!important;box-shadow:0 8px 20px rgba(207,169,41,.16)!important}
    main .button:hover,main .btn-login:hover,main .btn-register:hover,main .btn-place-order:hover{filter:brightness(1.08);transform:translateY(-2px)}
    main .button.secondary{background:#20262d!important;color:#e7c449!important;border:1px solid #343b43!important;box-shadow:none!important}
    main .button.danger{background:#382124!important;color:#ff9a9e!important;border:1px solid #62333a!important}
    main .price,main .product-copy .price{color:#e6c13f!important}
    main .cart-table th{background:#20262d!important;color:#d5b53e!important;border-color:#343b43!important}
    main .cart-table td{color:#e4e8ec!important;border-color:#2a3037!important}
    main .product-card{background:#191e24!important;border-color:#292f37!important;box-shadow:0 14px 34px rgba(0,0,0,.22)!important}
    main .product-image{background:#13181d!important;border-color:#252c33!important}
    main .hero-btn{background:linear-gradient(135deg,#f0cf56,#c99e1d)!important;color:#15181b!important}
    main .login-wrapper,main .register-wrapper{background-color:#101417!important;background-image:radial-gradient(ellipse at 50% 0%,rgba(205,169,47,.13),transparent 48%),linear-gradient(135deg,#101417,#0b0f12)!important}
    main .login-card,main .register-card{backdrop-filter:blur(18px);border-radius:22px!important}
    main .login-header h2,main .register-header h2{color:#f5f6f7!important}
    main .login-header h2 span,main .register-header h2 span,main .floating-input:focus~.floating-label{color:#e4c342!important}
    main .login-header p,main .register-header p,main .register-link,main .login-link{color:#aab4be!important}
    main .register-link a,main .login-link a{color:#e4c342!important}
    main .google-login{background:#20262d!important;color:#e9edf0!important;border-color:#3b424a!important}
    main .floating-icon{color:#929eaa!important}
    main .summary-line strong[style],main td strong[style*="#e31d2b"],main i[style*="#e31d2b"]{color:#e4c342!important}
    main [style*="background: #f8fafc"],main [style*="background:#f8fafc"],main [style*="background: #f1f5f9"],main [style*="background:#f1f5f9"]{background:#14191e!important;border-color:#303740!important;color:#e9edf0!important}
    main td[style*="#1e293b"],main strong[style*="#1e293b"],main span[style*="#1e293b"]{color:#e8ebee!important}
    main .status-badge{border:1px solid #454b50}
    main .hero-badge{background:transparent!important;color:#e9c742!important;border:1px solid #d7b234!important;box-shadow:none!important}
    main .hero-content h2 em{color:#e3bd39}
    main .filter-panel{background:#171c21!important}
    main .payment-option{background:#171c21!important;border-color:#353c44!important;color:#e9edf0!important}
    main .payment-option:has(input:checked){background:#2b281b!important;border-color:#d1ac2d!important;color:#f0d365!important}
    main .summary-total{border-color:#3b3f43!important;color:#e7c449!important}
    main a:not(.button):hover{color:#f2d156}
    .shop-ai-launch{background:linear-gradient(135deg,#f0cf56,#c99e1d)!important;color:#171a1c!important;box-shadow:0 8px 24px rgba(207,169,41,.2)!important}
    .shop-ai-panel{background:#191e24!important;border-color:#303740!important;color:#edf0f3!important}
    .shop-ai-head{background:#11161a!important;border-bottom:1px solid #303740;color:#f0cf56!important}
    .shop-ai-log{background:#12171b!important}.shop-ai-message.assistant,.shop-ai-product{background:#1c2228!important;border-color:#303740!important;color:#e6e9ec!important}
    .shop-ai-product strong{color:#f5f6f7!important}.shop-ai-product span{color:#9aa5b1!important}
    .shop-ai-message.user{background:#9d7b10!important;color:#fff!important}
    .shop-ai-compose{background:#191e24!important;border-color:#303740!important}.shop-ai-compose input{background:#11161a!important;color:#f4f6f8!important;border-color:#333b44!important}.shop-ai-compose button{background:#d1ad31!important;color:#171a1c!important}
    /* Keep form text readable when page-level styles try to use pale fields. */
    main input:not([type="radio"]):not([type="checkbox"]):not([type="hidden"]),
    main select,main textarea{
        background-color:#11171c!important;
        color:#f1f3f5!important;
        border-color:#39434c!important;
        -webkit-text-fill-color:#f1f3f5!important;
        caret-color:#e4c342;
    }
    main input::placeholder,main textarea::placeholder{color:#84909b!important;opacity:1}
    main select option,main select optgroup{background-color:#191e24!important;color:#edf0f3!important}
    main .filters select{background:#11171c!important;background-color:#11171c!important;color:#f1f3f5!important;border:1px solid #39434c!important;-webkit-text-fill-color:#f1f3f5!important;color-scheme:dark}
    main .filters select option{background:#191e24!important;background-color:#191e24!important;color:#edf0f3!important;-webkit-text-fill-color:#edf0f3!important}
    main .filters select:focus{background:#11171c!important;background-color:#11171c!important;color:#fff!important;border-color:#dfb936!important;box-shadow:0 0 0 3px rgba(217,182,59,.14)!important}
    main input:not([type="radio"]):not([type="checkbox"]):focus,
    main select:focus,main textarea:focus{background-color:#11171c!important;color:#fff!important;border-color:#dfb936!important;box-shadow:0 0 0 3px rgba(217,182,59,.14)!important}
    main input:-webkit-autofill,main input:-webkit-autofill:hover,main input:-webkit-autofill:focus{
        -webkit-text-fill-color:#f1f3f5!important;
        box-shadow:0 0 0 1000px #11171c inset!important;
        transition:background-color 9999s ease-out 0s;
    }
    header.topbar .nav form.nav-search input{background:transparent!important;color:#f1f3f5!important;-webkit-text-fill-color:#f1f3f5!important;border:0!important;box-shadow:none!important}
    header.topbar .nav form.nav-search input::placeholder{color:#8993a0!important;-webkit-text-fill-color:#8993a0!important}
    .checkout-promo-entry{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:10px;align-items:start}
    .checkout-promo-entry input{margin-bottom:0!important}
    .checkout-promo-entry .button{white-space:nowrap;min-height:52px}
    .promo-feedback{min-height:20px;margin:-10px 0 16px;font-size:13px;color:#9aa6b2}
    .promo-feedback.is-success{color:#70d5a0}.promo-feedback.is-error{color:#ff9298}
    main #checkout-summary .summary-line{color:#c4cdd5!important}
    main #checkout-summary .summary-line strong{color:#e5c449!important}
    main #checkout-summary .promo-discount-line strong{color:#70d5a0!important}
    main #checkout-summary .summary-total span{color:#e5c449!important}
    main #checkout-summary #applied-promo-code{color:#e5c449!important;font-size:12px}
    @media(max-width:560px){.checkout-promo-entry{grid-template-columns:1fr}.checkout-promo-entry .button{width:100%}}
    .profile-layout{display:grid;grid-template-columns:minmax(0,1.45fr) minmax(300px,.9fr);gap:22px;align-items:stretch}
    .profile-panel{max-width:none!important;margin-bottom:0!important}
    .profile-panel h2{margin:24px 0 10px;font-size:21px}
    .profile-promo{position:relative;overflow:hidden;min-height:360px;padding:28px;border:1px solid rgba(225,190,65,.24);border-radius:18px;background:radial-gradient(circle at 82% 18%,rgba(231,195,62,.2),transparent 30%),linear-gradient(145deg,#1d282b 0%,#152522 48%,#182126 100%);box-shadow:0 18px 48px rgba(0,0,0,.24);display:flex;flex-direction:column;justify-content:space-between}
    .profile-promo:after{content:"";position:absolute;width:230px;height:230px;border:1px solid rgba(229,195,68,.13);border-radius:50%;right:-84px;top:82px;box-shadow:0 0 0 22px rgba(229,195,68,.025),0 0 0 46px rgba(229,195,68,.02)}
    .profile-promo-kicker{position:relative;z-index:1;align-self:flex-start;padding:7px 11px;border:1px solid rgba(226,190,57,.45);border-radius:999px;color:#e8c94d;font-size:11px;font-weight:800;letter-spacing:.8px}
    .profile-promo-mark{position:absolute;right:34px;top:84px;width:104px;height:104px;display:grid;place-items:center;border:1px solid rgba(239,207,94,.5);border-radius:28px;transform:rotate(8deg);background:linear-gradient(145deg,#f0cf56,#c99e1d);color:#171a1c;font-size:42px;box-shadow:0 18px 40px rgba(207,169,41,.18);z-index:1}
    .profile-promo-copy{position:relative;z-index:2;max-width:310px;margin-top:auto;padding-top:132px}
    .profile-promo-copy h2{font-size:29px;line-height:1.16;margin:0 0 13px;color:#fff!important}
    .profile-promo-copy p{font-size:14px;color:#aab7bd;margin:0 0 22px;max-width:285px}
    .profile-promo-copy a{display:inline-flex;align-items:center;gap:10px;padding:11px 15px;border-radius:9px;background:linear-gradient(135deg,#f0cf56,#c99e1d);color:#171a1c;font-weight:800;font-size:13px;box-shadow:0 8px 20px rgba(207,169,41,.15)}
    .profile-promo-copy a:hover{filter:brightness(1.08);transform:translateY(-1px)}
    @media(max-width:820px){.topbar-inner{padding:12px 0;flex-wrap:wrap}.nav{justify-content:flex-start}.nav-search{order:5;width:min(100%,420px)}main.container{width:min(100% - 28px,1180px)}.profile-layout{grid-template-columns:1fr}.profile-promo{min-height:300px}.profile-promo-mark{width:84px;height:84px;right:28px;top:72px;font-size:34px}.profile-promo-copy{padding-top:112px}}
    @media(max-width:560px){.brand{font-size:18px}.nav{gap:3px}.nav a,.nav-logout{font-size:12px;padding:8px}.nav-search{width:100%}}
</style>
<footer>
    <div class="container footer-grid">
        <section class="footer-brand"><a class="brand" href="/Baitaplon/store.php?url=Storefront/Get_data">DailyMart <span style="color:#e3bd39">Premium</span></a><p>Chuỗi siêu thị tiện lợi hàng đầu, mang lại sản phẩm tốt và trải nghiệm mua sắm an tâm mỗi ngày.</p><div class="footer-social"><span><i class="fab fa-facebook-f"></i></span><span><i class="fab fa-instagram"></i></span><span><i class="fab fa-youtube"></i></span></div></section>
        <section><h3>Dịch vụ khách hàng</h3><a href="/Baitaplon/store.php?url=CustomerOrders/Get_data">Đơn hàng của tôi</a><a href="/Baitaplon/store.php?url=Cart/Get_data">Giỏ hàng</a><a href="/Baitaplon/store.php?url=CustomerAuth/Profile">Tài khoản</a></section>
        <section><h3>Khám phá DailyMart</h3><a href="/Baitaplon/store.php?url=Storefront/Get_data">Sản phẩm</a><a href="/Baitaplon/store.php?url=Storefront/Get_data#categories">Danh mục</a><a href="/Baitaplon/store.php?url=Storefront/Get_data#products">Gợi ý hôm nay</a></section>
        <section><h3>Mua sắm an tâm</h3><p>Thực phẩm và hàng tiêu dùng được tuyển chọn cho gia đình bạn.</p><a class="footer-shop" href="/Baitaplon/store.php?url=Storefront/Get_data">Bắt đầu mua sắm <i class="fas fa-arrow-right"></i></a></section>
    </div>
    <div class="container footer-bottom"><span>© 2026 DailyMart</span><span>Chọn lọc mỗi ngày · An tâm mua sắm</span></div>
</footer>
<style>
.footer-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1.2fr;gap:50px;padding:16px 0 34px}.footer-grid section{display:flex;flex-direction:column;align-items:flex-start;gap:10px}.footer-grid .brand{color:#f5f6f7}.footer-grid p{font-size:13px;line-height:1.7;color:#82909d;max-width:280px;margin:5px 0}.footer-grid h3{font-size:14px;color:#e3bd39;margin:4px 0 5px}.footer-grid section>a:not(.brand){font-size:13px;color:#a7b0b9}.footer-grid section>a:hover{color:#f1d25c}.footer-social{display:flex;gap:9px;margin-top:5px}.footer-social span{width:30px;height:30px;border-radius:50%;display:grid;place-items:center;background:#1b2127;color:#d7b437}.footer-shop{color:#e3bd39!important;font-weight:700;font-size:13px}.footer-bottom{border-top:1px solid #22282e;padding-top:18px;display:flex;justify-content:space-between;gap:18px;font-size:11px;color:#6f7c88}@media(max-width:760px){.footer-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:28px 20px}}@media(max-width:460px){.footer-grid{grid-template-columns:1fr 1fr}.footer-bottom{flex-direction:column}}
</style>
</body>
</html>
