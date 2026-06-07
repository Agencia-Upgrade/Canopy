# CLAUDE.md — Canopy WordPress Boilerplate

> Developed by **Agência Upgrade** — https://agenciaupgrade.com.br
> Stack: WordPress 7.0 · Bedrock · Timber 2.x · Twig · PHP 8.5 · PSR-12

---

## About Canopy

Canopy is a production-ready WordPress boilerplate that combines Bedrock's infrastructure-first structure with Timber's clean templating approach. It's built from real-world patterns used across Agência Upgrade's client projects.

**Key decisions:**
- WordPress managed as a Composer dependency
- Timber/Twig for clean template separation
- No build step for assets (CSS/JS committed directly)
- Laravel Pint for PSR-12 formatting
- LiteSpeed Cache (optional) for production caching on LiteSpeed servers
- GitHub Actions for deployment and auto-updates

---

## Stack Details

| Component | Version | Notes |
|---|---|---|
| **WordPress** | ^7.0 | Follows minor/patch, blocks MAJOR via Actions |
| **PHP** | >=8.5 | PSR-4 namespacing, modern OOP patterns. Opcache is built into core. |
| **Bedrock** | Latest | Git-first, Composer-managed, `.env` per environment |
| **Timber** | ^2.1 | Installed in Bedrock root, available to any theme |
| **Twig** | 3.x | Via Timber, clean separation of PHP/HTML |
| **Laravel Pint** | ^1.0 | PSR-12 code formatting, `composer lint:fix` |
| **PHPStan** | ^2.1 | Static analysis level 5 via `composer phpstan` |
| **LiteSpeed Cache** | Latest | Optional — page + browser cache on LiteSpeed servers; install with `composer require wp-plugin/litespeed-cache` |

---

## Getting Started

### 1. Create a new project from Canopy

**Local (Docker) — recommended:**

```bash
git clone https://github.com/Agencia-Upgrade/canopy.git my-project
cd my-project
make setup
```

`make setup` creates `.env`, generates salts, builds the containers, installs
WordPress, and activates the theme. Access http://localhost:8080.

**Manual:**

```bash
git clone https://github.com/Agencia-Upgrade/canopy.git my-project
cd my-project
cp .env.example .env
# Edit .env with your database credentials and site URL
```

### 2. Local development (Docker)

**Quick start (one command):**

```bash
make setup
# Creates .env, generates salts, builds containers, installs WP, activates theme
# Access: http://localhost:8080 — Admin: http://localhost:8080/wp/wp-admin (admin/admin)
```

**Manual setup:**

```bash
# First time: build containers and install
make rebuild
make composer install

# Install WordPress
docker compose exec php wp --allow-root core install \
  --url=http://localhost:8080 \
  --title="My Site" \
  --admin_user=admin \
  --admin_password=password \
  --admin_email=admin@example.com

# Activate theme
docker compose exec php wp --allow-root theme activate canopy

# Start/stop (after initial setup)
make up
make down
```

**Default Docker credentials** (in `docker-compose.yml`):
- DB: `canopy_local` / user: `dev` / password: `dev` / host: `db`
- `.env` must match these values (or update both)

### 3. WP-CLI with Docker

```bash
# Simple commands via make
make wp plugin list
make wp core version

# Commands with flags — use docker compose exec directly
docker compose exec php wp --allow-root core install \
  --url=http://localhost:8080 --title="My Site" \
  --admin_user=admin --admin_password=password \
  --admin_email=admin@example.com

# Or pass flags via ARGS variable
make wp core install ARGS="--url=http://localhost:8080 --title='My Site'"

# Shell access for multiple commands
make bash
```

> **Why not just `make wp`?** Make interprets `--flags` as its own options. For WP-CLI commands with flags, use `docker compose exec` directly or the `ARGS=` pattern.

### 4. Deploy

GitHub Actions workflows handle deployment:
- **deploy-production.yml** → Pushes to main branch trigger SSH deployment, `composer install`, cache flushes
- **auto-update.yml** → Weekly (Monday 07:00 UTC) `composer update`, commit to main, auto-deploy if `composer.lock` changed

### 5. Without Docker (server/hosting)

