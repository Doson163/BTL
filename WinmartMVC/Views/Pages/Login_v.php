<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    /* Reset body to full-screen */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .adminlogin-wrapper {
        width: 100vw;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
        box-sizing: border-box;
        position: relative;

        /* Same vibrant mesh gradient as store pages */
        background-color: #ffdde1;
        background-image:
            radial-gradient(at 40% 20%, hsla(353,100%,74%,0.8) 0px, transparent 50%),
            radial-gradient(at 80% 0%, hsla(189,100%,56%,0.15) 0px, transparent 50%),
            radial-gradient(at 0% 50%, hsla(355,100%,93%,1) 0px, transparent 50%),
            radial-gradient(at 80% 50%, hsla(340,100%,76%,0.7) 0px, transparent 50%),
            radial-gradient(at 0% 100%, hsla(22,100%,77%,0.6) 0px, transparent 50%),
            radial-gradient(at 80% 100%, hsla(242,100%,70%,0.1) 0px, transparent 50%),
            radial-gradient(at 0% 0%, hsla(343,100%,76%,0.5) 0px, transparent 50%);
        background-size: cover;
        background-position: center;
        overflow: hidden;
    }

    /* Animated blobs */
    .adminlogin-wrapper::before,
    .adminlogin-wrapper::after {
        content: '';
        position: absolute;
        width: 40vw;
        height: 40vw;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(227,29,43,0.2), rgba(255,117,140,0.2));
        filter: blur(60px);
        animation: al-float 10s infinite ease-in-out alternate;
        z-index: 0;
    }
    .adminlogin-wrapper::after {
        width: 30vw;
        height: 30vw;
        bottom: -10vw;
        right: -10vw;
        background: linear-gradient(135deg, rgba(255,167,81,0.2), rgba(255,226,89,0.2));
        animation-duration: 12s;
        animation-delay: -5s;
    }

    @keyframes al-float {
        0%   { transform: translate(0, 0) rotate(0deg); }
        100% { transform: translate(50px, 50px) rotate(20deg); }
    }

    .adminlogin-card {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        padding: 48px 52px;
        border-radius: 32px;
        width: 100%;
        max-width: 500px;
        box-shadow:
            0 30px 60px rgba(0,0,0,0.08),
            0 0 0 1px rgba(255,255,255,0.6) inset;
        border: 1px solid rgba(255,255,255,0.8);
        position: relative;
        z-index: 1;
        transition: transform 0.4s cubic-bezier(0.175,0.885,0.32,1.275);
    }

    .adminlogin-card:hover {
        transform: translateY(-5px);
    }

    .adminlogin-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .adminlogin-header h2 {
        color: #1a1a1a;
        font-size: 34px;
        font-weight: 800;
        margin: 0 0 10px 0;
        letter-spacing: -0.5px;
    }

    .adminlogin-header h2 span { color: #e31d2b; }

    .adminlogin-header p {
        color: #555;
        font-size: 16px;
        margin: 0;
        font-weight: 400;
    }

    /* Error alert */
    .adminlogin-error {
        background: rgba(254,242,242,0.9);
        color: #b91c1c;
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 500;
        border: 1px solid rgba(239,68,68,0.3);
        text-align: center;
        font-size: 15px;
    }

    /* Floating label groups */
    .al-group {
        position: relative;
        margin-bottom: 24px;
    }

    .al-input {
        width: 100%;
        padding: 16px 20px 16px 52px;
        font-size: 16px;
        font-family: inherit;
        background: rgba(255,255,255,0.9);
        border: 2px solid rgba(226,232,240,0.8);
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        box-sizing: border-box;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        color: #1a1a1a;
        font-weight: 500;
    }

    .al-input:focus {
        background: #ffffff;
        border-color: #e31d2b;
        outline: none;
        box-shadow: 0 8px 24px rgba(227,29,43,0.1);
    }

    .al-label {
        position: absolute;
        left: 52px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 15px;
        color: #64748b;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        font-weight: 500;
    }

    .al-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 17px;
        transition: all 0.3s;
    }

    .al-input:focus ~ .al-label,
    .al-input:not(:placeholder-shown) ~ .al-label {
        top: -12px;
        left: 15px;
        font-size: 13px;
        color: #e31d2b;
        font-weight: 700;
        background: #ffffff;
        padding: 0 8px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transform: translateY(0);
    }

    .al-input:focus ~ .al-icon { color: #e31d2b; }

    /* Role buttons row */
    .al-role-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 28px;
    }

    .al-btn {
        padding: 16px 10px;
        border: none;
        border-radius: 16px;
        font-weight: 700;
        font-size: 16px;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
        color: white;
    }

    .al-btn-customer {
        background: linear-gradient(135deg, #e31d2b 0%, #ff4b2b 100%);
        box-shadow: 0 8px 20px rgba(227,29,43,0.25);
    }

    .al-btn-manager {
        background: linear-gradient(135deg, #c0111f 0%, #d81b26 100%);
        box-shadow: 0 8px 20px rgba(192,17,31,0.25);
    }

    .al-btn::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(rgba(255,255,255,0.18), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .al-btn:hover {
        transform: translateY(-2px);
    }

    .al-btn-customer:hover { box-shadow: 0 12px 25px rgba(227,29,43,0.38); }
    .al-btn-manager:hover  { box-shadow: 0 12px 25px rgba(192,17,31,0.38); }

    .al-btn:hover::after { opacity: 1; }
    .al-btn:active { transform: scale(0.96); }

    /* Register link */
    .al-register-link {
        text-align: center;
        margin-top: 28px;
        font-size: 15px;
        color: #475569;
        font-weight: 500;
    }

    .al-register-link a {
        color: #e31d2b;
        font-weight: 700;
        text-decoration: none;
        position: relative;
    }

    .al-register-link a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #e31d2b;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
    }

    .al-register-link a:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }

    /* Match the dark charcoal and gold palette used by the admin workspace. */
    .adminlogin-wrapper {
        background-color: #101417;
        background-image:
            radial-gradient(ellipse at 50% 0%, rgba(205,169,47,.13), transparent 48%),
            linear-gradient(135deg, #101417, #0b0f12);
    }
    .adminlogin-wrapper::before {
        background: linear-gradient(135deg, rgba(223,185,54,.12), rgba(223,185,54,.02));
    }
    .adminlogin-wrapper::after {
        background: linear-gradient(135deg, rgba(223,185,54,.09), rgba(30,39,42,.1));
    }
    .adminlogin-card {
        background: rgba(23,28,32,.96);
        border: 1px solid #2a3238;
        border-radius: 24px;
        box-shadow: 0 28px 70px rgba(0,0,0,.42), 0 0 0 1px rgba(223,185,54,.04) inset;
    }
    .adminlogin-header h2 { color: #f1f3f4; }
    .adminlogin-header h2 span { color: #dfb936; }
    .adminlogin-header p,.al-register-link { color: #aab4be; }
    .al-input {
        background: #11171c;
        border: 1px solid #39434c;
        color: #f1f3f5;
        box-shadow: none;
    }
    .al-input:focus {
        background: #11171c;
        border-color: #d9b63b;
        box-shadow: 0 0 0 3px rgba(217,182,59,.14);
    }
    .al-label { color: #87939e; }
    .al-input:focus ~ .al-label,
    .al-input:not(:placeholder-shown) ~ .al-label {
        color: #e4c342;
        background: #171c20;
        box-shadow: none;
    }
    .al-icon { color: #87939e; }
    .al-input:focus ~ .al-icon { color: #e4c342; }
    .al-btn-manager {
        background: linear-gradient(135deg, #f0cf56 0%, #c99e1d 100%);
        color: #171a1c;
        box-shadow: 0 8px 22px rgba(207,169,41,.18);
    }
    .al-btn-manager:hover { box-shadow: 0 12px 28px rgba(207,169,41,.28); }
    .adminlogin-error {
        background: rgba(112,35,36,.2);
        color: #ff9999;
        border-color: rgba(239,95,95,.34);
    }
    @media(max-width:560px) {
        .adminlogin-wrapper { padding: 20px 14px; }
        .adminlogin-card { padding: 34px 24px; border-radius: 20px; }
        .adminlogin-header h2 { font-size: 27px; }
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="adminlogin-wrapper">
    <div class="adminlogin-card">
        <div class="adminlogin-header">
            <h2>ĐĂNG NHẬP QUẢN TRỊ <span>.</span></h2>
            <p>Khu vực dành cho quản trị viên</p>
        </div>

        <?php if(!empty($data['LoginError'])) { ?>
        <div class="adminlogin-error">
            <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>
            <?php echo htmlspecialchars($data['LoginError'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php } ?>

        <form action="/Baitaplon/Admin/Authentication" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['LoginCsrf'], ENT_QUOTES, 'UTF-8'); ?>">

            <div class="al-group">
                <input id="username" type="text" name="username" class="al-input" placeholder=" " required autocomplete="username">
                <label class="al-label" for="username">Tài khoản quản trị</label>
                <i class="fas fa-user al-icon"></i>
            </div>

            <div class="al-group">
                <input id="password" type="password" name="password" class="al-input" placeholder=" " required autocomplete="current-password">
                <label class="al-label" for="password">Mật khẩu</label>
                <i class="fas fa-lock al-icon"></i>
            </div>

            <button type="submit" class="al-btn al-btn-manager" style="width:100%;margin-top:20px">
                <i class="fas fa-user-shield"></i> Đăng nhập quản trị
            </button>
        </form>
    </div>
</div>
