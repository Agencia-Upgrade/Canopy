/**
 * Island: reveal
 * Agência Upgrade — https://agenciaupgrade.com.br
 *
 * Fades + slides its element into view using Motion. Loaded on demand by
 * site.js only when a <… data-island="reveal"> is present and scrolled near.
 * Motion is imported here, so it ships only with this island.
 *
 * Usage:
 *   <div data-island="reveal"> … </div>
 */

import { animate } from 'https://cdn.jsdelivr.net/npm/motion@12.40.0/+esm';

export default function reveal(el) {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	animate(el, { opacity: [0, 1], transform: ['translateY(20px)', 'translateY(0)'] }, { duration: 0.6, easing: 'ease-out' });
}
