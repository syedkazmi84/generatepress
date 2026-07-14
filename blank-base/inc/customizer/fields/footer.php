<?php
/**
 * Customizer: Footer fields.
 *
 * Footer widget column count and footer-bar options.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Footer section and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function blank_base_customize_footer( $wp_customize ) {
	$wp_customize->add_section(
		'blank_base_footer',
		array(
			'title'    => esc_html__( 'Footer', 'blank-base' ),
			'priority' => 58,
		)
	);

	// Footer widget column count.
	$wp_customize->add_setting(
		'blank_base_footer_widgets',
		array(
			'default'           => 3,
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_footer_widgets',
		array(
			'label'   => esc_html__( 'Footer Widget Columns', 'blank-base' ),
			'section' => 'blank_base_footer',
			'type'    => 'select',
			'choices' => array(
				'0' => esc_html__( '0 — Disabled', 'blank-base' ),
				'1' => esc_html__( '1 Column', 'blank-base' ),
				'2' => esc_html__( '2 Columns', 'blank-base' ),
				'3' => esc_html__( '3 Columns', 'blank-base' ),
				'4' => esc_html__( '4 Columns', 'blank-base' ),
				'5' => esc_html__( '5 Columns', 'blank-base' ),
			),
		)
	);

	// Footer bar enable.
	$wp_customize->add_setting(
		'blank_base_footer_bar',
		array(
			'default'           => true,
			'sanitize_callback' => 'blank_base_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'blank_base_footer_bar',
		array(
			'label'   => esc_html__( 'Show Footer Bar', 'blank-base' ),
			'section' => 'blank_base_footer',
			'type'    => 'checkbox',
		)
	);

	// Footer bar layout.
	$wp_customize->add_setting(
		'blank_base_footer_bar_alignment',
		array(
			'default'           => 'space-between',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_footer_bar_alignment',
		array(
			'label'   => esc_html__( 'Footer Bar Layout', 'blank-base' ),
			'section' => 'blank_base_footer',
			'type'    => 'select',
			'choices' => array(
				'space-between' => esc_html__( 'Copyright left, menu right', 'blank-base' ),
				'center'        => esc_html__( 'Centered (stacked)', 'blank-base' ),
				'left'          => esc_html__( 'All left', 'blank-base' ),
			),
		)
	);
}
add_action( 'customize_register', 'blank_base_customize_footer', 30 );
