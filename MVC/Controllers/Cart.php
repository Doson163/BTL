<?php
class Cart extends controller {
    private $storeModel;
    public function __construct() { $this->storeModel=$this->model('StorefrontModel'); }
    private function requireCustomer() {
        if (empty($_SESSION['shop_customer']['MaKH'])) {
            $_SESSION['shop_notice'] = 'Vui lòng đăng nhập để sử dụng giỏ hàng.';
            header('Location: /Baitaplon/User');
            exit;
        }
    }
    private function requirePostAndCsrf() {
        if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit('Method not allowed.'); }
        if (!hash_equals((string)($_SESSION['shop_csrf'] ?? ''),(string)($_POST['csrf_token'] ?? ''))) { http_response_code(403); exit('Invalid session. Reload the page.'); }
    }
    private function redirectToCart() { header('Location: /Baitaplon/store.php?url=Cart/Get_data'); exit; }
    public function Get_data() {
        $this->requireCustomer();
        $items=$this->storeModel->GetCartProducts($_SESSION['shop_cart'] ?? []); $total=0;
        foreach($items as $item) $total+=$item['LineTotal'];
        $this->view('CustomerMaster',['page'=>'Customer/Cart','items'=>$items,'total'=>$total,'cartCount'=>array_sum($_SESSION['shop_cart'] ?? []),'notice'=>$_SESSION['shop_notice'] ?? '']);
        unset($_SESSION['shop_notice']);
    }
    public function Add($id) {
        $this->requireCustomer();
        $this->requirePostAndCsrf(); $product=$this->storeModel->GetProductById($id); $current=(int)($_SESSION['shop_cart'][$id] ?? 0);
        if (!$product || (int)$product['SoLuongTon']<$current+1) { $_SESSION['shop_notice']='Product is unavailable or out of stock.'; $this->redirectToCart(); }
        $_SESSION['shop_cart'][$id]=$current+1; CustomerCartSession::saveActive(); $_SESSION['shop_notice']='Product added to your cart.'; $this->redirectToCart();
    }
    public function Update($id) {
        $this->requireCustomer();
        $this->requirePostAndCsrf(); $quantity=filter_var($_POST['quantity'] ?? null,FILTER_VALIDATE_INT); $product=$this->storeModel->GetProductById($id);
        if (!$product || $quantity===false || $quantity<0) { $_SESSION['shop_notice']='Please enter a valid quantity.'; $this->redirectToCart(); }
        if ($quantity===0) unset($_SESSION['shop_cart'][$id]); else { $stock=(int)$product['SoLuongTon']; $_SESSION['shop_cart'][$id]=min($quantity,$stock); if($quantity>$stock) $_SESSION['shop_notice']='Quantity was adjusted to current stock.'; }
        CustomerCartSession::saveActive();
        $this->redirectToCart();
    }
    public function Remove($id) { $this->requireCustomer(); $this->requirePostAndCsrf(); unset($_SESSION['shop_cart'][$id]); CustomerCartSession::saveActive(); $this->redirectToCart(); }
    public function Clear() { $this->requireCustomer(); $this->requirePostAndCsrf(); $_SESSION['shop_cart']=[]; CustomerCartSession::saveActive(); $this->redirectToCart(); }
}
