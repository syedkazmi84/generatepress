<?php
/**
 * Customizer: Typography manager fields.
 *
 * Extends the Typography section with weight, line-height, text-transform and
 * per-element font sizes for the body, headings, site title and navigation.
 * A size of 0 (or empty weight) keeps the base stylesheet's value.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the typography-manager controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function blank_base_customize_typography( $wp_customize ) {
	$weight_choices = array(
		''    => esc_html__( 'Default', 'blank-base' ),
		'300' => esc_html__( '300 — Light', 'blank-base' ),
		'400' => esc_html__( '400 — Normal', 'blank-base' ),
		'500' => esc_html__( '500 — Medium', 'blank-base' ),
		'600' => esc_html__( '600 — Semi-bold', 'blank-base' ),
		'700' => esc_html__( '700 — Bold', 'blank-base' ),
		'800' => esc_html__( '800 — Extra-bold', 'blank-base' ),
	);

	$transform_choices = array(
		'none'       => esc_html__( 'None', 'blank-base' ),
		'uppercase'  => esc_html__( 'UPPERCASE', 'blank-base' ),
		'lowercase'  => esc_html__( 'lowercase', 'blank-base' ),
		'capitalize' => esc_html__( 'Capitalize', 'blank-base' ),
	);

	/* ---- Body ---- */
	$wp_customize->add_setting(
		'blank_base_body_weight',
		array(
			'default'           => '',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_body_weight',
		array(
			'label'   => esc_html__( 'Body Font Weight', 'blank-base' ),
			'section' => 'blank_base_typography',
			'type'    => 'select',
			'choices' => $weight_choices,
		)
	);

	blank_base_add_number_control( $wp_customize, 'blank_base_body_line_height', esc_html__( 'Body Line Height', 'blank-base' ), 'blank_base_typography', '', 1, 2.5, 0.05, 'blank_base_sanitize_float' );

	/* ---- Headings ---- */
	$wp_customize->add_setting(
		'blank_base_heading_weight',
		array(
			'default'           => '',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_heading_weight',
		array(
			'label'   => esc_html__( 'Heading Font Weight', 'blank-base' ),
			'section' => 'blank_base_typography',
			'type'    => 'select',
			'choices' => $weight_choices,
		)
	);

	$wp_customize->add_setting(
		'blank_base_heading_transform',
		array(
			'default'           => 'none',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_heading_transform',
		array(
			'label'   => esc_html__( 'Heading Text Transform', 'blank-base' ),
			'section' => 'blank_base_typography',
			'type'    => 'select',
			'choices' => $transform_choices,
		)
	);

	blank_base_add_number_control( $wp_customize, 'blank_base_heading_line_height', esc_html__( 'Heading Line Height', 'blank-base' ), 'blank_base_typography', '', 0.8, 2, 0.05, 'blank_base_sanitize_float' );

	/* ---- Per-heading font sizes (px, 0 = default). ---- */
	$heading_sizes = array(
		'blank_base_h1_size' => esc_html__( 'H1 Size (px)', 'blank-base' ),
		'blank_base_h2_size' => esc_html__( 'H2 Size (px)', 'blank-base' ),
		'blank_base_h3_size' => esc_html__( 'H3 Size (px)', 'blank-base' ),
		'blank_base_h4_size' => esc_html__( 'H4 Size (px)', 'blank-base' ),
		'blank_base_h5_size' => esc_html__( 'H5 Size (px)', 'blank-base' ),
		'blank_base_h6_size' => esc_html__( 'H6 Size (px)', 'blank-base' ),
	);
	foreach ( $heading_sizes as $key => $label ) {
		blank_base_add_number_control( $wp_customize, $key, $label, 'blank_base_typography', 0, 0, 120, 1, 'absint' );
	}

	/* ---- Site title. ---- */
	blank_base_add_number_control( $wp_customize, 'blank_base_site_title_size', esc_html__( 'Site Title Size (px)', 'blank-base' ), 'blank_base_typography', 0, 0, 80, 1, 'absint' );

	/* ---- Navigation typography. ---- */
	blank_base_add_number_control( $wp_customize, 'blank_base_nav_font_size', esc_html__( 'Navigation Font Size (px)', 'blank-base' ), 'blank_base_typography', 0, 0, 32, 1, 'absint' );

	$wp_customize->add_setting(
		'blank_base_nav_font_weight',
		array(
			'default'           => '',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_nav_font_weight',
		array(
			'label'   => esc_html__( 'Navigation Font Weight', 'blank-base' ),
			'section' => 'blank_base_typography',
			'type'    => 'select',
			'choices' => $weight_choices,
		)
	);

	$wp_customize->add_setting(
		'blank_base_nav_transform',
		array(
			'default'           => 'none',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_nav_transform',
		array(
			'label'   => esc_html__( 'Navigation Text Transform', 'blank-base' ),
			'section' => 'blank_base_typography',
			'type'    => 'select',
			'choices' => $transform_choices,
		)
	);
}
add_action( 'customize_register', 'blank_base_customize_typography', 30 );

/**
 * Helper to register a number control + setting in one call.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @param string               $id           Setting ID.
 * @param string               $label        Control label.
 * @param string               $section      Section ID.
 * @param mixed                $default      Default value.
 * @param float                $min          Minimum.
 * @param float                $max          Maximum.
 * @param float                $step         Step.
 * @param callable             $sanitize     Sanitize callback.
 */
function blank_base_add_number_control( $wp_customize, $id, $label, $section, $default, $min, $max, $step, $sanitize ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => $sanitize,
		)
	);
	$wp_customize->add_control(
		$id,
		array(
			'label'       => $label,
			'section'     => $section,
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => $min,
				'max'  => $max,
				'step' => $step,
			),
		)
	);
}
