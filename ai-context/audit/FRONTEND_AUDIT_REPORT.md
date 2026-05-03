# Frontend Audit Report — vattunongnghiep58

## 1) Frontend Inventory

### Blade Views

- Frontend views: `resources/views/frontend/*`
- Backend/admin views: `resources/views/backend/*`
- Shared components/partials: `resources/views/components/*`, `resources/views/backend/partials/*`
- Email templates: `resources/views/email/*`, `resources/views/mail/*`

### JS/CSS Sources

- Vite sources:
  - `resources/js/*` (Vue components + app.js + axios setup)
  - `resources/scss/*`, `resources/css/app.css`
  - `tailwind.config.js`, `vite.config.js`
- Built assets:
  - `public/build/manifest.json` + `public/build/assets/*`
- Legacy/static assets (rất lớn):
  - `public/assets/*` (AdminLTE, jQuery, CKEditor/CKFinder, plugins, nhiều file “copy”)
  - `public/assets/css/*`, `public/assets/js/*`

## 2) Findings (UI Maintainability & Consistency)

### 2.1 Blade “not thin” (logic in views)

- Evidence:
  - Có nhiều `@php` trong Blade (104 occurrences trong 50 files dưới `resources/views`).
  - Ví dụ điển hình: `resources/views/backend/product/single.blade.php` dùng:
    - `extract(...)`, parse/unserialize gallery, set defaults…
- Impact:
  - Khó test, khó reuse, dễ tạo bug khi data shape thay đổi
  - View trở thành nơi chứa business logic + data transformation

### 2.2 Inline JavaScript trong Blade

- Evidence:
  - `<script>` xuất hiện nhiều trong Blade (141 occurrences trong 50 files).
  - Backend layout đặc biệt nặng inline script (`resources/views/backend/layouts/*`).
- Impact:
  - Khó maintain, khó build pipeline, khó enforce CSP
  - Dễ bị duplication khi nhiều page cùng logic

### 2.3 Inline CSS / hardcoded style

- Evidence:
  - Trong `resources/views/backend/product/single.blade.php` có inline style (ví dụ link color).
  - public assets có nhiều file css copy (`public/assets/css/style copy*.css`).
- Impact:
  - UI inconsistency, khó chuẩn hoá design system
  - “style drift” giữa trang và giữa admin/frontend

### 2.4 UI stack pha trộn (Tailwind + AdminLTE + custom legacy)

- Evidence:
  - `package.json` có Tailwind v4 + Vite
  - `public/assets/admin/*` có AdminLTE css/js
  - `public/assets/css/app.css`, `style.css`, `style_admin.css`...
- Impact:
  - Risk xung đột CSS (specificity)
  - Bundle size lớn, ảnh hưởng performance
  - Responsive behavior không đồng nhất (mỗi UI framework có breakpoint khác nhau)

## 3) Responsive & UX Risk Areas (cần kiểm tra thủ công)

> Static scan chỉ ra rủi ro, cần QA thực tế để xác nhận.

- Cart/Checkout pages:
  - `resources/views/frontend/cart/*`
  - `resources/views/frontend/checkout/*`
  - Có nhiều render partial + AJAX update (CartController trả HTML partial trong JSON)
- Admin forms phức tạp:
  - Product create/edit: `resources/views/backend/product/single.blade.php`
  - Category tree selection: `resources/views/backend/partials/category-item.blade.php`

## 4) Technical Debt (frontend-specific)

- Nhiều legacy plugin trong `public/assets/plugin/*` (ckeditor/ckfinder/select2/...)
- Dễ phát sinh:
  - duplicated UI behavior (multiple JS init)
  - inconsistent validation messages/UI
  - khó enforce security headers/CSP vì inline scripts

## 5) Recommendations (Roadmap-ready)

> Không triển khai ngay; đây là input cho `modernization/UI_MODERNIZATION_PLAN.md`.

- Di chuyển inline JS ra bundle (Vite) theo page/module; dùng `@pushOnce`/components nếu cần.
- Chuẩn hoá Blade components (giảm duplication); tách data preparation về controller/view composer.
- Audit và dọn asset legacy theo usage thực tế (tạo inventory “used vs unused”).
- Chốt UI direction:
  - Admin: AdminLTE (legacy) hay migrate dần sang Tailwind-based admin?
  - Frontend: ưu tiên Tailwind + component library, giảm phụ thuộc jQuery plugin.

