<?php
class Login extends Controller {
    function Index() {
        if (empty($_SESSION['login_csrf'])) $_SESSION['login_csrf']=bin2hex(random_bytes(32));
        $this->view('Master',['Page'=>'Login_v','LoginCsrf'=>$_SESSION['login_csrf'],'LoginError'=>$_SESSION['login_error'] ?? '']);
        unset($_SESSION['login_error']);
    }
    function Authentication() {
        if ($_SERVER['REQUEST_METHOD']!=='POST') { header('Location: '.BASE_URL.'Login'); exit; }
        $token=(string)($_POST['csrf_token'] ?? '');
        if (!hash_equals((string)($_SESSION['login_csrf'] ?? ''),$token)) { $_SESSION['login_error']='Phien dang nhap khong hop le. Hay thu lai.'; header('Location: '.BASE_URL.'Login'); exit; }
        $role=(string)($_POST['role'] ?? '');
        $identity=trim((string)($_POST['username'] ?? ''));
        $password=(string)($_POST['password'] ?? '');
        $userModel=$this->model('User');
        if ($role==='customer') {
            $account=$identity!=='' ? $userModel->CheckCustomerLogin($identity) : null;
            if (!$account || !password_verify($password,$account['MatKhauHash'])) {
                $_SESSION['login_error']='Email/phone or password is incorrect. Use the email or phone entered at registration.';
                header('Location: '.BASE_URL.'Login'); exit;
            }
            session_regenerate_id(true);
            unset($_SESSION['user_login']);
            $_SESSION['shop_customer']=['MaKH'=>$account['MaKH'],'TenKH'=>$account['TenKH']];
            header('Location: http://localhost/BaitaplonSale/'); exit;
        }
        if ($role==='manager') {
            $account=$identity!=='' && $password!=='' ? $userModel->CheckLogin($identity,$password) : false;
            if (!$account) {
                $_SESSION['login_error']='Tai khoan quan ly hoac mat khau khong dung.';
                header('Location: '.BASE_URL.'Login'); exit;
            }
            session_regenerate_id(true);
            unset($_SESSION['shop_customer']);
            $_SESSION['user_login']=$account;
            header('Location: http://localhost/Baitaplon/'); exit;
        }
        $_SESSION['login_error']='Hay chon Khach hang hoac Quan ly.';
        header('Location: '.BASE_URL.'Login'); exit;
    }
    function Logout() {
        unset($_SESSION['user_login']);
        header('Location: '.BASE_URL.'Login'); exit;
    }
}
?>
