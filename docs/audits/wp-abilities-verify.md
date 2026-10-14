---
Last updated: 2026-10-14 11:00
---

# Canopy Abilities Verification — Static Mode

## Status: PASS (vacuous — nothing to verify)

No Abilities API surface exists in this worktree. Zero hits for
`wp_register_ability(` across the repo (PHP-wide grep), zero
`permission_callback` / `execute_callback` / `input_schema` hits in
`web/app/themes/canopy/`, and the `code-review-graph` knowledge graph
(full rebuild: 22 files, 44 nodes, 385 edges) returns only low-score
generic file nodes for ability queries — no ability symbols. Per the
`wp-abilities-verify` skill failure-modes guidance, this is reported as
a clear "no abilities registered" result, which is a vacuous PASS: there
are no annotations that could lie, no gates that could fail, and no
schemas that could mislead an agent.

Scope note: Canopy is a Bedrock-based site + classic Timber theme, not a
plugin. `Site::registerPostTypes()` and `Site::registerTaxonomies()`
(`web/app/themes/canopy/src/Site.php:68-74`) are empty stubs. There are
no REST routes, no controllers, and no `show_in_rest` exposures to serve
as an adjacent machine-readable surface.

## Audit doc validation

No audit document was provided. Sibling check: no `wp-abilities-*` file
exists in `docs/audits/` on this branch (expected — the sibling
`wp-abilities-audit` worker output is absent from this worktree, so
there is nothing to cross-check or validate). Lint 6
(`reference_ability` implies no required inputs) is skipped accordingly.

## Static inventory

Found 0 ability registrations across 0 files.

| Ability | Source file | Registration line | Callback file | Callback lines |
|---|---|---|---|---|
| — | — | — | — | — |

Method: `grep -rn 'wp_register_ability|wp_get_abilities|WP_Ability|register_ability'`
(repo root, no matches); `rg --multiline --pcre2` unnecessary with zero
single-line hits; theme-scoped grep for
`abilit|permission_callback|execute_callback|input_schema|show_in_rest`
(no matches). Graph `semantic_search_nodes_tool` for
`wp_register_ability abilities` and
`register_rest_route REST controller ability` post-build: no ability
nodes (FTS fallback, generic file hits only). Enumerated hooks in
`Site.php` (theme supports, menus, context, Twig filters/functions,
asset enqueue, head output, mail/SMTP) confirm no registration path
that could hide a dynamic or conditional `wp_register_ability` call.

### Limitations

- None material. Static enumeration is normally best-effort against
  variable-indirected names, dynamic annotations, and conditional
  registration — but with zero literal hits and no ability-adjacent
  infrastructure (no REST surface, no service/repository layer that
  callbacks could hide behind), there is no evidence of an obscured
  registration path. A runtime-mode rerun (`wp_get_abilities()` over
  wp-cli) would be redundant until a first `wp_register_ability(` lands.

## Annotation correctness

| Ability | Claim | Result | Evidence |
|---|---|---|---|
| — | — | N/A (no abilities) | — |

What this would check if a surface existed: for each ability, read the
`execute_callback` body end-to-end (not pattern-match alone) and compare
against the declared `meta.annotations`. `readonly: true` FAILs on any
durable write (`$wpdb->insert/update/delete`, options/post/user/term/
comment writes, filesystem, cron/Action Scheduler dispatches,
non-GET HTTP/REST delegates, `do_action()` whose listeners write).
`destructive: false` FAILs on delete/refund/void/cancel/trash verbs,
including domain-specific ones. `idempotent: true` FAILs on per-call
accumulating effects (counters, append-only logs, per-call cron
schedules). Legitimate read-shaped writes (read-through cache,
observability timestamps) pass only with inline
`// verify-ignore: <annotation> -- <reason>` suppressions. Static PASS
means "no obvious-shape violations," never "verified write-free" —
runtime twin-invocation belongs to a future runtime run.

## Permission gates

Permission gates (static inspection only — no abilities, runtime
roundtrip not applicable).

| Ability | Shape | Resolved cap(s) | Audit match |
|---|---|---|---|
| — | — | — | — |

What this would check if a surface existed: classify each
`permission_callback` (Shape A `current_user_can(...)` preferred;
FAIL on Shape B-bad `WP_REST_Request` patterns — the callback receives
validated `$input`, never a request object — and on Shape E literal
`true`; WARN on Shape C `__return_true` public and Shape F
`is_user_logged_in()`). Runtime roundtrip (anon denied, subscriber
denied, admin allowed unless deliberately public) and audit
`capability_gate` cross-check would apply once abilities exist.

## Schema lints

| Ability | Lint | Result | Detail |
|---|---|---|---|
| — | — | N/A (no abilities) | — |

What this would check if a surface existed: the six static lints per
ability — object schemas declare `additionalProperties`; every required
field has a non-empty description (FAIL if missing); enums non-empty
(FAIL on `[]`); no `$ref` (FAIL); defaults statically constant
(scalars, literal arrays, `(object) array()`, `new stdClass()`; FAIL on
computed expressions like `gmdate()`/`time()`); `reference_ability`
implies no required inputs (needs an audit doc).

## Error-code vocabulary

No callbacks to inspect; no `WP_Error` codes to check against the
Abilities API vocabulary. Non-vocabulary codes in future callbacks
should be WARNed.

## Fixes applied

None. No findings, no trivial safe fixes in scope. No code touched.
