<?php
class Sanpham extends controller {
    public $spModel;
    public $dmModel;

    public function __construct() {
        $this->spModel = $this->model("SanphamModel");
        $this->dmModel = $this->model("DanhmucModel");
    }

    // 1. Hiển thị (Giữ nguyên)
    public function Get_data() {
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) {
            $keyword = $_POST['txtTimKiem'];
        }

        $this->view("Master", [
            "page" => "Sanpham_V",
            "dulieu" => $this->spModel->GetSanpham($keyword),
            "danhmuc" => $this->dmModel->DanhSach(),
            "keyword" => $keyword
        ]);
    }

    // 2. THÊM MỚI (Cập nhật lấy txtHSD)
    public function Themmoi() {
        if(isset($_POST['btnThem'])) {
            $masp = $_POST['txtMaSP'];
            if($this->spModel->CheckTrungMa($masp)) {
                $this->backWithMessage("Lỗi: Mã sản phẩm [$masp] đã tồn tại!", 'warning', 'Cảnh báo', '/Baitaplon/Sanpham');
                return;
            }

            $tensp = $_POST['txtTenSP'];
            $madm = $_POST['ddlDanhmuc'];
            $gia = $_POST['txtGia'];
            $soluong = $_POST['txtSoLuong'];
            $hsd = $_POST['txtHSD']; // Lấy hạn sử dụng
            if (!$this->DuLieuHopLe($tensp, $madm, $gia, $soluong, $hsd)) {
                $this->backWithMessage('Vui lòng nhập tên, danh mục, giá và số lượng hợp lệ.', 'warning', 'Cảnh báo', '/Baitaplon/Sanpham');
                return;
            }
            
            $hinhanh = "";
            if(isset($_FILES['txtHinhAnh']['name']) && $_FILES['txtHinhAnh']['name'] != ""){
                $hinhanh = basename($_FILES['txtHinhAnh']['name']);
                move_uploaded_file($_FILES['txtHinhAnh']['tmp_name'], "./Public/Images/" . $hinhanh);
            }

            $this->spModel->InsertSanpham($masp, $tensp, $madm, null, $gia, $soluong, $hinhanh, $hsd);
            $this->redirectWithMessage('/Baitaplon/Sanpham', 'Thêm sản phẩm mới thành công!', 'success', 'Thêm thành công');
        }
    }

    // 3. Xóa (Giữ nguyên)
    public function Xoa($masp) {
        if ($this->spModel->CoDonHang($masp)) {
            $this->redirectWithMessage('/Baitaplon/Sanpham', 'Không thể xóa sản phẩm đã xuất hiện trong đơn hàng.', 'error', 'Không thể xóa');
            return;
        }
        $this->spModel->DeleteSanpham($masp);
        $this->redirectWithMessage('/Baitaplon/Sanpham', 'Đã xóa sản phẩm thành công!', 'success', 'Xóa thành công');
    }

    // 4. Sửa (Giữ nguyên)
    public function Sua($masp) {
        $this->view("Master", [
            "page" => "Sanpham_V",
            "dulieu" => $this->spModel->GetSanpham(),
            "danhmuc" => $this->dmModel->DanhSach(),
            "editData" => mysqli_fetch_array($this->spModel->GetSanphamByID($masp))
        ]);
    }

    // 5. CẬP NHẬT (Cập nhật lấy txtHSD)
    public function CapNhat() {
        if(isset($_POST['btnLuu'])) {
            $masp = $_POST['txtMaSP'];
            $tensp = $_POST['txtTenSP'];
            $madm = $_POST['ddlDanhmuc'];
            $gia = $_POST['txtGia'];
            $soluong = $_POST['txtSoLuong'];
            $hsd = $_POST['txtHSD']; // Lấy hạn sử dụng
            if (!$this->DuLieuHopLe($tensp, $madm, $gia, $soluong, $hsd)) {
                $this->backWithMessage('Vui lòng nhập tên, danh mục, giá và số lượng hợp lệ.', 'warning', 'Cảnh báo', '/Baitaplon/Sanpham');
                return;
            }

            $hinhanh = "";
            if(isset($_FILES['txtHinhAnh']['name']) && $_FILES['txtHinhAnh']['name'] != ""){
                $hinhanh = basename($_FILES['txtHinhAnh']['name']);
                move_uploaded_file($_FILES['txtHinhAnh']['tmp_name'], "./Public/Images/" . $hinhanh);
            }

            $this->spModel->UpdateSanpham($masp, $tensp, $madm, $gia, $soluong, $hinhanh, $hsd);
            $this->redirectWithMessage('/Baitaplon/Sanpham', 'Cập nhật thông tin sản phẩm thành công!', 'success', 'Cập nhật thành công');
        }
    }

    // --- 6. XUẤT EXCEL (Thêm cột HSD) ---
    public function XuatExcel() {
        $this->startExcelExport();
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS San Pham');
        $rowCount = 1;

        // Tiêu đề cột
        $sheet->setCellValue('A1', 'Mã SP');
        $sheet->setCellValue('B1', 'Tên Sản Phẩm');
        $sheet->setCellValue('C1', 'Mã DM');
        $sheet->setCellValue('D1', 'Mã NCC');
        $sheet->setCellValue('E1', 'Giá Bán');
        $sheet->setCellValue('F1', 'Số Lượng');
        $sheet->setCellValue('G1', 'Hình Ảnh');
        $sheet->setCellValue('H1', 'Hạn Sử Dụng'); // Cột mới

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Đổ dữ liệu
        $data = $this->spModel->GetSanpham("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            $sheet->setCellValue('A' . $rowCount, $row['MaSP']);
            $sheet->setCellValue('B' . $rowCount, $row['TenSP']);
            $sheet->setCellValue('C' . $rowCount, $row['MaDM']);
            $sheet->setCellValue('D' . $rowCount, $row['MaNCC']);
            $sheet->setCellValue('E' . $rowCount, $row['GiaBan']);
            $sheet->setCellValue('F' . $rowCount, $row['SoLuongTon']);
            $sheet->setCellValue('G' . $rowCount, $row['HinhAnh']);
            $sheet->setCellValue('H' . $rowCount, $row['HanSuDung']); // Dữ liệu HSD
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'H' . ($rowCount))->applyFromArray($styleArray);

        // Xuất file
        $fileName = 'DanhSachSanpham.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        $this->downloadExcel($objWriter, $fileName);
    }

    // --- 7. NHẬP EXCEL (Thêm đọc cột H: HSD) ---
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
                    for ($i = 2; $i <= $TotalRow; $i++) {
                        $masp = $sheet->getCell('A' . $i)->getValue();
                        $tensp = $sheet->getCell('B' . $i)->getValue();
                        $madm = $sheet->getCell('C' . $i)->getValue();
                        $mancc = $sheet->getCell('D' . $i)->getValue();
                        $gia = $sheet->getCell('E' . $i)->getValue();
                        $soluong = $sheet->getCell('F' . $i)->getValue();
                        $hinhanh = $sheet->getCell('G' . $i)->getValue();
                        $hsd = $sheet->getCell('H' . $i)->getValue(); // Đọc cột H

                        // Xử lý ngày tháng Excel
                        if(!empty($hsd) && is_numeric($hsd)) {
                            $hsd = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($hsd));
                        } else {
                            $hsd = NULL;
                        }

                        if (empty($hinhanh)) $hinhanh = "no-image.jpg";

                        if (!empty($masp) && !$this->spModel->CheckTrungMa($masp)) {
                            $this->spModel->InsertSanpham($masp, $tensp, $madm, $mancc, $gia, $soluong, $hinhanh, $hsd);
                            $count++;
                        }
                    }
                    $this->redirectWithMessage('/Baitaplon/Sanpham', "Đã nhập thành công $count sản phẩm từ file Excel!", 'success', 'Import thành công');

                } catch (Exception $e) {
                    $this->backWithMessage('Lỗi khi đọc file Excel: ' . $e->getMessage(), 'error', 'Lỗi Import', '/Baitaplon/Sanpham');
                }
            } else {
                $this->backWithMessage('Vui lòng chọn file Excel!', 'warning', 'Cảnh báo', '/Baitaplon/Sanpham');
            }
        }
    }

    private function DuLieuHopLe($tensp, $madm, $gia, $soluong, $hsd) {
        $giaHopLe = filter_var($gia, FILTER_VALIDATE_INT);
        $soLuongHopLe = filter_var($soluong, FILTER_VALIDATE_INT);
        if (trim((string)$tensp) === '' || filter_var($madm, FILTER_VALIDATE_INT) === false || (int)$madm < 1) return false;
        if ($giaHopLe === false || $giaHopLe <= 0 || $soLuongHopLe === false || $soLuongHopLe < 0) return false;
        if ($hsd !== '' && (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $hsd) || !checkdate((int)substr($hsd, 5, 2), (int)substr($hsd, 8, 2), (int)substr($hsd, 0, 4)))) return false;
        return true;
    }
}
?>
