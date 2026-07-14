/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 *
 * @package Blank_Base
 */
( function () {
	const siteNavigation = document.getElementById( 'site-navigation' );

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function () {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function ( event ) {
		const isClickInside = siteNavigation.contains( event.target );

		if ( ! isClickInside ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	// Close the menu on Escape and return focus to the toggle button.
	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && siteNavigation.classList.contains( 'toggled' ) ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
			button.focus();
		}
	} );

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName( 'a' );

	// Get all the link elements with children within the menu.
	const linksWithChildren = menu.querySelectorAll(
		'.menu-item-has-children > a, .page_item_has_children > a'
	);

	// Toggle focus each time a menu link is focused or blurred.
	for ( const link of links ) {
		link.addEventListener( 'focus', toggleFocus, true );
		link.addEventListener( 'blur', toggleFocus, true );
	}

	// Toggle focus each time a menu link with children receive a touch event.
	for ( const link of linksWithChildren ) {
		link.addEventListener( 'touchstart', toggleFocus, false );
	}

	/**
	 * Sets or removes the .focus class on an element.
	 *
	 * @param {Event} event The focus/blur/touch event.
	 */
	function toggleFocus( event ) {
		if ( event.type === 'focus' || event.type === 'blur' ) {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( ! self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}

		if ( event.type === 'touchstart' ) {
			const menuItem = this.parentNode;
			event.preventDefault();
			for ( const link of menuItem.parentNode.children ) {
				if ( menuItem !== link ) {
					link.classList.remove( 'focus' );
				}
			}
			menuItem.classList.toggle( 'focus' );
		}
	}
}() );

/**
 * Enhancements for GeneratePress-parity navigation options:
 *   - Off-canvas mobile menu (slide-in panel with backdrop + close button).
 *   - Click-to-open sub-menus when "Sub-Menu Opens On: Click" is selected.
 */
( function () {
	const nav = document.getElementById( 'site-navigation' );

	if ( ! nav ) {
		return;
	}

	const toggle = nav.querySelector( '.menu-toggle' );
	const close = nav.querySelector( '.menu-close' );
	const isOffCanvas = nav.classList.contains( 'mobile-menu--offcanvas' );
	const isClick = nav.classList.contains( 'nav-dropdown--click' );

	// --- Off-canvas: sync a body class so the backdrop + scroll lock apply. ---
	const syncOffCanvas = function () {
		if ( ! isOffCanvas ) {
			return;
		}
		if ( nav.classList.contains( 'toggled' ) ) {
			document.body.classList.add( 'offcanvas-open' );
		} else {
			document.body.classList.remove( 'offcanvas-open' );
		}
	};

	if ( toggle ) {
		toggle.addEventListener( 'click', syncOffCanvas );
	}

	if ( close ) {
		close.addEventListener( 'click', function () {
			nav.classList.remove( 'toggled' );
			if ( toggle ) {
				toggle.setAttribute( 'aria-expanded', 'false' );
			}
			document.body.classList.remove( 'offcanvas-open' );
		} );
	}

	// Close the off-canvas panel when the backdrop (outside the menu) is tapped.
	if ( isOffCanvas ) {
		document.addEventListener( 'click', function ( event ) {
			if (
				document.body.classList.contains( 'offcanvas-open' ) &&
				! nav.contains( event.target )
			) {
				document.body.classList.remove( 'offcanvas-open' );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				document.body.classList.remove( 'offcanvas-open' );
			}
		} );
	}

	// --- Click-to-open sub-menus. ---
	if ( isClick ) {
		const parents = nav.querySelectorAll( '.menu-item-has-children > a' );

		for ( const link of parents ) {
			link.addEventListener( 'click', function ( event ) {
				const parent = this.parentNode;
				const alreadyOpen = parent.classList.contains( 'is-open' );

				// Close sibling sub-menus at the same level.
				const siblings = parent.parentNode.querySelectorAll(
					':scope > .menu-item-has-children.is-open'
				);
				for ( const sibling of siblings ) {
					if ( sibling !== parent ) {
						sibling.classList.remove( 'is-open' );
					}
				}

				if ( ! alreadyOpen ) {
					// First click opens the sub-menu instead of following the link.
					event.preventDefault();
					parent.classList.add( 'is-open' );
				}
				// A second click on an open parent follows the link normally.
			} );
		}

		// Close open sub-menus when clicking outside the navigation.
		document.addEventListener( 'click', function ( event ) {
			if ( ! nav.contains( event.target ) ) {
				const open = nav.querySelectorAll( '.menu-item-has-children.is-open' );
				for ( const item of open ) {
					item.classList.remove( 'is-open' );
				}
			}
		} );
	}
}() );
