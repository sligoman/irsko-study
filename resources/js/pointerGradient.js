// Pointer-driven gradient & parallax helper
// Updates CSS vars --mx and --my (percent) and per-section transform vars --px/--py
// Usage: import initPointerGradient from './pointerGradient'; initPointerGradient('.pointer-gradient');

export default function initPointerGradient(selector = '.pointer-gradient') {
	if (typeof window === 'undefined' || typeof document === 'undefined') return;

	const getEls = () => Array.from(document.querySelectorAll(selector));
	let ticking = false;
	let lastX = window.innerWidth / 2;
	let lastY = window.innerHeight / 2;

	function onMove(e) {
		const point = (e.touches && e.touches[0]) || e;
		lastX = point.clientX;
		lastY = point.clientY;
		if (!ticking) {
			ticking = true;
			requestAnimationFrame(update);
		}
	}

	function update() {
		ticking = false;
		const mx = (lastX / window.innerWidth) * 100;
		const my = (lastY / window.innerHeight) * 100;
		getEls().forEach(el => {
			try {
				el.style.setProperty('--mx', `${mx}%`);
				el.style.setProperty('--my', `${my}%`);

				// Compute parallax offsets relative to element center
				const rect = el.getBoundingClientRect();
				const cx = rect.left + rect.width / 2;
				const cy = rect.top + rect.height / 2;
				const dx = (lastX - cx) / rect.width; // roughly -0.5..0.5
				const dy = (lastY - cy) / rect.height;
				const intensity = parseFloat(el.getAttribute('data-parallax-intensity')) || 24;
				const tx = (dx * intensity).toFixed(2) + 'px';
				const ty = (dy * intensity).toFixed(2) + 'px';
				el.style.setProperty('--px', tx);
				el.style.setProperty('--py', ty);

				// Move inner .parallax children with a smaller multiplier for depth
				el.querySelectorAll('.parallax').forEach(child => {
					const childScale = parseFloat(child.getAttribute('data-parallax-scale')) || 6;
					const ctx = (dx * childScale).toFixed(2) + 'px';
					const cty = (dy * childScale).toFixed(2) + 'px';
					child.style.transform = `translate(${ctx}, ${cty})`;
				});
			} catch (err) {
				// ignore individual element errors
				// console.debug('pointerGradient update error', err);
			}
		});
	}

	function initPosition() {
		const mx = 50;
		const my = 50;
		getEls().forEach(el => {
			el.style.setProperty('--mx', `${mx}%`);
			el.style.setProperty('--my', `${my}%`);
			el.style.setProperty('--px', `0px`);
			el.style.setProperty('--py', `0px`);
		});
	}

	window.addEventListener('mousemove', onMove, { passive: true });
	window.addEventListener('touchmove', onMove, { passive: true });
	window.addEventListener('resize', initPosition);

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initPosition);
	} else {
		initPosition();
	}

	return {
		destroy() {
			window.removeEventListener('mousemove', onMove);
			window.removeEventListener('touchmove', onMove);
			window.removeEventListener('resize', initPosition);
		}
	};
}
