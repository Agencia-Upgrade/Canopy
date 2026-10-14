/**
 * Island host. data-island="name" → ./islands/name.js
 * data-island-src must be same-origin under /islands/. Do not import Motion here.
 */

const islandDir = new URL('./islands/', import.meta.url);
const namePattern = /^[a-z0-9-]+$/;

const show = (el) => {
	el.removeAttribute('data-island');
};

const isAllowedSrc = (value) => {
	try {
		const url = new URL(value, import.meta.url);

		return url.origin === islandDir.origin && url.pathname.startsWith(islandDir.pathname);
	} catch {
		return false;
	}
};

const load = (el) => {
	const src = el.getAttribute('data-island-src');

	if (src) {
		if (!isAllowedSrc(src)) {
			return Promise.reject(new Error('island src'));
		}

		return import(src);
	}

	const name = el.dataset.island || '';

	if (!namePattern.test(name)) {
		return Promise.reject(new Error('island name'));
	}

	return import(`./islands/${name}.js`);
};

const hydrate = (el) => {
	load(el)
		.then((mod) => {
			mod.default?.(el);
			show(el);
		})
		.catch(() => {
			show(el);
		});
};

let pending = 0;

const observer =
	'IntersectionObserver' in window
		? new IntersectionObserver((entries, obs) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					obs.unobserve(entry.target);
					pending -= 1;

					if (pending === 0) {
						obs.disconnect();
					}

					hydrate(entry.target);
				}
			});
		}, { rootMargin: '100px' })
		: null;

document.querySelectorAll('[data-island]').forEach((el) => {
	if (el.hasAttribute('data-island-eager') || !observer) {
		hydrate(el);
	} else {
		pending += 1;
		observer.observe(el);
	}
});
