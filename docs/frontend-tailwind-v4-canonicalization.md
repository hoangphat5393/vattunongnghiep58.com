# Kế hoạch chuẩn hoá class Tailwind v4 (Frontend)

Tài liệu dùng để **lên lịch, thực hiện và theo dõi** việc đổi class theo gợi ý _canonical_ của Tailwind CSS v4 / Tailwind IntelliSense, **không làm lệch giao diện** so với thiết kế hiện tại.

---

## 1. Bối cảnh kỹ thuật

| Hạng mục                                     | Giá trị trong project                                                          |
| -------------------------------------------- | ------------------------------------------------------------------------------ |
| `tailwindcss`                                | `^4.3.0` (`package.json`)                                                      |
| `@tailwindcss/vite`                          | `^4.3.0` — build qua `vite.config.js`                                          |
| Entry CSS chính                              | `resources/css/app.css` (đi kèm Blade frontend qua Vite)                       |
| Cấu hình Tailwind                            | `tailwind.config.js` (`preflight: false` — lưu ý khi so sánh với doc mặc định) |
| CSS legacy (không qua Tailwind JIT của Vite) | `resources/scss/style.scss`, Bootstrap/AdminLTE dưới `public/assets/…`         |

**Phạm vi chuẩn hoá (khuyến nghị):**

- `resources/views/frontend/**/*.blade.php`
- `resources/views/errors/**/*.blade.php` (ví dụ `404.blade.php` nếu dùng class Tailwind)
- Các partial/include được `@include` từ các file trên

**Tách riêng (xử lý khác hoặc giữ nguyên):**

- **Admin / backend** (`resources/views/backend/…`): nhiều chỗ dùng **Bootstrap / AdminLTE** (`flex-grow-1`, `flex-shrink-0` trong CSS vendor). Đây **không** phải utility Tailwind; chỉ đổi khi đã xác nhận layout admin load `app.css` Tailwind và không phụ thuộc class Bootstrap trùng tên.
- **`menu-module/`**, **`index.html` gốc**, file ngoài `content` của Tailwind: chỉ chuẩn hoá nếu đưa vào pipeline Vite hoặc cập nhật `tailwind.config.js` → `content`.

---

## 2. Mục tiêu & tiêu chí “không hư giao diện”

1. **Build sạch:** `pnpm run build` không báo class không tồn tại / lỗi Vite.
2. **Tương đương trực quan:** sau đổi class, so sánh nhanh (mắt + chụp màn hình) các vùng: header, hero, card, form giỏ/checkout, tin tức, footer.
3. **Ưu tiên đổi an toàn trước:** alias đổi tên 1–1 (`flex-grow` → `grow`) trước khi đổi arbitrary → scale (`rounded-[2rem]` → `rounded-4xl`).
4. **Giữ arbitrary khi cần:** nếu scale Tailwind **không trùng** giá trị pixel/design token, **giữ** `[…]` và ghi chú lý do vào mục 6 (bảng theo dõi).

---

## 3. Bảng ánh xạ (canonical) — tham chiếu nhanh

