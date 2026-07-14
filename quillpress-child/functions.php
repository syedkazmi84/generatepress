<?php
/**
 * Quill & Press — GeneratePress child theme.
 *
 * A complete book-publishing-services website in a box. The presentation lives
 * in assets/css/main.css and the one-click demo builder lives in /inc.
 *
 * @package Quill_Press
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'QUILLPRESS_VERSION', '1.0.0' );
define( 'QUILLPRESS_DIR', get_stylesheet_directory() );
define( 'QUILLPRESS_URI', get_stylesheet_directory_uri() );

/**
 * Enqueue parent + child styles, web fonts and the design system.
 */
function quillpress_enqueue_assets() {
	// Google Fonts — literary serif for headings, clean sans for body.
	wp_enqueue_style(
		'quillpress-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	// Parent theme stylesheet.
	wp_enqueue_style(
		'generatepress-style',
		get_template_directory_uri() . '/style.css',
		array( 'quillpress-fonts' ),
		QUILLPRESS_VERSION
	);

	// Child theme header (kept minimal).
	wp_enqueue_style(
		'quillpress-style',
		get_stylesheet_uri(),
		array( 'generatepress-style' ),
		QUILLPRESS_VERSION
	);

	// The full design system.
	wp_enqueue_style(
		'quillpress-main',
		QUILLPRESS_URI . '/assets/css/main.css',
		array( 'quillpress-style' ),
		QUILLPRESS_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'quillpress_enqueue_assets', 20 );

/**
 * Load the same design system inside the block editor so pages look accurate
 * while editing.
 */
function quillpress_editor_assets() {
	add_editor_style( 'assets/css/main.css' );
}
add_action( 'after_setup_theme', 'quillpress_editor_assets' );

/**
 * Register the "Primary" menu location (GeneratePress provides it, but we make
 * sure a footer location also exists for the imported footer menu).
 */
function quillpress_register_menus() {
	register_nav_menus(
		array(
			'quillpress_footer' => __( 'Footer Menu', 'quillpress' ),
		)
	);
}
add_action( 'after_setup_theme', 'quillpress_register_menus' );

/**
 * Expose the theme's image directory to inline block markup / templates.
 */
function quillpress_img( $file ) {
	return QUILLPRESS_URI . '/assets/images/' . ltrim( $file, '/' );
}

/**
 * Hide the automatic page title on imported landing pages (they carry their own
 * hero heading). Uses a per-page flag set by the importer.
 */
function quillpress_show_title( $show ) {
	if ( is_singular( 'page' ) && get_post_meta( get_the_ID(), '_qp_hide_title', true ) ) {
		return false;
	}
	return $show;
}
add_filter( 'generate_show_title', 'quillpress_show_title' );

/**
 * Add a helper body class so the theme's own footer credit is replaced by ours.
 */
function quillpress_body_class( $classes ) {
	$classes[] = 'qp-has-custom-footer';
	return $classes;
}
add_filter( 'body_class', 'quillpress_body_class' );

/**
 * Render the branded site footer (brand blurb + footer menu + copyright).
 */
function quillpress_render_footer() {
	$mark = quillpress_img( 'logo-mark.svg' );
	echo '<footer class="qp-site-footer" role="contentinfo">';
	echo '<div class="qp-footer-inner">';
	echo '<div class="qp-footer-brand"><div class="qp-fmark"><img src="' . esc_url( $mark ) . '" alt="">Quill &amp; Press</div>';
	echo '<p>Full-service book publishing for authors who want beautiful books — and keep every right and royalty.</p></div>';
	echo '<div class="qp-footer-nav"><h4>Explore</h4>';
	if ( has_nav_menu( 'quillpress_footer' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'quillpress_footer',
				'container'      => false,
				'menu_class'     => 'qp-footer-list',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
	}
	echo '</div></div>';
	echo '<div class="qp-footer-bar">&copy; ' . esc_html( gmdate( 'Y' ) ) . ' Quill &amp; Press. Crafted for authors &middot; Powered by GeneratePress.</div>';
	echo '</footer>';
}
add_action( 'wp_footer', 'quillpress_render_footer', 5 );

/**
 * Allow the bundled SVG artwork to live in the media library.
 */
function quillpress_allow_svg( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'quillpress_allow_svg' );

function quillpress_fix_svg_filetype( $data, $file, $filename, $mimes ) {
	if ( '.svg' === strtolower( substr( $filename, -4 ) ) ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'quillpress_fix_svg_filetype', 10, 4 );

// One-click demo importer + content library.
require_once QUILLPRESS_DIR . '/inc/block-helpers.php';
require_once QUILLPRESS_DIR . '/inc/demo-content.php';
require_once QUILLPRESS_DIR . '/inc/class-demo-importer.php';

Quill_Press_Demo_Importer::instance();
