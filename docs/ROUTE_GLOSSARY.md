# ROUTE_GLOSSARY — Route name ↔ URL ↔ Controller

> **Mục đích:** Tra cứu nhanh route khi sửa Blade, test, hoặc refactor.  
> **Cập nhật:** 2026-07-10 · Laravel **13**
> **Nguồn:** `routes/web.php`, `routes/admin.php`, `RouteServiceProvider` (prefix `/admin`).

**Tài liệu liên quan:** [MASTER.md](../MASTER.md) · [TABLE_GLOSSARY.md](TABLE_GLOSSARY.md) · [CUSTOMER_ACCOUNT_PLAYBOOK.md](CUSTOMER_ACCOUNT_PLAYBOOK.md)

---

## 1. Quy ước đặt tên

| Prefix route name     | URL prefix              | Middleware                                  | Ý nghĩa                                          |
| --------------------- | ----------------------- | ------------------------------------------- | ------------------------------------------------ |
| `customer.*`          | `/auth/*`, `/account/*` | `web`, `auth` (account)                     | Khách hàng — **không** liên quan bảng `customer` |
| `cart.*`, `carts.*`   | `/cart/*`, `/checkout`  | `web`                                       | Giỏ hàng & checkout                              |
| `admin.*`             | `/admin/*`              | `web`, `auth:admin`, `checkAdminPermission` | Quản trị                                         |
| `product.*`, `news.*` | `/product/*`, `/news/*` | `web`                                       | Storefront catalog                               |
| `contact.*`           | `POST /contact*`        | `web`                                       | Liên hệ                                          |

**Admin load:** `RouteServiceProvider` → `prefix('admin')` + `namespace Admin` → URL đầy đủ `/admin/...`.

**Catch-all trang CMS:** `GET /{slug}` · `page` — đăng ký **cuối** `web.php`, không conflict với route cố định phía trên.

**Alias legacy:** Một số route có **tên + URI mới** và giữ alias cũ (cùng controller). JS nên dùng tên mới qua `AppRoutes` / `AdminRoutes`; bookmark/script cũ vẫn hoạt động.

---

## 2. Storefront — trang & catalog

| Route name         | Method | URL                         | Controller@method                 |
| ------------------ | ------ | --------------------------- | --------------------------------- |
| `index`            | GET    | `/`                         | `PageController@index`            |
| `page`             | GET    | `/{slug}`                   | `PageController@page`             |
| `change_language`  | GET    | `/lang/{locale}`            | Closure (`vi`/`en`)               |
| `product`          | GET    | `/product`                  | `ProductController@index`         |
| `product.category` | GET    | `/product/{slug}.html`      | `ProductController@index`         |
| `product.detail`   | GET    | `/product/{slug}-{id}.html` | `ProductController@productDetail` |
| `shop.quickView`   | POST   | `/quick-view`               | `ProductController@quickView`     |
| `shop.buyNow`      | GET    | `/buy-now/{id}`             | `ProductController@buyNow`        |
| `shop.buyNow.post` | POST   | `/buy-now`                  | `ProductController@getBuyNow`     |
| `news`             | GET    | `/news`                     | `PostController@index`            |
| `news.category`    | GET    | `/news/{slug}.html`         | `PostController@index`            |
| `news.detail`      | GET    | `/news/{slug}-{id}.html`    | `PostController@show`             |
| `search`           | GET    | `/search`                   | `SearchController@index`          |

---

## 3. Liên hệ & newsletter

| Route name            | Method | URL                  | Controller@method                                                    |
| --------------------- | ------ | -------------------- | -------------------------------------------------------------------- |
| `contact.submit`      | POST   | `/contact`           | `ContactController@submit`                                           |
| `contact_completed`   | GET    | `/contact-completed` | `ContactController@completed` → `frontend.page.contact-completed`    |
| `cart.contact.submit` | POST   | `/cart/contact`      | `ContactController@submit`                                           |
| `subscription`        | POST   | `/subscription`      | `CustomerController@subscription` → `contacts` (`type=subscription`) |

---

## 4. Xác thực khách (`/auth/*`)

