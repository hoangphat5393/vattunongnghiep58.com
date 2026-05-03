# Refactor Priority — vattunongnghiep58

> Priority theo yêu cầu: security → workflow consistency → maintainability → scalability → modernization readiness.

## P0 (must-fix hardening)

1) **CSRF protection**
- Bật lại CSRF middleware trong `app/Http/Kernel.php` (web group) và audit whitelist (nếu có webhook).

2) **Remove/lock debug endpoint**
- Route `/admin/cc` phải nằm sau `auth:admin` + permission hoặc chỉ enable trong local.

3) **Mass assignment controls**
- Các model nhạy cảm (Order, User, PaymentRequest, Product, Role/Permission) phải dùng `$fillable` tối thiểu hoặc `$guarded` hợp lý.

## P1 (workflow consistency)

4) **Unify order/checkout domain**
- Chọn 1 nguồn sự thật:
  - Nhánh `Order/OrderItem` (shop_orders/shop_order_items) hoặc
  - Nhánh legacy `Addtocard*`
- Viết mapping + deprecate nhánh còn lại.

5) **Order state transitions**
- Chuẩn hoá `cart_status`, `cart_payment` thành enum/state machine (ít nhất validate transitions).
- Đồng nhất status labels giữa frontend/customer/admin.

6) **Payment flow completeness**
- VNPay: bổ sung route callback/return + verify + idempotency.
- Stripe/PayPal: quyết định giữ hay loại; làm sạch dead code/deps.

## P2 (maintainability)

7) **Service/Action layer**
- Tách use-case:
  - CreateOrder
  - UpdateOrderStatus
  - SendOrderEmails
  - SocialLoginProvisionUser
- Giảm “fat controller”, tăng testability.

8) **Reduce Blade logic**
- Di chuyển data preparation từ Blade về controller/view composer.
- Chuẩn hoá components/partials.

## P3 (scalability & performance)

9) **Eager loading audit**
- Các màn hình list admin + customer order detail.

10) **Caching strategy**
- Cache config/route/view theo best practice deploy.
- Cache read-heavy data (settings/options/category tree) với invalidation rõ ràng.

## P4 (modernization readiness)

11) **Dependency hygiene**
- Xác minh & loại bỏ package “treo” hoặc thêm dependency thiếu (Cashier/Twilio/PayPal) theo quyết định roadmap.

12) **Frontend modernization**
- Chuẩn hoá build pipeline (Vite) và dọn public assets legacy theo usage thật.

