<?php
class Storefront extends controller {
    private $storeModel;
    public function __construct() { $this->storeModel=$this->model('StorefrontModel'); }
    public function Get_data() {
        $filters=['q'=>trim((string)($_GET['q'] ?? '')),'category'=>$_GET['category'] ?? '','min_price'=>$_GET['min_price'] ?? '','max_price'=>$_GET['max_price'] ?? '','sort'=>$_GET['sort'] ?? ''];
        $this->view('CustomerMaster',['page'=>'Customer/Catalog','products'=>$this->storeModel->GetProducts($filters),'categories'=>$this->storeModel->GetCategories(),'filters'=>$filters,'cartCount'=>array_sum($_SESSION['shop_cart'] ?? []),'notice'=>$_SESSION['shop_notice'] ?? '']);
        unset($_SESSION['shop_notice']);
    }
    public function ChiTiet($id) {
        $product=$this->storeModel->GetProductById($id);
        if (!$product) { http_response_code(404); $_SESSION['shop_notice']='Product is unavailable.'; header('Location: store.php?url=Storefront/Get_data'); exit; }
        $this->view('CustomerMaster',['page'=>'Customer/ProductDetail','product'=>$product,'cartCount'=>array_sum($_SESSION['shop_cart'] ?? []),'notice'=>$_SESSION['shop_notice'] ?? '']);
        unset($_SESSION['shop_notice']);
    }
}
?>
