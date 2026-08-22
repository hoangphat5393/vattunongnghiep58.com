# 03. LỖI CÚ PHÁP BLADE (PARSE ERROR: UNEXPECTED TOKEN ",")

---

## 🏷️ Phân Loại:
> **`[LỖI TRONG CODE DỰ ÁN - CÚ PHÁP BLADE TEMPLATE (BLADE VIEW SYNTAX)]`**

---

## 1. Hiện Tượng (Symptom)
- Khi truy cập vào trang sửa sản phẩm trong Admin (`admin/product/{id}/edit`), trang bị lỗi trắng hoặc xuất hiện màn hình báo lỗi Laravel:
  ```text
  ParseError: syntax error, unexpected token ","
  (View: resources\views\backend\product\single.blade.php)
  ```

---

## 2. Nguyên Nhân Gốc Rễ (Root Cause)
- Trong file `resources/views/backend/product/single.blade.php`, sau khi khai báo các biến mảng cấu hình PHP:
  ```blade
  @php
      $quote_arr = [...];
      $content_arr = [...];
  ```
  Thẻ đóng **`@endphp`** bị thiếu trước khi gọi chỉ thị `@include('backend.partials.quote', $quote_arr)`.
- Trình biên dịch Blade compiler đã nhúng nguyên khối `@include` vào bên trong mã PHP thuần đang mở, dẫn tới việc PHP bắt gặp dấu phẩy `,` không hợp lệ trong câu lệnh và ném ra lỗi cú pháp `ParseError`.

---

## 3. Cách Khắc Phục (Solution Applied)
- Luôn đảm bảo đóng thẻ `@endphp` rõ ràng ngay sau khi kết thúc khối mã PHP trước khi gọi bất kỳ directive Blade nào (`@include`, `@if`, `@foreach`):
  ```blade
  @php
      $quote_arr = [
          'id' => 'description',
          'label' => 'Trích dẫn',
          'name' => 'description',
          'description' => $description ?? '',
      ];
      $content_arr = [
          'id' => 'content',
          'label' => 'Nội dung',
          'name' => 'content',
          'content' => $content ?? '',
      ];
  @endphp
  @include('backend.partials.quote', $quote_arr)
  @include('backend.partials.content', $content_arr)
  ```
- Chạy lệnh làm sạch cache view:
  ```bash
  php artisan optimize:clear
  ```
