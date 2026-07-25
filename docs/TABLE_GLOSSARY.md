# TABLE_GLOSSARY — Bảng ↔ Model ↔ Nghiệp vụ

> **Mục đích:** Tra cứu nhanh khi đọc code, viết migration, hoặc refactor sang dự án khác (vd. `3nong`).  
> **Cập nhật:** 2026-07-10 · Laravel **13**  
> **Nguồn sự thật:** Model `$table`, migrations, `MASTER.md` §6.

**Tài liệu liên quan:** [MASTER.md](../MASTER.md) · [ROUTE_GLOSSARY.md](ROUTE_GLOSSARY.md) · [REFACTOR_3NONG_PLAYBOOK.md](REFACTOR_3NONG_PLAYBOOK.md) · [PROJECT_ANALYSIS.md](PROJECT_ANALYSIS.md)

---

## 1. Cách đọc bảng tra cứu

| Cột                | Ý nghĩa                                               |
| ------------------ | ----------------------------------------------------- |
| **Bảng**           | Tên bảng MySQL hiện tại                               |
| **Model**          | Class Eloquent chính (namespace rút gọn)              |
| **Alias / legacy** | Tên cũ, class alias, hoặc convention Laravel mặc định |
| **Nghiệp vụ**      | Thuật ngữ business / mô tả ngắn                       |
| **PK**             | Primary key (nếu khác `id`)                           |
| **Trạng thái**     | `active` · `legacy` · `dropped` · `low-use`           |

---

## 2. Thuật ngữ dễ nhầm (đọc trước khi tra bảng)

| Thuật ngữ                 | Trong code / DB                    | Ghi chú                                     |
| ------------------------- | ---------------------------------- | ------------------------------------------- |
| **Khách hàng (Customer)** | Bảng `users`, guard `web`          | Không còn bảng `customer`                   |
| **Tài khoản (Account)**   | URL `/account/*`                   | Chỉ là prefix route, không phải bảng        |
| **Đơn hàng (Order)**      | `shop_orders` + `shop_order_items` | Tên cũ: `addtocard`, `addtocard_detail`     |
| **Bài viết (Post)**       | `pages` với `type = post`          | Không còn bảng `posts`                      |
| **Trang CMS (Page)**      | `pages` với `type = page`          | Cùng bảng với bài viết                      |
| **Admin**                 | `users` + role, guard `admin`      | Provider `admins` → model `Backend\User`    |
| **Giỏ hàng (Cart)**       | Session (`shoppingcart` package)   | Chỉ persist DB khi checkout → `shop_orders` |

---

## 3. Bảng nghiệp vụ chính (`active`)

### 3.1. Người dùng & xác thực

| Bảng                       | Model                                                | Alias / legacy               | Nghiệp vụ                      | PK   | Trạng thái |
| -------------------------- | ---------------------------------------------------- | ---------------------------- | ------------------------------ | ---- | ---------- |
| `users`                    | `Frontend\User`, `Backend\User`                      | —                            | Khách hàng + quản trị (shared) | `id` | active     |
| `roles`                    | `Backend\Role`                                       | —                            | Vai trò admin                  | `id` | active     |
| `permissions`              | `Backend\Permission`                                 | thay `admin_permission`      | Quyền theo `http_uri`          | `id` | active     |
| `role_user`                | `Backend\RoleUser`                                   | —                            | Pivot user ↔ role              | —    | active     |
| `permission_role`          | —                                                    | thay `admin_role_permission` | Pivot role ↔ permission        | —    | active     |
| `password_reset_tokens`    | —                                                    | Laravel default              | Reset mật khẩu (token)         | —    | active     |
| `customer_forget_pass_otp` | `Backend\Customer_forget_pass_otp`                   | —                            | OTP quên mật khẩu (3 bước)     | `id` | active     |
| `user_password_auto`       | `Backend\UserPasswordAuto`                           | —                            | Mật khẩu tạm / auto            | `id` | low-use    |
| `user_register_email`      | `Backend\User_register_email`, `User_register_email` | —                            | Log email đăng ký              | `id` | low-use    |
| `sessions`                 | —                                                    | Laravel                      | Session driver DB              | `id` | active     |

> **Lưu ý:** Bảng `admins` có thể còn trong schema cũ nhưng **runtime dùng `users`** — không tạo logic mới trên `admins`.

### 3.2. Sản phẩm & danh mục