> Đối chiếu thêm với [Tailwind CSS v4](https://tailwindcss.com/docs) khi làm từng batch.

| Gợi ý IntelliSense  | Class cũ / tùy biến     | Class mới (v4)                        | Ghi chú rủi ro UI                                                     |
| ------------------- | ----------------------- | ------------------------------------- | --------------------------------------------------------------------- |
| `grow`              | `flex-grow`             | `grow`                                | Thấp — tương đương                                                    |
| `shrink-0`          | `flex-shrink-0`         | `shrink-0`                            | Thấp                                                                  |
| `bg-linear-to-r`    | `bg-gradient-to-r`      | `bg-linear-to-r` + `from-*` / `to-*`  | Trung bình — kiểm tra gradient hero / nút CTA                         |
| `bg-linear-to-t`    | `bg-gradient-to-t`      | `bg-linear-to-t` …                    | Trung bình — overlay ảnh                                              |
| `rounded-4xl`       | `rounded-[2rem]`        | `rounded-4xl`                         | Kiểm tra: mặc định theme `4xl` = `2rem` (xác nhận trong doc bản lock) |
| `h-120`             | `h-[30rem]`             | `h-120`                               | Kiểm tra: scale spacing (v4) — so chiều cao ảnh hero                  |
| (tuỳ scale)         | `min-h-[3.75rem]`       | ví dụ `min-h-15` _nếu_ khớp `3.75rem` | Chỉ đổi khi đã đối chiếu token                                        |
| Giữ nguyên / review | `shadow-[0_4px_20px_…]` | — hoặc `@theme` / plugin              | Arbitrary phức tạp — đổi sau cùng                                     |

**Không** map nhầm:

- `flex-grow-1` (Bootstrap) ≠ `grow` (Tailwind) trên cùng một hệ stylesheet — cần biết file đang dựa vào CSS nào.

---

## 4. Lộ trình thực hiện (theo batch)

### Batch A — Flex (rủi ro thấp)

- Thay `flex-grow` → `grow`, `flex-shrink-0` → `shrink-0` trên toàn bộ file trong phạm vi mục 1.
- **Kiểm tra:** giỏ hàng, checkout, tin tức, about, search, 404, footer.

### Batch B — Gradient (rủi ro trung bình)

- `home.blade.php`, `news/single.blade.php`: `bg-gradient-to-r` / `bg-gradient-to-t` → `bg-linear-to-r` / `bg-linear-to-t` (giữ nguyên `from` / `via` / `to`).
- **Kiểm tra:** hero, nút CTA, overlay card tin.

### Batch C — Kích thước / bo góc arbitrary (rủi ro trung bình–cao)

- `rounded-[2rem]` → `rounded-4xl` (nếu xác nhận = `2rem`).
- `h-[30rem]` → `h-120` (nếu xác nhận đúng quy đổi theme).
- Các `min-w-[640px]`, `min-h-[120px]`, `max-w-[140px]`: đối chiếu scale hoặc **giữ arbitrary** nếu không có token tương ứng.

### Batch D — Shadow / ring phức tạp

- `sidebar-categories.blade.php` (`shadow-[…]`, `ring-gray-900/[0.06]`): chỉ đụng sau khi A–C ổn định; cân nhắc giữ nguyên hoặc chuyển sang `@theme` trong `app.css` nếu team muốn một token dùng lại.

---

## 5. Quy trình kiểm tra mỗi batch

1. `pnpm run build`
2. (Tuỳ chọn) `pnpm run dev` — F5 các URL: `/`, `/product`, giỏ hàng, checkout, tin tức, trang tĩnh (about), 404
3. Cập nhật **mục 6** — đánh dấu file/batch và ngày
4. PR nhỏ theo batch (dễ review, dễ revert)

---

## 6. Bảng theo dõi (cập nhật tay khi làm)

| Batch | File / nhóm                                                        | Trạng thái               | Người xử lý | Ngày | Ghi chú       |
| ----- | ------------------------------------------------------------------ | ------------------------ | ----------- | ---- | ------------- |
| A     | `cart/cart.blade.php`                                              | ☐ Chưa / ☐ Xong          |             |      |               |
| A     | `checkout/checkout.blade.php`, `completed.blade.php`               | ☐ / ☐                    |             |      |               |
| A     | `news/index.blade.php`                                             | ☐ / ☐                    |             |      |               |
| A     | `search.blade.php`                                                 | ☐ / ☐                    |             |      |               |
| A     | `errors/404.blade.php`                                             | ☐ / ☐                    |             |      |               |
| A     | `layouts/footer.blade.php`                                         | ☐ / ☐                    |             |      |               |
| A     | `page/about.blade.php`                                             | ☐ / ☐                    |             |      |               |
| A     | `cart/*` (cart-table, cart-list, …)                                | ☐ / ☐                    |             |      |               |
| A     | `news/includes/sidebar.blade.php`                                  | ☐ / ☐                    |             |      |               |
| B     | `home.blade.php` (gradient)                                        | ☐ / ☐                    |             |      |               |
| B     | `news/single.blade.php` (gradient)                                 | ☐ / ☐                    |             |      |               |
| C     | `home.blade.php` (`rounded-[2rem]`, `h-[30rem]`, …)                | ☐ / ☐                    |             |      |               |
| C     | `about.blade.php` (`rounded-[2rem]`)                               | ☐ / ☐                    |             |      |               |
| C     | `cart/includes/*`, `product/includes/sidebar-categories.blade.php` | ☐ / ☐                    |             |      |               |
| —     | Backend / Bootstrap (`flex-grow-1`)                                | ☐ Tách / ☐ Đã rõ phạm vi |             |      | Không đổi bừa |

---

## 7. Lệnh grep hỗ trợ rà soát (PowerShell / repo root)

```powershell
# Flex (frontend)
rg "flex-grow|flex-shrink-0" resources/views/frontend resources/views/errors --glob "*.blade.php"

# Gradient
rg "bg-gradient-to-" resources/views/frontend --glob "*.blade.php"

# Arbitrary phổ biến
rg "rounded-\[|h-\[|min-h-\[|min-w-\[|max-w-\[" resources/views/frontend --glob "*.blade.php"
```

Sau khi chuẩn hoá xong một nhóm, chạy lại grep — **kết quả mong đợi:** không còn pattern cũ trong phạm vi đã cam kết (hoặc chỉ còn các chỗ _cố tình_ giữ arbitrary, có ghi chú ở bảng trên).

---

## 8. Liên kết nội bộ

- `tailwind.config.js` — mảng `content` quyết định file nào được quét sinh class.
- `vite.config.js` — plugin `@tailwindcss/vite`.
- `resources/css/app.css` — `@import "tailwindcss"` và `@theme` / `@layer` tùy chỉnh.

---

_Tạo để theo dõi chuẩn hoá FE; cập nhật bảng mục 6 mỗi khi merge batch._
