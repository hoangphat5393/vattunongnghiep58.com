# Backend Audit Playbook — Vật Tư Nông Nghiệp 58

> **Theo dõi rà soát backend** (routes, middleware, Admin controllers, models, tests).  
> **Trạng thái:** ✅ **BACK-001 … BACK-018 hoàn tất** (2026-07-07)  
> Cập nhật lần cuối: **2026-07-07**  
> Nguồn audit: rà soát code + test suite Feature.

**Liên quan:** [IMPROVEMENT_PLAYBOOK.md](IMPROVEMENT_PLAYBOOK.md) (IMP-001…015 đã xong) · [PROJECT_ANALYSIS.md](PROJECT_ANALYSIS.md) (kiến trúc) · [CHANGE_LOG.md](CHANGE_LOG.md)

---

## Cách dùng

1. Mỗi hạng mục có **ID cố định** (`BACK-xxx`) — không đổi ID khi đổi trạng thái.
2. Cập nhật cột **Trạng thái** khi bắt đầu / xong / hoãn.
3. Sau khi fix: ghi `docs/CHANGE_LOG.md` + thêm/ cập nhật test Feature.
4. IMP cũ (`IMP-001…015`) giữ trong `IMPROVEMENT_PLAYBOOK.md`; backlog backend mới dùng `BACK-xxx`.

### Trạng thái

| Ký hiệu       | Ý nghĩa              |
| ------------- | -------------------- |
| `todo`        | Chưa làm             |
| `in_progress` | Đang làm             |
| `done`        | Xong + đã kiểm chứng |
| `deferred`    | Hoãn có lý do        |
| `optional`    | Không blocker        |

---

## Bảng tổng quan

| ID       | Hạng mục                             | Ưu tiên | Trạng thái | File chính                                     |
| -------- | ------------------------------------ | ------- | ---------- | ---------------------------------------------- |
| BACK-001 | Khóa/xóa `GET /admin/cc`             | **P0**  | `done`     | `routes/admin.php`                             |
| BACK-002 | Sửa CKFinder auth (logic đảo)        | **P0**  | `done`     | `CustomCKFinderAuth.php`                       |
| BACK-003 | `PostController::destroy` (+ `show`) | **P0**  | `done`     | `PostController.php`, `routes/admin.php`       |
| BACK-004 | RBAC phủ toàn admin + AJAX           | P1      | `done`     | `routes/admin.php`, `CheckAdminPermission.php` |
| BACK-005 | Bỏ `ALTER TABLE` trong Ajax delete   | P1      | `done`     | `AjaxController.php`                           |
| BACK-006 | Mass assignment User/Post/Order      | P1      | `done`     | `User.php`, `*Controller.php`                  |
| BACK-007 | `route('login')` → `user.login`      | P1      | `done`     | `header.blade.php`, `web.php`                  |
| BACK-008 | `Authenticate::redirectTo()`         | P1      | `done`     | `Authenticate.php`                             |
| BACK-009 | Rate limit login/register            | P1      | `done`     | `web.php`, `RouteServiceProvider.php`          |
| BACK-010 | Pagination `total()` sai             | P2      | `done`     | `ProductController`, `PageController`, …       |
| BACK-011 | Dọn `PostCategoryController` orphan  | P2      | `done`     | `PostCategoryController.php`, `admin.php`      |
| BACK-012 | Form Request Post/User/Order/Menu    | P2      | `done`     | `app/Http/Requests/Admin/`                     |
| BACK-013 | `GET /admin/logout` → POST           | P2      | `done`     | `routes/admin.php`, views                      |
| BACK-014 | Index DB `slug` / `status`           | P2      | `done`     | migrations mới                                 |
| BACK-015 | Feature tests admin CRUD             | P2      | `done`     | `tests/Feature/`                               |
| BACK-016 | `RouteServiceProvider` prefix        | P2      | `done`     | `RouteServiceProvider.php`                     |
| BACK-017 | Theme CSS write / XSS surface        | P2      | `done`     | `AdminController@updateCSS`                    |
| BACK-018 | `public/assets/js/main.js` thừa      | P3      | `done`     | `public/assets/js/main.js`                     |

---

## Hoàn tất & việc còn lại (không thuộc BACK)

| Hạng mục                | Ghi chú                                                                   |
| ----------------------- | ------------------------------------------------------------------------- |
| CKFinder smoke thủ công | BACK-002 checklist — upload ảnh trong CKEditor/admin (không blocker code) |
| `php artisan migrate`   | BACK-014 — index `slug`/`status` trên server nếu chưa chạy                |
| IMP-011                 | Bootstrap admin `deferred` — xem `IMPROVEMENT_PLAYBOOK.md`                |
| Backlog mới             | Mở `BACK-019+` khi có audit tiếp theo                                     |

---

### BACK-001 — `GET /admin/cc` không cần đăng nhập

**Triệu chứng:** `GET /admin/cc` gọi `Artisan::call('optimize:clear')` ngoài `auth:admin`.

**File:** `routes/admin.php` (khoảng L22–27)

**Đã làm (2026-07-06):**

