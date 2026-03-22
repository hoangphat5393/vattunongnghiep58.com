# Ghi chú: Loại bỏ bảng `admin_permission` và `admin_role_permission`

**Ngày:** 2026-03-22

## Lý do

- Hai bảng này là **schema ACL cũ** (tạo trong migration `2026_02_11_100000_create_admin_permissions_tables.php`).
- Dữ liệu đã được **migrate** sang hệ thống chuẩn Laravel-style:
    - Bảng **`permissions`**
    - Pivot **`permission_role`**
    - Migration thực hiện copy: `database/migrations/2026_02_11_110000_refactor_acl_schema.php` (mục 7–8).
- Code runtime **không** đọc/ghi `admin_permission` / `admin_role_permission`:
    - `App\Models\Backend\Permission` dùng `$table = 'permissions'`.
    - Quan hệ role–permission dùng pivot **`permission_role`** (`Role::permissions()`, `Permission::roles()`).
    - `CheckAdminPermission` / `User::allPermissions()` chỉ load qua `roles()->with('permissions')`.

## Việc đã làm

1. **Migration mới:** `database/migrations/2026_03_22_120000_drop_legacy_admin_permission_tables.php`
    - `Schema::dropIfExists('admin_role_permission')`
    - `Schema::dropIfExists('admin_permission')`

2. **Sửa test:** `tests/Feature/AdminPermissionTest.php`
    - Assert đúng bảng `permissions` và `permission_role` (trước đó nhầm với bảng legacy).

3. **Cập nhật health check:**
    - `app/Console/Commands/CheckSystemHealth.php`
    - `tests/Feature/SystemHealthTest.php`
    - Danh sách bảng kiểm tra: bỏ hai bảng legacy, thêm `permissions` / `permission_role` nếu cần.

4. **Xóa model không dùng:** `app/Models/Backend/RolePermission.php` (chỉ còn comment `$table`, không được reference).

5. **Cập nhật tài liệu:** `docs/PROJECT_ANALYSIS.md` (danh sách bảng + mô tả ACL).

## Cách áp dụng trên server

```bash
php artisan migrate
```

Sau khi chạy, trong HeidiSQL/MySQL sẽ **không còn** hai bảng trên. Quyền admin vẫn hoạt động qua `permissions` + `permission_role` + `role_user`.

## Rollback

- Migration drop **không** tạo lại bảng trong `down()` (tránh tái giới thiệu schema lỗi thời).
- Nếu cần khôi phục dữ liệu cực hiếm từ backup DB trước khi drop, khôi phục file SQL backup; không khuyến khích tái tạo bảng legacy trong code mới.
