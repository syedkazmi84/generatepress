<?php
/**
 * Customizer: Layout fields.
 *
 * Adds container type, per-context sidebar layouts and sidebar width to the
 * Theme Options section, extending the single global "Sidebar Position" control
 * into a per-context layout system.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register layout controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function blank_base_customize_layout( $wp_customize ) {
	$sidebar_choices = array(
		'default' => esc_html__( 'Inherit global default', 'blank-base' ),
		'right'   => esc_html__( 'Right sidebar', 'blank-base' ),
		'left'    => esc_html__( 'Left sidebar', 'blank-base' ),
		'both'    => esc_html__( 'Both sidebars', 'blank-base' ),
		'none'    => esc_html__( 'No sidebar', 'blank-base' ),
		'full'    => esc_html__( 'Full width (edge to edge)', 'blank-base' ),
	);

	// Content container type.
	$wp_customize->add_setting(
		'blank_base_content_layout',
		array(
			'default'           => 'boxed',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_content_layout',
		array(
			'label'       => esc_html__( 'Content Container', 'blank-base' ),
			'description' => esc_html__( 'Boxed keeps content within the content width. Full width lets it span the browser.', 'blank-base' ),
			'section'     => 'blank_base_theme_options',
			'type'        => 'select',
			'priority'    => 5,
			'choices'     => array(
				'boxed'      => esc_html__( 'Boxed (max width)', 'blank-base' ),
				'full-width' => esc_html__( 'Full width', 'blank-base' ),
			),
		)
	);

	// Per-context sidebar layouts.
	$contexts = array(
		'blank_base_blog_sidebar'    => esc_html__( 'Blog / Posts Page Sidebar', 'blank-base' ),
		'blank_base_single_sidebar'  => esc_html__( 'Single Post Sidebar', 'blank-base' ),
		'blank_base_page_sidebar'    => esc_html__( 'Static Page Sidebar', 'blank-base' ),
		'blank_base_archive_sidebar' => esc_html__( 'Archive / Search Sidebar', 'blank-base' ),
	);

	$priority = 20;
	foreach ( $contexts as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => 'default',
				'sanitize_callback' => 'blank_base_sanitize_select',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'    => $label,
				'section'  => 'blank_base_theme_options',
				'type'     => 'select',
				'priority' => $priority,
				'choices'  => $sidebar_choices,
			)
		);
		$priority++;
	}

	// Sidebar width.
	$wp_customize->add_setting(
		'blank_base_sidebar_width',
		array(
			'default'           => 30,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'blank_base_sidebar_width',
		array(
			'label'       => esc_html__( 'Sidebar Width (%)', 'blank-base' ),
			'section'     => 'blank_base_theme_options',
			'type'        => 'number',
			'priority'    => 30,
			'input_attrs' => array(
				'min'  => 15,
				'max'  => 50,
				'step' => 1,
			),
		)
	);
}
add_action( 'customize_register', 'blank_base_customize_layout', 30 );
