# Blueprint — Canopy

Audit of WordPress Playground Blueprint fit for the Canopy boilerplate
(Bedrock + Timber 2 + Twig 3). Branch: `audit/v2-blueprint`.

## Scope

- Assess whether a declarative `blueprint.json` can set up this boilerplate.
- Check schema keys, steps, and resources against `.agents/skills/blueprint/SKILL.md`.
- Commit a minimal `blueprint.json` only if warranted; otherwise doc-only.
- Only trivial safe additions. No refactors, no theme behavior changes.

Out of scope: Playground runtime testing (no network/CLI here), detector fixes,
theme fixes. Those belong to their own workers.

## Method

1. Read `.agents/skills/blueprint/SKILL.md` (schema keys, steps, resources,
   bundles, common mistakes).
2. Verified in source: `composer.json`, `config/application.php`,
   `.env.example`, `Makefile:32-58` (`setup`), `wp-cli.yml`,
   `web/app/themes/canopy/` (`functions.php`, `style.css`, `src/Site.php`).
3. Checked repo for existing blueprints: none (`**/blueprint*` no hits).

## Assessment

### Full-environment blueprint: not possible

The local stack is imperative, not declarative:

- `make setup` runs `composer install`, generates salts, boots Docker
  (nginx/php/MariaDB/Redis/Mailpit), then `wp core install`, `theme activate
  canopy`, option updates, rewrite structure (`Makefile:32-58`).
- Bedrock remaps the content dir to `web/app` (`config/application.php:81-83`)
  and loads core from Composer-managed `web/wp` (gitignored, do not edit).
  Playground uses a fixed `/wordpress` layout with standard `wp-content`;
  no Blueprint step remaps the content dir or loads the Bedrock autoloader.
- Blueprint JSON is trusted declarations only: file steps, `runPHP`,
  `wp-cli`, `runSql`, config steps. There is no Composer step, no shell,
  no Docker. `composer.json` (`roots/wordpress ^7.0`, `timber/timber ^2.1`,
  Bedrock autoloader) cannot be executed by any documented step.
- `.env`-driven config (`WP_HOME`, `WP_SITEURL`, salts, `DATABASE_URL` vs
  `DB_*`) has no Blueprint equivalent; `defineWpConfigConsts` / `constants`
  cover single constants, not the Bedrock bootstrap.

### Theme-only preview blueprint: fragile, not warranted

A preview that installs just the theme would need, at minimum:

- `installTheme` with `themeData` as `git:directory` scoped to
  `path: "/web/app/themes/canopy"`, or a `bundled` zip of the theme dir.
- Timber resolved outside Composer. The theme requires Timber classes at
  boot (`functions.php:14`: `Timber::init()`; `src/Site.php:15` extends
  `TimberSite`). The wp.org Timber plugin slug was not verified in this
  environment, and inventing it in a committed file would violate the
  no-guess rule.
- A PSR-4 shim for `Canopy\` → theme `src/`. That mapping lives in the
  Bedrock root `composer.json:86-90`, not in the theme, so standard WP
  would fatal on `new Canopy\Site()` without an mu-plugin autoloader
  (`writeFile` to `/wordpress/wp-content/mu-plugins/`). Fragile and
  outside trivial-safe scope.
- `DISALLOW_FILE_MODS=true` (`config/application.php:143`) signals
  production intent; irrelevant to Playground but a reminder this repo
  is not designed for admin-installed themes.

### Schema notes (per skill, for any future attempt)

- Top-level: only documented keys. `$schema` must be
  `https://playground.wordpress.net/blueprint-schema.json` (`.net`, not `.org`).
- `preferredVersions` requires both `php` and `wp` when present.
  `php: "8.5"` is schema-legal (major.minor); `wp: "latest"` tracks 7.x.
  Patch versions (`"8.5.1"`) are invalid.
- `features` allows only `networking` / `intl`. Debug mode goes in
  `constants: { "WP_DEBUG": true }`, never `features.debug`.
- Steps: `installPlugin` / `installTheme` use `pluginData` / `themeData`
  (never `pluginZipFile` / `themeZipFile`); step name is `wp-cli` (not `cli`);
  `runPHP` using WP functions must `require '/wordpress/wp-load.php'`;
  `writeFiles.filesTree` must be a `literal:directory` or `git:directory`
  resource, never a flat object; `git:directory` branch/tag `ref` requires
  `refType`.
- Shorthands (`login`, `plugins`, `siteOptions`, `constants`) expand in
  unspecified order; use explicit `steps` when order matters.

## Recommendation

Doc-only. No `blueprint.json` committed. Rationale: a full-env blueprint
is impossible (Composer/Docker/`.env`/content-dir remap have no Blueprint
equivalent), and a theme-only preview needs an unverified Timber source
plus a fragile autoloader shim — worse than no file.

If a preview is ever wanted, sketch (not committed, needs verification of
the Timber source and a Playground run before shipping):

```json
{
  "$schema": "https://playground.wordpress.net/blueprint-schema.json",
  "landingPage": "/wp-admin/",
  "preferredVersions": { "php": "8.5", "wp": "latest" },
  "steps": [
    { "step": "installTheme", "themeData": "UNVERIFIED — Timber source first" },
    { "step": "activateTheme", "themeFolderName": "canopy" },
    { "step": "login" }
  ]
}
```

Do not fill in the Timber step with a guessed wp.org slug. Verify the
plugin source, add the `Canopy\` autoloader mu-plugin shim, and test via
`npx @wp-playground/cli run-blueprint` before committing anything.

## Verification

- No `blueprint.json` or bundle exists in repo (`**/blueprint*` no hits);
  nothing to validate, nothing changed outside `docs/audits/blueprint.md`.
- Every structural claim above cites its source file and line.
- No PHP/JSON added, so `composer lint` / `composer phpstan` are unaffected
  (`vendor/` absent, Docker unavailable here; CI covers the next theme change).
