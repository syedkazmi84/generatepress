<?php
/**
 * Bookwright functions and definitions.
 *
 * @package Bookwright
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOOKWRIGHT_VERSION', '1.1.0' );
// Bump this when the bundled demo content changes so existing sites refresh it.
define( 'BOOKWRIGHT_CONTENT_VERSION', '4' );
define( 'BOOKWRIGHT_DIR', get_template_directory() );
define( 'BOOKWRIGHT_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function bookwright_setup() {
	load_theme_textdomain( 'bookwright', BOOKWRIGHT_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'custom-background',
		array( 'default-color' => 'faf7f1' )
	);

	// Custom image sizes for the catalog and blog.
	add_image_size( 'bookwright-cover', 480, 700, true );
	add_image_size( 'bookwright-card', 720, 405, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'bookwright' ),
			'footer'  => __( 'Footer Menu', 'bookwright' ),
		)
	);
}
add_action( 'after_setup_theme', 'bookwright_setup' );

/**
 * Content width.
 */
function bookwright_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bookwright_content_width', 1160 );
}
add_action( 'after_setup_theme', 'bookwright_content_width', 0 );

/**
 * Enqueue styles and scripts.
 */
function bookwright_assets() {
	// Google Fonts (with system fallbacks defined in CSS).
	wp_enqueue_style(
		'bookwright-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;0,800;1,600;1,700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'bookwright-base', get_stylesheet_uri(), array(), BOOKWRIGHT_VERSION );
	wp_enqueue_style( 'bookwright-theme', BOOKWRIGHT_URI . '/assets/css/theme.css', array( 'bookwright-base' ), BOOKWRIGHT_VERSION );

	wp_enqueue_script( 'bookwright-theme', BOOKWRIGHT_URI . '/assets/js/theme.js', array(), BOOKWRIGHT_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bookwright_assets' );

/**
 * Register widget areas.
 */
function bookwright_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'bookwright' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets shown alongside blog posts and archives.', 'bookwright' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer Column %d', 'bookwright' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => __( 'Optional footer widget column.', 'bookwright' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}
}
add_action( 'widgets_init', 'bookwright_widgets_init' );

/**
 * Body classes.
 */
function bookwright_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'bw-archive-view';
	}
	if ( is_page_template() ) {
		$classes[] = 'bw-has-template';
	}
	return $classes;
}
add_filter( 'body_class', 'bookwright_body_classes' );

/**
 * Custom excerpt length & more string.
 */
function bookwright_excerpt_length( $length ) {
	return 26;
}
add_filter( 'excerpt_length', 'bookwright_excerpt_length' );

function bookwright_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'bookwright_excerpt_more' );

/**
 * Includes.
 */
require BOOKWRIGHT_DIR . '/inc/template-tags.php';
require BOOKWRIGHT_DIR . '/inc/cpt-book.php';
require BOOKWRIGHT_DIR . '/inc/content-types.php';
require BOOKWRIGHT_DIR . '/inc/customizer.php';
require BOOKWRIGHT_DIR . '/inc/demo-content.php';
