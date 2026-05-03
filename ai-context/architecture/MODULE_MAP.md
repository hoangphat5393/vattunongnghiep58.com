# Module Map (Functional) — vattunongnghiep58

> Dự án không có thư mục `Modules/` (nwidart/laravel-modules). “Module” dưới đây là phân nhóm theo chức năng dựa trên routes/controllers/views/models hiện có.

## 1) Public Storefront (Frontend)

- Routes: `routes/web.php`
- Controllers:
  - `app/Http/Controllers/PageController.php` (home + page slug)
  - `app/Http/Controllers/ProductController.php` (listing + detail + quick-view/buy-now)
  - `app/Http/Controllers/NewsController.php` (listing + detail)
  - `app/Http/Controllers/SearchController.php` (search)
- Views (examples):
  - `resources/views/frontend/home.blade.php`
  - `resources/views/frontend/product/*`
  - `resources/views/frontend/search.blade.php`
  - `resources/views/frontend/page/*`
- Models:
  - `app/Models/Frontend/Product.php`
  - `app/Models/Frontend/Category.php`
  - `app/Models/Frontend/Page.php`

## 2) Cart & Checkout

- Routes: `routes/web.php` (prefix `cart/*`, `checkout`, `checkout-process`)
- Controllers:
  - `app/Http/Controllers/CartController.php`
  - `app/Http/Controllers/CheckoutController.php` (tồn tại song song, nhiều đoạn dùng model legacy Addtocard)
- Views:
  - `resources/views/frontend/cart/*`
  - `resources/views/frontend/checkout/*`
- Models:
  - `app/Models/Frontend/Order.php`, `app/Models/Frontend/OrderItem.php` (bảng `shop_orders`, `shop_order_items`)
  - Legacy: `app/Models/Addtocard.php`, `app/Models/Addtocard_Detail.php` (có migration rename về order nhưng code còn references)
  - Pricing: `app/Models/ProductPrice.php`
- Requests:
  - `app/Http/Requests/CheckoutRequest.php`

## 3) Customer Account (Frontend Auth & Profile)

- Routes: `routes/web.php` (prefix `auth/*`, `forget/*`, `customer/*`)
- Controllers:
  - `app/Http/Controllers/CustomerController.php`
  - `app/Http/Controllers/Auth/*` (Forgot/Login/Register/Reset/Verification)
  - `app/Http/Controllers/RegisterAuthController.php` (social login)
- Models:
  - `app/Models/Frontend/User.php`

## 4) Admin Panel

- Routes: `routes/admin.php` (prefix `admin`)
- Controllers:
  - Auth/Dashboard:
    - `app/Http/Controllers/Admin/LoginController.php`
    - `app/Http/Controllers/Admin/HomeController.php`
  - CRUD (được generate theo `$admin_module` trong routes/admin.php):
    - `app/Http/Controllers/Admin/ProductController.php`
    - `app/Http/Controllers/Admin/ProductCategoryController.php`
    - `app/Http/Controllers/Admin/PageController.php`
    - `app/Http/Controllers/Admin/PostController.php`
    - `app/Http/Controllers/Admin/AlbumController.php`
    - `app/Http/Controllers/Admin/ContactController.php`
    - `app/Http/Controllers/Admin/EmailTemplateController.php`
  - Orders:
    - `app/Http/Controllers/Admin/OrderController.php`
  - Menu/Theme:
    - `app/Http/Controllers/Admin/MenuController.php`
    - `app/Http/Controllers/Admin/AdminController.php` (theme-option, theme-css)
- Views:
  - `resources/views/backend/*`
  - Ví dụ: `resources/views/backend/product/single.blade.php`
- Models (Backend namespace):
  - `app/Models/Backend/*` (Product, Order, OrderItem, User, Role, Permission, Setting, ShopCurrency, ShopPaymentMethod, ...)

## 5) ACL / Permission System

- Middleware gate: `checkAdminPermission` (registered trong `app/Http/Kernel.php`)
- Controllers:
  - `app/Http/Controllers/Admin/Auth/RoleController.php`
  - `app/Http/Controllers/Admin/Auth/PermissionController.php`
  - `app/Http/Controllers/Admin/UserAdminController.php`
- Models:
  - `app/Models/Backend/Role.php`
  - `app/Models/Backend/Permission.php`
  - `app/Models/Backend/RoleUser.php`
- Policies: `app/Policies/*` (tồn tại nhưng cần audit xem có đang được enforce hay không)

## 6) Settings / Theme / UI Customization

- Admin routes:
  - `admin/theme-option` (GET/POST)
  - `admin/theme-css` (GET/PUT)
- Views:
  - `resources/views/backend/setting/*`
- Models:
  - `app/Models/Backend/Setting.php`, `app/Models/Setting.php`
  - Theme-related: `app/Models/Theme*.php`, `app/Models/Variable_Theme.php`, ...

## 7) API (đang nghi ngờ “không hoạt động”)

- Routes file: `routes/api.php` (ApiController)
- Nhưng `app/Providers/RouteServiceProvider.php` đang comment phần register `routes/api.php`
- Controller:
  - `app/Http/Controllers/ApiController.php`

## 8) Third-party / Integrations

- reCAPTCHA v3: `config/recaptchav3.php`, `CartController`
- Socialite: `RegisterAuthController`
- VNPay: `PaymentController` (cần bổ sung route callback để hoàn chỉnh flow)
- CKFinder: `config/ckfinder.php`
- Image: `intervention/image` (được dùng trong Social login avatar)

