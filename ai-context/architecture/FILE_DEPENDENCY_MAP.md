# File Dependency Map — vattunongnghiep58

> Mục tiêu: nhanh chóng trace “route → controller → model → view” cho các luồng quan trọng. Map này ưu tiên các workflow có rủi ro cao (checkout/auth/admin).

## Web Routes → Controllers

### Storefront core

- `GET /` → `app/Http/Controllers/PageController@index` → `resources/views/frontend/home.blade.php` (suy đoán theo convention)
- `GET /product` → `app/Http/Controllers/ProductController@index` → `resources/views/frontend/product/*`
- `GET /product/{slug}-{id}.html` → `ProductController@productDetail` → `resources/views/frontend/product/single.blade.php`
- `GET /news` → `app/Http/Controllers/NewsController@index` → `resources/views/frontend/news/*` (cần xác minh view path)
- `GET /search` → `app/Http/Controllers/SearchController@index` → `resources/views/frontend/search.blade.php`
- `GET /{slug}` → `app/Http/Controllers/PageController@page` → `resources/views/frontend/page/index.blade.php` (cần xác minh)

### Cart & Checkout (nhánh CartController)

- `GET /cart` → `app/Http/Controllers/CartController@cart`
  - `Cart::content()` (package `surfsidemedia/shoppingcart`)
  - View: `resources/views/frontend/cart/cart.blade.php`
- `POST /cart/addCart` → `CartController@addCart`
  - Models: `app/Models/Frontend/Product.php`, `app/Models/ProductPrice.php`
- `POST /checkout` → `CartController@checkoutConfirm`
  - Request: `app/Http/Requests/CheckoutRequest.php`
  - Models: `app/Models/Frontend/Order.php`, `app/Models/Frontend/OrderItem.php`
  - reCAPTCHA: `config/recaptchav3.php`
- `GET /checkout-completed` → `CartController@completed`
  - Model: `Order::find(session('cart_id'))`
  - View: `resources/views/frontend/checkout/completed.blade.php`

### Checkout (nhánh CheckoutController — legacy)

- `POST /checkout-process` → `app/Http/Controllers/CheckoutController@checkoutProcess`
  - Models referenced: `App\Models\Frontend\Addtocard`, `App\Models\Frontend\Addtocard_Detail` (không thấy trong tree, cần reconcile)
  - Email: `app/Models/Frontend/EmailTemplate.php` + inline HTML build trong controller

### Auth & Social

- `GET /social/{provider}` → `app/Http/Controllers/RegisterAuthController@redirectToProvider`
  - Socialite (`laravel/socialite`)
- `GET /callback/{provider}` → `RegisterAuthController@handleProviderCallback`
  - Model: `app/Models/Frontend/User.php`
  - File IO: `public/images/users/avatar/*`

## Admin Routes → Controllers → Views

Routes file: `routes/admin.php`

### Admin auth & dashboard

- `GET /admin/login` → `app/Http/Controllers/Admin/LoginController@showLoginForm` → `resources/views/backend/auth/login.blade.php`
- `POST /admin/login` → `Admin\LoginController@login`
- `GET /admin` → `app/Http/Controllers/Admin/HomeController@index` → `resources/views/backend/home.blade.php`

### Admin CRUD (dynamic routes)

`routes/admin.php` build resource-ish routes cho: `contact`, `email-template`, `album`, `page`, `post`, `product`

Ví dụ `product`:

- `GET /admin/product` → `app/Http/Controllers/Admin/ProductController@index` → `resources/views/backend/product/index.blade.php`
- `GET /admin/product/create` → `Admin\ProductController@create` → `resources/views/backend/product/single.blade.php`
- `POST /admin/product` → `Admin\ProductController@store`
- `GET /admin/product/{id}` → `Admin\ProductController@show`
- `GET /admin/product/{id}/edit` → `Admin\ProductController@edit` → `resources/views/backend/product/single.blade.php`
- `PUT /admin/product/{id}` → `Admin\ProductController@update`
- `DELETE /admin/product/{id}` → `Admin\ProductController@destroy`

### Admin orders

- `GET /admin/order` → `app/Http/Controllers/Admin/OrderController@index` → `resources/views/backend/orders/index.blade.php`
- `GET /admin/order/{id}` → `Admin\OrderController@orderDetail` → `resources/views/backend/orders/single.blade.php`
- `POST /admin/order/update` → `Admin\OrderController@postOrderDetail`

## Shared Infrastructure Dependencies

- Global helper include: `composer.json` autoload files → `app/Libraries/system.php`
  - Nhiều helper function được gọi xuyên suốt (ví dụ `setting_option()`, `auto_code()`, `msg_move_page()`…)
- Currency middleware:
  - `app/Http/Middleware/Currency.php` → `app/Models/Backend/ShopCurrency.php`
- ACL (permission check):
  - Middleware registered: `checkAdminPermission` trong `app/Http/Kernel.php`
  - Permission data: `app/Models/Backend/Role.php`, `Permission.php`

