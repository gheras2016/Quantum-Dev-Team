# Architecture

## Tech stack

- **Framework:** Laravel 10 (PHP `^8.2`).
- **Frontend:** Blade + Vite + Tailwind CSS 3 + Alpine.js. Compiled assets are
  committed under `public/build/` (git-ignored path force-added) so the site is
  styled even if the host does not rebuild them.
- **Auth & access:** Laravel session auth + `spatie/laravel-permission` (roles &
  permissions). Sanctum is installed but the app is server-rendered (no SPA).
- **Two-factor auth:** `pragmarx/google2fa` (TOTP) + `bacon/bacon-qr-code` (SVG QR).
- **Media:** `intervention/image` (resize + WebP) with optional
  `cloudinary-labs/cloudinary-laravel` for persistent, CDN-served uploads.
- **Docs/export:** `barryvdh/laravel-dompdf` + `mpdf/mpdf` (PDF), `maatwebsite/excel`
  (Excel export of contacts / project requests).

## Directory layout (`app/`)

```
app/
├── Console/            # scheduled command wiring
├── Exceptions/
├── Exports/            # ContactsExport, ProjectRequestsExport (maatwebsite/excel)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # the whole /admin panel (+ Admin/Auth for login & 2FA challenge)
│   │   └── *.go            # public controllers (Home, About, Service, Project, Team,
│   │                       # Blog, Contact, ProjectRequest, Language, Sitemap, Legal)
│   ├── Middleware/         # EnsureUserIsAdmin, SetLocale, TrustProxies, SecurityHeaders, …
│   └── Requests/           # form request validation
├── Models/             # 17 models (see below)
├── Notifications/      # NewContactNotification, NewProjectRequestNotification (mail)
├── Policies/           # UserPolicy, … (authorizeResource)
├── Providers/          # AppServiceProvider (view composers: footer social links, SEO JSON-LD)
├── Services/           # reusable business logic (see below)
└── Support/
```

## Models (`app/Models/`)

`User`, `Service`, `Project`, `ProjectImage`, `TeamMember`, `Post`, `Testimonial`,
`Faq`, `Technology`, `Category`, `Tag`, `SocialLink`, `Contact`, `ProjectRequest`,
`Media`, `Setting`, `ActivityLog`.

Common traits:
- **`HasLocalizedContent`** — fields like `title`, `description`, `excerpt`, `body`
  are JSON columns cast to `array` holding `{ "ar": "...", "en": "..." }`. Read with
  `$model->translate('title')` (returns the current locale, falls back to the other).
- **`LogsActivity`** — writes to `activity_log` on create/update/delete.
- **`SoftDeletes`** — most content models; the Trash admin section restores/purges them.
- **Media** is polymorphic (`morphMany`) and attached via `MediaService`.

### Key model notes
- `Setting`: key/value store, **cached** as `settings.all` (see `Setting::all()`),
  read via the global `setting('key', $default)` helper. Value column cast to `array`.
- `User`: has the 2FA columns (`two_factor_secret`, `two_factor_recovery_codes`,
  `two_factor_confirmed_at`) — secret & recovery codes are **encrypted casts**.
- Scopes are consistent: `active()`, `published()`, `featured()`, `ordered()`.

## Services (`app/Services/`)

- **`MediaService`** — stores uploads. If `CLOUDINARY_URL` is set it uploads to
  Cloudinary (persistent); otherwise it writes an optimized WebP to the local
  `public` disk. Also attaches polymorphic `Media` rows.
- **`BackupService`** — full-database JSON export / transactional restore (see
  [SECURITY.md](SECURITY.md)).
- **`TwoFactorService`** — TOTP secret generation, code verification, QR SVG,
  recovery codes.
- **`ActivityService`** — records admin actions.
- **`Admin/*Service`** — per-resource create/update/delete helpers used by admin
  controllers (keeps controllers thin).

## Roles & permissions

Seeded by `RolesAndPermissionsSeeder`:
- `super_admin` — everything (also bypasses gates via `Gate::before`).
- `admin` — everything including user management.
- `content_manager` — content CRUD (create/edit, no delete), read leads.
- `editor` — view/edit content, read leads.

Route protection: the admin area is behind the `admin` middleware
(`EnsureUserIsAdmin`); individual routes add `permission:*` or `role:*`.
`super_admin`-only areas (e.g. Backup) use `role:super_admin`.

## Routing map

**Public** (`routes/web.php`): `/`, `/about`, `/services`, `/projects` + `/projects/{slug}`,
`/team`, `/blog` + `/blog/{slug}`, `/contact` (GET/POST, throttled), `/request-project`
(GET/POST, throttled), `/privacy`, `/terms`, `/lang/{locale}`, `/sitemap.xml`, `/robots.txt`.

**Admin** (`routes/admin.php`, prefix `/admin`, name `admin.`):
`login` / `logout` / `two-factor-challenge` (guest), then behind `admin` middleware:
`dashboard`, resource CRUD for services/projects/team/posts/testimonials/faqs/
technologies/categories/social-links/users, `contacts`, `project-requests`, `trash`,
`settings`, `backup` (super_admin), `profile` (+ `two-factor` self-service), and
`users/{user}/reset-two-factor`.

## Internationalization (i18n)

- Default locale is Arabic (RTL); English is LTR. The `SetLocale` middleware +
  `/lang/{locale}` switch handle it; the layout sets `dir` accordingly.
- UI strings: `lang/{ar,en}/*.php` (e.g. `messages.php`, `navigation.php`,
  `blog.php`, `settings.php`, `two-factor.php`, `legal.php`).
- Model content: JSON columns via `HasLocalizedContent` (see above).

## Frontend build

- `npm run build` → Vite outputs to `public/build/` with `manifest.json`.
- The compiled output **is committed** so production serves styled pages without a
  Node build step. If you change CSS/JS, rebuild and commit `public/build/`.
- Blade references assets with `@vite([...])`; error pages use a minimal
  `errors/layout.blade.php` (this matters — see the 500 debugging story in the log).
