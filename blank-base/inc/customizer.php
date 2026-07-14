<?php
/**
 * Blank Base Theme Customizer.
 *
 * Registers theme options in the Customizer so the site owner can configure the
 * blank theme dynamically — colors, layout and footer text — with live preview.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blank_base_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'blank_base_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'blank_base_customize_partial_blogdescription',
			)
		);
	}

	/*
	 * Theme Options panel.
	 */
	$wp_customize->add_section(
		'blank_base_theme_options',
		array(
			'title'       => esc_html__( 'Theme Options', 'blank-base' ),
			'priority'    => 130,
			'description' => esc_html__( 'Configure layout and footer options for Blank Base.', 'blank-base' ),
		)
	);

	// Sidebar layout.
	$wp_customize->add_setting(
		'blank_base_sidebar_position',
		array(
			'default'           => 'right',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_sidebar_position',
		array(
			'label'       => esc_html__( 'Sidebar Position', 'blank-base' ),
			'description' => esc_html__( 'The global default. Individual contexts (blog, pages, posts) and single posts can override this below and in each post.', 'blank-base' ),
			'section'     => 'blank_base_theme_options',
			'type'        => 'select',
			'priority'    => 10,
			'choices'     => array(
				'right' => esc_html__( 'Right sidebar', 'blank-base' ),
				'left'  => esc_html__( 'Left sidebar', 'blank-base' ),
				'both'  => esc_html__( 'Both sidebars', 'blank-base' ),
				'none'  => esc_html__( 'No sidebar', 'blank-base' ),
				'full'  => esc_html__( 'Full width (edge to edge)', 'blank-base' ),
			),
		)
	);

	// Accent color.
	$wp_customize->add_setting(
		'blank_base_accent_color',
		array(
			'default'           => '#2563eb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'blank_base_accent_color',
			array(
				'label'   => esc_html__( 'Accent / Link Color', 'blank-base' ),
				'section' => 'blank_base_theme_options',
			)
		)
	);

	// Footer text.
	$wp_customize->add_setting(
		'blank_base_footer_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'blank_base_footer_text',
		array(
			'label'       => esc_html__( 'Footer Text', 'blank-base' ),
			'description' => esc_html__( 'Leave blank to use the default copyright line.', 'blank-base' ),
			'section'     => 'blank_base_theme_options',
			'type'        => 'textarea',
		)
	);
}
add_action( 'customize_register', 'blank_base_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 */
function blank_base_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function blank_base_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Sanitize select / radio choices against the registered control choices.
 *
 * @param string               $input   The value to sanitize.
 * @param WP_Customize_Setting $setting The setting instance.
 * @return string
 */
function blank_base_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return array_key_exists( $input, $choices ) ? $input : $setting->default;
}

/**
 * Sanitize a checkbox value to a strict boolean.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function blank_base_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked );
}

/**
 * Sanitize a floating-point value, returning an empty string when blank so the
 * corresponding CSS is skipped.
 *
 * @param mixed $value The value to sanitize.
 * @return string|float
 */
function blank_base_sanitize_float( $value ) {
	if ( '' === $value || null === $value ) {
		return '';
	}
	return abs( (float) $value );
}

