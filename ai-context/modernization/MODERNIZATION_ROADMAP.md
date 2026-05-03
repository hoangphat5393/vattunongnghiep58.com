# Modernization Roadmap — vattunongnghiep58

> Roadmap này dựa trên audit hiện tại. Không refactor ngay trong phiên này; mục tiêu là chuẩn bị lộ trình có thể triển khai dần, an toàn.

## Phase 0 — Stabilize & Secure (P0/P1)

- Bật lại CSRF protection cho web.
- Khoá hoặc loại bỏ route maintenance `/admin/cc` khỏi public access.
- Audit mass assignment: thiết lập `$fillable` tối thiểu cho model nhạy cảm.
- Add throttling cho auth endpoints + “check email/phone”.
- Verify route registration:
  - quyết định bật lại `routes/api.php` hay không; nếu bật thì thêm auth/rate limit phù hợp.

## Phase 1 — Workflow Consistency

- Chọn và chuẩn hoá “Order domain”:
  - migrate triệt để sang `shop_orders` + `shop_order_items` (Order/OrderItem), hoặc
  - giữ legacy Addtocard nhưng normalize naming/schema (không khuyến nghị)
- Chuẩn hoá order status/payment status:
  - mapping constants/enums
  - validate transitions trong admin update
- Hoàn thiện payment callback flows:
  - VNPay return route + verify signature + idempotent update
  - Decide Stripe/PayPal roadmap: remove dead code hoặc add missing deps + implement đúng chuẩn

## Phase 2 — Architecture Cleanup (Maintainability)

- Tách nghiệp vụ thành action/service layer:
  - CreateOrder (transactional)
  - UpdateOrderStatus (with validation)
  - SendOrderEmails (queued)
  - SocialLoginProvisionUser (hardened)
- Giảm logic trong Blade:
  - chuyển data prep về controller/view composer
  - componentize repeated blocks

## Phase 3 — Performance & Scalability

- N+1 audit và eager loading:
  - admin list pages, product detail, customer order detail
- Cache strategy cho read-heavy:
  - settings/options, category tree, menu
- Queue strategy:
  - email notifications, heavy processing

## Phase 4 — UI Modernization

- Thực hiện theo `UI_MODERNIZATION_PLAN.md`
- Dọn legacy assets theo usage thật, giảm bundle size

## Phase 5 — Observability & Quality

- Thiết lập baseline metrics:
  - error rate, latency, slow queries, queue health
- Tăng test coverage cho workflows critical:
  - checkout, admin order update, auth flows, ACL enforcement

