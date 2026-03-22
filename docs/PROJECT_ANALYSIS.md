# Phân tích dự án — Vật Tư Nông Nghiệp 58

Tài liệu này tổng hợp kiến trúc, cơ sở dữ liệu, luồng dữ liệu, vấn đề và kế hoạch cải thiện.  
**Phạm vi:** mã nguồn ứng dụng (`app/`, `routes/`, `config/`, `resources/`, `database/migrations/`), không liệt kê từng file trong `node_modules/` hay `public/assets/plugin/`.

---

## 1. Tổng quan dự án (Project Overview)

- **Mục đích:** Website thương mại điện tử / giới thiệu sản phẩm nông nghiệp (thương hiệu giao diện: _Vật Tư 58_, _Nông Nghiệp Sạch_), hỗ trợ **danh mục sản phẩm**, **giỏ hàng**, **đặt hàng / thanh toán**, **tin bài / trang tĩnh**, **tìm kiếm**, **liên hệ**, và **khu vực quản trị** (CMS + đơn hàng + phân quyền).
- **Công nghệ lõi:**
    - **Backend:** PHP **8.4+**, **Laravel 12**, Eloquent ORM.
    - **Auth:** Guard `web` (khách hàng) và guard `admin` (quản trị — model dùng bảng `users`).
    - **Frontend:** Blade + **Vite 7**, **Tailwind CSS 4**, jQuery / Axios / Swiper / AOS (bundle `resources/js/app.js`).
    - **Thư viện đáng chú ý:** `surfsidemedia/shoppingcart`, `gornymedia/laravel-shortcodes`, `ckfinder/ckfinder-laravel-package`, `diglactic/laravel-breadcrumbs`, `intervention/image`, Socialite, reCAPTCHA v3, Mailgun.
- **Đặc điểm:** Codebase có dấu vết **refactor** (gộp bài viết vào `pages` với cột `type`, bỏ một số bảng/category cũ), và phần **API/controller cũ** (`ApiController`) còn tham chiếu schema **không thuộc** shop hiện tại.

---

## 2. Tóm tắt kiến trúc (Architecture Summary)

### 2.1. Mô hình

- **Chủ đạo: MVC Laravel** — Controller truy cập trực tiếp **Model** (Frontend/Backend); **không có tầng Repository**; **Service layer** rất mỏng (`app/Services/PayPalService.php`, `app/Services/Twilio/Verification.php`).
- **Phân tách model:**
    - `App\Models\Frontend\*` — dữ liệu storefront (sản phẩm, danh mục, giỏ, trang…).
    - `App\Models\Backend\*` — cấu hình, user quản trị, menu admin, v.v.
- **Traits dùng chung:** ví dụ `App\Traits\FrontendDataTransform` (chuẩn hóa dữ liệu trang chủ), `App\Traits\LocalizeController`, `Filterable`.
- **Helpers toàn cục:** `app/Libraries/system.php` (autoload qua `composer.json`) — hằng số, `setting_option()`, cache theme, v.v.

### 2.2. Ranh giới module (module boundaries)

| Module         | Trách nhiệm chính                                                                                                                                            |
| -------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Storefront** | `routes/web.php` → `PageController`, `ProductController`, `NewsController`, `CartController`, `CustomerController`, `ContactController`, `SearchController`… |
| **Admin**      | `routes/admin.php` (prefix URL `/admin`) → CRUD page/post/product/contact/email-template/album, menu, theme-option, user/role/permission, order…             |
| **API (file)** | `routes/api.php` **được định nghĩa nhưng không được đăng ký** trong `RouteServiceProvider` — thực tế **không phục vụ request** qua `/api/*` (xem mục 6).     |

### 2.3. Jobs / Queue

- Thư mục **`app/Jobs`:** không có class Job trong scan hiện tại.
- Database có bảng `jobs`, `failed_jobs` (chuẩn Laravel) — có thể dùng queue nhưng **không thấy job nghiệp vụ tùy chỉnh** trong repo.

