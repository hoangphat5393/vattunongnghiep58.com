# Backend Audit Report — vattunongnghiep58

## 1) Inventory of Backend Code

### Routes

- `routes/web.php` — storefront + customer + cart/checkout
- `routes/admin.php` — admin panel + ACL + order management + dynamic CRUD route generator
- `routes/api.php` — API endpoints (nhưng có khả năng không được register)
- `app/Providers/RouteServiceProvider.php` — route registration

### Controllers (high level)

- Storefront: `PageController`, `ProductController`, `NewsController`, `SearchController`
- Cart/Checkout: `CartController`, `CheckoutController`
- Auth: `Auth/*`, `CustomerController`, `RegisterAuthController`
- Admin: `Admin/*` (CRUD modules + orders + ACL + settings/menu)

### Models

- Dual namespace:
  - `App\Models\Frontend\*`
  - `App\Models\Backend\*`
- Legacy/extra models ở `App\Models\*` (Theme, Addtocard, Discount, Wishlist, ...)
- Quan sát: nhiều model dùng `protected $guarded = [];` (mass assignment risk, khó kiểm soát input)

### Migrations

- Base schema: `database/migrations/2020_01_01_000000_create_base_tables.php`
- Nhiều migration “refactor/rename/drop legacy tables” trong 2026 (ví dụ rename addtocard → orders)

### Services / Integrations

- `app/Services/PayPalService.php` (PayPal REST SDK style)
- `app/Services/Twilio/Verification.php`
- `config/recaptchav3.php`
- CKFinder: `config/ckfinder.php`

## 2) Architecture & Maintainability Observations

### 2.1 Route organization & consistency

- `routes/web.php` pha trộn 2 style gọi controller:
  - FQCN string: `'\App\Http\Controllers\PageController@index'`
  - Legacy string controller: `'CustomerController@registerCustomer'` (phụ thuộc `$namespace` trong RouteServiceProvider)
- `app/Providers/RouteServiceProvider.php`:
  - `routes/api.php` đang bị comment out → API có thể không hoạt động
  - `Route::prefix('admin', 'currency')` là dấu hiệu bất thường (prefix normally chỉ nhận 1 argument)

### 2.2 Controller fatness & business logic placement

- `app/Http/Controllers/CheckoutController.php` có dấu hiệu “fat controller”:
  - Xử lý order creation + email templating + HTML building + branching payment trong 1 method
  - Dùng nhiều helper (`setting_option`, `auto_code`, `render_price`) và direct model calls
- `app/Http/Controllers/Admin/OrderController.php`:
  - Update order status bằng `request()->all()` (thiếu validation; state machine)
- `app/Http/Controllers/RegisterAuthController.php`:
  - IO + image processing + user provisioning trong controller method

### 2.3 Model design issues

- `protected $guarded = []` xuất hiện rộng:
  - làm tăng rủi ro mass assignment
  - làm mờ boundary giữa input validation và persistence
- Trùng lặp model “Frontend vs Backend” nhưng dùng cùng table:
  - Ví dụ `users` được map bởi cả `App\Models\Frontend\User` và `App\Models\Backend\User`
  - Điều này tạo coupling và dễ drift (logic thay đổi ở 1 nơi không phản ánh nơi còn lại)

### 2.4 Missing/weak service layer

- Nhiều nghiệp vụ (checkout, email building, social auth) nằm trực tiếp trong controller
- Không thấy patterns “Action/Service per use-case” được áp dụng nhất quán

## 3) Workflow Consistency Findings

### 3.1 Hai hệ order song song (risk)

- Nhánh mới: `Frontend\Order` + `Frontend\OrderItem` (được tạo trong `CartController@checkoutConfirm`)
- Nhánh legacy: `Addtocard` + `Addtocard_Detail` (được refer trong `CheckoutController@checkoutProcess`)
- Đây là dấu hiệu của migration/modernization chưa hoàn tất:
  - routes vẫn trỏ vào nhánh legacy
  - models referenced có thể không tồn tại đúng namespace

### 3.2 Payment flow chưa hoàn chỉnh

- `PaymentController` gọi `route('payment.retun')` nhưng không có route tương ứng trong `routes/*`
- Dễ dẫn tới giao dịch không finalize / không reconcile

## 4) Data Integrity & Transactionality

- Order creation trong `CartController@checkoutConfirm`:
  - tạo order rồi loop tạo items
  - không thấy transaction wrapper → nếu lỗi giữa chừng sẽ tạo order “mồ côi” hoặc thiếu items
- Admin update status:
  - không validate shipping_cost/cart_payment/cart_status type range đầy đủ

## 5) Security Findings (backend-specific)

- CSRF middleware disabled trong web group (`app/Http/Kernel.php`) → tác động toàn bộ backend web routes
- Unauthenticated cache clear route: `/admin/cc` (routes/admin.php)
- Social login provisioning yếu (xem SECURITY_REPORT)
- Nhiều endpoint dùng `request()->all()` không validate (Admin\OrderController, CheckoutController)

## 6) Performance Findings (backend-specific)

- Nhiều nơi dùng `$model->relation` có thể trigger lazy loading trong loop (cần quét sâu để xác định N+1 cụ thể)
- `CartController@updateCarts` tính subtotal bằng loop trên `Cart::content()` (OK), nhưng nhiều endpoint render partial view HTML trong JSON response (tăng cost CPU khi traffic lớn)

## 7) Actionable Recommendations (Roadmap-ready)

> Không triển khai ngay; đây là input cho `modernization/*`.

- Chuẩn hoá 1 order flow duy nhất (deprecate legacy `Addtocard*` hoặc viết adapter).
- Tách use-cases: `CreateOrder`, `SendOrderEmails`, `UpdateOrderStatus` thành action/service.
- Thêm transaction + idempotency cho order creation/payment callbacks.
- Chuẩn hoá routing style (FQCN) và bật lại API route registration nếu cần.

