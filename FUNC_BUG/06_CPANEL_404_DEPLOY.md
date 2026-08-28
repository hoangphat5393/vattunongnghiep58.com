# 06. LỖI 404 NOT FOUND KHI DEPLOY CODE LARAVEL LÊN CPANEL LITESPEED WEB SERVER

---

## 🏷️ Phân Loại:
> **`[LỖI NGOÀI CODE DỰ ÁN - MÔI TRƯỜNG / HẠ TẦNG WEB SERVER (CPANEL LITESPEED SUEXEC / PERMISSIONS & REWRITES)]`**

---

## 1. Hiện Tượng (Symptom)
- Sau khi `git pull` hoặc khôi phục mã nguồn Laravel lên hosting cPanel sử dụng máy chủ web **LiteSpeed Web Server (LSWS)**:
  - Khi truy cập vào tên miền chính (`https://vattunongnghiep58.com/`) hoặc bất kỳ tệp tin tĩnh nào (kể cả tệp `test.txt` đặt trong thư mục `public/` hay thư mục gốc), trình duyệt đều trả về trang báo lỗi mặc định của LiteSpeed:
  ```http
  404 Not Found
  The resource requested could not be found on this server!
  ```
- Dù kiểm tra bản ghi DNS (A Record) đã trỏ đúng 100% vào IP của hosting, trang web vẫn không thể tải giao diện hoặc file tĩnh.

---

## 2. Bằng Chứng & Phân Tích Kỹ Thuật (Investigation & Evidence)

1. **Kiểm tra phản hồi mạng (CURL Request)**:
   ```http
   HTTP/1.1 404 Not Found
   Connection: Keep-Alive
   Server: LiteSpeed
   ```
2. **Cơ chế bảo mật LiteSpeed suEXEC (File & Directory Permissions)**:
   - Qua kiểm tra trên FileZilla và Terminal, các thư mục mới tải về (như `vendor/`) mang phân quyền **`0775`** (Group Writable).
   - Máy chủ **LiteSpeed có cơ chế bảo mật suEXEC nghiêm ngặt**: Nếu bất kỳ thư mục nào có quyền ghi cho nhóm (`775` hoặc `777`), LiteSpeed sẽ coi đó là mối đe dọa bảo mật và **lập tức từ chối phục vụ mọi yêu cầu đến thư mục đó**, trả về lỗi **404 Not Found** (thay vì 403 Forbidden hay 500).
   - **Quy chuẩn bắt buộc của LiteSpeed:**
     - Toàn bộ thư mục (Directories): Phải là **`0755`** (`drwxr-xr-x`).
     - Toàn bộ tệp tin (Files): Phải là **`0644`** (`-rw-r--r--`).

3. **Thiếu nhân thư viện `vendor`**:
   - Khi clone/pull từ Git, thư mục `vendor/` bị loại bỏ theo `.gitignore`. Nếu chưa chạy `composer install`, file `public/index.php` không thể load `vendor/autoload.php` khiến ứng dụng PHP bị gián đoạn.

4. **Cấu hình Document Root của Tên miền**:
   - Trong cPanel, nếu Addon Domain trỏ Document Root vào thư mục gốc `vattunongnghiep58.com` thay vì thư mục con `vattunongnghiep58.com/public`, máy chủ sẽ tìm sai file `index.php` điều hướng.

---

## 3. Nguyên Nhân Gốc Rễ (Root Causes)

| STT | Nguyên nhân | Chi tiết kỹ thuật |
| :--- | :--- | :--- |
| 1 | **Vi phạm bảo mật phân quyền suEXEC** | Thư mục `vendor` hoặc thư mục dự án mang quyền `775`/`777`. LiteSpeed chặn không đọc file và trả về 404. |
| 2 | **Chưa nạp thư viện Composer (`vendor`)** | Thiếu thư viện lõi Laravel khiến PHP không thể khởi tạo luồng xử lý ứng dụng. |
| 3 | **Document Root trỏ sai thư mục** | Tên miền cPanel trỏ vào thư mục cha thay vì thư mục `public/` của Laravel. |
| 4 | **Xung đột cấu hình `AddHandler` trong `.htaccess`** | Khai báo handler cố định (ví dụ `alt-php83`) khi hosting sử dụng phiên bản PHP khác gây lỗi dịch file. |

---

## 4. Quy Trình Khắc Phục Triệt Để (Step-by-Step Solution)

### Bước 1: Sửa phân quyền chuẩn LiteSpeed 1-Click (Quan trọng nhất)
Mở cửa sổ **Terminal** trên cPanel và chạy cụm lệnh:
```bash
cd /home/mfxiueoc/vattunongnghiep58.com
find . -type d -exec chmod 755 {} +
find . -type f -exec chmod 644 {} +
chmod 755 /home/mfxiueoc/vattunongnghiep58.com
```

### Bước 2: Cài đặt thư mục `vendor`
Ngay trong Terminal, chạy lệnh tải và tối ưu autoload của Composer:
```bash
composer install --no-dev --optimize-autoloader
```

### Bước 3: Cấu hình Document Root trong cPanel
1. Vào **cPanel** $\rightarrow$ **Domains**.
2. Tìm tên miền dự án $\rightarrow$ Sửa **Document Root** thành:
   ```text
   vattunongnghiep58.com/public
   ```

### Bước 4: Kiểm tra file `.env`
Đảm bảo file `.env` tồn tại trong thư mục gốc dự án với đầy đủ cấu hình Database và `APP_KEY`.

---

## 5. Nguyên Tắc Phòng Ngừa Khi Triển Khai (Best Practices)
1. **Quy tắc phân quyền:** Tuyệt đối không `chmod 777` hoặc `775` cho thư mục trên shared hosting LiteSpeed. Luôn duy trì chuẩn `755` cho folder và `644` cho file.
2. **Quy tắc Document Root:** Đối với tất cả dự án Laravel, Document Root bắt buộc phải trỏ vào thư mục con `/public`.
3. **Quy tắc Git:** Sau khi pull code trên server, luôn chạy `composer install --no-dev` để đảm bảo đồng bộ thư viện phụ thuộc.