### 2.4. Middleware quan trọng

- `web`: session, cookie, locale… Trong `app/Http/Kernel.php`, **`VerifyCsrfToken` bị comment** — rủi ro bảo mật lớn (mục 6).
- `currency`: áp vào nhóm route public trong `RouteServiceProvider`.
- `auth:admin` + `checkAdminPermission`: phân quyền menu/URI cho admin.

---

## 3. Cấu trúc cơ sở dữ liệu (Database Structure)

### 3.1. Môi trường đã introspect (MySQL)

- Kết nối ứng dụng trỏ tới database **`vattunnongnghiep58`** (MySQL 8.x).
- **Lư ý:** Server MySQL có thể chứa **nhiều schema** (ví dụ các bảng thuộc project khác); số bảng “tổng” trên server không đồng nghĩa chỉ có bảng của shop này.

### 3.2. Bảng thuộc ứng dụng (schema `vattunnongnghiep58`)

Danh sách bảng thực tế (rút gọn theo output `php artisan db:show`):

`addtocard`, `addtocard_detail`, `admin_menus`, `admins`, `album_items`, `albums`, `cache`, `cache_locks`, `categories`, `contacts`, `countries`, `customer`, `customer_forget_pass_otp`, `email_templates`, `failed_jobs`, `import_log`, `jobs`, `media_files`, `menu_items`, `menus`, `migrations`, `pages`, `password_reset_tokens`, `password_resets`, `payment_request`, `payments`, `permission_role`, `permissions`, `product_categories`, `products`, `role_user`, `roles`, `sessions`, `settings`, `settings_cost`, `shipping_order`, `shop_currencies`, `shop_order_payment_status`, `shop_order_status`, `shop_payment_method`, `user_password_auto`, `users`.

_(Các bảng legacy `admin_permission` / `admin_role_permission` đã được loại bỏ sau khi dữ liệu chuyển sang `permissions` + `permission_role` — xem `docs/GHI_CHU_LOAI_BO_BANG_ADMIN_PERMISSION.md`.)_

### 3.3. Quan hệ logic (ORM / nghiệp vụ)

- **Sản phẩm — danh mục (n-n):** bảng trung gian **`product_categories`** (`product_id`, `category_id`) — có **index FK** trên `product_id` và `category_id`.
- **Trang / bài viết:** bảng **`pages`**, phân biệt **`type`** (ví dụ `page` / `post`) — thay cho mô hình `posts` cũ (đã migrate/loại bỏ qua migrations).
- **Đơn hàng:** **`addtocard`** (header đơn) + **`addtocard_detail`** (dòng chi tiết) — tên bảng theo legacy; model `Frontend\AddToCard` / `AddToCardDetail`.
- **ACL:** `users` ↔ `roles` qua **`role_user`**; `roles` ↔ `permissions` qua **`permission_role`**. (Schema cũ `admin_permission` / `admin_role_permission` không còn dùng runtime.)
- **Bảng `admins`:** tồn tại trong schema nhưng **guard admin** cấu hình dùng model `Backend\User` với **`$table = 'users'`** — cần **một nguồn sự thật** (tránh nhầm lẫn khi bảo trì).

### 3.4. Ví dụ chi tiết bảng (từ `php artisan db:table`)

**`products` (35 cột, chỉ có PK `id`):**

- Khóa chính: `id`.
- Đa ngôn ngữ: `name` / `name_en`, `description` / `description_en`, `content` / `content_en`.
- Thương mại: `price` (varchar), `price_type`, `promotion`, `stock`, `sku`, `currency`, khuyến mãi theo thời gian `date_start` / `date_end`.
- SEO: `seo_title`, `seo_keyword`, `seo_description`.
- **Index:** hiện chỉ **`PRIMARY (id)`** — truy vấn theo **`slug`**, **`status`**, **`user_id`** có thể **chậm** khi dữ liệu lớn (gợi ý index ở mục 7).

**`pages` (28 cột, chỉ PK `id`):**

