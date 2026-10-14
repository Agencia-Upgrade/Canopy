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

Skills are versioned in git (`.agents/skills/canopy`, `modern-css`,
`modern-web-guidance`). Do not require a skill that is not in this repository.

## CSS rule: modern-css (required)

Any CSS task (write, refactor, review, new component, base styles, tokens)
must follow the `modern-css` skill. It is a rule, not a suggestion.

Skills (versioned in git, symlinked into `.claude/skills/`):

- `.agents/skills/modern-css/SKILL.md`: opinionated rules (`@layer`,
  `@scope`, nesting with `&`, range queries, logical properties, two-value
  `display`, `cqi` spacing, `clamp()` with a `rem` term, `text-wrap`,
  `oklch()`, `light-dark()`, relative colors, `prefers-reduced-motion:
  no-preference`).
- `.agents/skills/modern-web-guidance/SKILL.md`: required dependency of
  `modern-css`. Search and retrieve guides with
  `npx -y modern-web-guidance@latest search "<query>"` and `retrieve "<id>"`.

Order for a CSS task:

1. Read `.agents/skills/canopy/references/css-js.md` (Canopy tokens, layers,
   BEM `cnp-`, scope order).
2. Read `.agents/skills/modern-css/SKILL.md` and apply every rule that fits.
3. Retrieve `modern-web-guidance` guides when needed. Skip their fallbacks:
   Baseline Newly Available features are used natively.
4. Run `npm run lint:css`. Fix every error before finishing.

Precedence: `css-js.md` wins for Canopy specifics (single file, tokens, BEM
`cnp-` names, layer order, `@scope` limits only on user HTML). For anything
else, `modern-css` wins over `modern-web-guidance`.

Lint dependency (Node, dev only; still no build step):

```bash
npm install            # stylelint + stylelint-config-modern
npm run lint:css       # check assets/styles/**/*.css
npm run lint:css:fix   # auto-fix what Stylelint can
```

Config: `.stylelintrc.json` extends `stylelint-config-modern`. Do not
disable rules or add `stylelint-disable` comments to pass the lint. CSS that
fails `npm run lint:css` is not done.

Restore or update the skills:

```bash
npx skills add moderncss/skills -y -a claude-code
npx skills add GoogleChrome/modern-web-guidance -s modern-web-guidance -y -a claude-code
```

The CLI copies into `.claude/skills/`. Move the folder to `.agents/skills/`
and symlink it back (`../../.agents/skills/<name>`), as the other skills do.

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
npm install         # first time: CSS lint deps
npm run lint:css
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
  `cqi`, `@container`, motion via `prefers-reduced-motion: no-preference`)
  plus the `modern-css` skill (see "CSS rule: modern-css"). BEM `cnp-`. One
  file, no build. `npm run lint:css` must pass.
- JS: vanilla islands, `data-island`, Motion inside the island, no GSAP,
  no jQuery, no build.
- No new code comments unless asked.

## Quality

PHPStan level 5 covers theme `src/` and the hierarchy PHP files only
(`phpstan.neon`). Deploy: see `docs/DEPLOYMENT.md`. After deploy, flush
rewrites, object cache, and `Timber\Cache\Cleaner::clear_cache_timber()`.

<!-- code-review-graph MCP tools -->
## MCP Tools: code-review-graph

**This project has a knowledge graph. Start with the code-review-graph
MCP tools to narrow scope, then read the source.** The graph is cheaper than scanning files and
gives you structural context (callers, dependents, test coverage) that file search cannot.

### When to use graph tools FIRST

- **Exploring code**: `semantic_search_nodes_tool` or `query_graph_tool` instead of Grep
- **Understanding impact**: `get_impact_radius_tool` instead of manually tracing imports
- **Code review**: `detect_changes_tool` + `get_review_context_tool` instead of reading entire files
- **Finding relationships**: `query_graph_tool` with callers_of/callees_of/imports_of/tests_for
- **Architecture questions**: `get_architecture_overview_tool` + `list_communities_tool`

### Verify in the source

- Narrow scope with the graph, then read the source. Do not change code from graph output alone.
- For any non-trivial change, read the implementation and the relevant tests before concluding.
- Verify the exact source when touching behavior, database logic, migrations, retries, fallbacks,
  recovery, or compatibility code.
- When the graph and the source disagree, the source wins. The graph may be stale or may not
  model that relationship.
- An empty graph result can mean "not indexed" or "not statically visible", not "does not exist".

### Key Tools

| Tool | Use when |
| ------ | ---------- |
| `detect_changes_tool` | Reviewing code changes — gives risk-scored analysis |
| `get_review_context_tool` | Need source snippets for review — token-efficient |
| `get_impact_radius_tool` | Understanding blast radius of a change |
| `get_affected_flows_tool` | Finding which execution paths are impacted |
| `query_graph_tool` | Tracing callers, callees, imports, tests, dependencies |
| `semantic_search_nodes_tool` | Finding functions/classes by name or keyword |
| `get_architecture_overview_tool` | Understanding high-level codebase structure |
| `refactor_tool` | Planning renames, finding dead code |

### Workflow

1. The graph auto-updates on file changes (via hooks).
2. Use `detect_changes_tool` for code review.
3. Use `get_affected_flows_tool` to understand impact.
4. Use `query_graph_tool` pattern="tests_for" to check coverage.
<!-- /code-review-graph MCP tools -->
