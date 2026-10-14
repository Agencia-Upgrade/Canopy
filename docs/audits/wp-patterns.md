# Audit: wp-patterns (v2)

## Scope

`wp-patterns` skill against Canopy theme (`web/app/themes/canopy`).
Canopy is a classic PHP + Timber/Twig theme. No `patterns/` directory,
no `register_block_pattern()` calls, no block markup. There are no WP
block patterns to register, validate, or fix.

Assessed instead: which pattern practices map to Twig components, and
whether the Twig equivalents (`hero`, `feed` + `teaser` + pagination,
`faq`, `search-form`, `brand`, `breadcrumbs`, templates) meet the
portable rules: markup quality, a11y, i18n/escaping, meaningful copy,
query-loop discipline. Block-registration mechanics stay N/A.

Component map (pattern category analogue):

- `components/hero.twig` ~ `banner` / `call-to-action`
- `components/feed.twig` + `components/teaser.twig` + `partials/pagination.twig` ~ `posts` / `query`
- `components/faq.twig` ~ `text` (+ FAQ structured data)
- `components/search-form.twig` ~ search; `templates/404.twig` recovery copy ~ 404 pattern guidance
- `components/brand.twig`, `components/breadcrumbs.twig` ~ `header` navigation aids

## Method

1. Read `wp-patterns` SKILL.md + `anti-patterns.md` + `pattern-categories-and-types.md`;
   canopy SKILL.md + `references/timber-twig.md`; repo AGENTS.md.
2. `code-review-graph`: full rebuild (22 PHP files, 44 nodes, 385 edges).
   Graph indexes PHP only, so it scoped the PHP side (no pattern
   registration found, no queries in Twig functions to chase); Twig has
   no graph nodes, so all component findings were verified in source
   with `grep` + full file reads (`views/components/*.twig`,
   `views/partials/*.twig`, `views/templates/*.twig`, `views/layouts/base.twig`).
3. Checked: `|raw` sites, `|e` on user input, `__()` domain `canopy`,
   heading order, alt text, link labels, nav labels, query discipline,
   empty states, hardcoded English strings.

## Findings

| # | File:line | Severity | Status | Finding |
|---|-----------|----------|--------|---------|
| 1 | `views/partials/pagination.twig:2` | low | taken | `aria-label="Posts navigation"` hardcoded English. Made translatable: `{{ __('Posts navigation', 'canopy') }}`. Matches existing `__('Primary', 'canopy')` nav-label convention. |
| 2 | `views/partials/footer.twig:19` | low | taken | `aria-label="Footer"` hardcoded English. Made translatable: `{{ __('Footer', 'canopy') }}`. |
| 3 | `views/components/brand.twig:1` (via `hero.twig:4`) | medium | taken | Empty link: `hero.twig` includes `brand.twig` with `show_name: false`, rendering `<a><img alt="">` with no accessible name. Added conditional `aria-label="{{ site.name }}"` only when `show_name` is false. Default brand (name shown) unchanged. |
| 4 | `views/components/teaser.twig:3-5,15-18` | — | pass | Decorative duplicate-image link correctly removed from tab order (`tabindex="-1"`, `aria-hidden`, empty `alt`); read link pairs generic label with `sr-only` post title. Pattern "descriptive link text" rule met. |
| 5 | `views/components/feed.twig:15-34` | — | pass | Query-loop analogue sound: loops prefetched `posts`, no `get_posts` in Twig, `posts.pagination` + `partials/pagination.twig`, empty state with `empty` copy + `empty_link`/`empty_label` fallback (= `query-no-results` analogue). |
| 6 | `views/components/faq.twig:23,26` | — | pass | `item.q\|json_encode\|raw` inside JSON-LD is correct (`json_encode` then `raw`); not an escaping violation. `<script type="application/ld+json">` is structured data, not a pattern "no JS" violation in Twig context. `dl/dt/dd` structure sound. |
| 7 | `views/templates/single.twig:32`, `page.twig:18`, `front-page.twig:47` | — | pass | `\|raw` only on `post.content` (WP-sanitized). Matches canopy escaping rule. `search_query\|e` in `search-form.twig:8` and `search.twig:5` correct. |
| 8 | `views/templates/single.twig:12` and titles/links site-wide | info | deferred | Author name, titles, term/crumb names output without `\|e` under `autoescape: false`. This is the documented Canopy convention ("titles, links, excerpts: output as-is"). Systematic `\|e` or enabling autoescape is a refactor with double-escaping risk; out of scope for this audit. |
| 9 | i18n `__()` vs `esc_html__()` site-wide | info | deferred | All strings use `__()` with domain `canopy` (consistent, correct domain). Pattern checklist prefers `esc_html__()`; Canopy's Twig convention is `__()` per `timber-twig.md`. Domain-wide switch is a refactor; deferred. |
| 10 | Block-registration mechanics | — | N/A | No `patterns/*.php`, slugs, categories, `Block Types`, `Template Types`, `Inserter`, preset slugs, or registration-time PHP exist by design. Nothing to validate. |
| 11 | Block-markup bans | — | N/A (equivalents pass) | No block comment JSON to validate. Twig equivalents pass: BEM `cnp-` classes + tokens (not inline `<style>`), no behavior `<script>` in components, local logo asset, real placeholder copy (no lorem), action-specific buttons ("Go to the homepage", "Read the post" + `sr-only`). |
| 12 | Heading order, landmarks | — | pass | `h1` per template (`hero`, `feed`, `single`, `page`); hero points `h2`; faq heading `h2`; no skipped levels. Skip link, `<main>`, labelled navs, `aria-current="page"`, search `role` + `sr-only` label all present. |

## Verification

- `git diff --stat`: 3 one-line Twig edits (brand, footer, pagination). No PHP touched: `composer lint` / `phpstan` not applicable.
- Twig has no static analyser in this repo; edits follow the existing `__('Primary', 'canopy')` attribute pattern and tab indentation.
- No Playground/wc-env run: classic theme renders via PHP templates, and the edits are attribute-only with no layout impact.

## Disposition

No block patterns to create or migrate. Three trivial safe fixes taken (#1–#3). Two systemic items deferred as refactors (#8, #9). Remainder pass or N/A by architecture.
