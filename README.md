# Quantum Dev Team

A bilingual (Arabic / English) portfolio & agency platform built on **Laravel 10**, with a
public marketing site and a role-based admin dashboard.

This is a clean, from-scratch rebuild of the original *Quantum Dev Team* project — same
features and business logic, re-architected around best practices, a unified database
schema, and a modern Vite + Tailwind + Alpine front end.

---

## Features

### Public site
- Home, Services, Projects (list + filter + details), Blog, Team, Contact, Request-Project.
- Blog with articles (reading time, tags, related posts), client Testimonials, and an FAQ accordion.
- Bilingual AR/EN with full RTL support and a session-based language switcher.
- Light / dark mode (persisted, system-aware).
- SEO: dynamic `sitemap.xml`, `robots.txt`, Open Graph / Twitter cards, per-entity SEO fields.
- Project view counter, YouTube embeds, image galleries, honeypot-protected forms.

### Admin dashboard (`/admin`)
- Authentication with roles & permissions (spatie/laravel-permission).
- Roles: `super_admin`, `admin`, `content_manager`, `editor`.
- CRUD for Services, Projects, Team, Blog Posts, Testimonials, FAQ, Technologies, Categories, Social Links.
- Dashboard with KPI cards, a 6-month new-content bar chart, recent activity and messages.
- User & role management, and a Trash bin (restore / permanently delete soft-deleted content).
- Read + triage for Contacts and Project Requests (status workflow) with email notifications and an in-panel notifications bell.
- Excel export for Contacts & Project Requests, and per-request PDF export.
- Dashboard with a content-over-time chart.
- Editable Site Settings (contact info, hero text, statistics) — no code changes needed.
- Profile & password management.
- Automatic activity log (who did what, IP, device type) for every content change.

---

## Tech stack

| Concern        | Choice                                   |
|----------------|------------------------------------------|
| Framework      | Laravel 10 (PHP 8.1+)                     |
| Authorization  | spatie/laravel-permission                |
| Front end      | Vite, Tailwind CSS 3, Alpine.js          |
| Database       | SQLite by default (MySQL-ready)          |

---

## Architecture

```
app/
├── Http/
│   ├── Controllers/            Thin controllers (public + Admin/)
│   ├── Requests/               FormRequests with real validation rules
│   └── Middleware/             EnsureUserIsAdmin, SetLocale
├── Models/
│   ├── Concerns/               HasLocalizedContent, LogsActivity
│   └── ...                     Domain models with scopes & accessors
├── Policies/                   ResourcePolicy base + per-model policies
├── Services/
│   ├── Admin/                  One service per resource (business logic)
│   ├── ActivityService         Central audit-trail writer
│   └── MediaService            Shared upload/attach/delete helper
└── Support/
    └── DeviceDetector          Single source of truth for UA parsing
```

Key design decisions:

- **No duplicated logging / device code.** Activity logging is automatic via the
  `LogsActivity` model trait → `ActivityService` → `DeviceDetector`. The legacy code
  repeated the same `ActivityLog::create([...])` and `getDeviceType()` block in ~15 files.
- **One clean schema.** Each table has a single canonical migration; the legacy
  half-migrated columns (`is_active` vs `featured`, `category` vs pivot tables, JSON
  `technologies` vs `project_technology`) are unified into pivot relations + status enums.
- **Localized content** (`title`, `description`) is stored as JSON and read through
  `->translate('title')`, falling back to the fallback locale.
- **Thin controllers, fat services.** Controllers validate (FormRequest), authorize
  (Policy) and delegate to a service.

---

## Getting started

```bash
# 1. Install dependencies
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
> Change this immediately after first login (Admin → Profile).

Create additional super admins interactively:
```bash
php artisan app:create-super-admin
```

---

## Localization

Translation files live in `lang/{ar,en}/`. The active locale is resolved from the
session by the `SetLocale` middleware and can be switched via `/lang/{locale}`.

## Testing

```bash
php artisan test
```

The suite (23 feature tests) covers public pages & SEO endpoints, admin access control
& RBAC, project CRUD with image/gallery uploads, media deletion, trash restore/force-delete,
activity logging, and the contact form incl. honeypot protection. Tests run against an
in-memory SQLite database and never touch your dev data.

## Media & galleries

Project cover images and multi-image galleries are stored on the `public` disk and
tracked via the polymorphic `media` table. Manage a project's gallery from its edit
screen (upload multiple, delete individually). Run `php artisan storage:link` once.

Uploaded raster images are automatically resized (max 1600px wide) and converted to
**WebP** (`intervention/image`, quality 82) to reduce page weight, with a safe
fallback to the original file if conversion fails.

## Exports

- **Excel** (`maatwebsite/excel`): Contacts and Project Requests index pages have an
  *Export Excel* button.
- **PDF** (`barryvdh/laravel-dompdf`): each Project Request can be exported to PDF from
  its detail page.
