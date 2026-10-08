<div class="container" style="justify-content:center;padding:50px 0;">
  <div style="background:white;padding:40px;border-radius:8px;width:480px;max-width:calc(100% - 30px);box-shadow:0 0 15px rgba(0,0,0,.1);">
    <h2 style="text-align:center;color:#e31d2b;margin-top:0;">&#272;&#258;NG NH&#7852;P</h2>
    <?php if(!empty($data['LoginError'])) { ?><div role="alert" style="margin:0 0 16px;padding:10px;background:#fff0f0;color:#a71923;border-radius:4px;"><?php echo htmlspecialchars($data['LoginError'],ENT_QUOTES,'UTF-8'); ?></div><?php } ?>
    <form action="<?php echo BASE_URL; ?>Login/Authentication" method="POST">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['LoginCsrf'],ENT_QUOTES,'UTF-8'); ?>">
      <div style="margin-bottom:18px;"><label for="username" style="font-weight:bold;display:block;margin-bottom:6px;">Email ho&#7863;c s&#7889; &#273;i&#7879;n tho&#7841;i &#273;&#227; &#273;&#259;ng k&#253;</label><input id="username" type="text" name="username" autocomplete="username" required style="width:100%;padding:11px;border:1px solid #ddd;border-radius:4px;box-sizing:border-box;"><small style="display:block;margin-top:6px;color:#666;">N&#7871;u l&#250;c &#273;&#259;ng k&#253; b&#7887; tr&#7889;ng email, h&#227;y d&#249;ng s&#7889; &#273;i&#7879;n tho&#7841;i.</small></div>
      <div style="margin-bottom:22px;"><label for="password" style="font-weight:bold;display:block;margin-bottom:6px;">M&#7853;t kh&#7849;u</label><input id="password" type="password" name="password" autocomplete="current-password" required style="width:100%;padding:11px;border:1px solid #ddd;border-radius:4px;box-sizing:border-box;"></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;overflow:hidden;border-radius:8px;">
        <button type="submit" name="role" value="customer" style="padding:14px 10px;background:#0879ed;color:white;border:0;font-weight:bold;font-size:16px;cursor:pointer;">Kh&#225;ch h&#224;ng</button>
        <button type="submit" name="role" value="manager" style="padding:14px 10px;background:#08b43b;color:white;border:0;font-weight:bold;font-size:16px;cursor:pointer;">Qu&#7843;n l&#253;</button>
      </div>
    </form>
    <p style="text-align:center;margin:18px 0 0;color:#666;">Ch&#432;a c&#243; m&#7853;t kh&#7849;u? <a href="http://localhost/BaitaplonSale/store.php?url=CustomerAuth/Register" style="color:#1456a0;font-weight:bold;text-decoration:none;">&#272;&#259;ng k&#253; t&#224;i kho&#7843;n kh&#225;ch h&#224;ng</a></p>
  </div>
</div>
