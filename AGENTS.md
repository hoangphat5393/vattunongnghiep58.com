# AGENTS.md

## Cursor Cloud specific instructions

### Services overview

This is a Vietnamese agricultural supplies e-commerce app (vattunongnghiep58.com) built on Laravel 12 with:
- **Frontend storefront** — Blade + Tailwind CSS v4 + Vite
- **Admin panel** — AdminLTE-based, multi-guard auth (`admin` guard, `users` table)
- **MySQL 8.0** — required; the project has many legacy tables not covered by migrations

### Running the application

```bash
# Start MySQL (must be running before artisan commands)
sudo service mysql start

# Laravel dev server
php artisan serve --host=0.0.0.0 --port=8000

# Vite dev server (hot-reload for CSS/JS)
pnpm dev
```

### Lint & Tests

```bash
# Code style (Laravel Pint)
./vendor/bin/pint --test          # check only (exit 1 = violations exist in pre-existing code)
./vendor/bin/pint                 # auto-fix

# Test suite (Pest, uses SQLite :memory: — does NOT need MySQL)
./vendor/bin/pest
```

### Key gotchas

1. **Legacy tables not in migrations**: The database has many tables (`categories`, `products`, `shop_currencies`, `shop_order_status`, `shop_order_payment_status`, `menus`, `menu_items`, `sponser`, etc.) that predate the migration system. On a fresh install, you must create them manually or run the SQL setup documented below. The test suite sidesteps this by using SQLite in-memory with its own schema in `tests/TestCase.php`.

2. **Fresh database bootstrap SQL**: After running `php artisan migrate`, these legacy tables must be created manually for the app to serve pages. See the SQL in the `tests/TestCase.php::setUp()` method for reference schemas, or use:
   ```sql
   -- Key tables: shop_currencies, shop_order_status, shop_order_payment_status,
   -- categories (with `parent`, `hot` columns), products, menus, menu_items, sponser, etc.
   -- Also insert a page with slug='home' in the pages table.
   ```

3. **`phpunit.xml`**: The repo has `phpunit.xml.bak` but no `phpunit.xml`. Copy it before running tests: `cp phpunit.xml.bak phpunit.xml`.

4. **No `.env.example`**: The repo doesn't ship an `.env.example`. Create `.env` manually with at minimum: `APP_KEY`, `DB_*` credentials, `APP_ENV=local`, `APP_DEBUG=true`.

5. **pnpm build scripts**: The `package.json` must include `pnpm.onlyBuiltDependencies` for `esbuild` and `@parcel/watcher` so Vite runs correctly.

6. **Admin login credentials** (dev): email `admin@test.com`, password `password123` (created via tinker during setup).

7. **Storage directories**: Must exist before `composer install` can run `package:discover`:
   ```bash
   mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
   ```