```bash
composer install
cp .env.example .env
# Edit .env with your database and site URL
wp core install --url=https://example.com --title="My Site" \
  --admin_user=admin --admin_password=password \
  --admin_email=admin@example.com --allow-root
wp theme activate canopy --allow-root
```

---

## Boot Flow

Understanding the boot sequence is critical for debugging:

```
Browser → nginx → web/index.php
  → require web/wp/wp-blog-header.php
    → require web/wp-config.php
      1. require vendor/autoload.php    (Composer: loads Env, Timber, etc.)
      2. require config/application.php  (.env loading, WP constants, ABSPATH)
      3. require wp-settings.php         (WordPress core boot)
```

**Key files:**

- **`web/index.php`** — Minimal: defines `WP_USE_THEMES` and loads `wp-blog-header.php`. Does NOT load autoload or config directly.
- **`web/wp-config.php`** — Opens with a `defined('WP_CACHE') || define('WP_CACHE', true)` guard (prevents LiteSpeed Cache from injecting a bare `define()` and avoids PHP 9 redeclaration fatal), then three `require_once` lines: autoload → application → wp-settings. WP-CLI requires the literal `wp-settings.php` string to be present.
- **`config/application.php`** — Loads `.env` via phpdotenv, defines all WP constants via `Roots\WPConfig\Config`, sets `ABSPATH`.

---

## File Structure & Architecture

```
canopy/
├── .env.example              ← Template environment (versioned)
├── .env                      ← Actual config (NOT versioned)
├── composer.json             ← Root Bedrock dependencies + PSR-4 autoload
├── phpstan.neon              ← PHPStan static analysis config (level 5)
├── pint.json                 ← Laravel Pint PSR-12 config
├── wp-cli.yml                ← WP-CLI configuration (path, docroot)
├── Makefile                  ← Docker shortcuts (make setup/up/down/wp/bash/rebuild)
├── CLAUDE.md                 ← This file
├── README.md                 ← Public documentation
│
├── .docker/                  ← Docker configuration
│   ├── php/
│   │   ├── Dockerfile        ← PHP 8.5-fpm-bookworm + extensions + WP-CLI
│   │   └── php.ini           ← PHP overrides
│   └── nginx/
│       └── default.conf      ← Nginx vhost config
│
├── docker-compose.yml        ← Local stack: nginx, php, mariadb, redis, mailpit
│
├── config/                   ← WordPress configuration (Roots/WPConfig)
│   ├── application.php       ← Base config, loads .env, defines constants
│   └── environments/
│       ├── development.php   ← Dev: debug on, cache off
│       ├── staging.php       ← Staging: cache on, no indexing
│       └── production.php    ← Prod: LiteSpeed cache, auto-purge
│
├── web/                      ← Web root (document root for nginx)
│   ├── index.php             ← Entry point → wp-blog-header.php
│   ├── wp-config.php         ← Autoload → application.php → wp-settings.php
│   ├── wp/                   ← WordPress core (Composer-managed, .gitignored)
│   ├── app/
│   │   ├── mu-plugins/       ← Must-use plugins (Composer-managed as directories)
│   │   ├── plugins/          ← Regular plugins (Composer-managed)
│   │   ├── uploads/          ← User uploads (.gitignored)
│   │   └── themes/
│   │       └── canopy/       ← Main theme (production-ready template)
│   │           ├── style.css       ← WP theme metadata only
│   │           ├── functions.php   ← Boot: Timber + Site class
│   │           ├── src/
│   │           │   └── Site.php    ← Central setup class (hooks, menus, CPTs, context)
│   │           ├── views/
│   │           │   ├── layouts/
│   │           │   │   └── base.twig      ← HTML shell
│   │           │   ├── partials/
│   │           │   │   ├── head.twig      ← Meta tags, assets
│   │           │   │   ├── header.twig    ← Site header + nav
│   │           │   │   └── footer.twig    ← Site footer
│   │           │   ├── components/        ← Reusable Twig fragments
│   │           │   └── templates/
│   │           │       ├── front-page.twig
│   │           │       ├── page.twig
│   │           │       ├── single.twig
│   │           │       ├── archive.twig
│   │           │       ├── search.twig
│   │           │       ├── 404.twig
│   │           │       └── index.twig     ← Fallback
│   │           ├── assets/
│   │           │   ├── styles/
│   │           │   │   └── main.css       ← Stylesheet (token-first, BEM)
│   │           │   ├── scripts/
│   │           │   │   ├── site.js        ← Island loader (lazy per-component JS)
│   │           │   │   └── islands/       ← Per-component modules (import Motion, etc.)
│   │           │   ├── fonts/             ← Self-hosted fonts
│   │           │   └── images/            ← Theme images
│   │           ├── cache/                 ← Twig cache (generated, .gitignored)
│   │           └── [other theme files]
│
├── .github/workflows/
│   ├── ci.yml                   ← Lint + PHPStan on every push/PR
│   ├── deploy-production.yml    ← Deploy on push to main
│   └── auto-update.yml          ← Weekly composer update + auto-deploy
│
└── vendor/                      ← Composer dependencies (.gitignored)
```