| Route name                        | Method | URL                            | Controller@method                                          | Middleware                   |
| --------------------------------- | ------ | ------------------------------ | ---------------------------------------------------------- | ---------------------------- |
| `customer.login`                  | GET    | `/auth/login`                  | `Auth\CustomerAuthController@showLoginForm`                | `web`                        |
| `customer.login.submit`           | POST   | `/auth/login`                  | `Auth\CustomerAuthController@postLogin`                    | `throttle:auth`              |
| `customer.register`               | GET    | `/auth/register`               | `Auth\CustomerAuthController@registerCustomer`             | `web`                        |
| `customer.register.submit`        | POST   | `/auth/register`               | `Auth\RegisterController@register`                         | `throttle:auth`              |
| `customer.register.success`       | GET    | `/auth/register-success`       | `Auth\CustomerAuthController@createCustomerSuccess`        | `web`                        |
| `customer.logout`                 | POST   | `/auth/logout`                 | `Auth\CustomerAuthController@logoutCustomer`               | `web`                        |
| `customer.password.forgot`        | GET    | `/auth/forgot-password`        | `Auth\ForgotPasswordController@forget`                     | `web`                        |
| `customer.password.forgot.submit` | POST   | `/auth/forgot-password`        | `Auth\ForgotPasswordController@actionForgetPassword`       | `throttle:auth`              |
| `customer.password.verify`        | GET    | `/auth/forgot-password/verify` | `Auth\ForgotPasswordController@forgetPassword_step2`       | `web`                        |
| `customer.password.verify.submit` | POST   | `/auth/forgot-password/verify` | `Auth\ForgotPasswordController@actionForgetPassword_step2` | `web`                        |
| `customer.password.reset`         | GET    | `/auth/forgot-password/reset`  | `Auth\ForgotPasswordController@forgetPassword_step3`       | `web`                        |
| `customer.password.reset.submit`  | POST   | `/auth/forgot-password/reset`  | `Auth\ForgotPasswordController@actionForgetPassword_step3` | `web`                        |
| `customer.vnpay`                  | POST   | `/auth/nap-tai-khoan`          | `PaymentController@checkout`                               | **disabled** (comment route) |
| `login_or_register`               | POST   | `/customer/login-or-register`  | `CustomerController@loginOrregister`                       | `web`                        |
| `auth.social`                     | GET    | `/social/{provider}`           | `RegisterAuthController@redirectToProvider`                | `web`                        |
| `auth.social.callback`            | GET    | `/callback/{provider}`         | `RegisterAuthController@handleProviderCallback`            | `web`                        |

---

## 5. Tài khoản khách (`/account/*`) — middleware `auth`

| Route name                 | Method | URL                         | Controller@method                              |
| -------------------------- | ------ | --------------------------- | ---------------------------------------------- | ----------------------------------- |
| `customer.dashboard`       | GET    | `/account`                  | `Account\AccountController@index`              |
| `customer.profile`         | GET    | `/account/profile`          | `Account\AccountController@profile`            |
| `customer.profile.update`  | POST   | `/account/profile`          | `Account\AccountController@updateProfile`      |
| `customer.orders.index`    | GET    | `/account/orders`           | `Account\AccountController@myOrder`            |
| `customer.orders.show`     | GET    | `/account/orders/{id_cart}` | `Account\AccountController@myOrderDetail`      |
| `customer.password.edit`   | GET    | `/account/password`         | `Account\AccountController@changePassword`     |
| `customer.password.update` | POST   | `/account/password`         | `Account\AccountController@postChangePassword` |
| `customer.reviews`         | GET    | `/account/my-reviews`       | `CustomerController@myReviews`                 | **Redirect** → `customer.dashboard` |
| `customer.post`            | GET    | `/account/quan-ly-tin-dang` | `CustomerController@myPost`                    | **Redirect** → `customer.dashboard` |
| `customer.refused`         | GET    | `/account/refused`          | `CustomerController@refused`                   | **Redirect** → `customer.dashboard` |
| `customer.payment.point`   | GET    | `/account/payment-point`    | `PaymentController@paymentPoint`               | **disabled** (comment route)        |
| `customer.post_reviews`    | POST   | `/account/post-reviews`     | `CustomerController@postReviews`               | **Redirect** → `customer.dashboard` |
| `customer.messages`        | GET    | `/account/messages`         | `CustomerController@messages`                  |

