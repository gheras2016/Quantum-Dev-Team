# Deployment (Railway, native — no Docker)

Production runs on **Railway** using the **Nixpacks** builder (native PHP), with a
managed **MySQL** service. Docker was deliberately removed. Do not re-add it.

## How it is wired

- **`railway.json`** — the single source of truth for the deploy:
  - `build.builder = "NIXPACKS"`.
  - `deploy.preDeployCommand` = `php artisan migrate --force && php artisan db:seed --force`.
  - `deploy.startCommand` = storage link + config/route/view cache + background
    scheduler + `php artisan serve --host=0.0.0.0 --port=$PORT`.
  - `healthcheckPath = "/"`, `restartPolicyType = "ON_FAILURE"`.
- **Nixpacks installs PHP extensions declared in `composer.json`** (the `ext-*`
  entries). This is why `ext-pdo_mysql` is required there — without it MySQL fails
  with *"could not find driver"*.
- Compiled Vite assets are committed under `public/build/`, so styling works even
  if Nixpacks does not run `npm run build`.

## Required Railway variables

Set these in **Railway → the app service → Variables** (never in Git):

```
APP_KEY=base64:...        # REQUIRED. Generate once, then NEVER change it.
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-domain>
LOG_CHANNEL=stderr        # so errors show in Railway logs

DB_CONNECTION=mysql
DB_HOST=${{ MySQL.MYSQLHOST }}
DB_PORT=${{ MySQL.MYSQLPORT }}
DB_DATABASE=${{ MySQL.MYSQLDATABASE }}
DB_USERNAME=${{ MySQL.MYSQLUSER }}
DB_PASSWORD=${{ MySQL.MYSQLPASSWORD }}
# (Replace "MySQL" with the EXACT name of your MySQL service.)

# Strongly recommended before real traffic / launch:
CLOUDINARY_URL=cloudinary://KEY:SECRET@CLOUD   # persistent uploads (see below)
PHP_CLI_SERVER_WORKERS=4                        # concurrency for `artisan serve`
MAIL_MAILER=smtp                                # real email for contact notifications
MAIL_HOST=... MAIL_PORT=587 MAIL_USERNAME=... MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls MAIL_FROM_ADDRESS=... MAIL_FROM_NAME="Quantum Dev Team"

# Optional:
ADMIN_EMAIL=... ADMIN_PASSWORD=...             # overrides seeded super-admin login
```

## Standard deploy flow

1. Commit locally, then **push from VS Code** (authenticates as `gheras2016`;
   the CLI on this machine cannot push to that repo).
2. Railway builds a **new deployment from the latest commit**. Make sure the
   deployment's commit is the one you just pushed — clicking *Redeploy* re-runs the
   **previous** deployment's commit, which will silently ship old code.
3. **After any deploy that added a migration, run it manually** in the Railway
   console (Deployments → open a shell):
   ```
   php artisan migrate --force
   ```
   (and `php artisan db:seed --force` if you need seed data). See the gotcha below.

## Gotchas that cost real time (read these)

### 1. `could not find driver (Connection: mysql)`
Nixpacks only installs PHP extensions listed in `composer.json`. The fix was adding
`"ext-pdo_mysql": "*"` (commit `d725288`). Never remove it.

### 2. `MissingAppKeyException` → HTTP 500 on every page
If `APP_KEY` is empty, the encrypter cannot be built, so the `EncryptCookies` /
session middleware throws on any page that uses a session or `csrf_token()`. The
symptom is sneaky: **static assets return 200, the styled error page returns 200,
but every real page returns 500** (the error page uses a minimal layout without a
session, so it renders). Fix: set a valid `APP_KEY`.

### 3. Laravel using database `forge` → `Connection refused`
`forge` is Laravel's built-in default in `config/database.php` for the mysql
connection. Seeing it means the `DB_*` variables are **not reaching the app** —
usually because the `${{ MySQL.* }}` reference names the wrong service, or the vars
were set on the DB service instead of the app service. Fix the variables; do not
change `config/database.php`.

### 4. Migrations/seed not running automatically
On this project's Railway setup the `preDeployCommand` did **not** reliably run.
Practical rule: **run `php artisan migrate --force` (and seed) manually** from the
Railway console after deploys with new migrations. Example symptom that hit us:
enabling 2FA threw *"Unknown column 'two_factor_secret'"* because the migration had
not been run on the live DB.

### 5. Config cache ignores env overrides (local dev, too)
`php artisan config:cache` bakes config; afterwards `env()` outside config files
returns null and any `$env:` overrides are ignored. If local tests behave oddly,
run `php artisan config:clear`. On Railway this is fine because the start command
caches with the correct env present.

### 6. `php artisan serve` is a single-threaded dev server
It works but chokes under concurrency (this caused `connection dial timeout` / 499).
Mitigation in place: set `PHP_CLI_SERVER_WORKERS`. Real fix for scale: migrate to
**FrankenPHP** or **nginx + php-fpm** (a deliberate, tested change — see the roadmap).

### 7. Uploads are ephemeral without Cloudinary
Railway's disk is wiped on every deploy. `MediaService` falls back to local storage
if `CLOUDINARY_URL` is unset, so uploaded images disappear on redeploy. Set
`CLOUDINARY_URL` for any real content.

## Free domain (paused task)

A free `quantumdev.is-a.dev` subdomain was being set up via the `is-a-dev/register`
repo. One file was prepared in a **separate staging folder**
(`D:\1مشاريع\is-a-dev-quantumdev\domains\quantumdev.json`, CNAME →
`0jqzcr5r.up.railway.app`). The second file (`_railway-verify.quantumdev.json`, a
TXT record) was not created, and no PR was opened. `owner.username` must be
`gheras2016` and the PR must be opened from that GitHub account (the is-a.dev CI
checks the PR author). This task is **not finished**.
