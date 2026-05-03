# Business Rules (Derived) — vattunongnghiep58

> Các rule dưới đây được suy ra từ routes/controllers/models hiện có. Khi thiếu evidence rõ ràng sẽ được đánh dấu “CẦN XÁC MINH”.

## Global

- Multi-language: hỗ trợ `en` và `vi` qua route `GET lang/{locale}` (routes/web.php).
- Currency: hệ thống có khái niệm currency code trong session (`app/Http/Middleware/Currency.php` + `setting_option('currency')`).

## Content (Pages/Posts/News)

- Có hệ page theo slug:
  - `GET /{slug}` → PageController@page (routes/web.php).
- Có module “news”:
  - `GET /news` và detail `/news/{slug}-{id}.html` (routes/web.php).
- Admin có CRUD cho page/post (routes/admin.php dynamic CRUD list).

## Catalog (Products)

- Product list:
  - `/product` và `/product/{slug}.html`
- Product detail:
  - `/product/{slug}-{id}.html`
- “Buy now” và “Quick view” tồn tại (routes/web.php).
- Price variants:
  - Có model `App\Models\ProductPrice` và field `product_price_id` được lưu vào order_item (CartController@checkoutConfirm).

## Customer Account

- Có customer dashboard + profile + my-orders + reviews.
- Có social login (provider param) và forgot password theo 3 bước.

## Payments / Wallet (CẦN XÁC MINH)

- Có khái niệm “nạp tiền” (route `/auth/nap-tai-khoan` → PaymentController@checkout).
- PaymentController có update `wallet` trên user sau khi VNPay success.
- Missing routes callback trong routes hiện tại → flow có thể chưa hoạt động.

