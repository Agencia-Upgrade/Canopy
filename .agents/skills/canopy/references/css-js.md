# CSS and JavaScript

This file is the full convention set. Do not assume any other skill is
installed. Token values live in `assets/styles/main.css`. Human map:
`docs/CSS-PATTERNS.md` (may be removed later).

## Contents

- CSS rules (modern CSS, no build)
- Layers, scope, BEM
- Tokens
- Adding a block
- What not to adopt
- Islands
- Motion vs GSAP

## CSS rules

Author CSS as if the modern-css skill were open. No preprocessor. One file:
`web/app/themes/canopy/assets/styles/main.css`. Served as-is; `filemtime()`
cache-busts.

| Do | Do not |
|---|---|
| `@layer` for every rule | Selectors outside layers |
| `@scope (.cnp-block)` per component | Unscoped component selectors |
| `@scope (outer) to (inner)` when the block hosts user HTML | Let page/post chrome leak into `post.content` |
| Nest with `&` | Repeat the parent selector |
| `:has()` for DOM relationships | `.has-*` classes |
| `:not()` and ranged queries | Override then undo (`:last-child { border: 0 }`) |
| `oklch()` + `light-dark()` + `color-scheme` | hex / rgb / hsl, hardcoded light-only |
| `oklch(from var(--token) …)` / `color-mix()` for related colors | Duplicate hex for hover |
| `clamp(min rem, rem + cqi, max rem)` | `html { font-size: 62.5% }`, bare `px` type |
| Logical properties (`padding-block`, `inline-size`, `inset-block-start`) | `padding-top`, `width`, `left` |
| `@container` + `cqi` | Only `@media (min-width: 768px)` |
| `text-wrap: balance` / `pretty` | Default wrap on headings and body copy |
| `@media (prefers-reduced-motion: no-preference)` | Global `* { transition: none }` on `reduce` |
| `container-type` on `html` and `.cnp-main` / `.cnp-feed` | Fixed `px` layout widths for content |

`clamp()` middle term must include `rem` (`1.45rem + 1.25cqi`), not only `cqi`.
Ranged queries must not overlap.

## Layers, scope, BEM

```css
@layer reset, tokens, base, components, utilities;
```

`@scope (.cnp-block)` is the no-build equivalent of a Svelte/Astro `<style>`:
rules only apply inside that Twig block. The CSS text still lives in
`main.css` — there is no compiler to extract `<style>` from Twig. Putting CSS
in the `.twig` would re-emit it on every include. BEM class names stay on the
markup so Twig stays readable. `@scope` is the fence; BEM is the name;
`@layer` is who wins.

Donut scope for editor HTML:

```css
@scope (.cnp-post) to (.cnp-post__content) { }
```

Prefix `cnp-`. Exception: `.cnp-pagination__link.current` (Timber/WP class).

Order of `@scope` blocks matches the render tree (see
[architecture.md](architecture.md)).

## Tokens

Brand greens from the Upgrade mark (`--cnp-hue: 168deg`, accent from
`#077E64` / `#56E188`). Retheme by editing `:root` in `tokens` — change
`--cnp-hue` and the `oklch`/`light-dark` pairs. Do not invent a button/card
library. Dummy logo: `assets/images/logo.svg`.

Scale: `--cnp-font-{sm,base,md,lg,xl}`, `--cnp-space-{xs,s,m,l,xl}`,
`--cnp-measure`, `--cnp-wide`, `--cnp-gutter`.

## Adding a block

1. Twig in `views/components/<name>.twig` (or a template/partial).
2. BEM `cnp-<name>`.
3. New `@scope (.cnp-<name>)` in `@layer components`, in render-tree order.
4. Consume tokens. No hex, no physical properties.

## What not to adopt

Canopy is a blank Timber theme, no build, website-first.

- **Not** Atomic Design folders (`atoms/` / `molecules/` / `organisms/`). That
  tree needs a button/input/card library this theme does not ship.
