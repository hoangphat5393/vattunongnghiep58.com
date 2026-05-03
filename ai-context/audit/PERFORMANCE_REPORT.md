# Performance Report — vattunongnghiep58

## 1) Hotspots (from static scan)

### 1.1 Large legacy asset footprint

- Evidence:
  - `public/assets/` chứa AdminLTE + nhiều plugin (ckeditor/ckfinder/select2/lodash copy…) + nhiều CSS copy
  - Song song với Vite build output `public/build/*`
- Impact:
  - Tăng TTFB/TTI do nhiều request hoặc bundle lớn
  - Cache invalidation khó, dễ tải thừa (unused assets)

### 1.2 Server-side HTML rendering trong AJAX responses

- Evidence:
  - `app/Http/Controllers/CartController.php@updateCarts/removeCart` trả về:
    - `view('frontend.cart.cart-table')->render()`
    - `view('frontend.cart.includes.cart-sidebar')->render()`
- Impact:
  - CPU cost tăng theo traffic; có thể trở thành bottleneck khi cart updates nhiều

### 1.3 ACL permission aggregation có thể expensive

- Evidence:
  - `app/Models/Backend/User.php::allPermissions()`:
    - `$user->roles()->with('permissions')->get()->pluck('permissions')->flatten()`
  - `checkUrlAllowAccess` gọi `allViewPermissions()` và normalize URL
- Impact:
  - Nếu gọi nhiều lần trong 1 request mà không memoize đúng → tốn query + CPU
  - Nếu roles/permissions relations lazy load trong loop → N+1 risk

### 1.4 CheckoutController “fat” làm nặng request checkout

- Evidence:
  - `app/Http/Controllers/CheckoutController.php` build orderDetail HTML, load templates, loop order items, render_price… trong 1 request
- Impact:
  - Latency tăng; khó queue email/background

## 2) Database Performance Risks (heuristic)

> Chưa chạy profiler/DB query log; đây là danh sách “nơi dễ có N+1/slow query”.

- Các màn hình admin index (Product, Order, User, Role/Permission) thường là nguồn N+1 nếu không eager load.
- `roles` relation access trong `isRole()` dùng `$this->roles->pluck(...)`:
  - nếu `$roles` chưa eager load, sẽ trigger query.
- Category tree rendering (backend) có thể tạo recursion + query nếu data không được preload đúng.

## 3) Caching & Config

- `routes/admin.php` có endpoint clear cache (`optimize:clear`) — tốt cho ops, nhưng không nên expose công khai.
- Chưa thấy evidence rõ ràng về:
  - route cache/config cache deployment strategy
  - HTTP caching headers / CDN

## 4) Recommendations (prioritized)

### P0

- Khoá/loại bỏ `/admin/cc` khỏi public access để tránh abuse/DoS.

### P1

- Chuẩn hoá asset pipeline: xác định “source of truth” (Vite) và lập danh sách asset legacy đang được include thực sự.
- Tách send email và các việc “nặng” ra queue (nếu đã có queue driver ổn định).

### P2

- Thêm instrumentation:
  - Laravel Debugbar (đã có dependency) cho môi trường dev
  - Query log sampling ở các page admin index, product detail, checkout confirm
- Audit eager loading cho các list pages và quan hệ nhiều-nhiều (roles/permissions, categories/products).

