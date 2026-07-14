/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @package Blank_Base
 */
( function ( $ ) {
	// Site title.
	wp.customize( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Site description / tagline.
	wp.customize( 'blogdescription', function ( value ) {
		value.bind( function ( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function ( value ) {
		value.bind( function ( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					clip: 'auto',
					position: 'relative',
				} );
				$( '.site-title a, .site-description' ).css( {
					color: to,
				} );
			}
		} );
	} );

	// Accent / link color.
	wp.customize( 'blank_base_accent_color', function ( value ) {
		value.bind( function ( to ) {
			document.documentElement.style.setProperty( '--bb-color-link', to );
		} );
	} );

	// Footer text.
	wp.customize( 'blank_base_footer_text', function ( value ) {
		value.bind( function ( to ) {
			$( '.site-info' ).html( to );
		} );
	} );

	// Base font size.
	wp.customize( 'blank_base_base_font_size', function ( value ) {
		value.bind( function ( to ) {
			var size = parseInt( to, 10 );
			if ( size >= 12 && size <= 24 ) {
				document.body.style.fontSize = size + 'px';
			}
		} );
	} );
}( jQuery ) );
