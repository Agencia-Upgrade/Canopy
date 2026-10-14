# WP Project Triage — Canopy

Audit of project classification, tooling, and version signals for the Canopy
boilerplate (Bedrock + Timber 2 + Twig 3). Branch: `audit/v2-wp-project-triage`.

## Scope

- Classify the repository (plugin / theme / block theme / site / core).
- Inventory PHP/Node tooling, test harness, and WP-CLI presence.
- Record version hints and their sources.
- Apply only trivial safe fixes inside triage scope. No refactors.

Out of scope: theme behavior fixes, docs rewrites, detector rewrites. Those
belong to their own audits and are listed as deferred below.

## Method

1. Ran the triage detector from the `wp-project-triage` skill:
   `node <skill>/scripts/detect_wp_project.mjs .` — output validated against
   `references/triage.schema.json` (`project.kind`, `signals`, `tooling` present).
   Result: `kind: ["unknown"]`. Treated as a signal to investigate, not as truth.
2. Built the `code-review-graph` knowledge graph first (full rebuild: 22 files,
   44 nodes, 383 edges, 2 communities), then verified every claim below in source.
   Graph communities: `src-register` (PHP, `Site.php`) and `scripts-show` (JS,
   `site.js`). Twig views are not parsed by the graph; checked with `grep`.
3. Verified manually: `composer.json`, `composer.lock`, `config/application.php`,
   `config/environments/*.php`, `web/index.php`, `web/wp-config.php`, `wp-cli.yml`,
   `web/app/themes/canopy/` (all 8 PHP templates, `src/Site.php`,
   `functions.php`, `style.css`), `Makefile`, `docker-compose.yml`, `.gitignore`.

## Classification

Manual result: **Bedrock-based `wp-site` + classic Timber `wp-theme`**
(not a block theme: no `theme.json`, no `block.json` anywhere in the theme).
`web/app` is the content dir (`WP_CONTENT_DIR`, `config/application.php:81-83`);
`web/wp` is Composer-managed core (gitignored, do not edit).

## Version / tooling matrix

| Signal | Value | Source |
|---|---|---|
| WordPress core | 7.0 | `composer.lock` (`roots/wordpress`), `style.css:8-9` (Requires 7.0, Tested 7.0) |
| Timber | 2.5.1 (`v2.5.1`) | `composer.lock` (`timber/timber`), `composer.json:46` (`^2.1`) |
| Bedrock autoloader / disallow-indexing / wp-config | 1.1.0 / 2.1.0 / ^1.0 | `composer.lock`, `composer.json:42-45` |
| PHP | >= 8.5 (platform pinned 8.5) | `composer.json:39,61-63`, `style.css:10`, `phpstan.neon:6` |
| PHP lint / static analysis | Pint PSR-12 / PHPStan level 5 (theme `src/` + hierarchy PHP) | `composer.json:91-95`, `pint.json`, `phpstan.neon` |
| Node / build step | none (no `package.json`) | repo root listing; JS is unbuilt islands |
| Tests | none (no PHPUnit, wp-env, Playwright, Jest) | detector `tooling.tests`, root listing |
| WP-CLI | yes (`path: web/wp`) | `wp-cli.yml`, `Makefile:23-24` |
| Local stack | nginx / php / mariadb:11 / redis / mailpit | `docker-compose.yml`, `Makefile:32-58` |
| Object/page cache | redis host config; LiteSpeed Cache optional via `suggest` (constants gated on `SERVER_SOFTWARE`) | `config/application.php:166-167`, `config/environments/production.php`, `composer.json:49-51` |

## Findings

| # | Location | Severity | Issue | Action |
|---|---|---|---|---|
| 1 | detector `detect_wp_project.mjs:294-297` (checks only `<root>/wp-content`) | Medium | False negative: reports `kind: ["unknown"]` on a Bedrock layout because the content dir is `web/app`, not `wp-content`. Same blind spot for drop-in/plugin scans (`wpCliHints` aside, all path signals null). | Deferred. Detector fix belongs upstream with the skill owner; the skill directory is not versioned on this branch (`.gitignore` keeps only `canopy`). Manual classification above stands in for the report. |
| 2 | `web/app/themes/canopy/src/Site.php:38,451-461` | Low | Sitemap URL appended to `robots_txt` on live hosts while `wp_sitemaps_enabled` returns false. URL 404s unless an SEO plugin serves it. | Deferred (product decision). Already tracked in `docs/KNOWN-ISSUES.md`. No change. |
| 3 | `web/app/themes/canopy/src/Site.php:86,93` | Low | `filemtime()` on `main.css` / `site.js` without existence check; a missing asset is a PHP warning. | Deferred. A `file_exists` guard is trivial but outside triage scope (theme behavior, needs its own change + lint/phpstan run). Already tracked in `docs/KNOWN-ISSUES.md`. No change. |
| 4 | `.env.example:73-74` vs `web/app/themes/canopy/src/Site.php:491-492` | Info | Example shows `465`/`ssl`; code fallback is `587`/`tls` when env vars are unset. | Deferred (docs/example alignment). Already tracked in `docs/KNOWN-ISSUES.md`. No change. |
| 5 | `docs/TIMBER.md` vs theme | Info | Pagination example uses deprecated `Timber::get_pagination()` + `pagination` key (theme uses `posts.pagination`); related-posts example queries inside the loop; `{{ post.content }}` printed without `|raw`. Theme verified correct in all three cases. | Deferred to a docs audit. No change. |
| 6 | repo root | Info | No test harness of any kind. Consistent with a starter boilerplate, but means triage `tooling.tests` signals are all false and stay false. | Deferred (by design). No change. |

## Correctness notes (verified, no issue)

- `web/index.php:6-7` loads only `wp/wp-blog-header.php`. No autoload/config load.
- `web/wp-config.php:4,11-13` keeps the `WP_CACHE` guard, autoload →
  `config/application.php` → literal `wp-settings.php` (WP-CLI grep target intact).
- All 8 theme PHP templates are thin (`Timber::context()` → `Timber::render()`).
  `search.php` adds only `found_posts`. No `get_posts` calls in Twig; no
  `new Timber\*` instantiations; boot is `Timber::init()` + `new Canopy\Site()`.
- Escaping: `search_query|e` in `search-form.twig:8` and `search.twig:5`;
  `esc_attr` on meta description (`Site.php:191`); `wp_json_encode` for
  JSON-LD (`Site.php:283-289`); `|raw` limited to WP-sanitized `post.content`
  and `json_encode` output.

## Verification

- Detector JSON parses; `project.kind`, `signals`, `tooling` present.
- No PHP in theme `src/` was changed, so `composer lint` / `composer phpstan`
  were not runnable here either: `vendor/` is absent and Docker is unavailable
  in this environment. CI or a staffed checkout should run them on the next
  theme change as usual.
