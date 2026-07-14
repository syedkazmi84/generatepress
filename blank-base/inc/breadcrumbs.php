<?php
/**
 * Breadcrumb trail for Blank Base.
 *
 * Outputs an accessible, schema.org BreadcrumbList navigation. Controlled by
 * the "Show Breadcrumbs" Customizer option and hidden on the front page.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'blank_base_breadcrumbs_enabled' ) ) :
	/**
	 * Whether the breadcrumb trail should display for the current view.
	 *
	 * Starts from the "Show Breadcrumbs" Customizer default, then lets an
	 * individual page or post override it via the "Breadcrumbs" control in the
	 * Blank Base Layout meta box (Show / Hide).
	 *
	 * @return bool
	 */
	function blank_base_breadcrumbs_enabled() {
		$enabled = (bool) get_theme_mod( 'blank_base_breadcrumbs', true );

		// Per page/post override, when viewing a singular entry.
		if ( is_singular() ) {
			$override = get_post_meta( get_the_ID(), '_blank_base_breadcrumbs', true );
			if ( 'show' === $override ) {
				$enabled = true;
			} elseif ( 'hide' === $override ) {
				$enabled = false;
			}
		}

		return $enabled;
	}
endif;

if ( ! function_exists( 'blank_base_breadcrumbs' ) ) :
	/**
	 * Render the breadcrumb trail.
	 */
	function blank_base_breadcrumbs() {
		// Respect the Customizer toggle / per-post override and skip on the homepage.
		if ( ! blank_base_breadcrumbs_enabled() || is_front_page() ) {
			return;
		}

		$position = 1;
		$items    = array();

		// Always start with Home.
		$items[] = blank_base_breadcrumb_item( esc_html__( 'Home', 'blank-base' ), home_url( '/' ), $position++ );

		if ( is_home() ) {
			// Blog posts index (static front page in use).
			$items[] = blank_base_breadcrumb_item( single_post_title( '', false ), '', $position++, true );

		} elseif ( is_singular( 'post' ) ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$cat     = $categories[0];
				$items[] = blank_base_breadcrumb_item( $cat->name, get_category_link( $cat->term_id ), $position++ );
			}
			$items[] = blank_base_breadcrumb_item( get_the_title(), '', $position++, true );

		} elseif ( is_page() ) {
			$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
			foreach ( $ancestors as $ancestor ) {
				$items[] = blank_base_breadcrumb_item( get_the_title( $ancestor ), get_permalink( $ancestor ), $position++ );
			}
			$items[] = blank_base_breadcrumb_item( get_the_title(), '', $position++, true );

		} elseif ( is_singular() ) {
			$post_type = get_post_type_object( get_post_type() );
			if ( $post_type && $post_type->has_archive ) {
				$items[] = blank_base_breadcrumb_item( $post_type->labels->name, get_post_type_archive_link( get_post_type() ), $position++ );
			}
			$items[] = blank_base_breadcrumb_item( get_the_title(), '', $position++, true );

		} elseif ( is_category() || is_tag() || is_tax() ) {
			$items[] = blank_base_breadcrumb_item( single_term_title( '', false ), '', $position++, true );

		} elseif ( is_author() ) {
			$items[] = blank_base_breadcrumb_item( get_the_author(), '', $position++, true );

		} elseif ( is_search() ) {
			$items[] = blank_base_breadcrumb_item(
				/* translators: %s: search query. */
				sprintf( esc_html__( 'Search: %s', 'blank-base' ), get_search_query() ),
				'',
				$position++,
				true
			);

		} elseif ( is_404() ) {
			$items[] = blank_base_breadcrumb_item( esc_html__( 'Not Found', 'blank-base' ), '', $position++, true );

		} elseif ( is_archive() ) {
			$items[] = blank_base_breadcrumb_item( get_the_archive_title(), '', $position++, true );
		}

		// A trail with only "Home" adds no value.
		if ( count( $items ) < 2 ) {
			return;
		}

		echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'blank-base' ) . '">';
		echo '<ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">';
		echo implode( '<li class="breadcrumbs__sep" aria-hidden="true">&rsaquo;</li>', $items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</ol></nav>';
	}
endif;

if ( ! function_exists( 'blank_base_breadcrumb_item' ) ) :
	/**
	 * Build a single schema.org breadcrumb list item.
	 *
	 * @param string $title    The crumb label.
	 * @param string $url      The crumb URL (empty for the current item).
	 * @param int    $position 1-based position in the trail.
	 * @param bool   $current  Whether this is the current page.
	 * @return string
	 */
	function blank_base_breadcrumb_item( $title, $url, $position, $current = false ) {
		$title = wp_strip_all_tags( $title );

		$item  = '<li class="breadcrumbs__item' . ( $current ? ' is-current' : '' ) . '" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

		if ( $url && ! $current ) {
			$item .= '<a itemprop="item" href="' . esc_url( $url ) . '"><span itemprop="name">' . esc_html( $title ) . '</span></a>';
		} else {
			$item .= '<span itemprop="name"' . ( $current ? ' aria-current="page"' : '' ) . '>' . esc_html( $title ) . '</span>';
		}

		$item .= '<meta itemprop="position" content="' . absint( $position ) . '" />';
		$item .= '</li>';

		return $item;
	}
endif;
