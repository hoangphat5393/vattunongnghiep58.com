# Admin UI Upgrade Plan

## 1. Current system

- Laravel project using Blade
- Existing admin UI in resources/views/admin

## 2. New UI

- Located in /new-admin-ui
- Pure HTML/CSS/JS template

## 3. Goals

- Upgrade UI without breaking backend logic
- Keep all routes, controllers, and database intact

## 4. Rules

- Do NOT rename variables
- Do NOT change routes
- Only update UI layer (Blade)

## 5. Mapping

- dashboard.blade.php → new dashboard.html
- user/index.blade.php → users.html

## 6. Progress

- [ ] Layout
- [ ] Sidebar
- [ ] Dashboard
- [ ] User module
