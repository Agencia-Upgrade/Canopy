# Known issues

Tracked here so they stay out of the agent skill. Rechecked against the v2
theme (`style.css` 2.0.0). Fixed items are gone.

## Theme (still reproduces)

- **Sitemap advertised, sitemaps off.** `Site::updateRobotsTxt()` still
  appends `Sitemap: …/sitemap.xml` on live hosts. `wp_sitemaps_enabled` is
  still `__return_false`. That URL 404s unless something else serves a
  sitemap.

- **Front page never lists posts.** Still true by design (website-first).
  `front-page.php` always wins the home URL. `front-page.twig` is a hero plus
  optional `post.content` — it does not loop `posts`. Settings → Reading →
  “Your latest posts” will not show a post list until that template changes.

- **SMTP example vs code.** Still true. `.env.example` uses port `465` /
  `ssl`. `Site::configureSMTP()` defaults to `587` / `tls` when those env
  vars are unset.

- **`filemtime()` without existence checks** in `enqueueAssets()` — still
  true. A missing `main.css` or `site.js` becomes a PHP warning.

## Docs (still reproduces)

- `docs/TIMBER.md` pagination still uses deprecated
  `Timber::get_pagination()` and a `pagination` context key. The theme uses
  `posts.pagination`.
- The related-posts Twig example in that file still queries inside the loop
  and contradicts the performance section below it.
- `TIMBER.md` still shows assigning `posts` in `addToContext()`. The theme
  does not.
- `TIMBER.md` still prints `{{ post.content }}` without `|raw`. The theme
  uses `|raw` on WP-sanitized content.

## Fixed (do not re-open)

- `html lang` — `base.twig` now uses `{{ function('language_attributes') }}`.
- Listing classes — `.cnp-archive` / `.cnp-search` are gone; use `.cnp-feed`
  / `.cnp-teaser`.
- `html { font-size: 62.5% }` is gone; type is `clamp()` on a 16px rem root.
- `CLAUDE.md` no longer describes shipped buttons/cards.
