# Menu module — Vật Tư 58 (frontend header)

Bản tách / mirror giao diện Laravel. Thư mục này **không** thay thế `resources/` — dùng để tham chiếu, static demo, hoặc copy có chủ đích.

## Đồng bộ gần đây (cùng logic production)

| Chủ đề                                  | Trong project                                                                        | Trong `menu-module`                                      |
| --------------------------------------- | ------------------------------------------------------------------------------------ | -------------------------------------------------------- |
| Offcanvas **ngoài** `<header>`          | `header.blade.php`: đóng `</header>` rồi mới `#mobile-menu-overlay` + `#mobile-menu` | `html/header.html`, `demo.html`, `html/header.blade.php` |
| **Z-index** cố định cho overlay / panel | `resources/css/app.css` (`#mobile-menu-overlay` 99998, `#mobile-menu` 99999)         | Cuối `css/menu.css` (cùng rule cho `demo.html`)          |
| Nền overlay                             | Class `bg-black/50`                                                                  | `menu.css`: `.bg-black\/50` + class trên HTML            |
| Drawer markup                           | `flex h-full w-full flex-col p-5`, viền `border-gray-200`                            | Giữ nguyên trong HTML mirror                             |
| `includes/menu.blade.php`               | Chỉ comment — không còn `<div></div>` rỗng                                           | README: menu thật nằm ở `layouts.header`                 |

## Entry point trong project gốc

| Thành phần                             | Đường dẫn                                                                                                                                          |
| -------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| Blade header (desktop + mobile drawer) | `resources/views/frontend/layouts/header.blade.php`                                                                                                |
| Layout include header                  | `resources/views/frontend/layouts/master.blade.php` (`@include('frontend.layouts.header')`)                                                        |
| Trang chủ                              | `resources/views/frontend/home.blade.php` — menu chính ở `layouts.header`; `frontend/includes/menu.blade.php` là include legacy (không output DOM) |
| Theme màu + font (Tailwind v4)         | `resources/css/app.css` (`@theme` — palette `leaf`, `--font-sans` Nunito)                                                                          |
| JS mobile menu + sync từ khóa URL      | `resources/js/custom.js` (IIFE trong `DOMContentLoaded`, ~248–283)                                                                                 |
| Script inline (trùng logic)            | Cuối `header.blade.php` — `@push('scripts')` (cùng logic với `custom.js`)                                                                          |

**Ghi chú:** Trên production, mobile menu listener có thể được đăng ký **hai lần** (Blade `@push` + `custom.js` qua Vite). Module tách chỉ một bản trong `js/mobile-menu.js` cho demo.

## Phụ thuộc thực tế (dependency graph)

### CSS

- **Production:** Tailwind CSS v4 qua Vite (`@vite` → `resources/css/app.css` + plugin `@tailwindcss/vite`). Các class utility trên header (`bg-white/80`, `leaf-*`, `md:*`, …) được biên dịch từ đây; **không** có Bootstrap/Swiper cho riêng header này.
- **Standalone (`menu-module`):** `css/menu.css` — utility + biến giống `@theme` trong `app.css`, **cộng** rule `#mobile-menu-overlay` / `#mobile-menu` (giống cuối `app.css`). Font Nunito: Google Fonts trong `demo.html`.

### JavaScript

- **Production:** `resources/js/app.js` → `custom.js` (jQuery, axios, …) — phần **chỉ liên quan menu** là đoạn mở/đóng drawer và đồng bộ `?keyword=` / `?q=` vào ô tìm.
- **Không có:** mega menu, dropdown Bootstrap, offcanvas Bootstrap, Swiper cho header, sticky logic thêm (header dùng `position: sticky` + class Tailwind).

### Asset

- **Logo production:** `get_image(setting_option('logo') ?: 'upload/images/logo/logo.png')` — ưu tiên CSDL, không cấu hình thì dùng file tĩnh **`public/upload/images/logo/logo.png`** (đặt ảnh PNG vào đây).
- **Demo HTML (`demo.html`, …):** `../public/upload/images/logo/logo.png` (cùng file với trên khi mở demo từ disk).
- **Icon:** SVG **inline** trong Blade (hamburger, đóng, tìm kiếm, giỏ); không dùng file icon riêng cho header.

### Menu dữ liệu (Laravel)

- Model: `App\Models\Frontend\Menu::byName('Menu-main')` với quan hệ `items`.
- Blade lặp `@foreach ($headerMenu->items as $item)` cho desktop + mobile.

## Cấu trúc thư mục

