# High Risk Workflows — vattunongnghiep58

> “High risk” = có thể gây mất tiền, takeover tài khoản, data leak, hoặc phá vỡ tính toàn vẹn đơn hàng. Các mục dưới đây đều dựa trên dấu vết code thực tế.

## 1) Web POST/PUT/DELETE toàn hệ thống (CSRF Protection)

- Evidence:
  - `app/Http/Kernel.php` có dòng `// \App\Http\Middleware\VerifyCsrfToken::class,` trong group `web` (đang bị comment)
- Impact:
  - Toàn bộ form web có thể bị CSRF (đặc biệt admin actions, checkout, thay đổi profile, xoá/sửa data)
- Priority:
  - P0 (Critical)

## 2) Checkout & Tạo Order (Data Integrity + Abuse)

### Nhánh CartController (Order/OrderItem)

- Evidence:
  - `app/Http/Controllers/CartController.php` tạo `Order::Create($data)` và `OrderItem::Create($cart_item)`
  - `app/Models/Frontend/Order.php` dùng `protected $guarded = [];`
- Risk:
  - Mass assignment (nếu có route/controller khác nhận input chưa validate)
  - Thiếu transaction (order + order_items có thể lệch nếu lỗi giữa chừng)
  - Thiếu throttling cho endpoint nhạy cảm (spam tạo order)

### Nhánh CheckoutController (legacy)

- Evidence:
  - `app/Http/Controllers/CheckoutController.php` chứa logic tạo order, build HTML email, branch `stripe`, gọi `StripeController` (không thấy file)
- Risk:
  - Flow không đồng nhất; có thể tồn tại routes “treo” hoặc logic lỗi runtime
  - HTML build trong controller dễ dẫn tới injection nếu template có placeholder không sanitize đúng

## 3) Admin Order Status Update (Fraud/Workflow Consistency)

- Evidence:
  - `app/Http/Controllers/Admin/OrderController.php@postOrderDetail` dùng `request()->all()` và update `cart_status`, `cart_payment`, `shipping_cost`
- Risk:
  - Thiếu validation rules + thiếu state machine → dễ tạo trạng thái “không hợp lệ”
  - Nếu CSRF bị tắt, endpoint này cực kỳ nguy hiểm

## 4) Social Login Callback (Account Takeover / Data Quality)

- Evidence:
  - `app/Http/Controllers/RegisterAuthController.php`
    - Nếu provider không trả email: tự tạo email `changeTitle(...) . '@gmail.com'`
    - Lưu password = `bcrypt($User_Data->token)`
    - Tạo thư mục avatar `0777`
    - Fetch avatar bằng `file_get_contents()` (không timeout)
- Risk:
  - Password derived from token (không phải password thực) + email giả có thể gây collision/ATO
  - IO permission 0777 tạo surface tấn công trong môi trường shared
  - Không kiểm soát URL fetch (SSRF / DoS) theo nguyên tắc chung

## 5) Payment / Top-up (VNPay)

- Evidence:
  - `app/Http/Controllers/PaymentController.php`
    - Hardcoded `vnp_Bill_Email`, `vnp_IpAddr`
    - `vnp_ReturnUrl` trỏ `route('payment.retun')` nhưng không thấy route name tương ứng trong `routes/*`
- Risk:
  - Flow callback không hoàn chỉnh → thất thoát trạng thái giao dịch
  - Hardcoded data làm sai thông tin hóa đơn/giao dịch khi deploy production
  - Nếu callback không verify signature đầy đủ → rủi ro giả mạo thanh toán

## 6) File Upload / Media (CKFinder)

- Evidence:
  - Dependency: `ckfinder/ckfinder-laravel-package`
  - Backend có upload/galleries partials (nhiều inline script)
- Risk:
  - Upload thường là điểm rủi ro cao (RCE, path traversal, stored XSS) nếu cấu hình permission/validation không chuẩn
  - Cần audit kỹ config + route middleware/ACL