- **Not** Twig namespaces (`@atoms`). Timber default dir is `views/`; paths
  are `layouts/`, `partials/`, `components/`, `templates/`.
- **Not** Symfony UX Twig Components or one-folder PHP+Twig+CSS+JS objects.
  Data stays in `Site.php` / `Timber::context()`. CSS stays in `main.css`. JS
  stays in islands.
- **Not** macros for a design system. `include` for independent fragments,
  `embed` for a slot (`reveal.twig`), `extends` for page templates.

Composition we do use: `extends` → `include` / `embed`. BEM on every root.

## Islands

No build. `site.js` is an ES module via `wp_enqueue_script_module()`.

```
assets/scripts/site.js
assets/scripts/islands/<name>.js
```

```js
import(`./islands/${name}.js`);
```

There is no name map. `data-island="reveal"` loads `islands/reveal.js`.
Adding an island is a Twig mark + a file. Do not edit `site.js`.

```js
export default function reveal(el) {
  if (!window.matchMedia('(prefers-reduced-motion: no-preference)').matches) {
    return;
  }
}
```

- Default export, one argument: the element.
- `IntersectionObserver` `rootMargin: '100px'`.
- `data-island-eager` hydrates immediately (hero).
- `data-reveal-stagger` animates children with Motion `stagger`.
- `data-island-src` is optional and must be same-origin under `islands/`.
- Host calls the island **then** strips `data-island`. Import errors also
  strip it so content is never stuck at `opacity: 0`.
- `html.js` (tiny inline in `head.twig`) + `@media (prefers-reduced-motion:
  no-preference)` hide `.cnp-reveal[data-island="reveal"]` only when JS ran.
  No JS → content stays visible. Motion is not downloaded.
- Motion (`motion.dev`) is imported **inside** `islands/reveal.js`, version-pinned:

  `https://cdn.jsdelivr.net/npm/motion@12.40.0/+esm`

- Do not put `<script>` logic in Twig. Twig declares; the `.js` file runs.
  That is the no-build stand-in for an Astro/Svelte `<script>`. There is no
  extract step; inline JS would duplicate, break `document.currentScript` in
  modules, and fight CSP.
- Do not enqueue island files separately.
- `head.twig` preconnects `https://cdn.jsdelivr.net`.
- Vanilla JS. No jQuery, no transpile.

Shipped Motion island: `views/components/reveal.twig` + `islands/reveal.js`.
Generic opt-in: `views/components/island.twig`.

## Motion vs GSAP

Motion (`motion.dev`, formerly Framer Motion) is the **only animation library
this starter ships**. It is the right default for Canopy: ESM `import()`,
MIT, and the `animate` + `stagger` we use is smaller than GSAP core (~18 KB
gzip full / ~3 KB mini vs ~23 KB GSAP). Pages without `data-island="reveal"`
never download it.

GSAP is not forbidden in a *forked project*. It is forbidden **in this
repo**. Do not add `islands/gsap.js`, do not enqueue `gsap.min.js` on
`wp_enqueue_scripts`, do not put both libraries in the starter “so the
client can delete one”. Forks do not delete the unused file; agents and
humans then mix two APIs.

GSAP is not inherently global. The host would load `islands/gsap-hero.js`
only if that Twig marks `data-island="gsap-hero"`. “GSAP on every URL” is
the old habit of a global `<script>`, not a property of the library.

When a brief actually needs GSAP (long timelines, ScrollTrigger pin/scrub,
SVG morph, Flip), add a project-only island:

1. `assets/scripts/islands/gsap-<name>.js` — import GSAP from a
   version-pinned CDN **inside that file**.
2. Mark only the Twig that needs it (`data-island="gsap-<name>"`).
3. Same `prefers-reduced-motion: no-preference` gate as `reveal.js`.
4. Do not also animate that block with `reveal`. One library per element.

Do not ship Motion and GSAP side by side in Canopy. One path in the
starter; GSAP is an escape hatch on the job, not a product option.
