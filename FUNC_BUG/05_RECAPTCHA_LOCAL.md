# 05. LỖI "HỆ THỐNG PHÁT HIỆN HÀNH VI BẤT THƯỜNG" KHI GỬI FORM LIÊN HỆ TRÊN MÔI TRƯỜNG LOCAL / DEV

---

## 🏷️ Phân Loại:
> **`[LỖI CODE DỰ ÁN & MÔI TRƯỜNG - GOOGLE RECAPTCHA V3 TRÊN TÊN MIỀN NỘI BỘ]`**

---

## 1. Hiện Tượng (Symptom)
- Khi người dùng nhập đầy đủ thông tin vào Form liên hệ trên môi trường local (ví dụ: `https://3nong.test/lien-he` hoặc `https://vattunongnghiep58.test/lien-he`) và nhấn nút **Gửi**.
- Trình duyệt hiện thông báo lỗi:
  > **`"Hệ thống phát hiện hành vi bất thường, vui lòng thử lại sau."`** (hoặc `You are most likely a bot`).

---

## 2. Nguyên Nhân Gốc Rễ (Root Cause)

1. **Google reCAPTCHA v3 kiểm tra Domain Whitelist**:
   - Khóa Google reCAPTCHA v3 (`RECAPTCHAV3_SITEKEY` / `RECAPTCHAV3_SECRET`) chỉ được cấp phép cho các domain chính thức (production).
   - Khi chạy trên tên miền ảo nội bộ (`.test`, `localhost`), Google API trả về phản hồi không hợp lệ (`hostname-mismatch` / `invalid-input-response`), khiến hàm `RecaptchaV3::verify()` trả về boolean `false`.

2. **Ép kiểu ngầm định trong PHP (`false < 0.3`)**:
   - Trong controller:
     ```php
     $score = RecaptchaV3::verify($recaptchaToken, 'contact'); // Trả về false
     if ($score < 0.3) {
         // Trong PHP: (int)false = 0, do đó 0 < 0.3 => TRUE!
         return redirect()->back()->withErrors('Hệ thống phát hiện hành vi bất thường, vui lòng thử lại sau.');
     }
     ```
   - Do `false < 0.3` luôn là `true`, mọi lượt submit form trên môi trường local/dev đều bị coi là Bot.

---

## 3. Cách Khắc Phục Triệt Để (Solutions Applied)

### Bước 1: Chỉ bắt buộc xác thực Google reCAPTCHA trên môi trường Production
- Kiểm tra điều kiện `app()->environment('production')`:
  ```php
  $score = 1.0;
  $recaptchaSecret = config('recaptchav3.secret');
  if (! empty($recaptchaSecret) && app()->environment('production')) {
      $recaptchaToken = $request->get('g-recaptcha-response');
      if (! empty($recaptchaToken) && class_exists('Lunaweb\RecaptchaV3\Facades\RecaptchaV3')) {
          try {
              $verified = RecaptchaV3::verify($recaptchaToken, 'contact');
              $score = is_numeric($verified) ? (float) $verified : 0.0;
          } catch (\Throwable $e) {
              report($e);
              $score = 1.0; // Fallback an toàn nếu Google API bị nghẽn
          }
      } else {
          $score = 0.0;
      }
  }

  if ($score < 0.3) {
      $botMsg = 'Hệ thống phát hiện hành vi bất thường, vui lòng thử lại sau.';
      ...
  }
  ```

### Bước 2: Đồng bộ giải pháp cho cả 2 dự án (`3nong` và `vattunongnghiep58`)
- Áp dụng logic an toàn này cho `ContactController`, `CartController`, và `RegisterController`.
- Khi ở môi trường `local` hoặc `testing`, form hoạt động mượt mà mà không bị chặn nhầm.
