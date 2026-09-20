# Security, Authentication, Cookies & Backup

This document explains the security-related work: how login and two-factor auth
work, how cookies/sessions are handled, the security headers, and the database
backup module. Read it before touching any of these areas.

---

## 1. Authentication (login)

- Admin login is a **custom flow**, not Fortify/Breeze: `Admin/Auth/LoginController`.
- `Auth::attempt()` checks the password; then `EnsureUserIsAdmin` / the route
  middleware confirm the user may access `/admin`.
- Sessions are server-side; the browser only holds an **encrypted session cookie**.
- Passwords are hashed (`bcrypt`/`argon` via the `hashed` cast on `User::password`).

### The `APP_KEY` dependency (critical)
Laravel encrypts cookies and encrypts the 2FA columns using `APP_KEY`. Consequences:
- If `APP_KEY` is **missing**, the app cannot build the encrypter → **500 on every
  page** that touches a session/cookie (see DEPLOYMENT.md gotcha #2).
- If `APP_KEY` **changes** after users enable 2FA, their encrypted secrets can no
  longer be decrypted → they are effectively locked out (must use recovery codes,
  or an admin resets their 2FA).
- **Therefore: set `APP_KEY` once and never change it.**

---

## 2. Two-Factor Authentication (2FA / TOTP)

Opt-in, per-admin. Built with `pragmarx/google2fa` + `bacon/bacon-qr-code` (the same
libraries Laravel Fortify uses — we did **not** hand-roll crypto).

### Data model
Migration `..._add_two_factor_columns_to_users_table` adds to `users`:
- `two_factor_secret` — cast `encrypted` (the TOTP secret).
- `two_factor_recovery_codes` — cast `encrypted:array` (8 one-time codes).
- `two_factor_confirmed_at` — `datetime`, null until the user confirms enrolment.

`User::hasTwoFactorEnabled()` = secret present **and** confirmed.
Both encrypted columns are in `$hidden`.

### Files
- `app/Services/TwoFactorService.php` — `generateSecret()`, `verify()`,
  `otpauthUri()`, `qrCodeSvg()` (inline SVG, no imagick), `generateRecoveryCodes()`.
- `app/Http/Controllers/Admin/TwoFactorController.php` — self-service:
  `enable` → `confirm` → (`recovery-codes` regenerate) / `disable`.
- `app/Http/Controllers/Admin/Auth/TwoFactorChallengeController.php` — the login
  second step.
- `app/Http/Controllers/Admin/UserController@resetTwoFactor` — admin recovery for a
  locked-out user.
- Views: the 2FA card is at the bottom of `admin/profile/edit.blade.php`
  (+ `partials/recovery-codes`); the login step is `admin/auth/two-factor-challenge`.
- Strings: `lang/{ar,en}/two-factor.php`.

### Enrolment flow (where to find it in the UI)
`/admin/profile` → scroll to the **Two-Factor Authentication** card → **Enable** →
scan the QR (or enter the key) in an authenticator app → enter the 6-digit code to
**Confirm**. Recovery codes are shown during setup — save them.

### Login challenge flow
1. `LoginController@login`: after the password succeeds, if the user has 2FA
   enabled, it **logs them back out**, stores `auth.2fa.id` (+ `auth.2fa.remember`)
   in the session, and redirects to `admin.two-factor.challenge`.
2. `TwoFactorChallengeController`: verifies a TOTP code **or** a recovery code
   (recovery codes are single-use and removed when consumed), then
   `Auth::loginUsingId()` completes the login.

### Reset (admin recovery)
`Users → edit a user → "Reset two-factor"` card. It **only appears when that user
already has 2FA enabled** (nothing to reset otherwise). Gated by the `UserPolicy`
`update` ability. Clears the three columns so the user can log in with just a
password and re-enrol.

### Gotchas
- The 2FA card only renders after the migration is applied **on the live DB** — run
  `php artisan migrate --force` on Railway (this bit us once).
- Enabling 2FA is **self-service only**; an admin cannot enable it *for* someone —
  only reset/disable it.

---

## 3. Cookies & sessions

- **Session cookie:** Laravel stores the session id in an encrypted cookie
  (`EncryptCookies` middleware). Default driver is `file` (`SESSION_DRIVER=file`).
  This is why `APP_KEY` is mandatory (it encrypts the cookie) and why a missing key
  breaks every page.
- **CSRF token:** every form includes `@csrf`; `VerifyCsrfToken` checks it. The token
  lives in the session and is exposed via the `<meta name="csrf-token">` tag.
- **Locale cookie / preference:** the language switch persists the chosen locale.
- **What we send to the browser:** only functional cookies (session + CSRF +
  locale). No third-party tracking cookies are set by the app.
- **Cookie-consent banner:** **not yet implemented.** Because only essential cookies
  are used, it is lower priority, but it is a recommended launch item (see roadmap).
  When added, it should be a small dismissible banner storing the choice in
  `localStorage`, linking to `/privacy`.

---

## 4. Security response headers

`app/Http/Middleware/SecurityHeaders.php` (registered globally in `Http/Kernel`)
adds on every response:
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN` (anti-clickjacking)
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: browsing-topics=(), camera=(), microphone=(), geolocation=()`
- `X-XSS-Protection: 0` (modern guidance: disable the legacy auditor)
- `Strict-Transport-Security: max-age=15552000` — **only over HTTPS**.

**No Content-Security-Policy yet** — a strict CSP would break inline Alpine/Vite.
Recommended next step: add a CSP in **report-only** mode first, then enforce once
clean.

---

## 5. Backup & restore module

`app/Services/BackupService.php` + `Admin/BackupController` + `admin/backup/index`.
Admin sidebar → **Backup** (super_admin only).

- **Export:** serializes every application table to a single JSON document and
  streams it to the browser as a download. No `mysqldump` needed (pure PHP), which
  suits Railway's ephemeral disk (the file is never stored on the server).
- **Restore:** uploads a JSON snapshot and replays it **inside one transaction**,
  using `DELETE` (not `TRUNCATE`, which auto-commits) with foreign-key checks
  disabled, so a failure rolls back cleanly. Idempotent round-trip verified.
- **Driver-agnostic:** works on MySQL (prod) and SQLite (local).
- **Excluded tables:** framework/transient ones (`migrations`, `sessions`, `cache`,
  `jobs`, `password_reset_tokens`, `personal_access_tokens`, …).
- **Sensitivity:** the export contains hashed passwords and encrypted 2FA data —
  treat the file as a secret; download over HTTPS; never commit it.

> Note: the backup covers the **database only**. Uploaded image *files* live on
> Cloudinary (persistent) and are referenced by URL inside the data, so they survive
> a restore without being embedded.

---

## Security checklist before a public launch

- [ ] `APP_KEY` set on Railway (and backed up somewhere safe, never in Git).
- [ ] Default admin password changed from `password`.
- [ ] `APP_DEBUG=false` in production.
- [ ] 2FA enabled on the owner's account (tested login → challenge → in).
- [ ] A fresh backup exported and stored off-Railway.
- [ ] Cloudinary configured (so uploads persist).
- [ ] Real SMTP configured (so lead notifications actually send).
- [ ] (Recommended) CSP added in report-only mode; cookie-consent banner added.