- [x] Gỡ route `GET /admin/cc` public
- [x] Thêm `POST /admin/cc` trong group `auth:admin`, route name `admin.cache.clear`
- [x] Thêm item **Xóa cache** dưới Setting sidebar, dùng form `POST` + `@csrf`
- [x] Controller `AdminController@clearCache` gọi `optimize:clear` rồi redirect dashboard
- [x] Test: `tests/Feature/AdminClearCacheRouteTest.php`

**Kiểm chứng:** `GET /admin/cc` → 405; guest `POST` → redirect login; admin `POST` → clear cache + redirect dashboard.

---

### BACK-002 — CKFinder auth logic đảo

**Triệu chứng:** Guest → `authentication` return `true`; admin đăng nhập → `false`. Kết hợp CSRF exclude `ckfinder/*` → rủi ro upload công khai.

**File:** `app/Http/Middleware/CustomCKFinderAuth.php`

```php
// Hiện tại (SAI):
guest()  → return true
check()  → return false

// Cần:
guest()  → return false
check()  → return true
```

**Checklist:**

- [x] Đảo return values — dùng `Auth::guard('admin')->check()` trong callable
- [x] Test: `tests/Feature/CustomCKFinderAuthTest.php`
- [ ] Smoke: admin upload ảnh trong CKEditor/CKFinder OK (thủ công)

**Kiểm chứng:** Guest `POST /ckfinder/connector` ≠ 200; middleware callback guest → `false`, admin → `true`.

**Hotfix (2026-07-06):** Route package mặc định **không** có middleware `web` → session admin không load → `check()` luôn `false`. Đã tắt `loadRoutes`, đăng ký lại route trong `RouteServiceProvider` với group `web`.

---

### BACK-003 — Post REST thiếu method

**Triệu chứng:** `routes/admin.php` đăng ký `DELETE /admin/post/{id}` và `GET .../show` qua loop module, nhưng `PostController` không có `destroy()` / `show()` → 500 khi xóa bài.

**File:** `app/Http/Controllers/Admin/PostController.php`

**Đã làm (2026-07-06):**

- [x] Thêm `destroy($id)` — `Page::posts()->findOrFail`, redirect index + flash
- [x] Thêm `show($id)` — redirect `admin.post.edit` (không có view show riêng)
- [x] Test: `tests/Feature/AdminPostCrudTest.php`

**Kiểm chứng:** Admin `DELETE /admin/post/{id}` → redirect index; guest → login; xóa `type=page` qua post route → 404.

---

## Chi tiết P1

### BACK-004 — RBAC không phủ hết admin

**Hiện trạng:** Chỉ nhóm `checkAdminPermission` (user, role, permission, order) có ACL. Product, post, menu, album, theme, AJAX delete chỉ cần `auth:admin`.

**Hướng xử lý:**

- [x] Mở rộng `checkAdminPermission` cho toàn bộ route quản trị (trừ dashboard, cc, change-password, check-password)
- [x] AJAX delete/replicate/quickchange nằm trong group ACL
- [x] Test: `BackendP1HardeningTest::test_user_without_permission_cannot_access_product_index`

---

### BACK-005 — `ALTER TABLE AUTO_INCREMENT` trong HTTP

**File:** `app/Http/Controllers/Admin/AjaxController.php` — nhiều case trong `ajax_delete`

**Lịch sử:**

- (2026-07-06) Gỡ `ALTER TABLE` khỏi HTTP (audit P1)
- (2026-07-06) **Khôi phục** theo yêu cầu vận hành — helper `resetTableAutoIncrement()`, bỏ qua SQLite (test)

---

### BACK-006 — Mass assignment

**Đã làm (2026-07-06):**

- [x] `User`: bỏ `$guarded = []`, giữ `$fillable`
- [x] `UserAdminController@post`: `$request->only([...])` thay `except()`
- [x] `PostController@post`: `$request->only([...])` thay `except()`
- [x] `OrderController@postOrderDetail` — Form Request (BACK-012)
- [x] Test: `BackendP1HardeningTest::test_user_model_ignores_non_fillable_fields_on_create`

---

### BACK-007 — Route name đăng nhập frontend

**Đã làm (2026-07-06):**

- [x] Sửa view: `route('user.login')` trong `header.blade.php` + `menu-module/html/header.blade.php`
- [x] Test: `BackendP1HardeningTest::test_frontend_header_uses_user_login_route`

---

### BACK-008 — Redirect khi chưa auth (customer)

**Đã làm (2026-07-06):**

- [x] `Authenticate::redirectTo()` → `route('user.login')` khi không expectsJson
- [x] Test: `BackendP1HardeningTest::test_guest_customer_route_redirects_to_user_login`

---

### BACK-009 — Rate limiting auth

**Đã làm (2026-07-06):**

- [x] Bật `configureRateLimiting()` + limiter `auth` (6/phút/IP)
- [x] `throttle:auth` trên POST login, register, forgot password
- [x] Test: `BackendP1HardeningTest::test_customer_login_is_rate_limited`

## Chi tiết P2 / optional

### BACK-010 — Pagination total sai