/**
 * Register the "Pro" Customizer controls: typography, colors, header layout
 * and layout extras. Hooked separately so it stays isolated from the core
 * registration above.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blank_base_customize_register_pro( $wp_customize ) {

	/* ---------------------------------------------------------------------
	 * Typography.
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'blank_base_typography',
		array(
			'title'    => esc_html__( 'Typography', 'blank-base' ),
			'priority' => 40,
		)
	);

	$font_choices = blank_base_font_choices();

	$wp_customize->add_setting(
		'blank_base_body_font',
		array(
			'default'           => 'system',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_body_font',
		array(
			'label'   => esc_html__( 'Body Font', 'blank-base' ),
			'section' => 'blank_base_typography',
			'type'    => 'select',
			'choices' => $font_choices,
		)
	);

	$wp_customize->add_setting(
		'blank_base_heading_font',
		array(
			'default'           => 'serif',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_heading_font',
		array(
			'label'   => esc_html__( 'Heading Font', 'blank-base' ),
			'section' => 'blank_base_typography',
			'type'    => 'select',
			'choices' => $font_choices,
		)
	);

	$wp_customize->add_setting(
		'blank_base_base_font_size',
		array(
			'default'           => 16,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'blank_base_base_font_size',
		array(
			'label'       => esc_html__( 'Base Font Size (px)', 'blank-base' ),
			'section'     => 'blank_base_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 12,
				'max'  => 24,
				'step' => 1,
			),
		)
	);

	/* ---------------------------------------------------------------------
	 * Colors.
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'blank_base_colors',
		array(
			'title'    => esc_html__( 'Colors', 'blank-base' ),
			'priority' => 45,
		)
	);

	$wp_customize->add_setting(
		'blank_base_color_preset',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_color_preset',
		array(
			'label'       => esc_html__( 'Color Preset', 'blank-base' ),
			'description' => esc_html__( 'Sets the accent color. Choose "Custom" to pick your own below.', 'blank-base' ),
			'section'     => 'blank_base_colors',
			'type'        => 'select',
			'choices'     => blank_base_color_preset_choices(),
		)
	);

	/* ---------------------------------------------------------------------
	 * Header.
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'blank_base_header',
		array(
			'title'    => esc_html__( 'Header', 'blank-base' ),
			'priority' => 50,
		)
	);

	$wp_customize->add_setting(
		'blank_base_header_layout',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_header_layout',
		array(
			'label'   => esc_html__( 'Header Layout', 'blank-base' ),
			'section' => 'blank_base_header',
			'type'    => 'select',
			'choices' => array(
				'default'  => esc_html__( 'Branding left, menu right', 'blank-base' ),
				'centered' => esc_html__( 'Centered', 'blank-base' ),
				'minimal'  => esc_html__( 'Minimal (compact)', 'blank-base' ),
			),
		)
	);

	$wp_customize->add_setting(
		'blank_base_sticky_header',
		array(
			'default'           => false,
			'sanitize_callback' => 'blank_base_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'blank_base_sticky_header',
		array(
			'label'   => esc_html__( 'Sticky Header', 'blank-base' ),
			'section' => 'blank_base_header',
			'type'    => 'checkbox',
		)
	);

	/* ---------------------------------------------------------------------
	 * Layout extras (added to the existing Theme Options section).
	 * ------------------------------------------------------------------- */
	$wp_customize->add_setting(
		'blank_base_container_width',
		array(
			'default'           => 1200,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'blank_base_container_width',
		array(
			'label'       => esc_html__( 'Content Width (px)', 'blank-base' ),
			'section'     => 'blank_base_theme_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 640,
				'max'  => 1600,
				'step' => 20,
			),
		)
	);

	$wp_customize->add_setting(
		'blank_base_breadcrumbs',
		array(
			'default'           => true,
			'sanitize_callback' => 'blank_base_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'blank_base_breadcrumbs',
		array(
			'label'   => esc_html__( 'Show Breadcrumbs', 'blank-base' ),
			'section' => 'blank_base_theme_options',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'blank_base_back_to_top',
		array(
			'default'           => true,
			'sanitize_callback' => 'blank_base_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'blank_base_back_to_top',
		array(
			'label'   => esc_html__( 'Show "Back to Top" Button', 'blank-base' ),
			'section' => 'blank_base_theme_options',
			'type'    => 'checkbox',
		)
	);

	/* ---------------------------------------------------------------------
	 * Header extras: announcement bar.
	 * ------------------------------------------------------------------- */
	$wp_customize->add_setting(
		'blank_base_announcement_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'blank_base_announcement_text',
		array(
			'label'       => esc_html__( 'Announcement Bar Text', 'blank-base' ),
			'description' => esc_html__( 'Shown as a dismissible bar above the header. Leave blank to hide.', 'blank-base' ),
			'section'     => 'blank_base_header',
			'type'        => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'blank_base_logo_max_height',
		array(
			'default'           => 60,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'blank_base_logo_max_height',
		array(
			'label'       => esc_html__( 'Logo Max Height (px)', 'blank-base' ),
			'section'     => 'blank_base_header',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 20,
				'max'  => 200,
				'step' => 5,
			),
		)
	);

	/* ---------------------------------------------------------------------
	 * Sticky sidebar (added to the existing Theme Options section).
	 * ------------------------------------------------------------------- */
	$wp_customize->add_setting(
		'blank_base_sticky_sidebar',
		array(
			'default'           => false,
			'sanitize_callback' => 'blank_base_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'blank_base_sticky_sidebar',
		array(
			'label'   => esc_html__( 'Sticky Sidebar', 'blank-base' ),
			'section' => 'blank_base_theme_options',
			'type'    => 'checkbox',
		)
	);

	/* ---------------------------------------------------------------------
	 * Blog & Posts.
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'blank_base_blog',
		array(
			'title'    => esc_html__( 'Blog & Posts', 'blank-base' ),
			'priority' => 55,
		)
	);

	$wp_customize->add_setting(
		'blank_base_blog_layout',
		array(
			'default'           => 'list',
			'sanitize_callback' => 'blank_base_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'blank_base_blog_layout',
		array(
			'label'   => esc_html__( 'Blog Layout', 'blank-base' ),
			'section' => 'blank_base_blog',
			'type'    => 'select',
			'choices' => array(
				'list'    => esc_html__( 'List (single column)', 'blank-base' ),
				'grid'    => esc_html__( 'Grid (cards)', 'blank-base' ),
				'masonry' => esc_html__( 'Masonry', 'blank-base' ),
			),
		)
	);

	$blank_base_post_toggles = array(
		'blank_base_reading_time'     => array( esc_html__( 'Show Reading Time', 'blank-base' ), true ),
		'blank_base_reading_progress' => array( esc_html__( 'Show Reading Progress Bar (single posts)', 'blank-base' ), true ),
		'blank_base_toc'              => array( esc_html__( 'Show Table of Contents (single posts)', 'blank-base' ), false ),
		'blank_base_related_posts'    => array( esc_html__( 'Show Related Posts', 'blank-base' ), true ),
		'blank_base_author_box'       => array( esc_html__( 'Show Author Box', 'blank-base' ), true ),
		'blank_base_social_share'     => array( esc_html__( 'Show Social Share Buttons', 'blank-base' ), true ),
	);

	foreach ( $blank_base_post_toggles as $blank_base_key => $blank_base_data ) {
		$wp_customize->add_setting(
			$blank_base_key,
			array(
				'default'           => $blank_base_data[1],
				'sanitize_callback' => 'blank_base_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$blank_base_key,
			array(
				'label'   => $blank_base_data[0],
				'section' => 'blank_base_blog',
				'type'    => 'checkbox',
			)
		);
	}
}
add_action( 'customize_register', 'blank_base_customize_register_pro', 20 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blank_base_customize_preview_js() {
	wp_enqueue_script(
		'blank-base-customizer',
		get_template_directory_uri() . '/assets/js/customizer.js',
		array( 'customize-preview' ),
		BLANK_BASE_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'blank_base_customize_preview_js' );
