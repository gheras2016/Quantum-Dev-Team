# Quantum Dev Team

A bilingual (Arabic / English) portfolio & agency platform built on **Laravel 10**, with a
public marketing site and a role-based admin dashboard.

This is a clean, from-scratch rebuild of the original *Quantum Dev Team* project — same
features and business logic, re-architected around best practices, a unified database
schema, and a modern Vite + Tailwind + Alpine front end.

> **Developer manual.** For the client-facing guide (Arabic) see
> [`docs/Quantum-System-Guide-AR.pdf`](docs/Quantum-System-Guide-AR.pdf).

---

## Table of contents

1. [Features](#features)
2. [Tech stack](#tech-stack)
3. [Architecture](#architecture)
4. [Getting started](#getting-started)
5. [Environment variables](#environment-variables)
6. [Database & seeders](#database--seeders)
7. [Media & storage](#media--storage)
8. [Localization](#localization)
9. [Testing](#testing)
10. [Exports & documents](#exports--documents)
11. [Deployment (Render + Docker)](#deployment-render--docker)
12. [Useful commands](#useful-commands)

---

## Features

### Public site
- Pages: Home, **About**, Services, Projects (list + category filter + details), Blog, Team,
  Contact, Request-Project.
- Blog with articles (reading time, tags, related posts), client Testimonials, and an FAQ accordion.
- Bilingual AR/EN with full RTL support and a session-based language switcher.
- Light / dark mode (persisted, system-aware).
- SEO: dynamic `sitemap.xml`, `robots.txt`, Open Graph / Twitter cards, per-entity SEO fields.
- Animated hero statistics, floating **WhatsApp** button, honeypot-protected forms.
- Project view counter, YouTube embeds, image galleries.

### Admin dashboard (`/admin`)
- Authentication with roles & permissions (`spatie/laravel-permission`).
  Roles: `super_admin`, `admin`, `content_manager`, `editor`.
- CRUD for Services, Projects, Team, Blog Posts, Testimonials, FAQ, Technologies, Categories, Social Links.
- Dashboard with KPI cards, a 6-month new-content bar chart, recent activity and messages.
- User & role management, and a **Trash bin** (restore / permanently delete soft-deleted content).
- Contacts and Project Requests triage (status workflow) with email notifications and a notifications bell.
- **Excel** export for Contacts & Project Requests, and per-request **PDF** export.
- Editable **Site Settings** (logo, contact info, hero text, statistics) — no code changes needed.
- Profile & password management.
- Automatic **activity log** (who did what, IP, device type) for every content change.

---

## Tech stack

| Concern            | Choice                                                        |
|--------------------|---------------------------------------------------------------|
| Framework          | Laravel 10 (PHP 8.1 local / **8.2** in Docker)                |
| Authorization      | spatie/laravel-permission                                     |
| Front end          | Vite, Tailwind CSS 3, Alpine.js                               |
| Database           | SQLite (local) · **PostgreSQL** (production) · MySQL-ready    |
| Image optimization | intervention/image (WebP)                                     |
| Persistent storage | Cloudinary (`cloudinary-labs/cloudinary-laravel`) + local fallback |
| Exports            | maatwebsite/excel · barryvdh/laravel-dompdf                   |
| Arabic PDF docs    | mpdf/mpdf                                                      |
| Deployment         | Docker (PHP-FPM + Nginx + Supervisor) on Render               |

---

## Architecture

```
app/
├── Console/Commands/         CreateSuperAdmin, GenerateDocs
├── Http/
│   ├── Controllers/          Thin controllers (public + Admin/)
│   ├── Requests/             FormRequests with real validation rules
│   └── Middleware/           EnsureUserIsAdmin, SetLocale
├── Models/
│   ├── Concerns/             HasLocalizedContent, LogsActivity
│   └── ...                   Domain models with scopes & accessors
├── Policies/                 ResourcePolicy base + per-model policies
├── Services/
│   ├── Admin/                One service per resource (business logic)
│   ├── ActivityService       Central audit-trail writer
│   └── MediaService          Upload → Cloudinary (or local WebP) → attach/delete
├── Support/DeviceDetector    Single source of truth for UA parsing
└── helpers.php               setting(), media_url()
```

Key design decisions:

- **No duplicated logging / device code.** Activity logging is automatic via the
  `LogsActivity` model trait → `ActivityService` → `DeviceDetector`.
- **One clean schema.** Each table has a single canonical migration; legacy half-migrated
  columns are unified into pivot relations + status enums.
- **Localized content** (`title`, `description`, …) is stored as JSON and read through
  `->translate('field')`, falling back to the fallback locale.
- **Thin controllers, fat services.** Controllers validate (FormRequest), authorize
  (Policy) and delegate to a service.
- **Storage abstraction.** `MediaService` uploads to Cloudinary when configured, otherwise
  stores a WebP locally. `media_url()` returns full URLs as-is and local paths from the disk.

---

## Getting started

```bash
# 1. Dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database (SQLite by default)
#    (Windows)  New-Item database\database.sqlite
#    (Unix)     touch database/database.sqlite
php artisan migrate --seed

# 4. Storage symlink + assets
php artisan storage:link
npm run build      # or: npm run dev

# 5. Serve
php artisan serve
```

### Default super admin (from the seeder)
```
email:    admin@quantum.test
password: password
```
> Change this immediately after first login (Admin → Profile). Override the seeded
> credentials with the `ADMIN_EMAIL` / `ADMIN_PASSWORD` env vars.

Create additional super admins interactively:
```bash
php artisan app:create-super-admin
```

---

## Environment variables

| Variable                         | Purpose                                                            |
|----------------------------------|--------------------------------------------------------------------|
| `APP_KEY`                        | **Required.** `php artisan key:generate`. Missing = HTTP 500.       |
| `APP_URL`                        | Public URL (needed for correct asset/OG links behind a proxy).     |
| `DB_CONNECTION` / `DATABASE_URL` | `pgsql` + a Postgres URL in production.                             |
| `CLOUDINARY_URL`                 | `cloudinary://KEY:SECRET@CLOUD` — persistent image storage.        |
| `MAIL_MAILER`                    | `log` for local; SMTP settings for real email.                     |
| `ADMIN_EMAIL` / `ADMIN_PASSWORD` | Override the seeded super-admin credentials.                       |
| `RUN_MIGRATIONS` / `RUN_SEED`    | Container entrypoint toggles (default: migrate on, seed on).       |

Secrets are read from the environment only — never commit them. `.env` is git-ignored.

---

## Database & seeders

`php artisan migrate --seed` runs, in order:

| Seeder                      | Populates                                                            |
|-----------------------------|---------------------------------------------------------------------|
| `RolesAndPermissionsSeeder` | Roles + granular permissions.                                       |
| `SettingsSeeder`            | Company profile, hero, contact info, statistics (Arabic).           |
| *super admin*               | `admin@quantum.test` (or `ADMIN_*` env).                            |
| `DemoContentSeeder`         | Technologies, categories, tags, 12 services, 18 projects, team, social links. |
| `EngagementSeeder`          | Arabic blog posts, testimonials, FAQs.                              |
| `InboxSeeder`               | Contact messages, project requests, activity log (fills the dashboard). |

All seeders are **idempotent** (`firstOrCreate`), so re-seeding never duplicates data.
The super admin is created **before** demo content, so login always works even if demo
seeding fails.

---

## Media & storage

- Uploaded raster images are resized (max 1600px) and either uploaded to **Cloudinary**
  (when `CLOUDINARY_URL` is set) or converted to **WebP** on the local `public` disk.
- `media_url($path)` resolves a stored reference to a URL (full Cloudinary URL as-is,
  local path via `asset('storage/…')`).
- Project galleries use the polymorphic `media` table; manage them on the project edit screen.
- Run `php artisan storage:link` once for the local disk.

> On Render the local filesystem is ephemeral — configure `CLOUDINARY_URL` so uploads persist.

---

## Localization

Translation files live in `lang/{ar,en}/`. The active locale is resolved from the session
by the `SetLocale` middleware and can be switched via `/lang/{locale}`.

---

## Testing

```bash
php artisan test
```

The suite (23 feature tests) covers public pages & SEO, admin access control & RBAC,
project CRUD with image/gallery uploads, media deletion, trash restore/force-delete,
activity logging, and the contact form incl. honeypot protection. Tests run against an
in-memory SQLite database and never touch your dev data.

---

## Exports & documents

- **Excel** (`maatwebsite/excel`): *Export* buttons on the Contacts and Project Requests pages.
- **PDF (per request)** (`dompdf`): export a single project request from its detail page.
- **Client manual (Arabic PDF)** (`mpdf`): generate/update with

  ```bash
  php artisan docs:generate
  ```

  Output: `docs/Quantum-System-Guide-AR.pdf`. Edit the content in
  `resources/views/docs/client-manual.blade.php` and add a new row to `$changelog` in
  `app/Console/Commands/GenerateDocs.php` for every release, then re-run the command.

---

## Deployment (Render + Docker)

The repo ships a production `Dockerfile` (multi-stage: Composer → Vite → PHP-FPM + Nginx +
Supervisor) and a `render.yaml` blueprint (web service + PostgreSQL).

1. Push to the connected GitHub repo (Render auto-deploys `main`).
2. In Render → Environment set at least: `APP_KEY`, `APP_URL`, and `CLOUDINARY_URL`.
3. `DATABASE_URL` is wired automatically from the managed Postgres.

The container entrypoint (`docker/entrypoint.sh`) templates Nginx for `$PORT`, caches
config/routes/views, runs migrations, and (idempotently) seeds the database.

---

## Useful commands

```bash
php artisan migrate:fresh --seed     # rebuild the database with demo data
php artisan app:create-super-admin   # create an admin interactively
php artisan docs:generate            # (re)generate the client PDF manual
php artisan test                     # run the test suite
npm run build                        # compile assets
```
