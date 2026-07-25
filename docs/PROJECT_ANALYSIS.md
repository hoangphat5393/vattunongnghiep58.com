# Phân tích dự án — Vật Tư Nông Nghiệp 58

Tài liệu này tổng hợp kiến trúc, cơ sở dữ liệu, luồng dữ liệu, vấn đề và kế hoạch cải thiện.  
**Phạm vi:** mã nguồn ứng dụng (`app/`, `routes/`, `config/`, `resources/`, `database/migrations/`), không liệt kê từng file trong `node_modules/` hay `public/assets/plugin/`.

**Cập nhật lần cuối:** 2026-07-10 — DB cleanup 35 bảng; Phase 4 orphan; [REFACTOR_3NONG_PLAYBOOK.md](REFACTOR_3NONG_PLAYBOOK.md).

**Theo dõi tiến độ:** [IMPROVEMENT_PLAYBOOK.md](IMPROVEMENT_PLAYBOOK.md) · [BACKEND_AUDIT_PLAYBOOK.md](BACKEND_AUDIT_PLAYBOOK.md) · [CHANGE_LOG.md](CHANGE_LOG.md) · [REFACTOR_3NONG_PLAYBOOK.md](REFACTOR_3NONG_PLAYBOOK.md)

## 1. Tổng quan dự án (Project Overview)

- **Mục đích:** Website thương mại điện tử / giới thiệu sản phẩm nông nghiệp (thương hiệu giao diện: _Vật Tư 58_, _Nông Nghiệp Sạch_), hỗ trợ **danh mục sản phẩm**, **giỏ hàng**, **đặt hàng / thanh toán**, **tin bài / trang tĩnh**, **tìm kiếm**, **liên hệ**, và **khu vực quản trị** (CMS + đơn hàng + phân quyền).
- **Công nghệ lõi:**
    - **Backend:** PHP **8.4+**, **Laravel 12**, Eloquent ORM.
    - **Auth:** Guard `web` (khách hàng) và guard `admin` (quản trị — model dùng bảng `users`).
    - **Frontend:** Blade + **Vite 7**, **Tailwind CSS 4**, jQuery / Axios / Swiper / AOS (bundle `resources/js/app.js`).
    - **Thư viện đáng chú ý:** `surfsidemedia/shoppingcart`, `gornymedia/laravel-shortcodes`, `ckfinder/ckfinder-laravel-package`, `intervention/image`, Socialite, reCAPTCHA v3, Mailgun.
- **Đặc điểm:** Codebase đã qua nhiều đợt refactor (gộp bài viết vào `pages` với `type`, `NewsController` → `PostController`, gỡ `ApiController` và bundle JS/webpack cũ). Frontend chỉ dùng **Vite**; admin dùng jQuery + **axios** (`js_admin.js` + Blade inline).

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
| **Storefront** | `routes/web.php` → `PageController`, `ProductController`, `PostController`, `CartController`, `CustomerController`, `ContactController`, `SearchController`… |
| **Admin**      | `routes/admin.php` (prefix URL `/admin`) → CRUD page/post/product/contact/email-template/album, menu, theme-option, user/role/permission, order…             |
| **API (file)** | `routes/api.php` — **stub trống** (legacy `ApiController` đã gỡ 2026-07-05); chưa đăng ký route API mới.                                                     |

### 2.3. Frontend assets (Vite vs public)

| Lớp                   | Nguồn                                                                                  | Ghi chú                                                                              |
| --------------------- | -------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------ |
| **Storefront**        | `@vite(['resources/css/app.css', 'resources/scss/style.scss', 'resources/js/app.js'])` | `app.js` → `http.js`, `auth-forms.js`, `custom.js`, axios-setup, jQuery, Swiper, AOS |
| **Storefront routes** | `app-routes.blade.php` → `window.AppRoutes`                                            | Cart remove, contact, auth POST — không hardcode URL                                 |
| **Admin**             | `assets/js/jquery-3.7.1.min.js`, `js_admin.js`, plugin trong `master.blade.php`        | Không dùng Vite; AJAX qua **axios** + `window.AdminRoutes`                           |
| **Menu builder**      | `public/assets/laravel-menu/menu.js`                                                   | axios + `AdminRoutes.bulkDelete` / `bulkReplicate`                                   |
| **Đã gỡ**             | `public/assets/js/app.js`, `custom.js`, `resources/js/components/*.vue`                | Webpack/Vue cũ — xem CHANGE_LOG P2–P4                                                |

### 2.4. Jobs / Queue

- Thư mục **`app/Jobs`:** không có class Job trong scan hiện tại.
- Database có bảng `jobs`, `failed_jobs` (chuẩn Laravel) — có thể dùng queue nhưng **không thấy job nghiệp vụ tùy chỉnh** trong repo.

### 2.5. Middleware quan trọng

- `web`: session, cookie, locale, **`VerifyCsrfToken`** (bật lại IMP-009; ngoại lệ `ckfinder/*`).
- `currency`: áp vào nhóm route public trong `RouteServiceProvider`.
- `auth:admin` + `checkAdminPermission`: phân quyền menu/URI cho admin.

