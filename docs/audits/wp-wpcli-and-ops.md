# Audit: wp-wpcli-and-ops (v2)

## Scope

WP-CLI config and ops surface of Canopy: `wp-cli.yml` targeting, safe
`search-replace` guidance, rewrite-flush needs (CPT stubs empty), cache flush,
cron, multisite (expected N/A). Static audit only — no `wp` commands run, no DB
writes. Worktree branch: `audit/v2-wp-wpcli-and-ops`.

## Method

- Read `.agents/skills/wp-wpcli-and-ops/SKILL.md` + all refs (`safety`,
  `search-replace`, `cron-and-cache`, `multisite`, `automation`), plus
  `AGENTS.md`, `Makefile`, `wp-cli.yml`, `docker-compose.yml`.
- `code-review-graph`: `get_minimal_context_tool` → `status: not_ready`
  (`missing_graph`). Ops surface is config/docs/shell, not indexed PHP/JS, so
  graph has low signal here by design; verified in source instead.
- Grep worktree for `register_post_type|register_taxonomy|cron|transient|
  wp_cache|rewrite.*flush|CACHE|REDIS|OBJECT_CACHE` and separately for
  `search-replace|search_replace|db export|db import|--dry-run|--allow-root`.
- Grep `config/` for `MULTISITE|SUBDOMAIN|DOMAIN_CURRENT|WP_ALLOW_MULTISITE` →
  zero matches (single-site confirmed).
- Checked WP-CLI handbook (`make.wordpress.org/cli/handbook/references/config/`)
  for `wp-cli.yml` env-var interpolation behavior.
- Read `docs/DEPLOYMENT.md`, `.env.example`, `config/application.php:160-167`
  (Redis), `config/environments/{production,staging}.php` (cron, LiteSpeed),
  `.github/workflows/{deploy-production,auto-update}.yml`,
  `.docker/php/Dockerfile:37-40` (WP-CLI installed in php image),
  `web/app/themes/canopy/src/Site.php:68-74` (CPT stubs),
  `README.md:60-139,222-269` (WP-CLI usage, CPT example, manual deploy).

## Findings

| # | Location | Severity | Status | Note |
|---|----------|----------|--------|------|
| 1 | `wp-cli.yml:4` | info | confirmed OK | `path: web/wp` is the correct Bedrock target (core subdir). `make wp` relies on yml discovery from container cwd `/var/www/html`; both deploy workflows pass `--path` explicitly and do not depend on discovery. `server.docroot`/`apache_modules` only affect `wp server`; harmless. |
| 2 | `wp-cli.yml:5` | low | fixed | `url: ${WP_HOME}` relied on env interpolation in yml values, which the WP-CLI handbook does not document (static values only; upstream `wp-cli/wp-cli#5566` still open, interpolation PRs cover aliases). Single-site derives URL from DB, so the line bought nothing and risked a literal `${WP_HOME}` URL context. Removed with an explanatory comment. |
| 3 | `docs/DEPLOYMENT.md:257` | low | fixed | Scheduled-backup crontab ran `wp db export …` with no `cd`/`--path`, so `wp-cli.yml` discovery fails under cron cwd (`$HOME`) → "not a WP install". Prefixed with `cd /home/deploy/example.com &&`, matching the established pattern in `.env.example:38` and `production.php:17`. |
| 4 | `docs/DEPLOYMENT.md:71-73,168-174` | low (docs) | fixed | Manual-deploy block omitted `clear_cache_twig`, which both GH workflows run alongside `clear_cache_timber`; rollback block omitted `rewrite flush`. Added both lines for parity with what CI actually executes. |
| 5 | `docs/DEPLOYMENT.md` (new) | low (docs) | fixed | Zero `search-replace` mentions repo-wide: no domain-migration runbook (skill requires backup → `--dry-run` → replace → flush). Added a compact "Domain migration" subsection with the safe sequence. |
| 6 | `Makefile:24` | low | deferred, no change | `wp` passthrough target appends `2>/dev/null`, hiding WP-CLI errors (wrong path, DB down) during ops. Left as-is: changing redirect behavior is beyond trivial, and `setup` uses the same redirect to tolerate idempotent re-runs. Revisit if ops debugging suffers. |
| 7 | `src/Site.php:68-74` | info | N/A by design | `registerPostTypes`/`registerTaxonomies` empty stubs on `init` default priority 10 (meets ≤10 rule when filled). No CPT → no rewrite-flush obligation beyond what `make setup` and both deploy workflows already do unconditionally. When a CPT lands, keep registration on `init` and keep the existing flush steps. |
| 8 | multisite / cron / cache | info | N/A or OK | Multisite N/A (no defines, finding 0 matches). Cron OK: `DISABLE_WP_CRON=true` in staging/prod with real-cron example (`*/5 … wp cron event run --due-now --quiet`). Cache flush OK: `wp cache flush` + Timber cleaners in both workflows. `make setup` default `admin/admin` is dev-local only. |

No destructive commands run. Code changes: none (PHP untouched). Config/docs fixes only:
`wp-cli.yml` (`url` removal + comment), `docs/DEPLOYMENT.md` (cron `cd`,
twig-clear/rewrite-flush parity, search-replace runbook).

## Verification

- Re-run the greps in Method; expect: `search-replace` hits only in
  `docs/DEPLOYMENT.md` (new runbook) + skill refs; `MULTISITE` still zero.
- `git diff --stat` shows only `wp-cli.yml` + `docs/DEPLOYMENT.md`.
- `composer lint` / `composer phpstan` not run: no PHP touched.
