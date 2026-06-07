<p align="center">
  <img src=".github/assets/logo.svg" alt="Canopy" width="200" />
</p>

<h1 align="center">Canopy — WordPress Boilerplate</h1>

<p align="center">
  A production-ready WordPress boilerplate combining Bedrock's structure,<br>
  Timber's templating power, and proven patterns from Agência Upgrade's real-world projects.
</p>

<p align="center">
  <strong>Built by <a href="https://agenciaupgrade.com.br">Agência Upgrade</a></strong>
</p>


---

## Features

- **Bedrock** — WordPress as a Composer dependency, cleaner folder structure
- **Timber 2.x** — Twig templating for cleaner PHP/HTML separation
- **Laravel Pint** — PSR-12 code formatting (PSR-4 namespacing)
- **LiteSpeed Cache** — File-first cache configuration for production
- **Zero Build Step** — CSS and JS committed directly; optional minification
- **GitHub Actions** — Automated deployment and weekly dependency updates
- **Cloudflare-Ready** — DNS, CDN, and image transformation integration
- **Accessibility & Performance** — WCAG 2.2 AA, Lighthouse ≥ 95 targets

## Quick Start

### Prerequisites

- PHP 8.5+
- Composer 2.x
- Docker (for local development) or web hosting
- MySQL 8.0+ or MariaDB 11+

### One-command local setup (Recommended)

From a fresh clone, this creates `.env`, generates salts, builds the containers,
installs WordPress, and activates the theme:

```bash
git clone https://github.com/Agencia-Upgrade/canopy.git my-site
cd my-site
make setup
# Access: http://localhost:8080 — Admin: http://localhost:8080/wp/wp-admin (admin/admin)
```

### Manual setup

If you prefer control over each step:

```bash
git clone https://github.com/Agencia-Upgrade/canopy.git my-site
cd my-site
cp .env.example .env
```

Edit `.env` with your values:

```
DB_NAME=canopy_local
DB_USER=dev
DB_PASSWORD=dev
DB_HOST=db
WP_HOME=http://localhost
WP_ENV=development
```

Start Docker and install dependencies:

```bash
make rebuild
make composer install
```

Install WordPress:

```bash
docker compose exec php wp --allow-root core install \
  --url=http://localhost \
  --title="My Site" \
  --admin_user=admin \
  --admin_password=password \
  --admin_email=admin@example.com

docker compose exec php wp --allow-root theme activate canopy
```

### Local Development with Docker

```bash
# Start containers
make up

# First time or after Dockerfile changes
make rebuild

# Install/update Composer packages
make composer install
make composer require wp-plugin/plugin-name

# WP-CLI (simple commands via make)
make wp plugin list
make wp core version

# WP-CLI (commands with flags — use docker compose exec directly)
docker compose exec php wp --allow-root core install \
  --url=http://localhost --title="My Site" \
  --admin_user=admin --admin_password=password \
  --admin_email=admin@example.com

# Shell access
make bash

# View logs
make logs

# Stop containers
make down
```

> **Note:** `make wp` works for simple WP-CLI commands. For commands with `--flags`, use `docker compose exec php wp --allow-root ...` directly, or pass flags via `ARGS=`: `make wp core install ARGS="--url=http://localhost"`.

### Without Docker (Server/Hosting)

```bash
composer install
cp .env.example .env
# Edit .env with your database and site URLs

wp core install --url=https://example.com --title="My Site" \
  --admin_user=admin --admin_password=password \
  --admin_email=admin@example.com --allow-root

wp theme activate canopy --allow-root
```

### Code Quality

```bash
# Check code style (Pint)
composer lint

# Fix code style
composer lint:fix
```

## Structure

```
Canopy/
├── config/                    # WordPress config (application.php + environments)
├── web/
│   ├── index.php             # Entry point (loads wp-blog-header.php)
│   ├── wp-config.php         # Autoload + config + wp-settings.php
│   ├── wp/                   # WordPress core (Composer-managed, .gitignored)
│   ├── app/
│   │   ├── mu-plugins/       # Must-use plugins (managed by Composer)
│   │   ├── plugins/          # Plugins (managed by Composer)
│   │   └── themes/
│   │       └── canopy/       # Main theme
│   │           ├── src/      # PHP classes (PSR-4: Canopy\)
│   │           ├── views/    # Twig templates
│   │           │   ├── layouts/
│   │           │   ├── partials/
│   │           │   ├── components/
│   │           │   └── templates/
│   │           ├── assets/
│   │           │   ├── styles/main.css
│   │           │   ├── scripts/site.js
│   │           │   ├── fonts/
│   │           │   └── images/
│   │           └── cache/    # Twig cache (generated)
├── .docker/                   # Docker config (Dockerfile, nginx, php.ini)
├── docker-compose.yml         # Local dev stack (nginx, php, mariadb, mailpit)
├── Makefile                   # Docker shortcuts (make setup, make up, make wp, etc.)
├── .env                       # Environment config (not versioned)
├── .env.example              # Environment template
├── composer.json
├── CLAUDE.md                 # AI context
└── .github/workflows/        # GitHub Actions
```

