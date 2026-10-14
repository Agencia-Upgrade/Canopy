---
name: canopy
description: >
  Implement and extend the Canopy WordPress theme (Bedrock + Timber 2.x + Twig 3).
  Use when editing web/app/themes/canopy, Site.php, Twig views, PHP templates,
  islands, theme CSS, Timber context or queries, CPTs, menus, or when the user
  mentions Canopy, Timber, Twig templates, or /canopy.
license: MIT
---

# Canopy

Self-contained skill for this repository. Theme code is the source of truth.
When `docs/TIMBER.md` or examples in other docs disagree with the theme, follow
the theme. Project-wide rules live in `AGENTS.md`.

## Load index

Read only the file that matches the task. Paths are relative to this skill.

| Task | Read |
|---|---|
| Boot, Bedrock paths, `Site.php` hooks, context keys, view map | [references/architecture.md](references/architecture.md) |
| Timber 2 / Twig API, escaping, pagination, queries, Timber 1 bans | [references/timber-twig.md](references/timber-twig.md) |
| PHP templates, CPT/tax, filters, SMTP, PHPStan | [references/php.md](references/php.md) |
| `main.css` tokens/BEM/`@scope`, islands, Motion vs GSAP | [references/css-js.md](references/css-js.md) |
| Docs vs code, hierarchy traps | [references/gotchas.md](references/gotchas.md) |

Known product/doc issues (not this skill): `docs/KNOWN-ISSUES.md`.

## Hard rules

- Call `Timber::init()` then `new Canopy\Site()` from `functions.php`. Do not
  instantiate `Timber\Post`, `Timber\Menu`, `Timber\Term`, or `Timber\User`
  (`__construct` is protected). Use `Timber::get_post()`, `get_posts()`,
  `get_menu()`, `get_term()`.
- PHP templates stay thin: `Timber::context()` → optional extras →
  `Timber::render('templates/<name>.twig', $context)`.
- Query in PHP. Never call `get_posts` / `Timber.get_posts` inside a Twig loop.
- Pagination is `posts.pagination` plus `partials/pagination.twig`. Do not use
  `Timber::get_pagination()` or a separate `$context['pagination']`.
- `Canopy\Site` is the hook class. Twig `{{ site }}` is a different
  `Timber\Site` from `Timber::context()`. Put globals in `addToContext()`.
- Timber's Twig `autoescape` default is off. Use `|raw` only on WordPress-sanitized
  HTML (`post.content`). Escape user input (`{{ search_query|e }}`).
- CSS lives in `assets/styles/main.css` only. Follow
  [references/css-js.md](references/css-js.md) as the CSS rule set (`@layer`,
  `@scope`, `oklch`, `light-dark`, logical properties, `clamp` + `cqi`,
  motion opt-in). BEM `cnp-`. No preprocessor, no build.
- JS is islands: file in `assets/scripts/islands/<name>.js`, mark Twig with
  `data-island="<name>"`. `site.js` is a host (no registry). Do not put
  `<script>` logic in Twig. Shipped animation is Motion inside
  `islands/reveal.js`. Do not add GSAP to the starter. A project may add a
  GSAP island later; do not ship both. No jQuery, no build.
- Do not load `config/application.php` from `web/index.php`. Keep the literal
  string `wp-settings.php` in `web/wp-config.php`.
- Frontend block-library CSS loads only on singular views (`is_singular()`).
  Do not rely on Gutenberg styles on the home, archives, or search.

## Recipes

### Page or view

1. Add a hierarchy PHP file (or `page-{slug}.php` / dedicated `Template Name:`
   file). Do not overload `index.php` or `404.php` as selectable page templates.
2. Render a Twig file under `views/templates/`.
3. Twig: `{% extends "layouts/base.twig" %}` and fill `{% block content %}`.
4. Classes: `cnp-<block>` BEM. Add `@scope (.cnp-<block>)` in `@layer
   components`, in render-tree order (see [architecture.md](references/architecture.md)).
5. Extra data: `$context['x'] = …` in that PHP file, or
   `Timber::context(['x' => …])`. Site-wide data: `addToContext()`.

```php
use Timber\Timber;

$context = Timber::context();
Timber::render('templates/page.twig', $context);
```

### CPT

1. `register_post_type()` in `Site::registerPostTypes()` on `init` (priority ≤ 10).
2. Taxonomy in `registerTaxonomies()` if needed.
3. `single-{cpt}.php` + `views/templates/single-{cpt}.twig` when the single
   layout differs from `single.twig`.
4. `archive-{cpt}.php` + matching Twig when the archive differs from `archive.twig`.
5. Extra queries belong on `$context` in those PHP files.

### Twig component

1. `views/components/<name>.twig`.
2. BEM `cnp-<name>` / `cnp-<name>__el`.
3. `{% include 'components/<name>.twig' with { … } %}`.
4. Named inner markup: support `{% embed %}` + `{% block %}` like `reveal.twig`.
5. Listings use `components/feed.twig` + `components/teaser.twig`. Do not
   copy archive/search markup.

### Twig filter or function

Register in `Site::addFiltersToTwig()` / `addFunctionsToTwig()`:

```php
$filters['excerpt'] = [
    'callable' => function ($text, $length = 55) {
        return wp_trim_words($text, $length);
    },
];
$functions['get_theme_mod'] = ['callable' => 'get_theme_mod'];
```

Hooks: `timber/twig/filters` and `timber/twig/functions`. Do not use Timber 1's
`timber/twig` environment hook.

### Island

1. `assets/scripts/islands/<name>.js` — `export default function (el) { … }`.
2. Twig: `data-island="<name>"` (or `{% embed 'components/island.twig' with { name: '<name>' } %}`).
   Do not register the name in `site.js`. Do not put JS source in the Twig.
3. Above the fold: `data-island-eager`. One-off module: `data-island-src` must
   be a same-origin URL under `assets/scripts/islands/`.
4. Heavy deps stay inside the island, version-pinned on the CDN URL.
5. Animate only when `prefers-reduced-motion: no-preference` matches.
   Shipped optional Motion island: `reveal` (`components/reveal.twig`).
   Do not add GSAP to this repo. If a brief needs timelines / ScrollTrigger /
   SVG morph, add a *project* island (`islands/gsap-….js` + `data-island` on
   that Twig). Same host, never enqueue GSAP globally. Details:
   [css-js.md](references/css-js.md).

### Menu

1. Location in `register_nav_menus()` inside `themeSupports()`.
2. `Timber::get_menu('location_slug')` in `addToContext()`.
3. Twig: `{% if menu %}` then `{% for item in menu.items %}`.
4. Children / current item (`item.children`, `item.current`) are not in the
   shipped header — add them if the design needs them.
5. Unassigned locations return `null`; templates already guard that.

### Extra query

```php
$context = Timber::context([
    'related' => Timber::get_posts([
        'post_type' => 'post',
        'posts_per_page' => 3,
        'post__not_in' => [get_the_ID()],
    ]),
]);
Timber::render('templates/single.twig', $context);
```

## Verify

After PHP changes: `composer lint` and `composer phpstan` (theme PHP listed in
`phpstan.neon` only). Twig is not statically analysed.
