# WP Plugin Development — Canopy

Audit of plugin-architecture discipline applied to the Canopy theme
(Bedrock + Timber 2 + Twig 3). Branch: `audit/v2-wp-plugin-development`.

Canopy is a theme, not a plugin. There is no plugin bootstrap, no
`wp-content/plugins/` code, and no Settings API surface. This audit maps
each `wp-plugin-development` concern onto its theme equivalent: bootstrap
discipline, `Site.php` as the hook class, CPT/tax stubs, Customizer stub,
and the security baseline (nonces, capabilities, sanitization, escaping)
within theme scope.

## Scope

- Bootstrap/loader discipline (`functions.php`, `src/Site.php`).
- Hook registration, lifecycle (activation/uninstall has no theme analog
  beyond `switch_theme`; assessed as N/A).
- Settings/admin UI (Customizer stub; Settings API N/A for themes).
- Security: input handling, `$_GET`/`$_POST`, AJAX, `$wpdb`, nonces,
  capabilities, sanitization/escaping in PHP + Twig.
- Data storage, cron, migrations (none; assessed as N/A).
- One trivial safe fix. No plugin scaffolding, no refactors.

Out of scope: block development, patterns, Timber API detail, CSS/JS.
Those belong to their own audits and are listed as deferred below.

## Method

1. Read `.agents/skills/wp-plugin-development/SKILL.md` plus
   `references/structure.md`, `lifecycle.md`, `security.md`, and the
   `canopy` skill plus `AGENTS.md`.
2. Queried `code-review-graph` first (`get_architecture_overview_tool`,
   `get_hub_nodes_tool`): graph is empty for this repository (0 communities,
   0 hubs — Twig/PHP not yet indexed here). Treated as "not indexed", not
   as evidence, and verified every claim below in source.
3. Verified manually: `functions.php`, `src/Site.php` (all 505 lines),
   all 8 hierarchy PHP files, `grep` for `$_GET|$_POST|$_REQUEST`,
   `wp_ajax`, `register_setting`, `current_user_can`, `wp_verify_nonce`,
   `wpdb`, `update_option`, `register_post_type` across the theme, plus
   `views/` escaping paths (`|raw`, `|e`, `search_query`, `crumbs`,
   `archive_title`) and the `robots_txt` hook signature upstream.
4. Fix verified with `php -l`, `composer lint` (Pint passed), and
   `vendor/bin/phpstan analyse --memory-limit=2G` (`[OK] No errors`;
   the stock 512M limit OOMs in the audit environment regardless of code).

## Findings

| # | Location | Severity | Issue | Action |
|---|---|---|---|---|
| 1 | `web/app/themes/canopy/src/Site.php:454-458` (was `:454-461`) | High | `updateRobotsTxt()` mistook the `robots_txt` 2nd arg for a hostname (`string $host`). Upstream signature is `apply_filters('robots_txt', string $output, bool $public)` — the 2nd arg is blog-public status, so `$host` coerced to `"1"`/`""` and the Sitemap line rendered as `Sitemap: {scheme}://1…` (or `://…`): broken host on every live site, and the localhost/`.test`/`.local` guard never matched. Prior audits tracked only the product question (sitemap advertised while native sitemaps off); the signature bug itself was missed. | Taken. Host now derived from `parse_url(home_url())`; 2nd param renamed `mixed $public` (WP passes `blog_public` option value, not strict bool). Pint + PHPStan clean. The remaining product question (advertise `/sitemap.xml` at all) stays deferred in `docs/KNOWN-ISSUES.md`. |
| 2 | `views/components/breadcrumbs.twig:7,9`, `feed.twig:5`, `teaser.twig:3,10,15-17`, `single.twig:8,12,19,27`, `pagination.twig:4,11,20` | Low | With Timber Twig autoescape off, `crumb.url/title`, `archive_title`, `post.title/link`, `term.name/link`, pagination links render without `\|e`. All values are WP-sanitized or internal (`home_url`, `get_permalink`, `get_the_title`, Timber object fields) — same posture as core bundled themes, which do not escape `the_title()` either. | Deferred. Explicit `\|e` would be defense-in-depth but risks double-escaping filtered titles and touches every listing template; needs its own Twig-escaping pass with rendered-output checks. No change. |
| 3 | `src/Site.php:68-74` (`registerPostTypes`, `registerTaxonomies`) | Info | Empty stubs. Correct for a starter: no CPT means no rewrite-flush obligation (the plugin-lifecycle analog). When a CPT lands, registration must stay on `init` ≤ 10 with a flush path (`wp rewrite flush` / `switch_theme`). | Deferred by design. No change. |
| 4 | `src/Site.php:161-163` (`customizeRegister`) | Info | Empty stub; theme reads options via the `get_theme_mod` Twig passthrough. Settings API (`register_setting`, sanitize callbacks) is N/A — themes have no options-page surface here. | Deferred by design. No change. |
| 5 | `src/Site.php:38` + `:454` | Info | Sitemap line advertised while `wp_sitemaps_enabled` returns false (URL 404s without an SEO plugin). Product decision, already in `docs/KNOWN-ISSUES.md` and two prior audits. Finding 1 above fixed the hostname; this row is only the whether-to-advertise question. | Deferred (product decision). No change. |
| 6 | Repo root | Info | No test harness; no plugin packaging surface (`readme.txt`, assets build, release zip all N/A for a Bedrock theme). Verification is Pint + PHPStan only. | Deferred by design. No change. |

## Correctness notes (verified, no issue)

- Bootstrap discipline: `functions.php:14-16` is `Timber::init()` + `new Site()` only. No side effects at file load; all setup lives in the `Canopy\Site` hook class. Matches the skill's loader pattern.
- No `$_GET`/`$_POST`/`$_REQUEST`, `wp_ajax_*`, `$wpdb`, `update_option`, or `register_setting` anywhere in the theme. Only search input exists (`GET ?s=`), handled by WP core; theme reads it via `get_search_query(false)` (`Site.php:388`) and escapes late (`search_query|e` in `search-form.twig:8`, `search.twig:5`). Sanitize-early/escape-late holds.
- No nonce/capability surface: no forms processed, no admin pages, no AJAX handlers. Nothing to pair `wp_verify_nonce`/`current_user_can` with.
- `|raw` limited to WP-sanitized HTML (`post.content` ×3) and `json_encode` output (`faq.twig:23,26`); `esc_attr` on meta description (`Site.php:194`); `wp_json_encode` for JSON-LD (`Site.php:286-292`); `found_posts` int-cast (`search.php:16`).
- `getenv()` returns handled (`?:` fallbacks in `mailFrom`, `mailFromName`, `configureSMTP`); SMTP auth only when both user+pass set.
- Hook names snake_case; text domain `canopy`; `add_filter('robots_txt', …, 10, 2)` arg count still matches the fixed 2-param signature.

## Verification

- `php -l web/app/themes/canopy/src/Site.php` — clean.
- `composer lint` (Pint `--test`) — passed.
- `vendor/bin/phpstan analyse --memory-limit=2G` — `[OK] No errors`.
- `git status` shows only `web/app/themes/canopy/src/Site.php` modified
  (plus this doc). `composer install` output is gitignored.
