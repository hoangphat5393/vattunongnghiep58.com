# CHANGELOG

## 2026-02-16

- Thay thế layout sidebar sản phẩm cũ (h2 “DANH MỤC SẢN PHẨM”) bằng layout mới sử dụng Tailwind trong `resources/views/frontend/includes/categories_sidebar.blade.php`.
- Cập nhật trang chủ để sử dụng sidebar mới trong `resources/views/frontend/home.blade.php`.
- Cập nhật trang danh sách sản phẩm để sử dụng sidebar mới trong `resources/views/frontend/product/index.blade.php`.
- Chạy `php artisan test --testsuite=Unit` – tất cả unit test đều pass.

### Cập nhật header & điều chỉnh accessibility

- Ẩn header legacy cũ (block chứa “Cửa Hàng Vật Tư Nông Nghiệp 58”) bằng class Tailwind `hidden` trong `resources/views/frontend/layouts/header.blade.php` để giao diện phần trên cùng tương thích hơn với mẫu header mới.
- Bổ sung thuộc tính `role="navigation"` và `aria-label="Main navigation"` cho thẻ `<nav>` trong `resources/views/frontend/includes/menu.blade.php` nhằm cải thiện khả năng truy cập (WCAG 2.2 AA).
- Thêm thuộc tính `role="search"`, `aria-label` cho form và input tìm kiếm, cùng `aria-label` cho nút submit, giúp người dùng dùng trình đọc màn hình nhận diện đúng chức năng.
- Chạy lại `php artisan test --testsuite=Unit` – tất cả unit test vẫn pass sau khi thay đổi.
