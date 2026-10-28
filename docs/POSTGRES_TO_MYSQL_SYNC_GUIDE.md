# Hướng Dẫn Tính Năng Đồng Bộ & Xuất Dữ Liệu: PostgreSQL ➔ MySQL

> **Dự án:** Vật Tư Nông Nghiệp 58 (`vattunongnghiep58`)  
> **Cập nhật:** 2026-10-28  
> **Mục đích:** Hỗ trợ phát triển và vận hành khi hệ thống chạy chính trên PostgreSQL 16 nhưng cần đưa dữ liệu hoặc triển khai lên môi trường MySQL (Hosting cPanel, phpMyAdmin, v.v.).

---

## 1. Tổng Quan Tính Năng

Môi trường phát triển của dự án sử dụng **PostgreSQL 16** làm cơ sở dữ liệu chính thức. Tuy nhiên, khi cần bàn giao, sao lưu hoặc đưa lên các máy chủ chỉ hỗ trợ MySQL, việc export trực tiếp từ PostgreSQL (`pg_dump`) sẽ không thể import vào MySQL do khác biệt về cú pháp (SQL dialect).

Hệ thống đã được tích hợp sẵn giải pháp **1-Click Đồng bộ & Xuất file SQL chuẩn MySQL 100%**:
* Tự động chuyển đổi các kiểu dữ liệu đặc thù (`boolean` ➔ `tinyint(1)`, `jsonb`/mảng ➔ `json string`).
* Reset chính xác `AUTO_INCREMENT` cho tất cả các bảng.
* Giữ nguyên vẹn toàn bộ quan hệ Khóa ngoại (Foreign Keys).
* Tự động sinh file `.sql` có sẵn `DROP TABLE IF EXISTS` và `ENGINE=InnoDB DEFAULT CHARSET=utf8mb4`.

---

## 2. Các Thành Phần Trong Dự Án

| Thành phần | Đường dẫn | Chức năng |
| :--- | :--- | :--- |
| **Batch script 1-Click** | `export_mysql.bat` | Click đúp để tự động chạy toàn bộ quy trình và sinh file `.sql` |
| **Artisan Command** | `app/Console/Commands/SyncPostgresToMysql.php` | Xử lý logic đồng bộ dữ liệu theo chunk và gọi `mysqldump` |
| **Cấu hình DB** | `config/database.php` (mục `mysql_sync`) | Kết nối tới MySQL local (mặc định port 3306, user `root`, pass `1`, db `vattunongnghiep58`) |
| **Phím tắt Composer** | `composer.json` (script `export-mysql`) | Chạy lệnh qua CLI: `composer export-mysql` |
| **File Dump kết quả** | `database/vattunongnghiep58_mysql_latest.sql` | File SQL chuẩn MySQL hoàn chỉnh dùng để import |

---

## 3. Cách Sử Dụng

### Cách 1: Nhanh nhất (Giao diện Windows)
* Mở thư mục gốc của dự án `e:\web\vattunongnghiep58\`.
* Click đúp chuột vào file **`export_mysql.bat`**.
* Cửa sổ dòng lệnh sẽ tự động chạy trong khoảng 3–5 giây, in bảng đối soát số dòng và thông báo hoàn tất.

### Cách 2: Dùng Composer
Mở Terminal tại thư mục dự án và chạy:
```bash
composer export-mysql
```

### Cách 3: Chạy trực tiếp Artisan Command
```bash
# Đồng bộ toàn bộ và xuất file dump .sql
php artisan db:sync-to-mysql

# Chỉ đồng bộ 1 bảng cụ thể (ví dụ: bảng products)
php artisan db:sync-to-mysql --table=products

# Chỉ đồng bộ dữ liệu vào MySQL local, không xuất file .sql
php artisan db:sync-to-mysql --skip-dump
```

---

## 4. Cơ Chế Xử Lý Kỹ Thuật

1. **Kiểm tra kết nối song song:** Kiểm tra PDO của cả 2 kết nối `pgsql` và `mysql_sync`.
2. **Khóa tạm thời Foreign Key:** Thực thi `SET FOREIGN_KEY_CHECKS=0;` trên MySQL để tránh lỗi xung đột thứ tự ràng buộc.
3. **Đồng bộ theo Chunk an toàn:** Đọc dữ liệu từ PostgreSQL theo từng block (300 dòng/lô), tự làm sạch bảng MySQL cũ (`truncate`) và nạp dữ liệu mới.
4. **Khử lệch kiểu dữ liệu:**
   - Giá trị boolean (`true`/`false`) chuyển thành `1`/`0`.
   - Dữ liệu dạng mảng hoặc object tự động chuyển thành JSON UTF-8 an toàn (`json_encode(..., JSON_UNESCAPED_UNICODE)`).
5. **Cân chỉnh AUTO_INCREMENT:** Tính toán `MAX(id) + 1` và thực thi câu lệnh `ALTER TABLE table AUTO_INCREMENT = N` để các bản ghi mới thêm vào sau này không bị trùng ID.
6. **Bật lại Foreign Key:** Thực thi `SET FOREIGN_KEY_CHECKS=1;`.
7. **Đối soát & Xuất Dump:** In bảng tổng kết so sánh số dòng giữa PostgreSQL và MySQL, sau đó gọi `mysqldump` để tạo file `.sql` hoàn chỉnh tại `database/vattunongnghiep58_mysql_latest.sql`.