> `{id_cart}` = `shop_orders.cart_id` (không phải `id`).

---

## 6. Redirect URL cũ → mới (301)

| URL cũ                                 | URL mới                        |
| -------------------------------------- | ------------------------------ |
| `/customer`                            | `/account`                     |
| `/customer/thong-tin`                  | `/account/profile`             |
| `/customer/my-orders`                  | `/account/orders`              |
| `/customer/my-orders-detail/{id_cart}` | `/account/orders/{id_cart}`    |
| `/customer/change-pass`                | `/account/password`            |
| `/forget/password`                     | `/auth/forgot-password`        |
| `/forget/password-step-2`              | `/auth/forgot-password/verify` |
| `/forget/password-step-3`              | `/auth/forgot-password/reset`  |

---

## 7. Giỏ hàng & checkout

| Route name                   | Method | URL                                | Controller@method                              |
| ---------------------------- | ------ | ---------------------------------- | ---------------------------------------------- |
| `cart`                       | GET    | `/cart`                            | `CartController@cart`                          |
| `cart.addCart`               | POST   | `/cart/addCart`                    | `CartController@addCart`                       |
| `cart.remove-item`           | POST   | `/cart/remove-item`                | `CartController@removeCart`                    |
| `cart.ajax.remove`           | POST   | `/cart/ajax/remove`                | cùng action — **alias legacy**                 |
| `carts.remove`               | GET    | `/cart/remove`                     | `CartController@removeCarts`                   |
| `carts.update`               | POST   | `/cart/update`                     | `CartController@updateCarts`                   |
| `cart.checkout`              | GET    | `/checkout`                        | `CartController@checkout`                      |
| `cart.checkout.submit`       | POST   | `/checkout`                        | `CartController@checkoutConfirm`               |
| `cart.checkout.confirm`      | POST   | `/cart/checkout-confirm`           | `CartController@checkoutConfirm`               |
| `cart.checkout.checkemail`   | GET    | `/cart/checkout-checkemail`        | `CartController@checkEmail`                    |
| `cart.checkout.checkphone`   | GET    | `/cart/checkout-checkphone`        | `CartController@checkphone`                    |
| `cart.checkout.success`      | GET    | `/cart/checkout/success`           | `CartController@success`                       |
| `checkout_completed`         | GET    | `/checkout-completed`              | `CartController@completed`                     |
| `cart.view`                  | GET    | `/cart/view/{id}`                  | `CartController@view`                          |
| `cart.check_payment`         | GET    | `/cart/check-payment/{cart_id}`    | `CartController@checkPayment`                  |
| `quick_buy.get.confirm`      | GET    | `/cart/quick-buy-checkout-confirm` | `CartController@quickBuyConfirm`               |
| `quick_buy.checkout.confirm` | POST   | `/cart/quick-buy-checkout-confirm` | `CartController@quickBuyConfirm`               |
| `cart_checkout.process`      | POST   | `/checkout-process`                | `CartController@legacyCheckoutProcessRedirect` |

**Legacy / comment (không active):** PayPal, Stripe, `checkout-charge`, `payment-success` — xem comment trong `web.php`.

---

## 8. Admin — đăng nhập & dashboard

| Route name                 | Method | URL                      | Controller@method                    | Middleware                |
| -------------------------- | ------ | ------------------------ | ------------------------------------ | ------------------------- |
| —                          | GET    | `/admin/login`           | `LoginController@showLoginForm`      | `web`                     |
| `admin.login`              | POST   | `/admin/login`           | `LoginController@login`              | `web`                     |
| `admin.logout`             | POST   | `/admin/logout`          | `LoginController@logout`             | `auth:admin`              |
| `admin.dashboard`          | GET    | `/admin`                 | `HomeController@index`               | `auth:admin`              |
| `admin.error`              | GET    | `/admin/404`             | `AdminController@error`              | `web`                     |
| `admin.cache.clear`        | POST   | `/admin/cc`              | `AdminController@clearCache`         | `auth:admin`              |
| `admin.change-password`    | GET    | `/admin/change-password` | `AdminController@changePassword`     | `auth:admin` + permission |
| `admin.postChangePassword` | POST   | `/admin/change-password` | `AdminController@postChangePassword` | `auth:admin` + permission |

---

