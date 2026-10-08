<style>
    

    .login-page-wrapper, .login-page-wrapper * { font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important; } 
    .login-page-wrapper {
        font-family: 'Outfit', sans-serif;
        min-height: calc(100vh - 70px);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
        box-sizing: border-box;
        /* Vibrant, Premium Mesh Gradient Background */
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
        position: relative;
        overflow: hidden;
    }

    /* Subtle animated background shapes */
    .login-page-wrapper::before,
    .login-page-wrapper::after {
        content: '';
        position: absolute;
        width: 40vw;
        height: 40vw;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(227, 29, 43, 0.2), rgba(255, 117, 140, 0.2));
        filter: blur(60px);
        animation: float 10s infinite ease-in-out alternate;
        z-index: 0;
    }
    .login-page-wrapper::after {
        width: 30vw;
        height: 30vw;
        bottom: -10vw;
        right: -10vw;
        background: linear-gradient(135deg, rgba(255, 167, 81, 0.2), rgba(255, 226, 89, 0.2));
        animation-duration: 12s;
        animation-delay: -5s;
    }

    @keyframes float {
        0% { transform: translate(0, 0) rotate(0deg); }
        100% { transform: translate(50px, 50px) rotate(20deg); }
    }

    .login-card {
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        padding: 50px;
        border-radius: 32px;
        width: 100%;
        max-width: 480px;
        box-shadow: 
            0 30px 60px rgba(0, 0, 0, 0.08), 
            0 0 0 1px rgba(255, 255, 255, 0.6) inset;
        border: 1px solid rgba(255, 255, 255, 0.8);
        position: relative;
        z-index: 1;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .login-card:hover {
        transform: translateY(-5px);
    }

    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .login-header h2 {
        color: #1a1a1a;
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px 0;
        letter-spacing: -1px;
    }
    
    .login-header h2 span {
        color: #e31d2b;
    }

    .login-header p {
        color: #555;
        font-size: 16px;
        margin: 0;
        font-weight: 400;
    }

    .login-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding: 16px;
        background: rgba(254, 242, 242, 0.9);
        color: #b91c1c;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid rgba(239, 68, 68, 0.3);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
        animation: shake 0.5s ease-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }

    /* Floating Labels Styling */
    .floating-group { position: relative; margin-top: 15px; margin-bottom: 24px; }

    .floating-input {
        width: 100%;
        padding: 16px 20px 16px 52px;
        font-size: 16px;
        font-family: inherit;
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-sizing: border-box;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02) inset, 0 4px 15px rgba(0,0,0,0.03);
        color: #1a1a1a;
        font-weight: 500;
    }

    .floating-input:focus {
        background: #ffffff;
        border-color: #e31d2b;
        outline: none;
        box-shadow: 0 8px 24px rgba(227, 29, 43, 0.15);
    }

    .floating-label {
        position: absolute;
        left: 52px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 15px;
        color: #888;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
    }

    .floating-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 18px;
        transition: all 0.3s;
    }

    .floating-input:focus ~ .floating-label,
    .floating-input:not(:placeholder-shown) ~ .floating-label {
        top: -12px;
        left: 15px;
        font-size: 13px;
        color: #e31d2b;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: rgba(255, 255, 255, 0.9);
        padding: 0 8px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transform: translateY(0);
    }

    .floating-input:focus ~ .floating-icon {
        color: #e31d2b;
    }

    .input-hint {
        display: block;
        margin-top: 8px;
        color: #666;
        font-size: 13px;
        padding-left: 10px;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 35px;
    }

    .btn {
        padding: 16px 20px;
        border: none;
        border-radius: 16px;
        font-weight: 700;
        font-size: 16px;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        position: relative;
        overflow: hidden;
    }
    
    .btn::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(rgba(255,255,255,0.2), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .btn:hover::after {
        opacity: 1;
    }

    .btn:active {
        transform: scale(0.96);
    }

    .btn-customer {
        background: linear-gradient(135deg, #e31d2b 0%, #ff4b2b 100%);
        color: white;
        box-shadow: 0 10px 20px rgba(227, 29, 43, 0.3);
    }
    
    .btn-customer:hover {
        box-shadow: 0 15px 30px rgba(227, 29, 43, 0.4);
        transform: translateY(-3px);
    }

    .btn-manager {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: white;
        box-shadow: 0 10px 20px rgba(17, 24, 39, 0.2);
    }

    .btn-manager:hover {
        box-shadow: 0 15px 30px rgba(17, 24, 39, 0.3);
        transform: translateY(-3px);
    }

    .register-link {
        text-align: center;
        margin-top: 35px;
        font-size: 15px;
        color: #555;
        font-weight: 500;
    }

    .register-link a {
        color: #e31d2b;
        font-weight: 700;
        text-decoration: none;
        position: relative;
    }
    
    .register-link a::after {
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

    .register-link a:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
</style>

<div class="login-page-wrapper">
    <div class="login-card">
        <div class="login-header">
            <h2>ĐĂNG NHẬP <span>.</span></h2>
            
        </div>

        <?php if(!empty($data['LoginError'])) { ?>
            <div class="login-alert" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo htmlspecialchars($data['LoginError'],ENT_QUOTES,'UTF-8'); ?></span>
            </div>
        <?php } ?>

        <form action="<?php echo BASE_URL; ?>Login/Authentication" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['LoginCsrf'],ENT_QUOTES,'UTF-8'); ?>">
            
            <div class="floating-group">
                <input id="username" type="text" name="username" class="floating-input" placeholder=" " autocomplete="username" required>
                <label class="floating-label" for="username">Email hoặc số điện thoại</label>
                <i class="fas fa-user floating-icon"></i>
            </div>
            
            <div class="floating-group">
                <input id="password" type="password" name="password" class="floating-input" placeholder=" " autocomplete="current-password" required>
                <label class="floating-label" for="password">Mật khẩu truy cập</label>
                <i class="fas fa-lock floating-icon"></i>
            </div>

            <div class="action-buttons">
                <button type="submit" name="role" value="customer" class="btn btn-customer">
                    Khách hàng
                </button>
                <button type="submit" name="role" value="manager" class="btn btn-manager">
                    Quản lý
                </button>
            </div>
        </form>

        <div class="register-link">
            <a href="http://localhost/Baitaplon/store.php?url=CustomerAuth/Register">Đăng ký tài khoản khách hàng</a>
        </div>
    </div>
</div>