| Bảng                    | Model                                   | Alias / legacy                  | Nghiệp vụ                          | PK   | Trạng thái |
| ----------------------- | --------------------------------------- | ------------------------------- | ---------------------------------- | ---- | ---------- |
| `products`              | `Frontend\Product`, `Backend\Product`   | —                               | Sản phẩm                           | `id` | active     |
| `categories`            | `Frontend\Category`, `Backend\Category` | —                               | Danh mục SP (cây `parent`)         | `id` | active     |
| `product_categories`    | `Backend\ProductCategory`               | pivot                           | SP ↔ danh mục (n-n)                | —    | active     |
| `product_prices`        | `ProductPrice`                          | —                               | Giá / biến thể                     | `id` | active     |
| `shop_product_category` | `Frontend\ShopProductCategory`          | —                               | Pivot / mapping SP (legacy naming) | —    | low-use    |
| `countries`             | `Frontend\Country`, `Backend\Country`   | `country` trong một số model cũ | Quốc gia / địa chỉ                 | `id` | active     |

### 3.3. Đơn hàng & thanh toán

| Bảng                        | Model                                                             | Alias / legacy     | Nghiệp vụ               | PK            | Trạng thái                  |
| --------------------------- | ----------------------------------------------------------------- | ------------------ | ----------------------- | ------------- | --------------------------- |
| `shop_orders`               | `Frontend\Order`, `Backend\Order`, **`Addtocard`**                | `addtocard`        | Header đơn hàng         | **`cart_id`** | active                      |
| `shop_order_items`          | `Frontend\OrderItem`, `Backend\OrderItem`, **`Addtocard_Detail`** | `addtocard_detail` | Dòng chi tiết đơn       | `id`          | active                      |
| `shop_order_status`         | `Frontend\ShopOrderStatus`                                        | —                  | Trạng thái đơn (lookup) | `id`          | active                      |
| `shop_order_payment_status` | `Frontend\ShopOrderPaymentStatus`                                 | —                  | Trạng thái thanh toán   | `id`          | active                      |
| `shop_payment_method`       | `Backend\ShopPaymentMethod`                                       | —                  | Phương thức thanh toán  | `id`          | low-use                     |
| `shop_currencies`           | `Backend\ShopCurrency`                                            | —                  | Tiền tệ                 | `id`          | active                      |
| `contact_payment`           | `Backend\ContactPayment`                                          | —                  | Liên hệ thanh toán      | `id`          | low-use (bảng có thể thiếu) |
| `orders`                    | `Backend\Orders`                                                  | —                  | Bảng đơn cũ / song song | `id`          | legacy — bảng không tồn tại |

> **PK đặc biệt:** `shop_orders.cart_id` — route account dùng `{id_cart}` trỏ vào cột này, không phải `id`.

### 3.4. CMS & nội dung

| Bảng              | Model                                             | Alias / legacy | Nghiệp vụ                      | PK   | Trạng thái |
| ----------------- | ------------------------------------------------- | -------------- | ------------------------------ | ---- | ---------- |
| `pages`           | `Frontend\Page`, `Backend\Page`                   | thay `posts`   | Trang CMS + bài viết (`type`)  | `id` | active     |
| `contacts`        | `Frontend\Contact`, `Backend\Contact`             | —              | Form liên hệ                   | `id` | active     |
| `email_templates` | `Frontend\EmailTemplate`, `Backend\EmailTemplate` | —              | Mẫu email theo `code`          | `id` | active     |
| `menus`           | `Frontend\Menu`, `Backend\Menu`                   | —              | Menu frontend                  | `id` | active     |
| `menu_items`      | `Frontend\MenuItems`, `Backend\MenuItems`         | —              | Item menu                      | `id` | active     |
| `admin_menus`     | `Backend\AdminMenu`                               | —              | Sidebar admin                  | `id` | active     |
| `albums`          | `Frontend\Album`, `Backend\Album`                 | —              | Album ảnh                      | `id` | active     |
| `album_items`     | `Frontend\AlbumItem`, `Backend\AlbumItem`         | —              | Ảnh trong album                | `id` | active     |
| `media_files`     | —                                                 | —              | File media (upload)            | `id` | active     |
| `settings`        | `Setting`, `Backend\Setting`                      | —              | Cấu hình site (SMTP, theme, …) | `id` | active     |

> **Newsletter:** không có bảng `subscription` — `POST /subscription` lưu `contacts` với `type=subscription`.

---

## 4. Bảng hệ thống & queue

| Bảng                   | Nghiệp vụ             | Trạng thái                |
| ---------------------- | --------------------- | ------------------------- |
| `migrations`           | Laravel migration log | active                    |
| `cache`, `cache_locks` | Cache driver DB       | active                    |
| `jobs`, `failed_jobs`  | Queue                 | active (nếu bật queue DB) |

---

## 5. Bảng legacy / low-use (model còn, luồng chính không dùng)

