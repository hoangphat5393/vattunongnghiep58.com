# 02. LỖI CKFINDER: MÃ 109 ("INVALID REQUEST") VÀ 500 INTERNAL SERVER ERROR

---

## 🏷️ Phân Loại:
> **`[LỖI TRONG CODE DỰ ÁN - SERVICE PROVIDER & CSRF PROTECTION GÓI MỞ RỘNG]`**

---

## 1. Hiện Tượng (Symptom)
- **Lỗi 1 (Mã 109)**: Khi người dùng bấm tạo thư mục mới (`CreateFolder`), xóa thư mục hoặc tải ảnh trong trình quản lý file CKFinder, xuất hiện thông báo lỗi popup: `{"error":{"number":109,"message":"Invalid request."}}`.
- **Lỗi 2 (HTTP 500)**: Khi mở modal CKFinder từ form quản trị sản phẩm/bài viết, trang bị trắng hoặc xuất hiện màn hình báo lỗi `"Oops! An Error Occurred. The server returned a 500 Internal Server Error"`.

---

## 2. Nguyên Nhân Gốc Rễ (Root Causes)

### A. Nguyên nhân Lỗi 109 (CSRF Token Mismatch trong CKFinder)
- Trong file `config/ckfinder.php`, thiết lập mặc định bật kiểm tra token CSRF riêng:
  ```php
  $config['csrfProtection'] = true;
  ```
- Khi chạy CKFinder dưới dạng Iframe / Popup bên trong trang Admin của Laravel, Cookie `ckCsrfToken` nội bộ của CKFinder không đồng bộ với session CSRF của Laravel, khiến CKFinder ném ngoại lệ `InvalidCsrfTokenException` (mã lỗi 109: Invalid request).

### B. Nguyên nhân Lỗi 500 (Service Binding Lifecycle sai)
- Trong gói `vendor/ckfinder/ckfinder-laravel-package/src/CKFinderServiceProvider.php`, binding `ckfinder.connector` bị đặt sai vị trí bên trong phương thức `boot()` và nằm sau khối điều kiện `if ($this->app->runningInConsole()) return;`.
- Do đó, khi ứng dụng Laravel khởi tạo container, service `ckfinder.connector` không được đăng ký vào IoC Container, dẫn đến ngoại lệ `EntryNotFoundException: ckfinder.connector` và làm sập route xử lý connector (HTTP 500).

---

## 3. Cách Khắc Phục (Solutions Applied)

### Bước 1: Tắt kiểm tra CSRF trùng lặp của riêng CKFinder
Trong file `config/ckfinder.php`:
```php
$config['csrfProtection'] = false;
```
*(Bảo mật vẫn được đảm bảo tuyệt đối nhờ Middleware xác thực `CustomCKFinderAuth` và Auth Admin của Laravel).*

### Bước 2: Chuyển Service Binding vào đúng phương thức `register()`
Trong file `CKFinderServiceProvider.php`:
```php
public function register()
{
    $this->mergeConfigFrom(__DIR__ . '/../config/ckfinder.php', 'ckfinder');

    $this->app->bind('ckfinder.connector', function () {
        if (! class_exists('\CKSource\CKFinder\CKFinder')) {
            throw new \Exception("Couldn't find CKFinder connector code.");
        }
        $ckfinderConfig = config('ckfinder');
        return new CKFinder($ckfinderConfig);
    });
}
```

### Bước 3: Hoàn thiện Middleware xác thực `CustomCKFinderAuth`
Cho phép kiểm tra linh hoạt trên các guard của Admin:
```php
config(['ckfinder.authentication' => function () use ($guard) {
    return Auth::guard($guard)->check() || Auth::guard('web')->check() || Auth::check();
}]);
```

### Bước 4: Xóa cache
```bash
php artisan optimize:clear
```
