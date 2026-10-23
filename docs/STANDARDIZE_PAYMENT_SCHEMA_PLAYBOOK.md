# PLAYBOOK: CHUẨN HÓA CƠ SỞ DỮ LIỆU THANH TOÁN (PAYMENT SCHEMA STANDARDIZATION)
## DỰ ÁN: VẬT TƯ NÔNG NGHIỆP 58 (`vattunongnghiep58`)

> **Loại tài liệu:** Playbook thực thi kỹ thuật (Engineering Execution Playbook)  
> **Áp dụng cho:** Codebase & Database `vattunongnghiep58` (Laravel 13, PostgreSQL 16 & MySQL 8.x Hosting)  
> **Mục tiêu:** Xóa bỏ hoàn toàn tàn dư `ShopPaymentMethod`/`order_payment_methods`, đưa hệ thống về bộ 3 chuẩn TMĐT quốc tế: `payment_methods`, `orders`, `order_payments`  
> **Trạng thái:** Sẵn sàng thực thi (Ready for Execution)  
> **Phiên bản:** 1.0.0 (2026-10-23)

---

## 1. TỔNG QUAN HIỆN TRẠNG & MỤC TIÊU (BASELINE & TARGET)

### 1.1. Hiện trạng trên `vattunongnghiep58` (Current State)
- **Bảng danh mục cổng:** Đang mang tên `order_payment_methods` (commit `7f87eaf7`), gây sai lệch ngữ nghĩa (tiền tố `order_` khiến lầm tưởng là bảng giao dịch đơn hàng).
- **Class Model còn tồn tại:**
  - `app/Models/OrderPaymentMethod.php`
  - `app/Models/ShopPaymentMethod.php` (alias thừa)
  - `app/Models/Backend/ShopPaymentMethod.php` (alias thừa)
- **Bảng `orders`:** Có `total`, `payment_method` (string), `payment_id`, `payment_status` (Enum). **Chưa có `paid_amount`**.
- **Bảng giao dịch đa cổng:** **Chưa có** bảng `order_payments` (chưa hỗ trợ thanh toán nhiều đợt, cọc tiền, hoặc lưu chi tiết payload JSONB).
- **Quy trình đặt hàng thực tế:** Web chạy COD / Hotline xác nhận (`CartController@checkoutConfirm`), hoàn toàn **không phụ thuộc** vào cổng thanh toán online.

### 1.2. Trạng thái mục tiêu chuẩn hóa (Target Architecture)

```
┌─────────────────────────────────┐       ┌─────────────────────────────────┐
│        payment_methods          │       │             orders              │
│  (Danh mục cổng bật/tắt)        │       │  (Đơn hàng tổng thể)            │
│  - code (PK): vnpay, momo, cod  │       │  - id (PK)                      │
│  - name: Tên hiển thị           │       │  - total: Tổng tiền             │
│  - is_active: 1/0               │       │  - paid_amount: Đã thanh toán   │
└────────────────┬────────────────┘       │  - payment_status: Trạng thái   │
                 │                        └────────────────┬────────────────┘
                 │                                         │
                 │        ┌─────────────────────────┐      │
                 └───────>│     order_payments      │<─────┘
                          │  (Nhật ký từng giao dịch)│
                          │  - id (PK)              │
                          │  - order_id (FK)        │
                          │  - gateway (vnpay,...)  │
                          │  - amount: Số tiền lần  │
                          │  - transaction_code     │
                          │  - status (success,...) │
                          │  - payload (JSONB)      │
                          └─────────────────────────┘
```

---

## 2. MA TRẬN ĐỐI CHIẾU THAY ĐỔI (SCHEMA & FILE MATRIX)

