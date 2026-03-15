# Tài liệu Hệ thống Phân quyền (ACL) Mới

## Tổng quan

Hệ thống phân quyền đã được chuyển đổi từ mô hình cũ (`admin`, `admin_permission`) sang mô hình tiêu chuẩn Laravel ACL sử dụng các bảng:

- `users`: Lưu thông tin người dùng (bao gồm cả quản trị viên và khách hàng).
- `roles`: Lưu danh sách các vai trò (ví dụ: Administrator, Editor, User).
- `permissions`: Lưu danh sách các quyền hạn (ví dụ: create-post, delete-user).
- `role_user`: Bảng trung gian liên kết Users và Roles (Many-to-Many).
- `permission_role`: Bảng trung gian liên kết Roles và Permissions (Many-to-Many).

## Cấu trúc Database

### 1. Bảng `users`

Bảng này thay thế bảng `admins` cũ.

- `id`: Primary Key
- `username`: Tên đăng nhập
- `email`: Email
- `password`: Mật khẩu (Hash)
- `admin_level`: (Legacy) Mức độ admin, vẫn được giữ để tương thích ngược nhưng logic chính sử dụng Roles.
- ... các trường thông tin cá nhân khác.

### 2. Bảng `roles`

- `id`: Primary Key
- `name`: Tên vai trò (hiển thị)
- `slug`: Mã vai trò (duy nhất, dùng trong code)
- `description`: Mô tả

### 3. Bảng `permissions`

- `id`: Primary Key
- `name`: Tên quyền
- `slug`: Mã quyền (duy nhất)
- `resource`: Tài nguyên (ví dụ: User, Post)
- `action`: Hành động (ví dụ: view, create, edit, delete)
- `http_uri`: URI routes được phép truy cập (nếu áp dụng phân quyền theo route)

## Migration Logic

Dữ liệu từ hệ thống cũ đã được chuyển đổi tự động:

1. **Admins -> Users**: Tất cả tài khoản trong bảng `admins` đã được copy sang bảng `users`. Nếu email/username trùng, tài khoản cũ trong `users` được giữ nguyên.
2. **Admin Permissions -> Permissions**: Các quyền cũ được copy sang bảng mới.
3. **Roles**: Các roles cũ được giữ nguyên hoặc tạo mới.
4. **Gán quyền**: Role `administrator` được gán cho tài khoản có `admin_level = 99999`.

## Hướng dẫn Sử dụng (Cho Developer)

### Kiểm tra quyền trong Controller

Sử dụng phương thức `can()` của model User hoặc middleware.

```php
// Kiểm tra user có role cụ thể
if ($user->isRole('administrator')) {
    // ...
}

// Kiểm tra user có quyền cụ thể (thông qua Role)
if ($user->can('create-post')) {
    // ...
}
```

### Middleware

Middleware `CheckAdminPermission` đã được cập nhật để kiểm tra:

1. User đã đăng nhập (guard `admin`).
2. User là Administrator HOẶC có ít nhất 1 role.

### Tạo quyền mới

Khi tạo module mới, hãy thêm quyền vào bảng `permissions` và gán cho `roles` tương ứng.

```php
Permission::create([
    'name' => 'Quản lý Sản phẩm',
    'slug' => 'manage-products',
    'http_uri' => 'admin/products*',
    'resource' => 'Product',
    'action' => 'manage'
]);
```

## Rollback

Để quay lại hệ thống cũ (nếu cần thiết, không khuyến khích):

1. Rollback migration mới nhất: `php artisan migrate:rollback`
2. Khôi phục code trong `UserAdminController` và `CheckAdminPermission` về phiên bản cũ (sử dụng `admin_level` và bảng `admins`).

## Đăng nhập Admin sau khi cập nhật DB

- Guard `admin` dùng bảng **`users`** (model `App\Models\Backend\User`), **không còn dùng bảng `admins`**.
- Bảng `admins` là legacy: dữ liệu đã được migrate sang `users` (refactor_acl_schema). Mọi thao tác đăng nhập, phân quyền, đổi mật khẩu đều dùng bảng `users`.
- Đăng nhập yêu cầu: `username` hoặc `email`, `password`, và `status = 1` (nếu bảng có cột `status`).
- Menu sidebar admin (User/Role/Permission) hiển thị theo role: chỉ user có role **administrator** (`$user->isAdministrator()`) mới thấy mục đó.

**Kiểm tra user và nguyên nhân không đăng nhập được:**

```bash
# Xem danh sách user trong bảng users (status, role, email, username)
php artisan admin:check-users
```

**Nếu gặp lỗi "These credentials do not match our records":**

1. Chạy `php artisan admin:check-users` để xem user có `status = 1` và có role hay không.
2. Đảm bảo có ít nhất một user trong bảng `users` với `status = 1`.
3. User đó phải có role **administrator** (bảng `role_user`, role `slug = 'administrator'` trong `roles`).
4. Nếu đúng user/email nhưng vẫn báo sai: mật khẩu trong DB có thể sai định dạng (phải bcrypt). Tạo hoặc reset tài khoản admin bằng lệnh:

```bash
# Tạo admin mặc định (username: admin, password: admin123)
php artisan admin:create

# Tạo với thông tin tùy chỉnh
php artisan admin:create --username=admin --email=admin@example.com --password=yourpassword

# Reset mật khẩu cho user đã tồn tại
php artisan admin:create --username=admin --password=newpassword --reset
```

Sau đó đăng nhập tại `/admin/login` với username/email và mật khẩu đã đặt.

## Testing

Unit tests được đặt tại `tests/Feature/NewAclTest.php`.
Chạy test: `php artisan test tests/Feature/NewAclTest.php`
