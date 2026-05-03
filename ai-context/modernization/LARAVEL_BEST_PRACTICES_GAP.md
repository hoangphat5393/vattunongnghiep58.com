# Laravel Best Practices Gap — vattunongnghiep58

## Security Baseline

- CSRF enabled (Laravel default): **GAP**
  - Evidence: `app/Http/Kernel.php` comment CSRF middleware.
- Mass assignment protection: **GAP**
  - Evidence: nhiều model dùng `protected $guarded = [];`.
- Rate limiting cho auth & sensitive endpoints: **GAP**
  - Evidence: routes login/social/callback/check-email/check-phone không thấy throttle middleware.

## Routing & Controllers

- Consistent controller references (FQCN + invokable/resources): **GAP**
  - Evidence: `routes/web.php` mix FQCN và string controller.
- Resource routes / REST consistency trong admin: **PARTIAL**
  - Có pattern “resource-like” trong `routes/admin.php` nhưng generate thủ công + foreach.
- Thin controllers / service/action extraction: **GAP**
  - Evidence: `CheckoutController` build HTML/email + order logic.

## Validation

- FormRequest usage cho các form chính: **PARTIAL**
  - Có `CheckoutRequest`, nhưng nhiều nơi dùng `request()->all()` không validate (ví dụ Admin\OrderController).
- Use `$request->validated()` thay vì `$request->all()`: **GAP**

## Eloquent & Data Integrity

- Transactions cho write workflows (order + items): **GAP**
  - Evidence: `CartController@checkoutConfirm` tạo order rồi items không transaction.
- Eager loading để tránh N+1: **UNKNOWN/GAP-RISK**
  - Evidence: access `$this->roles` trong model (lazy load risk); cần audit rộng hơn.
- Typed relationships + casts: **GAP**
  - Nhiều model không thấy `casts()`/typed returns.

## Views (Blade)

- “No logic in Blade”: **GAP**
  - Evidence: nhiều `@php` + `extract()` + parse/unserialize trong views.
- Inline JS/CSS minimized: **GAP**
  - Evidence: nhiều `<script>` blocks trong Blade.

## Integrations

- Use Laravel HTTP client với timeout/retry cho remote calls: **GAP**
  - Evidence: `file_get_contents()` fetch avatar trong social login.
- Config through `.env → config → code`: **GAP**
  - Evidence: PaymentController có hardcoded email/ip.

## Testing

- Core business flow có test coverage: **UNKNOWN**
  - Repo có PHPUnit tests, nhưng cần map coverage vào checkout/admin/ACL.
- Dependency alignment (Pest vs PHPUnit): **GAP**
  - composer dev có Pest nhưng tests folder là PHPUnit.

