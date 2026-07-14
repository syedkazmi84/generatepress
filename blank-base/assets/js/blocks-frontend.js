/**
 * Front-end behaviour for interactive Blank Base blocks.
 *
 * Handles the Countdown timer and the Circle Progress ring. The Counter and
 * Progress Bar blocks are animated by assets/js/theme.js; the Toggle/FAQ block
 * uses a native <details> element and needs no JavaScript.
 *
 * @package Blank_Base
 */
( function () {
	'use strict';

	var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var hasIO = 'IntersectionObserver' in window;

	function pad( n ) {
		return ( n < 10 ? '0' : '' ) + n;
	}

	/* --------------------------------------------------------------------
	 * Countdown timers.
	 * ------------------------------------------------------------------ */
	var countdowns = document.querySelectorAll( '.bb-countdown[data-deadline]' );

	Array.prototype.forEach.call( countdowns, function ( node ) {
		var deadline = node.getAttribute( 'data-deadline' );
		if ( ! deadline ) {
			return;
		}
		var target = new Date( deadline ).getTime();
		if ( isNaN( target ) ) {
			return;
		}

		var nums = {
			days: node.querySelector( '[data-unit="days"]' ),
			hours: node.querySelector( '[data-unit="hours"]' ),
			minutes: node.querySelector( '[data-unit="minutes"]' ),
			seconds: node.querySelector( '[data-unit="seconds"]' ),
		};

		var timer = null;

		var tick = function () {
			var diff = target - Date.now();
			if ( diff < 0 ) {
				diff = 0;
			}
			var s = Math.floor( diff / 1000 );
			var d = Math.floor( s / 86400 );
			s -= d * 86400;
			var h = Math.floor( s / 3600 );
			s -= h * 3600;
			var m = Math.floor( s / 60 );
			s -= m * 60;

			if ( nums.days ) {
				nums.days.textContent = pad( d );
			}
			if ( nums.hours ) {
				nums.hours.textContent = pad( h );
			}
			if ( nums.minutes ) {
				nums.minutes.textContent = pad( m );
			}
			if ( nums.seconds ) {
				nums.seconds.textContent = pad( s );
			}

			if ( diff <= 0 && timer ) {
				window.clearInterval( timer );
				node.classList.add( 'is-complete' );
			}
		};

		tick();
		timer = window.setInterval( tick, 1000 );
	} );

	/* --------------------------------------------------------------------
	 * Circle progress rings.
	 * ------------------------------------------------------------------ */
	var circles = document.querySelectorAll( '.bb-circle[data-percent]' );
	var CIRC = 339.292; // 2 * PI * r, with r = 54.

	var runCircle = function ( node ) {
		var pct = Math.max( 0, Math.min( 100, parseFloat( node.getAttribute( 'data-percent' ) ) || 0 ) );
		var bar = node.querySelector( '.bb-circle__bar' );
		var num = node.querySelector( '.bb-circle__num' );

		if ( reduce ) {
			if ( bar ) {
				bar.style.strokeDashoffset = CIRC * ( 1 - pct / 100 );
			}
			if ( num ) {
				num.textContent = Math.round( pct ) + '%';
			}
			return;
		}

		var duration = 1200;
		var start = null;
		var frame = function ( now ) {
			if ( ! start ) {
				start = now;
			}
			var p = Math.min( ( now - start ) / duration, 1 );
			var eased = 0.5 - Math.cos( Math.PI * p ) / 2;
			var val = pct * eased;
			if ( bar ) {
				bar.style.strokeDashoffset = CIRC * ( 1 - val / 100 );
			}
			if ( num ) {
				num.textContent = Math.round( val ) + '%';
			}
			if ( p < 1 ) {
				window.requestAnimationFrame( frame );
			}
		};
		window.requestAnimationFrame( frame );
	};

	if ( circles.length ) {
		if ( hasIO && ! reduce ) {
			var io = new IntersectionObserver(
				function ( entries, obs ) {
					Array.prototype.forEach.call( entries, function ( entry ) {
						if ( entry.isIntersecting ) {
							obs.unobserve( entry.target );
							runCircle( entry.target );
						}
					} );
				},
				{ threshold: 0.4 }
			);
			Array.prototype.forEach.call( circles, function ( c ) {
				io.observe( c );
			} );
		} else {
			Array.prototype.forEach.call( circles, runCircle );
		}
	}
}() );
