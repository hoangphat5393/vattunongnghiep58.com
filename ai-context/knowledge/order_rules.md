# Order Rules (Derived) — vattunongnghiep58

## Order Creation (nhánh CartController)

- Trigger:
  - `POST /checkout` → `CartController@checkoutConfirm` (routes/web.php)
- Required fields:
  - `order.name`, `order.email`, `order.phone`, `order.address` (CheckoutRequest)
- Anti-bot:
  - reCAPTCHA v3 được check nếu `RECAPTCHAV3_SECRET` có giá trị (CartController)
  - Score threshold: `<= 0.7` bị reject (CartController)
- Persistence:
  - Tạo `shop_orders` (model: `App\Models\Frontend\Order`, PK: `cart_id`)
  - Tạo `shop_order_items` (model: `App\Models\Frontend\OrderItem`)
  - OrderItem có thể lưu thêm:
    - `product_price_id`, `price_label`, `price_unit` (CartController)
- Cart lifecycle:
  - Sau khi tạo order: `Cart::destroy()`

## Order Status (admin-facing)

> Có 2 sets status label khác nhau trong code, cần chuẩn hoá.

- `Admin\OrderController@orderDetail` defines:
  - `0` Chờ xác nhận
  - `1` Đang xử lý
  - `2` Hoàn thành
  - `3` Đã hủy
- `CartController@orderStatus()` defines:
  - `0` Chờ xác nhận
  - `1` Đã hủy
  - `2` Đã nhận
  - `3` Đang giao hàng
  - `4` Hoàn thành

## Payment Status (admin-facing)

- `Admin\OrderController@orderDetail` defines:
  - `0` Chưa thanh toán
  - `1` Đã thanh toán
- `CartController@orderPayment()` defines:
  - `0` Chưa thanh toán
  - `1` Đã thanh toán

## Workflow Gaps (needs reconciliation)

- Có nhánh legacy order flow trong `CheckoutController@checkoutProcess` (Addtocard/Addtocard_Detail).
- Migrations 2026 có rename “addtocard → orders”, nhưng code còn references legacy models.

