<?php
class User extends DB {
    public function CheckLogin($user, $pass) {
        $stmt=mysqli_prepare($this->con,'SELECT * FROM taikhoan WHERE username=? AND password=? LIMIT 1');
        if(!$stmt) return false;
        mysqli_stmt_bind_param($stmt,'ss',$user,$pass);
        if(!mysqli_stmt_execute($stmt)) return false;
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: false;
    }
    public function CheckCustomerLogin($identity) {
        $email=strtolower(trim((string)$identity));
        $phoneDigits=preg_replace('/\D+/','',(string)$identity);
        $phoneLocal=$phoneDigits;
        if(substr($phoneLocal,0,2)==='84' && strlen($phoneLocal)===11) $phoneLocal='0'.substr($phoneLocal,2);
        $sql="SELECT a.MaKH,a.Email,a.DienThoai,a.MatKhauHash,k.TenKH FROM TaiKhoanKhachHang a JOIN Khachhang k ON k.MaKH=a.MaKH WHERE LOWER(a.Email)=? OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(a.DienThoai,'+',''),' ',''),'-',''),'(',''),')',''),'.','') IN (?,?) LIMIT 1";
        $stmt=mysqli_prepare($this->con,$sql);
        if(!$stmt) return null;
        mysqli_stmt_bind_param($stmt,'sss',$email,$phoneDigits,$phoneLocal);
        if(!mysqli_stmt_execute($stmt)) return null;
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
    }
}
?>