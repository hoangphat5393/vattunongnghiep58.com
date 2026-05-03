# Security Report — vattunongnghiep58

> Phạm vi: static audit dựa trên file tree + routes + một số controller/model/view trọng yếu. Mục tiêu là “phát hiện rủi ro” và “đặt ưu tiên”, không refactor ngay.

## Executive Summary

Các phát hiện Critical/High có thể dẫn đến compromise hệ thống hoặc gian lận dữ liệu:

1. **CSRF protection đang bị tắt toàn cục** trong web middleware group.
2. **Admin route `/admin/cc` chạy `optimize:clear` không có auth middleware** (khả năng public endpoint).
3. **Mass assignment risk diện rộng** do nhiều model dùng `protected $guarded = [];`.
4. **Social login flow có nhiều điểm yếu** (password derived từ token, email giả, folder permission 0777, fetch remote avatar không kiểm soát).
5. **Payment/VNPay flow có dấu hiệu không hoàn chỉnh + hardcode** (có thể sai business + rủi ro fraud nếu callback verify không chặt).

## Findings (Prioritized)

### P0 — Critical

#### 1) CSRF middleware disabled (system-wide)

- Evidence:
  - `app/Http/Kernel.php` (web group) comment CSRF:
    - `// \App\Http\Middleware\VerifyCsrfToken::class,`
- Impact:
  - Tất cả POST/PUT/DELETE routes trên web có thể bị CSRF.
  - Với admin routes, có thể dẫn đến xoá/sửa dữ liệu khi admin đang đăng nhập.
- Notes:
  - Nhiều form vẫn có `@csrf`, nhưng nếu middleware bị tắt thì token không được verify.

#### 2) Unauthenticated “maintenance” endpoint (`/admin/cc`)

- Evidence:
  - `routes/admin.php` có:
    - `Route::get('cc', function () { Artisan::call('optimize:clear'); ... });`
  - Route này nằm **ngoài** group `middleware => ['auth:admin']`.
- Impact:
  - Bất kỳ ai có thể gọi URL này để clear cache liên tục (DoS nhẹ/ảnh hưởng hiệu năng).
  - Có thể gây side-effects khi clear cache trong production.

### P1 — High

#### 3) Mass assignment risk (many models)

- Evidence:
  - Nhiều model có `protected $guarded = [];` (52 occurrences trong `app/`).
  - Ví dụ: `app/Models/Frontend/Order.php`, `app/Models/Backend/User.php`, ...
- Impact:
  - Nếu có endpoint nhận input chưa validate/filtered chặt, attacker có thể set các field nhạy cảm (role/admin_level/status/price...) tùy theo model.

#### 4) Social login hardening gaps

- Evidence:
  - `app/Http/Controllers/RegisterAuthController.php`
    - Tạo email fallback `*@gmail.com` khi provider không trả email
    - `password` = `bcrypt($User_Data->token)`
    - `File::makeDirectory($path, 0777, ...)`
    - `file_get_contents(...)` lấy avatar từ URL (kèm token cho Facebook)
- Impact:
  - Account takeover / account collision (đặc biệt nếu provider email không ổn định).
  - Rủi ro về filesystem permission (0777).
  - Rủi ro SSRF/DoS do remote fetch không có timeout/hardening.

#### 5) Admin permission enforcement relies on URL matching (risk of bypass)

- Evidence:
  - `app/Models/Backend/User.php` và `app/Models/Frontend/User.php` có `checkUrlAllowAccess($url)` và xử lý pattern `*`, `{id}`.
- Impact:
  - Nếu route patterns/URL normalization không nhất quán (slash, query, scheme), có thể phát sinh bypass hoặc false deny.
  - Coupling cao giữa user model và URL generator.

#### 6) Payment / VNPay flow incomplete + hardcoded data

- Evidence:
  - `app/Http/Controllers/PaymentController.php`
    - `vnp_Bill_Email` hardcoded
    - `vnp_IpAddr` hardcoded
    - `vnp_ReturnUrl` dùng `route('payment.retun')` nhưng không thấy route name này trong `routes/*`
- Impact:
  - Callback/return không chạy → transaction không finalize đúng.
  - Nếu thiếu verify signature đầy đủ (chưa thấy code verify ở routes) → rủi ro giả mạo kết quả thanh toán.

### P2 — Medium

#### 7) API surface unclear (routes/api.php may be disabled)

- Evidence:
  - `app/Providers/RouteServiceProvider.php` comment phần load `routes/api.php`.
- Impact:
  - Nếu API dự kiến dùng cho frontend/AJAX, có thể đang “chết” (availability issue).
  - Nếu được bật lại không kèm auth/rate limit, có thể tăng attack surface.

#### 8) Missing/Drift dependencies (dead code or supply-chain confusion)

- Evidence:
  - `app/Listeners/StripeEventListener.php` tham chiếu `Laravel\Cashier\Events\WebhookReceived` nhưng composer.json không có `laravel/cashier`.
  - `app/Services/Twilio/Verification.php` tham chiếu `Twilio\*` và `App\Verify\*` (không thấy `app/Verify/*`).
  - `app/Services/PayPalService.php` tham chiếu PayPal SDK classes nhưng composer.json không thấy PayPal REST SDK.
- Impact:
  - Runtime errors khi code path được gọi.
  - Khó bảo trì, khó upgrade, dễ phát sinh “phantom feature”.

## Quick Wins (không refactor logic, chỉ hardening)

> Các mục này là gợi ý cho roadmap; không thực hiện ngay trong phiên audit.

- Bật lại CSRF middleware cho web group và audit các endpoint cần ngoại lệ.
- Khoá `/admin/cc` bằng `auth:admin` + permission hoặc xoá khỏi production routing.
- Chuẩn hoá mass assignment: chuyển sang `$fillable` tối thiểu cho các model nhạy cảm (Order/User/PaymentRequest/...).
- Thêm rate limit cho auth endpoints và các endpoint “check email/phone”.
- Hardening social login: không dùng token làm password; validate provider/email; thiết lập file permission an toàn; thay `file_get_contents` bằng HTTP client có timeout và allowlist.
- Hoàn thiện payment callback: routes + verify signature + idempotency.

## Evidence Index (file paths)

- `app/Http/Kernel.php`
- `routes/admin.php`
- `app/Models/*` (nhiều file có `protected $guarded = [];`)
- `app/Http/Controllers/RegisterAuthController.php`
- `app/Http/Controllers/PaymentController.php`
- `app/Providers/RouteServiceProvider.php`

