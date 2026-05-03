# AI Context — vattunongnghiep58 (Laravel E-commerce)

## Mục đích

Thư mục này chứa bối cảnh (context) và báo cáo audit được trích xuất trực tiếp từ source code để:

- Hiểu kiến trúc tổng thể + entrypoints (routes/controllers/views)
- Nắm workflow nghiệp vụ (catalog/cart/checkout/order/admin)
- Phát hiện security risk, technical debt, và vấn đề hiệu năng
- Chuẩn bị modernization roadmap (không refactor ngay)
- Cung cấp dữ liệu RAG (JSON) để AI truy hồi nhanh ở các lần làm việc sau

## Project Overview (tóm tắt từ codebase)

- Framework: Laravel 12 (structure kiểu Laravel 10), PHP ^8.3
- Frontend stack: Vite + Tailwind v4 (resources/), nhưng vẫn có lượng lớn asset legacy trong public/assets (AdminLTE, jQuery, plugin)
- Domain chính (suy ra từ routes/controllers):
  - Frontend: trang nội dung (Page), sản phẩm (Product), tin tức (News), tìm kiếm (Search), giỏ hàng (Cart), checkout (Checkout)
  - Customer: đăng ký/đăng nhập, quên mật khẩu, dashboard, lịch sử đơn hàng
  - Admin: CRUD content/product/category/album/page/post, quản lý order, ACL (role/permission), menu, theme-option, theme-css

## Module Overview (theo functional area)

Xem chi tiết tại:

- `architecture/MODULE_MAP.md`
- `architecture/LARAVEL_ARCHITECTURE_OVERVIEW.md`

## Workflow (business flow)

Xem chi tiết tại:

- `architecture/BUSINESS_FLOW_OVERVIEW.md`
- `architecture/HIGH_RISK_WORKFLOW.md`
- `rag/workflow.json`

## Business Rules (knowledge base)

Các file dưới đây là “giả thuyết có kiểm chứng” dựa trên code; mỗi rule đều cố gắng gắn với evidence (file/route/model):

- `knowledge/business_rules.md`
- `knowledge/order_rules.md`
- `knowledge/inventory_rules.md`
- `knowledge/security_rules.md`
- `knowledge/coding_rules.md`

## Audit Summary (điểm nóng cần ưu tiên)

Xem báo cáo chi tiết:

- `audit/SECURITY_REPORT.md`
- `audit/BACKEND_AUDIT_REPORT.md`
- `audit/FRONTEND_AUDIT_REPORT.md`
- `audit/TECH_DEBT_REPORT.md`
- `audit/PERFORMANCE_REPORT.md`

Các rủi ro nổi bật (được xác nhận trong code):

- CSRF middleware đang bị tắt trong `app/Http/Kernel.php` (rủi ro rất cao cho toàn bộ form/POST web).
- Nhiều model dùng `protected $guarded = [];` (mass assignment risk).
- RouteServiceProvider không đăng ký `routes/api.php` (API có thể “đang chết”/không truy cập được).
- Payment/VNPay flow có dấu hiệu thiếu route callback và có hardcoded dữ liệu nhạy cảm/không portable.
- Business logic và HTML building nằm trong Controller/Blade (maintainability thấp, khó test, khó scale).

## High Risk Areas

- Checkout / tạo Order / update Order status
- Auth & Social login (Socialite callback)
- Admin permission enforcement (phụ thuộc middleware + kiểm tra URL)
- Upload/media handling + CKFinder/CKEditor integration

## Modernization Priority

- Ưu tiên theo thứ tự: security → workflow consistency → maintainability → scalability → modernization readiness
- Xem:
  - `modernization/MODERNIZATION_ROADMAP.md`
  - `modernization/REFACTOR_PRIORITY.md`
  - `modernization/LARAVEL_BEST_PRACTICES_GAP.md`
  - `modernization/UI_MODERNIZATION_PLAN.md`

## Nơi lưu AI knowledge

- Báo cáo Markdown: `audit/`, `architecture/`, `modernization/`, `knowledge/`
- Dữ liệu RAG JSON: `rag/`

