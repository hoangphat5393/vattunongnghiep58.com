# Customer Account Playbook — Vật Tư Nông Nghiệp 58

> **Theo dõi chuẩn hóa tài khoản khách hàng** (đăng nhập, đăng ký, khu vực account, lịch sử đơn).  
> **Trạng thái:** ✅ **CUST-001…004 `done` (P0 xong)** · CUST-005…008 `todo`  
> Cập nhật lần cuối: **2026-07-08**  
> Nguồn: rà soát code + smoke test local (2026-07-07).

**Liên quan:** [IMPROVEMENT_PLAYBOOK.md](IMPROVEMENT_PLAYBOOK.md) · [BACKEND_AUDIT_PLAYBOOK.md](BACKEND_AUDIT_PLAYBOOK.md) · [PROJECT_ANALYSIS.md](PROJECT_ANALYSIS.md) · [CHANGE_LOG.md](CHANGE_LOG.md)

---

## Mục tiêu

1. Module **tài khoản khách mua hàng** chạy end-to-end (login → đặt hàng → xem lại đơn).
2. **URL chuẩn Hướng B** (`/auth/*`, `/account/*`) + redirect 301 từ URL cũ.
3. **Route name** thống nhất prefix `customer.*` (tên module nghiệp vụ, không phụ thuộc URL `/account`).
4. Playbook **copy được** sang shop Laravel khác (cùng pattern route + test).

---

## Thuật ngữ (tránh nhầm)

| Thuật ngữ                      | Ý nghĩa                                                                    | Đổi tên khi Hướng B?       |
| ------------------------------ | -------------------------------------------------------------------------- | -------------------------- |
| **Customer** (nghiệp vụ)       | Khách hàng — đối tượng admin quản lý, model `User`, `Admin\Customer\*`     | ❌ Giữ                     |
| **Account** (UI)               | Khu vực tự phục vụ sau login — **chỉ URL** `/account/*`                    | ✅ Đổi URL                 |
| **`users` (bảng)**             | Bảng Auth guard `web` — chuẩn Laravel                                      | ❌ **Không** đổi (Track B) |
| **`customer` (bảng)**          | ~~Bảng legacy~~ — **đã drop** (CUST-RF-001, migration `2026_07_08_181633`) | ✅ Xong                    |
| **`CustomerController`**       | Controller frontend account                                                | ❌ Giữ (Track B)           |
| **`views/frontend/customer/`** | Blade khu account                                                          | ❌ Giữ (Track B)           |

---

## Hai track triển khai

### Track B — Chuẩn URL (khuyến nghị làm trước)

- Đổi **URL** + **route name** + sửa bug (view `theme.*`, `user_id` đơn, auth status).
- **Không** đổi tên controller, thư mục view, bảng DB.
- Effort: ~4–5 ngày · Rủi ro: thấp.

### Track R — Refactor toàn phần (tùy chọn, sau Track B ổn định)

- Đổi thêm: controller, thư mục view, tách service, dọn bảng legacy.
- **Lúc này** mới đổi `views/frontend/customer/` → `views/frontend/account/` cho đồng bộ.
- Effort: +5–10 ngày · Rủi ro: cao — chỉ khi có test coverage đủ.

> **Trả lời câu hỏi refactor:** Có, **được phép** đổi tên thư mục view `customer` → `account` **nếu và chỉ nếu** bạn refactor cả controller (vd. `AccountController`) trong **cùng một phase Track R**, làm **một lần**, có mapping đầy đủ và test. **Không** nên đổi thư mục view một mình khi chưa đổi controller — lợi ích thấp, rủi ro cao.

---

## Hiện trạng (cập nhật 2026-07-10)

| Chức năng      | URL hiện tại            | Trạng thái                                  |
| -------------- | ----------------------- | ------------------------------------------- |
| Đăng nhập      | `/auth/login`           | ✅ Active — axios, `CustomerAuthController` |
| Đăng ký        | `/auth/register`        | ✅ Active — `fullname`, mail `new_register` |
| Xem đơn        | `/account/orders`       | ✅ Active — `AccountController`, `cart_id`  |
| Profile        | `/account/profile`      | ✅ Active                                   |
| Đổi MK         | `/account/password`     | ✅ Active                                   |
| Quên MK        | `/auth/forgot-password` | ✅ Active — OTP 3 bước                      |
| Checkout guest | `/checkout`             | ✅ OK                                       |