```
menu-module/
├── index.html                # Entry: `js/tailwind-4.2.js` + `menu-custom.css` (HTTP). `file://` → dùng demo.html
├── demo.html                 # Portable: css/menu.css + menu-custom.css (file:// OK)
├── demo-tailwind-v4.html     # Tailwind v4 browser + menu-custom.css (cần HTTP)
├── README.md
├── html/
│   ├── header.blade.php   # Bản sao Blade từ project (để tham chiếu / copy tay vào views)
│   ├── header.html        # HTML tĩnh mẫu (menu giống screenshot)
│   └── mobile-menu.html   # Fragment overlay + drawer (tùy chọn include)
├── css/
│   ├── menu.css           # Subset utility (không cần Tailwind browser)
│   ├── menu-custom.css    # Z-index drawer, hero demo, fix logo/shrink — dùng chung cả hai demo
│   └── vendor/            # Rỗng — header không dùng Bootstrap/Swiper
├── js/
│   ├── tailwind-4.2.js    # @tailwindcss/browser v4 — dùng cho demo-tailwind-v4.html
│   ├── menu.js            # Sync query string → input tìm kiếm
│   ├── mobile-menu.js     # Mở/đóng mobile drawer + overlay
│   └── vendor/            # Rỗng
└── assets/
    ├── images/logo.svg
    ├── icons/             # Rỗng (icon = inline SVG trong HTML)
    └── fonts/             # Rỗng (Nunito từ Google Fonts trong demo)
```

## Cách chạy demo

1. **`index.html`:** `js/tailwind-4.2.js` + khối `type="text/tailwindcss"` (`@import` + `@theme` leaf) + `css/menu-custom.css` — mở qua **HTTP** (vd. `npx --yes serve .` trong `menu-module/`). Trên **`file://`** import Tailwind thường lỗi → dùng **`demo.html`**.
2. **`demo.html` (khuyến nghị cho `file://`):** `menu.css` + `menu-custom.css`, không script Tailwind browser.
3. **`demo-tailwind-v4.html`:** giống `index.html` (cùng stack Tailwind browser + `menu-custom.css`); giữ để tham chiếu tên file riêng nếu cần.
4. Thu nhỏ &lt; 768px: hamburger, overlay, drawer; ≥ 768px: tìm kiếm + nav ngang.
5. `?keyword=test` trên URL — `js/menu.js` đồng bộ ô tìm kiếm.

## Cách import vào project khác

### Chỉ HTML/CSS/JS (static)

**Cách A — Giống dự án Vật Tư (Tailwind v4):** xem `demo-tailwind-v4.html`: copy `js/tailwind-4.2.js` (hoặc CDN `@tailwindcss/browser`), markup từ `html/header.html`, `<script type="text/tailwindcss">` với `@import "tailwindcss"` + `@theme` (palette `leaf`), link `css/menu-custom.css` (z-index `#mobile-menu*`, hero tùy chọn), Google Fonts Nunito; cuối body: `tailwind-4.2.js` → block `text/tailwindcss` → `mobile-menu.js` → `menu.js`.

**Cách B — Không Tailwind browser:** copy `css/menu.css`, `css/menu-custom.css`, `js/menu.js`, `js/mobile-menu.js`, `assets/`, link cả hai CSS + font, script như `demo.html`.

### Laravel / Blade

1. Giữ nguyên `@include('frontend.layouts.header')` trong master **hoặc** copy nội dung từ `html/header.blade.php` vào component của bạn.
2. Tiếp tục `@vite(['resources/css/app.css', ...])` — Tailwind cover utility; **giữ** block z-index `#mobile-menu*` trong `app.css` khi dùng Blade header hiện tại.
3. Nếu bỏ script inline trong Blade, thêm vào bundle Vite: import `./mobile-menu-logic` (tách từ `js/mobile-menu.js`) và import `menu.js` tương ứng để tránh trùng listener.

## File entry chính

| Mục đích                      | File                                                                              |
| ----------------------------- | --------------------------------------------------------------------------------- |
| Demo portable (`file://`)     | `index.html` hoặc `demo.html`                                                     |
| Demo Tailwind v4 (HTTP)       | `demo-tailwind-v4.html`                                                           |
| Tích hợp Laravel (tham chiếu) | `html/header.blade.php` + `resources/views/frontend/layouts/master.blade.php` gốc |
| Style static / không Vite     | `css/menu.css` + `css/menu-custom.css`                                            |
| Hành vi menu                  | `js/mobile-menu.js` + `js/menu.js`                                                |

## Cách menu hoạt động (tóm tắt)

1. **Sticky header:** class `sticky top-0 z-50` (+ nền mờ `backdrop-blur-md`, `bg-white/80`).
2. **Desktop (≥768px):** `hidden md:flex` hiện form tìm kiếm và `nav`; nút hamburger và panel `md:hidden`.
3. **Mobile:** Drawer `#mobile-menu` **sau** `</header>` (tránh lỗi nền trong suốt / khối đen khi `backdrop-blur` trên `<header>`). `fixed` trái, mặc định `-translate-x-full`; mở/đóng như trên; z-index cố định trong `app.css` (Laravel) và `menu-custom.css` (demo/module).
4. **Giỏ hàng:** `#CartCountDot` là chấm cam cố định trên icon (project gốc không có script đổi trạng thái dot theo `CartCountDot`; logic giỏ khác dùng `#CartCount` ở layout cũ).

---

_Cập nhật định kỳ theo `resources/views/frontend/layouts/header.blade.php` và `resources/css/app.css`._