## 9. Admin — CRUD module (pattern sinh từ vòng lặp)

Các module trong `$admin_module`: `contact`, `email-template`, `album`, `page`, `post`, `product`.

**Pattern route name:** `admin.{module}.{action}`

| Action  | Method | URL pattern                 | Route name suffix |
| ------- | ------ | --------------------------- | ----------------- |
| index   | GET    | `/admin/{module}`           | `.index`          |
| create  | GET    | `/admin/{module}/create`    | `.create`         |
| store   | POST   | `/admin/{module}`           | `.store`          |
| show    | GET    | `/admin/{module}/{id}`      | `.show`           |
| edit    | GET    | `/admin/{module}/{id}/edit` | `.edit`           |
| update  | PUT    | `/admin/{module}/{id}`      | `.update`         |
| destroy | DELETE | `/admin/{module}/{id}`      | `.destroy`        |

**Ví dụ:**

| Route name                  | URL                               |
| --------------------------- | --------------------------------- |
| `admin.product.index`       | `/admin/product`                  |
| `admin.product.create`      | `/admin/product/create`           |
| `admin.post.index`          | `/admin/post`                     |
| `admin.email-template.edit` | `/admin/email-template/{id}/edit` |

**Module có category** (`product`, `post`): thêm prefix `admin.{module}-category.*`

| Route name                     | URL                       |
| ------------------------------ | ------------------------- |
| `admin.product-category.index` | `/admin/product-category` |
| `admin.post-category.index`    | `/admin/post-category`    |

**Import sản phẩm:**

| Route name                     | Method | URL                     |
| ------------------------------ | ------ | ----------------------- |
| `admin.product.import`         | GET    | `/admin/product/import` |
| `admin.product.import_process` | POST   | `/admin/product/import` |

---

## 10. Admin — đơn hàng, user, ACL, menu

| Route name           | Method | URL                     | Controller@method                     |
| -------------------- | ------ | ----------------------- | ------------------------------------- |
| `admin.order.index`  | GET    | `/admin/order`          | `OrderController@index`               |
| `admin.order.search` | GET    | `/admin/order/search`   | `OrderController@searchOrder`         |
| `admin.order.detail` | GET    | `/admin/order/{id}`     | `OrderController@orderDetail`         |
| `admin.order.update` | POST   | `/admin/order/update`   | `OrderController@postOrderDetail`     |
| `admin.user.index`   | GET    | `/admin/user`           | `UserAdminController@index`           |
| `admin.userList`     | GET    | `/admin/user/list`      | `UserAdminController@index` (alias)   |
| `admin.user.create`  | GET    | `/admin/user/create`    | `UserAdminController@create`          |
| `admin.user.store`   | POST   | `/admin/user`           | `UserAdminController@store`           |
| `admin.user.edit`    | GET    | `/admin/user/{id}/edit` | `UserAdminController@edit`            |
| `admin.user.update`  | PUT    | `/admin/user/{id}`      | `UserAdminController@update`          |
| `admin.user.destroy` | DELETE | `/admin/user/{id}`      | `UserAdminController@deleteUserAdmin` |
| `admin.role.*`       | REST   | `/admin/role/*`         | `Auth\RoleController`                 |
| `admin.permission.*` | REST   | `/admin/permission/*`   | `Auth\PermissionController`           |

---

## 11. Admin — album item, AJAX, cấu hình

