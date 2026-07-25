# CHANGE_LOG

## 2026-07-10 — Đồng bộ tài liệu chuẩn refactor `3nong`

- Tạo [docs/REFACTOR_3NONG_PLAYBOOK.md](REFACTOR_3NONG_PLAYBOOK.md) — gap matrix, phase P0–P5, map bảng/route 3nong → vattun.
- Cập nhật `MASTER.md`: test **192 passed**, newsletter `contacts`, legacy account redirect, checklist port 3nong.
- Cập nhật `TABLE_GLOSSARY.md`: bỏ bảng `subscription` active; newsletter qua `contacts`.
- Cập nhật `ROUTE_GLOSSARY.md`, `DB_AUDIT.md`, `PROJECT_ANALYSIS.md`, `README.md`, `RECOMMENDATIONS.md`.
- Test docs: `DocumentationCleanupTest` thêm assert `REFACTOR_3NONG_PLAYBOOK.md`.

---

## 2026-07-10 — Phase 4: dọn code mồ côi (orphan legacy)

- Newsletter `POST /subscription` → lưu `contacts` (`type=subscription`) thay bảng `subscription`.
- Admin bulk delete `subscription` → xóa từ `contacts` (type subscription).
- Helper `permalink_by_id` / `get_product_by_id` → dùng `products` + route `product.detail`.
- Route account legacy (`myReviews`, `postReviews`, `refused`, `myPost`) → redirect `customer.dashboard`.
- `wishlist()` không query bảng `wishlist` (empty state).
- `backend/product/filter.blade.php`: bỏ `category_theme` / `Theme` joins.
- Test: `tests/Feature/OrphanLegacyCodeTest.php` (5 tests).
- Full suite: **192 passed, 2 skipped** (756 assertions).

---

## 2026-07-10 — DB cleanup Phase 1–3: drop bảng legacy payment/admin

- Migration `2026_07_09_194029_drop_legacy_admin_and_payment_tables`: drop `admins`, `payments`, `payment_request`, `user_password_auto`, `settings_cost`, `password_resets`, `shipping_order`.
- `config/auth.php`: password broker → `password_reset_tokens` (thay `password_resets`).
- `routes/web.php`: comment route VNPay (`customer.vnpay`, `customer.payment.point`) — PayPal/VNPay deferred.
- `CheckSystemHealth`: bỏ check `admins`, thêm `shop_orders`.
- Test: `tests/Feature/LegacyTablesDroppedTest.php`.
- User đã backup DB trước khi chạy migration.

---

## 2026-07-10 — Route rename, axios chuẩn hóa, email đăng ký, dọn dead code

### Route (tên mới + alias legacy)

| Chức năng             | Route mới                        | URI mới                                | Alias cũ                                       |
| --------------------- | -------------------------------- | -------------------------------------- | ---------------------------------------------- |
| Xóa SP khỏi giỏ       | `cart.remove-item`               | `POST /cart/remove-item`               | `cart.ajax.remove` → `/cart/ajax/remove`       |
| Xóa hàng loạt admin   | `admin.bulk.delete`              | `POST /admin/bulk-delete`              | `admin.ajax_delete` → `/admin/delete-id`       |
| Nhân bản hàng loạt    | `admin.bulk.replicate`           | `POST /admin/bulk-replicate`           | `admin.ajax_replicate` → `/admin/replicate-id` |
| Sửa nhanh field admin | `admin.quick-change`             | `POST /admin/quick-change`             | (tên cũ `admin.ajaxQuickChange` đã bỏ)         |
| Sắp xếp album item    | `admin.albumItem.update_sort`    | `POST /admin/album_item/update-sort`   | `admin.albumItem.ajax_update_sort`             |
| Sắp xếp theme option  | `admin.theme-option.update_sort` | `POST /admin/theme-option/update-sort` | `admin.theme-option.ajax_update_sort`          |

- JS storefront: `window.AppRoutes` (`resources/views/frontend/layouts/app-routes.blade.php`).
- JS admin: `window.AdminRoutes` (`resources/views/backend/layouts/admin-routes.blade.php`) — include trong `master.blade.php` và `empty.blade.php`.
- Test: `tests/Feature/RouteAliasTest.php`.