**Test tự động:** `CustomerAuthFlowTest`, `CustomerRegistrationEmailTest`, `CustomerAccountRoutesTest`, …

> URL cũ `/customer/*`, `/forget/password*` → redirect 301 (xem [ROUTE_GLOSSARY.md](ROUTE_GLOSSARY.md) §6).

---

## Chuẩn URL — Hướng B (đích)

### Guest — prefix `/auth`

| URL mới                        | Method   | Route name mới                    | Route name cũ (alias/redirect)   |
| ------------------------------ | -------- | --------------------------------- | -------------------------------- |
| `/auth/login`                  | GET      | `customer.login`                  | `user.login`                     |
| `/auth/login`                  | POST     | `customer.login.submit`           | `loginCustomerAction`            |
| `/auth/register`               | GET      | `customer.register`               | `registerCustomer`               |
| `/auth/register`               | POST     | `customer.register.submit`        | `postRegisterCustomer`           |
| `/auth/logout`                 | POST     | `customer.logout`                 | `customer.logout` (đổi GET→POST) |
| `/auth/forgot-password`        | GET      | `customer.password.forgot`        | `forgetPassword`                 |
| `/auth/forgot-password`        | POST     | `customer.password.forgot.submit` | `actionForgetPassword`           |
| `/auth/forgot-password/verify` | GET/POST | `customer.password.verify`        | `forgetPassword_step2` …         |
| `/auth/forgot-password/reset`  | GET/POST | `customer.password.reset`         | `forgetPassword_step3` …         |

### Account — prefix `/account` (middleware `auth`)

| URL mới                | Method   | Route name mới             | URL cũ → 301                      |
| ---------------------- | -------- | -------------------------- | --------------------------------- |
| `/account`             | GET      | `customer.dashboard`       | `/customer`                       |
| `/account/profile`     | GET      | `customer.profile`         | `/customer/thong-tin`             |
| `/account/profile`     | PUT/POST | `customer.profile.update`  | POST `/customer/thong-tin`        |
| `/account/orders`      | GET      | `customer.orders.index`    | `/customer/my-orders`             |
| `/account/orders/{id}` | GET      | `customer.orders.show`     | `/customer/my-orders-detail/{id}` |
| `/account/password`    | GET      | `customer.password.edit`   | `/customer/change-pass`           |
| `/account/password`    | PUT/POST | `customer.password.update` | POST `/customer/change-pass`      |

### Giữ nguyên (shop core)

| URL              | Route name             | Ghi chú        |
| ---------------- | ---------------------- | -------------- |
| `/cart`          | `cart`                 | Giỏ hàng       |
| `/checkout`      | `cart.checkout`        | Guest checkout |
| `POST /checkout` | `cart.checkout.submit` | Tạo đơn        |

### Redirect 301 (bắt buộc sau đổi URL)

```php
// routes/web.php — nhóm redirect, giữ ít nhất 2 release
Route::redirect('/customer/thong-tin', '/account/profile', 301);
Route::redirect('/customer/my-orders', '/account/orders', 301);
Route::redirect('/customer/my-orders-detail/{id}', '/account/orders/{id}', 301);
Route::redirect('/customer/change-pass', '/account/password', 301);
Route::redirect('/forget/password', '/auth/forgot-password', 301);
Route::redirect('/customer', '/account', 301);
```

---

## Quy ước view (Track B)

- Path Blade: **`frontend.customer.*`** (không `theme.customer.*`).
- Biến layout: `$templatePath` = `frontend` (`APP_THEME=frontend` trong `.env`).
- **Không** đổi thư mục `resources/views/frontend/customer/` trong Track B.

```php
// Đúng
return view($this->templatePath.'.customer.login', $data);
// hoặc
return view('frontend.customer.login', $data);

// Sai (bug hiện tại)
return view('theme.customer.login', $data);
```

