# Route Inventory — vattunongnghiep58

Nguồn dữ liệu route “thực tế runtime” (bao gồm routes generate động trong `routes/admin.php`) được export từ:

- `php artisan route:list --json --except-vendor`
- File JSON output: `ai-context/rag/routes.json`

## Tổng quan

- Total routes (non-vendor): **182**
- Prefix breakdown:
  - `admin`: 118
  - `cart`: 14
  - `customer`: 14
  - `auth`: 7
  - `forget`: 6
  - `product`: 3
  - `news`: 3
  - `buy-now`: 2
  - `checkout`: 2
  - Các prefix khác: `subscription`, `search`, `quick-view`, `social`, `callback`, `lang`, `contact*`, `/`, `{slug}`, `checkout-process`

## Routes không có name (unnamed)

> Unnamed routes làm giảm khả năng generate URL bằng `route()` và khó audit/trace.

- `GET|HEAD admin/cc` → `Closure` (đặc biệt rủi ro vì là maintenance endpoint)
- `GET|HEAD admin/login` → `App\Http\Controllers\Admin\LoginController@showLoginForm`

## Closure routes

- `GET|HEAD admin/cc` → closure (source: `routes/admin.php:22`)
- `GET|HEAD lang/{locale}` → closure (source: `routes/web.php:20`) (name: `change_language`)

## High-risk route highlights (security/workflow)

> Đây là shortlist; xem chi tiết phân tích tại `ai-context/audit/SECURITY_REPORT.md` và `ai-context/architecture/HIGH_RISK_WORKFLOW.md`.

- Admin maintenance:
  - `GET admin/cc` (closure) chạy `optimize:clear` và hiện không nằm dưới `auth:admin` trong `routes/admin.php`
- Checkout/order create:
  - `POST checkout` → `CartController@checkoutConfirm`
  - `POST checkout-process` → `CheckoutController@checkoutProcess` (legacy branch)
- Admin order update:
  - `POST admin/order/update` → `Admin\OrderController@postOrderDetail`
- Social auth:
  - `GET social/{provider}` và `GET callback/{provider}` → `RegisterAuthController`

## Cách dùng cho RAG/AI

- Tra cứu feature theo route name: filter trên `name`.
- Trace flow: `uri + method → action → controller file → model/view`.
- Audit middleware coverage: field `middleware` trong `ai-context/rag/routes.json`.

