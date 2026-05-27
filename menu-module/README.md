# Menu module — Vật Tư 58 (frontend header)

Bản tách / mirror giao diện Laravel. Thư mục này **không** thay thế `resources/` — dùng để tham chiếu, static demo, hoặc copy có chủ đích.

## Đồng bộ gần đây (cùng logic production)

| Chủ đề                                  | Trong project                                                                                                         | Trong `menu-module`                                                                                                   |
| --------------------------------------- | --------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------- |
| Offcanvas **ngoài** `<header>`          | `header.blade.php`: đóng `</header>` rồi mới `#mobile-menu-overlay` + `#mobile-menu`                                  | `html/header.html`, `demo.html`, `html/header.blade.php`                                                              |
| **Dropdown desktop** (cấp 2)            | `header.blade.php`: `[data-nav-dropdown]`, nút `[data-nav-dropdown-toggle]`, panel `group-hover` + `group-[.is-open]` | `index.html` (utility đủ); `demo.html` / `html/header.html` (class panel `vt-nav-dropdown-panel` + `menu-custom.css`) |
| **Mobile accordion**                    | `<details class="nav-mobile-details">` + chevron                                                                      | Giống trong các file HTML / Blade mirror                                                                              |
| **Z-index** cố định cho overlay / panel | `resources/css/app.css` (`#mobile-menu-overlay` 99998, `#mobile-menu` 99999)                                          | Cuối `css/menu-custom.css` (cùng rule cho demo)                                                                       |
| JS dropdown + drawer                    | `resources/js/custom.js` (mobile + `[data-nav-dropdown]`)                                                             | `js/mobile-menu.js` + **`js/desktop-nav-dropdown.js`** (chỉ dropdown; giữ đúng logic toggle / Escape / click ngoài)   |
| `includes/menu.blade.php`               | Legacy / comment                                                                                                      | README: menu thật nằm ở `layouts.header`                                                                              |

## Entry point trong project gốc

| Thành phần                                      | Đường dẫn                                                                                               |
| ----------------------------------------------- | ------------------------------------------------------------------------------------------------------- |
| Blade header (desktop dropdown + mobile drawer) | `resources/views/frontend/layouts/header.blade.php`                                                     |
| Layout include                                  | `resources/views/frontend/layouts/master.blade.php` (`@include('frontend.layouts.header')`)             |
| Theme + chevron `<details>`                     | `resources/css/app.css` (`@layer components` — `.nav-mobile-details[open] .nav-mobile-details-chevron`) |
| JS (mobile + dropdown + keyword sync)           | `resources/js/custom.js` (IIFE `DOMContentLoaded`)                                                      |

**Ghi chú:** Trên production, listener có thể chạy **hai lần** nếu vừa có logic trong Blade `@push` vừa bundle Vite. Module tách: một file `mobile-menu.js`, một file `desktop-nav-dropdown.js`.

## Phụ thuộc (dependency graph)

### CSS

- **Production:** Tailwind v4 qua Vite (`resources/css/app.css`).
- **Standalone (`demo.html`):** `css/menu.css` (subset utility) + `css/menu-custom.css` (z-index drawer, hero demo, **`.vt-nav-dropdown-panel`**, chevron mobile).

### JavaScript

- **Production:** `resources/js/app.js` → `custom.js`.
- **Module:** `js/mobile-menu.js`, `js/desktop-nav-dropdown.js`, `js/menu.js` (sync `?keyword=` / `?q=`).

### Asset

- **Logo module:** `assets/images/logo.svg` (demo / index dùng file này).
- **Production:** `get_image(setting_option('logo') …)` — thường `public/upload/images/logo/logo.png`.

## Cấu trúc thư mục

```
menu-module/
├── index.html              # Tailwind v4 browser (HTTP): utility đầy đủ, dropdown giống production
├── demo.html               # file:// OK: menu.css + menu-custom.css; panel dropdown = .vt-nav-dropdown-panel
├── README.md
├── html/
│   ├── header.blade.php    # Bản sao Blade từ resources/views/frontend/layouts/header.blade.php
│   ├── header.html         # HTML tĩnh (../assets/…); subset panel
│   └── mobile-menu.html    # Fragment overlay + drawer
├── css/
│   ├── menu.css
│   ├── menu-custom.css
│   └── vendor/
├── js/
│   ├── tailwind-4.2.js     # @tailwindcss/browser — cho index.html
│   ├── menu.js
│   ├── mobile-menu.js
│   └── desktop-nav-dropdown.js   # Toggle class is-open (đồng bộ custom.js)
└── assets/
    └── images/
```

## Cách chạy demo

1. **`demo.html`:** `menu.css` + `menu-custom.css` — mở trực tiếp (`file://`). Desktop: hover hoặc nút mũi tên mở dropdown; mobile: `<details>` mở nhóm con.
2. **`index.html`:** `tailwind-4.2.js` + block `type="text/tailwindcss"` + `menu-custom.css` — cần **HTTP** (vd. `npx --yes serve .` trong `menu-module/`). Class dropdown trùng Blade production (`group-hover:` + `group-[.is-open]:`).
3. Thu nhỏ &lt; 768px: hamburger, overlay, drawer; ≥ 768px: tìm kiếm + nav + dropdown.
4. `?keyword=` — `js/menu.js`.

## Import vào project khác

### Static

- **Có Tailwind v4 browser:** theo `index.html` (script order: `tailwind-4.2.js` → `text/tailwindcss` → `mobile-menu.js` → `desktop-nav-dropdown.js` → `menu.js`).
- **Không Tailwind browser:** theo `demo.html`: hai file CSS + `mobile-menu.js` + `desktop-nav-dropdown.js` + `menu.js`.

### Laravel

Giữ `@vite` + Blade `layouts/header.blade.php`; logic đã nằm trong `custom.js`. Nếu tách script khỏi Blade, import hai module tương ứng trong bundle để tránh đăng ký trùng.

## File entry chính

| Mục đích                  | File                                                              |
| ------------------------- | ----------------------------------------------------------------- |
| Demo portable (`file://`) | `demo.html`                                                       |
| Demo Tailwind v4 (HTTP)   | `index.html`                                                      |
| Blade tham chiếu          | `html/header.blade.php`                                           |
| Style static              | `css/menu.css` + `css/menu-custom.css`                            |
| Hành vi                   | `js/mobile-menu.js` + `js/desktop-nav-dropdown.js` + `js/menu.js` |

## Cách menu hoạt động (tóm tắt)

1. **Sticky header:** `sticky top-0 z-50`, nền `backdrop-blur-md`, **`overflow-visible`** để panel dropdown không bị cắt.
2. **Desktop:** Mục có con: `data-nav-dropdown` + link chính + nút `data-nav-dropdown-toggle`; panel hiện khi **hover** nhóm hoặc khi **click** toggle (class `is-open`); đóng khi click ngoài hoặc Escape.
3. **Mobile:** Drawer sau `</header>`; mục có con = `<details class="nav-mobile-details">` + link “Tất cả …”.
4. **Giỏ:** `#CartCountDot` — chấm cố định (logic đếm giỏ nằm nơi khác trên site).

---

_Cập nhật theo `resources/views/frontend/layouts/header.blade.php`, `resources/css/app.css`, `resources/js/custom.js`._