---

## 3. Cấu trúc cơ sở dữ liệu (Database Structure)

### 3.1. Môi trường đã introspect (MySQL)

- Kết nối ứng dụng trỏ tới database **`vattunnongnghiep58`** (MySQL 8.x).
- **Lư ý:** Server MySQL có thể chứa **nhiều schema** (ví dụ các bảng thuộc project khác); số bảng “tổng” trên server không đồng nghĩa chỉ có bảng của shop này.

### 3.2. Bảng thuộc ứng dụng (schema `vattunnongnghiep58`)

Danh sách bảng thực tế (**35 bảng**, sau cleanup 2026-07-10):

`admin_menus`, `album_items`, `albums`, `cache`, `cache_locks`, `categories`, `contacts`, `countries`, `customer_forget_pass_otp`, `email_templates`, `failed_jobs`, `import_log`, `jobs`, `media_files`, `menu_items`, `menus`, `migrations`, `pages`, `password_reset_tokens`, `permission_role`, `permissions`, `product_categories`, `product_prices`, `products`, `role_user`, `roles`, `sessions`, `settings`, `shop_currencies`, `shop_order_items`, `shop_order_payment_status`, `shop_order_status`, `shop_orders`, `shop_payment_method`, `users`.

_(Đã drop: `admins`, `payments`, `payment_request`, `password_resets`, `user_password_auto`, `settings_cost`, `shipping_order`. Đơn hàng: `addtocard`/`addtocard_detail` đã rename → `shop_orders`/`shop_order_items`.)_

### 3.3. Quan hệ logic (ORM / nghiệp vụ)

- **Sản phẩm — danh mục (n-n):** bảng trung gian **`product_categories`** (`product_id`, `category_id`) — có **index FK** trên `product_id` và `category_id`.
- **Trang / bài viết:** bảng **`pages`**, phân biệt **`type`** (ví dụ `page` / `post`) — thay cho mô hình `posts` cũ (đã migrate/loại bỏ qua migrations).
- **Đơn hàng:** **`shop_orders`** (header, PK `cart_id`) + **`shop_order_items`** (chi tiết) — model `Frontend\Order` / `OrderItem`; alias legacy `Addtocard`.
- **ACL:** `users` ↔ `roles` qua **`role_user`**; `roles` ↔ `permissions` qua **`permission_role`**. Guard admin dùng model `Backend\User` với **`$table = 'users'`** — bảng `admins` **đã drop**.
- **Newsletter:** `POST /subscription` → bản ghi `contacts` với `type=subscription` (không bảng `subscription`).

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

**`shop_orders` (đơn):**

- PK: `cart_id` (không phải `id`). Thông tin khách, địa chỉ, `cart_total`, `cart_status`, `user_id`, thanh toán.

### 3.5. So sánh schema vs Model Laravel

- **`App\Models\Frontend\Page`:** khớp hướng dùng `type`, scope `posts` / `pages`; accessor đa ngôn ngữ; **`getCategoriesAttribute`** trả collection rỗng (bảng category-page đã bỏ) — **đồng bộ với refactor DB**.
- **`App\Models\Frontend\Product`:** khớp bảng `products`; quan hệ category qua `product_categories` (cần đối chiếu method quan hệ trong model).
- **`App\Models\Backend\User`:** `$table = 'users'` — **khớp** `auth.php` provider `admins`; bảng `admins` **đã drop** (2026-07-10).

### 3.6. Rủi ro truy vấn (unsafe / nặng)

- **`whereRaw` / `orderByRaw`** với chuỗi thời gian hoặc tên bảng — một số chỗ ghép chuỗi (ví dụ logic OTP/giới hạn 300 giây trong `CustomerController`, `ForgotPasswordController`, `HomeController`) — cần rà soát **SQL injection** nếu có phần input người dùng lọt vào raw (hiện tại chủ yếu là thời gian server).
- **`Admin\AjaxController`:** dùng `DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;")` — **nguy hiểm** nếu `$table` có thể bị thao túng (phụ thuộc validation đầu vào và quyền admin).

---

## 4. Các module chính (Key Modules)

- **Trang chủ & trang tĩnh:** `PageController@index`, `PageController@page` — load `pages` (`slug` home, hoặc slug động), shortcode, SEO.
- **Sản phẩm:** `ProductController` — danh sách, chi tiết URL dạng `product/{slug}-{id}.html`, quick view, mua nhanh.
- **Tin tức / bài viết:** `PostController` — URL `/news/...`, dữ liệu từ `pages` type `post`, transform qua `FrontendDataTransform`.
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

### 5.3. API

- `routes/api.php` chỉ còn comment stub — **không có endpoint** hoạt động.
- Legacy `/api/slider`, `/api/products`, … trả **404** (`ApiLegacyCleanupTest`).
- Nếu cần API mobile: viết mới + Sanctum, không khôi phục controller cũ.

### 5.4. Logic trùng / dùng chung

