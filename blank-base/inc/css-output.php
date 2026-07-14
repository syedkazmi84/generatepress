<?php
/**
 * Dynamic CSS output engine for Blank Base.
 *
 * Turns the Customizer color, typography and layout options into inline CSS.
 * Every generator is additive: an option left at its empty/zero default emits
 * no CSS, so the base stylesheet stays in control — the same "only override
 * what you change" model GeneratePress uses.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Append a `property:value;` declaration when the value is non-empty.
 *
 * @param string $property CSS property.
 * @param string $value    Value (already sanitized by the caller).
 * @return string
 */
function blank_base_css_line( $property, $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	return $property . ':' . $value . ';';
}

/**
 * Wrap declarations in a selector, or return an empty string if there are none.
 *
 * @param string $selector CSS selector.
 * @param string $body     Declaration block contents.
 * @return string
 */
function blank_base_css_rule( $selector, $body ) {
	$body = trim( $body );
	if ( '' === $body ) {
		return '';
	}
	return $selector . '{' . $body . '}';
}

/**
 * Read a color theme mod and validate it (hex only).
 *
 * @param string $key Theme mod key.
 * @return string Sanitized hex color or empty string.
 */
function blank_base_color_mod( $key ) {
	$value = get_theme_mod( $key, '' );
	if ( ! $value ) {
		return '';
	}
	$value = sanitize_hex_color( $value );
	return $value ? $value : '';
}

/**
 * Generate the color-manager CSS.
 *
 * @return string
 */
