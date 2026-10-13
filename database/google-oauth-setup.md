# Bật đăng nhập Google cho Win Mart

Nút Google chưa đăng nhập được vì máy hiện chưa có OAuth Client ID và Client Secret. Đây là thông tin xác thực OAuth riêng của ứng dụng, không phải API key.

1. Trong Google Cloud Console, tạo OAuth Client ID loại **Web application**.
2. Thêm chính xác URI này vào **Authorized redirect URIs**:
   `http://localhost/Baitaplon/store.php?url=CustomerAuth/GoogleCallback`
3. Sao chép `MVC/Config/google-oauth.example.php` thành `MVC/Config/google-oauth.local.php`.
4. Mở file mới và thay `YOUR_GOOGLE_OAUTH_CLIENT_ID` cùng `YOUR_GOOGLE_OAUTH_CLIENT_SECRET` bằng giá trị Google cấp. Không sửa `redirect_uri`.
5. Khi chạy Docker, cURL đã được cài trong image PHP. Sau khi đổi biến môi trường, chạy lại `docker compose up -d --build`.
6. Khách hàng cần đăng ký Win Mart bằng cùng email Google trước khi dùng nút Google.

Trang khách hàng: `http://localhost/Baitaplon/User`  
Trang quản trị: `http://localhost/Baitaplon/Admin`

File `google-oauth.local.php` đã được thêm vào `.gitignore` để tránh đưa Client Secret lên Git. Xem [hướng dẫn OAuth chính thức của Google](https://developers.google.com/identity/protocols/oauth2/web-server) để tạo thông tin xác thực.
