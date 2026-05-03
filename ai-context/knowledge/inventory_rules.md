# Inventory Rules (Derived) — vattunongnghiep58

> Mức độ xác định: THẤP. Static scan chưa thấy logic trừ tồn kho rõ ràng trong flow tạo order.

## Observations

- Có dấu vết field/khái niệm stock trong code:
  - `CartController@addCart` có comment check `$product->stock < qty` (hiện đang bị comment).
- Admin có module Product CRUD:
  - `routes/admin.php` tạo CRUD routes cho `product`
  - View product edit có include `backend.product.includes.price_stock`

## Likely Intended Rules (CẦN XÁC MINH)

- Stock là thuộc tính trên product hoặc product price variant.
- Khi checkout thành công:
  - nên trừ tồn kho theo từng item (atomic, transactional).
- Khi admin cập nhật trạng thái “đã hủy/hoàn thành”:
  - có thể cần hoàn/trừ tồn kho tuỳ business.

## Gaps / Risks

- Không thấy transaction + lock logic cho inventory.
- Nếu có tồn kho nhưng không enforce ở add-to-cart/checkout:
  - dễ over-sell khi concurrent orders.

