<?php
/**
 * Custom blocks for Blank Base.
 *
 * A small, dependency-free block library registered under the theme's own
 * category so every block the theme ships (or that you add later) appears
 * grouped together in the inserter under your brand.
 *
 * The blocks use the "no-build" approach: they are authored in plain
 * JavaScript (see assets/js/blocks.js) using the wp.* globals, so the theme
 * keeps shipping as a normal zip with no webpack/npm build step. Front-end
 * behaviour (the animated counters and progress bars) is handled by the
 * existing assets/js/theme.js, which already animates the .bb-counter and
 * .bb-skill markup these blocks output.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The block category label shown in the inserter.
 *
 * Defaults to the active theme's name so the blocks appear under your own
 * brand (e.g. "AuthorWings"). Override with the filter if you want a fixed
 * label regardless of the active theme.
 *
 * @return string
 */
function blank_base_blocks_category_label() {
	$name = wp_get_theme()->get( 'Name' );
	if ( ! $name ) {
		$name = esc_html__( 'Blank Base', 'blank-base' );
	}

	/**
	 * Filters the label of the theme's block category.
	 *
	 * @param string $label Category label.
	 */
	return apply_filters( 'blank_base_blocks_category_label', $name );
}

/**
 * Register the theme's own block category at the top of the inserter list.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function blank_base_register_block_category( $categories ) {
	// Avoid registering twice.
	foreach ( $categories as $category ) {
		if ( isset( $category['slug'] ) && 'blank-base' === $category['slug'] ) {
			return $categories;
		}
	}

	array_unshift(
		$categories,
		array(
			'slug'  => 'blank-base',
			'title' => blank_base_blocks_category_label(),
			'icon'  => null,
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'blank_base_register_block_category' );

/**
 * Register the shared block stylesheet so it can be enqueued in both the
 * editor and on the front end.
 */
function blank_base_register_block_style() {
	wp_register_style(
		'blank-base-blocks',
		get_template_directory_uri() . '/assets/css/blocks.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);
}
add_action( 'init', 'blank_base_register_block_style' );

/**
 * Enqueue the editor script (which registers every block in the browser) and
 * the shared block styles inside the block editor.
 *
 * These blocks save static markup, so they are fully defined in JavaScript;
 * no server-side render callback or block.json is required.
 */
function blank_base_blocks_editor_assets() {
	$theme_uri = get_template_directory_uri();
	$version   = wp_get_theme( get_template() )->get( 'Version' );

	wp_enqueue_script(
		'blank-base-blocks',
		$theme_uri . '/assets/js/blocks.js',
		array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-i18n', 'wp-hooks' ),
		$version,
		true
	);

	// Make the category label available to the script.
	wp_localize_script(
		'blank-base-blocks',
		'blankBaseBlocks',
		array(
			'category' => blank_base_blocks_category_label(),
		)
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'blank-base-blocks', 'blank-base' );
	}

	wp_enqueue_style( 'blank-base-blocks' );
}
add_action( 'enqueue_block_editor_assets', 'blank_base_blocks_editor_assets' );

/**
 * Ensure Dashicons are available on the front end for the Icon Box block.
 *
 * Dashicons ship with WordPress but are only enqueued in wp-admin by default.
 */
function blank_base_blocks_frontend_assets() {
	wp_enqueue_style( 'dashicons' );
	// The shared block style is registered above; enqueue it on the front end.
	wp_enqueue_style( 'blank-base-blocks' );

	// Front-end behaviour for the Countdown and Circle Progress blocks.
	wp_enqueue_script(
		'blank-base-blocks-frontend',
		get_template_directory_uri() . '/assets/js/blocks-frontend.js',
		array(),
		wp_get_theme( get_template() )->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'blank_base_blocks_frontend_assets' );
