# Architecture

## Contents

- Boot
- Theme file map
- `Site.php` hooks
- Context keys
- Bedrock paths

## Boot

```
Browser → nginx → web/index.php
  → web/wp/wp-blog-header.php
    → web/wp-config.php
      1. vendor/autoload.php
      2. config/application.php
      3. ABSPATH . 'wp-settings.php'
        → theme functions.php
```

`web/index.php` only defines `WP_USE_THEMES` and loads `wp/wp-blog-header.php`.
It must not load autoload or `config/application.php`.

`web/wp-config.php` starts with `defined('WP_CACHE') || define('WP_CACHE', true)`
then the three requires. WP-CLI greps for the literal string `wp-settings.php`.

Theme boot (`web/app/themes/canopy/functions.php`):

```php
Timber::init();
new Canopy\Site();
```

`Timber::init()` is required (Timber 2). Default view dir is `views/`.
`Canopy\` maps to `web/app/themes/canopy/src/` via Composer PSR-4.

`Canopy\Site` extends `Timber\Site` and only registers hooks. Twig `{{ site }}`
is a separate `new Timber\Site()` created inside `Timber::context_global()`.

## Theme file map

```
web/app/themes/canopy/
  functions.php
  style.css                 WP metadata only — no CSS
  src/Site.php              all theme setup
  *.php                     thin Timber::render wrappers
  views/
    layouts/base.twig       HTML shell, block content
    partials/               head, header, footer, pagination
    components/             brand, hero, reveal, island, feed, teaser, search-form, breadcrumbs, faq
    templates/              one Twig per PHP template
  assets/
    styles/main.css
    scripts/site.js
    scripts/islands/
```

| WP file | Twig | Role |
|---|---|---|
| `front-page.php` | `templates/front-page.twig` | Front (static page or latest posts) |
| `page.php` | `templates/page.twig` | Pages |
| `single.php` | `templates/single.twig` | Singles / CPTs without a more specific file |
| `archive.php` | `templates/archive.twig` | Archives without a more specific file |
| `search.php` | `templates/search.twig` | Search |
| `404.php` | `templates/404.twig` | 404 |
| `index.php` | `templates/index.twig` | Fallback; also the posts page when a static front is set |

Website-first hierarchy: `front-page` + `page` + `404` + `search` are the
site. `single` + `archive` + `index` stay so blogging works the moment
someone publishes posts. Do not add `author`, `category`, `tag`, `comments`,
or `sidebar` unless the project asks.

There is no `home.php`. `front-page.php` always wins the site front.
When Reading is “Your latest posts”, that file unsets `post` so the first
loop post is not treated as front-page body copy.

`archive.twig`, `index.twig`, and `search.twig` include `components/feed.twig`
(which includes `teaser.twig` + `pagination.twig`).

Render-tree order (Twig and `@scope` in `main.css` use this list):

1. `base.twig` → `.cnp-main`
2. `header.twig` → `.cnp-header`
3. `brand.twig` → `.cnp-brand` (header, hero, footer)
4. `hero.twig` → `.cnp-hero`
5. `faq.twig` → `.cnp-faq`
6. `breadcrumbs.twig` → `.cnp-crumbs`
7. `search-form.twig` → `.cnp-search`
8. `.cnp-container` (sibling of hero, own `@scope`)
9. `page.twig` → `.cnp-page`
10. `single.twig` → `.cnp-post`
11. `feed.twig` / `teaser.twig` → `.cnp-feed` / `.cnp-teaser`
12. `pagination.twig` → `.cnp-pagination`
13. `404.twig` → `.cnp-error-404`
14. `reveal.twig` → `.cnp-reveal`
15. `footer.twig` → `.cnp-footer`

Missing on purpose: `author.php`, `category.php`, `tag.php`, `taxonomy.php`,
`comments.php`, `header.php`, `footer.php`, `sidebar.php`.

Every shipped PHP template has a `Template Name:` header, so WordPress lists
them as selectable page templates.

`base.twig` includes `partials/head.twig`, skip link, `partials/header.twig`,
`<main id="main" class="cnp-main">` + `{% block content %}`, `partials/footer.twig`.
`<html {{ function('language_attributes') }}>`. One content block. Assets come
from `wp_enqueue_*`, not Twig.

## `Site.php` hooks

| Hook | Method | Role |
|---|---|---|
| `after_setup_theme` | `themeSupports` | title-tag, thumbnails, HTML5, textdomain `canopy`, menus |
| `init` | `registerPostTypes` | empty stub |
| `init` | `registerTaxonomies` | empty stub |
| `wp_enqueue_scripts` | `enqueueAssets` | `main.css` + `site.js` module, `filemtime` versions |
| `wp_enqueue_scripts` 20 | `dequeueBlockStyles` | drop Gutenberg frontend CSS |
| `customize_register` | `customizeRegister` | empty stub |
| `wp_head` 2 | `addNoindexTags` | noindex on search, 404, paged non-singular |
| `timber/context` | `addToContext` | menus + archive/search helpers |
| `timber/twig/filters` | `addFiltersToTwig` | `excerpt` |
| `timber/twig/functions` | `addFunctionsToTwig` | `get_theme_mod` + gettext |
| `timber/twig/environment/options` | `updateTwigEnvironmentOptions` | debug auto_reload vs `cache/twig` |
| `robots_txt` | `updateRobotsTxt` | Sitemap line on live hosts |
| `wp_sitemaps_enabled` | `__return_false` | native sitemaps off |
| `wp_mail_from` / `from_name` / `phpmailer_init` | mail + SMTP | `getenv('SMTP_*')` |

Menus registered: `primary_navigation` → Twig `menu`, `footer_navigation` →
`footer_menu`.

Twig cache: `WP_DEBUG` true → `auto_reload`; else
`web/app/themes/canopy/cache/twig` (gitignored). Deploy clears it with
`Timber\Cache\Cleaner::clear_cache_timber()`.

## Context keys

From `Timber::context()` / `context_global()`:

| Key | When |
|---|---|
| `site`, `theme`, `user`, `http_host`, `wp_title`, `body_class` | always |
| `post` | singular (includes static front) or `is_home()` |
| `posts` | home, tax, search, author, other archives |
| `term` | category / tag / tax |
| `author` | author archive |
| `search_query` | search |

From `Site::addToContext()` (always): `menu`, `footer_menu`, `body_class`
(overwrite), `archive_title`, `search_query`, `crumbs`. Search also gets
`found_posts` from `search.php`.

Shipped PHP templates do not add extra keys. Use `$context['…']` or
`Timber::context(['…' => …])` for page-specific data.

## Bedrock paths

| Path | Role |
|---|---|
| `web/` | document root |
| `web/wp/` | WordPress core (Composer, do not edit) |
| `web/app/` | `wp-content` |
| `web/app/themes/canopy/` | this theme |
| `config/application.php` | WP constants, `.env` |
| `config/environments/{WP_ENV}.php` | per-env overrides |
| `vendor/` | Composer (do not edit) |

Installer paths (`composer.json` extra): mu-plugins / plugins / themes under
`web/app/…/{$name}/`. Core: `wordpress-install-dir` = `web/wp`.

`WP_ENV` unset defaults to production. Required env: `WP_HOME`, `WP_SITEURL`.
Docker: `WP_HOME=http://localhost:8080`, `WP_SITEURL="${WP_HOME}/wp"`.

Composer-managed mu-plugin directories must be listed in `.gitignore`. Custom
mu-plugins you write are committed; deploy `git reset --hard` deletes
uncommitted ones.
