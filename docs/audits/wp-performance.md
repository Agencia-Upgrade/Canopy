# Audit: wp-performance (v2)

## Scope

Backend perf posture of Canopy theme (`web/app/themes/canopy`) + Bedrock config (`config/`): asset enqueue/`filemtime` versioning, block-library gating, object-cache notes, query posture (N+1, pagination), autoload/cron/HTTP. No live profiling environment — static audit only.

## Method

- Read `.agents/skills/wp-performance/SKILL.md` (+ refs `database`, `object-cache`, `autoload-options`, `cron`, `http-api`) and canopy skill + `AGENTS.md` (+ refs `architecture`, `timber-twig`, `php`).
- `code-review-graph`: `get_architecture_overview_tool` → 0 communities, 0 edges; `list_graph_stats_tool` → 0 files/nodes, never built for this repository. No graph nodes to trace; proceeded with source greps.
- Grep theme for `get_posts|WP_Query|query_posts|filemtime|wp_enqueue|update_option|set_transient|wp_remote|wp_schedule|cron|autoload|pre_get_posts|posts_per_page|no_found_rows|update_post_meta_cache|fields.*ids` → only enqueue/filemtime hits in `Site.php` + `found_posts` read in `search.php`.
- Grep `web/app` for `object-cache.php|add_option|set_transient|wp_remote_get|wp_schedule|pre_get_posts` → zero matches.
- Grep `config` for cache/cron/debug constants; read `Site.php` (full), all 7 hierarchy PHP files, `feed/teaser/single/pagination` Twig, `composer.json`, `web/wp-config.php`.
- No live `wp doctor`/`wp profile`/Query Monitor: no running install in this repository.

## Findings

| # | Location | Severity | Status | Note |
|---|----------|----------|--------|------|
| 1 | `web/app/themes/canopy/src/Site.php:77-97` | ok | confirmed | `filemtime` versioning guarded by `file_exists` (null fallback). Two `stat` calls per frontend request; negligible. Skill ref `php.md:123` claims no guard — stale, source wins. No change. |
| 2 | `Site.php:99-106,147-159` | ok | confirmed | Block-library gating coherent: `wp-block-library` + stored styles only on `is_singular()`; priority-100 dequeue strips `wp-block-library(-theme)`, `core-block-supports`, `global-styles`, `classic-theme-styles` elsewhere. `cleanFrontend()` removes global/classic/stored-style actions. No Gutenberg CSS on home/archives/search. No change. |
| 3 | `config/application.php:166-167`, `web/app/object-cache.php` (absent) | info | N/A by design | `WP_REDIS_HOST/PORT` defined but no drop-in installed, no Redis client in `composer.json`. Default per-request memory object cache only. Expected for starter; add persistent cache at deploy/infra layer, not in this audit. No change. |
| 4 | Theme PHP + `views/components/feed.twig`, `teaser.twig`, `partials/pagination.twig` | ok | confirmed | Query posture clean: zero custom queries, all templates render main query via `Timber::context()`. Pagination via `posts.pagination` collection API. No `get_posts` in Twig loops, no N+1. `search.php:16` reads `$wp_query->found_posts` only. No change. |
| 5 | `views/templates/single.twig:11-29` | ok | confirmed | Per-post lazy loads (`post.author`, `post.terms('category')`, `post.thumbnail`) on single-post view only — one object, not a loop. No batching owed. No change. |
| 6 | `Site.php:381-392` | info | accepted | `addToContext()` runs both `Timber::get_menu()` calls + `breadcrumbs()` on every view. Cost is two cached menu lookups + option reads; acceptable for starter. Lazy-loading menus would change behavior — out of scope. No change. |
| 7 | `Site.php:435-446` | ok | confirmed | Twig compile cache: `auto_reload` under `WP_DEBUG`, else `cache/twig` (created via `wp_mkdir_p`). Dir absent in repo (gitignored, created on first prod request, cleared on deploy). No change. |
| 8 | `config/application.php:137,146,149`, `config/environments/*.php` | ok | confirmed | Autoload/cron/HTTP N/A in theme code (no options writes, no transients, no `wp_remote_*`, no cron scheduling). Config sane: `DISABLE_WP_CRON` true in prod/staging, revisions capped at 5, `CONCATENATE_SCRIPTS` false, `SAVEQUERIES` dev-only. No change. |

No code changes made (nothing trivial-and-safe beyond current state; caching behavior changes explicitly out of scope).

## Verification

- Greps above are the evidence; re-run to confirm.
- `composer lint` / `composer phpstan` not run: no PHP touched.
