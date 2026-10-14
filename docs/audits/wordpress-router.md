# wordpress-router audit — Canopy v2

Date: 2026-10-14. Branch: `audit/v2-wordpress-router`. Graph: 22 files, 44 nodes, 383 edges (PHP/JS only; Twig/config not indexed). Source verified manually.

Note: `.agents/skills/wordpress-router/SKILL.md` does not exist in this worktree. Classification and routing below use `AGENTS.md` plus `.agents/skills/canopy/` (SKILL.md load index, `architecture.md`, `timber-twig.md`, `php.md`, `gotchas.md`) as source of truth.

## 1. Classification

Bedrock + Timber 2.x + Twig 3 classic theme. Not a block theme, not a plugin, no REST surface, no custom blocks.

- Bedrock layout: `web/` docroot, `web/wp/` core (untouched), `web/app/` content dir, `config/application.php`, `composer.json` (`roots/wordpress ^7.0`, `timber/timber ^2.1`, PHP >= 8.5). Boot chain intact: `web/index.php` only loads `web/wp/wp-blog-header.php`; `web/wp-config.php` keeps `WP_CACHE` guard, autoload, `config/application.php`, literal `wp-settings.php`.
- Theme boot: `web/app/themes/canopy/functions.php:14-16` runs `Timber::init()` then `new Canopy\Site()`. PSR-4 `Canopy\` maps to `src/`. All hierarchy files (`404`, `archive`, `front-page`, `index`, `page`, `search`, `single`) are thin `Timber::context()` + `Timber::render()` wrappers.
- No `theme.json`, no `block.json`, no `register_block_type`, no `register_rest_route`, no `show_in_rest`, no CPT/taxonomy registrations (stubs empty). `views/` is flat `layouts/partials/components/templates`. Pagination uses `posts.pagination` + `partials/pagination.twig`. Escaping follows Timber autoescape-off convention (`|raw` on `post.content`, `|e` on `search_query`).

## 2. Routing decisions

| Skill / workflow | Applies | Why |
|---|---|---|
| `canopy` (theme, Timber, Twig, islands, `cnp-` CSS) | Yes, primary | All findings are theme routing, context, hierarchy, Twig. |
| Classic PHP-theme hierarchy + CPT/tax workflow (`canopy` recipes, `php.md`) | Yes | `front-page.php` front handling, `single/archive` CPT catch-alls, empty `registerPostTypes`/`registerTaxonomies` stubs. |
| `wp-project-triage` style inspection | Yes, done | Used for classification above. |
| `wp-performance` (backend) | Partial | `enqueueAssets`/`dequeueBlockStyles`/`filemtime` versioning only. No query/perf regression found. |
| `wp-wpcli-and-ops` | Partial | `wp-cli.yml` present; rewrite flush needed only when CPTs are added. No action. |
| `wp-block-themes` / `theme.json` | No | No `theme.json`; classic Timber theme by design. Do not add. |
| `wp-block-development` | No | No blocks, no `block.json`. |
| `wp-interactivity-api` | No | Frontend is vanilla islands (`data-island`), not Interactivity API. |
| `wp-rest-api` | No | No routes, no controller, no `register_rest_field`/meta exposure. |
| `wp-plugin-development`, `wp-plugin-directory-guidelines` | No | No plugin code. |
| `wp-abilities-api`, `wp-abilities-audit`, `wp-abilities-verify` | No | No abilities surface. |
| `wp-playground` / `blueprint` | No | No Playground packaging in scope. |
| `wp-env`, `wp-phpstan` (beyond theme scope) | Partial / No | Docker/`Makefile` env exists; PHPStan scope is theme-only per `phpstan.neon` (unchanged). |

## 3. Findings

| # | File:line | Severity | Finding |
|---|---|---|---|
| 1 | `web/app/themes/canopy/src/Site.php:38` + `:449-460` | Medium | Sitemap advertised, sitemaps off. `wp_sitemaps_enabled` returns false while `updateRobotsTxt()` appends `Sitemap: …/sitemap.xml` on live hosts. URL 404s unless an SEO plugin serves it. Known issue, still reproduces. Deferred: product decision (enable sitemap provider or drop line). |
| 2 | `web/app/themes/canopy/front-page.php:1-18` | Low (by design) | `front-page.php` always wins site front and never loops `posts`. Reading "latest posts" shows hero + optional page body, not a post list. Website-first intent; document before changing. |
| 3 | `web/app/themes/canopy/404.php:4`, `index.php:4` (also `archive.php:4`, `search.php:4`, `single.php:4`, `page.php:4`, `front-page.php:4`) | Low | `Template Name:` on fallback/hierarchy files lists `404`/`Index` as assignable page templates. Assigning them to a page is a misuse trap (`canopy` `php.md` warns against it). Deferred: removing headers changes WP admin listing; needs product call. |
| 4 | `docs/TIMBER.md:162`, `:229-236` + related-posts/escaping snippets | Low (docs) | Doc contradicts theme: `Timber::get_pagination()` + `pagination` key (theme uses `posts.pagination`), `addToContext()` assigning `posts` (theme does not), related posts via `Timber.get_posts` in Twig loop (banned; query in PHP), `{{ post.content }}` without `\|raw`. Deferred: separate docs workstream, tracked in `docs/KNOWN-ISSUES.md`. |
| 5 | `web/app/themes/canopy/src/Site.php:388` (fixed) | Low | `get_search_query()` default `$escaped=true` double-escaped through Twig `search_query\|e` (`search-form.twig`, `search.twig`). Changed to `get_search_query(false)`; Twig `\|e` is now the single escape point. |
| 6 | `web/app/themes/canopy/src/Site.php:82-97` (fixed) | Low | `filemtime()` without existence guard warned on missing `main.css`/`site.js`. Now `file_exists() ? (string) filemtime() : null`. |
| 7 | `web/app/themes/canopy/src/Site.php:460` | Info | `updateRobotsTxt()` reads `$home['scheme']` without guard; malformed `home_url()` yields empty scheme notice. Not observed; no change. |
| 8 | `web/app/themes/canopy/src/Site.php:295-372` | Info | `breadcrumbs()` has no page-parent chain and no CPT-archive branch; pages show Home + current only. Acceptable for starter; extend per project. No change. |

No Timber 1 API violations in shipped PHP/Twig (`new Timber\Post/Menu`, `Timber::get_context()`, `Timber::get_pagination()`, query-string `get_posts`, `timber_context`, `timber/twig` env hook): none found. `search.php` `found_posts` via `$wp_query->found_posts` is consistent with `feed.twig` count block. `logo.svg` referenced by `printSchema()` exists at `assets/images/logo.svg`.

## 4. Verification

- `composer lint` (pint --test): pass after fixes.
- `composer phpstan` (level 5, theme scope): pass with `--memory-limit=2G`; default 512M OOMs in worker env (infra, not code).
- Twig/CSS/JS not statically analysed per `phpstan.neon`; `views/` checked by read-through only.
