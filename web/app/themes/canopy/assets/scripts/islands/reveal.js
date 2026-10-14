/**
 * Optional Motion island. Loaded only when data-island="reveal" is in the Twig.
 */

import { animate, stagger } from 'https://cdn.jsdelivr.net/npm/motion@12.40.0/+esm';

export default function reveal(el) {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	const items = el.hasAttribute('data-reveal-stagger')
		? [...el.children]
		: [el];

	if (!items.length) {
		return;
	}

	animate(
		items,
		{ opacity: [0, 1], transform: ['translateY(0.75rem)', 'none'] },
		{
			duration: 0.55,
			easing: 'ease-out',
			delay: items.length > 1 ? stagger(0.08) : 0,
		},
	);
}
