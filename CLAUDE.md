# CLAUDE.md — Canopy WordPress Boilerplate

> Developed by **Agência Upgrade** — https://agenciaupgrade.com.br
> Stack: WordPress 6.9 · Bedrock · Timber 2.x · Twig · PHP 8.5 · PSR-12

---

## About Canopy

Canopy is a production-ready WordPress boilerplate that combines Bedrock's infrastructure-first structure with Timber's clean templating approach. It's built from real-world patterns used across Agência Upgrade's client projects.

**Key decisions:**
- WordPress as a Composer dependency (not manually installed)
- Timber/Twig for template separation (not Sage/Blade)
- No build step for assets (CSS/JS committed directly)
- Laravel Pint for PSR-12 formatting (not PHPCS)
- Redis for caching (with LiteSpeed as alternative)
- GitHub Actions for deployment and auto-updates

---

## Stack Details

| Component | Version | Notes |
|---|---|---|
| **WordPress** | ^6.9 | Follows minor/patch, blocks MAJOR via Actions |
| **PHP** | >=8.5 | PSR-4 namespacing, modern OOP patterns |
| **Bedrock** | Latest | Git-first, Composer-managed, `.env` per environment |
| **Timber** | ^2.1 | Installed in Bedrock root, available to any theme |
| **Twig** | 3.x | Via Timber, clean separation of PHP/HTML |
| **Laravel Pint** | ^1.0 | PSR-12 code formatting, `composer lint:fix` |
| **Redis** | Any | redis-cache plugin, optional—LiteSpeed alternative |
| **PHP-BCrypt** | ^1.1 | Secure password hashing via `roots/wp-password-bcrypt` |

---

## Getting Started

### 1. Create a new project from Canopy

```bash
git clone https://github.com/Agencia-Upgrade/canopy.git my-project
cd my-project
cp .env.example .env
# Edit .env with your database and site URLs
composer install
wp core install --url=http://localhost --title="My Site" --admin_user=admin --admin_password=password --admin_email=admin@example.com --allow-root
wp theme activate canopy --allow-root
```

### 2. Local development (Docker)

```bash
# Start
make up

# Run commands inside PHP container
make composer require wp-plugin/plugin-name
make wp plugin list
make bash

# Logs
make logs

# Stop
make down
```

### 3. Deploy

GitHub Actions workflows handle deployment:
- **deploy-production.yml** → Pushes to main branch trigger SSH deployment, `composer install`, cache flushes
- **auto-update.yml** → Weekly (Monday 07:00 UTC) `composer update`, commit to main, auto-deploy if `composer.lock` changed

---

## File Structure & Architecture

```
canopy/
├── .env.example              ← Template environment (versioned)
├── .env                      ← Actual config (NOT versioned)
├── composer.json             ← Root Bedrock dependencies + PSR-4 autoload
├── pint.json                 ← Laravel Pint PSR-12 config
├── wp-cli.yml                ← WP-CLI configuration
├── Makefile                  ← Docker/local dev shortcuts
├── CLAUDE.md                 ← This file
├── README.md                 ← Public documentation
│
├── config/                   ← WordPress configuration (Roots/WPConfig)
│   ├── application.php       ← Base config, loads environment overrides
│   └── environments/
│       ├── development.php   ← Dev: debug on, cache off
│       ├── staging.php       ← Staging: cache on, no indexing
│       └── production.php    ← Prod: LiteSpeed cache, auto-purge
│
├── web/                      ← Web root (everything below is served)
│   ├── index.php             ← Entry point (loads config/application.php)
│   ├── wp-config.php         ← WP config loader (minimal)
│   ├── wp/                   ← WordPress core (Composer-managed, .gitignored)
│   ├── app/
│   │   ├── mu-plugins/       ← Must-use plugins (Composer-managed)
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
│   │           │   │   ├── main.css       ← Source CSS (token-first, BEM)
│   │           │   │   └── main.min.css   ← Production (optional, manual minification)
│   │           │   ├── scripts/
│   │           │   │   ├── site.js        ← Source JS
│   │           │   │   └── site.min.js    ← Production (optional)
│   │           │   ├── fonts/             ← Self-hosted fonts
│   │           │   └── images/            ← Theme images
│   │           ├── cache/                 ← Twig cache (generated, .gitignored)
│   │           └── [other theme files]
│
├── .github/workflows/
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
  public function enqueueAssets(): void { /* CSS + JS + GSAP */ }
  public function addToContext($context): array { /* Twig global data */ }
  // ... more methods
}
```

