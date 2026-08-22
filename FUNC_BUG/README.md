# TỔNG HỢP & PHÂN LOẠI LỖI HỆ THỐNG (FUNC_BUG)

Tài liệu này lưu trữ danh mục các lỗi đã từng xảy ra, phân tích nguyên nhân gốc rễ và quy trình khắc phục để phục vụ việc tra soát, bàn giao và vận hành hệ thống.

---

## 📑 BẢNG PHÂN LOẠI LỖI (BUG CLASSIFICATION INDEX)

### 🌐 NHÓM 1: LỖI NGOÀI CODE DỰ ÁN (Môi trường, Web Server, Hạ tầng, DNS, Host)
*Các lỗi phát sinh do cấu hình Web Server (Apache/Nginx), Virtual Host (Laragon), phần mềm máy chủ hoặc môi trường hệ điều hành.*

| Mã Lỗi | Tên Lỗi & Hiện Tượng | Nguyên Nhân Chính | Tài Liệu Chi Tiết |
| :--- | :--- | :--- | :--- |
| **ENV-01** | **Truy cập domain ảo (`.test`) bị tự động tải file `index.php` (1.892 B) về máy** | File cấu hình Virtual Host của Laragon đặt sai `DocumentRoot` (trỏ vào thư mục cha thay vì `/public`) kết hợp xung đột `AddHandler` của cPanel. | [Xem chi tiết](file:///e:/web/vattunongnghiep58/FUNC_BUG/01_BUG_VIRTUALHOST_AUTO_DOWNLOAD_FILE.md) |
| **ENV-02** | **Lỗi `InvalidArgumentException: Please provide a valid cache path` khi chạy composer trên server** | Hàm `realpath(storage_path('framework/views'))` trả về `false` do thư mục chưa được tạo khi git clone/pull lên server. | [Xem chi tiết](file:///e:/web/vattunongnghiep58/FUNC_BUG/04_BUG_INVALID_CACHE_PATH_STORAGE_VIEWS.md) |

---

### 💻 NHÓM 2: LỖI TRONG CODE DỰ ÁN (Mã nguồn, Controller, Model, Blade, Package)
*Các lỗi phát sinh do cú pháp PHP/Blade, cấu hình logic ứng dụng, vòng đời Service Provider hoặc cơ chế bảo mật nội bộ.*

| Mã Lỗi | Tên Lỗi & Hiện Tượng | Nguyên Nhân Chính | Tài Liệu Chi Tiết |
| :--- | :--- | :--- | :--- |
| **SRC-01** | **CKFinder: Mã 109 ("Invalid request") & HTTP 500 Internal Server Error** | Xung đột token CSRF riêng của CKFinder trong Iframe và Service Binding sai phương thức trong `CKFinderServiceProvider`. | [Xem chi tiết](file:///e:/web/vattunongnghiep58/FUNC_BUG/02_BUG_CKFINDER_109_500.md) |
| **CODE-02** | **Lỗi cú pháp Blade Parse Error do nhúng `@include` sai bên trong thẻ `@script`** | Thẻ `@script` của Livewire/Blade xử lý nội dung như Javascript thuần, nhúng `@include` vào bên trong gây lỗi cú pháp PHP/Blade khi compile view. | [Xem chi tiết](file:///e:/web/vattunongnghiep58/FUNC_BUG/03_BUG_BLADE_SYNTAX_PARSE_ERROR.md) |
| **CODE-03** | **Lỗi "Hệ thống phát hiện hành vi bất thường" khi gửi Form liên hệ trên Local/Dev** | Google reCAPTCHA v3 trả về `false` do domain `.test` không nằm trong whitelist, dẫn đến biểu thức `false < 0.3` đánh giá thành `true`. | [Xem chi tiết](file:///e:/web/vattunongnghiep58/FUNC_BUG/05_BUG_RECAPTCHA_BLOCK_ON_LOCAL_ENVIRONMENT.md) |

---

## 📌 NGUYÊN TẮC PHÒNG NGỪA CHUNG KHI VẬN HÀNH

1. **Khi chuyển đổi / nhân bản thư mục dự án Laravel mới**:
   - Luôn kiểm tra file Virtual Host của Apache/Nginx trong Laragon đảm bảo `DocumentRoot` trỏ đúng vào thư mục con `/public`.
   - Xóa bỏ các file tĩnh (`index.php`, `index.html`) cũ ở thư mục gốc để tránh Laragon auto-detect nhầm.
   - Luôn chạy `php artisan optimize:clear` sau khi thay đổi cấu hình hoặc chuyển thư mục.

2. **Khi chỉnh sửa file Blade template**:
   - Tuyệt đối không để khối `@php ... @endphp` bị hở trước khi gọi directive khác.
   - Luôn sử dụng đúng chuẩn Grid Bootstrap (`row > col-md-* > form-group mb-3`) để tránh vỡ giao diện.
