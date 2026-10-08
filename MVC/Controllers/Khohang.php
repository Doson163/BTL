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
            "nv" => $this->khoModel->GetAllNhanvien(),       
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
                echo "<script>alert('Sá»‘ lÆ°á»£ng vÃ  giÃ¡ nháº­p pháº£i lÃ  sá»‘ nguyÃªn dÆ°Æ¡ng.'); window.history.back();</script>";
                return;
            }

            /*if ($gia <= 0) {
                echo "<script>alert('GiÃ¡ nháº­p pháº£i lá»›n hÆ¡n 0!'); window.history.back();</script>";
                return; // Dá»«ng ngay
            }*/

            

            
            $sp = mysqli_fetch_array($this->khoModel->GetSP($id));

            
            $item = [
                'id' => $id,
                'ten' => $sp['TenSP'],
                'soluong' => $sl,
                'gia' => $gia
            ];

            
            $_SESSION['gio_nhap'][] = $item;

            
            header("Location: http://localhost/Baitaplon/Khohang");
        }
    }

    
    public function XoaTam($index) {
        if(isset($_SESSION['gio_nhap'][$index])) {
            unset($_SESSION['gio_nhap'][$index]); // XÃ³a pháº§n tá»­ táº¡i vá»‹ trÃ­ $index
            $_SESSION['gio_nhap'] = array_values($_SESSION['gio_nhap']); // Sáº¯p xáº¿p láº¡i chá»‰ sá»‘ máº£ng (0,1,2...) trÃ¡nh lá»—i
        }
        header("Location: http://localhost/Baitaplon/Khohang");
    }

    
    public function LuuPhieu() {
        
        if(isset($_SESSION['gio_nhap']) && count($_SESSION['gio_nhap']) > 0) {
            
            $mancc = $_POST['ddlNCC'];
            $manv = isset($_POST['ddlNhanVien']) ? trim($_POST['ddlNhanVien']) : '';
            if ($manv === '') {
                echo "<script>alert('Vui lÃ²ng chá»n nhÃ¢n viÃªn kiá»ƒm kÃª.'); window.history.back();</script>";
                return;
            }
            
            
            $tongtien = 0;
            foreach($_SESSION['gio_nhap'] as $item) {
                $tongtien += $item['soluong'] * $item['gia'];
            }

            
            $kq = $this->khoModel->NhapHang($mancc, $manv, $tongtien, $_SESSION['gio_nhap']);

            if($kq) {
                unset($_SESSION['gio_nhap']); 
                echo "<script>alert('Nháº­p kho thÃ nh cÃ´ng!'); window.location.href='http://localhost/Baitaplon/Khohang';</script>";
            } else {
                echo "<script>alert('Lá»—i nháº­p kho! Vui lÃ²ng kiá»ƒm tra láº¡i.'); window.location.href='http://localhost/Baitaplon/Khohang';</script>";
            }

        } else {
            echo "<script>alert('ChÆ°a chá»n sáº£n pháº©m nÃ o!'); window.location.href='http://localhost/Baitaplon/Khohang';</script>";
        }
    }

    
    public function XuatExcelLichSu() {
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
        $objWriter->save($fileName);
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($fileName));
        readfile($fileName);
        unlink($fileName);
        exit;
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
                    echo "<script>alert('ÄÃ£ import thÃ nh cÃ´ng $count phiáº¿u nháº­p!'); window.location.href='http://localhost/Baitaplon/Khohang';</script>";

                } catch (Exception $e) {
                    echo "<script>alert('Lá»—i: " . $e->getMessage() . "'); window.history.back();</script>";
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
                echo "<script>alert('Tá»“n thá»±c táº¿ pháº£i lÃ  sá»‘ nguyÃªn khÃ´ng Ã¢m.'); window.history.back();</script>";
                return;
            }
            $lydo = $_POST['txtLyDo'];

            
            
            
            $row = mysqli_fetch_array($this->khoModel->GetSP($id));
            if (!$row) {
                echo "<script>alert('KhÃ´ng tÃ¬m tháº¥y sáº£n pháº©m Ä‘á»ƒ kiá»ƒm kÃª.'); window.history.back();</script>";
                return;
            }
            if ((int)$row['SoLuongTon'] !== $tonThucHopLe && trim($lydo) === '') {
                echo "<script>alert('Vui lÃ²ng ghi lÃ½ do khi sá»‘ tá»“n thá»±c táº¿ khÃ¡c tá»“n trÃªn mÃ¡y.'); window.history.back();</script>";
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
            header("Location: http://localhost/Baitaplon/Khohang/KiemKe");
        }
    }

    
    public function XoaKiem($index) {
        if(isset($_SESSION['gio_kiem'][$index])) {
            unset($_SESSION['gio_kiem'][$index]);
            $_SESSION['gio_kiem'] = array_values($_SESSION['gio_kiem']);
        }
        header("Location: http://localhost/Baitaplon/Khohang/KiemKe");
    }

    
    public function LuuPhieuKiem() {
        if(isset($_SESSION['gio_kiem']) && count($_SESSION['gio_kiem']) > 0) {
            $ghichu = $_POST['txtGhiChu'];
            $manv = isset($_POST['ddlNhanVien']) ? trim($_POST['ddlNhanVien']) : '';
            if ($manv === '') {
                echo "<script>alert('Vui lÃ²ng chá»n nhÃ¢n viÃªn kiá»ƒm kÃª.'); window.history.back();</script>";
                return;
            }

            /*foreach ($_SESSION['gio_kiem'] as $item) {
                if ($item['tonmay'] != $item['tonthuc'] && empty($item['lydo'])) {
                    echo "<script>alert('CÃ³ sáº£n pháº©m lá»‡ch kho chÆ°a nháº­p lÃ½ do!'); window.history.back();</script>";
                    return; 
                }
            }*/
            
            
            if (!$this->khoModel->LuuKiemKho($manv, $ghichu, $_SESSION['gio_kiem'])) {
                echo "<script>alert('Could not save inventory count.'); window.history.back();</script>";
                return;
            }
            
            unset($_SESSION['gio_kiem']);
            echo "<script>alert('ÄÃ£ cÃ¢n báº±ng kho thÃ nh cÃ´ng!'); window.location.href='http://localhost/Baitaplon/Khohang/KiemKe';</script>";
        } else {
            echo "<script>alert('ChÆ°a cÃ³ sáº£n pháº©m nÃ o!'); window.location.href='http://localhost/Baitaplon/Khohang/KiemKe';</script>";
        }
    }

    
    public function XuatExcelKiemKe() {
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
        $objWriter->save($fileName);
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($fileName));
        readfile($fileName);
        unlink($fileName);
        exit;
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
                    echo "<script>alert('ÄÃ£ import $count phiáº¿u kiá»ƒm kÃª!'); window.location.href='http://localhost/Baitaplon/Khohang/KiemKe';</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Lá»—i: " . $e->getMessage() . "'); window.history.back();</script>";
                }
            }
        }
    }
}
?>
