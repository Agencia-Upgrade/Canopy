# JavaScript Patterns & Conventions

> Developed by Agência Upgrade — https://agenciaupgrade.com.br

## Architecture Overview

Canopy ships JavaScript as **islands**: small per-component modules that load
only when their markup is on the page, and only once it scrolls into view.
There is no build step. `site.js` is a native ES module enqueued with
`wp_enqueue_script_module()`, and islands are loaded with dynamic `import()`.

A page that doesn't use an island never downloads its code — or its
dependencies. Motion ([motion.dev](https://motion.dev)), for example, is
imported *inside* an island, so it ships only with the components that animate.

```
assets/scripts/
├── site.js              ← island loader (scans data-island, lazy-imports modules)
└── islands/
    └── reveal.js        ← example island: imports Motion, animates on view
```

## How It Works

1. **Markup opts in** with a `data-island` attribute naming the island:

   ```html
   <div data-island="reveal"> … </div>
   ```

2. **`site.js` registers** each name → a dynamic import, and observes every
   `[data-island]` element with an `IntersectionObserver`.

3. **Hydration** happens when the element nears the viewport (the observer uses
   a 100px `rootMargin`, so it fires ~100px before scroll-in): the module is
   imported and its default export is called with the element.

   ```js
   // assets/scripts/site.js
   const islands = {
     reveal: () => import('./islands/reveal.js'),
   };
   ```

4. **Eager hydration** — add `data-island-eager` to hydrate immediately
   instead of waiting for scroll (use for above-the-fold components).

   ```html
   <div data-island="reveal" data-island-eager> … </div>
   ```

## Writing an Island

An island is a module with a **default export** that receives its element:

```js
// assets/scripts/islands/reveal.js
import { animate } from 'https://cdn.jsdelivr.net/npm/motion@12.40.0/+esm';

export default function reveal(el) {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  animate(
    el,
    { opacity: [0, 1], transform: ['translateY(20px)', 'translateY(0)'] },
    { duration: 0.6, easing: 'ease-out' }
  );
}
```

Conventions:

- **Default export, one argument** — the hydrated element.
- **Respect reduced motion** — bail out early when
  `prefers-reduced-motion: reduce` is set, before animating.
- **Import heavy deps inside the island** — never at the top of `site.js`.
  Pin the version in the CDN URL (`motion@12.40.0`).
- **Vanilla JS**, ES6+, runs natively — no jQuery, no transpilation.

## Adding a New Island

Three steps:

1. **Create the module** at `assets/scripts/islands/<name>.js` with a default
   export.

2. **Register it** in `site.js` (which ships with a commented placeholder
   showing exactly where new islands go):

   ```js
   const islands = {
     reveal: () => import('./islands/reveal.js'),
     // menu: () => import('./islands/menu.js'),   // ← add yours here
   };
   ```

3. **Mark the markup** with `data-island="<name>"`. A reusable Twig wrapper
   pattern lives in `views/components/reveal.twig`:

   ```twig
   {% include 'components/reveal.twig' with { content: '<p>Hi</p>' } %}
   ```

## Loading

`site.js` is enqueued in `Site::enqueueAssets()` as a script module, with
`filemtime()` for cache-busting:

```php
wp_enqueue_script_module(
    'canopy-site',
    $uri . '/assets/scripts/site.js',
    [],
    filemtime($dir . '/assets/scripts/site.js')
);
```

Because islands are imported relative to `site.js`, they are cache-busted by
the browser's module resolution — no separate enqueue per island.

## Resources

- [MDN — Dynamic import()](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/import)
- [MDN — IntersectionObserver](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
- [MDN — JavaScript modules](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules)
- [Motion](https://motion.dev/)
