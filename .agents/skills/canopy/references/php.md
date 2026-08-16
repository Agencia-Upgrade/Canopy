# PHP

## Contents

- Template contract
- Context extras
- CPT and taxonomies
- Filters, functions, Customizer
- SMTP
- Quality

## Template contract

Every hierarchy file is the same two calls. Only the Twig path changes.

```php
use Timber\Timber;

$context = Timber::context();
Timber::render('templates/page.twig', $context);
```

PHP files live in the theme root. Twig lives under `views/`. A new view is a
PHP + Twig pair, not Twig alone (WordPress will not find a Twig file).

`Template Name:` in the PHP header makes WordPress list the file as a page
template. Use a dedicated file for real custom page templates. Do not assign
`index.php` or `404.php` to a page.

Namespace for new classes: `Canopy\` in `src/`. Methods camelCase; hook names
snake_case. Indent PHP with 4 spaces (PSR-12). Text domain: `canopy`.

## Context extras

Global (every view): `Site::addToContext()`.

This request only:

```php
$context = Timber::context();
$context['related'] = Timber::get_posts([
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post__not_in' => [get_the_ID()],
]);
Timber::render('templates/single.twig', $context);
```

Or merge into the call: `Timber::context(['related' => $posts])`.

Do not add page-specific queries to `addToContext()`.

## CPT and taxonomies

Register on `init` inside `Site::registerPostTypes()` /
`registerTaxonomies()`, priority 10 or lower so Timber can query the type.

```php
register_post_type('portfolio', [
    'labels' => [
        'name' => __('Portfolio', 'canopy'),
        'singular_name' => __('Portfolio Item', 'canopy'),
    ],
    'public' => true,
    'has_archive' => true,
    'rewrite' => ['slug' => 'portfolio'],
    'supports' => ['title', 'editor', 'thumbnail'],
    'show_in_rest' => true,
]);
```

Then add `single-portfolio.php` + `views/templates/single-portfolio.twig`
(and `archive-portfolio.php` if the archive layout differs).

After new rewrites: `wp rewrite flush`.

## Filters, functions, Customizer

```php
$filters['name'] = ['callable' => $callable];
$functions['name'] = ['callable' => $callable];
```

Passing a PHP function name string matches the theme (`'get_theme_mod'`).

Customizer controls go in `customizeRegister()`. Read them in Twig with
`{{ get_theme_mod('key') }}`. The Customizer stub is empty.

POT from templates: `wp i18n make-pot`.

## SMTP

`Site::configureSMTP()` uses `getenv()`, not `Env\env()`, so host-injected
secrets work. If `SMTP_HOST` is empty, PHPMailer is left alone (local Mailpit
does not see WP mail unless you set SMTP).

| Env | Default in code |
|---|---|
| `SMTP_HOST` | required to enable |
| `SMTP_PORT` | `587` |
| `SMTP_ENCRYPTION` | `tls` |
| `SMTP_USER` + `SMTP_PASS` | auth only if both set |
| `SMTP_FROM` | `noreply@{home host}` |
| `SMTP_FROM_NAME` | `get_bloginfo('name')` |

`.env.example` shows `465` / `ssl`. That is an example, not the code default.

## Quality

`phpstan.neon` analyses only:

- `web/app/themes/canopy/src`
- `functions.php` and the seven hierarchy PHP files

Twig, CSS, JS, `config/`, and plugins are out of scope.

```bash
composer lint
composer lint:fix
composer phpstan
```

`filemtime()` in `enqueueAssets()` has no `file_exists` guard. Do not delete
`main.css` or `site.js`.
