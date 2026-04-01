# Danh sách đề xuất cải thiện hệ thống (Vật Tư Nông Nghiệp 58)

Dưới đây là các đề xuất cải thiện được rút ra từ quá trình phân tích hệ thống, nhằm nâng cao tính bảo trì, hiệu năng và bảo mật.

## 1. Chuẩn hóa Cơ sở dữ liệu và Model
- **Vấn đề**: Tên bảng đơn hàng bị sai chính tả (`addtocard`) và không đồng nhất với code (sử dụng `cart_id`, `cart_status`, v.v.).
- **Đề xuất**: 
    - Rename bảng `addtocard` thành `shop_orders`.
    - Rename bảng `addtocard_detail` thành `shop_order_items`.
    - Rename các cột trong database để khớp với logic code hiện có (ví dụ: `id` -> `cart_id`, `status` -> `cart_status`).
    - Cập nhật các Model tương ứng ([Order.php](file:///e:/web/vattunongnghiep58/app/Models/Backend/Order.php), [OrderItem.php](file:///e:/web/vattunongnghiep58/app/Models/Backend/OrderItem.php)).
- **Trạng thái**: **Đã hoàn thành**. (Đã cập nhật cả tên bảng, tên cột và Model).

## 2. Áp dụng Form Request Validation
- **Vấn đề**: Logic kiểm tra dữ liệu đầu vào nằm trực tiếp trong Controller.
- **Đề xuất**: Tạo các lớp `FormRequest` riêng biệt (ví dụ: `CheckoutRequest`).
- **Trạng thái**: **Đã hoàn thành**.

## 3. Tối ưu hóa Truy vấn (N+1 Query)
- **Vấn đề**: 
    - Truy cập quan hệ `$product->user->name` trong vòng lặp Backend mà không sử dụng Eager Loading.
    - Thực hiện truy vấn DB trực tiếp trong Blade views (`home.blade.php`, `product/index.blade.php`) gây ra N+1 query nghiêm trọng (truy vấn sản phẩm cho từng danh mục trong vòng lặp).
- **Đề xuất**: 
    - Sử dụng `with(['user', 'categories'])` trong Backend Controllers.
    - Chuyển toàn bộ logic truy vấn từ View sang Controller và sử dụng Eager Loading cho các quan hệ sản phẩm/danh mục.
- **Trạng thái**: **Đã hoàn thành**. (Đã tối ưu cả Backend và Frontend, loại bỏ hoàn toàn DB query trong views).

## 4. Chuẩn hóa Quản lý Package (Node.js)
- **Vấn đề**: Xung đột giữa nhiều file lock (npm, pnpm).
- **Đề xuất**: Chỉ sử dụng `pnpm-lock.yaml` và Vite.
- **Trạng thái**: **Đã hoàn thành**. (Đã xóa `package-lock.json`).

## 5. Tăng cường Bảo mật
- **Vấn đề**: Một số endpoint chưa có validation chặt chẽ hoặc CSRF protection cho AJAX. Đặc biệt là `ajax_quickchange` cho phép cập nhật dữ liệu tùy ý.
- **Đề xuất**: 
    - Áp dụng Validation cho các phương thức AJAX.
    - Whitelist các Model và Column được phép cập nhật qua `ajax_quickchange`.
- **Trạng thái**: **Đã hoàn thành**. (Đã thêm validation và whitelist cho các phương thức trong `AjaxController`).

## 6. Cải thiện Kiến trúc Frontend (Mới)
- **Vấn đề**: Sử dụng Data Transformation Trait nhưng chưa nhất quán giữa các trang.
- **Đề xuất**: Mở rộng `FrontendDataTransform` để bao quát tất cả các loại dữ liệu hiển thị, giúp Blade view sạch sẽ hơn và dễ bảo trì.
- **Trạng thái**: Đang thực hiện. (Đã áp dụng cho Home và Product List).