---

## Bảng tổng quan — Track B (`CUST-001` … `CUST-008`)

| ID       | Hạng mục                                               | Ưu tiên | Trạng thái | Phụ thuộc          |
| -------- | ------------------------------------------------------ | ------- | ---------- | ------------------ |
| CUST-001 | Sửa view `theme.*` + `APP_THEME=frontend`              | **P0**  | `done`     | —                  |
| CUST-002 | Auth: login/register + status user + URL AJAX          | **P0**  | `done`     | CUST-001           |
| CUST-003 | Route URL Hướng B + route name `customer.*` + 301      | **P0**  | `done`     | CUST-002           |
| CUST-004 | Gắn `user_id` đơn + policy xem đơn                     | **P0**  | `done`     | CUST-002           |
| CUST-005 | Orders UI: `myorder` / `orderdetail` + `orderPayment`  | P1      | `done`     | CUST-004           |
| CUST-006 | Profile + đổi MK + quên MK (view + fillable + session) | P1      | `done`     | CUST-001, CUST-003 |
| CUST-007 | Header `@auth` + logout POST + Feature tests           | P1      | `done`     | CUST-003           |
| CUST-008 | Dọn legacy sidebar (wishlist, ví, tin đăng)            | P2      | `done`     | CUST-007           |

### Trạng thái

| Ký hiệu       | Ý nghĩa              |
| ------------- | -------------------- |
| `todo`        | Chưa làm             |
| `in_progress` | Đang làm             |
| `done`        | Xong + đã kiểm chứng |
| `deferred`    | Hoãn có lý do        |

---

## Chi tiết từng hạng mục — Track B

### CUST-001 — View & theme path (P0)

**Triệu chứng:** `/auth/login`, `/auth/register` → 500 `View [theme.customer.*] not found`.

**File:** `CustomerController.php`, `.env`, tùy chọn `AppServiceProvider.php`

**Checklist:**

- [x] `.env`: `APP_THEME=frontend`
- [x] Thay mọi `view('theme.customer.*')` → `$this->templatePath.'.customer.*'`
- [x] `register.blade.php`: `@extends('frontend.layouts.master')` (bỏ `theme.layouts.index`)
- [x] `login.blade.php`: AJAX POST dùng `form` action (`route('loginCustomerAction')`)
- [x] `View::prependNamespace('theme', frontend)` — backward-compat
- [x] Test: `tests/Feature/CustomerAccountViewTest.php`

---

### CUST-002 — Auth end-to-end (P0)

**Triệu chứng:** Đăng ký xong không login được (`postLogin` chỉ `status == 0`, register tạo `status = 1`).

**File:** `CustomerController.php`, `Auth\RegisterController.php`, `config/auth.php`

**Checklist:**

- [x] Thống nhất `User.status`: `1` = active (`postLogin` `status == 1`)
- [x] `RegisterController@register`: contract JSON `{error, msg, view}` khớp view JS + `Auth::login`
- [x] Register form khớp validator (`name`, `phone`, `email`, `password`, `password_confirm`)
- [x] reCAPTCHA bỏ qua khi env ≠ `production` (đăng ký chạy được local/test)
- [x] Rate limit giữ `throttle:auth`
- [x] Test: `tests/Feature/CustomerAuthFlowTest.php` (register → login → session)

---

### CUST-003 — URL Hướng B + route names (P0) ✅

**File:** `routes/web.php`, `Authenticate.php`, `header.blade.php`, mọi `route('user.login')` …

**Checklist:**

- [x] Đăng ký route name `customer.*` theo bảng trên
- [x] Cập nhật toàn bộ Blade/test sang `customer.*` (không giữ alias cũ)
- [x] Redirect 301 URL cũ (`/customer/*`, `/forget/password*`)
- [x] `POST /auth/logout` + form CSRF (bỏ GET logout)
- [x] Test: `tests/Feature/CustomerAccountRoutesTest.php`

---

### CUST-004 — Đơn hàng ↔ user (P0) ✅

**Triệu chứng:** `shop_orders.user_id` = null → `/account/orders` trống.

