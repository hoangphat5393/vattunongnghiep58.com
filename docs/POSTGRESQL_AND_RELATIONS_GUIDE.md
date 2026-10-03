# TÀI LIỆU QUAN HỆ CƠ SỞ DỮ LIỆU & CHIẾN LƯỢC NÂNG CẤP POSTGRESQL

> **Dự án áp dụng:** `vattunongnghiep58`, `3nong`, `salondungtokyo`  
> **Cập nhật:** Tháng 09/2026  
> **Mục đích:** Lưu trữ kết quả phân tích hiện trạng quan hệ các bảng trong Database, đánh giá tác động hệ thống CRUD, chiến lược di chuyển an toàn sang PostgreSQL và ví dụ chi tiết về truy vấn đệ quy (`WITH RECURSIVE`).

---

## PHẦN 1: HIỆN TRẠNG QUAN HỆ CÁC BẢNG (DATABASE RELATIONS AUDIT)

### 1. Ở tầng Cơ sở dữ liệu vật lý (MySQL Engine)

- **Tình trạng:** **KHÔNG CÓ ràng buộc khóa ngoại cứng (Foreign Key Constraints)**.
- **Minh chứng:** Truy vấn hệ thống `INFORMATION_SCHEMA.KEY_COLUMN_USAGE` lọc theo `REFERENCED_TABLE_NAME IS NOT NULL` trên DB `vattunnongnghiep58` trả về kết quả rỗng (`0 foreign keys`).
- **Lưu ý:** Các index có đuôi `_foreign` (như `product_category_category_id_foreign`, `addtocard_detail_addtocard_id_foreign`) thực chất chỉ là **Normal B-Tree Index** nhằm tối ưu tốc độ tìm kiếm, không phải ràng buộc khóa ngoại ép cứng.

### 2. Ở tầng Ứng dụng (Laravel Eloquent Model)

- **Tình trạng:** **ĐÃ NỐI ĐẦY ĐỦ QUAN HỆ** qua các phương thức của Eloquent:
  - **Sản phẩm & Giá đa biến thể:** `Product` `hasMany` `ProductPrice` ↔ `ProductPrice` `belongsTo` `Product`.
  - **Sản phẩm & Danh mục:** `Product` `belongsToMany` `Category` (qua bảng trung gian `product_categories`).
  - **Đơn hàng & Chi tiết:** `Order` (`shop_orders`) `hasMany` `OrderItem` (`shop_order_items`) qua khóa `cart_id`.
  - **Danh mục cha - con:** `Category` tự liên kết đệ quy (`hasMany` danh mục con, `belongsTo` danh mục cha qua `parent`).
  - **Menu & Menu Items:** `Menu` `hasMany` `MenuItems` và `MenuItems` `belongsTo` `Menu`.
  - **Phân quyền người dùng:** `User` ↔ `Role` ↔ `Permission` (qua `role_user` và `permission_role`).
  - **Album & Media:** `Album` `hasMany` `AlbumItem`.

### 3. Tác động tới hệ thống CRUD hiện tại

- **Hệ thống CRUD hiện tại chạy mượt mà, linh hoạt:**
  - Không lo lỗi ràng buộc toàn vẹn khi nhập/xuất dữ liệu, import Excel hay khôi phục backup.
  - Tốc độ Create/Update cao do không phải kiểm tra FK đa bảng ở tầng MySQL.
- **Điểm cần chú ý khi XÓA (Delete):**
  - Cần xử lý xóa kèm dữ liệu liên quan ở bảng con trong Controller hoặc Eloquent Model Event `booted() / deleting()` để tránh sót lại bản ghi mồ côi (orphan records).

### 4. Cảnh báo: KHÔNG NÊN thêm khóa ngoại cứng vào MySQL lúc này

Nếu tự ý chạy migration thêm `foreignId()->constrained()` vào MySQL hiện tại sẽ gặp lỗi nghiêm trọng:

1. **Lệch kiểu dữ liệu:** Cột `products.id` là `BIGINT UNSIGNED`, trong khi `product_categories.product_id` và `shop_order_items.product_id` lại là `INT` thường → MySQL từ chối tạo khóa ngoại 100%.
2. **Dữ liệu cũ:** Nếu có dòng mồ côi từ trước, MySQL sẽ chặn không cho add constraint.
3. **Nguy cơ xóa mất lịch sử đơn hàng:** Nếu cấu hình `ON DELETE CASCADE`, khi xóa một sản phẩm, MySQL sẽ tự động xóa sạch các dòng trong `shop_order_items` của đơn hàng cũ, gây sai lệch báo cáo doanh thu.

