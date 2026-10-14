<?php
class CustomerAuth extends controller {
    private $accountModel;

    public function __construct() { $this->accountModel = $this->model('CustomerAccountModel'); }

    private function activateCustomerCart($customerId) {
        CustomerCartSession::activate($customerId);
    }

    private function validPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed.'); }
        if (!hash_equals((string)($_SESSION['shop_csrf'] ?? ''), (string)($_POST['csrf_token'] ?? ''))) { http_response_code(403); exit('Invalid session. Reload the page.'); }
    }

    private function showForm($page, $message = '') {
        $this->view('CustomerMaster', ['page'=>'Customer/'.$page,'notice'=>$message,'cartCount'=>array_sum($_SESSION['shop_cart'] ?? [])]);
    }

    private function googleConfig() { return require __DIR__ . '/../Config/google-oauth.php'; }

    private function googleIsConfigured($config) {
        foreach (['client_id', 'client_secret', 'redirect_uri'] as $key) {
            if (empty($config[$key]) || strpos((string)$config[$key], 'YOUR_GOOGLE_OAUTH_') === 0) return false;
        }
        return true;
    }

    private function googleError($message) {
        $_SESSION['shop_notice'] = $message;
        header('Location: /Baitaplon/User'); exit;
    }

    private function requireCustomer() {
        if (empty($_SESSION['shop_customer']['MaKH'])) { header('Location: /Baitaplon/User'); exit; }
    }

    public function Register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showForm('Register'); return; }
        $this->validPost();
        $name=trim((string)($_POST['name'] ?? '')); $phone=trim((string)($_POST['phone'] ?? ''));
        $email=strtolower(trim((string)($_POST['email'] ?? ''))); $password=(string)($_POST['password'] ?? '');
        if ($name==='' || $phone==='' || !preg_match('/^[0-9+(). -]{8,20}$/',$phone) || ($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL)) || strlen($password)<8) { $this->showForm('Register','Please check your name, phone, email, and password (at least 8 characters).'); return; }
        $email=$email===''?null:$email; $customerId='KH'.strtoupper(bin2hex(random_bytes(6)));
        if (!$this->accountModel->Register($name,$phone,$email,password_hash($password,PASSWORD_DEFAULT),$customerId)) { $this->showForm('Register','Phone or email is already in use.'); return; }
        session_regenerate_id(true); $_SESSION['shop_customer']=['MaKH'=>$customerId,'TenKH'=>$name]; $this->activateCustomerCart($customerId);
        header('Location: /Baitaplon/store.php?url=CustomerAuth/Profile'); exit;
    }

    public function Login() {
        if ($_SERVER['REQUEST_METHOD']!=='POST') { $message=(string)($_SESSION['shop_notice'] ?? ''); unset($_SESSION['shop_notice']); $this->showForm('Login',$message); return; }
        $this->validPost(); $identity=trim((string)($_POST['identity'] ?? ''));
        $account=$identity!==''?$this->accountModel->Authenticate($identity):null;
        if (!$account || !password_verify((string)($_POST['password'] ?? ''),$account['MatKhauHash'])) { $this->showForm('Login','Login details are incorrect.'); return; }
        session_regenerate_id(true); $_SESSION['shop_customer']=['MaKH'=>$account['MaKH'],'TenKH'=>$account['TenKH']]; $this->activateCustomerCart($account['MaKH']);
        header('Location: /Baitaplon/store.php?url=CustomerAuth/Profile'); exit;
    }

    public function GoogleStart() {
        $config=$this->googleConfig();
        if (!$this->googleIsConfigured($config)) $this->googleError('Google chưa được cấu hình: hãy điền Client ID và Client Secret trong MVC/Config/google-oauth.local.php.');
        $state=bin2hex(random_bytes(32)); $_SESSION['shop_google_oauth_state']=$state;
        $params=['client_id'=>$config['client_id'],'redirect_uri'=>$config['redirect_uri'],'response_type'=>'code','scope'=>'openid email profile','state'=>$state,'prompt'=>'select_account'];
        header('Location: https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query($params,'','&',PHP_QUERY_RFC3986)); exit;
    }

    public function GoogleCallback() {
        $expectedState=(string)($_SESSION['shop_google_oauth_state'] ?? ''); unset($_SESSION['shop_google_oauth_state']);
        if ($expectedState==='' || !hash_equals($expectedState,(string)($_GET['state'] ?? ''))) $this->googleError('Phiên đăng nhập Google không hợp lệ. Vui lòng thử lại.');
        if (!empty($_GET['error']) || empty($_GET['code'])) $this->googleError('Bạn đã hủy đăng nhập Google hoặc Google không trả về mã xác thực.');
        $config=$this->googleConfig();
        if (!$this->googleIsConfigured($config)) $this->googleError('Google chưa được cấu hình: hãy điền Client ID và Client Secret trong MVC/Config/google-oauth.local.php.');
        if (!function_exists('curl_init')) $this->googleError('PHP cURL đang tắt. Hãy bật extension=curl trong XAMPP/php/php.ini rồi khởi động lại Apache.');

        $token=$this->googleRequest('https://oauth2.googleapis.com/token',[
            'code'=>(string)$_GET['code'],'client_id'=>$config['client_id'],'client_secret'=>$config['client_secret'],
            'redirect_uri'=>$config['redirect_uri'],'grant_type'=>'authorization_code'
        ]);
        if (empty($token['access_token'])) $this->googleError('Không xác thực được tài khoản Google. Vui lòng thử lại.');
        $profile=$this->googleRequest('https://openidconnect.googleapis.com/v1/userinfo',null,$token['access_token']);
        if (empty($profile['email']) || empty($profile['email_verified'])) $this->googleError('Google không cung cấp email đã xác minh cho tài khoản này.');
        $account=$this->accountModel->AuthenticateGoogle($profile['email']);
        if (!$account) $this->googleError('Email Google chưa có tài khoản Win Mart. Hãy đăng ký khách hàng bằng email Google này trước.');
        session_regenerate_id(true); $_SESSION['shop_customer']=['MaKH'=>$account['MaKH'],'TenKH'=>$account['TenKH']]; $this->activateCustomerCart($account['MaKH']);
        header('Location: /Baitaplon/store.php?url=CustomerAuth/Profile'); exit;
    }

    private function googleRequest($url,$form=null,$bearerToken=null) {
        $curl=curl_init($url); $headers=[];
        if ($bearerToken!==null) $headers[]='Authorization: Bearer '.$bearerToken;
        curl_setopt_array($curl,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>15,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_HTTPHEADER=>$headers,CURLOPT_POST=>$form!==null,CURLOPT_POSTFIELDS=>$form!==null?http_build_query($form):null]);
        $body=curl_exec($curl); $status=(int)curl_getinfo($curl,CURLINFO_HTTP_CODE); curl_close($curl);
        if (!is_string($body) || $status<200 || $status>=300) return [];
        $decoded=json_decode($body,true); return is_array($decoded)?$decoded:[];
    }

    public function Logout() {
        $this->validPost(); CustomerCartSession::sync(null); unset($_SESSION['shop_customer']); session_regenerate_id(true);
        header('Location: /Baitaplon/User'); exit;
    }

    public function Profile() {
        $this->requireCustomer(); $profile=$this->accountModel->GetProfile($_SESSION['shop_customer']['MaKH']);
        if (!$profile) { unset($_SESSION['shop_customer']); header('Location: /Baitaplon/User'); exit; }
        $this->view('CustomerMaster',['page'=>'Customer/Profile','profile'=>$profile,'cartCount'=>array_sum($_SESSION['shop_cart'] ?? []),'notice'=>$_SESSION['shop_notice'] ?? '']);
        unset($_SESSION['shop_notice']);
    }

    public function SaveProfile() {
        $this->requireCustomer(); $this->validPost();
        $name=trim((string)($_POST['name'] ?? '')); $phone=trim((string)($_POST['phone'] ?? ''));
        $email=strtolower(trim((string)($_POST['email'] ?? ''))); $recipient=trim((string)($_POST['recipient'] ?? ''));
        $recipientPhone=trim((string)($_POST['recipient_phone'] ?? '')); $address=trim((string)($_POST['address'] ?? ''));
        if ($name==='' || !preg_match('/^[0-9+(). -]{8,20}$/',$phone) || ($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL)) || ($address!=='' && ($recipient==='' || !preg_match('/^[0-9+(). -]{8,20}$/',$recipientPhone)))) { $_SESSION['shop_notice']='Please check your profile and delivery address.'; header('Location: /Baitaplon/store.php?url=CustomerAuth/Profile'); exit; }
        $email=$email===''?null:$email;
        $ok=$this->accountModel->UpdateProfile($_SESSION['shop_customer']['MaKH'],$name,$phone,$email,$recipient,$recipientPhone,$address);
        $_SESSION['shop_notice']=$ok?'Profile updated.':'Could not update profile. Phone or email may already be in use.';
        $_SESSION['shop_customer']['TenKH']=$name; header('Location: /Baitaplon/store.php?url=CustomerAuth/Profile'); exit;
    }
}
