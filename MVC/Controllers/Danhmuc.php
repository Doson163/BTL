<?php
class Danhmuc extends controller {
    public $dmModel;

    public function __construct() {
        $this->dmModel = $this->model("DanhmucModel");
    }

    public function Get_data() {
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) $keyword = $_POST['txtTimKiem'];

        $this->view("Master", [
            "page" => "Danhmuc_V",
            "dulieu" => $this->dmModel->DanhSach($keyword),
            "keyword" => $keyword
        ]);
    }

    public function Luu() {
        if(isset($_POST['btnThem']) || isset($_POST['btnLuu'])) {
            $macode = $_POST['txtMaCode']; 
            $ten = $_POST['txtTen'];
            $mota = $_POST['txtMoTa'];
            if (trim($macode) === '' || trim($ten) === '') {
                $this->backWithMessage('Mã và tên danh mục không được để trống.', 'warning', 'Cảnh báo', '/Baitaplon/Danhmuc');
                return;
            }
            
            if(isset($_POST['btnLuu'])) {
                // Sửa
                $id = $_POST['txtID'];
                if ($this->dmModel->CheckTrungMa($macode, $id) || $this->dmModel->CheckTrungTen($ten, $id)) {
                    $this->backWithMessage('Mã hoặc tên danh mục đã tồn tại.', 'warning', 'Cảnh báo', '/Baitaplon/Danhmuc');
                    return;
                }
                $this->dmModel->Sua($id, $macode, $ten, $mota);
                $this->redirectWithMessage('/Baitaplon/Danhmuc', 'Đã cập nhật danh mục thành công!', 'success', 'Cập nhật thành công');
            } else {
                // Thêm mới
                if($this->dmModel->CheckTrungMa($macode) || $this->dmModel->CheckTrungTen($ten)){
                    $this->backWithMessage("Mã danh mục [$macode] đã tồn tại!", 'warning', 'Cảnh báo', '/Baitaplon/Danhmuc');
                    return;
                }
                $this->dmModel->Them($macode, $ten, $mota);
                $this->redirectWithMessage('/Baitaplon/Danhmuc', 'Thêm mới danh mục thành công!', 'success', 'Thêm mới thành công');
            }
        }
    }
    
    public function Sua($id) {
        $this->view("Master", [
            "page" => "Danhmuc_V",
            "dulieu" => $this->dmModel->DanhSach(),
            "edit_data" => mysqli_fetch_array($this->dmModel->GetByID($id))
        ]);
    }

    public function Xoa($id) {
        if ($this->dmModel->CoSanPham($id)) {
            $this->redirectWithMessage('/Baitaplon/Danhmuc', 'Không thể xóa danh mục đang có sản phẩm.', 'error', 'Không thể xóa');
            return;
        }
        $this->dmModel->Xoa($id);
        $this->redirectWithMessage('/Baitaplon/Danhmuc', 'Đã xóa danh mục thành công!', 'success', 'Xóa thành công');
    }

    // --- 1. XUẤT EXCEL (Chuẩn .xlsx) ---
    public function XuatExcel() {
        $this->startExcelExport();
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS Danh Muc');
        $rowCount = 1;

        // Tiêu đề cột
        $sheet->setCellValue('A' . $rowCount, 'Mã Danh Mục');
        $sheet->setCellValue('B' . $rowCount, 'Tên Danh Mục');
        $sheet->setCellValue('C' . $rowCount, 'Mô Tả');

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00'); // Xanh lá
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Đổ dữ liệu
        $data = $this->dmModel->DanhSach("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            // Ưu tiên lấy MaCode (Mã người dùng nhập), nếu không có thì lấy ID tự tăng
            $maHienThi = isset($row['MaCode']) && $row['MaCode']!='' ? $row['MaCode'] : $row['MaDM'];
            
            $sheet->setCellValue('A' . $rowCount, $maHienThi);
            $sheet->setCellValue('B' . $rowCount, $row['TenDM']);
            $sheet->setCellValue('C' . $rowCount, $row['MoTa']);
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'C' . ($rowCount))->applyFromArray($styleArray);

        // Lưu và tải về
        $fileName = 'DanhSachDanhmuc.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        $this->downloadExcel($objWriter, $fileName);
    }

    // --- 2. NHẬP EXCEL ---
    public function NhapExcel() {
        if (isset($_POST["btnNhapExcel"])) {
            if (isset($_FILES["fileExcel"]["name"]) && $_FILES["fileExcel"]["error"] == 0) {
                $file = $_FILES["fileExcel"]["tmp_name"];
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($file);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objReader->setReadDataOnly(true);
                    
                    $objExcel = $objReader->load($file);
                    $sheet = $objExcel->getSheet(0);
                    $TotalRow = $sheet->getHighestRow();

                    $count = 0;
                    // Đọc từ dòng 2
                    for ($i = 2; $i <= $TotalRow; $i++) {
                        $macode = $sheet->getCell('A' . $i)->getValue();
                        $ten = $sheet->getCell('B' . $i)->getValue();
                        $mota = $sheet->getCell('C' . $i)->getValue();

                        // Nếu có Mã và Tên, và Mã chưa trùng thì thêm
                        if (!empty($macode) && !empty($ten) && !$this->dmModel->CheckTrungMa($macode)) {
                            $this->dmModel->Them($macode, $ten, $mota);
                            $count++;
                        }
                    }
                    $this->redirectWithMessage('/Baitaplon/Danhmuc', "Đã nhập thành công $count danh mục từ file Excel!", 'success', 'Import thành công');

                } catch (Exception $e) {
                    $this->backWithMessage('Lỗi khi đọc file Excel: ' . $e->getMessage(), 'error', 'Lỗi Import', '/Baitaplon/Danhmuc');
                }
            } else {
                $this->backWithMessage('Vui lòng chọn file Excel!', 'warning', 'Cảnh báo', '/Baitaplon/Danhmuc');
            }
        }
    }
}
?>
