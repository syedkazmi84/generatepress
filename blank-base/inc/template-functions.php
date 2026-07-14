<?php
/**
 * Functions which enhance the theme by hooking into WordPress.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function blank_base_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Sidebar/layout classes are added by the layout engine
	// (see inc/layout.php → blank_base_layout_body_classes()).

	// Adds a helpful class when the custom logo is in use.
	if ( has_custom_logo() ) {
		$classes[] = 'has-custom-logo';
	}

	// Blog layout class on post listings only (never on singular views).
	if ( is_home() || is_archive() || is_search() ) {
		$layout    = get_theme_mod( 'blank_base_blog_layout', 'list' );
		$classes[] = 'blog-layout-' . sanitize_html_class( $layout );
	}

	// Sticky sidebar option.
	if ( get_theme_mod( 'blank_base_sticky_sidebar', false ) ) {
		$classes[] = 'sticky-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'blank_base_body_classes' );

/**
 * Add a `js` class to the html element as early as possible.
 *
 * Scroll-reveal animations use this class to scope their hidden start state,
 * so content stays visible when JavaScript is disabled.
 */
function blank_base_js_detection() {
	echo "<script>document.documentElement.classList.add('js');</script>\n";
}
add_action( 'wp_head', 'blank_base_js_detection', 0 );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function blank_base_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'blank_base_pingback_header' );

/**
 * Changes the excerpt "read more" ellipsis.
 *
 * @param string $more The default "read more" string.
 * @return string
 */
function blank_base_excerpt_more( $more ) {
	if ( is_admin() ) {
		return $more;
	}
	return '&hellip;';
}
add_filter( 'excerpt_more', 'blank_base_excerpt_more' );

/**
 * Filters the excerpt length.
 *
 * @param int $length Excerpt length in words.
 * @return int
 */
function blank_base_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}
	return 40;
}
add_filter( 'excerpt_length', 'blank_base_excerpt_length' );

/**
 * Adds a "Continue reading" wrapper class to the <!--more--> link.
 *
 * @param string $link The default read more link.
 * @return string
 */
function blank_base_content_more_link( $link ) {
	return '<span class="more-link-wrapper">' . $link . '</span>';
}
add_filter( 'the_content_more_link', 'blank_base_content_more_link' );

/**
 * Add a dropdown icon to top-level menu items that have children.
 *
 * @param string   $title The menu item's title.
 * @param WP_Post  $item  The current menu item.
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item.
 * @return string
 */
function blank_base_add_menu_caret( $title, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'menu-1' === $args->theme_location ) {
		if ( in_array( 'menu-item-has-children', $item->classes, true ) && 0 === $depth ) {
			$title .= ' <span class="dropdown-caret" aria-hidden="true"></span>';
		}
	}
	return $title;
}
add_filter( 'nav_menu_item_title', 'blank_base_add_menu_caret', 10, 4 );
