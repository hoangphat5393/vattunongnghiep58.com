# 01. LỖI TRUY CẬP DOMAIN ẢO (.TEST) BỊ TỰ ĐỘNG TẢI FILE INDEX.PHP VỀ MÁY

---

## 🏷️ Phân Loại:
> **`[LỖI NGOÀI CODE DỰ ÁN - MÔI TRƯỜNG / HẠ TẦNG WEB SERVER (LARAGON APACHE)]`**

---

## 1. Hiện Tượng (Symptom)
- Khi người dùng truy cập vào domain nội bộ trên trình duyệt (ví dụ: `http://3nong.test` trên Cốc Cốc hoặc Chrome), website **không hiển thị giao diện HTML** mà trình duyệt tự động kích hoạt popup tải một tệp về máy:
  - Tên file tải về: `tải xuống` hoặc `tải xuống (1)`
  - Dung lượng file: **`1.892 Bytes`** (hoặc xấp xỉ ~1.8 KB).

---

## 2. Bằng Chứng & Phân Tích Kỹ Thuật (Investigation & Evidence)

1. **Khớp chính xác từng byte dữ liệu**:
   - Tệp tin `public/index.php` trong source code Laravel có kích thước chính xác là **`1.892 Bytes`**.
   - Điều này chứng minh Web Server (Apache) đã gửi toàn bộ nội dung mã nguồn của file `public/index.php` về cho client thay vì đưa qua trình thông dịch PHP để thực thi.

2. **Dữ liệu Header phản hồi từ Apache (CURL Request)**:
   ```http
   HTTP/1.1 200 OK
   Server: Apache/2.4.66 (Win64) OpenSSL/3.0.18 PHP/8.3.30
   Content-Length: 1892
   Content-Type: application/x-httpd-php
   ```
   - Apache trả về `Content-Type: application/x-httpd-php` (MIME Type dạng nhị phân/tập tin) thay vì `text/html; charset=utf-8`.
   - Trình duyệt nhận header này sẽ hiểu đây là file đính kèm cần tải về máy.

---

## 3. Nguyên Nhân Gốc Rễ (Root Causes)

### A. Cấu hình Virtual Host của Laragon trỏ sai `DocumentRoot`
- Trong file cấu hình tự sinh của Laragon (`E:\laragon\etc\apache2\sites-enabled\auto.3nong.test.conf`), đường dẫn gốc bị đặt vào thư mục cha:
  ```apache
  # SAI: Trỏ vào thư mục gốc của project
  define ROOT "E:/web/3nong"
  ```
  *(Trong khi chuẩn của Laravel bắt buộc phải trỏ vào thư mục con: `define ROOT "E:/web/3nong/public"`).*

### B. Xung đột chỉ thị cấu hình cPanel trong file `.htaccess`
- Khi Virtual Host trỏ vào thư mục gốc `E:/web/3nong`, Apache sẽ đọc file `.htaccess` tại thư mục này.
- Trong file có chứa đoạn chỉ thị dành riêng cho hosting Linux cPanel (CloudLinux):
  ```apache
  <IfModule mime_module>
    AddHandler application/x-httpd-alt-php83 .php .php8 .phtml
  </IfModule>
  ```
- Apache trên Windows (Laragon) không có module xử lý handler `application/x-httpd-alt-php83`, dẫn đến việc Apache không chuyển tiếp file cho PHP engine mà trả thẳng file về client dưới dạng download stream.

### C. Tồn tại các file tĩnh cũ (`index.php`, `index.html`) ở thư mục gốc
- Ở thư mục gốc có các file tĩnh cũ (`index.html` 118KB và `index.php` 64B) khiến cơ chế auto-detect của Laragon hiểu nhầm đây là trang web thuần chứ không phải Laravel Framework để tự thêm `/public`.

---

## 4. Quy Trình Khắc Phục Triệt Để (Step-by-Step Solution)

### Bước 1: Sửa file cấu hình Virtual Host của Apache trong Laragon
Mở file `E:\laragon\etc\apache2\sites-enabled\auto.3nong.test.conf` và sửa `define ROOT` trỏ đúng vào thư mục `public`:
```apache
define ROOT "E:/web/3nong/public"
define SITE "3nong.test"

<VirtualHost *:80> 
    DocumentRoot "${ROOT}"
    ServerName ${SITE}
    ServerAlias *.${SITE}
    <Directory "${ROOT}">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:443>
    DocumentRoot "${ROOT}"
    ServerName ${SITE}
    ServerAlias *.${SITE}
    <Directory "${ROOT}">
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on
    SSLCertificateFile      E:/laragon/etc/ssl/laragon.crt
    SSLCertificateKeyFile   E:/laragon/etc/ssl/laragon.key
</VirtualHost>
```

### Bước 2: Dọn dẹp file tĩnh ở thư mục gốc
Xóa bỏ các file `index.php` và `index.html` nằm ngoài thư mục gốc của project (chỉ giữ lại duy nhất `public/index.php`).

### Bước 3: Tắt dòng `AddHandler` cPanel trong `.htaccess`
Tại file `.htaccess` (thư mục gốc), chú thích (comment out) đoạn sau:
```apache
# <IfModule mime_module>
#   AddHandler application/x-httpd-alt-php83 .php .php8 .phtml
# </IfModule>
```

### Bước 4: Khởi động lại Apache & Xóa cache Laravel
1. Trên giao diện **Laragon**: Bấm **`Stop All`** $\rightarrow$ bấm **`Start All`** (để nạp lại cấu hình vhost mới).
2. Chạy lệnh làm sạch cache Laravel:
   ```bash
   php artisan optimize:clear
   ```
3. Mở trình duyệt truy cập `http://3nong.test` $\rightarrow$ Website hiển thị bình thường.
