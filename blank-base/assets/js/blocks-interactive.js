/**
 * Front-end engines for the interactive Blank Base blocks (no dependencies).
 *
 *   .bb-carousel   — image/content/testimonial/post carousels & sliders.
 *   .bb-tabs       — tabbed content (nav built from panels).
 *   .bb-accordion  — collapsible items.
 *
 * All engines respect prefers-reduced-motion, are keyboard accessible and are
 * safe to run more than once (they mark initialised nodes).
 *
 * @package Blank_Base
 */
( function () {
	'use strict';

	var reduceMotion =
		window.matchMedia &&
		window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ==================================================================
	 * Carousel
	 * ================================================================ */
	function setupCarousel( root ) {
		if ( root.dataset.bbInit ) {
			return;
		}
		root.dataset.bbInit = '1';

		var track = root.querySelector( '.bb-carousel__track' );
		if ( ! track ) {
			return;
		}
		var slides = Array.prototype.slice.call( track.children );
		if ( ! slides.length ) {
			return;
		}

		var perView = parseFloat( root.dataset.perView || '1' ) || 1;
		var perTablet = parseFloat( root.dataset.perViewTablet || '' ) || null;
		var showArrows = root.dataset.arrows !== '0';
		var showDots = root.dataset.dots !== '0';
		var autoplay = parseInt( root.dataset.autoplay || '0', 10 ) || 0;
		var index = 0;
		var dotsWrap = null;

		function current() {
			var w = window.innerWidth;
			if ( w < 600 ) {
				return 1;
			}
			if ( w < 900 && perTablet ) {
				return perTablet;
			}
			return perView;
		}

		function maxIndex() {
			return Math.max( 0, slides.length - Math.floor( current() ) );
		}

		function updateDots() {
			if ( ! dotsWrap ) {
				return;
			}
			var buttons = dotsWrap.children;
			for ( var i = 0; i < buttons.length; i++ ) {
				var on = i === index;
				buttons[ i ].classList.toggle( 'is-active', on );
				buttons[ i ].setAttribute( 'aria-current', on ? 'true' : 'false' );
			}
		}

		function apply() {
			var pv = current();
			slides.forEach( function ( s ) {
				s.style.flex = '0 0 ' + 100 / pv + '%';
				s.style.maxWidth = 100 / pv + '%';
			} );
			if ( index > maxIndex() ) {
				index = maxIndex();
			}
			track.style.transform = 'translateX(-' + index * ( 100 / pv ) + '%)';
			updateDots();
		}

		function go( i ) {
			var max = maxIndex();
			index = i < 0 ? 0 : i > max ? max : i;
			apply();
		}

		// Viewport wrapper for overflow clipping.
		var viewport = document.createElement( 'div' );
		viewport.className = 'bb-carousel__viewport';
		track.parentNode.insertBefore( viewport, track );
		viewport.appendChild( track );

		// Arrows.
		if ( showArrows && slides.length > 1 ) {
			var prev = document.createElement( 'button' );
			prev.type = 'button';
			prev.className = 'bb-carousel__arrow bb-carousel__arrow--prev';
			prev.setAttribute( 'aria-label', 'Previous' );
			prev.innerHTML = '<span aria-hidden="true">‹</span>';
			prev.addEventListener( 'click', function () {
				go( index - 1 );
			} );

			var next = document.createElement( 'button' );
			next.type = 'button';
			next.className = 'bb-carousel__arrow bb-carousel__arrow--next';
			next.setAttribute( 'aria-label', 'Next' );
			next.innerHTML = '<span aria-hidden="true">›</span>';
			next.addEventListener( 'click', function () {
				go( index + 1 );
			} );

			root.appendChild( prev );
			root.appendChild( next );
		}

		// Dots.
		if ( showDots && slides.length > 1 ) {
			dotsWrap = document.createElement( 'div' );
			dotsWrap.className = 'bb-carousel__dots';
			var pages = maxIndex() + 1;
			for ( var d = 0; d < pages; d++ ) {
				( function ( page ) {
					var dot = document.createElement( 'button' );
					dot.type = 'button';
					dot.className = 'bb-carousel__dot';
					dot.setAttribute( 'aria-label', 'Go to slide ' + ( page + 1 ) );
					dot.addEventListener( 'click', function () {
						go( page );
					} );
					dotsWrap.appendChild( dot );
				} )( d );
			}
			root.appendChild( dotsWrap );
		}

		// Basic touch/drag support.
		var startX = null;
		viewport.addEventListener(
			'touchstart',
			function ( e ) {
				startX = e.touches[ 0 ].clientX;
			},
			{ passive: true }
		);
		viewport.addEventListener(
			'touchend',
			function ( e ) {
				if ( startX === null ) {
					return;
				}
				var dx = e.changedTouches[ 0 ].clientX - startX;
				if ( Math.abs( dx ) > 40 ) {
					go( index + ( dx < 0 ? 1 : -1 ) );
				}
				startX = null;
			},
			{ passive: true }
		);

		window.addEventListener( 'resize', apply );
		apply();

		if ( autoplay && ! reduceMotion && slides.length > 1 ) {
			var timer = setInterval( function () {
				go( index >= maxIndex() ? 0 : index + 1 );
			}, autoplay );
			root.addEventListener( 'mouseenter', function () {
				clearInterval( timer );
			} );
		}
	}

	/* ==================================================================
	 * Tabs — build the tablist from the panels' data-bb-title.
	 * ================================================================ */
	function setupTabs( root ) {
		if ( root.dataset.bbInit ) {
			return;
		}
		root.dataset.bbInit = '1';

		var panelsWrap = root.querySelector( '.bb-tabs__panels' );
		if ( ! panelsWrap ) {
			return;
		}
		var panels = Array.prototype.slice.call( panelsWrap.children ).filter(
			function ( p ) {
				return p.classList.contains( 'bb-tab' );
			}
		);
		if ( ! panels.length ) {
			return;
		}

		var list = document.createElement( 'div' );
		list.className = 'bb-tabs__list';
		list.setAttribute( 'role', 'tablist' );

		var buttons = [];

		function activate( i ) {
			panels.forEach( function ( p, n ) {
				var on = n === i;
				p.hidden = ! on;
				buttons[ n ].setAttribute( 'aria-selected', on ? 'true' : 'false' );
				buttons[ n ].tabIndex = on ? 0 : -1;
				buttons[ n ].classList.toggle( 'is-active', on );
			} );
		}

		panels.forEach( function ( panel, i ) {
			var title = panel.getAttribute( 'data-bb-title' ) || 'Tab ' + ( i + 1 );
			var btn = document.createElement( 'button' );
			btn.type = 'button';
			btn.className = 'bb-tabs__tab';
			btn.setAttribute( 'role', 'tab' );
			btn.textContent = title;
			btn.addEventListener( 'click', function () {
				activate( i );
			} );
			btn.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'ArrowRight' || e.key === 'ArrowLeft' ) {
					e.preventDefault();
					var dir = e.key === 'ArrowRight' ? 1 : -1;
					var n = ( i + dir + panels.length ) % panels.length;
					buttons[ n ].focus();
					activate( n );
				}
			} );
			buttons.push( btn );
			list.appendChild( btn );
			panel.setAttribute( 'role', 'tabpanel' );
		} );

		root.insertBefore( list, panelsWrap );
		activate( 0 );
	}

	/* ==================================================================
	 * Accordion — toggle items (buttons are already in the markup).
	 * ================================================================ */
	function setupAccordion( root ) {
		if ( root.dataset.bbInit ) {
			return;
		}
		root.dataset.bbInit = '1';

		var single = root.dataset.single === '1';
		var headers = Array.prototype.slice.call(
			root.querySelectorAll( '.bb-accordion__header' )
		);

		headers.forEach( function ( header ) {
			var item = header.closest( '.bb-accordion__item' );
			var panel = item ? item.querySelector( '.bb-accordion__panel' ) : null;
			if ( ! panel ) {
				return;
			}

			header.addEventListener( 'click', function () {
				var isOpen = item.classList.contains( 'is-open' );

				if ( single ) {
					headers.forEach( function ( h ) {
						var it = h.closest( '.bb-accordion__item' );
						if ( it && it !== item ) {
							it.classList.remove( 'is-open' );
							h.setAttribute( 'aria-expanded', 'false' );
						}
					} );
				}

				item.classList.toggle( 'is-open', ! isOpen );
				header.setAttribute( 'aria-expanded', ! isOpen ? 'true' : 'false' );
			} );
		} );
	}

	/* ================================================================ */
	function init() {
		document.querySelectorAll( '.bb-carousel' ).forEach( setupCarousel );
		document.querySelectorAll( '.bb-tabs' ).forEach( setupTabs );
		document.querySelectorAll( '.bb-accordion' ).forEach( setupAccordion );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
