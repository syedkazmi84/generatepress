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
		'bw_phone'     => array( __( 'Phone number', 'bookwright' ), '+1 (555) 018-2420', 'text' ),
		'bw_email'     => array( __( 'Email address', 'bookwright' ), 'hello@yourpublishing.com', 'text' ),
		'bw_address'   => array( __( 'Street address', 'bookwright' ), '123 Author Avenue, Suite 200, Your City, ST 00000', 'text' ),
		'bw_hours'     => array( __( 'Office hours', 'bookwright' ), 'Mon–Sat · 8:00 AM–6:00 PM', 'text' ),
		'bw_tagline'   => array( __( 'Brand tagline', 'bookwright' ), 'Book Publishing Services', 'text' ),
		'bw_social_tw' => array( __( 'Twitter / X URL', 'bookwright' ), '#', 'url' ),
		'bw_social_ig' => array( __( 'Instagram URL', 'bookwright' ), '#', 'url' ),
		'bw_social_fb' => array( __( 'Facebook URL', 'bookwright' ), '#', 'url' ),
		'bw_social_in' => array( __( 'LinkedIn URL', 'bookwright' ), '#', 'url' ),
		'bw_footer_txt' => array( __( 'Footer about text', 'bookwright' ), 'An independent book publishing services company helping authors write, edit, design, publish and market their books — while keeping full ownership every step of the way.', 'textarea' ),
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
		'bw_hero_eyebrow' => array( __( 'Eyebrow text', 'bookwright' ), 'Ghostwriting · Editing · Publishing · Marketing' ),
		'bw_hero_title'   => array( __( 'Hero heading', 'bookwright' ), 'Your story, published the right way' ),
		'bw_hero_lead'    => array( __( 'Hero paragraph', 'bookwright' ), 'From first draft to final launch, our team helps you write, edit, design, publish and market your book — while you keep full ownership every step of the way.' ),
		'bw_hero_btn1'    => array( __( 'Primary button label', 'bookwright' ), 'Book a free consultation' ),
		'bw_hero_btn2'    => array( __( 'Secondary button label', 'bookwright' ), 'Explore our services' ),
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
		'bw_services_title'   => array( __( 'Services — heading', 'bookwright' ), 'Everything your book needs, in one place', 'text' ),
		'bw_services_lead'    => array( __( 'Services — lead text', 'bookwright' ), 'From writing to marketing, our team handles the hard parts of publishing so you can focus on your story. Choose a package or build your own.', 'textarea' ),
		// Process block.
		'bw_process_title'  => array( __( 'Process — heading', 'bookwright' ), 'How we bring your book to life', 'text' ),
		'bw_step1_t' => array( __( 'Step 1 title', 'bookwright' ), 'Book a free call', 'text' ),
		'bw_step1_d' => array( __( 'Step 1 text', 'bookwright' ), 'Grab a no-obligation consultation at a time that suits you.', 'textarea' ),
		'bw_step2_t' => array( __( 'Step 2 title', 'bookwright' ), 'Share your vision', 'text' ),
		'bw_step2_d' => array( __( 'Step 2 text', 'bookwright' ), 'Tell us about your book, your goals and where you are right now.', 'textarea' ),
		'bw_step3_t' => array( __( 'Step 3 title', 'bookwright' ), 'Get a tailored plan', 'text' ),
		'bw_step3_d' => array( __( 'Step 3 text', 'bookwright' ), 'We recommend the right services and send an honest, no-pressure quote.', 'textarea' ),
		'bw_step4_t' => array( __( 'Step 4 title', 'bookwright' ), 'We bring it to life', 'text' ),
		'bw_step4_d' => array( __( 'Step 4 text', 'bookwright' ), 'Your dedicated team writes, edits, designs, publishes and markets your book.', 'textarea' ),
		// Portfolio block.
		'bw_books_title'    => array( __( 'Portfolio — heading', 'bookwright' ), 'Books we’ve helped bring to life', 'text' ),
		// Stats band.
		'bw_stat1_n' => array( __( 'Stat 1 number', 'bookwright' ), '750+', 'text' ),
		'bw_stat1_l' => array( __( 'Stat 1 label', 'bookwright' ), 'Books published', 'text' ),
		'bw_stat2_n' => array( __( 'Stat 2 number', 'bookwright' ), '900+', 'text' ),
		'bw_stat2_l' => array( __( 'Stat 2 label', 'bookwright' ), 'Happy authors', 'text' ),
		'bw_stat3_n' => array( __( 'Stat 3 number', 'bookwright' ), '15+', 'text' ),
		'bw_stat3_l' => array( __( 'Stat 3 label', 'bookwright' ), 'Years of experience', 'text' ),
		'bw_stat4_n' => array( __( 'Stat 4 number', 'bookwright' ), '100%', 'text' ),
		'bw_stat4_l' => array( __( 'Stat 4 label', 'bookwright' ), 'Ownership you keep', 'text' ),
		// Testimonials + journal headings.
		'bw_testi_title'    => array( __( 'Testimonials — heading', 'bookwright' ), 'What our authors say', 'text' ),
		'bw_journal_title'  => array( __( 'Journal — heading', 'bookwright' ), 'Tips for writers & authors', 'text' ),
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