- `type` (vd: `page` / `post`), `slug`, nhiều khối nội dung `content`, `content2`…, SEO, `template`, `parent`, `status`.
- **Index:** chỉ **`PRIMARY (id)`** — tra cứu **`slug`** + **`type`** nên được đo và cân nhắc **composite index**.

**`categories`:**

- Cây danh mục: `parent`, `sort`, `hot`, `status`; SEO; chỉ PK `id` (tương tự cần index theo `slug`/`parent` nếu query nhiều).

**`addtocard` (đơn):**

- Thông tin khách, địa chỉ, `total_price`, `status`, vận chuyển, `user_id`, thanh toán; chỉ PK `id` — thường filter theo `user_id`, `status`, `created_at` → xem xét index sau khi đo.

### 3.5. So sánh schema vs Model Laravel

- **`App\Models\Frontend\Page`:** khớp hướng dùng `type`, scope `posts` / `pages`; accessor đa ngôn ngữ; **`getCategoriesAttribute`** trả collection rỗng (bảng category-page đã bỏ) — **đồng bộ với refactor DB**.
- **`App\Models\Frontend\Product`:** khớp bảng `products`; quan hệ category qua `product_categories` (cần đối chiếu method quan hệ trong model).
- **`App\Models\Backend\User`:** `$table = 'users'` — **khớp** cấu hình `auth.php` provider `admins` → model này; bảng `admins` có thể là **di sản** hoặc dùng mục đích khác — nên **tài liệu hóa** hoặc dọn dẹp sau audit.
- **`ApiController`:** tham chiếu model/bảng kiểu `theme`, `category_theme` — **không khớp** schema shop hiện tại → coi là **dead code** hoặc project cũ.

### 3.6. Rủi ro truy vấn (unsafe / nặng)

- **`whereRaw` / `orderByRaw`** với chuỗi thời gian hoặc tên bảng — một số chỗ ghép chuỗi (ví dụ logic OTP/giới hạn 300 giây trong `CustomerController`, `ForgotPasswordController`, `HomeController`) — cần rà soát **SQL injection** nếu có phần input người dùng lọt vào raw (hiện tại chủ yếu là thời gian server).
- **`Admin\AjaxController`:** dùng `DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;")` — **nguy hiểm** nếu `$table` có thể bị thao túng (phụ thuộc validation đầu vào và quyền admin).

---

## 4. Các module chính (Key Modules)

- **Trang chủ & trang tĩnh:** `PageController@index`, `PageController@page` — load `pages` (`slug` home, hoặc slug động), shortcode, SEO.
- **Sản phẩm:** `ProductController` — danh sách, chi tiết URL dạng `product/{slug}-{id}.html`, quick view, mua nhanh.
- **Tin tức:** `NewsController` — tương tự pattern slug + id, nội dung từ `pages` type `post`.
- **Giỏ hàng & checkout:** `CartController` — session cart (package), đồng bộ/ghi `addtocard` / chi tiết, xác nhận email/phone, redirect success.
- **Khách hàng:** `CustomerController` — đăng ký/đăng nhập, profile, đơn hàng, đánh giá (route có), social login (`RegisterAuthController`).
- **Liên hệ / tìm kiếm:** `ContactController`, `SearchController`.
- **Admin:** resource-style loop trong `admin.php` cho `contact`, `email-template`, `album`, `page`, `post`, `product` + `product-category`; riêng menu, theme-option, album-item, order, user/role/permission.
- **Tiện ích:** `SitemapController`, `ImageController`, export (thư mục `Exports/`).

---

## 5. Luồng dữ liệu (Data Flow)

### 5.1. Request chuẩn (storefront)

```
HTTP Request
  → Middleware `web` (+ `currency`)
  → Route (`routes/web.php`)
  → Controller
  → Model (Eloquent) / Cart facade / helper `setting_option()`
  → MySQL
  → Blade view + Vite assets
  → HTTP Response
```

