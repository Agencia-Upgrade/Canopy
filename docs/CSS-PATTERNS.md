# CSS Patterns & Conventions

> Developed by Agência Upgrade — https://agenciaupgrade.com.br

## Architecture Overview

Canopy uses a **token-first** CSS approach with `@layer` for organization and **BEM naming** with the `cnp-` prefix.

### Layers (in order)

```css
@layer reset, tokens, base, components, utilities;
```

1. **Reset** — Browser reset, normalization (no classes)
2. **Tokens** — CSS custom properties (colors, spacing, typography)
3. **Base** — Element styles (h1-h6, p, a, etc.)
4. **Components** — Reusable blocks (`.cnp-header`, `.cnp-card`, etc.)
5. **Utilities** — Single-purpose helpers (`.sr-only`, media query resets)

### Font Scale

**1rem = 10px** (set via `html { font-size: 62.5%; }`)

This makes math easy: `1.6rem` = 16px, `2rem` = 20px, etc.

### Tokens (Custom Properties)

```css
:root {
  /* Colors */
  --cnp-color-primary: #0066cc;
  --cnp-color-text: #333;

  /* Spacing scale (4px baseline) */
  --cnp-space-4: 0.4rem;   /* 4px */
  --cnp-space-8: 0.8rem;   /* 8px */
  --cnp-space-16: 1.6rem;  /* 16px */
  --cnp-space-24: 2.4rem;  /* 24px */
  --cnp-space-32: 3.2rem;  /* 32px */

  /* Typography */
  --cnp-font-size-base: 1.6rem;  /* 16px - body text */
  --cnp-font-weight-bold: 700;

  /* Transitions */
  --cnp-transition-fast: 150ms ease-in-out;
}
```

## BEM Naming Convention

**Block__Element--Modifier**

```css
.cnp-button { }              /* Block: main component */
.cnp-button__icon { }        /* Element: part of button */
.cnp-button--primary { }     /* Modifier: variation */
```

### Good Examples

```css
.cnp-card { }
.cnp-card__header { }
.cnp-card__title { }
.cnp-card__content { }
.cnp-card--featured { }

.cnp-nav { }
.cnp-nav__list { }
.cnp-nav__item { }
.cnp-nav__link { }
.cnp-nav__link--active { }
```

## Common Patterns

### Component with States

```css
.cnp-button {
  padding: var(--cnp-space-12) var(--cnp-space-16);
  background: var(--cnp-color-primary);
  border-radius: 4px;
  transition: background-color var(--cnp-transition-fast);
}

.cnp-button:hover {
  background-color: var(--cnp-color-primary-dark);
}

.cnp-button--secondary {
  background-color: var(--cnp-color-secondary);
}

.cnp-button--small {
  padding: var(--cnp-space-8) var(--cnp-space-12);
  font-size: var(--cnp-font-size-sm);
}
```

### Responsive Stack

```css
.cnp-grid {
  display: grid;
  gap: var(--cnp-space-24);
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
}

@media (max-width: 768px) {
  .cnp-grid {
    gap: var(--cnp-space-16);
    grid-template-columns: 1fr;
  }
}
```

### Focus States (Accessibility)

```css
.cnp-link {
  color: var(--cnp-color-primary);
  text-decoration: none;
  outline: 2px solid transparent;
  outline-offset: 2px;
  transition: outline var(--cnp-transition-fast);
}

.cnp-link:focus-visible {
  outline-color: var(--cnp-color-primary);
}
```

### Reduced Motion

```css
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

## File Organization

**Single `main.css` file** (not split into partials)

```
assets/styles/
├── main.css       ← All CSS (source)
└── main.min.css   ← Minified (production, optional)
```

Comments within main.css for sections:

```css
/* =============================================================================
   Layer: Reset & Normalization
   ============================================================================= */

/* =============================================================================
   Layer: Design Tokens
   ============================================================================= */

/* =============================================================================
   Layer: Base Styles
   ============================================================================= */

/* =============================================================================
   Layer: Components — Header
   ============================================================================= */
```

## Color System

Define a semantic color palette:

```css
:root {
  /* Semantic */
  --cnp-color-primary: #0066cc;     /* CTAs, links */
  --cnp-color-secondary: #ff6600;   /* Accents */
  --cnp-color-success: #10b981;     /* Confirmation */
  --cnp-color-warning: #f59e0b;     /* Caution */
  --cnp-color-error: #ef4444;       /* Errors */

  /* Grayscale */
  --cnp-color-text: #333;           /* Main text */
  --cnp-color-text-light: #666;     /* Secondary text */
  --cnp-color-border: #ddd;         /* Borders */
  --cnp-color-background: #fff;
  --cnp-color-background-alt: #f9f9f9;
}
```

## Typography Scale

```css
:root {
  --cnp-font-size-sm: 1.2rem;   /* 12px - small labels */
  --cnp-font-size-base: 1.6rem; /* 16px - body text */
  --cnp-font-size-lg: 2rem;     /* 20px - subheadings */
  --cnp-font-size-xl: 2.4rem;   /* 24px - headings */
  --cnp-font-size-2xl: 3.2rem;  /* 32px - page titles */

  --cnp-font-weight-normal: 400;
  --cnp-font-weight-medium: 500;
  --cnp-font-weight-bold: 700;
}
```

## Spacing Scale

Consistently use token-based spacing:

```css
.cnp-container {
  padding: var(--cnp-space-24);
}

.cnp-card {
  padding: var(--cnp-space-16);
}

.cnp-card__title {
  margin-bottom: var(--cnp-space-12);
}

.cnp-nav__list {
  gap: var(--cnp-space-24);
}
```

## Responsive Design

Mobile-first approach:

```css
.cnp-grid {
  grid-template-columns: 1fr;        /* Mobile: 1 column */
  gap: var(--cnp-space-12);
}

@media (min-width: 768px) {
  .cnp-grid {
    grid-template-columns: repeat(2, 1fr);  /* Tablet: 2 columns */
  }
}

@media (min-width: 1024px) {
  .cnp-grid {
    grid-template-columns: repeat(3, 1fr);  /* Desktop: 3 columns */
  }
}
```

Common breakpoints:
- `768px` — Tablet
- `1024px` — Desktop
- `1280px` — Large desktop

## Resources

- [MDN CSS Guide](https://developer.mozilla.org/en-US/docs/Web/CSS)
- [BEM Naming](http://getbem.com/naming/)
- [CSS Layers (`@layer`)](https://developer.mozilla.org/en-US/docs/Web/CSS/@layer)
- [CSS Custom Properties](https://developer.mozilla.org/en-US/docs/Web/CSS/--*)
