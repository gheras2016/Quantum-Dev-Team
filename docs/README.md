# Quantum Dev Team — Project Documentation

> **Start here.** This `docs/` folder is the source of truth for how this system
> is built, deployed, and secured, and where development currently stands. It is
> written so that a future contributor (human or AI assistant) can pick the work
> up with full context.

## What this project is

A bilingual (Arabic / English) corporate website + admin panel for a software
development studio. It presents services, projects (portfolio), team, blog
posts, testimonials and FAQs, and captures leads via a contact form and a
"request a project" form. Everything is managed from a full admin panel at
`/admin`.

It is a **clean, from-scratch rebuild** of an older project. The rebuild lives in
`D:\1مشاريع\Quantum-Dev-Team-Clear` (this repo). The old project at
`c:\Users\A\Desktop\Quantum-Dev-Team` is legacy reference only — **do not edit it**.

## The documents

| File | Read it when you need… |
|------|------------------------|
| [ARCHITECTURE.md](ARCHITECTURE.md) | The tech stack, folder layout, models, roles, i18n, and app conventions. |
| [DEPLOYMENT.md](DEPLOYMENT.md) | How it runs on Railway (native, no Docker), env vars, and the deployment gotchas that cost real time. |
| [SECURITY.md](SECURITY.md) | Authentication, two-factor auth, the backup module, security headers, and how cookies/sessions work. |
| [DEVELOPMENT-LOG.md](DEVELOPMENT-LOG.md) | The phases we went through, key decisions, **where development stopped**, and the prioritized roadmap. |

## Golden rules (learned the hard way)

1. **Production is Railway, native (Nixpacks) — never Docker.** Do not re-introduce
   a `Dockerfile`. See [DEPLOYMENT.md](DEPLOYMENT.md).
2. **The database is MySQL in production, SQLite locally.** Keep code driver-agnostic.
3. **`APP_KEY` must be set on Railway and must never change.** Encrypted data
   (2FA secrets) depends on it; changing it locks users out. See [SECURITY.md](SECURITY.md).
4. **Never commit secrets** (`.env`, DB passwords, `CLOUDINARY_URL`, `APP_KEY`,
   mail credentials). They live only in Railway → Variables.
5. **On Railway, migrations/seeding are run manually** from the Railway console
   after any deploy that adds a migration: `php artisan migrate --force`
   (and `php artisan db:seed --force` if needed). The `preDeployCommand` is not
   reliably executing on this project's Railway setup.
6. **Uploads need Cloudinary.** Railway's filesystem is ephemeral; without
   `CLOUDINARY_URL`, uploaded images vanish on redeploy.

## Environments at a glance

| | Local dev | Production |
|-|-----------|------------|
| Host | Windows, XAMPP | Railway (Nixpacks) |
| PHP | 8.1 (`C:\xampp\php\php.exe`) | 8.2+ |
| DB | SQLite (`database/database.sqlite`) | MySQL service |
| Git | `C:\flutter\bin\mingit\cmd\git.exe` | GitHub `gheras2016/Quantum-Dev-Team` |
| Push | via VS Code (auth as `gheras2016`) | auto/redeploy from GitHub |

## The 30-second orientation

- Public routes: `routes/web.php`. Admin routes: `routes/admin.php`.
- Admin controllers: `app/Http/Controllers/Admin/`. Public: `app/Http/Controllers/`.
- Business logic that is reused lives in `app/Services/`.
- Content is bilingual: translatable model fields are JSON columns cast to `array`
  and read with `->translate('field')` (the `HasLocalizedContent` trait). UI strings
  live in `lang/ar/*` and `lang/en/*`.
- Roles: `super_admin`, `admin`, `content_manager`, `editor` (spatie/laravel-permission).