### HTTP client axios (storefront + admin)

- `resources/js/http.js` — `get`, `post`, `postForm`, `postJson`, `put`, `delete`, `postText`.
- `resources/js/auth-forms.js` — login/register khách qua axios (không reload khi lỗi validate).
- Storefront: `custom.js`, `cart.blade.php`, `contact.blade.php` dùng `http.*`.
- Admin: `js_admin.js`, `menu.js` dùng `window.http` / `AdminRoutes`.
- Không còn `$.ajax` trong view/JS app (chỉ vendor plugin).

### Email template & đăng ký khách

- Admin email template: field `code` (thay `group`); validator CKEditor; test `AdminEmailTemplateTest`.
- Đăng ký: `RegisterController` ghi `fullname` (không còn cột `name`); mail từ template **`new_register`** qua `CustomerRegistrationEmailService`.
- Test: `CustomerAuthFlowTest`, `CustomerRegistrationEmailTest`.

### Dọn dead code legacy `theme` quick-edit

- Xóa JS: `update_theme_fast`, `new_item_click`, `propose_click`, `store_status_click`, `flash_sale_click`, `sale_top_week_click`, helper `adminToggleCheckbox`, `adminPostText`.
- Xóa stub tương ứng trong `Admin\AjaxController` (`processThemeFast`, `update_new_item_status`, …).
- Thay thế hiện tại: toggle **Hot** / **Công khai** trên danh sách SP admin qua `.quick_change_value` + `admin.quick-change`.

---

## 2026-07-10 — TABLE_GLOSSARY + ROUTE_GLOSSARY

- Tạo `docs/TABLE_GLOSSARY.md`: bảng ↔ model ↔ alias/legacy ↔ nghiệp vụ ↔ trạng thái.
- Tạo `docs/ROUTE_GLOSSARY.md`: route name ↔ URL ↔ controller ↔ middleware.
- Cập nhật index trong `MASTER.md`, `README.md`, `RECOMMENDATIONS.md`.
- Sửa `MASTER.md` §6.2: `subscriptions` → `subscription` (khớp model).

---

## 2026-07-10 — MASTER.md: tài liệu master nghiệp vụ

- Tạo `MASTER.md` ở root: business flows, user features, schema, business rules.
- Dùng làm chuẩn tham chiếu khi refactor sang dự án khác (vd. `3nong`).
- Cập nhật index trong `README.md`, `RECOMMENDATIONS.md`.

---

## 2026-07-09 — Gỡ diglactic/laravel-breadcrumbs

- `composer remove diglactic/laravel-breadcrumbs` (package không được dùng).
- Xóa file mồ côi: `routes/breadcrumbs.php`, `config/breadcrumbs.php`, `resources/views/backend/partials/breadcrumbs.blade.php`.
- Breadcrumb UI vẫn là HTML Bootstrap viết tay trong từng Blade.
- Test: **176 passed, 1 skipped**.

---

## 2026-07-09 — L13: Nâng cấp Laravel 12 → 13

- `laravel/framework` **12.62** → **13.19.0**; `laravel/tinker` **2.11** → **3.0.2**.
- **Package xung đột upstream** (chưa khai báo L13) — fork nội bộ trong `packages/`:
    - `ckfinder/ckfinder-laravel-package` 5.0.3
    - `surfsidemedia/shoppingcart` 2.0.1
    - `gornymedia/laravel-shortcodes` 1.5.1
- `barryvdh/laravel-debugbar` **3.16** → **4.4** (dev).
- CSRF: `App\Http\Middleware\PreventRequestForgery` (alias `VerifyCsrfToken` giữ tương thích).
- `config/cache.php`: `serializable_classes => false`.
- `setting_option()`: cache `theme_option` dạng **array** thay vì Eloquent Collection (tương thích L13 cache).
- Test: `php artisan test --compact` → **176 passed, 1 skipped**.

---

## 2026-07-09 — CUST-RF-006: Full regression Track R