**File:** `CartController.php` (`checkoutConfirm`), `CustomerController.php` (`myOrder`, `myOrderDetail`)

**Checklist:**

- [x] Khi checkout: `user_id` = `Auth::id()` nếu đã login
- [x] Guest: sau login gắn đơn theo `cart_email`
- [x] `myOrder` / `myOrderDetail`: `where('user_id', auth()->id())`
- [x] abort 403 nếu đơn không thuộc user (chống IDOR)
- [x] Truyền `orderPayment` trong `myOrder`
- [x] Test Feature: `tests/Feature/CustomerOrderOwnershipTest.php`

---

### CUST-005 — UI đơn hàng (P1)

**File:** `myorder.blade.php`, `orderdetail.blade.php`, `CustomerController.php`

**Checklist:**

- [x] Truyền `orderPayment`, `orderStatus` từ model/helper
- [x] Việt hóa cột (bỏ "Game Name")
- [x] Bật lại `order-view` AJAX hoặc bỏ nút, dùng link `customer.orders.show`
- [x] Đồng bộ Tailwind với header mới (không bắt buộc hết phase 1)

---

### CUST-006 — Profile & mật khẩu (P1)

**File:** `User.php` (`$fillable`), `CustomerController.php`, `ForgotPasswordController.php`, views `frontend/customer/auth/*`

**Checklist:**

- [x] `changePassword()` → view `frontend.customer.auth.change_pass`
- [x] `ForgotPasswordController` → view `frontend.customer.auth.forget-password*`
- [x] Bỏ `$_SESSION` PHP thuần → Laravel `session()`
- [x] Form Request `UpdateCustomerProfileRequest`
- [x] Avatar → `storage/app/public/avatars`

---

### CUST-007 — Header & tests (P1)

**File:** `header.blade.php`, `tests/Feature/CustomerAccount*`

**Checklist:**

- [x] `@guest` → Đăng nhập / Đăng ký
- [x] `@auth` → Tài khoản, Đơn hàng, Đăng xuất (POST)
- [x] Feature tests: login, register, orders, profile, change password, forgot password
- [x] Chạy: `php artisan test --compact tests/Feature/CustomerAccount*.php`

---

### CUST-008 — Legacy (P2) ✅

Ẩn hoặc gỡ khỏi sidebar account (không xóa file trừ khi user yêu cầu **DELETE THIS FILE**):

- [x] `customer.post` (quản lý tin đăng) — không còn trong nav
- [x] `customer.payment.point` (ví) — không còn trong nav
- [x] `customer.messages` / TalkJS — không còn trong nav
- [x] `wishlist` — không còn trong nav
- [x] `sidebar-customer` delegate → `account-nav` (một nguồn nav)
- [x] Test: `tests/Feature/CustomerAccountLegacyNavTest.php`

**Ghi chú:** Route legacy vẫn tồn tại (URL trực tiếp vẫn mở được); chỉ ẩn khỏi menu tài khoản.

---

## Track R — Refactor toàn phần (`CUST-RF-001` …)

> **Chỉ bắt đầu khi Track B (`CUST-001`…`007`) = `done` và test pass.**

### Khi nào nên đổi tên controller + thư mục view?

| Điều kiện                     | Đổi `customer/` → `account/` view? | Đổi `CustomerController`?                             | Đổi bảng `users`?                                  |
| ----------------------------- | ---------------------------------- | ----------------------------------------------------- | -------------------------------------------------- |
| Chỉ Hướng B (URL)             | ❌ Không                           | ❌ Không                                              | ❌ Không                                           |
| Refactor controller + service | ✅ **Có** — cùng PR/phase          | ✅ `AccountController` hoặc tách `Auth/` + `Account/` | ❌ Vẫn giữ `users`                                 |
| Refactor DB đầy đủ            | ✅ Có                              | ✅ Có                                                 | ⚠️ **Không khuyến nghị** đổi `users` → `customers` |

**Khuyến nghị bảng DB (Track R):**