- **Chuẩn hóa dữ liệu UI:** `FrontendDataTransform` (tránh lặp map trong từng view).
- **Cấu hình theme:** `setting_option()` + cache vĩnh viễn — **một nơi** nhưng cần **chiến lược xóa cache** khi đổi setting.
- **Trùng có thể có:** nhiều controller **legacy** (`HomeController` cấp root với query `theme`/`post`) song song với flow mới — dễ **trùng nghiệp vụ** nếu route còn trỏ tới.

---

## 6. Vấn đề & trạng thái (Issues)

### 6.1. Đã xử lý (2026-07-05)

| Hạng mục                 | Chi tiết                                                                     |
| ------------------------ | ---------------------------------------------------------------------------- |
| **CSRF**                 | `VerifyCsrfToken` bật trong `web`; test `CsrfProtectionTest`                 |
| **API legacy**           | Gỡ `ApiController`; `routes/api.php` stub; test `ApiLegacyCleanupTest`       |
| **Checkout legacy**      | Redirect/stub quick-buy; test `CheckoutLegacyCleanupTest`                    |
| **Category tree N+1**    | `childrenMap` + Blade đệ quy; test `ProductCategoryCategoryTreeViewsTest`    |
| **Admin `$.ajax` Blade** | `admin-menu`, `change-password` → axios; test `AdminAxiosP1Test`             |
| **Admin route rename**   | `admin.bulk.*`, `cart.remove-item`, alias legacy; test `RouteAliasTest`      |
| **Theme quick-edit gỡ**  | JS `process_theme_fast` / toggle `theme.*` — thay `admin.quick-change`       |
| **JS dead code**         | Gỡ `public/assets/js/app.js`, `custom.js`, 8 file `.vue`; test cleanup suite |
| **Tailwind v4**          | Batch A–C frontend; test `FrontendPagesTest`                                 |

### 6.2. Backlog backend (đã đóng 2026-07-07)

**Theo dõi chi tiết:** [BACKEND_AUDIT_PLAYBOOK.md](BACKEND_AUDIT_PLAYBOOK.md) — `BACK-001` … `BACK-018` trạng thái `done`.

| Giai đoạn | ID           | Trạng thái |
| --------- | ------------ | ---------- |
| P0        | BACK-001…003 | `done`     |
| P1        | BACK-004…009 | `done`     |
| P2        | BACK-010…017 | `done`     |
| P3        | BACK-018     | `done`     |

**Việc ngoài BACK:** smoke CKFinder thủ công; `php artisan migrate` (index slug); IMP-011 `deferred`.

### 6.3. Hiệu năng

- **`products` / `pages` / `categories`:** migration index `slug`/`status` (BACK-014) — chạy `php artisan migrate` trên server.
- **N+1:** một số chỗ đã `with()` (vd: `PageController@index` với `home_categories`) — cần audit toàn bộ listing (product, news) bằng **Laravel Debugbar** hoặc **Telescope** trên staging.
- **Cache `theme_option`:** `Cache::forever` — nhanh nhưng khi đổi setting trong admin phải **invalidate** (kiểm tra đã gọi clear chưa).

### 6.4. Bảo mật (đã xử lý chính qua BACK)

- `/admin/cc`, CKFinder auth, RBAC admin, logout POST, Theme CSS administrator-only — xem playbook.
- **Còn:** smoke test upload CKFinder thủ công trên staging/local.

### 6.5. Bảo trì & mở rộng

- Thiếu **service layer** — logic nghiệp vụ nằm rải rác controller → khó test.
- **Migrations** không bao phủ toàn bộ bảng shop (một phần schema đến từ **DB có sẵn**) — môi trường mới khó **tái tạo** chỉ bằng migrate.

---

## 7. Kế hoạch cải thiện (sau BACK-018)

> IMP-001…015 và BACK-001…018 đã xong (trừ IMP-011 `deferred`). Backlog mới → mở `BACK-019+` trong playbook.

### Gợi ý ưu tiên tiếp theo (chưa có ID BACK)

| Hạng mục           | Hướng xử lý                                       |
| ------------------ | ------------------------------------------------- |
| **Eager loading**  | Audit listing product/news                        |
| **Cache settings** | Invalidate `theme_option` khi save admin          |
| **Service layer**  | Tách dần `CartService`, `OrderService` (tùy chọn) |

### Ưu tiên sản phẩm

| Hạng mục               | Ghi chú                             |
| ---------------------- | ----------------------------------- |
| **API REST**           | Sanctum + resource mới              |
| **SEO / sitemap**      | Đồng bộ URL `slug-id`               |
| **Notification queue** | Dùng bảng `jobs` cho email đơn hàng |

### Kiểm chứng (living)

```bash
php artisan test --compact                    # 192 passed, 2 skipped (2026-07-10)
pnpm run build                                # sau đổi frontend
```

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

_Tài liệu living doc — cập nhật sau thay đổi kiến trúc lớn hoặc mỗi sprint cleanup. Index: `RECOMMENDATIONS.md`._
