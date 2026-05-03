# Business Flow Overview — vattunongnghiep58

> Flow dưới đây được suy ra từ routes + controller + model hiện hữu. Những phần “mơ hồ/thiếu” được đánh dấu rõ để tránh suy diễn.

## 1) Product / Catalog Flow

### Browse & Category

- Entry routes:
  - `GET /product` → `App\Http\Controllers\ProductController@index` (name: `product`)
  - `GET /product/{slug}.html` → `ProductController@index` (name: `product.category`)
  - `GET /product/{slug}-{id}.html` → `ProductController@productDetail` (name: `product.detail`)
- Likely models:
  - `App\Models\Frontend\Product`
  - Category-related models (`App\Models\Frontend\Category`, `App\Models\Backend\ProductCategory`)

### Quick view / Buy now

- Routes:
  - `POST /quick-view` → `ProductController@quickView`
  - `GET /buy-now/{id}` → `ProductController@buyNow`
  - `POST /buy-now` → `ProductController@getBuyNow`

## 2) Cart Flow

- Routes:
  - `GET /cart` → `CartController@cart`
  - `POST /cart/addCart` → `CartController@addCart` (AJAX add)
  - `POST /cart/update` → `CartController@updateCarts`
  - `POST /cart/ajax/remove` → `CartController@removeCart`
  - `GET /cart/remove` → `CartController@removeCarts` (destroy all)
- Cart storage:
  - Dùng package `surfsidemedia/shoppingcart` (`Cart::content()`, `Cart::add()`, `Cart::update()`)

## 3) Checkout / Order Creation Flow (hiện có 2 nhánh)

### A) Nhánh “mới” (Order/OrderItem)

- Routes:
  - `GET /checkout` → `CartController@checkout`
  - `POST /checkout` → `CartController@checkoutConfirm` (dùng `CheckoutRequest`)
  - `GET /checkout-completed` → `CartController@completed`
- Xử lý chính (từ code `CartController@checkoutConfirm`):
  - Validate `order.*` + reCAPTCHA v3 (nếu có secret)
  - Tạo record `shop_orders` qua `App\Models\Frontend\Order::Create($data)`
  - Tạo record `shop_order_items` qua `App\Models\Frontend\OrderItem::Create(...)`
  - `Cart::destroy()` sau khi tạo order
- Mơ hồ/thiếu:
  - Không thấy logic thanh toán “online” trong nhánh này (chủ yếu ghi nhận thông tin và “liên hệ xác nhận”)

### B) Nhánh “legacy” (Addtocard/Addtocard_Detail)

- Route:
  - `POST /checkout-process` → `App\Http\Controllers\CheckoutController@checkoutProcess` (name: `cart_checkout.process`)
- Xử lý chính (từ code `CheckoutController`):
  - Lưu `cart-info` trong session
  - Tạo `Addtocard` và `Addtocard_Detail` (model được refer với namespace `App\Models\Frontend\Addtocard*` nhưng codebase có model ở `App\Models\Addtocard*` → cần xác minh runtime)
  - Sinh `cart_code`/`order_id` (hàm helper `auto_code()`)
  - Có branch `stripe` gọi `StripeController` (controller không thấy trong danh sách controllers) → khả năng incomplete/broken
  - Build email HTML ngay trong controller từ EmailTemplate + string replace
- Mơ hồ/thiếu:
  - Nhánh này dường như thuộc “theme.*” views và dùng helper/templatePath; không đồng nhất với nhánh CartController

## 4) Admin Order Management Flow

- Routes (prefix `admin`):
  - `GET /admin/order` → `Admin\OrderController@index`
  - `GET /admin/order/search` → `Admin\OrderController@searchOrder`
  - `GET /admin/order/{id}` → `Admin\OrderController@orderDetail`
  - `POST /admin/order/update` → `Admin\OrderController@postOrderDetail`
- Status/payment update:
  - `postOrderDetail()` nhận toàn bộ input `request()->all()` và update `cart_status`, `cart_payment`, `shipping_cost`, `admin_note` (được `htmlspecialchars`)
  - Không thấy validation/transition guard rõ ràng (state machine)

## 5) Customer Auth & Account Flow

### Register/Login/Logout

- Routes:
  - `GET/POST /auth/register` → `CustomerController@registerCustomer` / `Auth\RegisterController@register`
  - `GET/POST /auth/login` → `CustomerController@showLoginForm` / `CustomerController@postLogin`
  - `GET /auth/logout` → `CustomerController@logoutCustomer`
- Social login:
  - `GET /social/{provider}` → `RegisterAuthController@redirectToProvider`
  - `GET /callback/{provider}` → `RegisterAuthController@handleProviderCallback`

### Forgot password (multi-step)

- Routes:
  - `/forget/password` step 1–3 (GET/POST) → `Auth\ForgotPasswordController@*`

### Customer dashboard & orders

- Routes (auth required):
  - `/customer` dashboard
  - `/customer/my-orders` và `/customer/my-orders-detail/{id_cart}`

## 6) Inventory Flow (mức độ xác định: thấp)

- Có dấu vết field `stock` (ví dụ comment trong CartController), và module Product trong admin
- Không thấy logic trừ tồn kho khi đặt hàng trong các đoạn đã đọc (cần quét sâu hơn model/controller Product)

## 7) Payment Flow

### VNPay (top-up / nạp tiền)

- Route:
  - `POST /auth/nap-tai-khoan` → `PaymentController@checkout` (name: `customer.vnpay`)
- Missing pieces:
  - `PaymentController` gọi `route('payment.retun')` nhưng không thấy route name đó trong `routes/*`
  - hardcoded email + ip + chưa thấy signature/validation callback trong routes

### PayPal / Stripe

- PayPal: có `App\Services\PayPalService` nhưng composer.json không thấy PayPal SDK; khả năng legacy/dead code
- Stripe: có listener `StripeEventListener` nhưng composer.json không thấy `laravel/cashier`; checkout flow gọi `StripeController` nhưng không thấy file controller

