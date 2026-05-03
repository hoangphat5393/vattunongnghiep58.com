# Laravel Architecture Overview — vattunongnghiep58

## Snapshot

- Laravel: ^12.56 (code structure kiểu Laravel 10, vẫn dùng `app/Http/Kernel.php`, `app/Providers/RouteServiceProvider.php`)
- PHP: ^8.3
- Auth guards: `web` (Frontend\User), `admin` (Backend\User), `api` (token legacy)
- Frontend build: Vite (`vite.config.js`, `package.json`) + Tailwind v4; nhưng public chứa nhiều asset legacy (AdminLTE/jQuery/plugins)

## Entry Points

### HTTP Routing

- Web storefront routes: `routes/web.php`
  - Cart/Checkout: `CartController`
  - Products: `ProductController`
  - Pages: `PageController`
  - News: `NewsController`
  - Auth/Customer: `CustomerController`, `Auth\*`, `RegisterAuthController` (Socialite)
- Admin routes: `routes/admin.php` (prefix `admin`)
  - Auth: `Admin\LoginController`
  - Admin dashboard: `Admin\HomeController@index`
  - CRUD modules (dynamic generate): `Admin\ProductController`, `Admin\PostController`, `Admin\PageController`, `Admin\AlbumController`, `Admin\ContactController`, `Admin\EmailTemplateController`
  - ACL: `Admin\Auth\RoleController`, `Admin\Auth\PermissionController`, `Admin\UserAdminController`
  - Order management: `Admin\OrderController`

### Route Loading Strategy (RouteServiceProvider)

File: `app/Providers/RouteServiceProvider.php`

- Web routes được register qua `->middleware('web', 'currency')`
- Admin routes được group với `prefix('admin', 'currency')` + `middleware('web')`
- API routes (`routes/api.php`) đang bị comment out nên có khả năng không được load

## Layers & Conventions (thực tế trong code)

### Controllers

- Có cả controller “frontend” và “admin” trong `app/Http/Controllers/*`
- Nhiều controller chứa business logic + ghép HTML string (ví dụ: `CheckoutController`)
- Validation: pha trộn giữa FormRequest (ví dụ `CheckoutRequest`) và `request()->validate()` inline
- Authorization: phần lớn dựa vào middleware group (`auth`, `auth:admin`, `checkAdminPermission`); ít thấy policy được gọi trực tiếp trong controller

### Models

- Model phân tách `App\Models\Frontend\*` và `App\Models\Backend\*`, nhưng nhiều bảng trùng (`users`, `shop_orders`, `shop_order_items`) → nguy cơ coupling + duplicate logic
- Nhiều model đặt `protected $guarded = [];` (mass assignment risk) và ít casts/relations chuẩn hoá

### Views (Blade)

- View tách `resources/views/frontend/*` và `resources/views/backend/*`
- Tỉ lệ sử dụng `@php`, `extract()`, và inline `<script>` khá cao → view không “thin”
- Có dấu hiệu duplication partials và logic render rải rác

### Middleware

- Có middleware “currency” set session currency → `ShopCurrency::setCode()`
- CSRF middleware bị tắt trong group `web` (xem `app/Http/Kernel.php`) → ảnh hưởng toàn cục

### Jobs / Events / Listeners

- Có `app/Listeners/StripeEventListener.php` tham chiếu Cashier event (`Laravel\Cashier\Events\WebhookReceived`) nhưng composer.json không thấy `laravel/cashier` → khả năng dead code / missing dependency

## External Integrations (theo dấu vết code)

- reCAPTCHA v3: `config/recaptchav3.php`, được dùng trong `CartController@checkoutConfirm`
- Social login: Socialite (`RegisterAuthController`)
- CKFinder/CKEditor: có dependency `ckfinder/ckfinder-laravel-package` (cần audit kỹ upload/access control)
- VNPay: `PaymentController` dùng `\VNPay::purchase(...)` nhưng chưa thấy routes callback tương ứng
- PayPal: có `app/Services/PayPalService.php` nhưng composer.json không thấy PayPal SDK package tương ứng
- Twilio Verify: có `app/Services/Twilio/Verification.php` nhưng thiếu `app/Verify/*` và Twilio package trong composer → dấu hiệu code không đồng bộ

## Test Surface

- Có thư mục `tests/Feature` và `tests/Unit` (PHPUnit)
- Composer dev có `pestphp/pest` nhưng test hiện hữu là PHPUnit → khả năng “dependency drift”

