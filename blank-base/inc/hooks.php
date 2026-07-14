<?php
/**
 * Theme hook framework for Blank Base.
 *
 * Provides a GeneratePress-style set of action hooks placed throughout the
 * templates, plus a small `blank_base_do_element()` helper. Child themes and
 * plugins can attach markup to any of these locations without editing template
 * files.
 *
 * Available hooks (all fire `do_action( '<name>' )`):
 *
 *   blank_base_before_header        Before the <header> element.
 *   blank_base_after_header         After the <header> element.
 *   blank_base_inside_header        Inside the header, after site branding.
 *   blank_base_before_navigation    Before the primary navigation.
 *   blank_base_after_navigation     After the primary navigation.
 *   blank_base_inside_navigation    Inside the primary navigation.
 *   blank_base_before_content       Inside #content, before #primary.
 *   blank_base_after_content        Inside #content, after the sidebars.
 *   blank_base_before_main          Top of the main content column.
 *   blank_base_after_main           Bottom of the main content column.
 *   blank_base_before_right_sidebar Before the right sidebar widget area.
 *   blank_base_after_right_sidebar  After the right sidebar widget area.
 *   blank_base_before_left_sidebar  Before the left sidebar widget area.
 *   blank_base_after_left_sidebar   After the left sidebar widget area.
 *   blank_base_before_footer        Before the <footer> element.
 *   blank_base_after_footer         After the <footer> element.
 *   blank_base_inside_footer        Inside the footer, before the footer bar.
 *   blank_base_footer_bar           Content of the footer bar row.
 *   blank_base_before_footer_bar    Before the footer bar row.
 *   blank_base_after_footer_bar     After the footer bar row.
 *   blank_base_top_bar              Content of the optional top bar.
 *   blank_base_before_entry_content Before .entry-content on singular views.
 *   blank_base_after_entry_content  After .entry-content on singular views.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'blank_base_do_element' ) ) :
	/**
	 * Fire a theme hook. Thin wrapper around do_action() kept for readability
	 * and so the list of available hooks lives in one place.
	 *
	 * @param string $name Hook name (without the `blank_base_` prefix).
	 * @param mixed  ...$args Optional arguments passed to the hook.
	 */
	function blank_base_do_element( $name, ...$args ) {
		do_action( 'blank_base_' . $name, ...$args );
	}
endif;

if ( ! function_exists( 'blank_base_get_hooks' ) ) :
	/**
	 * Return the list of registered theme hook locations. Used by the docs page
	 * and available for programmatic discovery.
	 *
	 * @return array
	 */
	function blank_base_get_hooks() {
		return array(
			'blank_base_before_header',
			'blank_base_after_header',
			'blank_base_inside_header',
			'blank_base_before_navigation',
			'blank_base_after_navigation',
			'blank_base_inside_navigation',
			'blank_base_before_content',
			'blank_base_after_content',
			'blank_base_before_main',
			'blank_base_after_main',
			'blank_base_before_right_sidebar',
			'blank_base_after_right_sidebar',
			'blank_base_before_left_sidebar',
			'blank_base_after_left_sidebar',
			'blank_base_before_footer',
			'blank_base_after_footer',
			'blank_base_inside_footer',
			'blank_base_footer_bar',
			'blank_base_before_footer_bar',
			'blank_base_after_footer_bar',
			'blank_base_top_bar',
			'blank_base_before_entry_content',
			'blank_base_after_entry_content',
		);
	}
endif;
