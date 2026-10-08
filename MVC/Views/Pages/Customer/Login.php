<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .login-wrapper {
        font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        /* Break out of container */
        width: 100vw;
        margin-left: -50vw;
        left: 50%;
        position: relative;
        margin-top: -28px;
        margin-bottom: -60px;
        min-height: calc(100vh - 68px); /* Minus topbar */
        
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
        overflow: hidden;
    }

    /* Subtle animated background shapes */
    .login-wrapper::before,
    .login-wrapper::after {
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
    .login-wrapper::after {
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
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        padding: 40px 50px;
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
        margin-bottom: 35px;
    }

    .login-header h2 {
        color: #1a1a1a;
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px 0;
        letter-spacing: -0.5px;
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

    /* Floating Labels Styling */
    .floating-group { position: relative; margin-top: 15px; margin-bottom: 24px; }

    .floating-input {
        width: 100%;
        padding: 16px 20px 16px 52px;
        font-size: 16px;
        font-family: inherit;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-sizing: border-box;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        color: #1a1a1a;
        font-weight: 500;
    }

    .floating-input:focus {
        background: #ffffff;
        border-color: #e31d2b;
        outline: none;
        box-shadow: 0 8px 24px rgba(227, 29, 43, 0.1);
    }

    .floating-label {
        position: absolute;
        left: 52px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #64748b;
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
        font-size: 14px;
        color: #e31d2b;
        font-weight: 700;
        background: #ffffff;
        padding: 0 8px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transform: translateY(0);
    }

    .floating-input:focus ~ .floating-icon {
        color: #e31d2b;
    }

    .btn-login {
        width: 100%;
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
        background: linear-gradient(135deg, #e31d2b 0%, #ff4b2b 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(227, 29, 43, 0.25);
        margin-top: 25px;
    }

    .btn-login::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(rgba(255,255,255,0.2), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .btn-login:hover {
        box-shadow: 0 12px 25px rgba(227, 29, 43, 0.35);
        transform: translateY(-2px);
    }

    .btn-login:hover::after {
        opacity: 1;
    }

    .btn-login:active {
        transform: scale(0.96);
    }

    .register-link {
        text-align: center;
        margin-top: 30px;
        font-size: 16px;
        color: #475569;
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

<!-- Bao g?m thu vi?n FontAwesome n?u CustomerMaster chua có -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <h2>ÐANG NH?P <span>.</span></h2>
            <p>Chào m?ng b?n quay tr? l?i WinMart</p>
        </div>

        <?php if(!empty($data['notice'])) { ?>
            <div style="background: rgba(254, 242, 242, 0.9); color: #b91c1c; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: 500; border: 1px solid rgba(239, 68, 68, 0.3); text-align: center;">
                <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>
                <?php echo htmlspecialchars($data['notice'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <form method="post" action="store.php?url=CustomerAuth/Login">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['shop_csrf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            
            <div class="floating-group">
                <input id="identity" type="text" name="identity" class="floating-input" placeholder=" " required autocomplete="username">
                <label class="floating-label" for="identity">S? di?n tho?i ho?c Email</label>
                <i class="fas fa-user floating-icon"></i>
            </div>
            
            <div class="floating-group">
                <input id="password" type="password" name="password" class="floating-input" placeholder=" " required autocomplete="current-password">
                <label class="floating-label" for="password">M?t kh?u</label>
                <i class="fas fa-lock floating-icon"></i>
            </div>
            
            <button class="btn-login" type="submit">
                Ðang nh?p
            </button>
        </form>

        <div class="register-link">
            Chua có tài kho?n? <a href="store.php?url=CustomerAuth/Register">Ðang ký t?i dây</a>
        </div>
    </div>
</div>
