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
 * Render an FAQ accordion from a plain array of [ question, answer ] pairs.
 *
 * FAQs are defined directly in each page template (front-page.php,
 * tpl-services.php, tpl-pricing.php, …) so every page can have its own set —
 * there is no FAQ post type or dashboard editor to manage.
 *
 * @param array $items      Array of array( question, answer ).
 * @param bool  $first_open Whether to open the first item by default.
 */
function bookwright_faq_accordion( $items, $first_open = true ) {
	if ( empty( $items ) || ! is_array( $items ) ) {
		return;
	}
	echo '<div class="bw-faq">';
	$i = 0;
	foreach ( $items as $f ) {
		if ( empty( $f[0] ) ) {
			continue;
		}
		$open = ( 0 === $i && $first_open ) ? ' open' : '';
		printf(
			'<details%1$s><summary>%2$s</summary><p>%3$s</p></details>',
			esc_attr( $open ), // phpcs:ignore WordPress.Security.EscapeOutput
			esc_html( $f[0] ),
			esc_html( isset( $f[1] ) ? $f[1] : '' )
		);
		$i++;
	}
	echo '</div>';
}

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
			'_bw_role'   => array( __( 'Tagline under the name (e.g. “First-time author”)', 'bookwright' ), 'text' ),
			'_bw_rating' => array( __( 'Rating (1–5 stars)', 'bookwright' ), 'number' ),
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
			'side',
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
		array( 'quill', 'Ghostwriting & Book Writing', 'Seasoned ghostwriters turn your ideas, notes or voice memos into a finished manuscript — written in your voice.', 'Have a story or expertise but not the time to write it? Partner with a professional ghostwriter who captures your voice and does the writing for you. You stay the sole author and rights-holder — we work quietly behind the scenes.', array( 'One-on-one discovery interviews', 'Chapter-by-chapter drafting', 'Revisions until it sounds like you', '100% confidential — credited entirely to you' ) ),
		array( 'edit', 'Editing & Proofreading', 'Developmental, line and copy editing plus a final proofread, so your book reads clean and professional.', 'Great books are shaped in the edit. Our editors work in clear stages, so your manuscript reads sharp, consistent and error-free from the first page to the last.', array( 'Developmental / structural editing', 'Line & copy editing', 'Final proofread', 'Style sheet & consistency pass' ) ),
		array( 'book', 'Publishing & Distribution', 'We format, convert and publish your book across major platforms as print and ebook — you keep the rights.', 'We handle the technical side of getting your book listed and available everywhere readers shop, in both print and digital formats. You keep 100% of your ownership and royalties.', array( 'Print & ebook formatting', 'Platform setup & upload', 'ISBN & metadata', 'Global distribution — you keep the rights' ) ),
		array( 'design', 'Cover & Interior Design', 'Original, market-ready cover concepts and clean interior layouts that make your book look its best.', 'Readers really do judge a book by its cover. Our designers craft original covers and polished interiors built to make your book look like it belongs on a bestseller shelf.', array( 'Three original cover concepts', 'Print & ebook interior layout', 'Typesetting & formatting', 'Print-ready files' ) ),
		array( 'megaphone', 'Book Marketing', 'Author branding, social media and launch campaigns that build visibility and reach the right readers.', 'A finished book is the start line, not the finish. Our marketing team builds an author brand and a launch that connects your book with the readers who will love it.', array( 'Launch strategy & timeline', 'Social media & author branding', 'Advertising campaigns', 'Reviews & media outreach' ) ),
		array( 'print', 'Book Printing', 'Premium print production with quality paper stocks, professional finishes and reliable fulfilment.', 'Bring your manuscript to life in print. We produce beautiful physical copies with premium paper and finishes — perfect for author copies, signings and events.', array( 'Premium paper & finishes', 'Hardcover, paperback & special editions', 'Proof copies before print run', 'Author-copy fulfilment' ) ),
		array( 'monitor', 'Author Website Setup', 'A polished author website that showcases your book, grows your list and gives readers one home online.', 'Give readers one place to find you. We build a clean, mobile-friendly author website that showcases your book, captures email signups and grows with your author brand.', array( 'Custom author website', 'Book & author bio pages', 'Mailing-list signup', 'Mobile-friendly & SEO-ready' ) ),
		array( 'mic', 'Media & Interview Booking', 'We pitch and arrange podcast, radio and TV interviews to put you and your story in front of new audiences.', 'Great publicity turns authors into authorities. We pitch your story and secure interview opportunities that grow your audience and your book’s reach.', array( 'Podcast & radio pitching', 'TV / interview booking', 'Media kit & talking points', 'Interview preparation & coaching' ) ),
	);
}

function bookwright_default_testimonials() {
	return array(
		array( 'Rebecca Hale', 'They took my half-finished draft and turned it into a book I’m proud to hand to anyone. The whole process was clear and completely stress-free.', 'First-time author', 5, 'avatar-1.svg' ),
		array( 'James Okoro', 'From ghostwriting to launch, one team handled everything and kept me updated at every step — and I still own 100% of my book.', 'Business author', 5, 'avatar-3.svg' ),
		array( 'Sofia Marín', 'The cover and the marketing launch went beyond what I imagined. My release week was the best I could have hoped for.', 'Memoir author', 5, 'avatar-2.svg' ),
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
			'name' => 'Publish', 'price' => 'Custom', 'period' => 'tailored quote', 'featured' => false,
			'desc' => 'For authors with a finished manuscript who need it edited and published professionally.',
			'features' => "Copy editing & proofreading\nPrint & ebook formatting\nCover design\nPublishing & distribution setup\n!Ghostwriting\n!Marketing & launch",
		),
		array(
			'name' => 'Complete Author', 'price' => 'Custom', 'period' => 'tailored quote', 'featured' => true,
			'desc' => 'Our most popular package — we write, edit, design, publish and launch your book for you.',
			'features' => "Ghostwriting or full editing\nCustom cover & interior design\nPrint & ebook publishing\nGlobal distribution — you keep the rights\nLaunch marketing campaign\nDedicated project manager",
		),
		array(
			'name' => 'Author Brand', 'price' => 'Custom', 'period' => 'tailored quote', 'featured' => false,
			'desc' => 'Everything you need to publish and build a lasting author platform.',
			'features' => "Everything in Complete Author\nProfessional book printing\nAuthor website setup\nMedia & interview booking\nOngoing marketing support\nPriority project support",
		),
	);
}

function bookwright_default_faqs() {
	return array(
		array( 'Do I keep the rights to my book?', 'Always. You remain the sole author and keep 100% of your rights and royalties. We are a publishing-assistance company that works behind the scenes — we never take ownership of your work.' ),
		array( 'Do you write the book for me?', 'We can. Our ghostwriters turn your ideas, notes or interviews into a finished manuscript in your voice — and the book is credited entirely to you.' ),
		array( 'How much does it cost?', 'Every project is different, so we give you an honest, tailored quote with no hidden fees. Book a free consultation and we’ll recommend only the services you actually need.' ),
		array( 'How long does the process take?', 'It depends on the services and manuscript length. After your consultation we give you a clear timeline with milestones — and we’re known for delivering on time.' ),
		array( 'Can I choose only the services I need?', 'Yes. Pick a package or build a custom plan — from writing and editing to design, printing, publishing and marketing. You only pay for what fits your goals.' ),
		array( 'Are you affiliated with a specific retailer or platform?', 'No. We are an independent publishing-assistance company and are not affiliated with any single retailer or platform, so our advice stays in your best interest.' ),
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
