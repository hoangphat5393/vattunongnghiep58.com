# Technical Debt Report — vattunongnghiep58

## 1) Structural Debt (High Impact)

### 1.1 Song song 2 hệ order/checkout

- Evidence:
  - `CartController` tạo `Frontend\Order` + `Frontend\OrderItem`
  - `CheckoutController` vẫn dùng `Addtocard`/`Addtocard_Detail` + nhiều “theme.*” view path
- Debt:
  - Workflow khó dự đoán; bug phát sinh khi sửa 1 nhánh mà quên nhánh còn lại
  - Migrations cho thấy đã rename/refactor nhưng code chưa đồng bộ

### 1.2 Dual model namespace (Frontend vs Backend) cho cùng 1 table

- Evidence:
  - `config/auth.php`: web dùng `App\Models\Frontend\User`, admin dùng `App\Models\Backend\User` (cùng table `users`)
  - Có `Backend\Order` và `Frontend\Order` cùng map `shop_orders`
- Debt:
  - Drift logic + duplication; khó enforce invariants (fillable/casts/relations)

### 1.3 Helper-driven architecture (hidden coupling)

- Evidence:
  - `composer.json` autoload `app/Libraries/system.php`
  - Nhiều chỗ dùng helper `setting_option()`, `auto_code()`, `msg_move_page()`, ...
- Debt:
  - Hidden dependencies; khó unit test; khó refactor vì logic rải rác ngoài class

## 2) Code Quality Debt

### 2.1 Inconsistent conventions

- Routes dùng mixed style (FQCN vs string controller).
- Eloquent create dùng `Create` (capital C) ở nhiều chỗ (ví dụ `Order::Create(...)`) → không theo convention Laravel (dù vẫn chạy do PHP case-insensitive).

### 2.2 Business logic ở Controller/Blade

- Evidence:
  - `CheckoutController` build HTML email trong controller
  - `resources/views/backend/product/single.blade.php` xử lý serialize/json + `extract()`
- Debt:
  - Khó test, khó maintain, khó tái sử dụng, khó scale team

### 2.3 Dead code / dependency drift

- Evidence:
  - `StripeEventListener` tham chiếu Cashier nhưng composer không có Cashier
  - Twilio Verification thiếu `app/Verify/*`
  - PayPalService tham chiếu PayPal SDK nhưng composer không thấy PayPal SDK
- Debt:
  - Feature “ảo” + rủi ro runtime errors khi code path được gọi

## 3) Process/Operational Debt

### 3.1 Debug/maintenance endpoints trong routes

- Evidence:
  - `routes/admin.php` có `/admin/cc` chạy `optimize:clear`
- Debt:
  - Dễ bị lạm dụng; ảnh hưởng production stability

### 3.2 Assets legacy phình to

- Evidence:
  - `public/assets/` chứa nhiều plugin + “admin - Copy” + css copy
- Debt:
  - Bundle size lớn, khó quản lý version + security patches

## 4) Modernization Readiness Scorecard (qualitative)

- Security posture: thấp (CSRF off, guarded empty, maintenance route public)
- Workflow consistency: trung bình-thấp (2 checkout flows)
- Maintainability: thấp-trung bình (logic rải rác + legacy helpers)
- Scalability: trung bình-thấp (khó scale team, khó optimize queries)
- Testability: trung bình (có tests, nhưng logic core chưa dễ test)

## 5) Debt Paydown Themes (đưa vào roadmap)

- Consolidate order model/flow
- Normalize auth + roles/permissions boundaries
- Remove/lock debug endpoints
- Reduce Blade/controller logic; move to services/actions/view composers
- Asset cleanup + unify frontend build pipeline