| Bảng                                                                                                          | Model                               | Ghi chú             | Trạng thái                                                                                         |
| ------------------------------------------------------------------------------------------------------------- | ----------------------------------- | ------------------- | -------------------------------------------------------------------------------------------------- |
| `wishlist`                                                                                                    | `Wishlist`                          | Yêu thích SP        | low-use                                                                                            |
| `rating_product`                                                                                              | `Rating_Product`                    | Đánh giá SP         | low-use                                                                                            |
| `discount_code`                                                                                               | `Discount_code`                     | Mã giảm giá         | low-use                                                                                            |
| `discount_for_brand`                                                                                          | `Discount_for_brand`                | Giảm giá theo brand | low-use                                                                                            |
| `check_product_limit_event`                                                                                   | `Backend\Check_product_limit_event` | Giới hạn sự kiện    | low-use                                                                                            |
| `import_log`                                                                                                  | `Backend\ImportLog`                 | Log import SP admin | active (admin import)                                                                              |
| `jt_address`                                                                                                  | `Backend\Jt_address`                | Địa chỉ J&T (ship)  | low-use                                                                                            |
| `sponser`                                                                                                     | `Sponser`                           | Nhà tài trợ         | low-use                                                                                            |
| `theme`, `theme_info`, `variable_theme`, `theme_join_variable_theme`, `category_theme`, `join_category_theme` | nhiều model Theme\*                 | Theme builder cũ    | legacy — **không** còn quick-edit AJAX admin (`process_theme_fast`, toggle `item_new`/`propose`/…) |

---

## 6. Bảng đã drop (không query / không migrate ngược)

| Bảng                                                    | Thay bằng               | Migration tham chiếu                                     |
| ------------------------------------------------------- | ----------------------- | -------------------------------------------------------- |
| `customer`                                              | `users`                 | `2026_07_08_181633_drop_legacy_customer_table`           |
| `addtocard`                                             | `shop_orders`           | `2026_03_24_163604_rename_addtocard_tables_to_orders`    |
| `addtocard_detail`                                      | `shop_order_items`      | cùng migration trên                                      |
| `admin_permission`                                      | `permissions`           | `2026_03_22_120000_drop_legacy_admin_permission_tables`  |
| `admin_role_permission`                                 | `permission_role`       | cùng migration trên                                      |
| `admins`                                                | `users` + ACL           | `2026_07_09_194029_drop_legacy_admin_and_payment_tables` |
| `payments`, `payment_request`                           | checkout offline        | cùng migration trên                                      |
| `password_resets`                                       | `password_reset_tokens` | cùng migration + `config/auth.php`                       |
| `user_password_auto`, `settings_cost`, `shipping_order` | —                       | cùng migration trên                                      |
| `subscription`                                          | `contacts` (type)       | Phase 4 orphan — không tạo lại bảng                      |

> Bảng `posts`, `post_categories`, `category_page`, `page_categories` đã drop — dùng `pages` (`type=post`); không liệt kê chi tiết.

---

## 7. Class alias quan trọng (không phải bảng)

| Class alias                      | Extends                       | Bảng thực tế          |
| -------------------------------- | ----------------------------- | --------------------- |
| `App\Models\Addtocard`           | `Frontend\Order`              | `shop_orders`         |
| `App\Models\Addtocard_Detail`    | `Frontend\OrderItem`          | `shop_order_items`    |
| `App\Models\User_register_email` | `Backend\User_register_email` | `user_register_email` |

---

## 8. Quan hệ pivot thường gặp

```
users ←→ roles          qua role_user
roles ←→ permissions    qua permission_role
products ←→ categories  qua product_categories
shop_orders → shop_order_items   qua cart_id (1-n)
shop_order_items → products      qua product_id
```

---

## 9. Checklist cho AI / refactor

- [ ] Đơn hàng: dùng `shop_orders` / `shop_order_items`, PK đơn là `cart_id`
- [ ] Bài viết: query `pages` + `where type = post`, không tạo bảng `posts`
- [ ] Khách hàng: `users` + guard `web`, không bảng `customer`
- [ ] Admin ACL: `permissions` + `permission_role`, không `admin_permission`
- [ ] Route `customer.*` ≠ bảng — chỉ là prefix tên route
- [ ] Newsletter: `contacts` + `type=subscription` (không bảng `subscription`)

---

## 10. Lịch sử

| Ngày       | Thay đổi                                                                                       |
| ---------- | ---------------------------------------------------------------------------------------------- |
| 2026-07-10 | Newsletter → `contacts`; bỏ hàng `subscription` khỏi §3 active                                 |
| 2026-07-10 | Ghi chú `theme` legacy: đã gỡ quick-edit AJAX; SP admin dùng `products` + `admin.quick-change` |
| 2026-07-10 | Bỏ §3.5 bảng post đã drop (trùng §6); rút gọn §6                                               |
| 2026-07-10 | Tạo TABLE_GLOSSARY.md                                                                          |
