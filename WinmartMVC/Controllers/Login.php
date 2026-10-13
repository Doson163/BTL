<?php
class Login extends Controller {
    function Index() {
        if (empty($_SESSION['login_csrf'])) $_SESSION['login_csrf']=bin2hex(random_bytes(32));
        $this->view('Master',['Page'=>'Login_v','LoginCsrf'=>$_SESSION['login_csrf'],'LoginError'=>$_SESSION['login_error'] ?? '']);
        unset($_SESSION['login_error']);
    }

    function Authentication() {
        if ($_SERVER['REQUEST_METHOD']!=='POST') { header('Location: /Baitaplon/Admin'); exit; }
        $token=(string)($_POST['csrf_token'] ?? '');
        if (!hash_equals((string)($_SESSION['login_csrf'] ?? ''),$token)) {
            $_SESSION['login_error']='Phiên đăng nhập không hợp lệ. Hãy thử lại.';
            header('Location: /Baitaplon/Admin'); exit;
        }
        $identity=trim((string)($_POST['username'] ?? ''));
        $password=(string)($_POST['password'] ?? '');
        $userModel=$this->model('User');
        $account=$identity!=='' && $password!=='' ? $userModel->CheckLogin($identity,$password) : false;
        if (!$account) {
            $_SESSION['login_error']='Tài khoản quản trị hoặc mật khẩu không đúng.';
            header('Location: /Baitaplon/Admin'); exit;
        }
        session_regenerate_id(true);
        unset($_SESSION['shop_customer']);
        $_SESSION['user_login']=$account;
        header('Location: /Baitaplon/'); exit;
    }

    function Logout() {
        unset($_SESSION['user_login']);
        header('Location: /Baitaplon/Admin'); exit;
    }
}
?>
