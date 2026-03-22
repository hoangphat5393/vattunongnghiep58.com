# Admin UI Upgrade Plan

## 1. Current system

- Laravel project using Blade
- Admin UI: `resources/views/backend/` (layouts, modules)
- Không phải `resources/views/admin` — dùng namespace `backend`

## 2. New UI

- Nguồn: `new-admin-ui/` (AdminLTE v4, bản build trong `dist/`)
- HTML/CSS/JS thuần; shell trùng cấu trúc với app hiện tại (`app-wrapper`, `app-header`, `app-sidebar`, `app-main`)

## 3. Goals

- Nâng UI theo template mới, **không** phá logic backend
- Giữ routes, controllers, queries, biến Blade

## 4. Rules

- Không đổi tên biến Blade không cần thiết
- Không đổi routes / business logic
- Chỉ chỉnh tầng giao diện (Blade + assets public)

## 5. Mapping (tham chiếu)

| Template mới              | Blade hiện tại                                              |
| ------------------------- | ----------------------------------------------------------- |
| `dist/index.html` (shell) | `backend/layouts/master.blade.php`                          |
| Header demo               | `backend/layouts/nav.blade.php`                             |
| Sidebar demo              | `backend/layouts/sidebar.blade.php` (menu động `AdminMenu`) |
| Footer                    | `backend/layouts/footer.blade.php`                          |
| Dashboard                 | `backend/home.blade.php`                                    |
| Layout tối giản           | `backend/layouts/empty.blade.php`                           |

Các module (user, product, orders, …) đã dùng pattern AdminLTE v4 (`app-content-header`, `app-content`, `card`); không bắt buộc đổi HTML từng file trừ khi cần chỉnh riêng từng màn.

## 6. Đã thực hiện (cập nhật)

### 6.1 Assets AdminLTE

- Đồng bộ file build từ `new-admin-ui/dist/` vào `public/assets/admin/`:
    - `css/` — `adminlte.css`, `adminlte.min.css`, RTL, source maps
    - `js/` — `adminlte.js`, `adminlte.min.js`, source maps
- Ảnh demo trong `dist/assets/img` đã copy sang `public/assets/admin/assets/img/` (nếu trùng tên sẽ ghi đè)

### 6.2 `master.blade.php`

- Thêm meta theo template mới: `color-scheme`, `theme-color` (light/dark), `supported-color-schemes`, viewport có `user-scalable=yes`
- OverlayScrollbars: nâng từ bản local 2.10.1 → **CDN 2.11.0** (khớp `new-admin-ui`)
- Thêm **Bootstrap Icons 1.13.1** (CDN) — icon `bi-*` trên navbar/dashboard; trước đó thiếu link trong layout chính
- Giữ nguyên: jQuery, Bootstrap bundle, Font Awesome Pro, plugin (Select2, Flatpickr, CKEditor, …), `@stack`, CSRF, favicon
- Script khởi tạo OverlayScrollbars cho `.sidebar-wrapper`: **không bật trên mobile** (`innerWidth <= 992`) để tránh xung đột cảm ứng, giống demo mới

### 6.3 `empty.blade.php`

- Cùng hướng xử lý: OverlayScrollbars 2.11 CDN, Bootstrap Icons, meta tương tự, scrollbar init có check mobile

### 6.4 `footer.blade.php`

- Copyright năm cập nhật theo template (2014–2026)

### 6.5 Module User + bộ lọc tìm kiếm

- `backend/user/index.blade.php`: nút lọc chỉ còn **icon Font Awesome** `fa-solid fa-magnifying-glass`, có `aria-label`; bảng thêm `table-hover` (giống demo bảng AdminLTE).
- `backend/product/index.blade.php`: nút `@lang('admin.Search')` đổi thành icon + `aria-label`; bảng thêm `table-hover`.
- `backend/product/filter.blade.php`, `backend/orders/filter.blade.php`: nút "Tìm kiếm" → icon + `aria-label`.
- `backend/orders/filter.blade.php`: thay `<section class="content">` bằng `<div class="app-content">` cho đồng bộ AdminLTE v4.
- `backend/product-category/index.blade.php`: nút tìm kiếm → icon FA; sửa **lỗi typo** `</div>/.row -->` (thiếu `<!--`) khiến chuỗi `<!-- /.row -->` hiện trên trang; bỏ `</section>` thừa; dòng tổng dùng `@lang('admin.category')` thay vì `admin.News`; `ml-2` → `ms-2`; thêm `table-hover`.

### 6.6 Đăng nhập admin (login v2)

- `backend/auth/login.blade.php`: bỏ theme cũ `assets/login/*`; dùng **`login-page` + `login-box` + `card card-outline card-primary`** như `new-admin-ui/dist/examples/login-v2.html`.
- Asset: `index.css`, Font Awesome Pro, `adminlte.min.css`; script: jQuery, Bootstrap bundle, `adminlte.min.js`.
- Form: `form-floating` + `input-group-text`, icon **Font Awesome** (`fa-envelope`, mắt ẩn/hiện mật khẩu).
- Thêm checkbox **Ghi nhớ đăng nhập** (`name="remember"`) — tương thích `AuthenticatesUsers` của Laravel, **không** đổi `LoginController`.
- Route / method POST / field `email`, `password` giữ như cũ.

### 6.8 `public/assets/css/style_admin.css`

- Viết lại gọn: bỏ reset HTML/Bootstrap trùng (normalize cũ, `ol/ul` global, `a` màu #3097d1, utility trùng `.p-0`/`.g-*`/`.mh-100`/`.text-white`), bỏ `body.push`/`body.fixed` không dùng, bỏ `@keyframes button-loading-spinner` không tham chiếu.
- Đã **bỏ** `.fl`/`.fr`/`.l`/`.r`/`.clear` — toàn bộ view backend chuyển sang utility Bootstrap 5: `float-start`, `float-end`, `clearfix` (và `d-flex flex-wrap gap-2` cho partial nút thay cho `d-flex fl`).
- Giữ phần **đặc thù CMS**: upload/gallery/biến thể, `#table_index`, Select2, tagsinput, loading spinner, menu đa cấp `#muti_menu_post`, v.v.
- Dùng **`var(--bs-*)`** (màu link, danger, border, primary cho tiêu đề cột bảng) để khớp theme AdminLTE mới.

### 6.7 Chưa đổi (có thể làm sau)

- `nav.blade.php`, `sidebar.blade.php` — menu động, không đổi logic.
- Form user chi tiết (`user/single.blade.php`) — có thể nâng `form-select` / floating labels sau nếu cần.

## 7. Progress

- [x] Layout (`master`, `empty`, assets AdminLTE)
- [x] Sidebar — giữ logic; không thay cấu trúc HTML cốt lõi
- [x] Dashboard — đã tương thích từ trước; chỉ hưởng lợi CSS/JS mới + Bootstrap Icons
- [x] User module — đồng bộ nút tìm kiếm (icon FA) + `table-hover` trên danh sách
- [x] Login admin — theo pattern `login-v2` (AdminLTE v4), icon Font Awesome
