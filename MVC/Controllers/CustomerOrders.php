<?php
class CustomerOrders extends controller {
    private $orders;
    public function __construct() { $this->orders = $this->model('CustomerOrderModel'); }
    private function signedIn() {
        if (empty($_SESSION['shop_customer']['MaKH'])) {
            $_SESSION['shop_notice'] = 'Vui lòng đăng nhập để xem giỏ hàng và đặt hàng.';
            header('Location: /Baitaplon/User');
            exit;
        }
    }
    private function postGuard() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Phương thức không được hỗ trợ.'); }
        if (!hash_equals((string)($_SESSION['shop_csrf'] ?? ''), (string)($_POST['csrf_token'] ?? ''))) { http_response_code(403); exit('Phiên làm việc không hợp lệ.'); }
    }
    private function render($page, $values = []) {
        $this->view('CustomerMaster', array_merge(['page'=>$page, 'cartCount'=>array_sum($_SESSION['shop_cart'] ?? [])], $values));
    }
    public function Checkout() {
        $this->signedIn();
        $items = $this->model('StorefrontModel')->GetCartProducts($_SESSION['shop_cart'] ?? []);
        if (!$items) { $_SESSION['shop_notice']='Giỏ hàng đang trống.'; header('Location: /Baitaplon/store.php?url=Cart/Get_data'); exit; }
        $profile = $this->orders->GetProfile($_SESSION['shop_customer']['MaKH']);
        $methods = $this->orders->GetPaymentMethods();
        $total=0; foreach($items as $item) $total += $item['LineTotal'];
        $this->render('Customer/Checkout', ['items'=>$items,'profile'=>$profile,'methods'=>$methods,'total'=>$total,'notice'=>$_SESSION['shop_notice'] ?? '']);
        unset($_SESSION['shop_notice']);
    }
    public function Place() {
        $this->signedIn(); $this->postGuard();
        $recipient=trim((string)($_POST['recipient'] ?? ''));
        $phone=trim((string)($_POST['phone'] ?? ''));
        $address=trim((string)($_POST['address'] ?? ''));
        $promoCode=strtoupper(trim((string)($_POST['promo_code'] ?? '')));
        $method=filter_var($_POST['payment'] ?? null, FILTER_VALIDATE_INT);
        if ($recipient==='' || !preg_match('/^[0-9+(). -]{8,20}$/',$phone) || $address==='' || $method===false) {
            $_SESSION['shop_notice']='Please enter a valid delivery name, phone, and address.';
            header('Location: /Baitaplon/store.php?url=CustomerOrders/Checkout'); exit;
        }
        $orderId=$this->orders->CreateOrder($_SESSION['shop_customer']['MaKH'],$recipient,$phone,$address,(int)$method,$_SESSION['shop_cart'] ?? [],$promoCode);
        if ($orderId===false) {
            $_SESSION['shop_notice']='Please enter a valid delivery name, phone, and address.';
            header('Location: /Baitaplon/store.php?url=CustomerOrders/Checkout'); exit;
        }
        $_SESSION['shop_cart']=[]; CustomerCartSession::saveActive();
        $_SESSION['shop_notice']='Đặt hàng thành công. Mã đơn hàng #'.$orderId.'.';
        header('Location: /Baitaplon/store.php?url=CustomerOrders/Detail/'.$orderId); exit;
    }
    public function Get_data() {
        $this->signedIn();
        $this->render('Customer/Orders',['orders'=>$this->orders->GetOrderList($_SESSION['shop_customer']['MaKH']),'notice'=>$_SESSION['shop_notice'] ?? '']);
        unset($_SESSION['shop_notice']);
    }
    public function Detail($id) {
        $this->signedIn(); $order=$this->orders->GetOrder($_SESSION['shop_customer']['MaKH'],(int)$id);
        if (!$order) { http_response_code(404); exit('Không tìm thấy đơn hàng.'); }
        $this->render('Customer/OrderDetail',['order'=>$order]);
    }
    public function Cancel($id) {
        $this->signedIn(); $this->postGuard();
        $ok=$this->orders->CancelOrder($_SESSION['shop_customer']['MaKH'],(int)$id);
        $_SESSION['shop_notice']=$ok?'Đã hủy đơn hàng.':'Không thể hủy đơn hàng này.';
        header('Location: /Baitaplon/store.php?url=CustomerOrders/Detail/'.(int)$id); exit;
    }

    public function Received($id) {
        $this->signedIn(); $this->postGuard();
        $ok=$this->orders->ConfirmReceived($_SESSION['shop_customer']['MaKH'],(int)$id);
        $_SESSION['shop_notice']=$ok?'Bạn đã xác nhận nhận hàng. Đơn hàng đã hoàn thành.':'Chỉ có thể xác nhận nhận hàng khi đơn đang giao.';
        header('Location: /Baitaplon/store.php?url=CustomerOrders/Detail/'.(int)$id); exit;
    }
}
