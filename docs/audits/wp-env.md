# wp-env audit — Canopy v2

Date: 2026-10-14. Branch: `audit/v2-wp-env`. Docker unavailable in worker
(`docker info` fails); Node v26.11.1. No containers started — every claim
below is verified in source (compose, Dockerfile, nginx conf, php.ini,
Makefile, `.env.example`, `config/`, `README.md`).

Note: `.agents/skills/wp-env/SKILL.md` is not versioned in this worktree
(gitignored, only `canopy` is kept); the skill text was provided for this
audit. No `references/` directory ships with it, so there was no detector
script to run. Classification below is manual.

## 1. Verdict: wp-env N/A by design — do NOT add

No `.wp-env.json` or `.wp-env.override.json` exists in the repo (glob
confirmed). That absence is correct and must be kept:

- **Bedrock layout defeats wp-env auto-detection.** The skill scans the
  project root for a plugin header, a `style.css` theme header, or
  `wp-includes/version.php`. This repo has none of those at root: core is
  Composer-managed at `web/wp/` (gitignored), the theme lives at
  `web/app/themes/canopy/`, content dir is `web/app/`
  (`config/application.php:81-83`). Detection would report nothing useful.
- **Split URL breaks wp-env assumptions.** `WP_HOME` (site) vs `WP_SITEURL`
  (`${WP_HOME}/wp`, `.env.example:30-32`) puts core in a subdirectory.
  wp-env serves core at the root; mapping this layout needs custom
  `mappings`/`config` that would duplicate `docker-compose.yml`.
- **Custom services have no wp-env equivalent.** `docker-compose.yml`
  provides nginx + php-fpm, MariaDB 11, redis, and Mailpit in one stack.
  wp-env gives MySQL only, no redis, no mail catcher, no nginx.
- **Two environments side by side is a support trap.** Keeping both means
  two port sets, two DB engines (MariaDB vs MySQL), two WP-CLI paths
  (`make wp` / `docker compose exec php wp` vs `wp-env run cli wp`).

## 2. Local parity matrix (Compose vs wp-env checklist)

| wp-env concern (skill §Verification) | Canopy equivalent | Status |
|---|---|---|
| Running env + ports | `make up`; nginx `${NGINX_PORT:-8080}`, db `${DB_PORT:-33060}`, Mailpit UI `8025` / SMTP `1025` (`docker-compose.yml:8-64`) | OK |
| WP admin loads | `make setup` installs core, activates `canopy`, sets permalinks (`Makefile:32-58`); admin at `$WP_HOME/wp/wp-admin` (admin/admin) | OK |
| WP-CLI works | `make wp` (simple) / `docker compose exec php wp --allow-root` (flags) (`Makefile:23-24`, `wp-cli.yml` pins `path: web/wp`) | OK, with caveat §3.5 |
| Theme visible in admin | `wp theme activate canopy` in `make setup` (`Makefile:51`) | OK |
| DB reset | No equivalent of `wp-env reset` — no Makefile target, no docs | Gap §3.4 |
| Xdebug | Not installed, not documented | Gap §3.3 |
| PHP version parity | `php:8.5-fpm-bookworm` (`Dockerfile:4`) matches `composer.json` platform 8.5; extensions cover gd/mysqli/pdo_mysql/zip/intl/imagick/redis | OK |
| Debug constants | `config/environments/development.php` sets `WP_DEBUG`, `SCRIPT_DEBUG`, `SAVEQUERIES`, `WP_DEBUG_LOG`; production gates LiteSpeed constants on `SERVER_SOFTWARE` | OK |
| Mail in dev | `php.ini:21` routes `sendmail` to Mailpit; blank `SMTP_HOST` skips SMTP (`Site.php:486-488`) so dev mail uses PHP `mail()` | Broken §3.2 |

## 3. Findings

