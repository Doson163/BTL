<?php
class CustomerAuth extends controller {
    private $accountModel;
    public function __construct() { $this->accountModel = $this->model('CustomerAccountModel'); }
    private function validPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed.'); }
        if (!hash_equals((string)($_SESSION['shop_csrf'] ?? ''), (string)($_POST['csrf_token'] ?? ''))) { http_response_code(403); exit('Invalid session. Reload the page.'); }
    }
    private function showForm($page, $message = '') {
        $this->view('CustomerMaster', ['page'=>'Customer/'.$page,'notice'=>$message,'cartCount'=>array_sum($_SESSION['shop_cart'] ?? [])]);
    }
    private function requireCustomer() {
        if (empty($_SESSION['shop_customer']['MaKH'])) { header('Location: store.php?url=CustomerAuth/Login'); exit; }
    }
    public function Register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showForm('Register'); return; }
        $this->validPost();
        $name=trim((string)($_POST['name'] ?? '')); $phone=trim((string)($_POST['phone'] ?? ''));
        $email=strtolower(trim((string)($_POST['email'] ?? ''))); $password=(string)($_POST['password'] ?? '');
        if ($name==='' || $phone==='' || !preg_match('/^[0-9+(). -]{8,20}$/',$phone) || ($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL)) || strlen($password)<8) { $this->showForm('Register','Please check your name, phone, email, and password (at least 8 characters).'); return; }
        $email=$email===''?null:$email; $customerId='KH'.strtoupper(bin2hex(random_bytes(6)));
        if (!$this->accountModel->Register($name,$phone,$email,password_hash($password,PASSWORD_DEFAULT),$customerId)) { $this->showForm('Register','Phone or email is already in use.'); return; }
        session_regenerate_id(true); $_SESSION['shop_customer']=['MaKH'=>$customerId,'TenKH'=>$name];
        header('Location: store.php?url=CustomerAuth/Profile'); exit;
    }
    public function Login() {
        if ($_SERVER['REQUEST_METHOD']!=='POST') { $this->showForm('Login'); return; }
        $this->validPost(); $identity=trim((string)($_POST['identity'] ?? ''));
        $account=$identity!==''?$this->accountModel->Authenticate($identity):null;
        if (!$account || !password_verify((string)($_POST['password'] ?? ''),$account['MatKhauHash'])) { $this->showForm('Login','Login details are incorrect.'); return; }
        session_regenerate_id(true); $_SESSION['shop_customer']=['MaKH'=>$account['MaKH'],'TenKH'=>$account['TenKH']];
        header('Location: store.php?url=CustomerAuth/Profile'); exit;
    }
    public function Logout() {
        $this->validPost(); unset($_SESSION['shop_customer']); session_regenerate_id(true);
        header('Location: http://localhost/Baitaplon/WinmartMVC/Login'); exit;
    }
    public function Profile() {
        $this->requireCustomer(); $profile=$this->accountModel->GetProfile($_SESSION['shop_customer']['MaKH']);
        if (!$profile) { unset($_SESSION['shop_customer']); header('Location: store.php?url=CustomerAuth/Login'); exit; }
        $this->view('CustomerMaster',['page'=>'Customer/Profile','profile'=>$profile,'cartCount'=>array_sum($_SESSION['shop_cart'] ?? []),'notice'=>$_SESSION['shop_notice'] ?? '']);
        unset($_SESSION['shop_notice']);
    }
    public function SaveProfile() {
        $this->requireCustomer(); $this->validPost();
        $name=trim((string)($_POST['name'] ?? '')); $phone=trim((string)($_POST['phone'] ?? ''));
        $email=strtolower(trim((string)($_POST['email'] ?? ''))); $recipient=trim((string)($_POST['recipient'] ?? ''));
        $recipientPhone=trim((string)($_POST['recipient_phone'] ?? '')); $address=trim((string)($_POST['address'] ?? ''));
        if ($name==='' || !preg_match('/^[0-9+(). -]{8,20}$/',$phone) || ($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL)) || ($address!=='' && ($recipient==='' || !preg_match('/^[0-9+(). -]{8,20}$/',$recipientPhone)))) { $_SESSION['shop_notice']='Please check your profile and delivery address.'; header('Location: store.php?url=CustomerAuth/Profile'); exit; }
        $email=$email===''?null:$email;
        $ok=$this->accountModel->UpdateProfile($_SESSION['shop_customer']['MaKH'],$name,$phone,$email,$recipient,$recipientPhone,$address);
        $_SESSION['shop_notice']=$ok?'Profile updated.':'Could not update profile. Phone or email may already be in use.';
        $_SESSION['shop_customer']['TenKH']=$name; header('Location: store.php?url=CustomerAuth/Profile'); exit;
    }
}
?>
