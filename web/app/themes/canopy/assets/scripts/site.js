/**
 * Canopy — Main Scripts
 * Agência Upgrade — https://agenciaupgrade.com.br
 */

document.addEventListener('DOMContentLoaded', function () {
  console.log('Canopy theme loaded');

  // Respect user's motion preferences
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!prefersReducedMotion) {
    // Initialize GSAP animations here
    // Example:
    // gsap.registerPlugin(ScrollTrigger);
    // gsap.to('.element', { duration: 1, opacity: 1 });
  }
});