function blank_base_generate_colors_css() {
	$css = '';

	/* ---- Global link colors (fall through to the accent preset). ---- */
	$link       = blank_base_color_mod( 'blank_base_link_color' );
	$link_hover = blank_base_color_mod( 'blank_base_link_hover_color' );
	$content_bg = blank_base_color_mod( 'blank_base_content_bg' );
	$content_tx = blank_base_color_mod( 'blank_base_content_text' );

	$root = '';
	$root .= blank_base_css_line( '--bb-color-link', $link );
	$root .= blank_base_css_line( '--bb-color-link-hover', $link_hover ? $link_hover : $link );
	$root .= blank_base_css_line( '--bb-color-background', $content_bg );
	$root .= blank_base_css_line( '--bb-color-text', $content_tx );
	$css  .= blank_base_css_rule( ':root', $root );

	/* ---- Header. ---- */
	$header = '';
	$header .= blank_base_css_line( 'background', blank_base_color_mod( 'blank_base_header_bg' ) );
	$header .= blank_base_css_line( 'color', blank_base_color_mod( 'blank_base_header_text' ) );
	$css    .= blank_base_css_rule( '.site-header', $header );

	$title_color = blank_base_color_mod( 'blank_base_site_title_color' );
	$css        .= blank_base_css_rule( '.site-title a', blank_base_css_line( 'color', $title_color ) );

	$header_link = blank_base_color_mod( 'blank_base_header_link' );
	$css        .= blank_base_css_rule( '.site-header a', blank_base_css_line( 'color', $header_link ) );
	$css        .= blank_base_css_rule( '.site-header a:hover,.site-header a:focus', blank_base_css_line( 'color', blank_base_color_mod( 'blank_base_header_link_hover' ) ) );

	/* ---- Primary navigation. ---- */
	$nav_bg   = blank_base_color_mod( 'blank_base_nav_bg' );
	$nav_text = blank_base_color_mod( 'blank_base_nav_text' );

	$css .= blank_base_css_rule(
		'.main-navigation,.navigation-bar',
		blank_base_css_line( 'background', $nav_bg )
	);
	$css .= blank_base_css_rule(
		'.main-navigation a',
		blank_base_css_line( 'color', $nav_text )
	);

	$nav_hover_bg = blank_base_color_mod( 'blank_base_nav_hover_bg' );
	$nav_hover_tx = blank_base_color_mod( 'blank_base_nav_hover_text' );
	$css         .= blank_base_css_rule(
		'.main-navigation a:hover,.main-navigation a:focus,.main-navigation .current-menu-item > a',
		blank_base_css_line( 'background', $nav_hover_bg ) . blank_base_css_line( 'color', $nav_hover_tx )
	);

	/* ---- Sub-menus. ---- */
	$css .= blank_base_css_rule(
		'.main-navigation ul ul',
		blank_base_css_line( 'background', blank_base_color_mod( 'blank_base_submenu_bg' ) )
	);
	$css .= blank_base_css_rule(
		'.main-navigation ul ul a',
		blank_base_css_line( 'color', blank_base_color_mod( 'blank_base_submenu_text' ) )
	);

	/* ---- Buttons. ---- */
	$btn_bg = blank_base_color_mod( 'blank_base_button_bg' );
	$btn_tx = blank_base_color_mod( 'blank_base_button_text' );
	$css   .= blank_base_css_rule(
		'button,input[type="button"],input[type="reset"],input[type="submit"],.wp-block-button__link,.button',
		blank_base_css_line( 'background', $btn_bg ) . blank_base_css_line( 'color', $btn_tx )
	);
	$css .= blank_base_css_rule(
		'button:hover,input[type="submit"]:hover,.wp-block-button__link:hover,.button:hover,button:focus,input[type="submit"]:focus',
		blank_base_css_line( 'background', blank_base_color_mod( 'blank_base_button_bg_hover' ) ) . blank_base_css_line( 'color', blank_base_color_mod( 'blank_base_button_text_hover' ) )
	);

	/* ---- Footer widgets. ---- */
	$fw = '';
	$fw .= blank_base_css_line( 'background', blank_base_color_mod( 'blank_base_footer_widget_bg' ) );
	$fw .= blank_base_css_line( 'color', blank_base_color_mod( 'blank_base_footer_widget_text' ) );
	$css .= blank_base_css_rule( '.footer-widgets', $fw );
	$css .= blank_base_css_rule( '.footer-widgets a', blank_base_css_line( 'color', blank_base_color_mod( 'blank_base_footer_widget_link' ) ) );

	/* ---- Footer bar. ---- */
	$fb = '';
	$fb .= blank_base_css_line( 'background', blank_base_color_mod( 'blank_base_footer_bar_bg' ) );
	$fb .= blank_base_css_line( 'color', blank_base_color_mod( 'blank_base_footer_bar_text' ) );
	$css .= blank_base_css_rule( '.footer-bar,.site-footer', $fb );
	$css .= blank_base_css_rule( '.footer-bar a', blank_base_css_line( 'color', blank_base_color_mod( 'blank_base_footer_bar_link' ) ) );

	return $css;
}

/**
 * Read a positive integer theme mod, returning 0 when unset.
 *
 * @param string $key Theme mod key.
 * @return int
 */
function blank_base_int_mod( $key ) {
	return absint( get_theme_mod( $key, 0 ) );
}

/**
 * Generate the typography-manager CSS.
 *
 * @return string
 */
