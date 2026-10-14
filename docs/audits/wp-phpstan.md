# WP PHPStan — Canopy

Audit of PHPStan static-analysis setup for the Canopy theme (Bedrock +
Timber 2 + Twig 3). Branch: `audit/v2-wp-phpstan`.

## Scope

- `phpstan.neon`: path coverage (theme `src/` + hierarchy PHP), level 5
  sufficiency, baseline need, WordPress-specific typing gaps.
- `composer.json` `phpstan` script (memory limit, binary path).
- Stubs: `szepeviktor/phpstan-wordpress` installed and wired.

Out of scope: theme behavior, Twig, CSS/JS, `Template Name:` headers on
`index.php`/`404.php` (belongs to a template/hierarchy audit), level
ratchet decision. Those are deferred below.

## Method

1. Read `.agents/skills/wp-phpstan/SKILL.md` + all three references,
   `.agents/skills/canopy/references/php.md`, `AGENTS.md`, `phpstan.neon`,
   `composer.json`.
2. Built the `code-review-graph` knowledge graph first (full rebuild:
   22 files, 44 nodes, 387 edges, 2 communities), then verified every
   claim below in source. Hub/bridge nodes confirm `Site` (class),
   `breadcrumbs`, `printSchema`, `enqueueAssets`, `updateRobotsTxt` as
   the analysis hotspots — all inside neon scope.
3. `composer install`, then ran analysis:
   - `composer phpstan` (512M): OOM crash in parallel worker.
   - `--memory-limit=2G` level 5 (neon): clean.
   - `--level=6`: clean. `--level=7`/`--level=8`: 1 error,
     `src/Site.php:461` (`offsetAccess.nonOffsetAccessible`).
4. Applied two trivial safe fixes, re-ran: level 5, 6, 7, 8 all clean;
   `composer lint` (Pint) passes.

## Findings

| Location | Severity | Status | Note |
|---|---|---|---|
| `composer.json:94` memory limit 512M | medium | taken | Parallel worker OOM-crashed on this repo. Bumped script to `--memory-limit=2G`. |
| `web/app/themes/canopy/src/Site.php:456-466` `updateRobotsTxt` | medium | taken | `parse_url()` returns `array\|false`; on `false`, `$host=''` passed the localhost checks and `$home['scheme']` would fatal. Added `!is_array` early return; `$scheme = $home['scheme'] ?? 'https'` fallback. Level 7/8 now clean. |
| neon `paths` vs theme PHP files | ok | — | All 8 theme PHP files (`functions.php` + 7 hierarchy files) listed; `src/` covered. Matches canopy skill §Quality. |
| `excludePaths` (`vendor`, `web/wp`, mu-plugins, plugins) | ok | — | First-party only. Correct per skill. |
| Stubs (`szepeviktor/phpstan-wordpress` v2.0.3 + `php-stubs/wordpress-stubs`) | ok | — | Installed; `extension.neon` included. No unknown-class/function errors. |
| Baseline | ok | — | No `phpstan-baseline.neon` exists and run is clean — none needed. Do not create one pre-emptively. |
| `ignoreErrors` | ok | — | None present; nothing masked. Keep empty. |
| Level 5 sufficiency | low | deferred | Codebase is clean through level 8 after the guard fix. Ratcheting neon to 7 is a maintainer decision (stricter CI, more churn on future code); left at 5, no scope creep. |
| `mailFrom()` `parse_url(..., PHP_URL_HOST)` null path | low | deferred | Returns `"noreply@"` if host unparseable (never in practice; `home_url()` always has a host). Level 8 does not flag. Optional `?? 'localhost'` fallback. |
| Untyped Twig-filter closure `addFiltersToTwig` (`$text`, `$length`) | low | deferred | Zero runtime effect; levels 6–8 do not flag it. Optional PHPDoc hardening only. |
| `Template Name:` headers in `index.php`, `404.php` | out of scope | deferred | Canopy skill forbids them as selectable page templates. Belongs to a template/hierarchy audit, not neon scope. |

## Verification

- `composer lint` — passes.
- `composer phpstan` (now 2G) — `[OK] No errors`.
- `vendor/bin/phpstan analyse --level=8` — `[OK] No errors`.
