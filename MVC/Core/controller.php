<?php
class controller{
    public function model($model){
        include_once './MVC/Models/'.$model.'.php';
        return new $model;
    }
    public function view($view,$data=[]){
        include_once './MVC/Views/'.$view.'.php';
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
