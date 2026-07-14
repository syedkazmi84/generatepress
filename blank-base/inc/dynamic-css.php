<?php
/**
 * Dynamic CSS and font maps for Blank Base.
 *
 * Translates the Customizer "Pro" options (typography, colors, container
 * width) into inline CSS and font enqueues at runtime, so no design choice is
 * hard-coded.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Font choices shown in the Customizer typography selects.
 *
 * @return array
 */
function blank_base_font_choices() {
	return array(
		'system'    => esc_html__( 'System (default sans)', 'blank-base' ),
		'serif'     => esc_html__( 'Georgia (default serif)', 'blank-base' ),
		'mono'      => esc_html__( 'Monospace', 'blank-base' ),
		'inter'     => 'Inter',
		'poppins'   => 'Poppins',
		'roboto'    => 'Roboto',
		'montserrat' => 'Montserrat',
		'playfair'  => 'Playfair Display',
		'lora'      => 'Lora',
		'merriweather' => 'Merriweather',
	);
}

/**
 * Map a font key to a CSS font-family stack.
 *
 * @param string $key Font key.
 * @return string
 */
function blank_base_font_stack( $key ) {
	$stacks = array(
		'system'       => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
		'serif'        => 'Georgia, "Times New Roman", serif',
		'mono'         => 'SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',
		'inter'        => '"Inter", sans-serif',
		'poppins'      => '"Poppins", sans-serif',
		'roboto'       => '"Roboto", sans-serif',
		'montserrat'   => '"Montserrat", sans-serif',
		'playfair'     => '"Playfair Display", Georgia, serif',
		'lora'         => '"Lora", Georgia, serif',
		'merriweather' => '"Merriweather", Georgia, serif',
	);

	return isset( $stacks[ $key ] ) ? $stacks[ $key ] : $stacks['system'];
}

/**
 * Map a font key to its Google Fonts family query segment, or empty if the
 * font is a locally available system stack.
 *
 * @param string $key Font key.
 * @return string
 */
function blank_base_google_font_param( $key ) {
	$fonts = array(
		'inter'        => 'Inter:wght@400;500;600;700',
		'poppins'      => 'Poppins:wght@400;500;600;700',
		'roboto'       => 'Roboto:wght@400;500;700',
		'montserrat'   => 'Montserrat:wght@400;500;600;700',
		'playfair'     => 'Playfair+Display:wght@400;600;700',
		'lora'         => 'Lora:wght@400;500;600;700',
		'merriweather' => 'Merriweather:wght@400;700',
	);

	return isset( $fonts[ $key ] ) ? $fonts[ $key ] : '';
}

/**
 * Return the color presets: slug => array( accent, hover ).
 *
 * @return array
 */
function blank_base_color_presets() {
	return array(
		'default' => array( '#2563eb', '#1d4ed8' ),
		'ocean'   => array( '#0891b2', '#0e7490' ),
		'forest'  => array( '#16a34a', '#15803d' ),
		'sunset'  => array( '#ea580c', '#c2410c' ),
		'royal'   => array( '#7c3aed', '#6d28d9' ),
		'slate'   => array( '#334155', '#1e293b' ),
		'custom'  => array( '', '' ),
	);
}

/**
 * Preset labels for the Customizer select.
 *
 * @return array
 */
function blank_base_color_preset_choices() {
	return array(
		'default' => esc_html__( 'Classic Blue (default)', 'blank-base' ),
		'ocean'   => esc_html__( 'Ocean', 'blank-base' ),
		'forest'  => esc_html__( 'Forest', 'blank-base' ),
		'sunset'  => esc_html__( 'Sunset', 'blank-base' ),
		'royal'   => esc_html__( 'Royal', 'blank-base' ),
		'slate'   => esc_html__( 'Slate', 'blank-base' ),
		'custom'  => esc_html__( 'Custom (use accent color)', 'blank-base' ),
	);
}

/**
 * Resolve the effective accent + hover colors from the preset and the custom
 * accent control.
 *
 * @return array [ accent, hover ]
 */
function blank_base_effective_accent() {
	$preset  = get_theme_mod( 'blank_base_color_preset', 'default' );
	$presets = blank_base_color_presets();

	if ( 'custom' === $preset ) {
		$accent = get_theme_mod( 'blank_base_accent_color', '#2563eb' );
		return array( $accent, $accent );
	}

	if ( isset( $presets[ $preset ] ) ) {
		return $presets[ $preset ];
	}

	return $presets['default'];
}

