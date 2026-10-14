# wpds audit — Canopy v2

Date: 2026-10-14. Branch: `audit/v2-wpds`. Graph: 22 files, 44 nodes, 385 edges (PHP/JS only; CSS/Twig not indexed, checked by read-through). Source verified manually.

Note: `.agents/skills/wpds/SKILL.md` requires the WPDS MCP server for canonical docs, but this worktree's `.mcp.json` registers only `code-review-graph`. Per the skill's own rule, no web search was used for canonical WPDS documentation. WPDS mapping below is therefore assessed from the theme source plus the canopy skill's `css-js.md` rule set, not from WPDS component docs.

## 1. Scope and method

Audit target: token usage, BEM discipline, `@scope` ordering, a11y (focus, contrast, reduced-motion), and what maps to WPDS vs stays custom.

- Graph first: `build_or_update_graph` (full, 22 files), then `get_architecture_overview` (2 communities, 0 cross-community edges, no warnings), `get_knowledge_gaps`, `get_hub_nodes`. Hubs (`Site::breadcrumbs`, `cleanFrontend`, `enqueueAssets`, island `hydrate`/`load`/`reveal`) are expected theme plumbing; nothing WPDS-relevant is coupled.
- Source read-through: `assets/styles/main.css` (all 757 lines), `site.js`, `islands/reveal.js`, all `views/` partials/components/templates, `src/Site.php` (`themeSupports`, `enqueueAssets`, `dequeueBlockStyles`), `architecture.md` render-tree order, prior audit `docs/audits/wordpress-router.md` for format.

## 2. WPDS mapping: what maps vs stays custom

Canopy is a classic Timber/PHP/Twig theme with no build step. `@wordpress/components` and `@wordpress/ui` are React-only for editor/admin surfaces; this frontend cannot import them without a build, which the project forbids. So there is no component adoption path — only token parity.

| Area | Verdict | Why |
|---|---|---|
| Color roles (bg/fg/muted/border/surface/accent/on-accent) | Maps (parity) | Token roles align 1:1 with WPDS/preset semantics; retheme via `--cnp-hue` |
| Spacing + type scales | Maps (parity) | `clamp(min rem, rem + cqi, max rem)` scales mirror DS ramp intent |
| Focus-visible outline, skip link, `sr-only` | Maps (pattern) | Same patterns WPDS prescribes |
| All components (header/hero/feed/teaser/faq/search/pagination/…​) | Stays custom | BEM `cnp-` + `@scope` system; no React runtime on this frontend |
| `theme.json` editor parity | Deferred, product call | Classic theme by design; add only if editor/frontend parity is briefed |

## 3. Findings

| # | File:line | Severity | Finding | Status |
|---|---|---|---|---|
| 1 | `views/partials/footer.twig:19` | Low | `aria-label="Footer"` hardcoded, untranslatable while every other nav label uses `__()` | Taken: wrapped in `{{ __('Footer', 'canopy') }}` |
| 2 | `views/partials/pagination.twig:2` | Low | `aria-label="Posts navigation"` hardcoded, same i18n gap as #1 | Taken: wrapped in `{{ __('Posts navigation', 'canopy') }}` |
| 3 | `assets/styles/main.css:338` vs `:366` | Low | Scope order violates `architecture.md` render-tree list: `@scope (.cnp-crumbs)` comes before `@scope (.cnp-faq)`, but the list orders faq (5) then crumbs (6) | Deferred: zero visual impact (disjoint scopes); fix together with #4 |
| 4 | `assets/styles/main.css:147-151` | Low | `.cnp-reveal` has no `@scope` block; its hide rule sits unscoped at the top of `@layer components` instead of render position #14 (between error-404 and footer) | Deferred: single rule keyed on unique class, no leakage; move with #3 |
| 5 | `views/components/feed.twig:20`, `views/components/hero.twig:21` | Low | Raw `<div class="… cnp-reveal" data-island="reveal" data-reveal-stagger>` duplicates `components/reveal.twig` markup instead of `{% embed %}`; drifts if the component changes (both verified in sync today) | Deferred: consolidate when reveal markup next changes |
| 6 | `views/` vs `main.css` (cross-check) | Info | Unstyled BEM hooks with no CSS rule: `cnp-teaser__body`, `cnp-teaser__link`, `cnp-brand__name`, `cnp-nav__item` (base; only `--parent` styled), `cnp-feed__title`, `cnp-page__title`, `cnp-post__date`, `cnp-post__author`, `cnp-footer__nav`, `cnp-footer__item`, `cnp-pagination__item`/`__prev`/`__next`, `cnp-crumbs__item`/`__current`, `cnp-faq__item`, `cnp-hero__point` — all inherit base link/type styles sensibly | No change: styling hooks, add rules only when design needs them |
| 7 | `views/partials/header.twig:6-24` | Low | Submenu opens on `:hover`/`:focus-within` only; no touch/keyboard toggle, no `aria-expanded`. Touch users tapping the parent link never reach children | Deferred: needs design decision (button toggle vs split link) |
| 8 | Tokens vs rendered contrast | Low | `light-dark()` pairs look right, but small-text pairs are unproven statically: `cnp-hero__eyebrow` (font-sm semibold accent on surface) and muted body copy need ≥ 4.5:1; accent body links likewise | Deferred: check rendered page with a contrast tool, adjust `oklch` lightness if short |
| 9 | `views/components/teaser.twig:3-5` | Info | Thumbnail link uses `tabindex="-1"` + `aria-hidden="true"` with `alt=""` img — correct duplicate-link suppression next to title + "Read the post" links | No change |

## 4. Passes (verified, no action)

- **Tokens:** every color through `oklch()` + `light-dark()` + `color-scheme`; hover via `oklch(from …)`; type/space all `clamp(rem, rem + cqi, rem)`; logical properties throughout (only `line-height` matches for physical-property grep — false positives). `px` only for 1px hairlines, 2px focus outline, `sr-only` — allowed.
- **Layers/containers:** every rule in `@layer`; `@container` + `cqi` used, zero bare `min-width` media queries (all `@media` are `prefers-reduced-motion` gates); `container-type` on `html`, `container:` on main/feed/hero.
- **Donut scopes:** `@scope (.cnp-page) to (.cnp-page__content)`, `@scope (.cnp-post) to (.cnp-post__content)` contain editor HTML as specified.
- **Focus/skip:** global `:focus-visible` accent outline; `.cnp-skip-link` reveals on `:focus`, targets `#main`.
- **Reduced motion:** no global kill; all transitions + reveal hide rule gated on `no-preference`; `reveal.js` early-returns on `reduce` leaving content visible.
- **BEM:** `cnp-` prefix throughout; only documented exception present (`cnp-pagination__link.current`, Timber/WP class); `:has()` used instead of `.has-*`; no Atomic-Design folders, namespaces, or macros per `css-js.md` bans.
- **Islands:** `site.js` host unchanged (name allowlist + same-origin `data-island-src` guard intact); Motion pinned `motion@12.40.0` inside `reveal.js` only; no GSAP/jQuery/build.
- **PHP:** no `add_theme_support` gaps for a classic theme (`title-tag`, `post-thumbnails`, `html5`, two menu locations); `enqueueAssets`/`dequeueBlockStyles` untouched by this audit.

## 5. Verification

- Edits are Twig attribute strings only (`aria-label` values); re-read both lines after edit.
- `composer lint` / `composer phpstan` not re-run: no PHP touched, and Twig/CSS/JS are outside both gates per `phpstan.neon` (same basis as prior audit §4).
- No build step; no mass CSS rewrite attempted.
