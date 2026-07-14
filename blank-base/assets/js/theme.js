/**
 * File theme.js.
 *
 * Pro interactions: the back-to-top button, reading-progress bar and other
 * front-end enhancements.
 *
 * @package Blank_Base
 */
( function () {
	/* --------------------------------------------------------------------
	 * Back-to-top button.
	 * ------------------------------------------------------------------ */
	const backToTop = document.querySelector( '.back-to-top' );

	if ( backToTop ) {
		const reduceMotion = window.matchMedia(
			'(prefers-reduced-motion: reduce)'
		).matches;

		const onScroll = function () {
			if ( window.scrollY > 400 ) {
				backToTop.classList.add( 'is-visible' );
			} else {
				backToTop.classList.remove( 'is-visible' );
			}
		};

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();

		backToTop.addEventListener( 'click', function () {
			window.scrollTo( {
				top: 0,
				behavior: reduceMotion ? 'auto' : 'smooth',
			} );
		} );
	}

	/* --------------------------------------------------------------------
	 * Reading-progress bar (single posts).
	 * ------------------------------------------------------------------ */
	const progress = document.querySelector( '.reading-progress__bar' );

	if ( progress ) {
		const updateProgress = function () {
			const scrollTop = window.scrollY;
			const docHeight =
				document.documentElement.scrollHeight - window.innerHeight;
			const pct = docHeight > 0 ? ( scrollTop / docHeight ) * 100 : 0;
			progress.style.width = Math.min( 100, Math.max( 0, pct ) ) + '%';
		};

		window.addEventListener( 'scroll', updateProgress, { passive: true } );
		window.addEventListener( 'resize', updateProgress );
		updateProgress();
	}

	/* --------------------------------------------------------------------
	 * Dismissible announcement bar.
	 * ------------------------------------------------------------------ */
	const announcement = document.querySelector( '.announcement-bar' );

	if ( announcement ) {
		const closeBtn = announcement.querySelector( '.announcement-bar__close' );
		const key = 'blankBaseAnnouncementDismissed';

		try {
			if ( localStorage.getItem( key ) === '1' ) {
				announcement.hidden = true;
			}
		} catch ( e ) {}

		if ( closeBtn ) {
			closeBtn.addEventListener( 'click', function () {
				announcement.hidden = true;
				try {
					localStorage.setItem( key, '1' );
				} catch ( e ) {}
			} );
		}
	}

	const prefersReduced = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;
	const hasIO = 'IntersectionObserver' in window;

	/* --------------------------------------------------------------------
	 * Animated counters (Animated stats pattern).
	 *
	 * The target is read from the editable heading text — e.g. "4.9/5",
	 * "10k+", "$29", "500+". Any leading text is kept as a prefix, the first
	 * number is animated, and the rest is kept as a suffix.
	 * ------------------------------------------------------------------ */
	const counters = document.querySelectorAll( '.bb-counter' );

	if ( counters.length ) {
		const parseCounter = function ( el ) {
			const match = el.textContent
				.trim()
				.match( /^(\D*?)([\d.,]+)(.*)$/ );
			if ( ! match ) {
				return null;
			}
			const numStr = match[ 2 ].replace( /,/g, '' );
			const dot = numStr.indexOf( '.' );
			return {
				prefix: match[ 1 ],
				suffix: match[ 3 ],
				target: parseFloat( numStr ) || 0,
				decimals: dot >= 0 ? numStr.length - dot - 1 : 0,
			};
		};

		const render = function ( el, data, value ) {
			el.textContent =
				data.prefix + value.toFixed( data.decimals ) + data.suffix;
		};

		const runCounter = function ( el ) {
			// Use the target parsed from the ORIGINAL text (cached before the
			// reset below overwrote the heading with "0"). Re-parsing here would
			// read the reset value and animate 0 → 0.
			const data = el.bbCounterData;
			if ( ! data ) {
				return;
			}
			if ( prefersReduced ) {
				render( el, data, data.target );
				return;
			}
			const duration = 1500;
			let startTime = null;
			const tick = function ( now ) {
				if ( ! startTime ) {
					startTime = now;
				}
				const progress = Math.min( ( now - startTime ) / duration, 1 );
				const eased = 0.5 - Math.cos( Math.PI * progress ) / 2;
				render( el, data, data.target * eased );
				if ( progress < 1 ) {
					window.requestAnimationFrame( tick );
				} else {
					render( el, data, data.target );
				}
			};
			window.requestAnimationFrame( tick );
		};

		// Parse each counter's target ONCE from its original text and cache it,
		// then reset the display to zero so it visibly counts up on scroll.
		counters.forEach( function ( el ) {
			const data = parseCounter( el );
			if ( ! data ) {
				return;
			}
			el.bbCounterData = data;
			if ( ! prefersReduced ) {
				render( el, data, 0 );
			}
		} );

		if ( hasIO ) {
			const counterIO = new IntersectionObserver(
				function ( entries, obs ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							obs.unobserve( entry.target );
							runCounter( entry.target );
						}
					} );
				},
				{ threshold: 0.4 }
			);
			counters.forEach( function ( el ) {
				if ( el.bbCounterData ) {
					counterIO.observe( el );
				}
			} );
		} else {
			counters.forEach( runCounter );
		}
	}

	/* --------------------------------------------------------------------
	 * Skill / progress bars.
	 *
	 * Each ".bb-skill" is an editable paragraph whose text is a label plus a
	 * trailing percentage, e.g. "Design 92%". The script parses that, builds
	 * the label row and bar, then animates the fill to the value on scroll.
	 * ------------------------------------------------------------------ */
	const skills = document.querySelectorAll( '.bb-skill' );

	if ( skills.length ) {
		const buildSkill = function ( el ) {
			if ( el.querySelector( '.bb-skill__track' ) ) {
				return null; // Already processed.
			}
			const match = el.textContent
				.trim()
				.match( /^(.*?)(\d+(?:\.\d+)?)\s*%?\s*$/ );
			const label = match ? match[ 1 ].trim() : el.textContent.trim();
			const pct = match
				? Math.max( 0, Math.min( 100, parseFloat( match[ 2 ] ) ) )
				: 0;

			el.textContent = '';

			const labelRow = document.createElement( 'span' );
			labelRow.className = 'bb-skill__label';
			const name = document.createElement( 'span' );
			name.textContent = label;
			const value = document.createElement( 'span' );
			value.textContent = pct + '%';
			labelRow.appendChild( name );
			labelRow.appendChild( value );

			const track = document.createElement( 'span' );
			track.className = 'bb-skill__track';
			const fill = document.createElement( 'span' );
			fill.className = 'bb-skill__fill';
			track.appendChild( fill );

			el.appendChild( labelRow );
			el.appendChild( track );

			return { fill: fill, pct: pct };
		};

		const activate = function ( fill, pct ) {
			fill.style.width = pct + '%';
		};

		skills.forEach( function ( el ) {
			const built = buildSkill( el );
			if ( ! built ) {
				return;
			}
			if ( ! hasIO || prefersReduced ) {
				activate( built.fill, built.pct );
				return;
			}
			const io = new IntersectionObserver(
				function ( entries, obs ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							obs.disconnect();
							activate( built.fill, built.pct );
						}
					} );
				},
				{ threshold: 0.3 }
			);
			io.observe( el );
		} );
	}

	/* --------------------------------------------------------------------
	 * Testimonial slider (CSS scroll-snap + controls + optional autoplay).
	 * ------------------------------------------------------------------ */
	document.querySelectorAll( '.bb-slider' ).forEach( function ( slider ) {
		const track = slider.querySelector( '.bb-slider__track' );
		const prev = slider.querySelector( '.bb-slider__prev' );
		const next = slider.querySelector( '.bb-slider__next' );

		if ( ! track ) {
			return;
		}

		const step = function ( dir ) {
			track.scrollBy( {
				left: dir * track.clientWidth,
				behavior: prefersReduced ? 'auto' : 'smooth',
			} );
		};

		if ( next ) {
			next.addEventListener( 'click', function () {
				step( 1 );
			} );
		}
		if ( prev ) {
			prev.addEventListener( 'click', function () {
				step( -1 );
			} );
		}

		// Optional autoplay (disabled when the visitor prefers reduced motion).
		if ( slider.getAttribute( 'data-autoplay' ) === '1' && ! prefersReduced ) {
			let timer = window.setInterval( function () {
				if ( track.scrollLeft + track.clientWidth >= track.scrollWidth - 4 ) {
					track.scrollTo( { left: 0, behavior: 'smooth' } );
				} else {
					step( 1 );
				}
			}, 5000 );

			slider.addEventListener( 'mouseenter', function () {
				window.clearInterval( timer );
			} );
			slider.addEventListener( 'focusin', function () {
				window.clearInterval( timer );
			} );
		}
	} );

	/* --------------------------------------------------------------------
	 * Scroll reveal animations.
	 *
	 * Applies to any block carrying the "bb-animate" class or an
	 * "is-style-animate-*" block-style class. The hidden start state only
	 * exists while JS is active (html.js) and motion is allowed, so content
	 * is always visible without JS or with reduced motion.
	 * ------------------------------------------------------------------ */
	const animated = document.querySelectorAll(
		'.bb-animate, [class*="is-style-animate-"]'
	);

	if ( animated.length ) {
		if ( hasIO && ! prefersReduced ) {
			const animIO = new IntersectionObserver(
				function ( entries, obs ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							obs.unobserve( entry.target );
							entry.target.classList.add( 'is-visible' );
						}
					} );
				},
				{ threshold: 0.12 }
			);
			animated.forEach( function ( el ) {
				animIO.observe( el );
			} );
		} else {
			// No IntersectionObserver (or reduced motion): reveal immediately.
			animated.forEach( function ( el ) {
				el.classList.add( 'is-visible' );
			} );
		}
	}
}() );
