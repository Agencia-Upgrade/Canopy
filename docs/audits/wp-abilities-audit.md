---
Last updated: 2026-10-14 12:00
---

# WP Abilities Audit — Canopy

Audit of the Canopy theme's REST surface for Abilities API readiness
(Bedrock + Timber 2 + Twig 3 classic theme). Branch:
`audit/v2-wp-abilities-audit`.

## Scope

- Enumerate theme REST controllers (`register_rest_route`, REST base
  classes, `WP_REST_Request` handlers).
- Sweep adjacent exposure points: `register_rest_field`, `register_meta`
  with `show_in_rest`, CPT/taxonomy `show_in_rest`, `rest_api_init`,
  `admin-ajax`/`wp_ajax_*`, `wp_send_json_*`, `wp_register_ability`.
- Propose `wp_register_ability` registrations ONLY if a real surface
  exists. Otherwise record the empty surface and what would trigger
  registrations. No code changes beyond this doc.

## Method

1. Read `.agents/skills/wp-abilities-audit/SKILL.md` plus references
   (`audit-schema.md`, `controller-enumeration.md`,
   `capability-gate-tracing.md`), the `canopy` skill (`SKILL.md`), and
   `AGENTS.md`.
2. Queried `code-review-graph` first (`semantic_search_nodes_tool` for
   `register_rest_route rest_controller` and `WP_REST_Request
   permission_callback`). Graph is empty/unindexed in this worktree, so
   both 0-result responses prove nothing; fell back to deterministic
   source checks per `controller-enumeration.md` (grep path).
3. Verified in source with `grep` over `web/app/themes/canopy/`, plus
   full reads of `src/Site.php` and `functions.php`.

## Result: no REST surface — audit N/A per skill failure mode

The skill's failure-mode clause applies verbatim: "Plugin has no REST
controllers — audit doesn't apply." Canopy is a theme (not a plugin)
and registers zero routes, zero fields, zero meta, zero AJAX handlers,
zero abilities:

- `register_rest_route(` — zero hits in theme scope.
- `register_rest_field`, `register_meta`, `show_in_rest`,
  `rest_api_init`, `WP_REST_` (class or request), `wp_register_ability`,
  `abilities` — zero hits in theme scope.
- `register_post_type` / `register_taxonomy` — only the empty stubs
  `Site::registerPostTypes()` (`src/Site.php:68-70`) and
  `Site::registerTaxonomies()` (`src/Site.php:72-74`). No CPT exists to
  expose via `show_in_rest`, so Mechanism B (post-type-backed caps)
  never engages.
- `admin-ajax` / `wp_ajax_*` / `wp_send_json_*` — zero hits. No
  AJAX-transport plumbing to inventory.
- Only REST-adjacent line in the theme is a removal, not an exposure:
  `remove_action('wp_head', 'rest_output_link_wp_head')`
  (`src/Site.php:133`) inside `cleanFrontend()`.
- `functions.php` is boot only (`Timber::init()` + `new Site()`). All
  hierarchy PHP files are thin `Timber::context()` +
  `Timber::render()` wrappers. No request handlers anywhere.

```yaml
plugin: canopy-theme  # theme, not a plugin; no slug to register under
repo: Canopy  # starter theme worktree; no Owner/Repository remote for abilities scope
branch_audited: audit/v2-wp-abilities-audit
audited_at: 2026-10-14
auditor: opencode audit worker (canopy-audit worktree)
baseline_abilities: 0
capability_gate: null  # no controllers, no gate to trace
proposed_abilities: []  # intentionally empty: zero backing endpoints
excluded_from_mvp: []
surfaced_gaps: []
```

The empty `proposed_abilities` is a finding, not missing data. The
schema's "at least one ability" verification rule cannot be satisfied
without fabricating a backing endpoint, so this audit deliberately
fails that check and records why instead.

## Controller Inventory

| Class | File | REST Base | Routes |
|---|---|---|---|
| *(none)* | *(none)* | *(none)* | *(none)* |

Enumeration paths per `controller-enumeration.md`: glob path N/A (no
`includes/` controller layout — theme has `src/Site.php` only); grep
path (`register_rest_route(` over every theme PHP file) returned zero
hits. No inherited routes: nothing extends `WP_REST_Controller`,
`WP_REST_Posts_Controller`, or any parent-plugin REST base.

## Notes and Surprises

### Nothing to group, nothing to gate

With zero controllers there is no semantic-intent grouping to apply
(`grouping-heuristic.md` never engages) and no `permission_callback`
to trace (`capability-gate-tracing.md` Mechanisms A and B both N/A).
No `__return_true` public endpoints, no compound `{read, write}` gates,
no post-type cap shadows.

### What would trigger registrations

Any of the following creates a real surface and re-opens this audit:

1. A `register_rest_route(` call in theme code (e.g. a headless /
   island-driven JSON endpoint) — each route needs inventory +
   `current_user_can()` trace + intent-grouped ability proposal.
2. A CPT/taxonomy registration with `show_in_rest: true` (the
   `registerPostTypes` / `registerTaxonomies` stubs are the obvious
   future site) — core's post-type controllers then expose the type,
   and the audit must record the `{read, write}` cap map.
3. `register_rest_field` / `register_meta` with `show_in_rest` —
   shape/permission review, ability only if it answers a real
   user/agent question.
4. `wp_ajax_*` handlers or `wp_send_json_*` transport — inventory as
   plumbing; promote to abilities only on passing the use-case-contract
   test (real workflow vs cache/scheduler/bookkeeping).
5. A deliberate `wp_register_ability` adoption decision — hooks/filter
   or service-backed abilities are out of scope for this skill's
   current version, which covers REST-backed abilities only.

### No fixes applied

No trivial safe fixes exist for an empty surface. Theme code untouched;
this doc is the only artifact.
