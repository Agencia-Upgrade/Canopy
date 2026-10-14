# WP Block Themes — Canopy

Audit of block-theme applicability for the Canopy classic theme
(Timber 2 + Twig 3). Branch: `audit/v2-wp-block-themes`.

Canopy is a **classic theme, not a block theme**: no `theme.json`, no
`templates/`, `parts/`, `patterns/`, or `styles/` — all absent by design
(verified by filesystem check). No conversion to block theme is proposed;
that would contradict `AGENTS.md` and `.agents/skills/canopy/SKILL.md`,
which are the source of truth.

## Scope

- Confirm theme type and state what `wp-block-themes` guidance does/does not apply.
- Check `theme.json` absence by design, templates/parts/patterns parity, style variations N/A.
- Check editor styles, block-library CSS gating (`is_singular`), and block-content rendering.
- Apply only trivial safe fixes in scope. No refactors.

Out of scope: converting to a block theme, adding `theme.json`, editor-style
parity work, content-element CSS. Deferred items are listed below.

## Method

1. Read `.agents/skills/wp-block-themes/SKILL.md` plus all six references
   (`theme-json`, `templates-and-parts`, `patterns`, `style-variations`,
   `debugging`, `creating-new-block-theme`) and `.agents/skills/canopy/SKILL.md` + `AGENTS.md`.
2. Queried `code-review-graph` first (`get_architecture_overview`,
   `semantic_search_nodes`): graph is empty (0 nodes indexed, "nothing is
   indexed" warning), so it contributed no signal; every claim below was
   verified in source with filesystem checks and `grep`.
3. Verified: `web/app/themes/canopy/` listing (no `theme.json`/`templates`/
   `parts`/`patterns`/`styles`), `src/Site.php:46-106,108-159`
   (`themeSupports`, `enqueueAssets`, `cleanFrontend`, `dequeueBlockStyles`),
   `views/templates/{single,page,front-page,index,archive,search}.twig`,
   `views/components/{teaser,feed}.twig`, `assets/styles/main.css`.

## What block-theme guidance does / does not apply

Applies (classic/block interop): block-library CSS gating, block-content
rendering (`post.content`), editor-support flags, editor-style parity.

Does not apply: `theme.json` settings/styles, HTML `templates/` + `parts/`
(incl. no-nesting rule), filesystem `patterns/`, `styles/` variations, Site
Editor style-hierarchy debugging (`references/debugging.md`,
`theme-json.md` slug gotcha), `creating-new-block-theme.md`.

## Findings

| # | Location | Severity | Issue | Action |
|---|---|---|---|---|
| 1 | `src/Site.php:46-66` | Info | No `align-wide`, `responsive-embeds`, `editor-styles`, or `wp-block-styles` support declared. Wide/full block alignments and embeds in `post.content` render constrained to `--cnp-measure` with no breakout CSS. Consistent with a classic starter; enabling support without matching CSS would break layout. | Deferred (needs design + CSS, not trivial). No change. No conversion proposed. |
| 2 | theme root | Info | No editor stylesheet (`add_editor_style` / `editor-styles`). Post editor does not reflect `main.css` tokens/measure. Expected for this starter; parity would require splitting or duplicating the single-file CSS, which is a design decision. | Deferred. No change. |
| 3 | `src/Site.php:99-105,147-159` | Info | Block-library CSS loads only on `is_singular()` (per canopy skill hard rule); `global-styles` / `classic-theme-styles` always dequeued, `core-block-supports` dequeued off-singular. Verified safe: listings (`feed.twig` → `teaser.twig`) render title/excerpt only, never `post.content`; full block output appears only on singular views where the CSS is present. `front-page.twig:43-50` guards `post.content` behind `post.type == 'page'`. | Taken: none needed. Behavior correct as designed. |
| 4 | `assets/styles/main.css:437-457` | Low | No inner content-element styles inside `.cnp-post__content` / `.cnp-page__content` (no `figure`/`figcaption`, `blockquote`, `table`, embed rules). Block output inherits base-layer typography only. Acceptable for a starter, but rich block content will look plain. | Deferred (design work, needs tokens + scope rules). No change. |
| 5 | `views/templates/{single.twig:32,page.twig:18,front-page.twig:47}` | Info | `post.content\|raw` is the only `\|raw` block output; per canopy skill this is the sanctioned WordPress-sanitized exception. No unescaped user input in block path. | Taken: none needed. Correct. |

## Correctness notes (verified, no issue)

- `theme.json`, `templates/`, `parts/`, `patterns/`, `styles/` all absent by design; nothing in the skill's folder-layout rules (`templates-and-parts.md`, `patterns.md`, `style-variations.md`) binds.
- `themeSupports()` declares only `title-tag`, `post-thumbnails`, `html5`, text domain, two nav menus — no block-theme markers, so WordPress treats Canopy as classic (no Site Editor takeover).
- `cleanFrontend()` removals (`wp_enqueue_global_styles`, `wp_enqueue_classic_theme_styles`, `wp_enqueue_stored_styles`, SVG filters, img auto-sizes fix) match a no-`theme.json` classic: no global styles exist to enqueue, and `enqueueAssets()` re-adds stored styles on singular only.
- `style.css` is metadata only; no CSS present. No `<script>` logic in Twig block paths.

## Verification

- Filesystem checks: `NO-theme.json`, `NO-templates`, `NO-parts`, `NO-patterns`, `NO-styles`.
- `grep` for `editor-style|align-wide|responsive-embed|add_editor_style|register_block|enqueue_block`: no matches in theme PHP/Twig (only expected `wp-block-library` enqueue/dequeue in `Site.php`).
- No theme PHP touched, so `composer lint` / `composer phpstan` not required (no changes to lint; `vendor/` absent in this environment regardless).
- No fixes committed: zero trivial safe fixes existed in scope; all findings above are deferred design decisions or verified-correct behavior.
