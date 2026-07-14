<?php
/**
 * Blank Base functions and definitions.
 *
 * This file bootstraps the theme. It defines theme support, registers menus,
 * sidebars, scripts and styles, and loads the modular files that live in the
 * `inc/` directory. No content is hard-coded — everything is added dynamically
 * by the site owner through the WordPress admin, Customizer and widgets.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'BLANK_BASE_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'BLANK_BASE_VERSION', '3.1.0' );
}

if ( ! function_exists( 'blank_base_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features,
	 * such as indicating support for post thumbnails.
	 */
	function blank_base_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'blank-base', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 9999 );

		// Register navigation menus.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary Menu', 'blank-base' ),
				'menu-2' => esc_html__( 'Footer Menu', 'blank-base' ),
				'social' => esc_html__( 'Social Links Menu', 'blank-base' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'blank_base_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
				'unlink-homepage-logo' => true,
			)
		);

		// Add support for full and wide align blocks.
		add_theme_support( 'align-wide' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom line height controls.
		add_theme_support( 'custom-line-height' );

		// Add support for experimental link color control.
		add_theme_support( 'link-color' );

		// Add support for custom units.
		add_theme_support( 'custom-units' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'assets/css/editor-style.css' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Remove core block patterns; the theme registers its own.
		remove_theme_support( 'core-block-patterns' );

		/*
		 * Starter content: a friendly starting point that appears in the
		 * Customizer on a fresh install and is only saved if the user
		 * chooses to publish it. Nothing is forced onto existing sites.
		 */
		add_theme_support(
			'starter-content',
			array(
				'widgets'   => array(
					'sidebar-1' => array( 'search', 'recent-posts', 'categories', 'archives' ),
					'footer-1'  => array(
						'text_about' => array(
							'text',
							array(
								'title' => esc_html__( 'About', 'blank-base' ),
								'text'  => esc_html__( 'A short sentence about this site. Replace it with your own from Appearance → Widgets.', 'blank-base' ),
							),
						),
					),
					'footer-2'  => array( 'recent-posts' ),
					'footer-3'  => array( 'recent-comments' ),
				),
				'nav_menus' => array(
					'menu-1' => array(
						'name'  => esc_html__( 'Primary', 'blank-base' ),
						'items' => array( 'page_home', 'page_blog', 'page_about', 'page_contact' ),
					),
					'menu-2' => array(
						'name'  => esc_html__( 'Footer', 'blank-base' ),
						'items' => array( 'page_about', 'page_contact' ),
					),
				),
				'posts'     => array(
					'home',
					'about',
					'blog',
					'contact',
				),
				'options'   => array(
					'show_on_front'  => 'page',
					'page_on_front'  => '{{home}}',
					'page_for_posts' => '{{blog}}',
				),
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'blank_base_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function blank_base_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'blank_base_content_width', 736 );
}
add_action( 'after_setup_theme', 'blank_base_content_width', 0 );

/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blank_base_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Right Sidebar', 'blank-base' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Shown as the right column for the "Right sidebar" layout, and as the right column of the "Both sidebars" layout.', 'blank-base' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Secondary widget area, shown as the left column for left / both layouts.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Left Sidebar', 'blank-base' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Shown as the left column for the "Left sidebar" layout, and as the left column of the "Both sidebars" layout.', 'blank-base' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Register footer widget columns based on the configured count (min 3 areas
	// are always registered so existing widgets are never orphaned).
	$footer_columns = function_exists( 'blank_base_footer_widget_count' ) ? blank_base_footer_widget_count() : 3;
	$footer_columns = max( 3, $footer_columns );
	for ( $i = 1; $i <= $footer_columns; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: Footer widget area number. */
				'name'          => sprintf( esc_html__( 'Footer %d', 'blank-base' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => esc_html__( 'Add widgets here to appear in the footer.', 'blank-base' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'blank_base_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function blank_base_scripts() {
	// Main stylesheet.
	wp_enqueue_style( 'blank-base-style', get_stylesheet_uri(), array(), BLANK_BASE_VERSION );
	wp_style_add_data( 'blank-base-style', 'rtl', 'replace' );

	// GeneratePress-style skin: separate boxed containers, 1200px width, 70/30
	// sidebar and GeneratePress default typography/colours. Depends on the main
	// stylesheet so it loads after it (and after the Customizer inline CSS).
	wp_enqueue_style(
		'blank-base-gp-style',
		get_template_directory_uri() . '/assets/css/gp-style.css',
		array( 'blank-base-style' ),
		BLANK_BASE_VERSION
	);

	// Print stylesheet.
	wp_enqueue_style(
		'blank-base-print',
		get_template_directory_uri() . '/assets/css/print.css',
		array(),
		BLANK_BASE_VERSION,
		'print'
	);

	// Navigation script for the responsive menu.
	wp_enqueue_script(
		'blank-base-navigation',
		get_template_directory_uri() . '/assets/js/navigation.js',
		array(),
		BLANK_BASE_VERSION,
		true
	);

	// Pro interactions: back-to-top, reading progress and other enhancements.
	wp_enqueue_script(
		'blank-base-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		BLANK_BASE_VERSION,
		true
	);

	// Threaded comment reply script.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'blank_base_scripts' );

/**
 * Enqueue block editor assets so the editor matches the front end.
 */
function blank_base_block_editor_assets() {
	wp_enqueue_style(
		'blank-base-editor',
		get_template_directory_uri() . '/assets/css/editor-style.css',
		array(),
		BLANK_BASE_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'blank_base_block_editor_assets' );

/**
 * Theme hook framework (GeneratePress-style action hooks).
 */
require get_template_directory() . '/inc/hooks.php';

/**
 * Layout engine: per-context sidebars, container type and the layout meta box.
 */
require get_template_directory() . '/inc/layout.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Breadcrumb trail.
 */
require get_template_directory() . '/inc/breadcrumbs.php';

/**
 * Color / typography / layout CSS output engine.
 */
require get_template_directory() . '/inc/css-output.php';

/**
 * Dynamic CSS and fonts.
 */
require get_template_directory() . '/inc/dynamic-css.php';

/**
 * Single-post reading features (reading time, TOC, related, author box, share).
 */
require get_template_directory() . '/inc/post-features.php';

/**
 * Header features (announcement bar, social menu).
 */
require get_template_directory() . '/inc/header-features.php';

/**
 * Structure: primary navigation positioning and footer (widgets + footer bar).
 */
require get_template_directory() . '/inc/structure/navigation.php';
require get_template_directory() . '/inc/structure/footer.php';

/**
 * Hook Elements: attach block content to theme hooks with display rules
 * (Appearance → Elements).
 */
require get_template_directory() . '/inc/elements.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions and modular field groups.
 */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/customizer/fields/layout.php';
require get_template_directory() . '/inc/customizer/fields/navigation.php';
require get_template_directory() . '/inc/customizer/fields/colors.php';
require get_template_directory() . '/inc/customizer/fields/typography.php';
require get_template_directory() . '/inc/customizer/fields/footer.php';

/**
 * Custom block styles.
 */
require get_template_directory() . '/inc/block-styles.php';

/**
 * Custom blocks (registered under the theme's own category).
 */
require get_template_directory() . '/inc/blocks.php';

/**
 * Advanced interactive blocks: tabs, accordion, sliders/carousels and
 * server-rendered post carousel/slider, plus core Table styles.
 */
require get_template_directory() . '/inc/blocks-advanced.php';

/**
 * Self-hosted Template Library.
 */
require get_template_directory() . '/inc/template-library.php';

/**
 * One-click Demo Content importer (Appearance → Import Demo Content).
 */
require get_template_directory() . '/inc/demo-import.php';

/**
 * Admin help / reference page.
 */
if ( is_admin() ) {
	require get_template_directory() . '/inc/admin-help.php';
}
