<?php
/**
 * Professional design system for a Book Publishing Services website.
 * Writes GeneratePress Customizer settings: Global Colors, Font Manager
 * (Google Fonts), and a full responsive Typography scale.
 *
 * Run with: wp eval-file apply-design.php --allow-root
 */

if ( ! function_exists( 'generate_get_defaults' ) ) {
	WP_CLI::error( 'GeneratePress not active.' );
}

$defaults = generate_get_defaults();
$settings = get_option( 'generate_settings', array() );
$settings = wp_parse_args( (array) $settings, $defaults );

/* -------------------------------------------------------------------------
 * 1. GLOBAL COLORS  (Appearance ▸ Customize ▸ Colors ▸ Global Colors)
 *    The first 7 slugs are GeneratePress core roles (drive text/link/bg).
 *    accent-2 / accent-3 are decorative brand colors -> --accent-2 etc.
 *    Palette concept: ink + paper + antique gold — editorial & premium.
 * ---------------------------------------------------------------------- */
$settings['global_colors'] = array(
	array( 'name' => 'Contrast',      'slug' => 'contrast',   'color' => '#20262E' ), // ink — body text / headings
	array( 'name' => 'Contrast 2',    'slug' => 'contrast-2', 'color' => '#5B6470' ), // muted slate — meta/secondary
	array( 'name' => 'Contrast 3',    'slug' => 'contrast-3', 'color' => '#D8D2C6' ), // warm grey — borders/dividers
	array( 'name' => 'Base',          'slug' => 'base',       'color' => '#F1ECE2' ), // cream — alternating sections
	array( 'name' => 'Base 2',        'slug' => 'base-2',     'color' => '#FAF8F3' ), // soft paper — page background
	array( 'name' => 'Base 3',        'slug' => 'base-3',     'color' => '#FFFFFF' ), // white — cards / content
	array( 'name' => 'Accent',        'slug' => 'accent',     'color' => '#1F3A5F' ), // ink navy — links & primary buttons
	array( 'name' => 'Accent 2 Gold', 'slug' => 'accent-2',   'color' => '#B0803A' ), // antique gold — decorative accent
	array( 'name' => 'Burgundy',      'slug' => 'accent-3',   'color' => '#7A2E39' ), // burgundy — secondary accent
);

/* -------------------------------------------------------------------------
 * 2. CORE COLOR ROLES  (reference the global-color CSS vars)
 * ---------------------------------------------------------------------- */
$settings['background_color']   = 'var(--base-2)';   // paper page bg
$settings['text_color']         = 'var(--contrast)'; // ink body text
$settings['link_color']         = 'var(--accent)';   // navy links
$settings['link_color_hover']   = 'var(--accent-2)'; // gold on hover
$settings['link_color_visited'] = '';
$settings['underline_links']    = 'hover';

/* Container / layout — professional editorial measure */
$settings['container_width']         = '1200';
$settings['content_layout_setting']  = 'separate-containers';
$settings['use_dynamic_typography']  = true;

/* -------------------------------------------------------------------------
 * 3. FONT MANAGER  (Appearance ▸ Customize ▸ Typography ▸ Font Manager)
 *    Playfair Display (display serif) for headings — publishing/editorial.
 *    Inter (humanist sans) for body — clean & highly legible.
 * ---------------------------------------------------------------------- */
$settings['font_manager'] = array(
	array(
		'fontFamily'          => 'Playfair Display',
		'googleFont'          => true,
		'googleFontApi'       => 1,
		'googleFontVariants'  => 'regular,500,600,700,italic,600italic',
		'googleFontCategory'  => 'serif',
	),
	array(
		'fontFamily'          => 'Inter',
		'googleFont'          => true,
		'googleFontApi'       => 1,
		'googleFontVariants'  => '300,regular,500,600,700',
		'googleFontCategory'  => 'sans-serif',
	),
);

/* -------------------------------------------------------------------------
 * 4. TYPOGRAPHY SCALE  (Appearance ▸ Customize ▸ Typography)
 *    Responsive px scale: desktop / tablet / mobile.
 * ---------------------------------------------------------------------- */
$typo_defaults = GeneratePress_Typography::get_defaults();

$rule = function( $overrides ) use ( $typo_defaults ) {
	$item = wp_parse_args( $overrides, $typo_defaults );
	$item['module'] = 'core';
	return $item;
};