function blank_base_generate_typography_css() {
	$css = '';

	// Body weight and line-height.
	$body_weight = get_theme_mod( 'blank_base_body_weight', '' );
	$body_lh     = get_theme_mod( 'blank_base_body_line_height', '' );
	$body        = '';
	if ( $body_weight && is_numeric( $body_weight ) ) {
		$body .= blank_base_css_line( 'font-weight', absint( $body_weight ) );
	}
	if ( $body_lh && is_numeric( $body_lh ) ) {
		$body .= blank_base_css_line( 'line-height', (float) $body_lh );
	}
	$css .= blank_base_css_rule( 'body', $body );

	// Heading weight, transform and line-height.
	$h_weight    = get_theme_mod( 'blank_base_heading_weight', '' );
	$h_transform = get_theme_mod( 'blank_base_heading_transform', 'none' );
	$h_lh        = get_theme_mod( 'blank_base_heading_line_height', '' );
	$headings    = '';
	if ( $h_weight && is_numeric( $h_weight ) ) {
		$headings .= blank_base_css_line( 'font-weight', absint( $h_weight ) );
	}
	if ( $h_transform && 'none' !== $h_transform ) {
		$headings .= blank_base_css_line( 'text-transform', sanitize_key( $h_transform ) );
	}
	if ( $h_lh && is_numeric( $h_lh ) ) {
		$headings .= blank_base_css_line( 'line-height', (float) $h_lh );
	}
	$css .= blank_base_css_rule( 'h1,h2,h3,h4,h5,h6', $headings );

	// Per-heading font sizes (px). 0 = keep the fluid default.
	$heading_sizes = array(
		'h1' => 'blank_base_h1_size',
		'h2' => 'blank_base_h2_size',
		'h3' => 'blank_base_h3_size',
		'h4' => 'blank_base_h4_size',
		'h5' => 'blank_base_h5_size',
		'h6' => 'blank_base_h6_size',
	);
	foreach ( $heading_sizes as $tag => $key ) {
		$size = blank_base_int_mod( $key );
		if ( $size >= 8 && $size <= 120 ) {
			$css .= blank_base_css_rule( $tag, blank_base_css_line( 'font-size', $size . 'px' ) );
		}
	}

	// Site title size.
	$title_size = blank_base_int_mod( 'blank_base_site_title_size' );
	if ( $title_size >= 12 && $title_size <= 80 ) {
		$css .= blank_base_css_rule( '.site-title', blank_base_css_line( 'font-size', $title_size . 'px' ) );
	}

	// Navigation typography.
	$nav_size      = blank_base_int_mod( 'blank_base_nav_font_size' );
	$nav_weight    = get_theme_mod( 'blank_base_nav_font_weight', '' );
	$nav_transform = get_theme_mod( 'blank_base_nav_transform', 'none' );
	$nav           = '';
	if ( $nav_size >= 10 && $nav_size <= 32 ) {
		$nav .= blank_base_css_line( 'font-size', $nav_size . 'px' );
	}
	if ( $nav_weight && is_numeric( $nav_weight ) ) {
		$nav .= blank_base_css_line( 'font-weight', absint( $nav_weight ) );
	}
	if ( $nav_transform && 'none' !== $nav_transform ) {
		$nav .= blank_base_css_line( 'text-transform', sanitize_key( $nav_transform ) );
	}
	$css .= blank_base_css_rule( '.main-navigation a', $nav );

	return $css;
}

/**
 * Generate the layout CSS (container widths, sidebar widths, content layout).
 *
 * @return string
 */
function blank_base_generate_layout_css() {
	$css = '';

	// Sidebar column width (single-sidebar layouts only; both-sidebars keeps
	// its own proportions from style.css).
	$sidebar_width = blank_base_int_mod( 'blank_base_sidebar_width' );
	if ( $sidebar_width >= 15 && $sidebar_width <= 50 ) {
		// Drive the single-sidebar column width through the custom property that
		// gp-style.css reads. Using a variable avoids a specificity / stylesheet
		// order fight with the skin, and the content column (flex:1 1 0) fills
		// whatever width the sidebar leaves.
		$css .= blank_base_css_rule( ':root', blank_base_css_line( '--bb-sidebar-w', $sidebar_width . '%' ) );
	}

	// Full-width content container removes the max-width.
	if ( 'full-width' === blank_base_content_layout() ) {
		$css .= blank_base_css_rule( '.container-full-width .site-content', blank_base_css_line( 'max-width', 'none' ) );
	}

	return $css;
}

/**
 * Combine every generator into one stylesheet fragment.
 *
 * @return string
 */
function blank_base_generate_extended_css() {
	return blank_base_generate_colors_css()
		. blank_base_generate_typography_css()
		. blank_base_generate_layout_css();
}
