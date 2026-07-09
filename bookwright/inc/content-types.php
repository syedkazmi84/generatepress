<?php
/**
 * Editable content types.
 *
 * Turns the sections that used to be hard-coded in templates — Services,
 * Testimonials, Team, Pricing Plans and FAQs — into dashboard-managed custom
 * post types with simple meta boxes, so the whole site is editable with no code.
 *
 * @package Bookwright
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the editable component post types.
 */
function bookwright_register_content_types() {

	$types = array(
		'bw_service'     => array(
			'labels'   => array( __( 'Service', 'bookwright' ), __( 'Services', 'bookwright' ) ),
			'icon'     => 'dashicons-hammer',
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		),
		'bw_testimonial' => array(
			'labels'   => array( __( 'Testimonial', 'bookwright' ), __( 'Testimonials', 'bookwright' ) ),
			'icon'     => 'dashicons-format-quote',
			'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		),
		'bw_team'        => array(
			'labels'   => array( __( 'Team Member', 'bookwright' ), __( 'Team', 'bookwright' ) ),
			'icon'     => 'dashicons-groups',
			'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		),
		'bw_plan'        => array(
			'labels'   => array( __( 'Pricing Plan', 'bookwright' ), __( 'Pricing Plans', 'bookwright' ) ),
			'icon'     => 'dashicons-tag',
			'supports' => array( 'title', 'page-attributes' ),
		),
		'bw_faq'         => array(
			'labels'   => array( __( 'FAQ', 'bookwright' ), __( 'FAQs', 'bookwright' ) ),
			'icon'     => 'dashicons-editor-help',
			'supports' => array( 'title', 'editor', 'page-attributes' ),
		),
	);

	foreach ( $types as $slug => $cfg ) {
		list( $single, $plural ) = $cfg['labels'];
		register_post_type(
			$slug,
			array(
				'labels'       => array(
					'name'          => $plural,
					'singular_name' => $single,
					/* translators: %s: singular post type label. */
					'add_new_item'  => sprintf( __( 'Add New %s', 'bookwright' ), $single ),
					/* translators: %s: singular post type label. */
					'edit_item'     => sprintf( __( 'Edit %s', 'bookwright' ), $single ),
					'menu_name'     => $plural,
					'all_items'     => $plural,
				),
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => true,
				'show_in_rest' => true,
				'menu_icon'    => $cfg['icon'],
				'supports'     => $cfg['supports'],
			)
		);
	}
}
add_action( 'init', 'bookwright_register_content_types' );

/**
 * Field definitions for each type's meta box.
 */
function bookwright_content_fields( $type ) {
	$fields = array(
		'bw_service'     => array(
			'_bw_icon' => array( __( 'Icon', 'bookwright' ), 'icon' ),
			'_bw_link' => array( __( 'Custom link (optional — defaults to Services page)', 'bookwright' ), 'url' ),
		),
		'bw_testimonial' => array(
			'_bw_role'   => array( __( 'Author role / book', 'bookwright' ), 'text' ),
			'_bw_rating' => array( __( 'Rating (1–5)', 'bookwright' ), 'number' ),
			'_bw_photo'  => array( __( 'Fallback photo file (used if no featured image)', 'bookwright' ), 'text' ),
		),
		'bw_team'        => array(
			'_bw_role'  => array( __( 'Role / title', 'bookwright' ), 'text' ),
			'_bw_photo' => array( __( 'Fallback photo file (used if no featured image)', 'bookwright' ), 'text' ),
		),
		'bw_plan'        => array(
			'_bw_price'    => array( __( 'Price (e.g. $2,499)', 'bookwright' ), 'text' ),
			'_bw_period'   => array( __( 'Period (e.g. one-time)', 'bookwright' ), 'text' ),
			'_bw_desc'     => array( __( 'Short description', 'bookwright' ), 'textarea' ),
			'_bw_featured' => array( __( 'Highlight as “Most popular”?', 'bookwright' ), 'checkbox' ),
			'_bw_features' => array( __( 'Features — one per line. Start a line with ! to show it greyed-out / not included.', 'bookwright' ), 'bigtext' ),
		),
	);
	return isset( $fields[ $type ] ) ? $fields[ $type ] : array();
}

/**
 * Register meta boxes.
 */
function bookwright_content_metaboxes() {
	foreach ( array( 'bw_service', 'bw_testimonial', 'bw_team', 'bw_plan' ) as $type ) {
		add_meta_box(
			$type . '_details',
			__( 'Details', 'bookwright' ),
			'bookwright_content_metabox_html',
			$type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'bookwright_content_metaboxes' );

/**
 * Meta box markup.
 */
function bookwright_content_metabox_html( $post ) {
	wp_nonce_field( 'bookwright_save_content', 'bookwright_content_nonce' );
	$fields = bookwright_content_fields( $post->post_type );
	echo '<div style="display:grid;gap:14px;">';
	foreach ( $fields as $key => $def ) {
		list( $label, $type ) = $def;
		$val = get_post_meta( $post->ID, $key, true );
		echo '<p style="margin:0;"><label style="display:block;font-weight:600;margin-bottom:5px;">' . esc_html( $label ) . '</label>';
		switch ( $type ) {
			case 'icon':
				$icons = array( 'edit', 'design', 'book', 'megaphone', 'quill', 'globe', 'search', 'chart', 'shield', 'award', 'users', 'star' );
				echo '<select name="' . esc_attr( $key ) . '" style="min-width:220px;">';
				foreach ( $icons as $ic ) {
					echo '<option value="' . esc_attr( $ic ) . '" ' . selected( $val, $ic, false ) . '>' . esc_html( $ic ) . '</option>';
				}
				echo '</select>';
				break;
			case 'textarea':
				echo '<textarea name="' . esc_attr( $key ) . '" rows="2" style="width:100%;">' . esc_textarea( $val ) . '</textarea>';
				break;
			case 'bigtext':
				echo '<textarea name="' . esc_attr( $key ) . '" rows="7" style="width:100%;">' . esc_textarea( $val ) . '</textarea>';
				break;
			case 'checkbox':
				echo '<label style="font-weight:400;"><input type="checkbox" name="' . esc_attr( $key ) . '" value="1" ' . checked( $val, '1', false ) . ' /> ' . esc_html__( 'Yes', 'bookwright' ) . '</label>';
				break;
			case 'number':
				echo '<input type="number" min="1" max="5" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" style="width:90px;" />';
				break;
			default:
				echo '<input type="text" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" style="width:100%;max-width:420px;" />';
		}
		echo '</p>';
	}
	echo '</div>';
}

/**
 * Save meta.
 */
function bookwright_save_content_meta( $post_id ) {
	if ( ! isset( $_POST['bookwright_content_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bookwright_content_nonce'] ) ), 'bookwright_save_content' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$type   = get_post_type( $post_id );
	$fields = bookwright_content_fields( $type );
	foreach ( $fields as $key => $def ) {
		$ftype = $def[1];
		if ( 'checkbox' === $ftype ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '' );
			continue;
		}
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		if ( 'url' === $ftype ) {
			$raw = esc_url_raw( $raw );
		} elseif ( in_array( $ftype, array( 'textarea', 'bigtext' ), true ) ) {
			$raw = sanitize_textarea_field( $raw );
		} else {
			$raw = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $key, $raw );
	}
}
foreach ( array( 'bw_service', 'bw_testimonial', 'bw_team', 'bw_plan' ) as $bw_ct ) {
	add_action( 'save_post_' . $bw_ct, 'bookwright_save_content_meta' );
}

/* ---------------------------------------------------------------------------
 * Helpers used by the templates
 * ------------------------------------------------------------------------- */

/**
 * Query a component type, ordered by menu order.
 */
function bookwright_get_items( $type, $limit = -1 ) {
	return new WP_Query(
		array(
			'post_type'      => $type,
			'posts_per_page' => $limit,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);
}

/**
 * True if any items of this type exist (so templates know whether to use the
 * editable content or fall back to the built-in defaults).
 */
function bookwright_has_items( $type ) {
	$q = bookwright_get_items( $type, 1 );
	return $q->have_posts();
}

/**
 * Photo URL for a testimonial/team member: featured image, then fallback meta,
 * then a generic bundled avatar.
 */
function bookwright_entry_photo( $fallback = 'avatar-1.svg' ) {
	if ( has_post_thumbnail() ) {
		return get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
	}
	$meta = get_post_meta( get_the_ID(), '_bw_photo', true );
	return bookwright_img( $meta ? $meta : $fallback );
}

/* ---------------------------------------------------------------------------
 * Built-in default content — used both as the template fallback (when no
 * editable items exist yet) and as the source for the demo seeder.
 * ------------------------------------------------------------------------- */

function bookwright_default_services() {
	// icon, title, short (excerpt), long (body), features[]
	return array(
		array( 'edit', 'Editorial & Proofreading', 'Developmental, line and copy editing that sharpens your story while protecting your voice.', 'Great books are rewritten, not written. Our editors work in clear stages so your budget goes exactly where your manuscript needs it.', array( 'Developmental / structural editing', 'Line & copy editing', 'Proofreading', 'Editorial assessment reports' ) ),
		array( 'design', 'Cover & Interior Design', 'Scroll-stopping covers and beautiful, readable interiors for print and ebook.', 'A cover sells the click; a beautiful interior keeps the reader turning pages. We design both, for print and digital.', array( 'Three original cover concepts', 'Print & ebook interior layout', 'Typesetting & formatting', 'Print-ready files for KDP & IngramSpark' ) ),
		array( 'book', 'Publishing & Distribution', 'We set up and launch across Amazon KDP, IngramSpark, Apple Books and more.', 'We handle the technical maze of getting your book listed, priced and available everywhere readers shop.', array( 'Amazon KDP & IngramSpark setup', 'ISBN & metadata optimisation', 'Global print & ebook distribution', 'Pricing & category strategy' ) ),
		array( 'megaphone', 'Book Marketing', 'Launch strategy, ads, email and PR that put your book in front of the right readers.', 'A finished book is the start line, not the finish. Our marketing team builds momentum that lasts beyond launch week.', array( 'Launch strategy & timeline', 'Amazon & Meta advertising', 'Email & newsletter campaigns', 'PR, reviews & influencer outreach' ) ),
		array( 'quill', 'Ghostwriting', 'Have the idea but not the time? Our writers turn your vision into finished chapters.', 'Bring us your outline, notes or voice memos and our ghostwriters craft polished chapters in your voice.', array( 'Discovery interviews', 'Chapter-by-chapter drafting', 'Unlimited revisions within scope', 'Full confidentiality' ) ),
		array( 'globe', 'Audiobook Production', 'Professional narration and mastering to reach listeners on Audible and beyond.', 'Reach the fastest-growing segment of readers with a studio-quality audiobook, start to finish.', array( 'Professional narrator casting', 'Studio recording & direction', 'Mastering to ACX spec', 'Distribution to Audible & Apple' ) ),
	);
}

function bookwright_default_testimonials() {
	return array(
		array( 'Eleanor Vance', 'Bookwright took my rough manuscript and turned it into a book I’m genuinely proud of. The cover alone doubled my click-through.', 'Author of The Lantern Keeper', 5, 'avatar-1.svg' ),
		array( 'Nadia Okafor', 'The team hit every deadline and treated my little memoir like it was the next big bestseller. I felt supported the whole way.', 'Author of Saltwater Girlhood', 5, 'avatar-2.svg' ),
		array( 'Marcus Ellison', 'I came for editing and stayed for the full launch. First week on Amazon we hit #1 in two categories. Worth every penny.', 'Author of Building Quiet Wealth', 5, 'avatar-3.svg' ),
	);
}

function bookwright_default_team() {
	return array(
		array( 'Amara Bright', 'Founder & Publisher', 'avatar-1.svg' ),
		array( 'Daniel Cho', 'Editorial Director', 'avatar-4.svg' ),
		array( 'Priya Raman', 'Creative Director', 'avatar-2.svg' ),
		array( 'Marcus Ellison', 'Head of Marketing', 'avatar-3.svg' ),
	);
}

function bookwright_default_plans() {
	return array(
		array(
			'name' => 'Starter', 'price' => '$899', 'period' => 'one-time', 'featured' => false,
			'desc' => 'For authors who need a polished, publish-ready manuscript.',
			'features' => "Professional proofreading\nEbook formatting\nAmazon KDP setup\n!Cover design\n!Marketing launch\n!Dedicated project manager",
		),
		array(
			'name' => 'Publish', 'price' => '$2,499', 'period' => 'one-time', 'featured' => true,
			'desc' => 'Our most popular package — everything to publish beautifully.',
			'features' => "Copy & line editing\nCustom cover design\nPrint & ebook formatting\nKDP & IngramSpark distribution\nBasic launch marketing\nDedicated project manager",
		),
		array(
			'name' => 'Bestseller', 'price' => '$5,900', 'period' => 'one-time', 'featured' => false,
			'desc' => 'A full-scale publish-and-launch built to hit the charts.',
			'features' => "Developmental + copy editing\nPremium cover & interior design\nGlobal distribution setup\nFull marketing & ad campaign\nPR & review outreach\nAudiobook production add-on",
		),
	);
}

function bookwright_default_faqs() {
	return array(
		array( 'Do I keep the rights to my book?', 'Absolutely. You retain 100% of your rights and royalties, always. We’re a service provider, not a publisher that owns your work.' ),
		array( 'How long does a typical project take?', 'Most Publish-package projects run 8–14 weeks depending on manuscript length and the level of editing required. We’ll give you a firm timeline before we start.' ),
		array( 'Can I mix and match services?', 'Yes. Every service is available à la carte. Tell us what you need and we’ll build a custom quote.' ),
		array( 'Do you offer payment plans?', 'We do. Most packages can be split into two or three milestone payments at no extra cost.' ),
		array( 'What if I’m not happy with the work?', 'Every package includes rounds of revision and a satisfaction guarantee. Your dedicated project manager is with you the whole way.' ),
	);
}

/**
 * Parse a plan's feature textarea into array( array( label, included ) ).
 */
function bookwright_parse_features( $raw ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$off = ( '!' === substr( $line, 0, 1 ) );
		$out[] = array( ltrim( $line, '!' ), ! $off );
	}
	return $out;
}