- `php artisan test --compact`: **166 passed, 1 skipped** (654 assertions).
- Track R (`CUST-RF-001` … `006`) hoàn tất.

---

## 2026-07-08 — CUST-RF-004: CustomerAccountService

- `app/Services/CustomerAccountService.php`: link guest orders, update profile, order ownership, order lists/details helpers.
- `AccountController`, `CustomerAuthController` inject service (constructor DI).
- Test: `tests/Unit/CustomerAccountServiceTest.php`.

---

## 2026-07-08 — CUST-RF-003 + RF-005: Di chuyển view account

- Di chuyển 12 view active `frontend/customer/*` → `frontend/account/*` (login, register, profile, myorder, orderdetail, auth/change_pass, auth/forget-password\*, includes/account-nav, login_success, register_success).
- Cập nhật view path trong `AccountController`, `CustomerAuthController`, `ForgotPasswordController`, `RegisterController`, `CustomerController` (legacy delegate) → `.account.*`.
- `sidebar-customer` legacy include trỏ `account.includes.account-nav`.
- Route name `customer.*` **giữ nguyên** (URL không đổi).
- Legacy giữ trong `customer/`: wishlist, messages, my-post, my-point, order-view, profile.blade_bk, auth/login, auth/register, auth/menu\*, auth/passwords.
- Grep view-path `.customer.<migrated>` = 0.

---

## 2026-07-08 — CUST-RF-002: Tách controller account/auth

- `Auth\CustomerAuthController`: login, register page, logout, register-success.
- `Account\AccountController`: dashboard, profile, orders, password.
- `routes/web.php`: route `customer.*` trỏ controller mới.
- `CustomerController`: delegate backward-compat cho method đã tách.
- Test: `CustomerAccountRoutesTest` + regression `CustomerAccount*`.

---

## 2026-07-08 — CUST-RF-001: Drop bảng legacy `customer`

- Audit: bảng `customer` 0 row, không code reference, không FK; tài khoản dùng `users`.
- Migration: `database/migrations/2026_07_08_181633_drop_legacy_customer_table.php`.
- Giữ `customer_forget_pass_otp` (OTP quên mật khẩu đang dùng).
- Test: `tests/Feature/LegacyCustomerTableTest.php`.
- Docs: `CUSTOMER_ACCOUNT_PLAYBOOK.md`, `PROJECT_ANALYSIS.md`.

---

## 2026-07-08 — CUST-008: Dọn legacy sidebar account

- `sidebar-customer.blade.php` delegate sang `account-nav` (một menu chuẩn).
- Ẩn khỏi nav: messages/TalkJS, wishlist, ví (`payment-point`), quản lý tin đăng, reviews.
- Route legacy giữ nguyên (chỉ bỏ link trên sidebar).
- Test: `tests/Feature/CustomerAccountLegacyNavTest.php`.

---

## 2026-07-08 — CUST-005 + CUST-006 + CUST-007: P1 account UI

**CUST-005 — Orders UI**

- `myorder.blade.php`, `orderdetail.blade.php`: Tailwind, tiếng Việt, sidebar `account-nav`.
- `CustomerController@myOrder` / `myOrderDetail`: truyền `orderStatus`, `orderPayment`; eager-load `product` trên order items.
- Link chi tiết đơn qua `customer.orders.show` (bỏ AJAX order-view).

**CUST-006 — Profile & mật khẩu**

- `profile.blade.php`, `auth/change_pass.blade.php`: Tailwind + flash message.
- `UpdateCustomerProfileRequest`, `UpdateCustomerPasswordRequest`.
- `updateProfile`: avatar lưu `storage/app/public/avatars`.
- `ForgotPasswordController`: `$_SESSION` → Laravel `session()`.
- `User` `$fillable`: mở rộng `avatar`, `address`, v.v.

**CUST-007 — Header & tests**

- `header.blade.php`: `@auth` / `@guest` — Tài khoản, Đơn hàng, Đăng xuất (POST) / Đăng nhập, Đăng ký.
- Test: `tests/Feature/CustomerAccountAuthenticatedTest.php`.