---

## PHẦN 2: CHIẾN LƯỢC CHUYỂN ĐỔI SANG POSTGRESQL

> **Nguyên tắc vàng:** **CHUYỂN TOÀN BỘ DỮ LIỆU SANG TRƯỚC → CHẠY TEST ỔN ĐỊNH → MỚI CÂN NHẮC NỐI KHÓA NGOẠI SAU.**

### Quy trình 4 bước chuẩn:

1. **Bước 1 (Schema & Bulk Data):** Dùng công cụ (`pgloader`, `DBeaver`, script) chuyển toàn bộ bảng và dữ liệu thô sang PostgreSQL (không bật constraint).
2. **Bước 2 (Keys & Sequences):** Thiết lập Primary Key, Sequence (`BIGSERIAL / IDENTITY`) và các Index tìm kiếm thông thường.
3. **Bước 3 (Cấu hình & Kiểm thử Laravel):**
   - Đổi `.env`: `DB_CONNECTION=pgsql`, `DB_PORT=5432`.
   - Bật extension `pdo_pgsql` trong `php.ini`.
   - Kiểm tra toàn bộ tính năng CRUD, giỏ hàng, xem chi tiết sản phẩm.
4. **Bước 4 (Dọn dẹp & Nối dây tùy chọn):**
   - Sau khi hệ thống vận hành trơn tru, nếu thực sự muốn khóa ngoại cứng: rà soát xóa bản ghi mồ côi, đồng bộ kiểu dữ liệu rồi mới thêm `ALTER TABLE ... ADD CONSTRAINT FOREIGN KEY`.

---

## PHẦN 3: LỢI ÍCH VƯỢT TRỘI CỦA POSTGRESQL ĐỐI VỚI DỰ ÁN

| STT | Lợi thế kỹ thuật | Ứng dụng thực tế | Tác động hiệu năng |
| :---: | :--- | :--- | :--- |
| **1** | **AI Vector Search (`pgvector`)** | Embeddings sâu bệnh & thuốc cho Chatbot AI | Tìm kiếm ngữ nghĩa trực tiếp, bỏ Vector DB ngoài |
| **2** | **Full-Text Search (Chỉ mục GIN)** | Tìm kiếm tên thuốc, hoạt chất, sâu bệnh hại | Nhanh hơn 5-10 lần, hỗ trợ xếp hạng `ts_rank` |
| **3** | **Đệ quy danh mục (`WITH RECURSIVE`)** | Duyệt cây danh mục nông nghiệp đa cấp (3-5 cấp) | **Chỉ 1 query duy nhất**, loại bỏ lỗi N+1 queries |
| **4** | **Lưu trữ & Truy vấn JSONB** | Lưu metadata sản phẩm, gallery, settings | Index GIN nhị phân, truy vấn siêu tốc |
| **5** | **Xử lý đồng thời (MVCC)** | Khách đặt hàng, trừ tồn kho lúc cao điểm | Không khóa bảng (no table lock), chống deadlock |

---

## PHẦN 4: VÍ DỤ CỤ THỂ VỀ TRUY VẤN ĐỆ QUY (`WITH RECURSIVE`)

### Bài toán

Cây danh mục 4 cấp:

- `Cây ăn trái` (ID: 1, parent: 0)
  - `Sầu riêng` (ID: 2, parent: 1)
    - `Giai đoạn nuôi trái` (ID: 3, parent: 2)
      - `Phân bón lá / Kích rễ` (ID: 4, parent: 3)

### 1. Truy vấn lấy toàn bộ danh mục con - cháu - chắt từ ID cha

```sql
WITH RECURSIVE category_tree AS (
    -- [1] Điểm neo: Lấy danh mục gốc
    SELECT id, name, parent, 1 as level
    FROM categories
    WHERE id = 1

    UNION ALL

    -- [2] Đệ quy: Tự động tìm tất cả cấp con cháu
    SELECT c.id, c.name, c.parent, ct.level + 1
    FROM categories c
    JOIN category_tree ct ON c.parent = ct.id
)
SELECT * FROM category_tree;
```

### 2. Kết hợp lấy toàn bộ sản phẩm của toàn cây danh mục