---

## Theme Architecture

### `Site.php` — Central Setup Class

All theme initialization happens in `Site.php` (extends `Timber\Site`):

```php
class Site extends TimberSite {
  public function __construct() {
    add_action('after_setup_theme', [$this, 'themeSupports']);
    add_action('init', [$this, 'registerPostTypes']);
    add_action('init', [$this, 'registerTaxonomies']);
    add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
    // ... more hooks
    add_filter('timber/context', [$this, 'addToContext']);
    // ... more filters
  }

  public function themeSupports(): void { /* theme supports */ }
  public function registerPostTypes(): void { /* CPTs */ }
  public function enqueueAssets(): void { /* CSS + JS module */ }
  public function addToContext(array $context): array { /* Twig global data */ }
  // ... more methods
}
```

**Responsibilities:**
- Theme feature support (title-tag, post-thumbnails, menus, HTML5)
- Custom post type registration
- Custom taxonomy registration
- Asset enqueue (CSS, JS module)
- Twig context (menus, global data)
- Twig filters and functions
- Cache configuration
- SMTP setup
- Robots.txt and sitemap logic

### Timber Context

Data passed to Twig templates via `addToContext()`:

```php
public function addToContext(array $context): array {
    $context['menu'] = Timber::get_menu('primary_navigation');
    $context['footer_menu'] = Timber::get_menu('footer_navigation');
    return $context;
}
```

Access in Twig:
```twig
{% for item in menu.items %}
  <a href="{{ item.url }}">{{ item.title }}</a>
{% endfor %}
```

### Twig Views

**Layout: `views/layouts/base.twig`**
- HTML5 doctype, head, body
- Includes partials (head, header, footer)
- `{% block content %}` for page-specific content

**Partials: `views/partials/`**
- `head.twig` — Meta tags, preconnects, wp_head()
- `header.twig` — Logo, navigation
- `footer.twig` — Footer nav, credits, wp_footer()

**Components: `views/components/`**
- Reusable Twig snippets (buttons, cards, CTA blocks)
- Called with `{% include 'components/card.twig' with { ... } %}`

**Templates: `views/templates/`**
- Each post type has a template: `page.twig`, `single.twig`, `archive.twig`, etc.
- Extend `base.twig` and fill `{% block content %}`

Example:
```twig
{# views/templates/page.twig #}
{% extends "layouts/base.twig" %}

{% block content %}
  <article class="cnp-page">
    <h1>{{ post.title }}</h1>
    {{ post.content|raw }}
  </article>
{% endblock %}
```

### CSS — Token-First Architecture

**File: `assets/styles/main.css`**

Uses `@layer` for organization:

```css
@layer reset, tokens, base, components, utilities;

@layer tokens {
  :root {
    --cnp-color-primary: #0066cc;
    --cnp-space-16: 1.6rem;
    --cnp-font-size-lg: 2rem;
  }
}

@layer components {
  .cnp-header { /* ... */ }
  .cnp-nav { /* ... */ }
}
```

**Conventions:**
- **Prefix:** `cnp-` (e.g., `.cnp-header`, `.cnp-nav__list`)
- **Naming:** BEM (Block__Element--Modifier)
- **Font size:** `1rem = 10px` (set via `html { font-size: 62.5%; }`)
- **Spacing:** Tokens like `--cnp-space-16` for consistency
- **No preprocessor:** Plain CSS, no build step

**Production:** Served as-is; the web server / LiteSpeed Cache handles minification and compression.

