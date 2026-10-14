# Timber 2 and Twig

This repo installs `timber/timber` `^2.1` (lock 2.5.x) and Twig 3.x via Timber.

## Contents

- PHP API
- Context
- Twig layout
- Objects in Twig
- Pagination
- Escaping
- Filters and functions
- Timber 1 bans

## PHP API

```php
Timber::init();
Timber::context(array $extra = []): array
Timber::context_global(): array
Timber::render($filenames, $data = []): void
Timber::compile($filenames, $data = []): string

Timber::get_post($query = false, $options = []): ?Post
Timber::get_posts($query = false, $options = []): ?PostCollectionInterface
Timber::get_post_by(string $type, string $search_value, array $args = []): ?Post

Timber::get_menu($identifier = null, array $args = []): ?Menu
Timber::get_term($term = null): ?Term
Timber::get_terms($args = null, array $options = []): iterable
```

`$query` for posts is an ID, `WP_Post`, `WP_Query`, WP_Query **array**, or
`false` (current query). Query **strings** are gone. Options may include
`merge_default => true` to layer args onto the main query.

`$filenames` is a string or array (first existing file wins). Paths are
relative to `views/`.

Class maps: `timber/post/classmap`, `timber/term/classmap`,
`timber/menu/classmap`. Prefer maps over passing a class name as the second
argument (that Timber 1 signature is gone).

When you replace the singular `post` yourself, call `$post->setup()` before
putting it in context.

## Context

`Timber::context()` merges:

1. Global cache (`context_global()`), then `timber/context` (`Site::addToContext`)
2. Template keys from the main query (`post`, `posts`, `term`, `author`, …)
3. Optional `$extra` for this call only

Define `timber/context` before the first `Timber::context()` in the request
(the theme constructor does this).

Shipped templates never put `posts` in `addToContext()`. Archives use the
`posts` key Timber already set.

## Twig layout

Templates extend `layouts/base.twig` and fill `{% block content %}`.

Includes are relative to `views/`:

```twig
{% include 'partials/pagination.twig' %}
{% include 'components/reveal.twig' with { content: html } %}
{% embed 'components/reveal.twig' %}
  {% block reveal %}{{ post.content|raw }}{% endblock %}
{% endembed %}
```

Gettext is registered on Twig. Text domain is `canopy`:

```twig
{{ __('Read more →', 'canopy') }}
```

WordPress functions: `{{ function('wp_head') }}`, `{{ function('wp_footer') }}`.

## Objects in Twig

| Need | Use |
|---|---|
| Title, URL, body, excerpt | `post.title`, `post.link`, `post.content`, `post.excerpt` |
| Date | `post.date\|date('F j, Y')` or `'c'` |
| Type | `post.type` |
| Featured image | `post.image` / `post.thumbnail` → `.src`, `.alt`; `src\|resize(w, h)` |
| Meta | `post.meta('key')` (not `post.get_field`) |
| Terms | `post.terms('category')` → `term.link`, `term.name` |
| Author | `post.author.name` |
| Site | `site.name`, `site.url` |
| Menu item | `item.url`, `item.title`, `item.current`, `item.children` |

Shipped loops use `post.link`, `post.title`, `post.excerpt` only.

## Pagination

Use the collection API, as `views/partials/pagination.twig` does:

- `posts.pagination.pages` — `page.link`, `page.class`, `page.title`
- `posts.pagination.prev.link` / `posts.pagination.next.link`
- Current page has no `page.link`; CSS expects Timber's `current` class

## Escaping

Timber's Twig environment defaults `autoescape` to `false`. Canopy does not
turn it on.

- `{{ post.content|raw }}` — WP already sanitized; required to keep markup
- `{{ search_query|e }}` — user input
- Titles, links, excerpts: output as-is in shipped templates
- `content|raw` in `reveal.twig` is the caller's responsibility

Never `|raw` unsanitized input.

## Filters and functions

Canopy adds:

- Filter `excerpt` → `wp_trim_words($text, $length = 55)`:
  `{{ post.content|excerpt(30) }}`. Lists use `post.excerpt` (Post property).
- Function `get_theme_mod`
- Gettext: `__`, `_e`, `_x`, `_n`, `esc_html__`, `esc_html_e`, `esc_attr__`,
  `esc_attr_e`

Timber also exposes `get_post` / `get_posts` as Twig functions. Do not use
them inside loops. Prefetch in PHP.

Useful Timber filters: `resize`, `retina`, `wpautop`, `time_ago`, `truncate`.

## Timber 1 bans

| Do not write | Write |
|---|---|
| `new Timber\Post($id)` | `Timber::get_post($id)` |
| `new Timber\Menu('primary')` | `Timber::get_menu('primary_navigation')` |
| `new Timber\Timber()` | `Timber::init()` |
| `Timber::get_context()` | `Timber::context()` |
| `Timber::get_post('about-us')` | `Timber::get_post_by('slug', 'about-us')` |
| `Timber::get_posts('post_type=x')` | `Timber::get_posts(['post_type' => 'x'])` |
| `Timber::get_pagination()` | `{{ posts.pagination }}` |
| `{{ post.get_field('x') }}` | `{{ post.meta('x') }}` |
| `{{ post.preview }}` | `{{ post.excerpt }}` |
| `{{ Post() }}` / `{{ Image() }}` | `get_post()` / `get_image()` |
| Filter `timber_context` | `timber/context` |
| Hook `timber/twig` to add filters | `timber/twig/filters` / `functions` |
