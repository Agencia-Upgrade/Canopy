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
- **Redis Cache** — High-performance object caching
- **Zero Build Step** — CSS and JS committed directly; optional minification
- **LiteSpeed Cache** — File-first cache configuration for production
- **GitHub Actions** — Automated deployment and weekly dependency updates
- **Cloudflare-Ready** — DNS, CDN, and image transformation integration
- **Accessibility & Performance** — WCAG 2.2 AA, Lighthouse ≥ 95, Schema.org

## Quick Start

### Prerequisites

- PHP 8.5+
- Composer 2.x
- Docker (for local development) or web hosting with Node.js (optional)
- MySQL 8.0+ or MariaDB 11+

### Installation

1. Clone and navigate:
   ```bash
   git clone https://github.com/Agencia-Upgrade/canopy.git my-site
   cd my-site
   ```

2. Copy environment file:
   ```bash
   cp .env.example .env
   ```

3. Edit `.env` with your values:
   ```
   DB_NAME=my_database
   DB_USER=root
   DB_PASSWORD=password
   WP_HOME=http://localhost
   WP_ENV=development
   ```

4. Install dependencies:
   ```bash
   composer install
   ```

5. Initialize WordPress:
   ```bash
   # If using WP-CLI
   wp core install --url=http://localhost --title="My Site" --admin_user=admin --admin_email=admin@example.com --admin_password=password --allow-root

   # Or manually via http://localhost/wp/wp-admin/install.php
   ```

6. Activate the theme:
   ```bash
   wp theme activate canopy --allow-root
   ```

### Local Development with Docker

```bash
# Start containers
make up

# Access WordPress
# Frontend: http://localhost
# Admin: http://localhost/wp/wp-admin

# Run commands inside the container
make composer install
make wp plugin list
make bash  # Full shell access

# Stop containers
make down
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
│   ├── wp/                   # WordPress core (Composer-managed)
│   └── index.php             # Web root entry
├── .env                       # Environment config (not versioned)
├── .env.example              # Environment template
├── composer.json
├── CLAUDE.md                 # AI context
├── .claude/                  # Domain-specific guides
└── .github/workflows/        # GitHub Actions
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

### GitHub Actions

Configure these secrets in your repository:

- `PROD_HOST` — Production server hostname
- `PROD_USER` — SSH user
- `PROD_SSH_KEY` — SSH private key
- `PROD_PATH` — Project path on server (e.g., `/home/user/public_html`)
- `CF_ZONE_ID` — Cloudflare Zone ID (for cache purge)
- `CF_EMAIL` — Cloudflare account email
- `CF_API_KEY` — Cloudflare API key

### Manual Deployment

```bash
# SSH to server
ssh user@host

# Navigate to project
cd /path/to/project

# Pull code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Flush caches
wp rewrite flush
wp cache flush
wp eval 'Timber\Cache\Cleaner::clear_cache_timber();'
```

## Asset Minification

The theme automatically loads minified assets in production:
- Development: `main.css` / `site.js`
- Production: `main.min.css` / `site.min.js`

To minify, use any tool (e.g., css-minify, uglify-js):

```bash
# Example with csso-cli
npm install -g csso-cli
csso assets/styles/main.css -o assets/styles/main.min.css
```

Or commit pre-minified files to Git.

## Standards

- **PHP** — 8.5+, PSR-12 (Laravel Pint), PSR-4 namespacing
- **CSS** — Token-first, BEM naming (`cnp-*` prefix), `@layer` for organization
- **JS** — Vanilla JS, GSAP for animations, defer/async loading
- **HTML** — Semantic HTML5, ARIA where needed
- **Performance** — Lighthouse ≥ 95, LiteSpeed Cache, Redis
- **Accessibility** — WCAG 2.2 AA, `prefers-reduced-motion` respected
- **SEO** — Schema.org markup, XML sitemap, robots.txt, Open Graph tags

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