---

## 2026-07-08 — CUST-003 + CUST-004: URL Hướng B + đơn hàng ↔ user

**CUST-003 — Routes**

- `/auth/*` (login, register, logout POST, forgot-password), `/account/*` (profile, orders, password).
- Route names chuẩn `customer.*`; redirect 301 từ URL cũ (`/customer/*`, `/forget/password*`).
- `Authenticate`, `Handler`, header, sidebar: `customer.login`; logout POST + CSRF.
- Test: `tests/Feature/CustomerAccountRoutesTest.php`.

**CUST-004 — Orders**

- `CartController@checkoutConfirm`: gán `user_id` khi đã login.
- `postLogin`: gắn đơn guest theo `cart_email`.
- `myOrderDetail` / `orderView`: scope `user_id`, abort 403 (IDOR).
- `myOrder`: truyền `orderPayment`; `index` redirect → profile.
- Test: `tests/Feature/CustomerOrderOwnershipTest.php`.

---

## 2026-07-08 — CUST-002: Auth login/register end-to-end

- `postLogin`: cho login khi `status == 1` (chuẩn codebase: 1 = active).
- `RegisterController@register`: trả JSON `{error, msg, view}` khớp view JS + `Auth::login` sau đăng ký; reCAPTCHA bỏ qua khi env ≠ production.
- `register.blade.php`: form khớp validator (`name`, `phone`, `email`, `password`, `password_confirm`).
- `tests/TestCase.php`: thêm cột `phone` vào bảng `users` (sqlite test).
- Test: `tests/Feature/CustomerAuthFlowTest.php` (4 cases).

---

## 2026-07-08 — CUST-001: View path customer account

- `APP_THEME=frontend` trong `.env`.
- `CustomerController`: thay `theme.customer.*` → `$this->templatePath.'.customer.*'`.
- `AppServiceProvider`: `View::prependNamespace('theme', frontend)` backward-compat.
- `register.blade.php` → `frontend.layouts.master`; `login.blade.php` AJAX dùng form action.
- Test: `tests/Feature/CustomerAccountViewTest.php`.

---

## 2026-07-07 — Đóng backlog BACK + dọn tài liệu

- Cập nhật `BACKEND_AUDIT_PLAYBOOK.md`, `RECOMMENDATIONS.md`, `PROJECT_ANALYSIS.md` — BACK-001…018 `done`.
- Xóa `CHANGELOG.md` (root, lỗi thời từ 2026-02; dùng `docs/CHANGE_LOG.md`).
- Test: `DocumentationCleanupTest`.

---

## 2026-07-07 — BACK-018: xóa `public/assets/js/main.js`

- Gỡ `public/assets/js/main.js` — không được include; frontend dùng Vite (`resources/js/app.js` → `custom.js`, `axios-setup.js`).
- Không đụng `public/assets/login/js/main.js`.
- Test: `tests/Feature/PublicLegacyJsCleanupTest.php`.

---

## 2026-07-07 — BACK-017: Theme CSS editor

- Form Request `UpdateThemeCss`: chỉ administrator, validate + chặn pattern XSS/CSS injection.
- `AdminController@getCSS` / `updateCSS`: RBAC administrator, path cố định `user_custom.css`.
- Sidebar ẩn link Theme CSS với non-administrator.
- Test: `BackendP2ContinuationTest` (theme CSS tests).

---

## 2026-07-07 — BACK-016: RouteServiceProvider prefix

- Sửa `Route::prefix('admin', 'currency')` → `Route::prefix('admin')` (tham số `currency` thừa, không phải middleware).
- Test: `BackendP2ContinuationTest::test_admin_routes_registered_under_admin_prefix`.

---

## 2026-07-06 — P2: BACK-012…015

- **BACK-012:** Form Request cho Post, UserAdmin, Order, Menu; wire controllers.
- **BACK-013:** `POST /admin/logout` + form CSRF trong nav/sidebar (gỡ GET).
- **BACK-014:** Migration index `slug` / `status` trên `pages`, `products`, `categories`.
- **BACK-015:** Test `tests/Feature/BackendP2ContinuationTest.php`.

