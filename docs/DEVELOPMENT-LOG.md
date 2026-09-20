# Development Log, Status & Roadmap

## Phases we went through

### Phase 1 — Clean rebuild
The original "Quantum Dev Team" project was rebuilt from scratch into this repo
(`Quantum-Dev-Team-Clear`) on modern Laravel 10 with a clean structure (thin
controllers, service classes, form requests, policies, bilingual JSON content,
soft deletes + trash, activity logging). Same features, cleaner code.

### Phase 2 — Feature build-out
Admin panel and public site: settings, notifications, honeypot/anti-spam, SEO
basics (meta/OG/canonical, sitemap, robots), blog, testimonials, FAQ, user
management with roles, media galleries (WebP), trash/restore, dashboard, Cloudinary
support, PDF docs, project covers, mobile responsiveness, Arabic seeders with
realistic demo content. Replaced fake "demo/source" buttons on project pages with a
lead CTA.

### Phase 3 — Deployment migration (the long one)
Went from Render+Docker → Railway. Decision: **drop Docker, deploy native
(Nixpacks), switch Postgres → MySQL.** Solved, in order:
1. `could not find driver (mysql)` → added `ext-pdo_mysql` to `composer.json`.
2. Consolidated Railway config into a single `railway.json` (removed a conflicting
   `railway.toml`).
3. `MissingAppKeyException` 500 → set `APP_KEY` on Railway.
4. `database = forge` / connection refused → set the real `DB_*` variables.
5. `connection dial timeout` / 499 → `php artisan serve` concurrency; mitigated with
   `PHP_CLI_SERVER_WORKERS`.
6. Learned that migrations/seed must be run **manually** on Railway.
(Full detail and symptoms are in [DEPLOYMENT.md](DEPLOYMENT.md).)

### Phase 4 — Launch-readiness features (most recent)
Commits, newest first:
- `c614af4` — bilingual Privacy Policy + Terms of Service pages (`/privacy`, `/terms`).
- `68a434d` — security response headers middleware.
- `4b5cf68` — schema.org structured data (JSON-LD): Organization + WebSite site-wide,
  BlogPosting + BreadcrumbList on posts.
- `4d4018f` — admin "reset two-factor" action for locked-out users.
- `414625d` — opt-in two-factor authentication (TOTP).
- `14b3a09` — full database backup & restore module.

All Phase-4 features were verified locally (rendering, JSON validity, 2FA end-to-end
on MySQL, backup round-trip) before committing.

---

## Where development stopped (current status)

**Done & working in production:** backup module, 2FA + reset, and (pending a
push/redeploy) SEO JSON-LD, security headers, legal pages.

**Pending — the owner's Railway actions (config, not code):**
- `CLOUDINARY_URL` — persistent uploads (without it, images vanish on redeploy).
- `MAIL_*` — real SMTP so contact/project-request notifications are delivered
  (currently `MAIL_MAILER=log`).
- `PHP_CLI_SERVER_WORKERS=4` — concurrency for the dev server.
- Review & customize the legal page text (`lang/{ar,en}/legal.php`) with real
  company/jurisdiction details.

**Pending — unfinished task:**
- Free domain `quantumdev.is-a.dev` (see DEPLOYMENT.md → "Free domain"). One file
  staged, second TXT file + PR not done.

---

## Roadmap / recommendations (prioritized)

### 🔴 Launch blockers
1. **Cloudinary** (`CLOUDINARY_URL`) — or uploaded media is lost on each deploy.
2. **Real SMTP** — otherwise leads silently never arrive by email.
3. **Change the default admin password**, confirm `APP_DEBUG=false`, and take a
   first **backup**.

### 🟠 Important, soon
4. **Production web server** — replace `php artisan serve` with **FrankenPHP** or
   **nginx + php-fpm**. This is the one real infrastructure weakness. Do it as a
   deliberate, tested change (the caching commands must run in the runtime
   container, so mind where `config:cache` executes). Keep `serve` as the fallback.
5. **Custom domain + HTTPS** — finish `quantumdev.is-a.dev`.
6. **Error monitoring** — wire Sentry (or rely on `LOG_CHANNEL=stderr` + Railway logs
   as a minimum).
7. **Queue worker + scheduler as separate services** — currently `QUEUE_CONNECTION`
   is `sync` and the scheduler is backgrounded inside the web process.

### 🟢 Polish & growth
8. **CSP** in report-only mode, then enforce (see SECURITY.md).
9. **Cookie-consent banner** linking to `/privacy`.
10. **Analytics** (Plausible/GA4).
11. **Add `/privacy` and `/terms` to the sitemap**; consider making legal text
    admin-editable (store in `settings`).
12. **DB indexes** on frequently-filtered columns; cache heavy homepage queries.
13. **Automated tests + CI** before each deploy.

---

## Working environment cheat-sheet (for the machine this was built on)

- PHP (local): `C:\xampp\php\php.exe` (8.1). Laravel 10 runs fine on it.
- Git: `C:\flutter\bin\mingit\cmd\git.exe`. **Pushing** must be done from **VS Code**
  (authenticates as `gheras2016`; the CLI credential is a different account and 403s).
- Local DB: SQLite. To reproduce production behavior, test against a throwaway MySQL
  DB by overriding `DB_*` env vars for artisan, and **`php artisan config:clear`
  first** (a stale config cache silently ignores the overrides).
- Never run `migrate:fresh` against the local SQLite unless you mean to reset it.
- Bash tool is unreliable on this box; prefer PowerShell with the full paths above.
