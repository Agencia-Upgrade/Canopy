# Known issues

Tracked here so they stay out of the agent skill. Rechecked against the v2
theme (`style.css` 2.0.0). Fixed items are gone.

## Fixed (do not re-open)

- `filemtime()` in `enqueueAssets()` — guarded by `file_exists()` with a
  `null` fallback.
- `html lang` — `base.twig` now uses `{{ function('language_attributes') }}`.
- Listing classes — `.cnp-archive` / `.cnp-search` are gone; use `.cnp-feed`
  / `.cnp-teaser`.
- `html { font-size: 62.5% }` is gone; type is `clamp()` on a 16px rem root.
- `CLAUDE.md` no longer describes shipped buttons/cards.
- Sitemap — `robots.txt` adds the `Sitemap:` line only when
  `wp_sitemaps_enabled` is true.
- Front page — “Your latest posts” renders `index.twig` (feed + pagination);
  a static front page keeps the hero template.
- SMTP example — `.env.example` ships Mailpit dev values; production values
  (`587` / `tls`) match the code defaults. `SMTP_ENCRYPTION=none` is handled.
- Local mail — php image installs msmtp; `sendmail_path` relays to
  `mailpit:1025`.
- `docs/TIMBER.md` — pagination, related posts, `addToContext()` and
  `post.content|raw` snippets match the theme.