```sql
WITH RECURSIVE category_tree AS (
    SELECT id FROM categories WHERE id = 1
    UNION ALL
    SELECT c.id FROM categories c JOIN category_tree ct ON c.parent = ct.id
)
SELECT * FROM products
WHERE id IN (
    SELECT product_id FROM product_categories
    WHERE category_id IN (SELECT id FROM category_tree)
);
```

### 3. Tạo thanh điều hướng Breadcrumb tự động

Từ danh mục con cấp 4, leo ngược về danh mục cha:

```sql
WITH RECURSIVE breadcrumb_tree AS (
    SELECT id, name, parent, CAST(name AS VARCHAR(1000)) as full_path
    FROM categories
    WHERE id = 4

    UNION ALL

    SELECT c.id, c.name, c.parent, CAST(c.name || ' > ' || bt.full_path AS VARCHAR(1000))
    FROM categories c
    JOIN breadcrumb_tree bt ON c.id = bt.parent
)
SELECT full_path FROM breadcrumb_tree WHERE parent = 0;
```

_Kết quả:_ `"Cây ăn trái > Sầu riêng > Giai đoạn nuôi trái > Phân bón lá"`.

### 4. Tích hợp gọn gàng vào Laravel Model Scope

```php
// app/Models/Category.php
public static function getTreeIds(int $parentId): array
{
    $sql = "
        WITH RECURSIVE category_tree AS (
            SELECT id FROM categories WHERE id = ?
            UNION ALL
            SELECT c.id FROM categories c JOIN category_tree ct ON c.parent = ct.id
        )
        SELECT id FROM category_tree
    ";

    return collect(DB::select($sql, [$parentId]))->pluck('id')->toArray();
}

// Tại Controller chỉ cần gọi:
$categoryIds = Category::getTreeIds($categoryId);
$products = Product::whereHas('categories', function($q) use ($categoryIds) {
    $q->whereIn('categories.id', $categoryIds);
})->paginate(20);
```

---

## PHẦN 5: CHỊU TẢI ĐỒNG THỜI (MVCC) & PHÒNG CHỐNG DEADLOCK

### 1. Khóa bi quan (Pessimistic Locking) & Vấn đề trên MySQL
* MySQL khi đặt hàng thường dùng `SELECT ... FOR UPDATE` (Khóa bi quan) để giữ chặt dòng sản phẩm, buộc các khách hàng khác phải xếp hàng chờ.
* Khi lượng truy cập tăng vọt (Flash Sale đầu vụ mùa), hàng đợi quá tải dẫn đến lỗi:  
  `SQLSTATE[HY000]: Lock wait timeout exceeded; try restarting transaction`.

### 2. Deadlock (Khóa chết vòng tròn)
* **Tình huống:** Khách 1 mua `[A + B]`, Khách 2 mua `[B + A]`. Khách 1 giữ A chờ B; Khách 2 giữ B chờ A → Hai giao dịch khóa chết nhau.
* MySQL buộc phải Rollback đơn hàng của 1 khách và ném lỗi 500:  
  `SQLSTATE[40001]: Serialization failure: 1213 Deadlock found when trying to get lock`.

### 3. Giải pháp vượt trội với MVCC của PostgreSQL
* **Nguyên lý:** "Đọc không khóa Ghi, Ghi không khóa Đọc". Người xem sản phẩm đọc trên Snapshot dữ liệu độc lập, không bao giờ bị nghẽn bởi người đang đặt hàng.
* **Trừ kho nguyên tử 1 bước an toàn với `RETURNING`:**
  ```sql
  UPDATE products 
  SET stock = stock - 1 
  WHERE id = 10 AND stock > 0
  RETURNING stock;
  ```
  * Cập nhật và kiểm tra kho đồng thời, triệt tiêu nguy cơ bán lố (overselling) và loại bỏ hoàn toàn Deadlock.

---

## PHẦN 6: KẾT LUẬN & KIẾN NGHỊ

1. **Hiện tại:** Giữ nguyên mô hình quan hệ quản lý qua Eloquent Model của Laravel, không ép khóa ngoại cứng vào MySQL để đảm bảo CRUD và đơn hàng vận hành an toàn.
2. **Khi chuyển đổi sang PostgreSQL:** Có thể thực hiện bất cứ khi nào cần mở rộng AI Vector Search (`pgvector`), tối ưu truy vấn đệ quy cây danh mục (`WITH RECURSIVE`) hoặc xử lý tải cao đặt hàng (`MVCC`). Mã nguồn backend của dự án đã sẵn sàng tương thích 100%.
