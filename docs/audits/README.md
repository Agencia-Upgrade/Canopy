# Audits

Point-in-time reviews of the starter, one per WordPress agent skill. Each
file records what the skill checks, what applies to Canopy, and what was
fixed or left as-is. Line numbers and "deferred" status reflect the commit
each audit ran on. Current state lives in [`../KNOWN-ISSUES.md`](../KNOWN-ISSUES.md).

Most REST, Abilities, block, and plugin-directory findings are N/A: the
starter ships no REST routes, abilities, custom blocks, or plugin code. A
fork that adds any of those should run the matching audit again.

## Resolved after the audits ran

- `filemtime()` existence guard (`wp-project-triage` #3).
- URL attributes escaped with `|e('esc_url')` across views; titles and
  excerpts stay as-is (`wp-plugin-development` #2, `wp-patterns` #8).
- `updateRobotsTxt()` scheme fallback (`wordpress-router` #7).
- `mailFrom()` host fallback (`wp-phpstan`, deferred row).
