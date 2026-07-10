# Improvement Playbook — Vật Tư Nông Nghiệp 58

> **File theo dõi chính** cho mọi hạng mục cải thiện dự án.  
> Cập nhật lần cuối: **2026-07-10**  
> **Backlog backend:** [BACKEND_AUDIT_PLAYBOOK.md](BACKEND_AUDIT_PLAYBOOK.md) — **BACK-001…018 `done`** (2026-07-07)  
> **Tài khoản khách (account):** [CUSTOMER_ACCOUNT_PLAYBOOK.md](CUSTOMER_ACCOUNT_PLAYBOOK.md) — Track R **done** (CUST-RF-001…006)

---

## Cách dùng

1. Mỗi hạng mục có **ID cố định** (`IMP-xxx`) — không đổi ID khi đổi trạng thái.
2. Cập nhật cột **Trạng thái** khi bắt đầu / xong / hoãn.
3. Ghi **Ghi chú / bước tiếp** ngắn gọn sau mỗi lần làm việc.
4. Chi tiết kỹ thuật sâu → xem **Tài liệu liên quan** (không copy trùng vào đây).

### Trạng thái (chuẩn)

| Ký hiệu       | Ý nghĩa                              |
| ------------- | ------------------------------------ |
| `done`        | Hoàn thành, đã kiểm chứng            |
| `in_progress` | Đang làm                             |
| `todo`        | Chưa bắt đầu, đã xác nhận cần làm    |
| `optional`    | Làm khi có thời gian / không blocker |
| `deferred`    | Hoãn có lý do rõ                     |

### Ưu tiên

| Mức    | Ý nghĩa                               |
| ------ | ------------------------------------- |
| **P0** | Bảo mật / blocker production          |
| **P1** | Ảnh hưởng UX hoặc maintainability lớn |
| **P2** | Cải thiện chất lượng, không gấp       |

---

## Bảng tổng quan

| ID      | Hạng mục                               | Ưu tiên | Trạng thái | Tài liệu                        |
| ------- | -------------------------------------- | ------- | ---------- | ------------------------------- |
| IMP-001 | Chuẩn hóa DB đơn hàng (`shop_orders`)  | P1      | `done`     | —                               |
| IMP-002 | Form Request validation                | P1      | `done`     | —                               |
| IMP-003 | Tối ưu N+1 / bỏ query trong Blade      | P1      | `done`     | —                               |
| IMP-004 | Chuẩn hóa pnpm + Vite                  | P2      | `done`     | —                               |
| IMP-005 | Bảo mật `ajax_quickchange` (whitelist) | P1      | `done`     | —                               |
| IMP-006 | Mở rộng `FrontendDataTransform`        | P1      | `done`     | § IMP-006                       |
| IMP-007 | Admin UI AdminLTE 4.1                  | P1      | `done`     | § IMP-007                       |
| IMP-008 | Category tree đệ quy (không N+1)       | P1      | `done`     | § IMP-008                       |
| IMP-009 | Bật lại CSRF middleware (web)          | **P0**  | `done`     | § IMP-009                       |
| IMP-010 | Frontend Tailwind v4 chuẩn hóa         | P2      | `done`     | § IMP-010                       |
| IMP-011 | Đồng bộ Bootstrap 5.3.8 (admin)        | P2      | `deferred` | § IMP-011 (lưu ý AdminLTE)      |
| IMP-012 | Dọn luồng checkout / quick-buy legacy  | P1      | `done`     | § IMP-012                       |
| IMP-013 | `js_admin.js` → axios (admin AJAX)     | P2      | `done`     | `public/assets/js/js_admin.js`  |
| IMP-014 | CKEditor content = toolbar đơn giản    | P2      | `done`     | `js_admin.js` `editor()`        |
| IMP-015 | Dọn JS legacy (P1–P4)                  | P2      | `done`     | § IMP-015, `docs/CHANGE_LOG.md` |
| IMP-016 | Axios storefront + route rename JS     | P2      | `done`     | `http.js`, `ROUTE_GLOSSARY.md`  |

---

## Đã hoàn thành (chi tiết ngắn)

### IMP-001 — Chuẩn hóa DB đơn hàng

- Rename `addtocard` → `shop_orders`, `addtocard_detail` → `shop_order_items`
- Model `Order`, `OrderItem` đồng bộ

### IMP-002 — Form Request

- Validation tách khỏi controller (vd. checkout)

### IMP-003 — N+1

- Eager loading backend; bỏ query trong `home`, `product/index`

### IMP-004 — Package Node

- Chỉ `pnpm-lock.yaml`; bỏ `package-lock.json`

### IMP-005 — AJAX admin

- Validation + whitelist model/column trong `AjaxController`

### IMP-007 — Admin UI 4.1

- Assets 4.1.0, shell, color mode, login v2, a11y h1/breadcrumb
- Nguồn template: `new-admin-ui/` (AdminLTE 4.1.0)