$settings['typography'] = array(

	// Body — Inter, comfortable reading measure.
	$rule( array(
		'selector'          => 'body',
		'fontFamily'        => 'Inter',
		'fontWeight'        => '400',
		'fontSize'          => 18,
		'fontSizeTablet'    => 17,
		'fontSizeMobile'    => 16,
		'fontSizeUnit'      => 'px',
		'lineHeight'        => 1.7,
		'lineHeightUnit'    => '',
		'marginBottom'      => 1.5,
		'marginBottomUnit'  => 'em',
	) ),

	// All headings — Playfair Display base styling.
	$rule( array(
		'selector'        => 'all-headings',
		'fontFamily'      => 'Playfair Display',
		'fontWeight'      => '700',
		'textTransform'   => 'none',
		'lineHeight'      => 1.2,
		'lineHeightUnit'  => '',
		'letterSpacing'   => -0.3,
		'letterSpacingUnit' => 'px',
	) ),

	// H1 — hero / page titles.
	$rule( array(
		'selector'        => 'h1',
		'fontFamily'      => 'Playfair Display',
		'fontWeight'      => '700',
		'fontSize'        => 46,
		'fontSizeTablet'  => 38,
		'fontSizeMobile'  => 32,
		'fontSizeUnit'    => 'px',
		'lineHeight'      => 1.15,
		'lineHeightUnit'  => '',
		'marginBottom'    => 20,
		'marginBottomUnit'=> 'px',
	) ),

	// H2 — section titles.
	$rule( array(
		'selector'        => 'h2',
		'fontFamily'      => 'Playfair Display',
		'fontWeight'      => '600',
		'fontSize'        => 36,
		'fontSizeTablet'  => 30,
		'fontSizeMobile'  => 26,
		'fontSizeUnit'    => 'px',
		'lineHeight'      => 1.2,
		'lineHeightUnit'  => '',
		'marginBottom'    => 16,
		'marginBottomUnit'=> 'px',
	) ),

	// H3 — sub-sections / card titles.
	$rule( array(
		'selector'        => 'h3',
		'fontFamily'      => 'Playfair Display',
		'fontWeight'      => '600',
		'fontSize'        => 27,
		'fontSizeTablet'  => 24,
		'fontSizeMobile'  => 22,
		'fontSizeUnit'    => 'px',
		'lineHeight'      => 1.3,
		'lineHeightUnit'  => '',
		'marginBottom'    => 12,
		'marginBottomUnit'=> 'px',
	) ),

	// H4 — minor headings (Inter for hierarchy contrast).
	$rule( array(
		'selector'        => 'h4',
		'fontFamily'      => 'Inter',
		'fontWeight'      => '600',
		'fontSize'        => 21,
		'fontSizeMobile'  => 19,
		'fontSizeUnit'    => 'px',
		'lineHeight'      => 1.4,
		'lineHeightUnit'  => '',
		'letterSpacing'   => 0,
		'letterSpacingUnit' => 'px',
	) ),

	// Single post/page content title.
	$rule( array(
		'selector'        => 'single-content-title',
		'fontFamily'      => 'Playfair Display',
		'fontWeight'      => '700',
		'fontSize'        => 44,
		'fontSizeTablet'  => 36,
		'fontSizeMobile'  => 30,
		'fontSizeUnit'    => 'px',
		'lineHeight'      => 1.15,
		'lineHeightUnit'  => '',
	) ),

	// Primary navigation menu items — Inter, refined.
	$rule( array(
		'selector'        => 'primary-menu-items',
		'fontFamily'      => 'Inter',
		'fontWeight'      => '500',
		'fontSize'        => 16,
		'fontSizeUnit'    => 'px',
		'letterSpacing'   => 0.2,
		'letterSpacingUnit' => 'px',
	) ),

	// Buttons — Inter, confident.
	$rule( array(
		'selector'        => 'buttons',
		'fontFamily'      => 'Inter',
		'fontWeight'      => '600',
		'fontSize'        => 16,
		'fontSizeUnit'    => 'px',
		'textTransform'   => 'none',
		'letterSpacing'   => 0.3,
		'letterSpacingUnit' => 'px',
	) ),

	// Widget / footer widget titles.
	$rule( array(
		'selector'        => 'widget-titles',
		'fontFamily'      => 'Playfair Display',
		'fontWeight'      => '600',
		'fontSize'        => 20,
		'fontSizeUnit'    => 'px',
		'lineHeight'      => 1.3,
		'lineHeightUnit'  => '',
	) ),

	// Site description / tagline.
	$rule( array(
		'selector'        => 'site-description',
		'fontFamily'      => 'Inter',
		'fontWeight'      => '400',
		'fontSize'        => 15,
		'fontSizeUnit'    => 'px',
		'letterSpacing'   => 0.3,
		'letterSpacingUnit' => 'px',
	) ),
);

/* Google font display strategy for performance. */
$settings['google_font_display'] = 'swap';

update_option( 'generate_settings', $settings );

/* -------------------------------------------------------------------------
 * 5. Secondary color options (GP Premium Colors module) — buttons & nav.
 * ---------------------------------------------------------------------- */
$color_defaults = function_exists( 'generate_get_color_defaults' ) ? generate_get_color_defaults() : array();
$colors = get_option( 'generate_settings', array() );
// GP stores button/nav colors within the same generate_settings via Premium;
// ensure sensible, on-brand hover states referencing our vars.
$nav = array(
	'navigation_background_color'        => 'var(--base-3)',
	'navigation_text_color'              => 'var(--contrast)',
	'navigation_text_hover_color'        => 'var(--accent)',
	'navigation_text_current_color'      => 'var(--accent)',
	'site_title_color'                   => 'var(--contrast)',
	'site_tagline_color'                 => 'var(--contrast-2)',
	'form_button_background_color'       => 'var(--accent)',
	'form_button_background_color_hover' => 'var(--accent-3)',
	'form_button_text_color'             => '#ffffff',
	'form_button_text_color_hover'       => '#ffffff',
);
foreach ( $nav as $k => $v ) {
	$colors[ $k ] = $v;
}
update_option( 'generate_settings', $colors );

WP_CLI::success( 'Design system applied: '
	. count( $colors['global_colors'] ) . ' global colors, '
	. count( $colors['font_manager'] ) . ' fonts, '
	. count( $colors['typography'] ) . ' typography rules.' );
