# Chạy Win Mart bằng Docker

Docker Compose thay thế XAMPP bằng hai dịch vụ: Apache/PHP và MariaDB. Ứng dụng vẫn được phục vụ dưới đường dẫn `/Baitaplon`.

## Chạy trên máy cá nhân

1. Cài Docker Desktop và khởi động Docker.
2. Sao chép `.env.example` thành `.env`.
3. Đổi `DB_PASSWORD`, `DB_ROOT_PASSWORD` và `ADMIN_PASSWORD` thành các mật khẩu riêng, dài và khó đoán. Không đưa `.env` lên Git.
4. Mở terminal tại thư mục dự án và chạy:

   ```sh
   docker compose up -d --build
   ```

5. Mở trang khách hàng tại `http://localhost:8080/Baitaplon/User` hoặc trang quản trị tại `http://localhost:8080/Baitaplon/Admin`.
6. Đăng nhập quản trị bằng `ADMIN_USERNAME` và `ADMIN_PASSWORD` trong `.env`. Tạo danh mục và sản phẩm đầu tiên trong trang quản trị.

Theo dõi log bằng `docker compose logs -f`. Dừng dịch vụ bằng `docker compose down`. Dữ liệu MariaDB được giữ trong volume `winmart_db` khi dừng hoặc cập nhật container.

## Dữ liệu cơ sở dữ liệu

`database/01-baitaplon-schema.sql` chỉ chứa cấu trúc bảng đã lấy từ dự án hiện tại; không chứa tài khoản, khách hàng, đơn hàng hoặc dữ liệu kinh doanh hiện có. Docker tự khởi tạo cấu trúc và phương thức thanh toán COD khi volume cơ sở dữ liệu còn mới. Sản phẩm và danh mục có thể thêm từ trang quản trị.

Các tệp SQL khởi tạo chỉ chạy lần đầu khi volume trống. Nếu cần xóa toàn bộ dữ liệu để tạo lại từ đầu, lệnh `docker compose down -v` sẽ xóa vĩnh viễn volume MariaDB.

## Đăng nhập Google và triển khai lên máy chủ

Khi triển khai, đặt `APP_BASE_URL` thành URL công khai đầy đủ, ví dụ `https://shop.example.com/Baitaplon`, và đặt `WINMART_GOOGLE_REDIRECT_URI` thành:

`https://shop.example.com/Baitaplon/store.php?url=CustomerAuth/GoogleCallback`

Thêm chính xác URI callback đó vào **Authorized redirect URIs** trong Google Cloud Console. Điền Client ID và Client Secret vào biến môi trường tương ứng trong `.env`. Dùng HTTPS và cấu hình domain/reverse proxy trên máy chủ trước khi cho khách truy cập. Không dùng các mật khẩu mẫu trong `.env.example` trên máy chủ thật.

Các biến `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` cũng được ứng dụng đọc trực tiếp nên có thể dùng khi chạy bên ngoài Docker.
