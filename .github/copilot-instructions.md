# Copilot / AI Agent Instructions — IrskoStudy.cz

Purpose: quick, actionable guidance so an AI coding agent (Copilot/assistant) is immediately productive in this Laravel + Blade + TailwindCSS + Vue 3 codebase.

1) Big-picture
- Framework: Laravel 12 (backend + routing + Blade views). Frontend assets built with Vite.
- UI: Blade templates in `resources/views/` (pages in `resources/views/pages/`, partials in `resources/views/components/`).
- Frontend toolchain: Vite + `laravel-vite-plugin`. Tailwind is wired through the Tailwind Vite plugin.
- Reactive pieces: Vue 3 components live in `resources/js/components/` and are mounted from `resources/js/app.js` (mount target: an element with `id="app"`).

2) Common developer workflows (commands you should use)
- Install dependencies: `composer install` and `npm install`.
- Local dev front-end build (hot reload): `npm run dev` (Vite dev server) and `php artisan serve` for the PHP server if needed.
- Production build: `npm run build` then typical Laravel deploy steps (assets under `public/build/`).
- Tests: run PHPUnit with `./vendor/bin/phpunit` (project has `phpunit.xml`).
- Useful VS Code tasks are defined in the workspace (e.g. `Set Storage Permissions`, `Artisan Optimize`, and deploy tasks). Check the VS Code tasks panel to run them.

3) Project-specific patterns and conventions
- Pages: add a new static page by creating a Blade file in `resources/views/pages/` and registering it in `routes/web.php` with `Route::view('/path', 'pages.name')->name('name');`.
- Reusable UI: create partials in `resources/views/components/` and include them via `@include('components.name')` from layout or pages.
- Vue components: place `.vue` single-file components in `resources/js/components/`. Register them in `resources/js/app.js` before mounting. Example:
  - `import FaqAccordion from './components/faq-accordion.vue';`
  - `app.component('faq-accordion', FaqAccordion);`
  - Use in Blade: `<div id="app"><faq-accordion /></div>`
- CSS: primary Tailwind entry is `resources/css/app.css`. The Vite config (`vite.config.js`) includes this file in the build input.
- Tailwind config lives at `tailwind.config.cjs` (content paths include Blade, JS, and Vue files).

4) Integration points & external dependencies
- Database: Laravel database config in `config/database.php` — common local setup uses SQLite (`database/database.sqlite`) or other DBs via `.env`.
- File storage & runtime permissions: `storage/` and `bootstrap/cache` must be writable by PHP/web user. There is a workspace task `Set Storage Permissions` that sets `www-data:www-data` ownership.
- Deploy: custom rsync-based deploy tasks exist in workspace tasks for pushing `public/build/`, `public/img/`, and `storage/app/` to remote hosts.

5) Common gotchas / debugging hints (from this repo)
- If you see "attempt to write a readonly database" for SQLite: check `database/database.sqlite` exists, ensure writable permissions, and verify `DB_CONNECTION` and `DB_DATABASE` in `.env` point to the correct file.
- Storage permissions: many runtime issues stem from unreadable/unwritable `storage/` and `bootstrap/cache`. Use the `Set Storage Permissions` task or run `chown -R www-data:www-data storage bootstrap/cache`.
- Tailwind/Vite: Tailwind v4 changed the PostCSS integration. This repo uses the Tailwind Vite plugin; check `vite.config.js` and `package.json` when adjusting Tailwind or Vite plugin versions.

6) Where to look for behavior/examples
- Route examples: `routes/web.php` shows simple `Route::view()` usage for site pages.
- Controllers: see `app/Http/Controllers/` for any API or controller-driven logic.
- Frontend entry points: `resources/js/app.js`, `resources/js/bootstrap.js`, `resources/css/app.css`.
- Composer & PHP entry: `artisan` (project root) and `composer.json`.

7) Safe edit checklist for AI agents
- Always run `npm run dev` locally after changing frontend code to verify Vite builds.
- After changing PHP code, run `php artisan route:list` and the test suite (`./vendor/bin/phpunit`) when appropriate.
- When adding files that must be writable (uploads, DB), ensure `storage/` permissions are handled; mention permission changes in the PR description.

8) PR authoring notes for agents
- Keep changes minimal and focused. Note any external environment assumptions (DB type, web user) at the top of the PR.
- When touching build tooling (Vite, Tailwind), include the exact `npm install` commands used and the updated `package.json` diffs.

If anything here looks wrong or incomplete, tell me which area you want expanded (examples, more file references, or specific workstreams like deployment or testing) and I will update this file accordingly.