| Thành phần | Hiện trạng trên `vattunongnghiep58` | Sau chuẩn hóa | Hành động cụ thể |
| :--- | :--- | :--- | :--- |
| **Bảng danh mục cổng** | `order_payment_methods` | **`payment_methods`** | Migration rename bảng |
| **Bảng giao dịch** | Chưa có | **`order_payments`** | Tạo mới (kèm cột `payload` JSONB) |
| **Bảng đơn hàng** | `orders` (chưa có `paid_amount`) | `orders` (**có `paid_amount`**) | Migration thêm cột `paid_amount` |
| **Model danh mục** | `OrderPaymentMethod.php` | **`PaymentMethod.php`** | Tạo mới class chuẩn |
| **Model giao dịch** | Chưa có | **`OrderPayment.php`** | Tạo mới class chuẩn + cast JSON |
| **Model cũ tàn dư 1** | `app/Models/ShopPaymentMethod.php` | ❌ BỊ XÓA BỎ | Xóa file |
| **Model cũ tàn dư 2** | `app/Models/Backend/ShopPaymentMethod.php` | ❌ BỊ XÓA BỎ | Xóa file |
| **Model cũ tàn dư 3** | `app/Models/OrderPaymentMethod.php` | ❌ BỊ XÓA BỎ | Xóa file |
| **Service Layer** | `CustomerAccountService.php` | Gọi `PaymentMethod` trực tiếp | Cập nhật namespace import |
| **SQL Hosting** | `vattunongnghiep58_hosting_final.sql` | Đồng bộ schema mới | Cập nhật cấu trúc bảng & dump |

---

## 3. LỘ TRÌNH THỰC THI CHI TIẾT (6 GIAI ĐOẠN)

### GIAI ĐOẠN 0: KIỂM TRA TRƯỚC KHI CHẠY (PRE-FLIGHT AUDIT)
- [ ] Chạy kiểm thử hiện tại: `php artisan test --compact` để xác nhận test suite đang xanh 100%.
- [ ] Kiểm tra kết nối cơ sở dữ liệu: `php artisan migrate:status`.

---

### GIAI ĐOẠN 1: DATABASE MIGRATION (DUAL-DRIVER POSTGRESQL & MYSQL)
Tạo file migration: `database/migrations/2026_10_23_235000_standardize_payment_schema.php`

