# Audit: wp-rest-api (v2)

## Scope

REST surface of Canopy theme (`web/app/themes/canopy`): custom routes, controllers, `show_in_rest` exposure, rest fields/meta, auth. Per task brief: confirm zero-route state, state what applies vs N/A for future work. No new endpoints added.

## Method

- Read `.agents/skills/wp-rest-api/SKILL.md` (+ refs index) and canopy skill + `AGENTS.md`.
- `code-review-graph`: `get_architecture_overview_tool` → 0 communities, 0 edges. No REST nodes to trace.
- Grep whole repository for `register_rest_route|WP_REST_Controller|rest_api_init|show_in_rest|rest_base|rest_controller_class|register_rest_field|register_meta|WP_REST_Request|WP_REST_Response|permission_callback|rest_ensure_response` → only hits are doc/skill examples (`README.md:234`, `.agents/skills/canopy/references/php.md:68`, prior audit notes). No live code.
- Grep `web/app/themes/canopy` for same set → zero matches.
- Grep `web/app` for `register_post_type|register_taxonomy|register_rest` → zero matches.
- Read `web/app/themes/canopy/src/Site.php:68-74`: `registerPostTypes()` and `registerTaxonomies()` are empty stubs. Nothing to expose via `show_in_rest`.

## Findings

| # | Location | Severity | Status | Note |
|---|----------|----------|--------|------|
| 1 | `web/app/themes/canopy/src/Site.php:68-74` | info | taken (confirmed) | CPT/tax stubs empty. No `show_in_rest` decision owed yet. When a CPT lands, default to `show_in_rest => true` + `rest_base` per `custom-content-types.md`, unless intentionally private. |
| 2 | (no route code) | N/A | deferred by design | `routes-and-endpoints.md` discipline (unique `vendor/v1` namespace, `permission_callback` always, `WP_REST_Server::*` methods, `rest_ensure_response`/`WP_Error` with status) applies to future endpoints only. No violation possible now. |
| 3 | (no route code) | N/A | deferred by design | `schema.md` discipline (typed `args`, JSON Schema validation via `rest_validate_value_from_schema`, no `$_GET`/`$_POST` reads) applies to future endpoints only. |
| 4 | (no route code) | N/A | deferred by design | `responses-and-fields.md` discipline (never strip core fields, `register_rest_field` for computed, `show_in_rest.schema` for object/array meta, `add_link()`) applies to future endpoints only. |
| 5 | (no route code) | N/A | deferred by design | `authentication.md` discipline (cookie+nonce for wp-admin JS, app passwords/external auth otherwise, capability checks in `permission_callback`) applies to future endpoints only. |
| 6 | (no route code) | N/A | deferred by design | `discovery-and-params.md` behavior (`_fields`, `_embed`, pagination caps) is core-provided; theme adds nothing. Revisit if theme ships JS that consumes `/wp-json`. |

No live REST defects found. No code changes made (nothing trivial-and-safe to fix; adding routes explicitly out of scope).

## Verification

- Greps above are the evidence; re-run to confirm.
- `composer lint` / `composer phpstan` not run: no PHP touched.
