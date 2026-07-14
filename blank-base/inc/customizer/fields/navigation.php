<?php
/**
 * Customizer: Primary Navigation fields.
 *
 * Location, alignment, dropdown trigger and mobile menu style for the primary
 * menu.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Primary Navigation section and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function blank_base_customize_navigation( $wp_customize ) {
	$wp_customize->add_section(
		'blank_base_navigation',
		array(
			'title'    => esc_html__( 'Primary Navigation', 'blank-base' ),
			'priority' => 52,
		)
	);

	// Location.
	$wp_customize->add_setting(
		'blank_base_nav_location',
		array(
			'default'           => 'float-right',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_nav_location',
		array(
			'label'   => esc_html__( 'Navigation Location', 'blank-base' ),
			'section' => 'blank_base_navigation',
			'type'    => 'select',
			'choices' => array(
				'float-right'  => esc_html__( 'Inside header, right', 'blank-base' ),
				'float-left'   => esc_html__( 'Inside header, left', 'blank-base' ),
				'nav-center'   => esc_html__( 'Inside header, centered', 'blank-base' ),
				'below-header' => esc_html__( 'Below header (full-width bar)', 'blank-base' ),
				'above-header' => esc_html__( 'Above header (full-width bar)', 'blank-base' ),
			),
		)
	);

	// Menu alignment (for the full-width bars).
	$wp_customize->add_setting(
		'blank_base_nav_alignment',
		array(
			'default'           => 'left',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_nav_alignment',
		array(
			'label'       => esc_html__( 'Menu Alignment', 'blank-base' ),
			'description' => esc_html__( 'Applies when the navigation is a full-width bar.', 'blank-base' ),
			'section'     => 'blank_base_navigation',
			'type'        => 'select',
			'choices'     => array(
				'left'   => esc_html__( 'Left', 'blank-base' ),
				'center' => esc_html__( 'Center', 'blank-base' ),
				'right'  => esc_html__( 'Right', 'blank-base' ),
			),
		)
	);

	// Dropdown trigger.
	$wp_customize->add_setting(
		'blank_base_nav_dropdown',
		array(
			'default'           => 'hover',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_nav_dropdown',
		array(
			'label'   => esc_html__( 'Sub-Menu Opens On', 'blank-base' ),
			'section' => 'blank_base_navigation',
			'type'    => 'select',
			'choices' => array(
				'hover' => esc_html__( 'Hover', 'blank-base' ),
				'click' => esc_html__( 'Click', 'blank-base' ),
			),
		)
	);

	// Mobile menu style.
	$wp_customize->add_setting(
		'blank_base_mobile_menu',
		array(
			'default'           => 'dropdown',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_mobile_menu',
		array(
			'label'   => esc_html__( 'Mobile Menu Style', 'blank-base' ),
			'section' => 'blank_base_navigation',
			'type'    => 'select',
			'choices' => array(
				'dropdown'  => esc_html__( 'Dropdown', 'blank-base' ),
				'offcanvas' => esc_html__( 'Off-canvas (slide-in panel)', 'blank-base' ),
			),
		)
	);

	// Navigation colors.
	$nav_colors = array(
		'blank_base_nav_bg'         => esc_html__( 'Navigation Background', 'blank-base' ),
		'blank_base_nav_text'       => esc_html__( 'Navigation Text', 'blank-base' ),
		'blank_base_nav_hover_bg'   => esc_html__( 'Navigation Item Hover Background', 'blank-base' ),
		'blank_base_nav_hover_text' => esc_html__( 'Navigation Item Hover Text', 'blank-base' ),
		'blank_base_submenu_bg'     => esc_html__( 'Sub-Menu Background', 'blank-base' ),
		'blank_base_submenu_text'   => esc_html__( 'Sub-Menu Text', 'blank-base' ),
	);

	foreach ( $nav_colors as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'blank_base_navigation',
				)
			)
		);
	}
}
add_action( 'customize_register', 'blank_base_customize_navigation', 30 );