### Boot Flow

```
Browser → nginx → web/index.php
  → web/wp/wp-blog-header.php
    → web/wp-config.php
      → vendor/autoload.php (Composer)
      → config/application.php (.env, constants)
      → wp-settings.php (WordPress boot)
```

## Configuration

### Environments

Set `WP_ENV` in `.env`:

- **development** — Debug mode on, cache off, indexing disabled
- **staging** — Production cache, no indexing
- **production** — LiteSpeed cache, auto-purge, indexing enabled

### Timber

Timber converts WordPress objects into Twig-friendly objects. Access post data in templates:

```twig
<h1>{{ post.title }}</h1>
<p>{{ post.content }}</p>
<time>{{ post.date|date('F j, Y') }}</time>
```

See [Timber docs](https://timber.github.io/docs/v2/) for full reference.

### Custom Post Types

Add CPTs in `Site.php`:

```php
public function registerPostTypes(): void
{
    register_post_type('portfolio', [
        'labels' => ['name' => __('Portfolio', 'canopy')],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
```

### Customizer

Add theme customizer sections in `Site.php`:

```php
public function customizeRegister($customize): void
{
    $customize->add_section('hero', [
        'title' => __('Hero Section', 'canopy'),
    ]);
    // Add settings and controls...
}
```

## Deployment

Push to `main` triggers the GitHub Actions deploy. See
[`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) for the full guide — required secrets,
the auto-update workflow, production `.env` setup, and troubleshooting.

Quick manual deploy:

```bash
ssh user@host
cd /path/to/project
git pull origin main
composer install --no-dev --optimize-autoloader
wp rewrite flush
wp cache flush
wp eval 'Timber\Cache\Cleaner::clear_cache_timber();'
```

## Assets

No build step. `main.css` and `site.js` are committed and served as-is; the web
server / LiteSpeed Cache handles minification, compression, and browser caching
in production.

JavaScript is split into **islands** — small per-component modules under
`assets/scripts/islands/` that load on demand via native dynamic `import()`, only
when their markup (`data-island="…"`) is on the page and scrolled into view.
Animations use Motion ([motion.dev](https://motion.dev)), imported inside the
island that needs it, so it never loads on pages that don't.

## Standards

- **PHP** — 8.5+, PSR-12 (Laravel Pint), PSR-4 namespacing
- **CSS** — Token-first, BEM naming (`cnp-*` prefix), `@layer` for organization
- **JS** — ES module islands (lazy per-component), Motion for animations
- **HTML** — Semantic HTML5, ARIA where needed
- **Performance** — Lighthouse ≥ 95, LiteSpeed Cache
- **Accessibility** — WCAG 2.2 AA, `prefers-reduced-motion` respected
- **SEO** — dynamic `robots.txt`, `noindex` on search/404/paged; clean semantic
  markup ready for an SEO plugin (Schema.org, sitemaps, Open Graph) per project

## Troubleshooting

### PHP 8.5 Notes

- **Opcache** is compiled statically into PHP 8.5 core — do NOT include it in `docker-php-ext-install` or `docker-php-ext-enable`.
- **WP-CLI deprecation warnings** (e.g., `Creation of dynamic property`) are cosmetic and do not affect functionality. WP-CLI 2.12+ on PHP 8.5 will show these until upstream fixes land.

### Docker: "Class Env\Env not found"

The boot files must follow modern Bedrock format:
- `web/index.php` loads `wp-blog-header.php` (NOT `config/application.php` directly)
- `web/wp-config.php` loads autoload → application → wp-settings.php

If you see this error, verify `web/index.php` and `web/wp-config.php` match the files in this repo.

### WP-CLI: "Strange wp-config.php"

WP-CLI requires a literal `wp-settings.php` string in `wp-config.php`. The file must contain:
```php
require_once ABSPATH . 'wp-settings.php';
```

### CSS not updating in browser

The theme uses `filemtime()` for cache-busting. Hard refresh with Ctrl+Shift+R / Cmd+Shift+R.

## Resources

- [Bedrock Documentation](https://roots.io/bedrock/)
- [Timber Documentation](https://timber.github.io/docs/v2/)
- [Laravel Pint](https://laravel.com/docs/pint)
- [CLAUDE.md](./CLAUDE.md) — AI-friendly context

## License

MIT

## Support

Developed and maintained by **[Agência Upgrade](https://agenciaupgrade.com.br)**

- 🌐 Website: https://agenciaupgrade.com.br
- 📧 Email: contato@agenciaupgrade.com.br
- 🐙 GitHub: https://github.com/Agencia-Upgrade
