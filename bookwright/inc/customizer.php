<?php
/**
 * Theme Customizer options: contact details, social links, footer text, hero.
 *
 * @package Bookwright
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bookwright_customize_register( $wp_customize ) {

	/* -------- Contact & Brand panel -------- */
	$wp_customize->add_section(
		'bookwright_contact',
		array(
			'title'    => __( 'Bookwright · Contact & Social', 'bookwright' ),
			'priority' => 30,
		)
	);

	$controls = array(
		'bw_phone'     => array( __( 'Phone number', 'bookwright' ), '+1 (212) 555-0139', 'text' ),
		'bw_email'     => array( __( 'Email address', 'bookwright' ), 'hello@bookwright.studio', 'text' ),
		'bw_address'   => array( __( 'Street address', 'bookwright' ), '48 Gramercy Park, New York, NY 10010', 'text' ),
		'bw_hours'     => array( __( 'Office hours', 'bookwright' ), 'Mon–Fri · 9am–6pm EST', 'text' ),
		'bw_tagline'   => array( __( 'Brand tagline', 'bookwright' ), 'Publishing Services', 'text' ),
		'bw_social_tw' => array( __( 'Twitter / X URL', 'bookwright' ), '#', 'url' ),
		'bw_social_ig' => array( __( 'Instagram URL', 'bookwright' ), '#', 'url' ),
		'bw_social_fb' => array( __( 'Facebook URL', 'bookwright' ), '#', 'url' ),
		'bw_social_in' => array( __( 'LinkedIn URL', 'bookwright' ), '#', 'url' ),
		'bw_footer_txt' => array( __( 'Footer about text', 'bookwright' ), 'Bookwright is a full-service publishing studio helping authors edit, design, publish and market books the world remembers.', 'textarea' ),
	);

	foreach ( $controls as $id => $data ) {
		list( $label, $default, $type ) = $data;
		$sanitize = ( 'url' === $type ) ? 'esc_url_raw' : ( 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field' );
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
				'label'   => $label,
				'section' => 'bookwright_contact',
				'type'    => ( 'textarea' === $type ) ? 'textarea' : ( 'url' === $type ? 'url' : 'text' ),
			)
		);
	}

	/* -------- Homepage hero panel -------- */
	$wp_customize->add_section(
		'bookwright_hero',
		array(
			'title'    => __( 'Bookwright · Homepage Hero', 'bookwright' ),
			'priority' => 31,
		)
	);

	$hero = array(
		'bw_hero_eyebrow' => array( __( 'Eyebrow text', 'bookwright' ), 'Trusted by 900+ authors worldwide' ),
		'bw_hero_title'   => array( __( 'Hero heading', 'bookwright' ), 'From manuscript to masterpiece' ),
		'bw_hero_lead'    => array( __( 'Hero paragraph', 'bookwright' ), 'Editing, cover design, publishing and marketing under one roof. We help you turn your story into a book readers can’t put down.' ),
		'bw_hero_btn1'    => array( __( 'Primary button label', 'bookwright' ), 'Start your book' ),
		'bw_hero_btn2'    => array( __( 'Secondary button label', 'bookwright' ), 'View our services' ),
	);

	foreach ( $hero as $id => $data ) {
		list( $label, $default ) = $data;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'bookwright_hero',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'bookwright_customize_register' );
