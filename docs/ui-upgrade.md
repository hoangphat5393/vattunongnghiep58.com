# Admin UI Upgrade Plan

> **Phiên bản mục tiêu:** AdminLTE **4.1.0** (`new-admin-ui/`)  
> **Cập nhật lần cuối:** 2026-07-02

## 1. Current system

- Laravel project using Blade
- Admin UI: `resources/views/backend/` (layouts, modules)
- Không phải `resources/views/admin` — dùng namespace `backend`

## 2. New UI

- Nguồn: `new-admin-ui/` (AdminLTE v4.1, bản build trong `dist/`)
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
| Theme init                | `backend/partials/admin-theme-init.blade.php`               |

## 6. Đã thực hiện

### 6.1 Assets AdminLTE 4.1.0

- Build `new-admin-ui` (`npm run build`) → sync `dist/css`, `dist/js`, `dist/assets` vào `public/assets/admin/`
- Xác nhận: `adminlte.min.js` header **v4.1.0** (thay thế rc7 cũ)
- **Không** deploy `dist/pages/` demo lên URL production

### 6.2 Layout shell (4.1)

- `master.blade.php`, `empty.blade.php`:
  - `@include('backend.partials.admin-theme-init')` — chống flash theme (`lte-theme`)
  - Font **Source Sans 3** (CDN), preload `adminlte.min.css`
  - OverlayScrollbars 2.11, Bootstrap Icons 1.13.1
  - `adminlte.min.js` 4.1 (ColorMode bundled)
- `nav.blade.php`:
  - Bỏ demo messages/notifications + **navbar-search** (dead widget)
  - **Color mode** light/dark/auto (`data-bs-theme-value`)
  - User menu thật (Auth admin), đổi mật khẩu + đăng xuất
  - `aria-label` cho nút icon
- `sidebar.blade.php`: brand = `setting_option('webtitle')`, `aria-label` nav
- `footer.blade.php`: copyright + tên site

### 6.3 Auth

- `auth/login.blade.php`: theme init, `<main>`, Bootstrap Icons, a11y password toggle

### 6.4 Module & a11y sweep

- ~30 trang: tiêu đề `h3` → **`h1`** trong `app-content-header`
- Breadcrumb bọc `<nav aria-label="breadcrumb">`
- `admin-menu.blade.php`: `section.content` → `app-content-header` + `app-content`
- `partials/breadcrumbs.blade.php`: active item không còn link, `aria-current="page"`

### 6.5 Trước đó (v4.0)

- Login v2, `style_admin.css` tinh gọn, icon search FA, `table-hover`, orders filter `app-content`

## 7. Progress

- [x] Assets AdminLTE **4.1.0**
- [x] Layout (`master`, `empty`, `nav`, `sidebar`, `footer`, theme init)
- [x] Color mode (light/dark/auto)
- [x] Login admin
- [x] Page titles h1 + breadcrumb a11y
- [x] `admin-menu` layout v4
- [x] Tests admin (17 passed)

## 8. Có thể làm sau (tùy chọn)

- Nâng Bootstrap local lên **5.3.8** (khớp AdminLTE 4.1 compile)
- Form user/product: `form-floating` toàn bộ
- Dashboard widgets: dữ liệu thật thay placeholder demo