**Nội dung Migration:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Đổi tên bảng danh mục về payment_methods
        if (Schema::hasTable('order_payment_methods') && !Schema::hasTable('payment_methods')) {
            Schema::rename('order_payment_methods', 'payment_methods');
        } elseif (Schema::hasTable('shop_payment_method') && !Schema::hasTable('payment_methods')) {
            Schema::rename('shop_payment_method', 'payment_methods');
        }

        // 2. Bổ sung cột paid_amount vào orders
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'paid_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('paid_amount', 15, 2)->default(0)->after('total');
            });
        }

        // 3. Tạo bảng nhật ký giao dịch order_payments
        if (!Schema::hasTable('order_payments')) {
            Schema::create('order_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->string('gateway', 50)->index();
                $table->decimal('amount', 15, 2);
                $table->string('currency', 10)->default('VND');
                $table->string('transaction_code', 100)->nullable()->index();
                $table->string('status', 30)->default('pending')->index();
                $table->jsonb('payload')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payments');

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'paid_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('paid_amount');
            });
        }

        if (Schema::hasTable('payment_methods') && !Schema::hasTable('order_payment_methods')) {
            Schema::rename('payment_methods', 'order_payment_methods');
        }
    }
};
```
- [ ] Chạy migration: `php artisan migrate`.

---

### GIAI ĐOẠN 2: TẠO MODEL CHUẨN MỚI
- [ ] Tạo Model danh mục [`app/Models/PaymentMethod.php`](file:///e:/web/vattunongnghiep58/app/Models/PaymentMethod.php):
  ```php
  <?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class PaymentMethod extends Model
  {
      public $timestamps = false;
      protected $table = 'payment_methods';
      protected $guarded = [];
  }
  ```

- [ ] Tạo Model giao dịch [`app/Models/OrderPayment.php`](file:///e:/web/vattunongnghiep58/app/Models/OrderPayment.php):
  ```php
  <?php

  namespace App\Models;

  use App\Models\Frontend\Order;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;

  class OrderPayment extends Model
  {
      protected $table = 'order_payments';
      protected $guarded = [];

      protected function casts(): array
      {
          return [
              'payload' => 'array',
              'amount' => 'decimal:2',
              'paid_at' => 'datetime',
          ];
      }

      public function order(): BelongsTo
      {
          return $this->belongsTo(Order::class, 'order_id', 'id');
      }
  }
  ```

- [ ] Cập nhật Model [`app/Models/Frontend/Order.php`](file:///e:/web/vattunongnghiep58/app/Models/Frontend/Order.php):
  Thêm relation:
  ```php
  public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
      return $this->hasMany(\App\Models\OrderPayment::class, 'order_id', 'id');
  }
  ```

---

### GIAI ĐOẠN 3: XÓA SỔ TÀN DƯ VÀ CẬP NHẬT SERVICE LAYER
- [ ] Xóa bỏ 3 file tàn dư cũ:
  - `app/Models/ShopPaymentMethod.php`
  - `app/Models/Backend/ShopPaymentMethod.php`
  - `app/Models/OrderPaymentMethod.php`
- [ ] Cập nhật [`app/Services/CustomerAccountService.php`](file:///e:/web/vattunongnghiep58/app/Services/CustomerAccountService.php):
  - Đổi `use App\Models\OrderPaymentMethod;` ➔ `use App\Models\PaymentMethod;`
  - Đổi `OrderPaymentMethod::query()` ➔ `PaymentMethod::query()`
  - Đổi `OrderPaymentMethod::pluck(...)` ➔ `PaymentMethod::pluck(...)`
- [ ] Cập nhật [`app/Http/Controllers/Account/AccountController.php`](file:///e:/web/vattunongnghiep58/app/Http/Controllers/Account/AccountController.php):
  - Truyền biến `$this->data['payment_methods'] = $this->accountService->activePaymentMethodLabels();`
  - Vẫn giữ alias `$this->data['shop_payment_method']` nếu view cũ còn dùng.

---

### GIAI ĐOẠN 4: CẬP NHẬT TÀI LIỆU & FILE DUMP HOSTING
- [ ] Cập nhật [`docs/TABLE_GLOSSARY.md`](file:///e:/web/vattunongnghiep58/docs/TABLE_GLOSSARY.md):
  - Bảng `payment_methods`: Model `PaymentMethod`.
  - Bảng `order_payments`: Model `OrderPayment`.
  - Bảng `orders`: Có thêm `paid_amount`.
- [ ] Cập nhật file SQL hosting [`vattunongnghiep58_hosting_final.sql`](file:///e:/web/vattunongnghiep58/vattunongnghiep58_hosting_final.sql):
  - Đổi tên bảng `order_payment_methods` ➔ `payment_methods`.
  - Thêm định nghĩa bảng `order_payments`.
  - Thêm cột `paid_amount` trong `orders`.

---

### GIAI ĐOẠN 5: KIỂM THỬ TỰ ĐỘNG & CHUẨN HÓA CODE STYLE
- [ ] Cập nhật test [`tests/Feature/CustomerOrderOwnershipTest.php`](file:///e:/web/vattunongnghiep58/tests/Feature/CustomerOrderOwnershipTest.php) (tạo bảng `payment_methods` thay cho `order_payment_methods`).
- [ ] Chạy test cụ thể: `php artisan test --filter=CustomerOrderOwnershipTest`.
- [ ] Chạy toàn bộ test suite: `php artisan test --compact`.
- [ ] Chạy Laravel Pint: `vendor/bin/pint --dirty --format agent`.
- [ ] Cập nhật Code Graph: `graphify update .`.

---

### GIAI ĐOẠN 6: KẾ HOẠCH PHỤC HỒI NẾU CÓ SỰ CỐ (ROLLBACK PLAN)
- Nếu có lỗi xảy ra: `php artisan migrate:rollback --step=1`.
- Toàn bộ code cũ có thể khôi phục tức thì qua Git: `git restore .`.