| # | File:line | Severity | Finding | Status |
|---|---|---|---|---|
| 1 | `.docker/php/Dockerfile:4-44` + `.docker/php/php.ini:21` | Medium | `sendmail_path = "mailpit sendmail -t"` but the php image never installs a `mailpit` binary (only the `mailpit` service container exists). With blank `SMTP_HOST`, `configureSMTP()` returns early and dev mail falls through to `mail()` → missing binary → silent send failure. Mailpit UI will stay empty. | Deferred: fix means adding the Mailpit binary (or msmtp relay to `mailpit:1025`) to the Dockerfile + rebuild; Docker is unavailable here so the fix cannot be built or tested. |
| 2 | `docker-compose.yml:10,35,61-62` + `.env.example` (before fix) | Low | Port overrides (`NGINX_PORT`, `DB_PORT`, `MAILPIT_UI_PORT`, `MAILPIT_SMTP_PORT`) existed only as compose-level defaults — the wp-env skill's top port-conflict failure mode had no documented knob. | Taken: documented as commented vars in `.env.example` (inert, Dotenv-safe). |
| 3 | repo-wide (grep: zero hits outside skill) | Low | No Xdebug story: extension absent from Dockerfile, no `Makefile` toggle, no IDE/`launch.json` docs, no troubleshooting row. wp-env gives this via `start --xdebug` + port 9003. Contributors get `SAVEQUERIES`/debug log only. | Deferred: product call (image size + port wiring + docs); needs a staffed checkout with Docker to verify the debug connection. |
| 4 | `Makefile:1-61` | Low | No DB-reset / clean-volume path (`wp-env reset` equivalent). `down` keeps `db_data`; a corrupted dev DB has no one-command recovery, and nothing documents `docker compose down -v` (destructive) vs a soft reset. | Deferred: new Make target + docs; behavior-adjacent, needs Docker to test. |
| 5 | `Makefile:24` | Info | `make wp` swallows stderr (`2>/dev/null`). WP-CLI errors (e.g. "Strange wp-config.php", DB connection refused before `db` is healthy) print nothing, which defeats the troubleshooting section it would send users to. | Deferred: removing the redirect changes CLI output contract; one-line change but needs a live stack to confirm no noise regression. |
| 6 | `README.md:178` (fixed) | Info | Structure map listed the compose stack as "(nginx, php, mariadb, mailpit)" — redis missing. | Taken: now "(nginx, php, mariadb, redis, mailpit)". |
| 7 | `README.md:68,83,111` (fixed) | Info | Manual-setup block used `WP_HOME=http://localhost` / `--url=http://localhost` while compose defaults to `8080` (`docker-compose.yml:11`, `.env.example:31`). Fresh manual installs would register the wrong site URL. | Taken: all three now `http://localhost:8080`, matching `make setup` behavior. |

## 4. Passes (verified, no action)

- **No wp-env config to remove or reconcile.** Glob for `.wp-env*` at root: no files. Nothing references `@wordpress/env` outside the skill text.
- **Boot chain intact for the Compose path.** `web/index.php` loads only `wp-blog-header.php`; `web/wp-config.php` keeps `WP_CACHE` guard → autoload → `config/application.php` → literal `wp-settings.php` (WP-CLI grep target intact).
- **DB parity documented in-source.** `.env.example:9-16` states defaults match `docker-compose.yml` ("change both if you change either"); values verified equal (`canopy_local` / `dev` / `dev` / `db`).
- **php.ini dev-sane.** `upload_max_filesize`/`post_max_size` 128M agree with nginx `client_max_body_size 128M`; `opcache.revalidate_freq = 0` + `display_errors = On` + `error_log = /dev/stderr` suit `make logs`.
- **Cron story consistent.** `DISABLE_WP_CRON` default-true in production with real-cron example, re-enable knob documented (`.env.example:37-40`, `production.php:14-20`).
- **Known-issue cross-check.** The SMTP port example-vs-code gap (`465`/`ssl` vs `587`/`tls` fallback) stays tracked in `docs/KNOWN-ISSUES.md`; not re-opened here. `filemtime()` guards reported by earlier audits are present (`Site.php:89,96`).

## 5. Verification

- `grep -rin xdebug` (excluding skill, vendor, `web/wp`): zero hits — finding 3 confirmed absent, not merely undocumented.
- `grep` mail path (`php.ini:21`, `Site.php:484-505`, `.env.example:68-78`): finding 1 chain confirmed end to end.
- Edits are docs-only (`README.md` 4 lines, `.env.example` 6 commented lines); re-read after edit. No PHP touched, so `composer lint` / `composer phpstan` are unaffected (and unrunnable here: `vendor/` absent, Docker unavailable).
- Docker-dependent claims (findings 1, 3–5) deliberately left as Deferred with fix sketches; no Dockerfile/compose/Makefile behavior changed.