| Bảng / Model                          | Hành động đề xuất                                            |
| ------------------------------------- | ------------------------------------------------------------ |
| `users` + `App\Models\Frontend\User`  | **Giữ** — chuẩn Laravel Sanctum/Auth                         |
| Model alias `Customer` extends `User` | ✅ Tùy chọn — `$table = 'users'`                             |
| `customer` (bảng legacy)              | **Đã drop** — auth dùng `users`; audit local 0 row, không FK |
| `customer_forget_pass_otp`            | Đổi tên `password_reset_otps` (migration mới) nếu cần        |
| `shop_orders.user_id`                 | Giữ — đã đúng nghiệp vụ                                      |

**Lý do không đổi `users` → `customers`:** `config/auth.php`, `sessions.user_id`, foreign key rải rác, package Laravel giả định `users` — chi phí cao, lợi migrate shop khác **thấp**.

### Mapping Track R (nếu làm)

| Hiện tại                                | Đích (Track R)                                                        | Ghi chú                                 |
| --------------------------------------- | --------------------------------------------------------------------- | --------------------------------------- |
| `CustomerController`                    | `Http\Controllers\Account\AccountController` + `Auth\LoginController` | Tách auth vs account                    |
| `views/frontend/customer/`              | `views/frontend/account/`                                             | Đổi **cùng lúc** với controller         |
| `customer.includes.sidebar-customer`    | `account.partials.sidebar`                                            | Đặt tên partial chuẩn                   |
| `Admin\Customer\*` (Form Request admin) | **Giữ nguyên**                                                        | Admin quản lý khách — khác “account UI” |
| Route name `customer.*`                 | Có thể giữ hoặc `account.*`                                           | Chọn 1; URL vẫn `/account/*`            |

### Checklist Track R (rút gọn)

| ID          | Hạng mục                                                               | Trạng thái |
| ----------- | ---------------------------------------------------------------------- | ---------- |
| CUST-RF-001 | Audit bảng `customer` vs `users` — báo cáo merge/deprecate             | `done`     |
| CUST-RF-002 | Tách `AccountController` + `CustomerAuthController`                    | `done`     |
| CUST-RF-003 | Di chuyển view `frontend/customer` → `frontend/account` (1 PR)         | `done`     |
| CUST-RF-004 | `CustomerAccountService` (orders link, profile)                        | `done`     |
| CUST-RF-005 | Cập nhật toàn bộ `@include` / `view()` — grep zero `frontend.customer` | `done`     |
| CUST-RF-006 | Feature test regression full suite                                     | `done`     |

**Thứ tự an toàn Track R:**

```
CUST-RF-001 (audit DB)
  → CUST-RF-002 (controller mới, controller cũ delegate/deprecated)
  → CUST-RF-003 + CUST-RF-005 (view rename cùng commit)
  → CUST-RF-004 (service)
  → CUST-RF-006 (test)
```

---

## Kiểm chứng (living)

```bash
# Sau mỗi phase Track B
php artisan test --compact tests/Feature/CustomerAccount*.php
php artisan route:list --name=customer

# Smoke HTTP local
curl -k -o NUL -w "%{http_code}" https://vattunongnghiep58.test/auth/login
curl -k -o NUL -w "%{http_code}" https://vattunongnghiep58.test/account/orders
```

---

## Thứ tự triển khai đề xuất

```
CUST-001 → CUST-002 → CUST-003 (song song một phần CUST-004)
  → CUST-004 → CUST-005 → CUST-006 → CUST-007
  → (ổn định) → CUST-RF-* nếu cần refactor tên class/view
```

---

## Ghi chú migrate sang shop khác

Khi copy sang dự án mới, mang theo tối thiểu:

1. Bảng mapping URL + route name (mục **Chuẩn URL — Hướng B**).
2. Pattern `shop_orders.user_id` + checkout gắn user.
3. Thư mục view `frontend/customer/` (hoặc `frontend/account/` nếu đã Track R).
4. File test `tests/Feature/CustomerAccount*.php`.
5. **Không** copy bảng `customer` legacy (đã drop khỏi schema mới).

---

_Cập nhật trạng thái `CUST-xxx` trong bảng tổng quan khi bắt đầu / hoàn thành từng hạng mục._
