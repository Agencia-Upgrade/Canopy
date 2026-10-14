# WP Plugin Directory Guidelines — Canopy

Audit of the Canopy boilerplate against the 18 WordPress.org Plugin
Directory guidelines, applied by analogy. Branch:
`audit/v2-wp-plugin-directory-guidelines`.

Canopy is a Bedrock + Timber 2 theme boilerplate, not a plugin
submission. Nothing here certifies .org approval. Guidelines tied to
directory mechanics (SVN, `readme.txt`, stable tags) are N/A and
marked as such. Licensing, naming/trademark, and security-posture
guidelines transfer directly.

## Scope

- GPL compliance of theme headers, `LICENSE`, `composer.json`, and
  bundled third-party code (Guideline 1).
- Naming/trademark screen by analogy (Guideline 17).
- Upsell/trialware, tracking, remote-code, credit-link, and admin-nag
  posture (Guidelines 5, 7, 8, 10, 11).
- Explicit N/A for directory-mechanics guidelines (3, 12, 14, 15).
- Only trivial safe fixes (headers, license text). No relicensing.

Out of scope: vendoring Motion locally, behavior changes, Plugin Check
runtime. Those are deferred below.

## Method

1. Read `.agents/skills/wp-plugin-directory-guidelines/SKILL.md` plus
   `references/guideline-review-checklist.md`, `gpl-compliance.md`,
   `naming-rules.md`, and `plugin-check.md`. Read the `canopy` skill
   and `AGENTS.md` for repo rules.
2. Built the `code-review-graph` knowledge graph first (full build:
   22 files, 44 nodes, 385 edges, 2 communities: `src-register` PHP
   and `scripts-show` JS), then verified every claim in source. Twig
   views are not parsed by the graph; checked with `grep`.
3. Verified manually: `LICENSE`, `composer.json` (+lock identifiers),
   `web/app/themes/canopy/style.css`, `functions.php`, `src/Site.php`,
   `assets/scripts/site.js`, `assets/scripts/islands/reveal.js`,
   `views/partials/head.twig`, `README.md`. Greps for enqueues, CDN
   hosts, `wp_remote_*`, `eval`/`base64`, license gates, credit links,
   admin notices, iframes, and `.min.js`/jQuery copies.
4. Plugin Check (PCP) **not run**: Canopy is not a plugin and no local
   WP runtime was provisioned in this worker. Not run is not a pass.

## Findings

| # | Guideline | Verdict | Evidence | Action |
|---|---|---|---|---|
| 1 | G1 GPL license | Pass (by analogy) | `style.css:11-12` declares `License: MIT` + `License URI: https://opensource.org/licenses/MIT`; root `LICENSE` is MIT text; `composer.json:4` `"license": "MIT"`. MIT matches the Plugin Check accepted-identifier regex (`MIT` listed in `gpl-compliance.md`). No split licensing, no restrictive clauses, no `All Rights Reserved`. Deps are GPL-compatible: `timber/timber` (GPL-2.0-or-later), `roots/wordpress` (GPL), `vlucas/phpdotenv` (BSD-3-Clause), `oscarotero/env` (MIT). | None. No relicensing. |
| 2 | G4 human-readable | Pass | No obfuscated PHP (`eval`, `base64_decode`, ionCube: none found). No `.min.js`/`.min.css` in theme; `main.css`, `site.js`, `reveal.js` ship as readable source. | None. |
| 3 | G8 remote executable (by analogy) | Flag / defer | `assets/scripts/islands/reveal.js:5` imports Motion from `https://cdn.jsdelivr.net/npm/motion@12.40.0/+esm`; `views/partials/head.twig:4` preconnects to `cdn.jsdelivr.net`. Under strict .org rules (G8) executable JS must be bundled locally; a CDN import would fail review. In this boilerplate it is intentional, version-pinned, island-scoped, and documented — but the analogy holds. | Deferred. Vendoring Motion locally is a behavior/build decision outside trivial-fix scope. No change. |
| 4 | G13 bundled libs | Pass | Enqueues use local paths + core handles only (`Site.php:85-105`: `canopy-main`, `canopy-site`, `wp-block-library`). No bundled jQuery/PHPMailer/SimplePie copies. SMTP uses core `PHPMailer` via `phpmailer_init` (`Site.php:484-504`); mail uses `wp_mail` filters. | None. |
| 5 | G7 tracking/consent | Pass | No `wp_remote_get/post`, no activation beacon, no analytics/tracking scripts, no third-party pixels in theme. External contact is SMTP relay only when `SMTP_HOST` env is set. | None. |
| 6 | G5 trialware | N/A — pass posture | No `has_paid_access`/`is_licensed`/license-key gates, no quotas, no expiry, no locked UI (grep clean). No upsell notices at all. | None. |
| 7 | G6 SaaS | N/A | No external service integration; nothing to document in a readme. | None. |
| 8 | G10 forced links | Pass | No `powered by` / credit-link output in theme (grep clean). Footer is menu-driven via `footer_navigation`. | None. |
| 9 | G11 admin hijack | Pass | Theme registers no admin pages, notices, iframes, or dashboard overlays. Only `customize_register` stub and frontend hooks. | None. |
| 10 | G9 honesty | Pass | No keyword stuffing, fake compliance claims, or visitor-resource abuse found. | None. |
| 11 | G17 naming/trademark (by analogy) | Pass | Theme name `Canopy` (generic, 6 chars), slug/directory `canopy` (lowercase, 6 chars, ≤ 50), text domain `canopy` matches. No leading trademark (`woocommerce-`, `elementor-`, …), no `-press`/`woo-` portmanteau, no banned terms (Meta brands, `wordpress`/`wp` redundant, Trustpilot). | None. |
| 12 | G2 responsibility | Pass | No restored review-team removals; no unlicensed bundled assets (only `logo.svg` + local CSS/JS, first-party). Fonts: none bundled, none remote. | None. |
| 13 | G3/G14/G15 SVN + versions | N/A | No SVN, no `readme.txt`, no stable tag. `style.css:7` `Version: 2.0.0` has no directory counterpart to match. Git release flow only. | None. |
| 14 | G12 readme spam | N/A | No `readme.txt`. `README.md` has no affiliate links, no tag list, no competitor keyword stuffing. | None. |
| 15 | G16 completeness | Pass (by analogy) | Complete working starter (templates, `Site.php`, assets, Docker, deploy docs), not a skeleton or slug reservation. | None. |
| 16 | G18 directory rights | Informational | No action; noted for submission strategy only. | None. |
| 17 | PCP automated validation | Not run | See Method §4. Manual review above stands in. Follow-up when a WP test site exists: `wp plugin install plugin-check --activate`, then `wp plugin check <slug>` — N/A target here since Canopy ships no plugin. | Deferred. |

## Fixes applied

None. Headers and license text already correct; the single flag
(G8/CDN) requires a product decision, not a trivial edit.

## Deferred / follow-ups

- Vendor Motion locally (or keep CDN deliberately) — needs its own
  worker: download pin, SRI/integrity, island fallback, docs update.
- Any future `.org` theme submission would need a fresh full-guideline
  pass plus Theme Check, not this by-analogy audit.