### JavaScript — islands, loaded on demand

JavaScript is split into **islands**: small per-component modules that load only
when their markup is on the page, and only once it scrolls into view. There is no
build step — `site.js` is an ES module enqueued with `wp_enqueue_script_module()`,
and islands are loaded with native dynamic `import()`. Heavy dependencies (e.g.
Motion) live inside an island, so a page that doesn't use them never downloads them.

```
assets/scripts/
├── site.js              ← island loader (scans data-island, lazy-imports modules)
└── islands/
    └── reveal.js        ← example island: imports Motion, animates on view
```

**How it works:**
- Markup opts in: `<div data-island="reveal"> … </div>`.
- `site.js` registers each island name → dynamic import, observes it with
  `IntersectionObserver`, and hydrates when it nears the viewport.
- Add `data-island-eager` to hydrate immediately instead of on scroll.
- The island's default export receives the element: `export default (el) => { … }`.

**Adding an island:**
1. Create `assets/scripts/islands/<name>.js` with a default export.
2. Register it in `site.js`: `<name>: () => import('./islands/<name>.js'),`.
3. Add `data-island="<name>"` to the component markup (see
   `views/components/reveal.twig`).

**Motion** ([motion.dev](https://motion.dev)) is imported inside an island from
jsDelivr (pin the version in the URL). Always keep the `prefers-reduced-motion`
check before animating.

---

## Commands & Workflows

### Docker (local development)

```bash
# Start/stop
make up
make down

# First time or after Dockerfile changes
make rebuild

# Shell access
make bash

# View logs
make logs
```

### Composer (inside Docker)

```bash
make composer install
make composer update
make composer require wp-plugin/plugin-name
make composer show

# Code style
composer lint          # Check (Pint PSR-12)
composer lint:fix      # Fix
composer phpstan       # Static analysis (level 5)
```

### WP-CLI (inside Docker)

```bash
# Simple commands via make
make wp plugin list
make wp core version
make wp user list

# Commands with flags — use docker compose exec
docker compose exec php wp --allow-root core install \
  --url=http://localhost:8080 \
  --title="My Site" \
  --admin_user=admin \
  --admin_password=password \
  --admin_email=admin@example.com

docker compose exec php wp --allow-root theme activate canopy
docker compose exec php wp --allow-root plugin activate litespeed-cache
docker compose exec php wp --allow-root cache flush

# Alternative: use ARGS= for flags
make wp core install ARGS="--url=http://localhost:8080 --title='My Site'"
```

### Without Docker

```bash
composer install
composer lint
composer phpstan
wp core install --url=https://example.com --title="My Site" ...
wp theme activate canopy --allow-root
```

---

## Extending Canopy

### Adding a Custom Post Type

In `Site.php`:

```php
public function registerPostTypes(): void
{
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
}
```

Create template: `views/templates/single-portfolio.twig`

### Adding a Twig Filter

In `Site.php`:

```php
public function addFiltersToTwig(array $filters): array
{
    $filters['uppercase'] = [
        'callable' => fn($text) => strtoupper($text),
    ];
    return $filters;
}
```

Use in Twig:
```twig
<h1>{{ post.title|uppercase }}</h1>
```

### Adding a Twig Function

```php
public function addFunctionsToTwig(array $functions): array
{
    $functions['get_related_posts'] = [
        'callable' => function($post_id, $limit = 3) {
            return Timber::get_posts([
                'post__not_in' => [$post_id],
                'posts_per_page' => $limit,
            ]);
        },
    ];
    return $functions;
}
```

Use in Twig:
```twig
{% for related in get_related_posts(post.id) %}
  <a href="{{ related.link }}">{{ related.title }}</a>
{% endfor %}
```

### Adding a Must-Use Plugin (mu-plugin)

Bedrock v2 installs mu-plugins as **directories** via `type:wordpress-muplugin`. Each Composer-managed directory must be added to `.gitignore` manually — the pattern only ignores what's explicitly listed.

```bash
# Install via Composer
make composer require roots/some-mu-plugin

# Add to .gitignore to prevent tracking the Composer-managed directory
echo "/web/app/mu-plugins/some-mu-plugin/" >> .gitignore
```

Custom mu-plugins you write manually (files or folders) should be committed to git normally — only Composer-managed ones are ignored. If a custom plugin is not committed, `git reset --hard` on deploy will delete it.

### Adding a Plugin

```bash
# Via Composer (inside Docker)
make composer require wp-plugin/yoast-seo

# Activate
docker compose exec php wp --allow-root plugin activate yoast-seo
```

---

## Standards & Conventions

### PHP

- **Version:** 8.5+
- **Namespacing:** `namespace Canopy;` (PSR-4: `Canopy\` in `src/`)
- **Code style:** PSR-12 via Laravel Pint
  - 4 spaces indentation
  - camelCase for methods
  - snake_case for hooks (WordPress convention)
  - Check with `composer lint`; fix with `composer lint:fix`

### CSS

- **Architecture:** Token-first `@layer` organization
- **Prefix:** `cnp-` (Canopy)
- **Naming:** BEM (`Block__Element--Modifier`)
- **Font size:** 1rem = 10px
- **Spacing:** Scaled tokens (`--cnp-space-4`, `--cnp-space-8`, etc.)
- **Responsive:** Mobile-first `@media (min-width: ...)`
- **Accessibility:** `prefers-reduced-motion`, high contrast colors, semantic HTML

### JavaScript

- **Vanilla JS** (no jQuery)
- **Motion for animations** (ES module via CDN, with `prefers-reduced-motion` check)
- **Defer/async loading** for non-critical scripts
- **No transpilation** (ES6+, but runs natively)

### SEO & Performance

- **Lighthouse:** ≥ 95 (all metrics)
- **Core Web Vitals:** Optimized (CLS, FID, LCP)
- **Schema.org:** JSON-LD markup for rich snippets
- **Robots.txt:** Dynamic (blocks AI bots, includes sitemap link)
- **Sitemap:** Auto-generated (optional; set `wp_sitemaps_enabled` to enable WordPress native sitemaps)
- **Caching:** LiteSpeed Cache plugin + browser cache headers

### Accessibility

- **WCAG 2.2 AA minimum**
- **Semantic HTML5** (proper use of `<header>`, `<main>`, `<article>`, `<nav>`)
- **ARIA labels** where needed
- **Color contrast:** 4.5:1 for text, 3:1 for graphics
- **Keyboard navigation:** All interactive elements keyboard-accessible
- **Motion:** Respect `prefers-reduced-motion`

---

## Deployment

### GitHub Secrets (Required)

```
PROD_SSH_HOST       → Production server hostname
PROD_SSH_USER       → SSH user
PROD_SSH_KEY        → SSH private key (full contents)
PROD_SSH_PORT       → SSH port (usually 22)
PROD_WP_PATH        → Project path on server
CF_ZONE_ID          → Cloudflare Zone ID
CF_API_TOKEN        → Cloudflare API token (cache_purge permission)
```

### Workflows

**`ci.yml`** (runs on push/PR to `main`):
1. Setup PHP 8.5
2. `composer install`
3. `composer lint` (Pint PSR-12)
4. `composer phpstan` (level 5)

**`deploy-production.yml`** (runs on `push` to `main`):
1. SSH to production
2. `git reset --hard origin/main`
3. `composer install --no-dev --optimize-autoloader`
4. `wp rewrite flush` (update permalink structure)
5. `wp cache flush` (clear object cache)
6. `Timber\Cache\Cleaner::clear_cache_timber()` (clear Twig template cache)
7. Purge Cloudflare cache (all files)

**`auto-update.yml`** (runs Monday 07:00 UTC):
1. `composer update` (respects version constraints in `composer.json`)
2. Check if WordPress MAJOR version bumped
3. If bumped: comment on the auto-generated commit with alert
4. If `composer.lock` changed: commit + push + auto-deploy

### Manual Deployment

```bash
ssh user@host
cd /path/to/project
git pull origin main
composer install --no-dev --optimize-autoloader
wp rewrite flush --allow-root
wp cache flush --allow-root
wp eval 'Timber\Cache\Cleaner::clear_cache_timber();' --allow-root
```

---

## Troubleshooting

### PHPStan: "Cannot redeclare function" fatal

If PHPStan crashes with a fatal about `wp_hash_password()` or another WP function being redeclared, a plugin is redefining a core function that `wordpress-stubs` also declares. Fix: remove the conflicting package (e.g. `roots/wp-password-bcrypt` was removed because WordPress 6.8+ ships native bcrypt). Do not switch `includes: extension.neon` to `scanFiles` as a workaround — remove the root cause instead.

### "Warning: Constant WP_CACHE already defined"

LiteSpeed Cache injects `define('WP_CACHE', true)` at the top of `web/wp-config.php` on plugin activation. The environment files use `defined('WP_CACHE') || define(...)` guards to avoid redefinition. If the warning appears, verify `web/wp-config.php` has the guard at the top and the environment file uses `defined() ||`. A bare `define()` in either file will trigger the warning on PHP 8 and a fatal on PHP 9.

### PHP 8.5: Opcache is built-in

On PHP 8.5, opcache is compiled statically into core. Do NOT add it to `docker-php-ext-install` or `docker-php-ext-enable` — it will fail with `'opcache' does not exist`.

### PHP 8.5: WP-CLI deprecation warnings

WP-CLI shows deprecation notices on PHP 8.5 (`Creation of dynamic property`, `Case statements followed by a semicolon`, `Using null as an array offset`). These come from WP-CLI's own vendor packages — cosmetic only, WP-CLI functions correctly. They will be resolved in a future WP-CLI release.

### "Class Env\Env not found" in browser

The Bedrock boot files must follow the correct format:

**`web/index.php`** must only load `wp-blog-header.php`:
```php
define('WP_USE_THEMES', true);
require __DIR__ . '/wp/wp-blog-header.php';
```

**`web/wp-config.php`** must load autoload, config, and wp-settings:
```php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/application.php';
require_once ABSPATH . 'wp-settings.php';
```

If `index.php` loads `config/application.php` directly instead of going through `wp-config.php`, autoload won't be loaded and the `Env\Env` class won't be available.

### WP-CLI: "Strange wp-config.php" error

WP-CLI does a literal grep for the string `wp-settings.php` in `wp-config.php`. The file must contain:
```php
require_once ABSPATH . 'wp-settings.php';
```
Without this line, WP-CLI refuses to run.

### "Headers already sent" error

Check that `functions.php` and `src/Site.php` have no output before the opening `<?php` tag.

### Timber not loading posts in Twig

Make sure `registerPostTypes()` runs on the `init` hook with priority 10 or lower, before Timber queries posts.

### CSS not updating in browser

The theme uses `filemtime()` for cache-busting:
```php
wp_enqueue_style('canopy-main', $uri . '/' . $cssFile, [], filemtime($dir . '/' . $cssFile));
```

If you edit CSS but the browser shows old styles:
- Hard refresh (Ctrl+Shift+R / Cmd+Shift+R)
- Check that `main.css` file timestamp actually changed
- Verify WP_DEBUG mode (dev should load unminified)

### Twig template not found

Check:
1. File exists in `views/` directory
2. Filename matches template name (e.g., `single-portfolio.twig` for `single-portfolio.php`)
3. PHP template file (e.g., `single-portfolio.php`) calls `Timber::render()` with correct path

### Docker: DB credentials mismatch

The `.env` database credentials must match `docker-compose.yml`. Default Docker values:
```
DB_NAME=canopy_local
DB_USER=dev
DB_PASSWORD=dev
DB_HOST=db
```

`.env.example` already ships with these Docker values, and `make setup` copies it for you. If you set up manually, ensure `.env` and `docker-compose.yml` match.

### Docker: cached layers after Dockerfile changes

If you edit the Dockerfile but `make up` uses old layers, run:
```bash
make rebuild   # = make down + build --no-cache + up
```

---

## References

- **External**
  - [Timber Documentation](https://timber.github.io/docs/v2/)
  - [Bedrock Docs](https://roots.io/bedrock/)
  - [Laravel Pint](https://laravel.com/docs/pint)
  - [WordPress.org Handbook](https://developer.wordpress.org/)

---

## Support & Contributing

Developed by **[Agência Upgrade](https://agenciaupgrade.com.br)**

- 🌐 Website: https://agenciaupgrade.com.br
- 📧 Email: contato@agenciaupgrade.com.br
- 🐙 GitHub: https://github.com/Agencia-Upgrade

MIT License
