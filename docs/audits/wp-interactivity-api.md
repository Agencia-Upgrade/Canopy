# WP Interactivity API — Canopy

Audit of Interactivity API relevance and islands parity for the Canopy
boilerplate (Bedrock + Timber 2 + Twig 3). Branch: `audit/v2-wp-interactivity-api`.

## Scope

- Decide whether the Interactivity API (`data-wp-*`, `@wordpress/interactivity`,
  `viewScriptModule`, `wp_interactivity_*()`) applies to this theme.
- Audit islands parity against what the skill requires: lazy hydration,
  reduced-motion gating, eager above the fold, heavy deps inside the island.
- Apply only trivial safe fixes. No new deps, no GSAP, no jQuery, no build.

Out of scope: block conversion, animation redesign, global escaping audit.
Those are listed as deferred below.

## Method

1. Built the `code-review-graph` knowledge graph first (full build on this
   branch), then verified every claim in source. Graph hits: `islands/reveal.js`
   (`reveal`), `site.js` (`hydrate`, `load`, `show`, `isAllowedSrc`).
   Twig views are not parsed by the graph; checked with `grep`.
2. Read `wp-interactivity-api/SKILL.md` + references (SSR, directives quickref,
   debugging) and the canopy skill (`site.js` host, `data-island`, Motion in
   `reveal.js`, no GSAP/jQuery/build) + `AGENTS.md`.
3. Searched the theme for `data-wp-interactive|data-wp-|wp_interactivity|
   viewScriptModule|@wordpress/interactivity` — zero matches.
4. Verified in source: `assets/scripts/site.js`, `assets/scripts/islands/reveal.js`,
   `views/components/island.twig`, `views/components/reveal.twig`,
   `views/components/hero.twig`, `views/components/feed.twig`,
   `views/templates/front-page.twig`, `views/partials/head.twig`,
   `assets/styles/main.css:146-151`, `src/Site.php:77-106`.

## Verdict

Interactivity API is **N/A by design**. Canopy is a classic Timber theme: no
`block.json`, no `theme.json`, no blocks, server-rendered Twig + vanilla
islands. There is nothing to hydrate via directives and no store to define.
The islands host already delivers the parity properties the skill cares about
(scoped lazy hydration, SSR-visible markup, reduced-motion opt-in, heavy dep
isolated to the island that needs it).

## Findings

| # | Location | Severity | Issue | Action |
|---|---|---|---|---|
| 1 | Theme-wide (grep: zero `data-wp-*`, `wp_interactivity_*`, `viewScriptModule`, `@wordpress/interactivity`; no `block.json`/`theme.json`) | Info | No Interactivity API surface exists. | Taken: no change. Documented as N/A; do not introduce directives or a store to this starter. |
| 2 | `assets/scripts/site.js:56-81` | Info | Lazy hydration via `IntersectionObserver` (`rootMargin: 100px`); `data-island-eager` hydrates immediately; no-observer fallback hydrates. Matches skill guidance to scope interactivity to small subtrees. | Taken: no change. |
| 3 | `assets/scripts/site.js:43-52`, `assets/styles/main.css:147-151`, `views/partials/head.twig:5` | Info | SSR parity: content renders server-side; `opacity: 0` hide rule requires both `html.js` and `data-island`; host strips `data-island` after hydrate and on import failure, so content is never stuck hidden. | Taken: no change. |
| 4 | `assets/scripts/islands/reveal.js:7-9`, `assets/styles/main.css:147-151` | Info | Reduced-motion gating correct on both layers: JS returns early on `(prefers-reduced-motion: reduce)`; CSS hides only under `no-preference`. No-JS keeps content visible; Motion never downloads without the mark. | Taken: no change. |
| 5 | `assets/scripts/islands/reveal.js:5`, `views/partials/head.twig:3-4`, `assets/scripts/site.js:1-3` | Info | Heavy dep isolated: Motion (`motion@12.40.0`, pinned) imported inside `reveal.js` only; `site.js` imports no Motion; `head.twig` preconnects `cdn.jsdelivr.net`. No GSAP, no jQuery, no build. | Taken: no change. |
| 6 | `views/components/hero.twig:2` (eager) vs `views/components/feed.twig:20`, `views/templates/front-page.twig:45` (lazy) | Info | Eager/lazy split correct: hero above the fold is eager; feed list and front-page body content are lazy below the fold. | Taken: no change. |
| 7 | `views/components/island.twig:4-6`, `views/components/reveal.twig:3` | Low | Island attribute interpolation unescaped (`{{ name }}`, `{{ class }}`, `{{ src }}`) while Timber Twig autoescape is off (canopy skill: escape user input). A context-supplied value could break out of the attribute. | Taken: escaped with `\|e` in `island.twig` and `reveal.twig`. `content\|raw` left as-is (sanitized-HTML contract). |
| 8 | `views/components/hero.twig:21` inside `hero.twig:2` | Low | Nested `data-island="reveal"` (points list, lazy) inside the eager hero reveal: two observers animate overlapping subtrees above the fold; wasted Motion run, possible double-fade. | Deferred (design decision). Fix is either `eager` on the inner list or dropping one of the two marks; changes visible animation, so left to the owning brief. No change. |
| 9 | `assets/scripts/site.js:13-21,34-38` | Info | `data-island-src` constrained to same-origin under `islands/`; `data-island` names restricted to `[a-z0-9-]`. Consistent with canopy rule that `src` must be a same-origin URL under `assets/scripts/islands/`. | Taken: no change. |

## Verification

- `grep` for Interactivity API tokens: zero matches (finding 1).
- Graph build + `semantic_search_nodes_tool` / `file_summary` confirmed host
  (`show`, `isAllowedSrc`, `load`, `hydrate`) and island (`reveal`) scope.
- No PHP touched: `composer lint` / `composer phpstan` not required (Twig-only
  `|e` change; Twig is not statically analysed per canopy skill).
- No push/merge performed; commit on `audit/v2-wp-interactivity-api` only.