**Responsibilities:**
- Theme feature support (title-tag, post-thumbnails, menus, HTML5)
- Custom post type registration
- Custom taxonomy registration
- Asset enqueue (CSS, JS, GSAP plugins)
- Twig context (menus, global data)
- Twig filters and functions
- Cache configuration
- SMTP setup
- Robots.txt and sitemap logic

### Timber Context

Data passed to Twig templates via `addToContext()`:

```php
public function addToContext($context) {
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
- **No preprocessor:** Vanilla CSS only (no Sass, PostCSS)

**Production:** Minify to `main.min.css` (optional; committed to Git)

### JavaScript — Vanilla + GSAP

**File: `assets/scripts/site.js`**

Loaded deferred in footer via `wp_enqueue_script()`:

```javascript
document.addEventListener('DOMContentLoaded', function () {
  // Respect prefers-reduced-motion
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!prefersReducedMotion) {
    // GSAP animations here
    // gsap.to('.element', { duration: 1, opacity: 1 });
  }
});
```

**GSAP via CDN:** Loaded from jsDelivr, not npm:
- `gsap.min.js` + 9 free plugins (ScrollTrigger, Flip, etc.)
- Always include `prefers-reduced-motion` check

**Production:** Minify to `site.min.js` (optional; committed to Git)

---

## Commands & Workflows

### Composer

```bash
# Install dependencies
composer install

# Update dependencies (with version constraints)
composer update

# Check code style (Pint)
composer lint

# Fix code style automatically
composer lint:fix

# Add a WordPress plugin
composer require wp-plugin/plugin-name

# View installed packages
composer show
```

### WordPress

```bash
# Via make (inside Docker)
make wp core version
make wp plugin list
make wp user list

# Direct WP-CLI
wp core install --url=http://localhost --title="Test"
wp plugin activate redis-cache
wp cache flush
```

### Local Development

```bash
# Start Docker stack
make up

# Access shell
make bash

# View logs
make logs

# Stop
make down

# Rebuild containers
make rebuild
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
public function addFiltersToTwig($filters)
{
    $filters['uppercase'] = new TwigFilter('uppercase', fn($text) => strtoupper($text));
    return $filters;
}
```

Use in Twig:
```twig
<h1>{{ post.title|uppercase }}</h1>
```

### Adding a Twig Function

```php
public function addFunctionsToTwig($functions)
{
    $functions['get_related_posts'] = new TwigFunction('get_related_posts', function($post_id, $limit = 3) {
        return Timber::get_posts([
            'post__not_in' => [$post_id],
            'posts_per_page' => $limit,
        ]);
    });
    return $functions;
}
```

Use in Twig:
```twig
{% for related in get_related_posts(post.id) %}
  <a href="{{ related.link }}">{{ related.title }}</a>
{% endfor %}
```

### Adding a Plugin

```bash
# Via Composer
composer require wp-plugin/yoast-seo

# Activate
wp plugin activate yoast-seo --allow-root
```

---

## Standards & Conventions

### PHP

- **Version:** 8.5+
- **Namespacing:** `namespace App;` (PSR-4: `Canopy\` in `src/`)
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
- **GSAP for animations** (via CDN, with `prefers-reduced-motion` check)
- **Defer/async loading** for non-critical scripts
- **No transpilation** (ES6+, but runs natively)

### SEO & Performance

- **Lighthouse:** ≥ 95 (all metrics)
- **Core Web Vitals:** Optimized (CLS, FID, LCP)
- **Schema.org:** JSON-LD markup for rich snippets
- **Robots.txt:** Dynamic (blocks AI bots, includes sitemap link)
- **Sitemap:** Auto-generated (optional; set `wp_sitemaps_enabled` to enable WordPress native sitemaps)
- **Caching:** Redis object cache + LiteSpeed/browser cache

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

### Redis not caching

Verify:
1. Redis server is running (`redis-cli ping` should return `PONG`)
2. Plugin "Redis Object Cache" is activated
3. Connection settings in `.env` are correct
4. Check `wp cache status --allow-root`

---

## References

- **`docs/`** directory — Developer guides
  - [`docs/TIMBER.md`](docs/TIMBER.md) — Timber/Twig patterns and reference
  - [`docs/CSS-PATTERNS.md`](docs/CSS-PATTERNS.md) — CSS conventions (tokens, BEM, layers)
  - [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) — Full deployment guide

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
