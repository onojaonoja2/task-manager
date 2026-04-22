# AGENTS.md

## Project Type

Laravel 13 project (PHP 8.3+), Vite + TailwindCSS frontend, SQLite database.

## Developer Commands

```bash
composer run test    # Run tests (clears config cache first)
composer run dev     # Run dev server + queue + logs + Vite concurrently
composer run setup   # Install deps, generate key, migrate, npm install/build
```

## Key Files

- Entry points: `routes/web.php`, `routes/console.php`
- App config: `bootstrap/app.php`
- Models: `app/Models/`
- Tests: `tests/Feature/`, `tests/Unit/`

## Database

SQLite at `database/database.sqlite` (touched on fresh setup).

## Code Style

Uses Laravel Pint (`vendor/bin/pint`). No explicit lint/typecheck scripts in composer.json.

## Notes

- Frontend assets in `resources/css/app.css`, `resources/js/app.js`
- Vite config ignores `storage/framework/views/` for hot reload