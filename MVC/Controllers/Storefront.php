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
        if (!$product) { http_response_code(404); $_SESSION['shop_notice']='Product is unavailable.'; header('Location: /Baitaplon/store.php?url=Storefront/Get_data'); exit; }
        $this->view('CustomerMaster',['page'=>'Customer/ProductDetail','product'=>$product,'cartCount'=>array_sum($_SESSION['shop_cart'] ?? []),'notice'=>$_SESSION['shop_notice'] ?? '']);
        unset($_SESSION['shop_notice']);
    }

    public function ChatRecommend() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Vui lòng gửi câu hỏi bằng biểu mẫu tư vấn.'], JSON_UNESCAPED_UNICODE);
            return;
        }
        if (!hash_equals((string)($_SESSION['shop_csrf'] ?? ''), (string)($_POST['csrf_token'] ?? ''))) {
            http_response_code(403);
            echo json_encode(['error' => 'Phiên làm việc đã hết hạn. Hãy tải lại trang.'], JSON_UNESCAPED_UNICODE);
            return;
        }
        $message = trim((string)($_POST['message'] ?? ''));
        if ($message === '' || mb_strlen($message, 'UTF-8') > 1000) {
            http_response_code(422);
            echo json_encode(['error' => 'Tin nhắn không được để trống và tối đa 1.000 ký tự.'], JSON_UNESCAPED_UNICODE);
            return;
        }
        $advisor = $this->model('ShoppingAdvisor');
        $result = $advisor->recommend($message, $this->storeModel->GetAvailableProductsForAdvisor());
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
