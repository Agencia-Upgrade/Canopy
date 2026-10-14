# CSS Patterns & Conventions

> Developed by Agência Upgrade — https://agenciaupgrade.com.br

How Canopy's CSS is organized, and how to edit or extend it. All styles live in
one file — `web/app/themes/canopy/assets/styles/main.css` — served as-is with no
build step. This guide is the map; `main.css` is the source of truth.

## Architecture

Token-first, `@layer`, `@scope` per Twig block, BEM with the `cnp-` prefix.
`@scope` is the no-build stand-in for a Svelte/Astro scoped `<style>`: the
selector belongs to one component, but the CSS is still one request.

```css
@layer reset, tokens, base, components, utilities;
```

1. **Reset** — normalize, no classes. `html` is a size container (`cqi`).
2. **Tokens** — `--cnp-*` in `oklch()` + `light-dark()`, fluid `clamp()`.
3. **Base** — element defaults. Headings `text-wrap: balance`; `p` `pretty`.
4. **Components** — one `@scope (.cnp-…)` per Twig block, render-tree order.
5. **Utilities** — `.sr-only`, `.cnp-skip-link`.

Page and post use donut scope so chrome does not leak into `post.content`:

```css
@scope (.cnp-post) to (.cnp-post__content) { }
```

There is no `html { font-size: 62.5% }`. Root rem is the browser default.
Type and space tokens are `clamp(min rem, rem + cqi, max rem)`.

Brand greens from the Upgrade mark (`--cnp-hue: 168deg`). Retheme by editing
`:root` (start with `--cnp-hue`). Do not hard-code hex.

## BEM

`Block__Element--Modifier`, prefixed `cnp-`. Exception: Timber/WP injects
`.current` on the active pagination link — target `.cnp-pagination__link.current`.

## What ships

Website-first. Listings share one feed, not a blog chrome.

| Block | Renders in | Role |
|---|---|---|
| `.cnp-main` | `layouts/base.twig` | Main landmark + container |
| `.cnp-header` / `.cnp-nav` | `partials/header.twig` | Sticky header (`:has(.cnp-nav)`) |
| `.cnp-brand` | `components/brand.twig` | Logo mark + site name |
| `.cnp-hero` | `components/hero.twig` | Front hero (eager reveal) |
| `.cnp-faq` | `components/faq.twig` | Extractable Q&A + FAQPage JSON-LD |
| `.cnp-crumbs` | `components/breadcrumbs.twig` | Breadcrumb trail |
| `.cnp-search` | `components/search-form.twig` | GET `?s=` form |
| `.cnp-container` | `templates/front-page.twig` | Measure wrap under the hero (own `@scope`; sibling of `.cnp-hero`) |
| `.cnp-page` | `templates/page.twig` | Static page (donut) |
| `.cnp-post` | `templates/single.twig` | Single (donut) |
| `.cnp-feed` / `.cnp-teaser` | `components/feed.twig`, `teaser.twig` | Archive, index, search |
| `.cnp-pagination` | `partials/pagination.twig` | Paged nav |
| `.cnp-error-404` | `templates/404.twig` | 404 |
| `.cnp-reveal` | `components/reveal.twig` | Island hook (no visual CSS) |
| `.cnp-footer` | `partials/footer.twig` | Footer |
| `.cnp-skip-link` / `.sr-only` | `layouts/base.twig` | A11y |

No `.cnp-button`, `.cnp-card`, or `.cnp-grid`. No Atomic Design folders.

## Adding a component

1. Twig under `views/components/<name>.twig`.
2. `@scope (.cnp-<name>)` in `@layer components`, in render-tree order.
3. Tokens only. Logical properties. Nest with `&`.
4. Motion only inside `@media (prefers-reduced-motion: no-preference)`.

## Related

- [TIMBER.md](./TIMBER.md) — templates
- [JAVASCRIPT.md](./JAVASCRIPT.md) — islands and Motion

## Resources

- [CSS Layers](https://developer.mozilla.org/en-US/docs/Web/CSS/@layer)
- [`@scope`](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/At-rules/@scope)
- [`oklch()`](https://developer.mozilla.org/en-US/docs/Web/CSS/color_value/oklch)
- [Logical properties](https://developer.mozilla.org/en-US/docs/Web/CSS/Guides/Logical_properties_and_values)
- [BEM](http://getbem.com/naming/)
