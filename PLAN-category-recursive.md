# Plan: Chuyển Category sang render đệ quy “đúng” (không N+1)

## Mục tiêu
- Render category tree nhiều cấp (3–4–N cấp) bằng đệ quy nhưng **không query DB trong vòng lặp** (tránh N+1).
- Giữ nguyên UI/HTML output hiện tại (không đổi layout), chỉ thay cách cấp dữ liệu cho view.
- Áp dụng cho các chỗ đang render category tree trong backend:
  - Select dropdown: `resources/views/backend/product-category/includes/select-category.blade.php`
  - Table rows: `resources/views/backend/product-category/includes/category_item.blade.php`
  - Checkbox tree (product edit): `resources/views/backend/partials/category-item.blade.php`

## Không làm (non-goals)
- Không đổi thiết kế DB (vẫn adjacency list: `parent`).
- Không đổi permission/auth/global middleware/global routes.
- Không tối ưu hoá lớn hoặc refactor kiến trúc.

## Hiện trạng (rủi ro)
- View hiện đang gọi quan hệ kiểu `children()->get()` trong đệ quy ⇒ nếu dữ liệu lớn sẽ phát sinh **N+1 query**.
- Luật dự án “không logic trong Blade” ⇒ phần dựng cây phải làm ở controller (hoặc nơi cấp data), không làm trong view.

## Phương án đề xuất (Recommended)
### A) Prefetch 1 lần + dựng cây trong PHP (query ổn định)
- Controller lấy toàn bộ categories cần dùng (status/type nếu có).
- Dựng cấu trúc dạng:
  - `childrenMap[parentId] = Collection<Category>`
  - hoặc `tree = [node => children => ...]`
- View chỉ render đệ quy dựa trên `childrenMap` / `tree` ⇒ **không query thêm**.

Vì codebase đang dùng include đệ quy (Blade `@include`), hướng này ít thay đổi nhất và an toàn.

## Kế hoạch thực hiện theo bước

### Bước 1 — Audit điểm render category tree (không sửa code)
- Xác định tất cả nơi dùng:
  - `children()` trong Blade
  - `includes/select-category`
  - `partials/category-item`
- Xác định controller nào trả về các view này (VD: product edit, product-category create/edit, ...).

**Output mong muốn**
- Danh sách file + route/controller liên quan.

### Bước 2 — Chuẩn hoá “data contract” cho view (thêm biến, chưa xoá code cũ)
- Chuẩn hoá dữ liệu truyền xuống view:
  - `$childrenMap` (array<int, \Illuminate\Support\Collection>)
  - `$rootParentId` (thường là `0`)
- Vẫn giữ fallback code cũ trong thời gian chuyển đổi để không vỡ UI.

**Acceptance**
- Tất cả view render được như trước (không lỗi).

### Bước 3 — Refactor Blade sang dùng `$childrenMap` (bỏ query trong view)
- Cập nhật 3 view:
  - `product-category/includes/select-category.blade.php`
  - `product-category/includes/category_item.blade.php`
  - `partials/category-item.blade.php`
- Đệ quy dựa trên `childrenMap[$id] ?? collect()` thay vì `$model->children()->get()`.

**Acceptance**
- Không còn gọi `children()` trong Blade ở các file trên.
- UI hiển thị đúng cây 4 cấp (đã có test).

### Bước 4 — Update controller(s) để cấp `$childrenMap`
- Với mỗi trang liên quan:
  - lấy categories 1 query
  - group theo `parent`
  - truyền `$childrenMap` xuống view (các include dùng chung nhận được biến).

**Acceptance**
- Số query không tăng theo số node (ổn định theo 1–2 query/trang).

### Bước 5 — Test & regression
- Mở rộng test hiện có (đang ở `tests/Feature/ProductCategoryCategoryTreeViewsTest.php`):
  - Assert render tree 4 cấp vẫn OK.
  - (Tuỳ mức độ) đo số query khi render view để đảm bảo không N+1.
- Chạy:
  - `php artisan test --compact tests/Feature/ProductCategoryCategoryTreeViewsTest.php`
  - `vendor/bin/pint --dirty --format agent`

## Tiêu chí hoàn thành
- Category tree render được 3–4–N cấp.
- Không query DB trong vòng đệ quy (không N+1).
- Không thay đổi UI/layout.
- Test pass + Pint pass.

