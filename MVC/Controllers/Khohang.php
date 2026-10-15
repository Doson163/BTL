<?php
class Khohang extends controller {
    public $khoModel;

    public function __construct() {
        $this->khoModel = $this->model("KhohangModel");
    }

    
    public function Get_data() {
        
        if(!isset($_SESSION['gio_nhap'])) $_SESSION['gio_nhap'] = [];

        
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) {
            $keyword = $_POST['txtTimKiem'];
        }

        
        $this->view("Master", [
            "page" => "Khohang_V",
            "ncc" => $this->khoModel->GetAllNCC(),
            "sp" => $this->khoModel->GetAllSP(),         
            "lichsu" => $this->khoModel->GetLichSuNhap($keyword), 
            "keyword" => $keyword
        ]);
    }

    /*if ($gia <= 0) {
                echo "<script>alert('GiÃ¡ nháº­p pháº£i lá»›n hÆ¡n 0!'); window.history.back();</script>";
                return; // Dá»«ng ngay
            }*/

    
    public function ThemTam() {
        if(isset($_POST['btnThem'])) {
            $id = $_POST['ddlSanPham'];
            $sl = $_POST['txtSoLuong'];
            $gia = $_POST['txtGiaNhap'];
            $quantity = filter_var($sl, FILTER_VALIDATE_INT);
            $unitPrice = filter_var($gia, FILTER_VALIDATE_INT);
            if ($quantity === false || $quantity < 1 || $unitPrice === false || $unitPrice < 1) {
                $this->backWithMessage('Số lượng và giá nhập phải là số nguyên dương.', 'warning', 'Cảnh báo', '/Baitaplon/Khohang');
                return;
            }

            $sp = mysqli_fetch_array($this->khoModel->GetSP($id));

            $item = [
                'id' => $id,
                'ten' => $sp['TenSP'],
                'soluong' => $sl,
                'gia' => $gia
            ];

            $_SESSION['gio_nhap'][] = $item;

            header("Location: /Baitaplon/Khohang");
        }
    }

    public function XoaTam($index) {
        if(isset($_SESSION['gio_nhap'][$index])) {
            unset($_SESSION['gio_nhap'][$index]);
            $_SESSION['gio_nhap'] = array_values($_SESSION['gio_nhap']);
        }
        header("Location: /Baitaplon/Khohang");
    }

    public function LuuPhieu() {
        if(isset($_SESSION['gio_nhap']) && count($_SESSION['gio_nhap']) > 0) {
            $mancc = $_POST['ddlNCC'];
            $manv = $this->khoModel->GetDefaultNhanVienId();
            if ($manv === null) {
                $this->backWithMessage('Chưa có nhân viên trong hệ thống để ghi nhận phiếu nhập kho.', 'warning', 'Cảnh báo', '/Baitaplon/Khohang');
                return;
            }

            $tongtien = 0;
            foreach($_SESSION['gio_nhap'] as $item) {
                $tongtien += $item['soluong'] * $item['gia'];
            }

            $kq = $this->khoModel->NhapHang($mancc, $manv, $tongtien, $_SESSION['gio_nhap']);

            if($kq) {
                unset($_SESSION['gio_nhap']); 
                $this->redirectWithMessage('/Baitaplon/Khohang', 'Nhập kho thành công!', 'success', 'Nhập kho thành công');
            } else {
                $this->redirectWithMessage('/Baitaplon/Khohang', 'Lỗi nhập kho! Vui lòng kiểm tra lại.', 'error', 'Lỗi nhập kho');
            }

        } else {
            $this->redirectWithMessage('/Baitaplon/Khohang', 'Chưa chọn sản phẩm nào để nhập kho!', 'warning', 'Cảnh báo');
        }
    }

    
    public function XuatExcelLichSu() {
        $this->startExcelExport();
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('Lich Su Nhap Kho');
        $rowCount = 1;

        
        $sheet->setCellValue('A1', 'MÃ£ Phiáº¿u');
        $sheet->setCellValue('B1', 'NhÃ  Cung Cáº¥p');
        $sheet->setCellValue('C1', 'Chi Tiáº¿t Nháº­p (Sáº£n pháº©m - SL - GiÃ¡)');
        $sheet->setCellValue('D1', 'NgÃ y Nháº­p');
        $sheet->setCellValue('E1', 'Tá»•ng Tiá»n');

        
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(60);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00BFFF');

        // Data
        $data = $this->khoModel->GetLichSuNhap("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            
            // Xá»­ lÃ½ cá»™t chi tiáº¿t: Chuyá»ƒn HTML thÃ nh text xuá»‘ng dÃ²ng
            // HTML gá»‘c: <div><b>TÃªn</b>...</div>
            // Chuyá»ƒn thÃ nh: TÃªn... \n
            $chitiet = str_replace('</div>', "\n", $row['ChiTietNhap']); 
            $chitiet = strip_tags($chitiet); // Loáº¡i bá» tháº» html cÃ²n láº¡i
            $chitiet = trim($chitiet);

            $sheet->setCellValue('A' . $rowCount, '#' . $row['MaPN']);
            $sheet->setCellValue('B' . $rowCount, $row['TenNCC']);
            $sheet->setCellValue('C' . $rowCount, $chitiet);
            $sheet->setCellValue('D' . $rowCount, date('d/m/Y H:i', strtotime($row['NgayNhap'])));
            $sheet->setCellValue('E' . $rowCount, $row['TongTien']);
            
            
            $sheet->getStyle('C' . $rowCount)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A' . $rowCount . ':E' . $rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        }

        
        $fileName = 'LichSuNhapKho.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        $this->downloadExcel($objWriter, $fileName);
    }

    
    public function NhapExcelLichSu() {
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
                        // Cáº¥u trÃºc cá»™t: A:MÃ£ | B:NCC | C:Chi Tiáº¿t | D:NgÃ y | E:Tá»•ng tiá»n
                        $tenNCC = $sheet->getCell('B' . $i)->getValue();
                        $strChiTiet = $sheet->getCell('C' . $i)->getValue();
                        $ngay = $sheet->getCell('D' . $i)->getValue();
                        $tongtien = $sheet->getCell('E' . $i)->getValue();

                        
                        if(is_numeric($ngay)) $ngay = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($ngay));

                        
                        $mancc = $this->khoModel->GetMaNCCByTen($tenNCC);
                        if(!$mancc) $mancc = 1; // Náº¿u khÃ´ng tÃ¬m tháº¥y, gÃ¡n táº¡m NCC máº·c Ä‘á»‹nh lÃ  1

                        // 2. PhÃ¢n tÃ­ch cá»™t Chi tiáº¿t (Quan trá»ng!)
                        // Äá»‹nh dáº¡ng text: "MÃ¬ Háº£o Háº£o - SL: 10 - GiÃ¡: 3,500Ä‘"
                        $arrChiTiet = [];
                        if(!empty($strChiTiet)) {
                            $lines = explode("\n", $strChiTiet);
                            foreach($lines as $line) {
                                // Sá»­ dá»¥ng Regex Ä‘á»ƒ báº¯t: TÃªn, Sá»‘ lÆ°á»£ng, GiÃ¡
                                // (.*?) : Láº¥y tÃªn (báº¥t ká»³ kÃ½ tá»± nÃ o)
                                // - SL: (\d+) : Láº¥y sá»‘ lÆ°á»£ng (sá»‘)
                                // - GiÃ¡: ([\d,]+) : Láº¥y giÃ¡ (sá»‘ vÃ  dáº¥u pháº©y)
                                if(preg_match('/^(.*?) - SL: (\d+) - GiÃ¡: ([\d,]+)/', trim($line), $matches)) {
                                    $tenSP = trim($matches[1]);
                                    $sl = $matches[2];
                                    $gia = str_replace(',', '', $matches[3]); // Bá» dáº¥u pháº©y trong giÃ¡

                                    
                                    $masp = $this->khoModel->GetMaSPByTen($tenSP);
                                    if($masp) {
                                        $arrChiTiet[] = ['masp' => $masp, 'sl' => $sl, 'gia' => $gia];
                                    }
                                }
                            }
                        }

                        
                        if (!empty($arrChiTiet)) {
                            
                            $this->khoModel->ImportPhieuLichSuFull($mancc, 1, $ngay, $tongtien, $arrChiTiet);
                            $count++;
                        }
                    }
                    $this->redirectWithMessage('/Baitaplon/Khohang', "Đã import thành công $count phiếu nhập từ file Excel!", 'success', 'Import thành công');

                } catch (Exception $e) {
                    $this->backWithMessage('Lỗi khi đọc file Excel: ' . $e->getMessage(), 'error', 'Lỗi Import', '/Baitaplon/Khohang');
                }
            }
        }
    }

    

    
    public function KiemKe() {
        if(!isset($_SESSION['gio_kiem'])) $_SESSION['gio_kiem'] = [];

        $keyword = "";
        if(isset($_POST['btnTimKiem'])) $keyword = $_POST['txtTimKiem'];

        $this->view("Master", [
            "page" => "Kiemkho_V",
            "sp" => $this->khoModel->GetAllSP(),
            "nv" => $this->khoModel->GetAllNhanvien(),
            "lichsu" => $this->khoModel->GetLichSuKiemKe($keyword),
            "keyword" => $keyword
        ]);
    }

    
    public function ThemKiem() {
        if(isset($_POST['btnThem'])) {
            $id = $_POST['ddlSanPham'];
            $thucte = $_POST['txtThucTe'];
            $tonThucHopLe = filter_var($thucte, FILTER_VALIDATE_INT);
            if ($tonThucHopLe === false || $tonThucHopLe < 0) {
                $this->backWithMessage('Tồn thực tế phải là số nguyên không âm.', 'warning', 'Cảnh báo', '/Baitaplon/Khohang/KiemKe');
                return;
            }
            $lydo = $_POST['txtLyDo'];

            
            
            
            $row = mysqli_fetch_array($this->khoModel->GetSP($id));
            if (!$row) {
                $this->backWithMessage('Không tìm thấy sản phẩm để kiểm kê.', 'warning', 'Cảnh báo', '/Baitaplon/Khohang/KiemKe');
                return;
            }
            if ((int)$row['SoLuongTon'] !== $tonThucHopLe && trim($lydo) === '') {
                $this->backWithMessage('Vui lòng ghi lý do khi số tồn thực tế lệch với tồn trên hệ thống.', 'warning', 'Cảnh báo', '/Baitaplon/Khohang/KiemKe');
                return;
            }
            
            $item = [
                'id' => $id,
                'ten' => $row['TenSP'],
                'tonmay' => $row['SoLuongTon'], 
                'tonthuc' => $thucte,           
                'lydo' => $lydo
            ];

            $_SESSION['gio_kiem'][] = $item;
            header("Location: /Baitaplon/Khohang/KiemKe");
        }
    }

    
    public function XoaKiem($index) {
        if(isset($_SESSION['gio_kiem'][$index])) {
            unset($_SESSION['gio_kiem'][$index]);
            $_SESSION['gio_kiem'] = array_values($_SESSION['gio_kiem']);
        }
        header("Location: /Baitaplon/Khohang/KiemKe");
    }

    
    public function LuuPhieuKiem() {
        if(isset($_SESSION['gio_kiem']) && count($_SESSION['gio_kiem']) > 0) {
            $ghichu = $_POST['txtGhiChu'];
            $manv = isset($_POST['ddlNhanVien']) ? trim($_POST['ddlNhanVien']) : '';
            if ($manv === '') {
                $this->backWithMessage('Vui lòng chọn nhân viên kiểm kê.', 'warning', 'Cảnh báo', '/Baitaplon/Khohang/KiemKe');
                return;
            }

            /*foreach ($_SESSION['gio_kiem'] as $item) {
                if ($item['tonmay'] != $item['tonthuc'] && empty($item['lydo'])) {
                    echo "<script>alert('CÃ³ sáº£n pháº©m lá»‡ch kho chÆ°a nháº­p lÃ½ do!'); window.history.back();</script>";
                    return; 
                }
            }*/
            
            
            if (!$this->khoModel->LuuKiemKho($manv, $ghichu, $_SESSION['gio_kiem'])) {
                $this->backWithMessage('Không thể lưu phiếu kiểm kê.', 'error', 'Lỗi lưu phiếu', '/Baitaplon/Khohang/KiemKe');
                return;
            }
            
            unset($_SESSION['gio_kiem']);
            $this->redirectWithMessage('/Baitaplon/Khohang/KiemKe', 'Đã cân bằng kho và lưu phiếu kiểm kê thành công!', 'success', 'Cân bằng kho thành công');
        } else {
            $this->redirectWithMessage('/Baitaplon/Khohang/KiemKe', 'Chưa có sản phẩm nào trong danh sách kiểm kê!', 'warning', 'Cảnh báo');
        }
    }

    
    public function XuatExcelKiemKe() {
        $this->startExcelExport();
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('Lich Su Kiem Ke');
        $rowCount = 1;

        $sheet->setCellValue('A1', 'MÃ£ Phiáº¿u');
        $sheet->setCellValue('B1', 'Ghi ChÃº');
        $sheet->setCellValue('C1', 'Chi Tiáº¿t Kiá»ƒm (MÃ¡y -> Thá»±c)');
        $sheet->setCellValue('D1', 'NgÃ y Kiá»ƒm');

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(60);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFA500'); // MÃ u cam

        $data = $this->khoModel->GetLichSuKiemKe("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            
            $chitiet = str_replace('</div>', "\n", $row['ChiTietKiem']);
            $chitiet = strip_tags($chitiet);
            $chitiet = trim($chitiet);

            $sheet->setCellValue('A' . $rowCount, '#PK' . str_pad($row['MaPK'], 3, '0', STR_PAD_LEFT));
            $sheet->setCellValue('B' . $rowCount, $row['GhiChu']);
            $sheet->setCellValue('C' . $rowCount, $chitiet);
            $sheet->setCellValue('D' . $rowCount, date('d/m/Y H:i', strtotime($row['NgayKiem'])));
            $sheet->getStyle('C' . $rowCount)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A' . $rowCount . ':D' . $rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        }

        $fileName = 'LichSuKiemKe.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        $this->downloadExcel($objWriter, $fileName);
    }

    
    public function NhapExcelKiemKe() {
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
                        // Cáº¥u trÃºc: A: MÃ£(Bá») | B: Ghi ChÃº | C: Chi Tiáº¿t | D: NgÃ y
                        $ghichu = $sheet->getCell('B' . $i)->getValue();
                        $strChiTiet = $sheet->getCell('C' . $i)->getValue();
                        $ngay = $sheet->getCell('D' . $i)->getValue();

                        if(is_numeric($ngay)) $ngay = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($ngay));

                        // PhÃ¢n tÃ­ch chi tiáº¿t: "TÃªn SP | MÃ¡y: 10 -> Thá»±c: 8 (LÃ½ do)"
                        $arrChiTiet = [];
                        if(!empty($strChiTiet)) {
                            $lines = explode("\n", $strChiTiet);
                            foreach($lines as $line) {
                                // Regex: TÃªn SP | MÃ¡y: 10 -> Thá»±c: 8 (LÃ½ do)
                                if(preg_match('/^(.*?) \| MÃ¡y: (\d+) -> Thá»±c: (\d+) \((.*?)\)/', trim($line), $matches)) {
                                    $tenSP = trim($matches[1]);
                                    $may = $matches[2];
                                    $thuc = $matches[3];
                                    $lydo = $matches[4];

                                    $masp = $this->khoModel->GetMaSPByTen($tenSP);
                                    if($masp) {
                                        $arrChiTiet[] = ['masp' => $masp, 'may' => $may, 'thuc' => $thuc, 'lydo' => $lydo];
                                    }
                                }
                            }
                        }

                        if (!empty($arrChiTiet)) {
                            $this->khoModel->ImportPhieuKiemFull(1, $ngay, $ghichu, $arrChiTiet);
                            $count++;
                        }
                    }
                    $this->redirectWithMessage('/Baitaplon/Khohang/KiemKe', "Đã import thành công $count phiếu kiểm kê từ file Excel!", 'success', 'Import thành công');
                } catch (Exception $e) {
                    $this->backWithMessage('Lỗi khi đọc file Excel: ' . $e->getMessage(), 'error', 'Lỗi Import', '/Baitaplon/Khohang');
                }
            }
        }
    }
}
?>
