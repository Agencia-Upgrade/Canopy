# WP Abilities API — Canopy

Audit of Abilities API relevance for the Canopy boilerplate
(Bedrock + Timber 2 + Twig 3 classic theme). Branch: `audit/v2-wp-abilities-api`.

## Scope

- Decide whether the Abilities API (`wp_register_ability`,
  `wp_register_ability_category`, `wp-abilities/v1`, `@wordpress/abilities`)
  applies to this theme.
- State capability discipline as applied to theme scope (caps in theme scope,
  permission posture) vs N/A.
- Do NOT register abilities. No new surface in this starter.

Out of scope: REST design, CPT permission mapping, block/command-palette
exposure. Those belong to a future plugin or project brief, not this theme.

## Method

1. Built the `code-review-graph` knowledge graph first (full build on this
   branch: 22 files, 44 nodes, 385 edges), then verified every claim in
   source. Semantic search for `ability register capability permission` and
   `register_post_type REST route Site` returned only `Site.php` lifecycle
   methods (`registerPostTypes`, `registerTaxonomies`, `customizeRegister`) —
   no ability nodes.
2. Read `wp-abilities-api/SKILL.md` + references (`domain-vs-projection.md`,
   `php-registration.md`) and the canopy skill + `AGENTS.md`.
3. Grepped the repo for `wp_register_ability|wp_register_ability_category|
   wp_abilities_api|wp-abilities/v1|@wordpress/abilities|permission_callback|
   current_user_can|map_meta_cap|register_rest_route|show_in_rest`.
4. Read `src/Site.php` (full), `functions.php`, and theme-scoped grep hits.

## Verdict

Abilities API is **N/A by design**. Canopy has no abilities surface: zero
registrations, zero categories, zero REST exposure, zero JS consumption.
There is nothing to project and nothing to register. Per
`domain-vs-projection.md`, the inclusion test fails — the theme exposes no
human-meaningful executable operations beyond core UI, so there are no
use-case contracts to formalize. Do not introduce abilities to this starter.

## Findings

| # | Location | Severity | Issue | Action |
|---|---|---|---|---|
| 1 | Theme-wide (grep: zero `wp_register_ability`, `wp_register_ability_category`, `wp_abilities_api_init`, `wp-abilities/v1`, `@wordpress/abilities`, `permission_callback`) | Info | No Abilities API surface exists. | Taken: no change. Documented as N/A; do not register abilities in this theme. |
| 2 | `web/app/themes/canopy/src/Site.php:68-74` | Info | `registerPostTypes()` / `registerTaxonomies()` are empty stubs. No CPT means no capability-type or `map_meta_cap` mapping decision exists yet. | Taken: no change. When a project adds a CPT, map caps then; abilities remain a separate opt-in decision. |
| 3 | `web/app/themes/canopy/src/Site.php:161-163` | Info | `customizeRegister()` is an empty stub. Customizer capability handling stays with core; theme adds no settings, no writes, no custom caps. | Taken: no change. |
| 4 | `web/app/themes/canopy/src/Site.php:416-427` | Info | Twig functions expose read-only `get_theme_mod` and gettext (`__`, `_n`, …). No executor, no input schema, no permission boundary crossed — nothing ability-shaped. | Taken: no change. |
| 5 | `README.md:234`, `.agents/skills/canopy/references/php.md:68` | Info | Only `show_in_rest` hits in repo are doc/skill CPT examples, not shipped code. No REST route, no `register_rest_route`, no `current_user_can` in theme PHP. | Taken: no change. Docs-only; not a surface. |
| 6 | Capability discipline (theme scope) | Info | Permission posture: theme performs no authenticated writes, no AJAX/`admin-post` handlers, no options updates, no user-input execution paths. All output paths are escaped at render (`esc_attr` in `Site.php`, `\|e` in Twig per canopy skill). Capability checks are therefore correctly absent — there is no privileged operation to gate. | Taken: no change. Rule for future work: any executor added later (AJAX, form handler, custom endpoint) must add `current_user_can` / `permission_callback` at that layer; Abilities API registration stays opt-in per project brief, never in the starter. |

## Verification

- `code-review-graph` full build: 22 files, 44 nodes, 385 edges; semantic
  search returned no ability/category/permission nodes.
- Repo-wide grep for ability/permission/REST tokens: only doc-example
  `show_in_rest` hits (finding 5) and the `customize_register` stub hook.
- Theme-scoped grep for `current_user_can|manage_options|edit_theme_options|
  admin-ajax|wp_ajax`: zero matches beyond the stub.
- No code touched: `composer lint` / `composer phpstan` not required.
- No push/merge performed; commit on `audit/v2-wp-abilities-api` only.
