# CSS Patterns & Conventions

> Developed by Agência Upgrade — https://agenciaupgrade.com.br

How Canopy's CSS is organized, and how to edit or extend it. All styles live in
one file — `web/app/themes/canopy/assets/styles/main.css` — served as-is with no
build step. This guide is the map; `main.css` is the source of truth.

## Architecture Overview

Canopy uses a **token-first** approach: design values live in CSS custom
properties, components consume them. Organization is by `@layer`, naming is
**BEM** with the `cnp-` prefix.

### Layers (in order)

```css
@layer reset, tokens, base, components, utilities;
```

1. **Reset** — browser normalization (no classes)
2. **Tokens** — custom properties: colors, spacing, typography, layout, z-index
3. **Base** — element defaults (`h1`–`h6`, `p`, `a`, form controls)
4. **Components** — the `cnp-` blocks (`.cnp-header`, `.cnp-hero`, …)
5. **Utilities** — single-purpose helpers (`.sr-only`, `.cnp-skip-link`)

Because `base` already styles elements, a component class is a **hook** — it
adds only the deltas its block needs. A heading inside `.cnp-archive__title`
gets its size from the `base` layer; the component layer doesn't restate it.

### Font Scale

**1rem = 10px** (set via `html { font-size: 62.5%; }`). Math stays simple:
`1.6rem` = 16px, `2rem` = 20px.

### Tokens

The full token set — colors, the `--cnp-space-*` scale, the `--cnp-font-size-*`
scale, layout, transitions, z-index — lives in the `tokens` layer of `main.css`.
Read it there rather than here, so there's one source of truth. To retheme
Canopy, edit those values; every component updates with them.

```css
/* Consume tokens; never hard-code values in components. */
.cnp-card {
  padding: var(--cnp-space-16);
  color: var(--cnp-color-text);
  border-radius: 4px;
}
```

To add a status palette (success / warning / error), add the tokens to the
`tokens` layer — Canopy ships a neutral palette and leaves brand/status colors
to the project.

## BEM Naming Convention

**Block__Element--Modifier**, all prefixed `cnp-`:

```css
.cnp-card { }              /* Block */
.cnp-card__title { }       /* Element */
.cnp-card--featured { }    /* Modifier */
```

The prefix is what gives each component its own namespace — it's why Canopy
needs no build-time style scoping (see *Co-locating Styles* below).

## Components Canopy Ships

These are the components defined in `main.css`, each paired with the template
that renders it. Edit them, extend them with modifiers, or delete what you don't
use. Canopy ships **structural** components only — no styled button/card/modal
library to undo.

| Block | Renders in | Purpose |
|---|---|---|
| `.cnp-header` / `.cnp-header__inner` / `.cnp-logo` | `partials/header.twig` | Sticky site header |
| `.cnp-nav__list` / `.cnp-nav__link` | `partials/header.twig` | Primary navigation |
| `.cnp-footer__*` | `partials/footer.twig` | Site footer + footer nav |
| `.cnp-hero__*` | `templates/front-page.twig` | Front-page hero |
| `.cnp-page__*` | `templates/page.twig` | Static page |
| `.cnp-post__*` | `templates/single.twig` | Single post |
| `.cnp-archive__*` | `templates/archive.twig`, `index.twig` | Post listing |
| `.cnp-search__*` | `templates/search.twig` | Search results |
| `.cnp-pagination__*` | `partials/pagination.twig` | Paged navigation |
| `.cnp-error-404__*` | `templates/404.twig` | 404 page |
| `.cnp-reveal` | `components/reveal.twig` | Scroll-reveal island wrapper |
| `.cnp-skip-link` / `.sr-only` | `layouts/base.twig` | Accessibility utilities |

There is **no** `.cnp-button`, `.cnp-card`, or `.cnp-grid` — those are example
names in this guide, not shipped classes. Add them as your project needs.

## Co-locating Styles with Components

There's no build step, so styles aren't physically scoped to a file the way a
`.astro` or `.vue` component is. Canopy co-locates **by convention**: each Twig
component maps one-to-one to a section in the `components` layer, tied by a
section comment. The `cnp-` prefix + BEM keep namespaces from colliding, so no
scoping tool is needed.

When you add `views/components/card.twig`, add a matching section in `main.css`:

```css
/* =============================================================================
   Layer: Components — Card  (views/components/card.twig)
   ============================================================================= */
@layer components {
  .cnp-card { padding: var(--cnp-space-16); }
  .cnp-card__title { margin-bottom: var(--cnp-space-8); }
  .cnp-card--featured { border: 2px solid var(--cnp-color-primary); }
}
```

The workflow: open `card.twig` and the `Card` section of `main.css` side by
side. One request, fully cacheable, no tooling.

Reserve the `base` layer for element-level defaults (typography, links, form
controls). Anything belonging to a specific component goes in its `components`
section, not in `base`.

## Common Patterns

These show the conventions on **example** classes — adapt them to your own
components.

### Component with states

```css
.cnp-card {
  padding: var(--cnp-space-12) var(--cnp-space-16);
  border-radius: 4px;
  transition: background-color var(--cnp-transition-fast);
}

.cnp-card--featured {
  border: 2px solid var(--cnp-color-primary);
}
```

### Focus states (accessibility)

Matches the shipped pattern — visible focus via `:focus-visible`:

```css
.cnp-card__link:focus-visible {
  outline: 2px solid var(--cnp-color-primary);
  outline-offset: 2px;
}
```

### Responsive

Mobile-first, with `@media (min-width: …)`:

```css
.cnp-card-list {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--cnp-space-16);
}

@media (min-width: 768px) {
  .cnp-card-list { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1024px) {
  .cnp-card-list { grid-template-columns: repeat(3, 1fr); }
}
```

Breakpoints used across the theme: `768px` (tablet), `1024px` (desktop).

### Reduced motion

The reset layer already honors `prefers-reduced-motion` globally; JS islands
check it too (see [JAVASCRIPT.md](./JAVASCRIPT.md)). For component animations,
respect it explicitly:

```css
@media (prefers-reduced-motion: reduce) {
  .cnp-card { transition: none; }
}
```

## File Organization

One `main.css`, organized by `@layer`. The web server (and LiteSpeed Cache, when
present) handles minification and compression; the file is served as-is with
`filemtime()` cache-busting. Section comments mark each block:

```css
/* =============================================================================
   Layer: Components — Header
   ============================================================================= */
```

## Related

- [TIMBER.md](./TIMBER.md) — templates that render these components
- [JAVASCRIPT.md](./JAVASCRIPT.md) — JS islands and Motion

## Resources

- [MDN CSS Guide](https://developer.mozilla.org/en-US/docs/Web/CSS)
- [BEM Naming](http://getbem.com/naming/)
- [CSS Layers (`@layer`)](https://developer.mozilla.org/en-US/docs/Web/CSS/@layer)
- [CSS Custom Properties](https://developer.mozilla.org/en-US/docs/Web/CSS/--*)
