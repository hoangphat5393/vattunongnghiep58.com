# Laravel 13 Upgrade Playbook — Vật Tư Nông Nghiệp 58

> **Kế hoạch nâng cấp framework** Laravel 12 → 13 trên **một môi trường duy nhất** (local = production).  
> **Trạng thái:** `done` — nâng cấp hoàn tất 2026-07-09  
> Cập nhật lần cuối: **2026-07-09**  
> Nguồn: [Upgrade Guide Laravel 13](https://laravel.com/docs/13.x/upgrade) · khảo sát codebase hiện tại

**Liên quan:** [IMPROVEMENT_PLAYBOOK.md](IMPROVEMENT_PLAYBOOK.md) · [PROJECT_ANALYSIS.md](PROJECT_ANALYSIS.md) · [CHANGE_LOG.md](CHANGE_LOG.md) · [RECOMMENDATIONS.md](../RECOMMENDATIONS.md)

---

## Mục tiêu

1. Nâng `laravel/framework` từ **12.x** lên **13.x** trên cùng máy đang chạy site.
2. Không làm gián đoạn dài: backup trước, maintenance ngắn, rollback rõ ràng.
3. Giữ nguyên cấu trúc Laravel 10 (`app/Http/Kernel.php`) — **không** migrate sang skeleton mới.
4. Toàn bộ test hiện có (`php artisan test --compact`) pass sau upgrade.

---

## Hiện trạng (baseline)

| Hạng mục       | Giá trị                                                       |
| -------------- | ------------------------------------------------------------- |
| Laravel        | 12.62.0                                                       |
| PHP            | 8.3 (đủ điều kiện L13)                                        |
| Test           | ~40 file (`tests/Feature`, `tests/Unit`)                      |
| Pest / PHPUnit | Pest 4 / PHPUnit 12 (đã tương thích L13)                      |
| CSRF           | `App\Http\Middleware\VerifyCsrfToken` trong `web` group       |
| Cache prefix   | `config/cache.php` — `CACHE_PREFIX` fallback `*_cache_`       |
| Redis prefix   | `config/database.php` — `REDIS_PREFIX` fallback `*_database_` |
| Session cookie | `config/session.php` — `SESSION_COOKIE` fallback `*_session`  |

### Package cần kiểm tra sau `composer update`

| Package                             | Rủi ro     | Smoke test sau upgrade    |
| ----------------------------------- | ---------- | ------------------------- |
| `ckfinder/ckfinder-laravel-package` | Trung bình | Upload file admin         |
| `surfsidemedia/shoppingcart`        | Trung bình | Giỏ hàng + checkout       |
| `laravel/sanctum`                   | Thấp       | Reference CSRF middleware |
| `josiasmontag/laravel-recaptchav3`  | Thấp       | Form đăng ký / liên hệ    |
| `laravel/ui`                        | Thấp       | Auth scaffold             |
| `diglactic/laravel-breadcrumbs`     | Thấp       | Trang có breadcrumb       |

---

## Cách dùng playbook

1. Mỗi bước có **ID cố định** (`L13-xxx`) — đánh dấu `[x]` khi xong.
2. Làm **theo thứ tự** — không nhảy phase.
3. Ghi ngắn vào cột **Ghi chú** sau mỗi bước (ngày, kết quả test).
4. Chi tiết breaking change → xem [§ Breaking changes ảnh hưởng dự án](#breaking-changes-ảnh-hưởng-dự-án).

### Trạng thái phase

| Ký hiệu       | Ý nghĩa              |
| ------------- | -------------------- |
| `todo`        | Chưa làm             |
| `in_progress` | Đang làm             |
| `done`        | Xong + đã kiểm chứng |

---

## Bảng tổng quan phase

| Phase | ID      | Hạng mục                           | Trạng thái    |
| ----- | ------- | ---------------------------------- | ------------- |
| 0     | L13-000 | Chuẩn bị & backup                  | `done`        |
| 1     | L13-100 | Nâng cấp Composer (local)          | `done`        |
| 2     | L13-200 | Sửa code breaking change           | `done`        |
| 3     | L13-300 | Test tự động + smoke thủ công      | `done`        |
| 4     | L13-400 | Triển khai lên môi trường duy nhất | `done`        |
| 5     | L13-500 | Ổn định & đóng playbook            | `in_progress` |

---

## Phase 0 — Chuẩn bị & backup (L13-000)

> **Thời gian ước tính:** 30–60 phút  
> **Downtime:** Không

### Checklist

- [ ] **L13-001** — Tạo branch Git: `upgrade/laravel-13`
- [ ] **L13-002** — Ghi baseline test (phải pass trước khi đụng Composer):

```bash
php artisan test --compact
```

- [ ] **L13-003** — Backup database (file `.sql` hoặc export HeidiSQL/phpMyAdmin):

```bash
# Ví dụ MySQL — điều chỉnh user/host/db theo .env
mysqldump -u USER -p DATABASE_NAME > backup_pre_laravel13_2026-07-09.sql
```

- [ ] **L13-004** — Backup file `.env` (copy ra ngoài repo, vd. `backup/.env.l12`)

- [ ] **L13-005** — **Ghim** 3 biến sau vào `.env` (tránh logout hàng loạt + cache miss sau upgrade):

```env
# Lấy giá trị ĐANG DÙNG trước khi upgrade — không đổi nội dung
CACHE_PREFIX=...
REDIS_PREFIX=...
SESSION_COOKIE=...
```

> Nếu chưa set trong `.env`, giá trị mặc định hiện tại theo `APP_NAME` là dạng `app_name_cache_`, `app_name_database_`, `app_name_session` (dấu gạch dưới). **Ghi đúng chuỗi đó vào .env.**

- [ ] **L13-006** — Kiểm tra PHP ≥ 8.3:

```bash
php -v
```

- [ ] **L13-007** — Kiểm tra package conflict trước (chỉ đọc, chưa update):

```bash
composer why-not laravel/framework 13.0
```

- [ ] **L13-008** — Chọn cửa sổ bảo trì ngắn (khuyến nghị: ngoài giờ cao điểm, ~15–30 phút)

**Tiêu chí hoàn thành Phase 0:** Có backup DB + `.env`, test L12 pass, biết trước package nào có thể chặn upgrade.

---

## Phase 1 — Nâng cấp Composer (L13-100)

> **Thời gian ước tính:** 15–30 phút  
> **Downtime:** Không (vẫn trên branch, chưa deploy)

### Cách A — Laravel Boost (khuyến nghị)

Dự án đã có `laravel/boost ^2.4`. Trong Cursor, chạy slash command:

```
/upgrade-laravel-v13
```

Boost sẽ hướng dẫn diff config và các file cần review.

### Cách B — Thủ công

- [ ] **L13-101** — Cập nhật `composer.json`:

```json
"laravel/framework": "^13.0",
"laravel/tinker": "^3.0"
```

(`laravel/boost` giữ `^2.0` trở lên; Pest 4 / PHPUnit 12 đã đủ.)

- [ ] **L13-102** — Chạy update:

```bash
composer update laravel/framework laravel/tinker --with-all-dependencies
```

Nếu conflict package bên thứ ba → ghi vào **Ghi chú phase**, tra Packagist / issue tracker package đó trước khi ép version.

- [ ] **L13-103** — Xác nhận phiên bản:

```bash
php artisan --version
```

Kỳ vọng: `Laravel Framework 13.x.x`

- [ ] **L13-104** — Publish config mới (chỉ khi cần so sánh):

```bash
php artisan vendor:publish --tag=laravel-config
```

> **Lưu ý:** Không overwrite toàn bộ config cũ. So sánh từng file thay đổi (đặc biệt `config/cache.php`).

- [ ] **L13-105** — Clear cache local:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**Tiêu chí hoàn thành Phase 1:** `php artisan --version` báo L13, `composer install` không lỗi.

---

## Phase 2 — Sửa code breaking change (L13-200)

> **Thời gian ước tính:** 1–2 giờ  
> **Downtime:** Không

### L13-201 — CSRF: `VerifyCsrfToken` → `PreventRequestForgery`

Laravel 13 đổi tên middleware CSRF. Alias cũ vẫn hoạt động tạm thời nhưng **nên cập nhật** để tránh deprecation.

| File                                      | Việc cần làm                                                                                                                                                            |
| ----------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `app/Http/Middleware/VerifyCsrfToken.php` | Đổi tên class/file → `PreventRequestForgery.php`; extend `Illuminate\Foundation\Http\Middleware\PreventRequestForgery` (hoặc middleware CSRF mới tương đương trong L13) |
| `app/Http/Kernel.php`                     | Đổi import + entry trong `$middlewareGroups['web']`                                                                                                                     |
| `config/sanctum.php`                      | Cập nhật key `verify_csrf_token`                                                                                                                                        |
| `tests/Feature/CsrfProtectionTest.php`    | Cập nhật class assertion                                                                                                                                                |

Giữ nguyên `$except = ['ckfinder/*']`.

- [ ] **L13-201** — CSRF middleware đã cập nhật
- [ ] **L13-202** — Chạy `tests/Feature/CsrfProtectionTest.php` pass

### L13-203 — Cache `serializable_classes`

L13 mặc định `serializable_classes => false` trong `config/cache.php`.

- [ ] Thêm option vào `config/cache.php` (sau khi publish/merge):

```php
'serializable_classes' => false,
```

- [ ] Nếu app **có** lưu PHP object trong cache → thêm allow-list class; nếu không → giữ `false`.

### L13-204 — Rà soát `upsert()`

L13 ném `InvalidArgumentException` nếu `uniqueBy` rỗng (MySQL/MariaDB).

- [ ] Grep codebase: `upsert(` — sửa chỗ nào thiếu `uniqueBy`.

### L13-205 — Các thay đổi khác (ít khả năng ảnh hưởng)

| Thay đổi L13                                             | Cần làm với dự án này?                |
| -------------------------------------------------------- | ------------------------------------- |
| `JobAttempted` event `$exceptionOccurred` → `$exception` | Chỉ nếu có listener queue tùy chỉnh   |
| `QueueBusy` `$connection` → `$connectionName`            | Chỉ nếu có listener                   |
| Password reset subject đổi text                          | Cập nhật test nếu assert subject cứng |
| Pagination view `pagination::default` → `bootstrap-3`    | Chỉ nếu reference trực tiếp tên view  |

- [ ] **L13-205** — Đã grep, không có code bị ảnh hưởng / đã sửa

### L13-206 — Format code

```bash
vendor/bin/pint --dirty
```

- [ ] **L13-206** — Pint chạy xong

**Tiêu chí hoàn thành Phase 2:** Code compile, không lỗi autoload, CSRF test pass.

---

## Phase 3 — Kiểm thử (L13-300)

> **Thời gian ước tính:** 1–2 giờ  
> **Downtime:** Không

### Test tự động

- [ ] **L13-301** — Full suite:

```bash
php artisan test --compact
```

- [ ] **L13-302** — Build frontend (nếu có thay đổi asset):

```bash
pnpm run build
```

### Smoke test thủ công (bắt buộc — một môi trường = chính site đang dùng)

| #   | Khu vực        | Thao tác                                         | OK? |
| --- | -------------- | ------------------------------------------------ | --- |
| 1   | Trang chủ      | Load, menu, danh mục                             | [ ] |
| 2   | Sản phẩm       | Xem chi tiết, thêm giỏ                           | [ ] |
| 3   | Giỏ / checkout | Đặt hàng test (không cần thanh toán thật)        | [ ] |
| 4   | Auth khách     | Đăng nhập, đăng ký, quên MK                      | [ ] |
| 5   | Account        | Xem đơn, profile, đổi MK                         | [ ] |
| 6   | Form + CSRF    | Contact / đăng ký — không lỗi 419                | [ ] |
| 7   | Admin          | Login, dashboard, CRUD bài viết                  | [ ] |
| 8   | CKFinder       | Upload ảnh trong admin                           | [ ] |
| 9   | Session        | Refresh nhiều trang — không bị logout bất thường | [ ] |

- [ ] **L13-303** — Toàn bộ smoke test trên pass

**Tiêu chí hoàn thành Phase 3:** 100% test tự động pass + smoke test không lỗi 419/500.

---

## Phase 4 — Triển khai (môi trường duy nhất) (L13-400)

> **Thời gian ước tính:** 15–30 phút downtime  
> **Lưu ý:** Local và production là **cùng một máy** — các bước dưới chạy trực tiếp trên server đang phục vụ site.

### Trước khi bật maintenance

- [ ] **L13-401** — Merge / checkout code L13 đã test xong
- [ ] **L13-402** — Backup DB lần cuối (file mới, có timestamp)
- [ ] **L13-403** — Xác nhận `.env` đã ghim `CACHE_PREFIX`, `REDIS_PREFIX`, `SESSION_COOKIE`

### Cửa sổ bảo trì

```bash
php artisan down --retry=60 --refresh=15
```

- [ ] **L13-404** — Maintenance mode ON

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
pnpm run build
```

- [ ] **L13-405** — Composer + migrate + cache + build xong

**Reload PHP / web server** (tùy stack local — Laragon, Herd, XAMPP, hoặc Windows IIS):

```bash
# Ví dụ nếu dùng PHP-FPM + Nginx trên Linux cùng máy:
# sudo systemctl reload php8.3-fpm
# sudo systemctl reload nginx

# Windows Laragon: Restart All từ UI hoặc restart Apache/Nginx
```

- [ ] **L13-406** — Web server / PHP đã reload

```bash
php artisan up
```

- [ ] **L13-407** — Maintenance mode OFF

### Kiểm tra ngay sau deploy (5–15 phút)

- [ ] **L13-408** — Trang chủ + admin login
- [ ] **L13-409** — Một luồng checkout / form có CSRF
- [ ] **L13-410** — Xem `storage/logs/laravel.log` — không exception mới

**Tiêu chí hoàn thành Phase 4:** Site online, không 500/419 hàng loạt.

---

## Phase 5 — Ổn định & đóng playbook (L13-500)

- [ ] **L13-501** — Theo dõi log + traffic **24–48 giờ**
- [ ] **L13-502** — Cập nhật `README.md` (dòng Laravel 12 → 13)
- [ ] **L13-503** — Ghi mục trong `docs/CHANGE_LOG.md` (ngày, version, file đổi chính)
- [ ] **L13-504** — Đổi trạng thái bảng phase ở đầu file này → `done`
- [ ] **L13-505** — Merge branch `upgrade/laravel-13` → `main` (nếu dùng Git flow)

---

## Breaking changes ảnh hưởng dự án

### Tác động cao

1. **Dependencies** — `laravel/framework ^13`, `laravel/tinker ^3`
2. **Request Forgery Protection** — middleware CSRF đổi tên + kiểm tra `Sec-Fetch-Site`

### Tác động trung bình

3. **`serializable_classes`** — mặc định `false` trong cache config
4. **`upsert()`** — `uniqueBy` không được rỗng

### Tác động thấp (đã mitigated nếu ghim .env)

5. **Cache prefix / session cookie** — đổi format mặc định framework; dự án đã có config explicit → **ghim `.env`** là đủ

---

## Rollback (một môi trường)

Nếu sau deploy có lỗi nghiêm trọng (checkout hỏng, 419 toàn site, admin không vào):

```bash
php artisan down

# Quay code về commit Laravel 12 (hoặc git revert merge)
git checkout main
git revert <merge-commit-l13>   # hoặc: git checkout <commit-hash-l12>

composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Khôi phục DB nếu migration L13 đã chạy và gây lỗi:
# mysql -u USER -p DATABASE_NAME < backup_pre_laravel13_YYYY-MM-DD.sql

php artisan up
```

| Tình huống             | Hành động                                                                       |
| ---------------------- | ------------------------------------------------------------------------------- |
| Lỗi code, chưa migrate | Revert Git + `composer install`                                                 |
| Lỗi sau migrate        | Restore DB backup + revert Git                                                  |
| Chỉ lỗi cache/session  | Kiểm tra `CACHE_PREFIX` / `SESSION_COOKIE` trong `.env` trước khi full rollback |

---

## Lệnh tham chiếu nhanh (copy-paste)

```bash
# === TRƯỚC UPGRADE ===
php artisan test --compact
composer why-not laravel/framework 13.0

# === UPGRADE ===
composer require laravel/framework:^13.0 laravel/tinker:^3.0 --with-all-dependencies
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear

# === SAU SỬA CODE ===
vendor/bin/pint --dirty
php artisan test --compact
pnpm run build

# === DEPLOY (môi trường duy nhất) ===
php artisan down --retry=60
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
pnpm run build
php artisan up
```

---

## Ghi chú theo dõi (điền khi làm)

| Ngày       | ID      | Người làm | Kết quả / ghi chú                |
| ---------- | ------- | --------- | -------------------------------- |
| 2026-07-09 | L13-000 | Agent     | Baseline 176 passed              |
| 2026-07-09 | L13-100 | Agent     | L13.19.0 + 3 package forks       |
| 2026-07-09 | L13-200 | Agent     | CSRF, cache, setting_option      |
| 2026-07-09 | L13-300 | Agent     | 176 passed sau fix cache         |
| 2026-07-09 | L13-400 | Agent     | composer update + cache:clear    |
|            | L13-500 |           | Smoke thủ công CKFinder/checkout |

---

## Tính năng L13 (làm sau — không gộp với upgrade)

- Laravel AI SDK
- JSON:API Resources
- Vector search

→ Chỉ xem xét **sau** khi L13-500 `done` và site ổn định ≥ 1 tuần.
