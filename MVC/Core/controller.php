<?php
class controller{
    public function model($model){
        include_once './MVC/Models/'.$model.'.php';
        return new $model;
    }
    public function view($view,$data=[]){
        include_once './MVC/Views/'.$view.'.php';
    }

    public function setFlash($message, $type = 'success', $title = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($title === null) {
            switch ($type) {
                case 'success': $title = 'Thành công'; break;
                case 'error':   $title = 'Lỗi'; break;
                case 'warning': $title = 'Cảnh báo'; break;
                case 'info':    
                default:        $title = 'Thông báo'; break;
            }
        }
        $_SESSION['flash_message'] = [
            'type' => $type,
            'title' => $title,
            'message' => $message
        ];
    }

    public function redirectWithMessage($url, $message, $type = 'success', $title = null) {
        $this->setFlash($message, $type, $title);
        header('Location: ' . $url);
        exit;
    }

    public function backWithMessage($message, $type = 'warning', $title = null, $defaultUrl = '/Baitaplon/Home') {
        $this->setFlash($message, $type, $title);
        $referer = isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $defaultUrl;
        header('Location: ' . $referer);
        exit;
    }

    /**
     * Fallback in case a direct response with SweetAlert2 dialog is preferred.
     */
    public function renderAlert($message, $redirectUrl = '/Baitaplon/Home', $type = 'info', $title = 'Thông báo') {
        $safeMsg = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeUrl = htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8');
        $safeType = in_array($type, ['success', 'error', 'warning', 'info'], true) ? $type : 'info';
        echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>DailyMart - Thông báo</title>
    <script src='/Baitaplon/Public/Js/sweetalert2.all.min.js'></script>
    <style>
        body { margin: 0; background: #f4f6fa; font-family: 'Segoe UI', Tahoma, sans-serif; height: 100vh; display: grid; place-items: center; }
    </style>
</head>
<body>
    <script>
        Swal.fire({
            icon: '$safeType',
            title: '$safeTitle',
            text: '$safeMsg',
            confirmButtonText: 'Đồng ý',
            confirmButtonColor: '#1a237e',
            allowOutsideClick: false
        }).then(function() {
            window.location.href = '$safeUrl';
        });
    </script>
</body>
</html>";
        exit;
    }

    /** Keep PHPExcel's PHP 8.2 deprecation notices out of downloaded files. */
    protected function startExcelExport(){
        error_reporting(error_reporting() & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        ini_set('display_errors', '0');
        ob_start();
    }

    /** Stream a generated Excel workbook as a download using a writable temp file. */
    protected function downloadExcel($writer, $fileName){
        $fileName = basename($fileName);
        $tempFile = tempnam(sys_get_temp_dir(), 'dailymart_excel_');
        if ($tempFile === false) {
            throw new RuntimeException('Không thể tạo file Excel tạm thời.');
        }

        try {
            $writer->save($tempFile);
            $fileSize = filesize($tempFile);
            if ($fileSize === false || $fileSize === 0) {
                throw new RuntimeException('File Excel được tạo không hợp lệ.');
            }
        } catch (Throwable $error) {
            @unlink($tempFile);
            throw $error;
        }

        // Remove HTML or warning output buffered earlier in the request so it
        // cannot corrupt the XLSX archive sent to the browser.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        readfile($tempFile);
        @unlink($tempFile);
        exit;
    }
}
?>
