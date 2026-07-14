<?php
/**
 * Customizer: Color manager fields.
 *
 * Adds per-element color controls (header, content, buttons, footer) to the
 * existing "Colors" section, giving GeneratePress-style granular
 * color control. Every color defaults to empty, so unset colors inherit from
 * the base stylesheet.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the color-manager controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function blank_base_customize_colors( $wp_customize ) {
	$groups = array(
		esc_html__( 'Content', 'blank-base' )        => array(
			'blank_base_content_bg'        => esc_html__( 'Content Background', 'blank-base' ),
			'blank_base_content_text'      => esc_html__( 'Body Text', 'blank-base' ),
			'blank_base_link_color'        => esc_html__( 'Link Color', 'blank-base' ),
			'blank_base_link_hover_color'  => esc_html__( 'Link Hover Color', 'blank-base' ),
		),
		esc_html__( 'Header', 'blank-base' )         => array(
			'blank_base_header_bg'         => esc_html__( 'Header Background', 'blank-base' ),
			'blank_base_header_text'       => esc_html__( 'Header Text', 'blank-base' ),
			'blank_base_header_link'       => esc_html__( 'Header Link', 'blank-base' ),
			'blank_base_header_link_hover' => esc_html__( 'Header Link Hover', 'blank-base' ),
			'blank_base_site_title_color'  => esc_html__( 'Site Title', 'blank-base' ),
		),
		esc_html__( 'Buttons', 'blank-base' )        => array(
			'blank_base_button_bg'          => esc_html__( 'Button Background', 'blank-base' ),
			'blank_base_button_text'        => esc_html__( 'Button Text', 'blank-base' ),
			'blank_base_button_bg_hover'    => esc_html__( 'Button Background Hover', 'blank-base' ),
			'blank_base_button_text_hover'  => esc_html__( 'Button Text Hover', 'blank-base' ),
		),
		esc_html__( 'Footer', 'blank-base' )         => array(
			'blank_base_footer_widget_bg'        => esc_html__( 'Footer Widget Background', 'blank-base' ),
			'blank_base_footer_widget_text'      => esc_html__( 'Footer Widget Text', 'blank-base' ),
			'blank_base_footer_widget_link'      => esc_html__( 'Footer Widget Link', 'blank-base' ),
			'blank_base_footer_bar_bg'           => esc_html__( 'Footer Bar Background', 'blank-base' ),
			'blank_base_footer_bar_text'         => esc_html__( 'Footer Bar Text', 'blank-base' ),
			'blank_base_footer_bar_link'         => esc_html__( 'Footer Bar Link', 'blank-base' ),
		),
	);

	foreach ( $groups as $keys ) {
		foreach ( $keys as $key => $label ) {
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
						'section' => 'blank_base_colors',
					)
				)
			);
		}
	}
}
add_action( 'customize_register', 'blank_base_customize_colors', 30 );
