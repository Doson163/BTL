<div class="page-head"><div><h1>H&#7891; s&#417; c&#7911;a t&#244;i</h1><div class="muted">C&#7853;p nh&#7853;t th&#244;ng tin li&#234;n h&#7879; v&#224; &#273;&#7883;a ch&#7881; nh&#7853;n h&#224;ng.</div></div></div>
<div class="profile-layout">
    <section class="panel profile-panel">
        <form method="post" action="store.php?url=CustomerAuth/SaveProfile">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
            <label for="name">H&#7885; v&#224; t&#234;n</label>
            <input id="name" name="name" required value="<?php echo htmlspecialchars($data['profile']['TenKH'],ENT_QUOTES,'UTF-8'); ?>">
            <label for="phone">S&#7889; &#273;i&#7879;n tho&#7841;i</label>
            <input id="phone" name="phone" required value="<?php echo htmlspecialchars($data['profile']['DienThoai'],ENT_QUOTES,'UTF-8'); ?>">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($data['profile']['Email'] ?? '',ENT_QUOTES,'UTF-8'); ?>">
            <h2>&#272;&#7883;a ch&#7881; giao h&#224;ng m&#7863;c &#273;&#7883;nh</h2>
            <label for="recipient">Ng&#432;&#7901;i nh&#7853;n</label>
            <input id="recipient" name="recipient" value="<?php echo htmlspecialchars($data['profile']['TenNguoiNhan'] ?? '',ENT_QUOTES,'UTF-8'); ?>">
            <label for="recipient_phone">S&#7889; &#273;i&#7879;n tho&#7841;i ng&#432;&#7901;i nh&#7853;n</label>
            <input id="recipient_phone" name="recipient_phone" value="<?php echo htmlspecialchars($data['profile']['DienThoaiNhan'] ?? '',ENT_QUOTES,'UTF-8'); ?>">
            <label for="address">&#272;&#7883;a ch&#7881;</label>
            <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($data['profile']['DiaChiMacDinh'] ?? '',ENT_QUOTES,'UTF-8'); ?></textarea>
            <button class="button" style="margin-top:18px" type="submit">L&#432;u h&#7891; s&#417;</button>
        </form>
        <form method="post" action="/Baitaplon/User/Logout" style="margin-top:12px">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'],ENT_QUOTES,'UTF-8'); ?>">
            <button class="button danger" type="submit">&#272;&#259;ng xu&#7845;t</button>
        </form>
    </section>
    <aside class="profile-promo" aria-label="Giới thiệu DailyMart Premium">
        <span class="profile-promo-kicker"><i class="fas fa-sparkles"></i> DAILYMART PREMIUM</span>
        <div class="profile-promo-mark"><i class="fas fa-basket-shopping"></i></div>
        <div class="profile-promo-copy">
            <h2>Chọn điều tốt<br>cho gia đình</h2>
            <p>Sản phẩm thiết yếu được tuyển chọn kỹ, giao đến tận nhà mỗi ngày.</p>
            <a href="/Baitaplon/store.php?url=Storefront/Get_data">Khám phá mua sắm <i class="fas fa-arrow-right"></i></a>
        </div>
    </aside>
</div>
