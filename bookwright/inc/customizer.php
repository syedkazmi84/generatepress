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

/**
 * Homepage section headings, stats and process steps — so every bit of the
 * front page can be edited with no code.
 */
function bookwright_customize_sections( $wp_customize ) {
	$wp_customize->add_section(
		'bookwright_sections',
		array(
			'title'    => __( 'Bookwright · Homepage Sections', 'bookwright' ),
			'priority' => 32,
		)
	);

	$settings = array(
		// Featured-in logos.
		'bw_logos'          => array( __( 'Featured-in logos (comma separated)', 'bookwright' ), 'The Paper Review, Inkwell, Readerly, Prose & Co., Bindery', 'text' ),
		// Services block.
		'bw_services_eyebrow' => array( __( 'Services — eyebrow', 'bookwright' ), 'What we do', 'text' ),
		'bw_services_title'   => array( __( 'Services — heading', 'bookwright' ), 'Everything your book needs, in one studio', 'text' ),
		'bw_services_lead'    => array( __( 'Services — lead text', 'bookwright' ), 'Pick a single service or hand us the whole journey. Either way you get a dedicated team and a clear plan.', 'textarea' ),
		// Process block.
		'bw_process_title'  => array( __( 'Process — heading', 'bookwright' ), 'A calm, four-step path to publication', 'text' ),
		'bw_step1_t' => array( __( 'Step 1 title', 'bookwright' ), 'Discover', 'text' ),
		'bw_step1_d' => array( __( 'Step 1 text', 'bookwright' ), 'We read your manuscript and map the fastest route to a finished book.', 'textarea' ),
		'bw_step2_t' => array( __( 'Step 2 title', 'bookwright' ), 'Refine', 'text' ),
		'bw_step2_d' => array( __( 'Step 2 text', 'bookwright' ), 'Editing and design shape the words and the look until both sing.', 'textarea' ),
		'bw_step3_t' => array( __( 'Step 3 title', 'bookwright' ), 'Publish', 'text' ),
		'bw_step3_d' => array( __( 'Step 3 text', 'bookwright' ), 'We format, proof and distribute across every major store and format.', 'textarea' ),
		'bw_step4_t' => array( __( 'Step 4 title', 'bookwright' ), 'Launch', 'text' ),
		'bw_step4_d' => array( __( 'Step 4 text', 'bookwright' ), 'A tailored marketing push builds momentum from day one.', 'textarea' ),
		// Books block.
		'bw_books_title'    => array( __( 'Featured books — heading', 'bookwright' ), 'Books we helped bring to the world', 'text' ),
		// Stats band.
		'bw_stat1_n' => array( __( 'Stat 1 number', 'bookwright' ), '1200+', 'text' ),
		'bw_stat1_l' => array( __( 'Stat 1 label', 'bookwright' ), 'Titles published', 'text' ),
		'bw_stat2_n' => array( __( 'Stat 2 number', 'bookwright' ), '45M+', 'text' ),
		'bw_stat2_l' => array( __( 'Stat 2 label', 'bookwright' ), 'Copies sold', 'text' ),
		'bw_stat3_n' => array( __( 'Stat 3 number', 'bookwright' ), '35+', 'text' ),
		'bw_stat3_l' => array( __( 'Stat 3 label', 'bookwright' ), 'Bestseller lists', 'text' ),
		'bw_stat4_n' => array( __( 'Stat 4 number', 'bookwright' ), '60+', 'text' ),
		'bw_stat4_l' => array( __( 'Stat 4 label', 'bookwright' ), 'Countries reached', 'text' ),
		// Testimonials + journal headings.
		'bw_testi_title'    => array( __( 'Testimonials — heading', 'bookwright' ), 'What our authors say', 'text' ),
		'bw_journal_title'  => array( __( 'Journal — heading', 'bookwright' ), 'Guides for writers & self-publishers', 'text' ),
	);

	foreach ( $settings as $id => $data ) {
		list( $label, $default, $type ) = $data;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => ( 'textarea' === $type ) ? 'sanitize_textarea_field' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'bookwright_sections',
				'type'    => ( 'textarea' === $type ) ? 'textarea' : 'text',
			)
		);
	}
}
add_action( 'customize_register', 'bookwright_customize_sections' );
