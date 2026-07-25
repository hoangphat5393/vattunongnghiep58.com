# Vật Tư Nông Nghiệp 58

Website thương mại điện tử / CMS — **Laravel 13**, **PHP 8.3+**, **Vite 7**, **Tailwind CSS 4**.

## Chạy nhanh

```bash
composer install
pnpm install
cp .env.example .env   # nếu chưa có
php artisan key:generate
pnpm run build         # hoặc pnpm run dev
php artisan serve
```

## Tài liệu (living)

| File                                                                       | Mục đích                                                  |
| -------------------------------------------------------------------------- | --------------------------------------------------------- |
| [MASTER.md](MASTER.md)                                                     | **Tài liệu master** — nghiệp vụ, luồng, schema, tính năng |
| [RECOMMENDATIONS.md](RECOMMENDATIONS.md)                                   | Index tài liệu + kiểm chứng                               |
| [docs/BACKEND_AUDIT_PLAYBOOK.md](docs/BACKEND_AUDIT_PLAYBOOK.md)           | Audit backend BACK-001…018 (**done**)                     |
| [docs/IMPROVEMENT_PLAYBOOK.md](docs/IMPROVEMENT_PLAYBOOK.md)               | Tiến độ IMP-001…015 (đã xong)                             |
| [docs/PROJECT_ANALYSIS.md](docs/PROJECT_ANALYSIS.md)                       | Kiến trúc, DB, luồng dữ liệu                              |
| [docs/CHANGE_LOG.md](docs/CHANGE_LOG.md)                                   | Ghi chú thay đổi kỹ thuật                                 |
| [docs/LARAVEL_13_UPGRADE_PLAYBOOK.md](docs/LARAVEL_13_UPGRADE_PLAYBOOK.md) | Kế hoạch nâng cấp Laravel 13                              |
| [docs/TABLE_GLOSSARY.md](docs/TABLE_GLOSSARY.md)                           | Bảng ↔ model ↔ nghiệp vụ / legacy                         |
| [docs/ROUTE_GLOSSARY.md](docs/ROUTE_GLOSSARY.md)                           | Route name ↔ URL ↔ controller                             |
| [docs/REFACTOR_3NONG_PLAYBOOK.md](docs/REFACTOR_3NONG_PLAYBOOK.md)         | Kế hoạch port `3nong` theo chuẩn này                      |
| [docs/DB_AUDIT.md](docs/DB_AUDIT.md)                                       | Rà soát bảng thừa / code mồ côi + kế hoạch dọn            |

## Kiểm thử

```bash
php artisan test --compact
```

## Stack tóm tắt

- **Frontend:** Blade + Vite (`resources/js/app.js` → `http.js`, `auth-forms.js`, `custom.js`, axios, jQuery)
- **Admin:** AdminLTE 4.1, `public/assets/js/js_admin.js` (axios, `AdminRoutes`)
- **Auth:** guard `web` (khách), `admin` (bảng `users`)
