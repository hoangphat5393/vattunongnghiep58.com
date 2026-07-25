# Đề xuất cải thiện — Vật Tư Nông Nghiệp 58

> **Cập nhật:** 2026-07-10  
> **IMP-001 … IMP-015:** hoàn tất (trừ IMP-011 `deferred`).  
> **BACK-001 … BACK-018:** hoàn tất — xem [docs/BACKEND_AUDIT_PLAYBOOK.md](docs/BACKEND_AUDIT_PLAYBOOK.md).

## Index tài liệu

| File                                                                       | Mục đích                                                  |
| -------------------------------------------------------------------------- | --------------------------------------------------------- |
| [docs/BACKEND_AUDIT_PLAYBOOK.md](docs/BACKEND_AUDIT_PLAYBOOK.md)           | Audit backend BACK-001…018 (đã xong)                      |
| [docs/IMPROVEMENT_PLAYBOOK.md](docs/IMPROVEMENT_PLAYBOOK.md)               | Tiến độ IMP-001…015                                       |
| [docs/PROJECT_ANALYSIS.md](docs/PROJECT_ANALYSIS.md)                       | Kiến trúc, DB, luồng dữ liệu                              |
| [docs/CHANGE_LOG.md](docs/CHANGE_LOG.md)                                   | Ghi chú thay đổi kỹ thuật theo ngày                       |
| [docs/LARAVEL_13_UPGRADE_PLAYBOOK.md](docs/LARAVEL_13_UPGRADE_PLAYBOOK.md) | Nâng cấp Laravel 12 → 13 (một môi trường)                 |
| [MASTER.md](MASTER.md)                                                     | **Tài liệu master** — nghiệp vụ, luồng, schema, tính năng |
| [docs/TABLE_GLOSSARY.md](docs/TABLE_GLOSSARY.md)                           | Bảng ↔ model ↔ legacy (tra cứu DB)                        |
| [docs/ROUTE_GLOSSARY.md](docs/ROUTE_GLOSSARY.md)                           | Route ↔ URL ↔ controller (tra cứu routing)                |
| [docs/REFACTOR_3NONG_PLAYBOOK.md](docs/REFACTOR_3NONG_PLAYBOOK.md)         | Kế hoạch port `3nong` theo chuẩn này                      |
| [docs/DB_AUDIT.md](docs/DB_AUDIT.md)                                       | Rà soát bảng thừa / code mồ côi + kế hoạch dọn            |

## Kiểm chứng

```bash
php artisan test --compact
pnpm run build
```

| Suite         | File gợi ý                                                                    |
| ------------- | ----------------------------------------------------------------------------- |
| CSRF          | `tests/Feature/CsrfProtectionTest.php`                                        |
| JS cleanup    | `tests/Feature/PublicLegacyJsCleanupTest.php`                                 |
| Backend P1/P2 | `tests/Feature/BackendP1HardeningTest.php`, `BackendP2ContinuationTest.php`   |
| Route alias   | `tests/Feature/RouteAliasTest.php`                                            |
| Customer auth | `tests/Feature/CustomerAuthFlowTest.php`, `CustomerRegistrationEmailTest.php` |
| Email admin   | `tests/Feature/AdminEmailTemplateTest.php`                                    |
| Docs          | `tests/Feature/DocumentationCleanupTest.php`                                  |

## Việc còn lại (không blocker)

- Smoke CKFinder upload trong admin (BACK-002 checklist thủ công).
- `php artisan migrate` trên server cho migration index BACK-014 (nếu chưa chạy).
- IMP-011 Bootstrap admin — `deferred` (dùng AdminLTE + `style_admin.css`).
