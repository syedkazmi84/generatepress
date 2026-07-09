<?php
/**
 * Reusable template helpers: icons, meta, breadcrumbs, defaults.
 *
 * @package Bookwright
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the URL for a bundled asset image.
 */
function bookwright_img( $file ) {
	return esc_url( BOOKWRIGHT_URI . '/assets/images/' . ltrim( $file, '/' ) );
}

/**
 * Inline SVG icon set (stroke icons, 24x24 viewbox).
 */
function bookwright_icon( $name, $echo = true ) {
	$paths = array(
		'edit'      => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
		'book'      => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>',
		'design'    => '<circle cx="13.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="10.5" r="2.5"/><circle cx="8.5" cy="7.5" r="2.5"/><circle cx="6.5" cy="12.5" r="2.5"/><path d="M12 2a10 10 0 1 0 0 20 3 3 0 0 0 3-3 2 2 0 0 1 2-2h1a4 4 0 0 0 4-4 10 10 0 0 0-10-9Z"/>',
		'quill'     => '<path d="M3 21c3-3 6-3 9-6s5-6 9-12c-6 4-9 6-12 9s-3 6-6 9Z"/><path d="M3 21h6"/>',
		'megaphone' => '<path d="m3 11 18-5v12L3 14v-3Z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
		'print'     => '<path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>',
		'monitor'   => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/>',
		'mic'       => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10a7 7 0 0 0 14 0"/><path d="M12 17v4"/><path d="M8 21h8"/>',
		'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/>',
		'sparkle'   => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4"/><path d="m6.3 6.3 2.8 2.8M14.9 14.9l2.8 2.8M17.7 6.3l-2.8 2.8M9.1 14.9l-2.8 2.8"/>',
		'globe'     => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/>',
		'search'    => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
		'chart'     => '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>',
		'shield'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>',
		'check'     => '<path d="M20 6 9 17l-5-5"/>',
		'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'phone'     => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7 12.8 12.8 0 0 0 .7 2.8 2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4 12.8 12.8 0 0 0 2.8.7 2 2 0 0 1 1.7 2Z"/>',
		'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'clock'     => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'star'      => '<path d="m12 2 3 6.5 7 .9-5 4.9 1.2 7L12 18l-6.4 3.3L6.9 14 2 9.1l7-1Z"/>',
		'arrow'     => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'users'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1A4 4 0 0 1 16 11"/>',
		'award'     => '<circle cx="12" cy="8" r="6"/><path d="M8.2 13.9 7 22l5-3 5 3-1.2-8.1"/>',
		'twitter'   => '<path d="M22 4c-.8.5-1.7.8-2.6 1a4 4 0 0 0-7 2.7v1A9.7 9.7 0 0 1 4 4s-4 9 5 13a10 10 0 0 1-6 2c9 5 20 0 20-11.5 0-.3 0-.6-.1-.8A6.7 6.7 0 0 0 22 4Z"/>',
		'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.4A4 4 0 1 1 12.6 8 4 4 0 0 1 16 11.4Z"/><path d="M17.5 6.5h.01"/>',
		'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3Z"/>',
		'linkedin'  => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-13h4v1.5A6 6 0 0 1 16 8Z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
	);

	$path = isset( $paths[ $name ] ) ? $paths[ $name ] : $paths['book'];
	$svg  = '<svg class="bw-icon" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $path . '</svg>';

	if ( 'star' === $name || 'facebook' === $name || 'instagram' === $name || 'twitter' === $name || 'linkedin' === $name ) {
		// keep stroke for social; star uses fill toggle below.
	}

	if ( $echo ) {
		echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup.
		return;
	}
	return $svg;
}

/**
 * Render a row of star glyphs.
 */
function bookwright_stars( $count = 5 ) {
	echo '<span class="bw-stars" aria-hidden="true">' . esc_html( str_repeat( '★', (int) $count ) ) . '</span>';
}

/**
 * Post meta line for blog listings.
 */
function bookwright_post_meta() {
	$cats = get_the_category();
	if ( $cats ) {
		echo '<span class="bw-article__cat">' . esc_html( $cats[0]->name ) . '</span>';
	}
	echo '<span>' . esc_html( get_the_date() ) . '</span>';
	echo '<span>' . esc_html( bookwright_reading_time() ) . '</span>';
}

/**
 * Rough reading time estimate.
 */
function bookwright_reading_time() {
	$words   = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	/* translators: %d: number of minutes. */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'bookwright' ), $minutes );
}

/**
 * Simple breadcrumb trail.
 */
function bookwright_breadcrumb() {
	if ( is_front_page() ) {
		return;
	}
	echo '<nav class="bw-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'bookwright' ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'bookwright' ) . '</a> / ';
	if ( is_singular( 'post' ) ) {
		echo '<a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'Journal', 'bookwright' ) . '</a> / ';
		echo esc_html( wp_trim_words( get_the_title(), 6 ) );
	} elseif ( is_page() ) {
		echo esc_html( get_the_title() );
	} elseif ( is_singular( 'book' ) ) {
		echo esc_html( get_the_title() );
	} elseif ( is_archive() ) {
		echo wp_kses_post( get_the_archive_title() );
	} elseif ( is_search() ) {
		echo esc_html__( 'Search results', 'bookwright' );
	} else {
		echo esc_html( wp_title( '', false ) );
	}
	echo '</nav>';
}

/**
 * Numeric pagination wrapper.
 */
function bookwright_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'class'     => 'bw-pagination',
		)
	);
}

/**
 * Featured image with a graceful SVG fallback.
 */
function bookwright_thumbnail( $size = 'bookwright-card', $fallback = 'placeholder-wide.svg' ) {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( $size, array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) );
	} else {
		echo '<img src="' . bookwright_img( $fallback ) . '" alt="" loading="lazy" />';
	}
}

/**
 * Fetch a Customizer contact detail with sane default.
 */
function bookwright_option( $key, $default = '' ) {
	$value = get_theme_mod( $key, $default );
	return $value ? $value : $default;
}