**Deploy:** `php artisan migrate` (BACK-014).

---

## 2026-07-06 — P2: BACK-010 + BACK-011

- **BACK-010:** Pagination admin dùng `$paginator->total()` thay `count()`.
- **BACK-011:** Đăng ký routes `admin.post-category.*`; thêm REST methods `PostCategoryController`.
- Sửa test ACL sau P1: `AdminMenuItemStoreTest`, `AdminAxiosP1Test` skip mysql.
- Test: `tests/Feature/BackendP2HardeningTest.php`.

---

## 2026-07-06 — P1 backend hardening (BACK-004…009)

- **BACK-004:** Mở rộng `checkAdminPermission` cho CRUD/AJAX admin (trừ dashboard, cc, đổi mật khẩu).
- **BACK-005:** Gỡ `ALTER TABLE AUTO_INCREMENT` trong `AjaxController@ajax_delete`.
- **BACK-006:** `User` bỏ `$guarded`; `UserAdminController` + `PostController` dùng `$request->only()`.
- **BACK-007:** Header frontend dùng `route('user.login')`.
- **BACK-008:** `Authenticate::redirectTo()` → `user.login`.
- **BACK-009:** Rate limit `auth` 6/phút trên login/register/forgot password.
- Test: `tests/Feature/BackendP1HardeningTest.php`.

---

## 2026-07-06 — CKFinder: route thiếu middleware `web`

- Route package CKFinder không có `web` → không load session → `Auth::guard('admin')->check()` luôn `false` sau BACK-002.
- `config/ckfinder.php`: `loadRoutes = false`.
- `RouteServiceProvider`: đăng ký `/ckfinder/*` trong group `web`.
- Override `ckfinder::setup` → load `assets/plugin/ckfinder/ckfinder.js` (package mặc định trỏ `js/ckfinder/` → 404 popup CKEditor).
- Test bổ sung: `CustomCKFinderAuthTest::test_ckfinder_browser_page_loads_assets_plugin_script`.

---

## 2026-07-06 — BACK-002 + BACK-003 (P0)

- **BACK-002:** Sửa `CustomCKFinderAuth` — chỉ admin đăng nhập mới pass CKFinder auth (`Auth::guard('admin')->check()`).
- **BACK-003:** Thêm `PostController::destroy()` và `show()` (redirect edit); test xóa bài + phân biệt `type=post` vs `page`.
- Test: `tests/Feature/CustomCKFinderAuthTest.php`, `tests/Feature/AdminPostCrudTest.php`.

---

## 2026-07-06 — BACK-001: khóa `/admin/cc`

- Gỡ `GET /admin/cc` public.
- Thêm `POST /admin/cc` trong group `auth:admin`, route name `admin.cache.clear`.
- Thêm item **Xóa cache** dưới Setting sidebar (form POST + CSRF).
- Test: `tests/Feature/AdminClearCacheRouteTest.php`.

---

## 2026-07-06 — Backend audit playbook

- Tạo `docs/BACKEND_AUDIT_PLAYBOOK.md` — 18 hạng mục `BACK-001`…`BACK-018` (P0–P3).
- Cập nhật `RECOMMENDATIONS.md`, `PROJECT_ANALYSIS.md` §6.2, `IMPROVEMENT_PLAYBOOK.md` (link backlog).
- Test: `tests/Feature/DocumentationCleanupTest.php`.

---

## 2026-07-05 — Xóa tài liệu lịch sử (knowledge base cleanup)

- Xóa: `PLAN-category-recursive.md`, `docs/frontend-tailwind-v4-canonicalization.md`, `docs/ui-upgrade.md`, `bug_hunt_prompt.txt`.
- Nội dung IMP-007/008/010 giữ trong `docs/IMPROVEMENT_PLAYBOOK.md` + mục cũ trong file này.
- Cập nhật link: `RECOMMENDATIONS.md`, `docs/IMPROVEMENT_PLAYBOOK.md`.
- Test: `tests/Feature/DocumentationCleanupTest.php`.

---