/**
 * Build the inline CSS derived from Customizer settings.
 *
 * @return string
 */
function blank_base_get_dynamic_css() {
	$body_font = get_theme_mod( 'blank_base_body_font', 'system' );
	$head_font = get_theme_mod( 'blank_base_heading_font', 'serif' );
	$font_size = absint( get_theme_mod( 'blank_base_base_font_size', 16 ) );
	$width     = absint( get_theme_mod( 'blank_base_container_width', 1200 ) );

	list( $accent, $hover ) = blank_base_effective_accent();

	$root = ':root{';
	$root .= '--bb-font-body:' . blank_base_font_stack( $body_font ) . ';';
	$root .= '--bb-font-headings:' . blank_base_font_stack( $head_font ) . ';';

	if ( $width >= 640 && $width <= 1600 ) {
		$root .= '--bb-wide-width:' . $width . 'px;';
	}

	if ( $accent ) {
		$root .= '--bb-color-link:' . sanitize_hex_color( $accent ) . ';';
	}
	if ( $hover ) {
		$root .= '--bb-color-link-hover:' . sanitize_hex_color( $hover ) . ';';
	}
	$root .= '}';

	$css = $root;

	if ( $font_size >= 12 && $font_size <= 24 && 16 !== $font_size ) {
		$css .= 'body{font-size:' . $font_size . 'px;}';
	}

	$logo_height = absint( get_theme_mod( 'blank_base_logo_max_height', 60 ) );
	if ( $logo_height >= 20 && $logo_height <= 200 ) {
		$css .= '.custom-logo{max-height:' . $logo_height . 'px;width:auto;}';
	}

	return $css;
}

/**
 * Enqueue Google Fonts when a hosted font is selected.
 */
function blank_base_enqueue_google_fonts() {
	$families = array();

	foreach ( array( get_theme_mod( 'blank_base_body_font', 'system' ), get_theme_mod( 'blank_base_heading_font', 'serif' ) ) as $font ) {
		$param = blank_base_google_font_param( $font );
		if ( $param ) {
			$families[ $param ] = true;
		}
	}

	if ( empty( $families ) ) {
		return;
	}

	// The family params already contain the Google-Fonts syntax (e.g. "Inter:wght@400;600"),
	// so they are concatenated directly rather than URL-encoded.
	$url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', array_keys( $families ) ) . '&display=swap';

	wp_enqueue_style( 'blank-base-google-fonts', esc_url_raw( $url ), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
}
add_action( 'wp_enqueue_scripts', 'blank_base_enqueue_google_fonts' );

/**
 * Whether a Google-hosted font is currently selected for body or headings.
 *
 * @return bool
 */
function blank_base_uses_google_fonts() {
	foreach ( array( get_theme_mod( 'blank_base_body_font', 'system' ), get_theme_mod( 'blank_base_heading_font', 'serif' ) ) as $font ) {
		if ( blank_base_google_font_param( $font ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Add preconnect hints for Google Fonts so the stylesheet and font files start
 * downloading a round-trip sooner. Only emitted when a hosted font is in use.
 *
 * Note: serving fonts from Google exposes visitor IP addresses to Google, which
 * can be a GDPR concern in the EU. Selecting a system-font stack (the default)
 * avoids any third-party request; self-hosting is the privacy-friendly upgrade.
 *
 * @param array  $hints         Existing resource hints for the relation type.
 * @param string $relation_type Current relation type (e.g. "preconnect").
 * @return array
 */
function blank_base_google_fonts_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' !== $relation_type || ! blank_base_uses_google_fonts() ) {
		return $hints;
	}

	$hints[] = 'https://fonts.googleapis.com';
	$hints[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $hints;
}
add_filter( 'wp_resource_hints', 'blank_base_google_fonts_resource_hints', 10, 2 );

/**
 * Attach the generated CSS to the main stylesheet.
 *
 * Combines the base dynamic CSS (fonts, container width, logo height) with the
 * extended color/typography/layout managers from inc/css-output.php.
 */
function blank_base_output_dynamic_css() {
	$css = blank_base_get_dynamic_css();

	if ( function_exists( 'blank_base_generate_extended_css' ) ) {
		$css .= blank_base_generate_extended_css();
	}

	wp_add_inline_style( 'blank-base-style', $css );
}
add_action( 'wp_enqueue_scripts', 'blank_base_output_dynamic_css', 20 );
