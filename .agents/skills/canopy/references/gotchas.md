# Gotchas

Follow the theme when a doc snippet disagrees. Product issues are listed in
`docs/KNOWN-ISSUES.md`; this file is only what an editor must not copy.

## Contents

- Docs that lie
- Hierarchy
- Data and APIs
- Assets

## Docs that lie

| Snippet | What the theme actually does |
|---|---|
| `docs/TIMBER.md` pagination + `Timber::get_pagination()` | `posts.pagination` in `partials/pagination.twig` |
| Related posts via `Timber.get_posts` inside Twig | Anti-pattern; query in PHP |
| `addToContext()` assigning `posts` | Not shipped; Timber already sets `posts` on archives |
| `{{ post.content }}` without `\|raw` in TIMBER.md | Theme uses `\|raw` on WP-sanitized content |
| “Buttons, cards, CTA blocks” as shipped components | Only structural `cnp-` blocks exist |
| Atomic Design / `@atoms` / PHP Twig Components | Flat `views/{layouts,partials,components,templates}` |
| `.cnp-archive` / `.cnp-search` | `.cnp-feed` + `.cnp-teaser` |
| Register each island in a `site.js` map | Convention: `data-island="name"` → `islands/name.js` |
| “GSAP is always global” / ship both libs | Motion only in the starter; GSAP = project island if needed |
| `<script>` / `<style>` inside Twig like Astro | Twig declares; CSS is `@scope` in `main.css`; JS is an island file |

## Hierarchy

- `front-page.php` always handles the site front. `front-page.twig` is a hero
  plus FAQ. It prints `post.content` only for a static front **page**.
  `front-page.php` unsets `post` when Reading is “Your latest posts”, so the
  first loop post does not dump under the hero. It does not loop `posts`.
- There is no `home.php`. A static front + posts page uses `index.twig`.
- `single.php` / `archive.php` catch CPTs until you add `single-{type}` /
  `archive-{type}`.
- Every shipped PHP file is a selectable page template because of
  `Template Name:`.

## Data and APIs

- Twig `{{ site }}` is not `Canopy\Site`. Public properties on the PHP class
  do not appear in Twig.
- `Timber::get_menu()` returns `null` when the location is empty. Guard with
  `{% if menu %}`.
- Header walks one level of `item.children` and sets `aria-current` on
  `item.current`. Footer stays a flat list.
- `archive_title` and `search_query` are set globally, including on singular
  views.
- Native sitemaps are disabled. Live `robots.txt` still appends
  `/sitemap.xml` unless you enable sitemaps or an SEO plugin.
- `excerpt` Twig filter is unused in shipped templates; lists use
  `post.excerpt`.
- Timber `posts` is a collection object. `{% if posts %}` is always true.
  Use `{% if posts|length %}` (`components/feed.twig`).

## Assets

- Gutenberg **editor** is on. Frontend: `wp-block-library` + stored layout
  styles load only on `is_singular()` (posts, pages, CPTs). Home, archives,
  and search stay without block CSS. `global-styles` (the `--wp--preset`
  dump), emoji, generator, and RSD stay off everywhere. Dequeue alone is
  not enough — WP re-prints global-styles from `wp_enqueue_global_styles`.
- `filemtime()` assumes `main.css` and `site.js` exist.
- Twig file cache is production-only (`WP_DEBUG` off). Development uses
  `auto_reload`.
- Island hydrate strips `data-island`. Style the BEM class.
- `html { font-size: 62.5% }` is gone. Type is `clamp()` on a 16px rem root.
