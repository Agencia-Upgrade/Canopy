/**
 * Canopy — Island loader
 * Agência Upgrade — https://agenciaupgrade.com.br
 *
 * ES module, no build step. JavaScript is loaded on demand, per component
 * ("island"). Markup opts in with data-island="name"; the matching module is
 * imported only when an instance is on the page, and only once it scrolls into
 * view. Heavy dependencies (e.g. Motion) live inside the island module, so they
 * are never downloaded on pages that don't use them.
 *
 *   <div data-island="reveal"> … </div>   →   ./islands/reveal.js
 *
 * Register each island below. Add data-island-eager to hydrate immediately
 * instead of waiting for scroll.
 */

const islands = {
	reveal: () => import('./islands/reveal.js'),
	// menu: () => import('./islands/menu.js'),
};

const hydrate = (el) => {
	const loader = islands[el.dataset.island];

	if (!loader) {
		return;
	}

	loader().then((mod) => {
		el.removeAttribute('data-island');
		mod.default?.(el);
	});
};

const observer =
	'IntersectionObserver' in window
		? new IntersectionObserver((entries, obs) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					obs.unobserve(entry.target);
					hydrate(entry.target);
				}
			});
		}, { rootMargin: '100px' })
		: null;

document.querySelectorAll('[data-island]').forEach((el) => {
	if (el.hasAttribute('data-island-eager') || !observer) {
		hydrate(el);
	} else {
		observer.observe(el);
	}
});
