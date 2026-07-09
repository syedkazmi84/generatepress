/* Bookwright theme scripts */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		/* ---- Mobile navigation toggle ---- */
		var toggle = document.querySelector('.bw-nav-toggle');
		var nav = document.getElementById('bw-primary-nav');

		if (toggle && nav) {
			toggle.addEventListener('click', function () {
				var open = nav.classList.toggle('is-open');
				toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			});

			/* Close menu when a real link (not a parent toggle) is tapped */
			nav.querySelectorAll('a').forEach(function (link) {
				link.addEventListener('click', function () {
					if (window.innerWidth <= 760 && !link.parentElement.classList.contains('menu-item-has-children')) {
						nav.classList.remove('is-open');
						toggle.setAttribute('aria-expanded', 'false');
					}
				});
			});
		}

		/* ---- Sticky header shadow on scroll ---- */
		var header = document.querySelector('.bw-header');
		if (header) {
			var onScroll = function () {
				if (window.scrollY > 12) {
					header.style.boxShadow = '0 10px 30px -20px rgba(28,36,56,.5)';
				} else {
					header.style.boxShadow = 'none';
				}
			};
			window.addEventListener('scroll', onScroll, { passive: true });
			onScroll();
		}

		/* ---- Lightweight count-up for stat numbers ---- */
		var counters = document.querySelectorAll('[data-count]');
		if (counters.length && 'IntersectionObserver' in window) {
			var io = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) return;
					var el = entry.target;
					var target = parseFloat(el.getAttribute('data-count'));
					var suffix = el.getAttribute('data-suffix') || '';
					var dur = 1400, start = null;
					function step(ts) {
						if (!start) start = ts;
						var p = Math.min((ts - start) / dur, 1);
						var val = Math.floor(p * target);
						el.textContent = val.toLocaleString() + suffix;
						if (p < 1) requestAnimationFrame(step);
						else el.textContent = target.toLocaleString() + suffix;
					}
					requestAnimationFrame(step);
					io.unobserve(el);
				});
			}, { threshold: 0.4 });
			counters.forEach(function (c) { io.observe(c); });
		}

		/* ---- Simple front-end validation feedback for demo contact form ---- */
		var demoForm = document.querySelector('[data-demo-form]');
		if (demoForm) {
			demoForm.addEventListener('submit', function (e) {
				e.preventDefault();
				var note = demoForm.querySelector('.bw-form__note');
				if (note) {
					note.textContent = 'Thanks! This is a demo form — connect a plugin like WPForms or Contact Form 7 to receive real submissions.';
					note.style.display = 'block';
				}
				demoForm.reset();
			});
		}
	});
})();
