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
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="hero-banner">
    <div class="hero-bg"></div>
    <div class="hero-gradient"></div>
    <div class="hero-content">
        <div class="hero-badge"><i class="fas fa-bolt"></i> Siêu ưu đãi</div>
        <h2>Đại tiệc Mua sắm<br>Tại WinMart</h2>
        <p>Hàng ngàn sản phẩm chính hãng, tươi sạch mỗi ngày với mức giá ưu đãi nhất dành riêng cho bạn.</p>
        <button class="hero-btn" type="button" onclick="window.scrollTo({top: 400, behavior: 'smooth'})">Khám phá ngay <i class="fas fa-arrow-right"></i></button>
    </div>
</div>

<form class="filter-panel filters" method="get" action="store.php">
    <input type="hidden" name="url" value="Storefront/Get_data">
    <input type="search" name="q" placeholder="Tìm tên hoặc mã sản phẩm..." value="<?php echo htmlspecialchars((string)$data['filters']['q'],ENT_QUOTES,'UTF-8'); ?>">
    <select name="category">
        <option value="">Tất cả danh mục</option>
        <?php if($data['categories']) { while($category=mysqli_fetch_assoc($data['categories'])) { ?>
            <option value="<?php echo (int)$category['MaDM']; ?>" <?php echo (string)$data['filters']['category']===(string)$category['MaDM']?'selected':''; ?>><?php echo htmlspecialchars($category['TenDM'],ENT_QUOTES,'UTF-8'); ?></option>
        <?php }} ?>
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

<h2 class="section-title"><i class="fas fa-fire" style="color: #e31d2b;"></i> Sản phẩm nổi bật</h2>

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
                        <form method="post" action="store.php?url=Cart/Add/<?php echo rawurlencode($product['MaSP']); ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'] ?? '',ENT_QUOTES,'UTF-8'); ?>">
                            <button class="button" type="submit" <?php echo (int)$product['SoLuongTon']<1?'disabled':''; ?>><i class="fas fa-cart-plus"></i> Thêm vào giỏ</button>
                        </form>
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
