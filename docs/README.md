# Canopy Documentation

> Developed by Agência Upgrade — https://agenciaupgrade.com.br

This directory contains public documentation for Canopy developers.

## Guides

### Quick Reference
- **[TIMBER.md](./TIMBER.md)** — Timber 2.x templating reference and patterns
  - Post objects, menus, images, custom fields
  - Common patterns (loops, conditionals, queries, pagination)
  - Performance tips

- **[CSS-PATTERNS.md](./CSS-PATTERNS.md)** — CSS architecture and conventions
  - Token-first design with `@layer`
  - BEM naming with `cnp-` prefix
  - Component co-location without a build step
  - Color system, typography scale, spacing scale
  - Responsive design patterns
  - Accessibility and performance tips

- **[JAVASCRIPT.md](./JAVASCRIPT.md)** — JavaScript architecture (islands, no build)
  - On-demand per-component modules via dynamic `import()`
  - Lazy hydration with `IntersectionObserver`
  - Writing and registering an island
  - Loading heavy dependencies (Motion) per island

- **[DEPLOYMENT.md](./DEPLOYMENT.md)** — Production deployment guide
  - GitHub Actions workflows (deploy-production, auto-update)
  - Server setup and prerequisites
  - Manual deployment and rollback
  - Debugging and troubleshooting
  - Best practices and advanced setup

### External Documentation (local, not versioned)

Mirrored third-party docs (Timber, Bedrock, LiteSpeed) live under
`docs/_vendor/` and are git-ignored — they are kept locally for reference but
excluded from the repository. Read them upstream:

- **Timber 2.x** — https://timber.github.io/docs/v2/
- **Bedrock** — https://roots.io/bedrock/docs/
- **LiteSpeed Cache** — https://docs.litespeedtech.com/lscache/lscwp/

## Project Structure

See [CLAUDE.md](../CLAUDE.md) in the project root for:
- Full stack documentation
- Architecture overview
- File structure reference
- Getting started guide
- Standards and conventions

See [README.md](../README.md) in the project root for:
- Public project introduction
- Quick start guide
- Features overview
- Support and license

