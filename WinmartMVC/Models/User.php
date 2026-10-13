<?php
class User extends DB {
    public function CheckLogin($user, $pass) {
        $stmt=mysqli_prepare($this->con,'SELECT * FROM taikhoan WHERE username=? AND password=? LIMIT 1');
        if(!$stmt) return false;
        mysqli_stmt_bind_param($stmt,'ss',$user,$pass);
        if(mysqli_stmt_execute($stmt)) {
            $account = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            if ($account) return $account;
        }

        // Optional first administrator account for a fresh Docker deployment.
        $adminUser = getenv('ADMIN_USERNAME') ?: '';
        $adminPassword = getenv('ADMIN_PASSWORD') ?: '';
        if ($adminUser !== '' && $adminPassword !== '' && hash_equals($adminUser, (string)$user) && hash_equals($adminPassword, (string)$pass)) {
            return ['username'=>$adminUser, 'hoten'=>getenv('ADMIN_DISPLAY_NAME') ?: 'Quản trị viên', 'role'=>1];
        }
        return false;
    }
}
?>