| Route name                            | Method   | URL                                    | Controller@method                    | Ghi chú                               |
| ------------------------------------- | -------- | -------------------------------------- | ------------------------------------ | ------------------------------------- |
| `admin.album.library`                 | GET      | `/admin/album-library`                 | `AlbumController@library`            | Thư viện album                        |
| `admin.album.albumItem.create`        | GET/POST | `/admin/album/{id}/album_item/*`       | `AlbumItemController`                | Item trong album                      |
| `admin.albumItem.*`                   | REST     | `/admin/album_item/{id}`               | `AlbumItemController`                | CRUD item                             |
| `admin.albumItem.update_sort`         | POST     | `/admin/album_item/update-sort`        | `AlbumItemController@ajaxUpdateSort` | **Tên mới**                           |
| `admin.albumItem.ajax_update_sort`    | POST     | `/admin/album_item/ajax_update_sort`   | cùng action                          | Alias legacy                          |
| `admin.bulk.delete`                   | POST     | `/admin/bulk-delete`                   | `AjaxController@ajax_delete`         | **Tên mới**                           |
| `admin.ajax_delete`                   | POST     | `/admin/delete-id`                     | cùng action                          | Alias legacy                          |
| `admin.bulk.replicate`                | POST     | `/admin/bulk-replicate`                | `AjaxController@ajax_replicate`      | **Tên mới**                           |
| `admin.ajax_replicate`                | POST     | `/admin/replicate-id`                  | cùng action                          | Alias legacy                          |
| `admin.quick-change`                  | POST     | `/admin/quick-change`                  | `AjaxController@ajax_quickchange`    | Toggle field (whitelist model/column) |
| `admin.theme-option`                  | GET/POST | `/admin/theme-option`                  | `AdminController`                    | Cấu hình theme                        |
| `admin.theme-option.update_sort`      | POST     | `/admin/theme-option/update-sort`      | `AdminController@ajaxUpdateSort`     | **Tên mới**                           |
| `admin.theme-option.ajax_update_sort` | POST     | `/admin/theme-option/ajax_update_sort` | cùng action                          | Alias legacy                          |
| `admin.admin-menu.*`                  | —        | `/admin/admin-menu/*`                  | `AdminMenuController`                | Sidebar menu admin                    |
| `admin.css.get`, `admin.css.update`   | GET/PUT  | `/admin/theme-css`                     | `AdminController`                    | Custom CSS                            |
| `admin.menu.*`                        | —        | `/admin/menu/*`                        | `MenuController`                     | Menu frontend                         |

> **Đã gỡ (không còn route):** `/admin/ajax/process_theme_fast`, `process_new_item`, `process_propose`, `process_store_status`, … — quick-edit bảng `theme` cũ; thay bằng `admin.quick-change` trên model `Product`.

---

## 12. API & route không dùng

| File                                    | Trạng thái                                |
| --------------------------------------- | ----------------------------------------- |
| `routes/api.php`                        | Stub trống — không có API public          |
| PayPal / Stripe / VNPay trong `web.php` | Comment — checkout chính offline/thủ công |

---

## 13. Middleware theo nhóm

| Nhóm URL                             | Middleware stack                              |
| ------------------------------------ | --------------------------------------------- |
| Storefront                           | `web` (+ `currency` global storefront)        |
| `/account/*`                         | `web` + `auth`                                |
| `/auth/*` POST login/register/forgot | + `throttle:auth`                             |
| `/admin/*` (protected)               | `web` + `auth:admin` + `checkAdminPermission` |

---

## 14. JS — map route cho axios

Blade inject object global để JS không hardcode URL:

| Object               | File Blade                                               | Dùng trong                                     |
| -------------------- | -------------------------------------------------------- | ---------------------------------------------- |
| `window.AppRoutes`   | `resources/views/frontend/layouts/app-routes.blade.php`  | `custom.js`, `auth-forms.js`, `cart.blade.php` |
| `window.AdminRoutes` | `resources/views/backend/layouts/admin-routes.blade.php` | `js_admin.js`, `menu.js`                       |

**Storefront (`AppRoutes`):** `cartRemove` → `cart.remove-item`, `cartRemoveLegacy` → `cart.ajax.remove`, …

**Admin (`AdminRoutes`):** `bulkDelete`, `bulkReplicate`, `quickChange`, `legacyBulkDelete`, `legacyBulkReplicate`, …

HTTP wrapper: `resources/js/http.js` (export qua `window.http` trong Vite bundle).

---

## 15. Lệnh tra cứu nhanh

```bash
php artisan route:list --except-vendor
php artisan route:list --name=customer
php artisan route:list --name=admin.product
php artisan route:list --path=checkout
```

---

## 16. Lịch sử

| Ngày       | Thay đổi                                                                               |
| ---------- | -------------------------------------------------------------------------------------- |
| 2026-07-10 | Account legacy routes → redirect dashboard; subscription → `contacts`                  |
| 2026-07-10 | Route rename + alias; §14 JS `AppRoutes`/`AdminRoutes`; gỡ endpoint `theme` quick-edit |
| 2026-07-10 | Tạo ROUTE_GLOSSARY.md                                                                  |