**Ví dụ:** Trang chủ — `GET /` → `PageController@index` → `Category`, `Product`, `Page::posts()` → transform `FrontendDataTransform` → view `frontend/home`.

### 5.2. Request admin

```
HTTP Request
  → `web` → `auth:admin` → (một phần route) `checkAdminPermission`
  → Controller trong `App\Http\Controllers\Admin\`
  → Model Backend + permission tables
  → View backend
```

### 5.3. API (lý thuyết file vs thực tế)

- File `routes/api.php` khai báo endpoint như `/slider`, `/products`… nhưng **`RouteServiceProvider` không `->group(api.php)`** — luồng **Route → ApiController** **không tồn tại** trên ứng dụng hiện tại trừ khi có chỗ đăng ký khác (không thấy).

### 5.4. Logic trùng / dùng chung

- **Chuẩn hóa dữ liệu UI:** `FrontendDataTransform` (tránh lặp map trong từng view).
- **Cấu hình theme:** `setting_option()` + cache vĩnh viễn — **một nơi** nhưng cần **chiến lược xóa cache** khi đổi setting.
- **Trùng có thể có:** nhiều controller **legacy** (`HomeController` cấp root với query `theme`/`post`) song song với flow mới — dễ **trùng nghiệp vụ** nếu route còn trỏ tới.

---

## 6. Vấn đề đã phát hiện (Issues Found)

### 6.1. Mã nguồn & kỹ thuật

| Vấn đề                             | Chi tiết / ví dụ trong project                                                                                                      |
| ---------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- |
| **CSRF tắt toàn cục**              | `app/Http/Kernel.php`: middleware `VerifyCsrfToken` bị comment trong nhóm `web`.                                                    |
| **Endpoint admin nguy hiểm**       | `routes/admin.php`: route `GET admin/cc` gọi `optimize:clear` **không** nằm trong `auth:admin` — ai biết URL cũng có thể kích hoạt. |
| **API không hoạt động**            | `RouteServiceProvider`: phần load `routes/api.php` bị comment — `routes/api.php` **không có hiệu lực**.                             |
| **Controller/API legacy**          | `ApiController` dùng model/bảng không thuộc schema shop — nếu bật route sẽ **lỗi** hoặc truy cập sai DB.                            |
| **Trùng đường dẫn file (Windows)** | Git có thể có `app\Http\...` và `app/Http/...` — nguy cơ **hai file cùng class** trên OS case-sensitive (Linux).                    |
| **Route name không khớp UI**       | `header.blade.php` dùng `route('login')` trong khi `web.php` đặt tên `user.login` — dễ **lỗi route** nếu không có alias `login`.    |
| **Validation không đồng nhất**     | `CartController@addCart`: không thấy validate mạnh/kiểm tồn kho (một phần đã comment) — rủi ro dữ liệu/SKU.                         |

### 6.2. Hiệu năng

- **`products` / `pages` / `categories`:** chỉ có index PK — các query theo **`slug`**, **`status`**, **`type`** có thể **full table scan** khi dữ liệu lớn.
- **N+1:** một số chỗ đã `with()` (vd: `PageController@index` với `home_categories`) — cần audit toàn bộ listing (product, news) bằng **Laravel Debugbar** hoặc **Telescope** trên staging.
- **Cache `theme_option`:** `Cache::forever` — nhanh nhưng khi đổi setting trong admin phải **invalidate** (kiểm tra đã gọi clear chưa).

### 6.3. Bảo mật

- **CSRF off** — ưu tiên P0.
- **SQL raw / ALTER TABLE** trong Ajax admin — cần **whitelist** bảng + chỉ super-admin.
- **Auth provider** — đảm bảo session `admin` và `web` không bị nhầm guard trên route nhạy cảm.

### 6.4. Bảo trì & mở rộng

- Thiếu **service layer** — logic nghiệp vụ nằm rải rác controller → khó test.
- **Migrations** không bao phủ toàn bộ bảng shop (một phần schema đến từ **DB có sẵn**) — môi trường mới khó **tái tạo** chỉ bằng migrate.