### IMP-013 — Axios admin

- Toàn bộ `$.ajax` trong `js_admin.js` → axios (`window.http.postForm` / `postText`)
- Route URL qua `window.AdminRoutes` (`admin-routes.blade.php`)
- **2026-07-10:** Gỡ dead code quick-edit `theme` (`process_theme_fast`, toggle `item_new`/…)

### IMP-016 — Axios storefront (2026-07-10)

- `resources/js/http.js` — wrapper axios thống nhất
- `resources/js/auth-forms.js` — login/register không reload khi lỗi
- `window.AppRoutes` trong `app-routes.blade.php`

### IMP-014 — CKEditor đơn giản

- `editor('content')` dùng cùng toolbar với `editorQuote()` (mô tả)

---

## Đang làm / Backlog

### IMP-006 — `FrontendDataTransform` (done)

**Mục tiêu:** Mọi trang storefront dùng trait transform; Blade không map logic lặp.

**Đã áp dụng:**

- `PageController` — home categories, home news
- `ProductController` — product list, category page
- `PostController` — post list, detail
- `SearchController` — kết quả tìm kiếm sản phẩm
- `CartController` — cart, checkout, AJAX cart partials

**Checklist:**

- [x] `PostController` — transform list/detail
- [x] `SearchController` — transform kết quả
- [x] `CartController` / checkout views — chuẩn hóa dữ liệu cart item
- [x] Category page — đồng bộ với product list
- [x] Test: `tests/Unit/FrontendDataTransformTest.php`

**Ghi chú:** `product/single` và quick-view vẫn dùng model trực tiếp — có thể mở rộng sau nếu cần.

---

### IMP-008 — Category tree đệ quy (done)

**Mục tiêu:** Render cây danh mục N cấp, **1 query prefetch**, không N+1 trong Blade.

**Đã làm (2026-07-05):**

- [x] `ProductCategoryController` — `buildChildrenMapForIndex` / `buildChildrenMapForSelect`
- [x] `ProductController` — `buildCategoryTreeData`
- [x] `MenuController` — `childrenMap` cho menu builder
- [x] View: `select-category`, `category_item`, `partials/category-item` — đệ quy qua `$childrenMap`
- [x] Truyền `childrenMap` rõ ràng trong `@include` (index, single, partials)
- [x] Test: `ProductCategoryCategoryTreeViewsTest` — 4 cấp + assert 0 query khi render

---

### IMP-009 — CSRF middleware (done) — **P0**

**Vấn đề:** `VerifyCsrfToken` đang comment trong `app/Http/Kernel.php`.

**Đã làm (2026-07-05):**

- [x] Tạo `app/Http/Middleware/VerifyCsrfToken.php` (extends `ValidateCsrfToken`)
- [x] Bật middleware trong group `web`
- [x] Ngoại lệ: `ckfinder/*` (CKFinder có CSRF riêng)
- [x] Frontend Vite: `resources/js/axios-setup.js` gửi `X-CSRF-TOKEN` từ meta
- [x] Admin: `js_admin.js` / `main.js` đã có header CSRF
- [x] Test: `tests/Feature/CsrfProtectionTest.php`
- [x] Ghi `docs/CHANGE_LOG.md`

**Smoke sau deploy:** login admin, checkout, add cart AJAX, quick-view, upload CKFinder.

---

### IMP-010 — Tailwind v4 canonicalization (done)

**Đã làm (2026-07-05):**

- [x] Batch A: `flex-grow` → `grow`, `flex-shrink-0` → `shrink-0`
- [x] Batch B: `bg-gradient-to-*` → `bg-linear-to-*` (home)
- [x] Batch C: `rounded-[2rem]` → `rounded-4xl`, `h-[30rem]` → `h-120`, `min-h-[3.75rem]` → `min-h-15`, `min-w-[640px]` → `min-w-160`, `min-h-[120px]` → `min-h-30`, `min-w-[2.5rem]` → `min-w-10`
- [x] Phạm vi: `resources/views/frontend/**`, `resources/views/errors/404.blade.php`
- [x] `pnpm run build` pass
- [ ] Batch D (shadow phức tạp `sidebar-categories`) — giữ nguyên, không blocker

---

### IMP-011 — Bootstrap 5.3.8 (deferred)

> **Lưu ý (2026-07-05, xác nhận từ chủ dự án):** AdminLTE 4.1 đã tích hợp sẵn Bootstrap 5.3
> trong file thư viện — **KHÔNG sửa/thay file CSS thư viện AdminLTE**.
> Nếu cần tùy chỉnh giao diện admin, chỉ sửa file custom `style_admin.css`.

- Không nâng riêng `public/assets/bootstrap/` nữa; dùng bản Bootstrap bundle trong AdminLTE.
- Tùy chỉnh (nếu cần) → `style_admin.css` (custom CSS của admin).

