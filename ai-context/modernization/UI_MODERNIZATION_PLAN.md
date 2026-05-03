# UI Modernization Plan — vattunongnghiep58

## Current UI State (from codebase)

- Build tool hiện đại: Vite + Tailwind v4 (`resources/*`, `tailwind.config.js`, `vite.config.js`)
- Nhưng lượng lớn UI legacy:
  - AdminLTE assets trong `public/assets/admin/*`
  - jQuery + plugins trong `public/assets/plugin/*`
- Blade views có nhiều inline `<script>` và `@php` logic.

## Goals

- Chuẩn hoá UI stack (giảm duplication framework)
- Giảm inline JS/CSS (để chuẩn bị CSP, giảm bug)
- Tăng consistency + responsive correctness
- Giảm asset footprint (bundle nhỏ hơn, load nhanh hơn)

## Plan (phased, non-destructive)

### Phase 0 — Inventory & Baseline

- Lập danh sách các layout entrypoints:
  - Frontend layout(s): `resources/views/frontend/layouts/*`
  - Backend layout(s): `resources/views/backend/layouts/*`
- Trace các file CSS/JS được include thực tế trong layout:
  - so sánh Vite assets vs public/assets
- Thiết lập checklist responsive cho các trang critical:
  - product listing/detail, cart, checkout, customer orders, admin product/order CRUD

### Phase 1 — Extract inline JS to bundles

- Với mỗi page critical, gom script vào `resources/js/*`:
  - init editor, category tree, cart ajax update, etc.
- Dùng cơ chế `@pushOnce`/components để tránh duplicated scripts.

### Phase 2 — Componentize Blade

- Chuẩn hoá patterns:
  - Form inputs, error blocks, buttons, modals, tables
- Giảm duplication giữa backend CRUD pages:
  - index/filter/single view patterns

### Phase 3 — Decide Admin UI direction

Option A: Giữ AdminLTE, “harden & clean”
- Ưu tiên giảm inline script + dọn plugin unused

Option B: Migrate dần admin sang Tailwind
- Bắt đầu từ màn hình ít rủi ro: dashboard widgets, list pages

### Phase 4 — Performance polish

- Minimize CSS/JS bundles, remove dead assets
- Add image optimization guidelines
- Enforce consistent responsive breakpoints