## 2026-07-05 — Documentation audit (doc ↔ code sync)

- `docs/PROJECT_ANALYSIS.md`: CSRF/API/JS đã xử lý; tách §6 done vs backlog; thêm bảng frontend assets.
- `docs/IMPROVEMENT_PLAYBOOK.md`: IMP-015 (JS P1–P4); smoke test full suite.
- `RECOMMENDATIONS.md` + `README.md`: living doc index + backlog P0–P3.
- `bug_hunt_prompt.txt`: cập nhật bối cảnh kỹ thuật sau cleanup.

---

## 2026-07-05 — P2 + P4: Gỡ JS public thừa (webpack cũ)

- Xóa `public/assets/js/custom.js` — bản duplicate cũ; frontend dùng `resources/js/custom.js` qua Vite.
- Xóa `public/assets/js/app.js` (~47k dòng, webpack bundle cũ + Vue comment); không còn được include.
- Giữ nguyên admin: `jquery-3.7.1.min.js`, `js_admin.js`.
- Test: `tests/Feature/PublicLegacyJsCleanupTest.php`.

---

## 2026-07-05 — P3: Gỡ Vue components chết (`resources/js/components/`)

- Xóa 8 file `.vue` không được import (Vite chỉ bundle `resources/js/app.js`, không có `vue` trong `package.json`).
- Các component gọi API legacy đã gỡ: `/api/slider`, `/api/category-products-html*`, `api/search-with-vue`, `api/test-pagination`.
- Test: `tests/Feature/DeadVueComponentsCleanupTest.php`.

---

## 2026-07-05 — Đồng bộ axios cho admin AJAX + dọn JS thừa

- Đổi `$.ajax` → `axios` trong `backend/admin-menu.blade.php` (xóa item + lưu sắp xếp) và `backend/change-password.blade.php` (check-password).
- Xóa file thừa `public/assets/laravel-menu/menu_no_delete.js` (không được include ở đâu).
- Không còn `$.ajax` trong `resources/views/backend/**`.

---

## 2026-07-05 — Smoke test frontend + dọn tài liệu

- `FrontendPagesTest`: cart, checkout, search, 404, assert class Tailwind `grow`.
- Rút gọn `PLAN-category-recursive.md`, `frontend-tailwind-v4-canonicalization.md`, cập nhật `RECOMMENDATIONS.md`.

---

## 2026-07-05 — Tailwind v4 canonical classes (IMP-010)

- Frontend Blade: `flex-grow` → `grow`, `flex-shrink-0` → `shrink-0`, `bg-gradient-to-*` → `bg-linear-to-*`.
- Arbitrary → scale: `rounded-[2rem]` → `rounded-4xl`, `h-[30rem]` → `h-120`, `min-h-[3.75rem]` → `min-h-15`, `min-w-[640px]` → `min-w-160`, `min-h-[120px]` → `min-h-30`, `min-w-[2.5rem]` → `min-w-10`.
- Phạm vi: `resources/views/frontend/**`, `errors/404.blade.php` — không đụng admin Bootstrap.
- Giữ nguyên: `max-w-[140px]`, shadow phức tạp trong `sidebar-categories.blade.php` (Batch D).
- `pnpm run build` pass.

---

## 2026-07-05 — Gỡ API legacy (ApiController)

- Xóa `app/Http/Controllers/ApiController.php` (schema `theme`/`category_theme` cũ, không load route).
- `routes/api.php` giữ stub trống cho API tương lai.
- Dọn comment dead code trong `RouteServiceProvider`.
- Test: `tests/Feature/ApiLegacyCleanupTest.php`.

---

- Controller prefetch `childrenMap` (`groupBy('parent')`) — `ProductCategoryController`, `ProductController`, `MenuController`.
- Blade đệ quy không query DB: `select-category`, `category_item`, `partials/category-item`.
- Truyền `childrenMap` rõ ràng trong `@include` (index/single/partials).
- Test: `ProductCategoryCategoryTreeViewsTest` — render 4 cấp + 0 query khi render view.

---

## 2026-07-05 — Dọn checkout / quick-buy legacy (IMP-012)