---

### IMP-012 — Checkout / quick-buy legacy (done)

**Triệu chứng đã ghi nhận (audit cũ):** asset 404, `$ is not defined`, route `game.detail`, model `\App\Product` cũ.

**Đã làm (2026-07-05):**

- [x] `checkout.blade.php` — chỉ Vite + partials mới (đã có từ trước)
- [x] `CheckoutController@checkoutProcess` / `quickBuyConfirm` → redirect `cart.checkout`
- [x] View quick-buy / cart-confirm → stub chuyển hướng (bỏ `cart.js`, Stripe CDN)
- [x] `templatePath` / `templateFile` mặc định `frontend` (AppServiceProvider, Controller)
- [x] Test: `tests/Feature/CheckoutLegacyCleanupTest.php`
- [x] Ghi `docs/CHANGE_LOG.md`

**Luồng chính:** `/cart` → `/checkout` → `CartController@checkoutConfirm` → `checkout_completed`.

**Còn lại (không blocker):** file `.bk`, `cart-list.blade.php`, `request_payment` — không nằm route chính; xử lý khi có yêu cầu.

---

### IMP-015 — JS legacy cleanup P1–P4 (done)

**Mục tiêu:** Một nguồn JS storefront (Vite); gỡ dead code webpack/Vue; admin Blade dùng axios.

**Đã làm (2026-07-05):**

| Bước | Nội dung                                                              |
| ---- | --------------------------------------------------------------------- |
| P1   | `admin-menu.blade.php`, `change-password.blade.php`: `$.ajax` → axios |
| P2   | Xóa `public/assets/js/custom.js` (duplicate)                          |
| P3   | Xóa 8 file `resources/js/components/*.vue`                            |
| P4   | Xóa `public/assets/js/app.js` (webpack ~47k dòng)                     |
| —    | Xóa `menu_no_delete.js` (không include)                               |

**Test:** `AdminAxiosP1Test`, `DeadVueComponentsCleanupTest`, `PublicLegacyJsCleanupTest`  
**Đã xong (BACK-018):** `public/assets/js/main.js` đã gỡ.

---

---

## Playbook hoàn tất (2026-07-05)

Tất cả hạng mục `todo` / `in_progress` đã xong (IMP-001…015). Chỉ **IMP-011** `deferred` (không sửa CSS AdminLTE; tùy chỉnh `style_admin.css` nếu cần).

**Smoke test:** `php artisan test --compact` — **87 passed**, 1 skipped (2026-07-05).

**Backlog mới** → `docs/BACKEND_AUDIT_PLAYBOOK.md` (BACK-001…018). Tóm tắt kiến trúc → `docs/PROJECT_ANALYSIS.md` §6.2.

---

## Tài liệu liên quan

| File                                 | Vai trò                         |
| ------------------------------------ | ------------------------------- |
| **`docs/IMPROVEMENT_PLAYBOOK.md`**   | **Theo dõi tiến độ** (file này) |
| **`docs/BACKEND_AUDIT_PLAYBOOK.md`** | **Backlog backend** (BACK-xxx)  |
| `RECOMMENDATIONS.md`                 | Index + backlog tóm tắt         |
| `docs/PROJECT_ANALYSIS.md`           | Kiến trúc tổng quan             |
| `docs/CHANGE_LOG.md`                 | Ghi chú thay đổi kỹ thuật       |

---

## Nhật ký cập nhật

| Ngày       | Thay đổi                                                                                                                               |
| ---------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| 2026-07-06 | Tạo `docs/BACKEND_AUDIT_PLAYBOOK.md` — backlog backend BACK-001…018                                                                    |
| 2026-07-05 | Xóa tài liệu lịch sử: `PLAN-category-recursive.md`, `frontend-tailwind-v4-canonicalization.md`, `ui-upgrade.md`, `bug_hunt_prompt.txt` |
| 2026-07-05 | IMP-015: JS legacy P1–P4; đồng bộ `PROJECT_ANALYSIS.md` (doc audit)                                                                    |
| 2026-07-05 | Gỡ `ApiController` + API routes legacy (dead code)                                                                                     |
| 2026-07-05 | IMP-010: Tailwind v4 canonical classes (frontend Batch A–C)                                                                            |
| 2026-07-05 | IMP-011 deferred: AdminLTE đã có Bootstrap 5.3, chỉ custom `style_admin.css`                                                           |
| 2026-07-05 | Hoàn tất IMP-006: Search, Cart, Category + transform post                                                                              |
| 2026-07-05 | Đổi `NewsController` → `PostController`; `FrontendDataTransform` cho post list/detail                                                  |
| 2026-07-05 | Tạo playbook; gộp nội dung từ `RECOMMENDATIONS.md`; thêm IMP-007–014                                                                   |
| 2026-07-02 | Admin UI 4.1 hoàn tất (IMP-007)                                                                                                        |

---
