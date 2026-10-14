# WP Block Development — Canopy

Audit of custom Gutenberg block posture for the Canopy boilerplate
(Bedrock + Timber 2 + Twig 3 classic theme). Branch:
`audit/v2-wp-block-development`.

## Scope

- Confirm presence or absence of custom blocks (`block.json`,
  `register_block_type` / `register_block_type_from_metadata`).
- Check block-adjacent risks only: `theme.json`, editor supports,
  block asset enqueue/dequeue, server-rendered content output in Twig/PHP.
- State which parts of the `wp-block-development` skill apply to future
  blocks vs are N/A today. Do NOT scaffold blocks.
- Apply only trivial safe fixes. No new blocks, no new source files
  beyond this audit doc.

Out of scope: block theme migration, editor styling, REST surface,
docs rewrites. Those belong to their own audit workers.

## Method

1. Read `.agents/skills/wp-block-development/SKILL.md` plus references
   (`block-json.md`, `registration.md`, `dynamic-rendering.md`,
   `deprecations.md`), the `canopy` skill (`SKILL.md`,
   `references/timber-twig.md`), and `AGENTS.md`.
2. Queried `code-review-graph` first (`semantic_search_nodes_tool` for
   block registration + theme/Editor support). Graph is empty/unindexed
   in this worktree, so the 0-result response proves nothing; fell back
   to deterministic source checks per the skill's triage procedure.
3. Verified in source with `grep`/`glob` over the worktree and the theme:
   `**/block.json` (zero hits), `**/theme.json` (zero hits),
   `**/blocks/**` (zero hits), `register_block_type` (only mentions in
   prior audit docs, no code), plus a block-adjacent sweep
   (`allowed_block_types`, `render_callback`, `get_block_wrapper`,
   `has_block`, `parse_blocks`, `do_blocks`, `show_in_rest`,
   `viewScriptModule`, `apiVersion`, `enqueue_block`,
   `allowed_block_types`, `block_editor_settings`) — zero code hits.
   Read `src/Site.php` (full), `single.twig`, `page.twig`,
   `front-page.twig` (content output sites).

## Classification

**Canopy ships zero custom blocks** — no `block.json`, no `theme.json`,
no `register_block_type(_from_metadata)`, no `render_callback`, no
`blocks/` directory. Classic Timber theme by design (consistent with the
`wp-project-triage` and `wordpress-router` audits). Post/page bodies are
core-block content rendered server-side via `{{ post.content|raw }}`.

## What applies vs N/A

Applies today (block-adjacent discipline, verified below):

- Server-rendered core-block output must stay WP-sanitized + `|raw`
  (canopy skill escaping rule); block-library CSS loads on singular
  views only (canopy hard rule).
- Dynamic-rendering wrapper rule (`get_block_wrapper_attributes()`)
  applies to any future `render.php` / `render_callback`.

Applies to future blocks only (currently N/A — nothing to migrate):

- `apiVersion: 3` in `block.json` (WP 6.9+ schema / WP 7 iframe editor).
- Metadata-first registration
  (`register_block_type_from_metadata()` on `init`).
- Stable `name`, scoped assets (`editorScript` vs `viewScriptModule`),
  `deprecated` entries + `migrate` before changing saved markup.

## Findings

| # | Location | Severity | Issue | Action |
|---|---|---|---|---|
| 1 | `web/app/themes/canopy/src/Site.php:46-66` (`themeSupports`) | Info | No editor supports declared (`align-wide`, `editor-styles`, `responsive-embeds`). Core blocks using wide/full alignment or editor styles render without theme opt-in. | Deferred (product decision). Classic theme posture is deliberate; a future design worker opts in if the design needs it. No change. |
| 2 | `web/app/themes/canopy/src/Site.php:99-106`, `147-159` | Info | `wp-block-library` enqueued only on `is_singular()`; dequeued (with `wp-block-library-theme`, `core-block-supports`, `global-styles`, `classic-theme-styles`) elsewhere. Non-singular views showing full post content (e.g. excerpts rendered as content) lose core-block styling. | Deferred (by design). Matches the canopy hard rule; shipped listings use `post.excerpt`, not content. No change. |

## Correctness notes (verified, no issue)

- `single.twig:32`, `page.twig:18`, `front-page.twig:47` output
  `{{ post.content|raw }}` — correct: `post.content` is WP-sanitized,
  and `|raw` is required with Timber's `autoescape: false` (canopy skill).
- `cleanFrontend()` (`Site.php:109-136`) strips global/classic/stored
  styles, SVG filters, and emoji — consistent with a classic theme that
  does not use `theme.json` or global styles.
- `enqueueAssets()` / `dequeueBlockStyles()` hook order
  (`wp_enqueue_scripts`, default 10 vs 100) means dequeue wins on
  non-singular views as intended.
- No `do_blocks` / `parse_blocks` / `has_block` calls needed: Timber
  `Post::content` already applies `the_content` filters.

## Verification

- `glob **/block.json`, `**/theme.json`, `**/blocks/**`: zero hits.
- `grep register_block_type` (worktree-wide): no code hits.
- Block-adjacent sweep (see Method §3): no code hits.
- No source files changed, so `composer lint` / `composer phpstan`
  are unaffected; nothing to run for a docs-only change
  (`vendor/` absent, Docker unavailable in this environment).
