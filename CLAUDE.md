# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 10 admin template/starter kit, currently being extended into a freight-forwarding/logistics operations app (bookings, contracts, CRM, proposals, tariffs). The README describes it as a "Document Tracking & Finance Management System," but that is aspirational/downstream branding — the code in this repo is the reusable admin scaffold underneath it.

## Commands

```bash
composer install               # PHP dependencies
npm install                    # JS dependencies
php artisan migrate            # run migrations
php artisan db:seed            # seed initial superadmin user
npm run dev                    # Vite dev server (asset watch)
npm run build                  # production asset build
php artisan serve              # local dev server

php artisan test                                    # run full test suite (phpunit)
php artisan test --filter=AuthFlowTest              # run a single test class
php artisan test tests/Feature/AuthFlowTest.php      # run a single test file
```

Seeded initial login: `superadmin@email.com` / `Testing123`.

There is no configured linter/formatter script in `composer.json`/`package.json` (Laravel Pint is a dev dependency but not wired to a composer script — run `vendor/bin/pint` directly if formatting PHP).

## Architecture

**Stack**: Laravel 10 (PHP 8.1+), Blade views, Alpine.js + jQuery + Tailwind, bundled via Vite (`resources/js/app.js`, `resources/css/app.css`). Uses the classic Laravel 10 `app/Http/Kernel.php` structure (not the newer `bootstrap/app.php` style).

**Routing**: `RouteServiceProvider` loads only `routes/web.php` (web middleware) and `routes/api.php` (api middleware, `/api` prefix). Other route files are pulled in via `require`, not independently registered:
- `web.php` requires `page.php` (Blade page routes) and `mailer.php`
- `api.php` requires `api_maintenance.php`
- `api_booking.php`, `api_contracts.php`, `api_master.php` exist on disk but are **not required anywhere** — dead/orphaned route files.

**Known broken state — logistics domain is scaffolded but not implemented**: `routes/api_maintenance.php` (which *is* loaded on every request via `api.php`) references controllers that do not exist in `app/Http/Controllers` (`PortController`, `BookingController`, `ContractController`, `LaneController`, `ContainerController`, `ChargeTypeController`, `VatRateController`, etc.), and there are no corresponding models or migrations (ports, lanes, tariffs, bookings, contracts, containers). Hitting any `/api/*` route currently throws a class-not-found error. `PageController` similarly has view-only stub methods (`page_bookings`, `page_contracts`, `page_crm`, `page_proposals`, `page_maintenance`, etc.) that just render Blade pages with no backing data layer yet. `web.php` also imports `ProfileController` and `ProposalController`, neither of which exist. Before "fixing" a bug in this area, check whether the feature has actually been built yet — much of the logistics domain (ports/lanes/tariffs/bookings/contracts/containers) is route-level scaffolding only.

**What is actually implemented** (the reusable admin-template core):
- Auth (Laravel Breeze-based) — `app/Http/Controllers/Auth/*`, `routes/auth.php`
- Users & roles — `UserController`, `RoleController`, `User` model (role via `role_id` → `SettingRole`), gate `isSuperAdmin` defined in `AuthServiceProvider` checks `$user->role_name === 'superadmin'`
- Dynamic nav menu system — `NavMenu` model (`nav_menus` table: title, icon, link, `allowed_roles`, `allowed_office`, `parent_menu`, `menu_order`), `MenusController`, `Api/MenuController`, driven client-side by `resources/js/navmenu.js`
- Notifications — `NotificationController`, streaming endpoint at `/api/notifications/stream`
- Dynamic mailer — `MailerSetting` model, `DynamicMailerService`/`ApplicationMailer`, admin-configurable SMTP settings instead of static `.env` mail config

**Middleware of note** (`app/Http/Kernel.php` aliases):
- `check.status` (`CheckUserStatus`) — force-logs-out and redirects users whose `status === 1` (inactive); applied on the main authenticated `web.php` group
- `prevent-back-history` (`PreventBackHistory`) — applied alongside `check.status`
- `EnsureSingleSession` (registered globally in the `web` group) — on every authenticated request, deletes any other active session rows for that user in the `sessions` table, enforcing single-session-per-user by force-logging out other sessions
- `SafeText` — validates POST/PUT/PATCH string input against a restrictive allowed-character regex, returns 422 on violation (available but check where it's actually applied before assuming it runs everywhere)

**Deployment layout** (see README): production deployment splits the repo into `app_core/` (all Laravel framework files: app, bootstrap, config, database, resources, routes, storage, vendor) with only `public/` exposed as the web root. `public/index.php` needs its relative paths updated to point at `app_core/` when restructured this way. Always run `npm run build` before restructuring for deployment. Never commit `.env`.
