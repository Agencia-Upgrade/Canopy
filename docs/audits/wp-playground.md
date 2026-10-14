# wp-playground audit — Canopy v2

Date: 2026-10-14. Branch: `audit/v2-wp-playground`. Source verified manually. No browser runs, per task scope.

Skill source: `.agents/skills/wp-playground/SKILL.md` (routing wrapper) + `references/cli.md`, `references/website.md`. No `blueprint` work undertaken; Blueprint schema stays in `blueprint` skill.

## 1. Current state

No Playground packaging exists in this repo. Verified: no `*playground*` or `*blueprint*` files, no Blueprint JSON, no snapshots, no CLI config, no docs references.

Canonical local env is Docker + `Makefile` (`make setup`, `make up`, `make wp`), backed by MySQL/Redis. That stays canonical after this audit.

## 2. Fit assessment: Bedrock + Timber vs Playground

Playground is a disposable SQLite/WASM sandbox with standard `wp-content` layout assumptions. Canopy is a Bedrock Composer stack. Gaps, narrowest first:

| # | Gap | Source | Effect |
|---|---|---|---|
| 1 | Non-standard content dir | `config/application.php:81-83` (`CONTENT_DIR /app`, `WP_CONTENT_DIR web/app`, `WP_SITEURL WP_HOME/wp`) | `start --auto-mount` and default `--mount` examples target `/wordpress/wp-content/...`; they do not map to `web/app/...`. Every mount needs an explicit custom VFS path. |
| 2 | Composer autoload required | `web/wp-config.php` (autoload), `composer.json:86-90` (PSR-4 `Canopy\`), `web/app/themes/canopy/functions.php:11-16` (`use Canopy\Site; use Timber\Timber`) | Mounting only `web/app/themes/canopy` leaves `Canopy\Site` unloadable and `Timber\Timber` missing (vendor/ is not mounted, no `composer install` inside WASM). Theme fatals on boot. |
| 3 | Timber via Composer, not wp.org | `composer.json:46` (`timber/timber ^2.1`) | A Blueprint could `installPlugin` the wp.org `timber-library` build instead, but version parity with `^2.1` must then be pinned and re-checked on every bump. Two dependency channels for one library. |
| 4 | MySQL-first config | `config/application.php:92-107`, `.env.example:13-16` | Playground persists to SQLite. Bedrock forces MySQL defines (`DB_NAME/USER/PASSWORD/HOST`, `DATABASE_URL` branch). No SQLite path exists; a Playground boot would need Blueprint-defined constants or a config fork. |
| 5 | PHP version ceiling | `composer.json:39` (`php >= 8.5`), skill compat (WP 7.0+, PHP 7.4.0+) | Playground `--php=` values track WASM builds; PHP 8.5 availability is not confirmed in this repo's references. Pin `--php` explicitly if attempted; do not rely on moving defaults. |
| 6 | Share-link hosting cost | `references/website.md` (public URL + `Access-Control-Allow-Origin: *` required) | A Bedrock bundle (core + vendor + content) is a large self-hosted ZIP with CORS needs. No hosting exists for it. Query-API-only links (`?theme=`, `?plugin=`) cannot express this stack. |

Net: a theme-only Playground demo is possible but not trivial — it needs a Blueprint that installs `timber-library`, ships the theme as a ZIP (or mount), shims the missing Composer autoload, and works around the MySQL config. A full-Bedrock Playground replica is out of scope for the tool.

## 3. Recommendation

Defer Playground packaging. Keep Docker/`Makefile` as the single local env. No Blueprint, snapshot, or share link added in this audit.

If a lightweight theme demo is wanted later, in order:

1. Blueprint (kept out of this repo or under `playground/`): `installPlugin timber-library` (pinned), install theme ZIP, `setSiteOptions`, `activateTheme canopy`.
2. Validate headless first: `run-blueprint --blueprint=<file> --verbosity=debug`, then `server --blueprint=<file>` with explicit `--wp`/`--php` pins.
3. Snapshot only a repro state (`build-snapshot --outfile=./site.zip`); load the ZIP fresh before sharing.
4. Document the gaps above in the demo README so the SQLite/MySQL and Composer differences are not mistaken for prod behavior.

Escalation per skill guardrails: anything needing Redis, MySQL parity, SMTP/Mailpit, or durable persistence stays on Docker, not Playground.

## 4. Findings

No defects found in shipped code from a Playground lens. No Playground files to review. Nothing changed at runtime.

## 5. Verification

- Glob `**/*playground*`, `**/*blueprint*`: no files.
- Read `config/application.php`, `composer.json`, `.env.example`, `wp-cli.yml`, `Makefile`, `web/app/themes/canopy/functions.php`: gaps in §2 confirmed in source.
- No code edits made; nothing to lint or analyse. `git status` clean before commit (doc only).
