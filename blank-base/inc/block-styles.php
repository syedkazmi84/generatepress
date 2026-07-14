<?php
/**
 * Custom block styles for Blank Base.
 *
 * Registers a handful of editor block-style variations. The visual rules live
 * in style.css (and editor-style.css) under the matching is-style-* classes.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register block styles.
 */
function blank_base_register_block_styles() {
	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	register_block_style(
		'core/image',
		array(
			'name'  => 'blank-base-frame',
			'label' => esc_html__( 'Framed', 'blank-base' ),
		)
	);

	register_block_style(
		'core/image',
		array(
			'name'  => 'blank-base-rounded',
			'label' => esc_html__( 'Rounded', 'blank-base' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'blank-base-pill',
			'label' => esc_html__( 'Pill', 'blank-base' ),
		)
	);

	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'blank-base-lead',
			'label' => esc_html__( 'Lead', 'blank-base' ),
		)
	);

	register_block_style(
		'core/separator',
		array(
			'name'  => 'blank-base-thick',
			'label' => esc_html__( 'Thick', 'blank-base' ),
		)
	);

	/*
	 * Scroll-reveal animation styles, offered on the most common blocks so
	 * they can be applied with one click from the Styles panel. Any other
	 * block can use the equivalent "bb-animate" utility classes via
	 * Advanced → Additional CSS class(es).
	 */
	$animation_styles = array(
		'animate-up'    => esc_html__( 'Animate: Rise up', 'blank-base' ),
		'animate-fade'  => esc_html__( 'Animate: Fade in', 'blank-base' ),
		'animate-zoom'  => esc_html__( 'Animate: Zoom in', 'blank-base' ),
		'animate-left'  => esc_html__( 'Animate: Slide from left', 'blank-base' ),
		'animate-right' => esc_html__( 'Animate: Slide from right', 'blank-base' ),
	);

	$animation_blocks = array(
		'core/paragraph',
		'core/heading',
		'core/image',
		'core/group',
		'core/columns',
		'core/column',
		'core/buttons',
		'core/cover',
		'core/quote',
		'core/list',
		'core/media-text',
	);

	foreach ( $animation_blocks as $block ) {
		foreach ( $animation_styles as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', 'blank_base_register_block_styles' );