- Route `checkout-process` → `CartController@legacyCheckoutProcessRedirect`.
- View `quick-buy*.blade.php`, `cart-confirm.blade.php` → stub thông báo + chuyển hướng (bỏ `templateFile/js/cart.js`, Stripe CDN).
- Mặc định `templatePath` / `templateFile` = `frontend` trong `AppServiceProvider` và `Controller`.
- Luồng đặt hàng chính: `CartController` (`/cart`, `/checkout`, `checkoutConfirm`, `completed`).
- Test: `tests/Feature/CheckoutLegacyCleanupTest.php`.

---

## 2026-07-05 — Bật CSRF protection (IMP-009)

- Bật `App\Http\Middleware\VerifyCsrfToken` trong nhóm middleware `web` (`app/Http/Kernel.php`).
- Middleware kế thừa `Illuminate\Foundation\Http\Middleware\ValidateCsrfToken`.
- **Ngoại lệ:** `ckfinder/*` — CKFinder PHP connector dùng cơ chế CSRF riêng (`config/ckfinder.php` → `csrfProtection = true`).
- Frontend (Vite): `resources/js/axios-setup.js` gửi header `X-CSRF-TOKEN` từ `<meta name="csrf-token">`.
- Admin: `public/assets/js/js_admin.js` đã gửi token; không đổi.
- Test: `tests/Feature/CsrfProtectionTest.php`.

**Smoke test sau deploy:** đăng nhập admin, thêm giỏ hàng, checkout, quick-view, upload ảnh CKFinder.

---

# Ghi chú: Loại bỏ bảng `admin_permission` và `admin_role_permission`

**Ngày:** 2026-03-22

## Lý do

- Hai bảng này là **schema ACL cũ** (tạo trong migration `2026_02_11_100000_create_admin_permissions_tables.php`).
- Dữ liệu đã được **migrate** sang hệ thống chuẩn Laravel-style:
    - Bảng **`permissions`**
    - Pivot **`permission_role`**
    - Migration thực hiện copy: `database/migrations/2026_02_11_110000_refactor_acl_schema.php` (mục 7–8).
- Code runtime **không** đọc/ghi `admin_permission` / `admin_role_permission`:
    - `App\Models\Backend\Permission` dùng `$table = 'permissions'`.
    - Quan hệ role–permission dùng pivot **`permission_role`** (`Role::permissions()`, `Permission::roles()`).
    - `CheckAdminPermission` / `User::allPermissions()` chỉ load qua `roles()->with('permissions')`.

## Việc đã làm

1. **Migration mới:** `database/migrations/2026_03_22_120000_drop_legacy_admin_permission_tables.php`
    - `Schema::dropIfExists('admin_role_permission')`
    - `Schema::dropIfExists('admin_permission')`

2. **Sửa test:** `tests/Feature/AdminPermissionTest.php`
    - Assert đúng bảng `permissions` và `permission_role` (trước đó nhầm với bảng legacy).

3. **Cập nhật health check:**
    - `app/Console/Commands/CheckSystemHealth.php`
    - `tests/Feature/SystemHealthTest.php`
    - Danh sách bảng kiểm tra: bỏ hai bảng legacy, thêm `permissions` / `permission_role` nếu cần.

4. **Xóa model không dùng:** `app/Models/Backend/RolePermission.php` (chỉ còn comment `$table`, không được reference).

5. **Cập nhật tài liệu:** `docs/PROJECT_ANALYSIS.md` (danh sách bảng + mô tả ACL).

## Cách áp dụng trên server

```bash
php artisan migrate
```

Sau khi chạy, trong HeidiSQL/MySQL sẽ **không còn** hai bảng trên. Quyền admin vẫn hoạt động qua `permissions` + `permission_role` + `role_user`.

## Rollback

- Migration drop **không** tạo lại bảng trong `down()` (tránh tái giới thiệu schema lỗi thời).
- Nếu cần khôi phục dữ liệu cực hiếm từ backup DB trước khi drop, khôi phục file SQL backup; không khuyến khích tái tạo bảng legacy trong code mới.
