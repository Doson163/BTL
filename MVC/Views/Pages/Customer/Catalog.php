<?php $categoryRows = $data['categories'] ? mysqli_fetch_all($data['categories'], MYSQLI_ASSOC) : []; ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    
    /* Hero Banner */
    .hero-banner {
        font-family: 'Outfit', sans-serif;
        position: relative;
        width: 100%;
        height: 320px;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 40px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        background: #103b75;
        box-shadow: 0 15px 40px rgba(16, 59, 117, 0.2);
    }
    
    .hero-bg {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=2000');
        background-size: cover;
        background-position: center;
        opacity: 0.3;
        mix-blend-mode: overlay;
    }
    
    .hero-gradient {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(90deg, #103b75 0%, rgba(16,59,117,0.8) 50%, transparent 100%);
    }

    .hero-content {
        position: relative;
        z-index: 1;
        padding: 40px 50px;
        color: white;
        max-width: 600px;
    }

    .hero-badge {
        display: inline-block;
        background: #ff4b2b;
        color: white;
        padding: 6px 12px;
        border-radius: 99px;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 15px;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3);
    }

    .hero-content h2 {
        font-size: 42px;
        font-weight: 800;
        margin: 0 0 15px 0;
        line-height: 1.2;
        letter-spacing: -1px;
    }

    .hero-content p {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.9);
        margin: 0 0 25px 0;
        line-height: 1.5;
    }

    .hero-btn {
        background: white;
        color: #e31d2b;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .hero-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        background: #f8fafc;
    }

    /* Page Head (Section Title) */
    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Sleek Filter Panel */
    .filter-panel {
        font-family: 'Outfit', sans-serif;
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border-radius: 20px !important;
        padding: 24px !important;
        margin-bottom: 30px !important;
    }
    
    .filters input, .filters select {
        font-family: 'Outfit', sans-serif;
        border-radius: 12px !important;
        border: 1px solid #e5e7eb !important;
        padding: 12px 16px !important;
        font-size: 15px !important;
        transition: all 0.3s;
        background: #f9fafb !important;
        color: #374151;
    }
    .filters input:focus, .filters select:focus {
        border-color: #e31d2b !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(227, 29, 43, 0.1) !important;
        outline: none;
    }
    
    .filters .button {
        border-radius: 12px !important;
        background: linear-gradient(135deg, #e31d2b 0%, #ff4b2b 100%) !important;
        color: white !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(227, 29, 43, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .filters .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(227, 29, 43, 0.4) !important;
    }

    /* Premium Product Cards */
    .products {
        font-family: 'Outfit', sans-serif;
        gap: 24px !important;
    }
    
    .product-card {
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.04) !important;
        border-radius: 20px !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.02) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        border-color: rgba(227, 29, 43, 0.1) !important;
    }

    .product-image {
        height: 200px !important;
        background: #fdfdfd !important;
        border-bottom: 1px solid rgba(0,0,0,0.03);
        overflow: hidden;
        border-radius: 20px 20px 0 0;
        padding: 15px;
        display: grid;
        place-items: center;
    }
    
    .product-image img {
        width: 100%; height: 100%; object-fit: contain;
        transition: transform 0.5s ease;
        padding: 0 !important;
        mix-blend-mode: multiply;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.08);
    }

    .product-copy {
        padding: 20px !important;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .product-copy .muted {
        font-size: 12px;
        color: #9ca3af !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    
    .product-copy h2 {
        font-size: 17px !important;
        font-weight: 700 !important;
        color: #1f2937;
        margin: 0 0 10px 0 !important;
        line-height: 1.4 !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-copy h2 a {
        transition: color 0.2s;
    }
    .product-copy h2 a:hover {
        color: #e31d2b;
    }

    .product-copy .price {
        font-size: 20px !important;
        font-weight: 800 !important;
        color: #e31d2b !important;
        margin-bottom: 4px;
    }
    
    .product-copy .stock {
        font-size: 13px !important;
        font-weight: 500;
        color: #059669 !important;
        margin-bottom: 20px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .product-copy .stock.out {
        color: #dc2626 !important;
    }

    /* Fixed buttons to stack or flex nicely without text wrapping */
    .product-actions {
        display: flex;
        flex-direction: column; /* Stack vertically for more space */
        gap: 8px !important;
        margin-top: auto;
    }
    
    .product-actions .button {
        width: 100%;
        border-radius: 12px !important;
        padding: 10px !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        transition: all 0.3s !important;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }
    
    .product-actions .button.secondary {
        background: #f1f5f9 !important;
        color: #475569 !important;
    }
    .product-actions .button.secondary:hover {
        background: #e2e8f0 !important;
        color: #1e293b !important;
    }
    
    .product-actions form {
        margin: 0;
        width: 100%;
    }
    
    .product-actions form .button {
        background: linear-gradient(135deg, #e31d2b 0%, #ff4b2b 100%) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(227, 29, 43, 0.2) !important;
        border: none;
    }
    .product-actions form .button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(227, 29, 43, 0.35) !important;
    }
    .product-actions form .button:disabled {
        background: #e5e7eb !important;
        color: #9ca3af !important;
        box-shadow: none !important;
    }
    .premium-catalog{font-family:'Outfit',sans-serif}
    .premium-catalog .hero-banner{height:370px;border-radius:4px;background:#11171a;margin:0 -20px 54px;width:calc(100% + 40px);box-shadow:none}
    .premium-catalog .hero-bg{opacity:.32;mix-blend-mode:normal;filter:saturate(.8)}
    .premium-catalog .hero-gradient{background:linear-gradient(90deg,rgba(12,17,20,.98) 0%,rgba(12,17,20,.88) 48%,rgba(12,17,20,.22) 100%)}
    .premium-catalog .hero-content{padding:44px 20px;max-width:650px}
    .premium-catalog .hero-content h2{font-size:clamp(34px,4vw,52px);line-height:1.13;margin-bottom:16px}
    .premium-catalog .hero-content p{font-size:15px;color:#aab3bc;max-width:570px}
    .premium-catalog .hero-btn{padding:13px 24px;border-radius:9px;margin-right:10px}
    .premium-catalog .hero-btn-outline{display:inline-flex;align-items:center;padding:12px 22px;border:1px solid #41484f;border-radius:9px;color:#e7e9ec;font-weight:600;font-size:14px}
    .category-section{margin:0 0 82px}
    .section-heading{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
    .section-heading .section-title{font-size:23px;margin:0;color:#fff}
    .section-heading>a{color:#e6c13f;font-size:12px;font-weight:700}
    .category-grid{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:15px}
    .category-card{min-height:126px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:13px;background:#1a2026;border:1px solid #252c33;border-radius:17px;color:#f5f6f7;font-size:13px;font-weight:600;text-align:center;padding:16px 10px;transition:transform .2s,border-color .2s,background .2s}
    .category-card i{font-size:22px;color:#dfb92d}
    .category-card:hover{transform:translateY(-4px);border-color:#79651f;background:#20262c;color:#f4d45f}
    .premium-catalog .filter-panel{margin-bottom:30px!important}
    .premium-catalog .products{gap:20px!important}
    .premium-catalog .product-image{height:210px!important;background:#11161a!important;border-bottom-color:#252c33}
    .premium-catalog .product-image img{mix-blend-mode:normal;object-fit:cover;border-radius:14px}
    .premium-catalog .product-copy{padding:17px!important}
    .premium-catalog .product-copy .muted{color:#e3bd39!important}
    .premium-catalog .product-copy h2{color:#f3f5f6}
    .premium-catalog .product-copy .stock{color:#8dccaa!important}
    .premium-catalog .product-card:hover{border-color:#78631c!important;box-shadow:0 20px 42px rgba(0,0,0,.38)!important}
    @media(max-width:800px){.category-grid{grid-template-columns:repeat(3,minmax(0,1fr))}.premium-catalog .hero-banner{margin-left:-14px;width:calc(100% + 28px)}}
    @media(max-width:520px){.category-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.premium-catalog .hero-banner{height:360px}.premium-catalog .hero-content{padding:28px 20px}.premium-catalog .hero-content h2{font-size:34px}.premium-catalog .product-image{height:165px!important}}
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="premium-catalog">
<div class="hero-banner">
    <div class="hero-bg"></div>
    <div class="hero-gradient"></div>
    <div class="hero-content">
        <div class="hero-badge">Exclusive collection</div>
        <h2>Nâng Tầm Trải Nghiệm<br><em>Mua Sắm Thượng Lưu</em></h2>
        <p>Chào mừng bạn đến với không gian mua sắm tuyển chọn. Mỗi sản phẩm đều được chọn lọc kỹ cho phong cách sống chất lượng.</p>
        <button class="hero-btn" type="button" onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">Khám phá ngay <i class="fas fa-arrow-right"></i></button><a class="hero-btn-outline" href="#categories">Xem danh mục</a>
    </div>
</div>

<section id="categories" class="category-section">
    <div class="section-heading"><h2 class="section-title">Danh mục thượng phẩm</h2><a href="#products">Tất cả danh mục&nbsp; <i class="fas fa-chevron-right"></i></a></div>
    <div class="category-grid">
        <?php $categoryIcons=['fa-apple-whole','fa-wine-bottle','fa-bread-slice','fa-house','fa-spa','fa-mug-hot']; foreach($categoryRows as $index=>$category) { ?>
            <a class="category-card" href="store.php?url=Storefront/Get_data&amp;category=<?php echo (int)$category['MaDM']; ?>"><i class="fas <?php echo $categoryIcons[$index % count($categoryIcons)]; ?>"></i><span><?php echo htmlspecialchars($category['TenDM'],ENT_QUOTES,'UTF-8'); ?></span></a>
        <?php } ?>
    </div>
</section>

<form class="filter-panel filters" method="get" action="store.php">
    <input type="hidden" name="url" value="Storefront/Get_data">
    <input type="search" name="q" placeholder="Tìm tên hoặc mã sản phẩm..." value="<?php echo htmlspecialchars((string)$data['filters']['q'],ENT_QUOTES,'UTF-8'); ?>">
    <select name="category">
        <option value="">Tất cả danh mục</option>
        <?php foreach($categoryRows as $category) { ?>
            <option value="<?php echo (int)$category['MaDM']; ?>" <?php echo (string)$data['filters']['category']===(string)$category['MaDM']?'selected':''; ?>><?php echo htmlspecialchars($category['TenDM'],ENT_QUOTES,'UTF-8'); ?></option>
        <?php } ?>
    </select>
    <input type="number" min="0" step="1000" name="min_price" placeholder="Mức giá từ (VNĐ)" value="<?php echo htmlspecialchars((string)$data['filters']['min_price'],ENT_QUOTES,'UTF-8'); ?>">
    <input type="number" min="0" step="1000" name="max_price" placeholder="Mức giá đến (VNĐ)" value="<?php echo htmlspecialchars((string)$data['filters']['max_price'],ENT_QUOTES,'UTF-8'); ?>">
    <select name="sort">
        <option value="">Sắp xếp mặc định</option>
        <option value="price_asc" <?php echo $data['filters']['sort']==='price_asc'?'selected':''; ?>>Giá: Thấp đến Cao</option>
        <option value="price_desc" <?php echo $data['filters']['sort']==='price_desc'?'selected':''; ?>>Giá: Cao đến Thấp</option>
        <option value="name" <?php echo $data['filters']['sort']==='name'?'selected':''; ?>>Tên: A đến Z</option>
    </select>
    <button class="button" type="submit"><i class="fas fa-filter"></i> Lọc</button>
</form>

<h2 id="products" class="section-title"><i class="fas fa-sparkles" style="color: #e3bd39;"></i> Gợi ý riêng cho bạn</h2>

<?php if($data['products'] && mysqli_num_rows($data['products'])>0) { ?>
    <div class="products">
        <?php while($product=mysqli_fetch_assoc($data['products'])) { ?>
            <article class="product-card">
                <a class="product-image" href="store.php?url=Storefront/ChiTiet/<?php echo rawurlencode($product['MaSP']); ?>">
                    <?php if(!empty($product['HinhAnh'])) { ?>
                        <img src="Public/Images/<?php echo rawurlencode($product['HinhAnh']); ?>" alt="<?php echo htmlspecialchars($product['TenSP'],ENT_QUOTES,'UTF-8'); ?>">
                    <?php } else { ?>
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:#cbd5e1;">
                            <i class="fas fa-image" style="font-size:40px;margin-bottom:10px;"></i>
                            <span class="muted" style="font-size:14px;font-weight:500;">Chưa có hình ảnh</span>
                        </div>
                    <?php } ?>
                </a>
                <div class="product-copy">
                    <div class="muted"><i class="fas fa-tag" style="margin-right:4px;"></i> <?php echo htmlspecialchars($product['TenDM'] ?? 'Chưa phân loại',ENT_QUOTES,'UTF-8'); ?></div>
                    <h2><a href="store.php?url=Storefront/ChiTiet/<?php echo rawurlencode($product['MaSP']); ?>"><?php echo htmlspecialchars($product['TenSP'],ENT_QUOTES,'UTF-8'); ?></a></h2>
                    <div class="price"><?php echo number_format((float)$product['GiaBan'],0,',','.'); ?> &#8363;</div>
                    
                    <?php if((int)$product['SoLuongTon']>0) { ?>
                        <div class="stock"><i class="fas fa-check-circle"></i> Sẵn hàng (<?php echo (int)$product['SoLuongTon']; ?>)</div>
                    <?php } else { ?>
                        <div class="stock out"><i class="fas fa-times-circle"></i> Hết hàng tạm thời</div>
                    <?php } ?>
                    
                    <div class="product-actions">
                        <?php if (!empty($_SESSION['shop_customer']['MaKH'])) { ?>
                        <form method="post" action="store.php?url=Cart/Add/<?php echo rawurlencode($product['MaSP']); ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'] ?? '',ENT_QUOTES,'UTF-8'); ?>">
                            <button class="button" type="submit" <?php echo (int)$product['SoLuongTon']<1?'disabled':''; ?>><i class="fas fa-cart-plus"></i> Thêm vào giỏ</button>
                        </form>
                        <?php } else { ?>
                        <a class="button" href="/Baitaplon/User"><i class="fas fa-sign-in-alt"></i> Đăng nhập để mua</a>
                        <?php } ?>
                        <a class="button secondary" href="store.php?url=Storefront/ChiTiet/<?php echo rawurlencode($product['MaSP']); ?>"><i class="fas fa-eye"></i> Xem chi tiết</a>
                    </div>
                </div>
            </article>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="empty" style="padding: 60px; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <i class="fas fa-search" style="font-size: 48px; color: #cbd5e1; margin-bottom: 20px;"></i>
        <h3 style="margin: 0 0 10px 0; color: #1e293b; font-size: 20px; font-family: 'Outfit', sans-serif;">Không tìm thấy sản phẩm</h3>
        <p style="margin: 0; color: #64748b; font-family: 'Outfit', sans-serif;">Thử thay đổi từ khóa hoặc bộ lọc để xem các kết quả khác nhé.</p>
    </div>
<?php } ?>
</div>
