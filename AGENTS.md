# AGENTS.md — Canopy

WordPress 7 boilerplate: Bedrock + Timber 2 + Twig 3 + PHP 8.5. Theme:
`web/app/themes/canopy`. Developed by [Agência Upgrade](https://agenciaupgrade.com.br).

## Skill

Theme, Timber, Twig, PHP templates, islands, and `cnp-` CSS:

**Read `.agents/skills/canopy/SKILL.md` first.** Then open only the reference
listed in its load index. Claude Code also sees that skill at
`.claude/skills/canopy` (symlink).

Human docs: `docs/` (`TIMBER.md`, `CSS-PATTERNS.md`, `JAVASCRIPT.md`,
`DEPLOYMENT.md`). Known issues: `docs/KNOWN-ISSUES.md`. When a doc snippet
disagrees with the theme, follow the theme.

This skill is self-contained and versioned in git (`.agents/skills/canopy`).
Do not require a skill that is not in this repository.

## Do not break

- `web/index.php` only loads `web/wp/wp-blog-header.php`.
- `web/wp-config.php`: `WP_CACHE` guard → autoload → `config/application.php`
  → literal `wp-settings.php`.
- Do not edit `web/wp/` or `vendor/`.
- PSR-4: `Canopy\` → `web/app/themes/canopy/src/`.
- Theme setup lives in `src/Site.php`. PHP templates only call
  `Timber::context()` and `Timber::render()`.
- `style.css` is metadata. CSS is `assets/styles/main.css`. JS is
  `assets/scripts/site.js` plus islands. No build step.

## Commands

```bash
make setup          # first time (Docker): .env, salts, WP, theme
make up / make down
make composer install
composer lint
composer lint:fix
composer phpstan
```

WP-CLI with flags: `docker compose exec php wp --allow-root …` or
`make wp <cmd> ARGS="…"`. Default admin after `make setup`: `admin` / `admin`
at `http://localhost:8080/wp/wp-admin`.

## Conventions

- PHP 8.5, PSR-12, 4 spaces, Laravel Pint. Hook names snake_case.
- Twig/JS/CSS: tabs (match existing files).
- Text domain: `canopy`.
- CSS: follow `.agents/skills/canopy/references/css-js.md` as the rule
  (`@layer`, `@scope`, `oklch`, `light-dark`, logical properties, `clamp` +
  `cqi`, `@container`, motion via `prefers-reduced-motion: no-preference`).
  BEM `cnp-`. One file, no build.
- JS: vanilla islands, `data-island`, Motion inside the island, no GSAP,
  no jQuery, no build.
- No new code comments unless asked.

## Quality

PHPStan level 5 covers theme `src/` and the hierarchy PHP files only
(`phpstan.neon`). Deploy: see `docs/DEPLOYMENT.md`. After deploy, flush
rewrites, object cache, and `Timber\Cache\Cleaner::clear_cache_timber()`.