---

## 7. Kế hoạch cải thiện (Improvement Plan)

### Ưu tiên 1 — Sửa khẩn cấp (Critical)

| Hạng mục                      | Vấn đề                           | Hướng xử lý                                                                    | Cách triển khai gợi ý                                                      |
| ----------------------------- | -------------------------------- | ------------------------------------------------------------------------------ | -------------------------------------------------------------------------- |
| **Bật lại CSRF**              | Form POST dễ bị giả mạo          | Bật `VerifyCsrfToken` trong `web`; exclude tối thiểu (nếu thật sự cần webhook) | Khôi phục middleware; kiểm thử toàn bộ form/AJAX (thêm token hoặc `@csrf`) |
| **Khóa route `admin/cc`**     | Clear cache không cần đăng nhập  | Xóa route public hoặc bọc `auth:admin` + `can`/super-admin                     | Chỉ giữ trên CLI hoặc env `local`                                          |
| **Chuẩn hóa route đăng nhập** | `route('login')` vs `user.login` | Một tên route thống nhất                                                       | Thêm `Route::get(...)->name('login')` alias hoặc sửa Blade                 |
| **Rà soát Ajax admin**        | `ALTER TABLE ... AUTO_INCREMENT` | Tránh DDL từ request; whitelist                                                | Refactor chỉ cho phép tên bảng cố định + policy                            |

### Ưu tiên 2 — Hiệu năng

| Hạng mục               | Vấn đề                                  | Hướng xử lý                                                                               |
| ---------------------- | --------------------------------------- | ----------------------------------------------------------------------------------------- |
| **Index DB**           | Thiếu index trên cột filter thường dùng | Thêm index `(slug)`, `(status, id)`, `(type, slug)` tùy query thực tế — đo bằng `EXPLAIN` |
| **Eager loading**      | N+1 ở listing                           | Audit `with()`, `select()` cần thiết                                                      |
| **Cache invalidation** | Setting đổi mà cache cũ                 | Khi save `settings`, xóa `theme_option` cache                                             |

### Ưu tiên 3 — Refactor

| Hạng mục             | Vấn đề                                        | Hướng xử lý                                       |
| -------------------- | --------------------------------------------- | ------------------------------------------------- |
| **Service layer**    | Controller phình to                           | Tách `OrderService`, `CartService`, `PageService` |
| **Dọn legacy**       | `ApiController`, controller `theme`/`post` cũ | Xóa hoặc migrate sang model `Product`/`Page`      |
| **Một schema nguồn** | `admins` vs `users` cho admin                 | Quyết định một bảng + cập nhật doc/migration      |
| **CI**               | Regressions                                   | PHPUnit + PHPStan/Pint trên PR                    |

### Ưu tiên 4 — Tính năng / sản phẩm

| Hạng mục                 | Ghi chú                                                                                    |
| ------------------------ | ------------------------------------------------------------------------------------------ |
| **API REST thật**        | Nếu cần app mobile — viết controller/resource mới + Sanctum, không dùng `ApiController` cũ |
| **Wishlist**             | Model `Wishlist` đã xóa trong git — hoàn thiện hoặc gỡ UI                                  |
| **Theo dõi đơn / email** | Chuẩn hóa notification queue (`jobs`)                                                      |
| **SEO & sitemap**        | Đồng bộ với cấu trúc URL mới (`slug` + id)                                                 |

---

## 8. Phụ lục — Gợi ý “bản đồ hệ thống” (tóm tắt)

```
[Browser]
    ↓
[Laravel Router: web / admin]
    ↓
[Middleware: web, currency, auth, checkAdminPermission]
    ↓
[Controllers]
    ↓
[Models Eloquent + Cart + Helpers]
    ↓
[MySQL: vattunnongnghiep58]
    ↓
[Blade + Vite assets]
```

---

_Tài liệu được tạo để onboard developer và lập kế hoạch refactor; cần cập nhật sau mỗi thay đổi kiến trúc lớn._
