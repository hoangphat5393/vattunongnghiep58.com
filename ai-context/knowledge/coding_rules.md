# Coding Rules (Project Conventions) — vattunongnghiep58

## Conventions observed

- Controllers:
  - Frontend: `app/Http/Controllers/*`
  - Admin: `app/Http/Controllers/Admin/*`
- Models:
  - Frontend: `app/Models/Frontend/*`
  - Backend: `app/Models/Backend/*`
  - Một số model legacy/khác nằm tại `app/Models/*`
- Views:
  - Frontend: `resources/views/frontend/*`
  - Backend: `resources/views/backend/*`
  - Nhiều partials trong `resources/views/backend/partials/*`
- Helper functions:
  - `app/Libraries/system.php` được autoload trong composer.

## Recommendations for future changes (to reduce drift)

- Routing:
  - Ưu tiên chuẩn hoá FQCN controllers trong routes (giảm phụ thuộc namespace string).
- Models:
  - Tránh thêm model trùng bảng ở nhiều namespace; nếu cần, tạo “read model” rõ ràng và document.
  - Giảm dùng `protected $guarded = [];` cho model nhạy cảm.
- Controllers:
  - Tránh build HTML trong controller; dùng view/mailables/notifications.
  - Dùng FormRequest cho validation ở các endpoint quan trọng.
- Views:
  - Tránh xử lý serialize/json/transform trong Blade; chuẩn bị data trước khi render.
- Integrations:
  - Không hardcode thông tin (email/ip/keys); luôn đi qua `.env → config → code`.

