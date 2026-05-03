# Security Rules (Target State) — vattunongnghiep58

## Web Security Baseline

- CSRF:
  - Bắt buộc bật CSRF middleware cho web routes.
  - Chỉ whitelist endpoint thực sự cần (ví dụ webhook) và phải có signature/secret verify.
- Auth throttling:
  - Throttle login/register/forgot/social callback và các endpoint validation (check email/phone).
- Output escaping:
  - Ưu tiên `{{ }}` trong Blade; hạn chế `{!! !!}`.

## Data Integrity

- Order creation phải transactional:
  - tạo order + items trong 1 transaction.
  - idempotency key cho payment callback.
- Order status transitions:
  - validate transitions (state machine hoặc rule table).

## Mass Assignment & Validation

- Model nhạy cảm phải có `$fillable` tối thiểu.
- Controller phải dùng FormRequest/validated input; hạn chế `request()->all()`.

## File Upload

- Upload phải validate:
  - MIME, extension, size, storage path
  - disallow executable types
- CKFinder/CKEditor:
  - đảm bảo route/middleware/ACL chặt chẽ
  - disable public write access

## Third-party calls

- Dùng Laravel HTTP client với:
  - timeout/connectTimeout
  - retry/backoff
  - allowlist domain nếu fetch remote resource (social avatar)

## Admin Ops Endpoints

- Không để endpoint maintenance/debug trong routes public.
- Nếu cần:
  - restrict theo `auth:admin` + permission
  - thêm environment guard (chỉ local)