**Đã làm (2026-07-06):** Đổi `$paginator->count()` → `$paginator->total()` trong Product, Page, Album, Contact, ProductCategory, PostCategory controllers.

**Test:** `BackendP2HardeningTest::test_page_index_reports_total_not_page_count`

---

### BACK-011 — `PostCategoryController` orphan

**Đã làm (2026-07-06):**

- [x] Thêm `post` vào `$modules_with_category` → routes `admin.post-category.*`
- [x] Thêm `store`, `update`, `show`, `destroy` vào `PostCategoryController`
- [x] Test: `BackendP2HardeningTest`

---

### BACK-012 — Form Request legacy

**Đã làm (2026-07-06):**

- [x] `Post\StorePost`, `Post\UpdatePost` → `PostController@store` / `update`
- [x] `UserAdmin\StoreUserAdmin`, `UpdateUserAdmin` → `UserAdminController@store` / `update`
- [x] `Order\UpdateOrderDetail` → `OrderController@postOrderDetail`
- [x] `Menu\StoreMenu`, `StoreMenuItem`, `GenerateMenu` → `MenuController`
- [x] Test: `BackendP2ContinuationTest`

---

### BACK-013 — Logout admin qua GET

**Đã làm (2026-07-06):**

- [x] `POST /admin/logout` + CSRF (gỡ GET)
- [x] Form logout trong `nav.blade.php`, `sidebar.blade.php`
- [x] Test: `BackendP2ContinuationTest::test_admin_logout_via_post_redirects_to_login`

---

### BACK-014 — Index database

**Đã làm (2026-07-06):** Migration `2026_07_06_120000_add_slug_status_indexes.php`

- [x] `pages`: `(slug)`, `(status, id)`, `(type, slug)`
- [x] `products`: `(slug)`, `(status, id)`
- [x] `categories`: `(slug)`, `(status, id)`, `(parent)`
- [x] Bỏ qua index đã tồn tại (idempotent)

**Deploy:** `php artisan migrate`

---

### BACK-015 — Test coverage admin

**Đã làm (2026-07-06):** `tests/Feature/BackendP2ContinuationTest.php`

- [x] Post store validation + create
- [x] User store validation
- [x] Product store validation
- [x] Menu store validation (JSON)
- [x] Order update validation
- [x] Ajax delete validation
- [x] Admin logout POST

---

### BACK-016 — `RouteServiceProvider` (optional)

**Đã làm (2026-07-07):**

- [x] `Route::prefix('admin', 'currency')` → `Route::prefix('admin')` (bỏ tham số `currency` thừa)
- [x] Test: `BackendP2ContinuationTest::test_admin_routes_registered_under_admin_prefix`

---

### BACK-017 — Theme CSS editor (optional)

**Đã làm (2026-07-07):**

- [x] `UpdateThemeCss` Form Request — chỉ `administrator`, max 512KB, chặn pattern nguy hiểm (`javascript:`, `<script`, …)
- [x] `getCSS` / `updateCSS` — `authorizeThemeCssAccess()`, ghi cố định `public/assets/css/user_custom.css`
- [x] Sidebar: ẩn **Theme CSS** với user không phải administrator
- [x] Test: `BackendP2ContinuationTest` (403, save OK, reject dangerous CSS)

---

### BACK-018 — `main.js` thừa (optional)

**Đã làm (2026-07-07):**

- [x] Xóa `public/assets/js/main.js` (template cũ, không được include; logic đã có trong Vite `resources/js/custom.js` + `axios-setup.js`)
- [x] Giữ nguyên `public/assets/login/js/main.js` (file khác, login form)
- [x] Test: `PublicLegacyJsCleanupTest`

---

## Điểm đã ổn (không cần BACK mới)

| Hạng mục                     | Ghi chú                |
| ---------------------------- | ---------------------- |
| CSRF web                     | IMP-009 `done`         |
| Admin Blade axios            | IMP-015 `done`         |
| API legacy gỡ                | `ApiLegacyCleanupTest` |
| Checkout legacy              | IMP-012 `done`         |
| Category tree N+1            | IMP-008 `done`         |
| `ajax_quickchange` whitelist | IMP-005 `done`         |

---

## Thứ tự đề xuất

```
Sprint 1 (P0):  BACK-001 → BACK-002 → BACK-003
Sprint 2 (P1):  BACK-004, BACK-005, BACK-007, BACK-008
Sprint 3 (P1):  BACK-006, BACK-009
Sprint 4 (P2):  BACK-010 … BACK-015
Tùy chọn:       BACK-016 … BACK-018
```

---

## Nhật ký

| Ngày       | Thay đổi                                                                         |
| ---------- | -------------------------------------------------------------------------------- |
| 2026-07-06 | BACK-001 done: khóa `/admin/cc` bằng POST + auth admin, thêm sidebar “Xóa cache” |
| 2026-07-07 | BACK-018 done: xóa `public/assets/js/main.js` (dead code)                        |
| 2026-07-07 | BACK-017 done: Theme CSS editor — administrator-only + validation                |
| 2026-07-07 | BACK-016 done: sửa `Route::prefix('admin')` trong RouteServiceProvider           |
