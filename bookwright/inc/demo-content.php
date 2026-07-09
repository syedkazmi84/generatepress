<?php
/**
 * One-click demo content.
 *
 * Runs once when the theme is activated: creates the core pages (assigned to
 * their page templates), a Blog posts page, the primary & footer menus,
 * sample journal posts and a full sample "Books" catalog — so activating the
 * theme yields a complete, ready-to-browse website.
 *
 * @package Bookwright
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Kick off the import after the theme is switched on.
 */
function bookwright_import_demo() {
	if ( get_option( 'bookwright_demo_imported' ) ) {
		return;
	}

	$pages = bookwright_create_pages();
	bookwright_configure_front_page( $pages );
	bookwright_create_posts();
	bookwright_create_books();
	bookwright_seed_components();
	bookwright_build_menus( $pages );

	update_option( 'bookwright_demo_imported', BOOKWRIGHT_VERSION );
	// Flush so the /books/ archive and permalinks work immediately.
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'bookwright_import_demo' );

/**
 * Seed the editable component post types (services, testimonials, team,
 * pricing plans, FAQs). Runs once, guarded by its own option so existing
 * installs also pick it up without needing to reactivate the theme.
 */
function bookwright_seed_components() {
	if ( get_option( 'bookwright_components_seeded' ) ) {
		return;
	}

	// Services.
	foreach ( bookwright_default_services() as $i => $s ) {
		$body = '<p>' . esc_html( isset( $s[3] ) ? $s[3] : $s[2] ) . '</p>';
		if ( ! empty( $s[4] ) ) {
			$body .= '<ul class="bw-checklist">';
			foreach ( $s[4] as $feat ) {
				$body .= '<li>' . esc_html( $feat ) . '</li>';
			}
			$body .= '</ul>';
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'bw_service',
				'post_status'  => 'publish',
				'post_title'   => $s[1],
				'post_excerpt' => $s[2],
				'post_content' => $body,
				'menu_order'   => $i,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_bw_icon', $s[0] );
		}
	}

	// Testimonials.
	foreach ( bookwright_default_testimonials() as $i => $t ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'bw_testimonial',
				'post_status'  => 'publish',
				'post_title'   => $t[0],
				'post_content' => $t[1],
				'menu_order'   => $i,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_bw_role', $t[2] );
			update_post_meta( $id, '_bw_rating', $t[3] );
			update_post_meta( $id, '_bw_photo', $t[4] );
		}
	}

	// Team.
	foreach ( bookwright_default_team() as $i => $m ) {
		$id = wp_insert_post(
			array(
				'post_type'   => 'bw_team',
				'post_status' => 'publish',
				'post_title'  => $m[0],
				'menu_order'  => $i,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_bw_role', $m[1] );
			update_post_meta( $id, '_bw_photo', $m[2] );
		}
	}

	// Pricing plans.
	foreach ( bookwright_default_plans() as $i => $d ) {
		$id = wp_insert_post(
			array(
				'post_type'   => 'bw_plan',
				'post_status' => 'publish',
				'post_title'  => $d['name'],
				'menu_order'  => $i,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_bw_price', $d['price'] );
			update_post_meta( $id, '_bw_period', $d['period'] );
			update_post_meta( $id, '_bw_desc', $d['desc'] );
			update_post_meta( $id, '_bw_featured', $d['featured'] ? '1' : '' );
			update_post_meta( $id, '_bw_features', $d['features'] );
		}
	}

	// FAQs.
	foreach ( bookwright_default_faqs() as $i => $f ) {
		wp_insert_post(
			array(
				'post_type'    => 'bw_faq',
				'post_status'  => 'publish',
				'post_title'   => $f[0],
				'post_content' => $f[1],
				'menu_order'   => $i,
			)
		);
	}

	update_option( 'bookwright_components_seeded', BOOKWRIGHT_VERSION );
}

/**
 * Back-fill the editable components for sites that activated Bookwright before
 * this feature existed (their demo-imported flag is already set).
 */
function bookwright_maybe_seed_components() {
	if ( get_option( 'bookwright_demo_imported' ) && ! get_option( 'bookwright_components_seeded' ) ) {
		bookwright_seed_components();
	}
}
add_action( 'admin_init', 'bookwright_maybe_seed_components' );

/**
 * Create (or find) a page by slug, optionally with a template.
 */
function bookwright_make_page( $title, $slug, $content = '', $template = '' ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		if ( $template ) {
			update_post_meta( $existing->ID, '_wp_page_template', $template );
		}
		return $existing->ID;
	}

	$id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		)
	);

	if ( $id && ! is_wp_error( $id ) && $template ) {
		update_post_meta( $id, '_wp_page_template', $template );
	}
	return $id;
}

/**
 * Create all core pages and return a slug => ID map.
 */
function bookwright_create_pages() {
	$map = array();

	$map['home']      = bookwright_make_page( __( 'Home', 'bookwright' ), 'home', '' );
	$map['about']     = bookwright_make_page( __( 'About', 'bookwright' ), 'about', '', 'page-templates/tpl-about.php' );
	$map['services']  = bookwright_make_page( __( 'Services', 'bookwright' ), 'services', '', 'page-templates/tpl-services.php' );
	$map['pricing']   = bookwright_make_page( __( 'Pricing', 'bookwright' ), 'pricing', '', 'page-templates/tpl-pricing.php' );
	$map['portfolio'] = bookwright_make_page( __( 'Portfolio', 'bookwright' ), 'portfolio', '', 'page-templates/tpl-portfolio.php' );
	$map['contact']   = bookwright_make_page( __( 'Contact', 'bookwright' ), 'contact', '', 'page-templates/tpl-contact.php' );
	$map['blog']      = bookwright_make_page( __( 'Journal', 'bookwright' ), 'journal', '' );

	return $map;
}

/**
 * Set the static front page and posts page.
 */
function bookwright_configure_front_page( $pages ) {
	if ( ! empty( $pages['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['home'] );
	}
	if ( ! empty( $pages['blog'] ) ) {
		update_option( 'page_for_posts', $pages['blog'] );
	}
}

/**
 * Sample journal posts.
 */
function bookwright_create_posts() {
	if ( get_option( 'bookwright_posts_created' ) ) {
		return;
	}

	// Ensure categories exist.
	$cats = array( 'Publishing', 'Writing Craft', 'Book Marketing', 'Design' );
	foreach ( $cats as $c ) {
		if ( ! term_exists( $c, 'category' ) ) {
			wp_insert_term( $c, 'category' );
		}
	}

	$posts = array(
		array(
			'title'   => 'Self-Publishing vs. Traditional: Which Path Fits Your Book?',
			'cat'     => 'Publishing',
			'excerpt' => 'Royalties, control, timelines and reach — a clear-eyed comparison to help you choose the publishing route that serves your goals.',
			'body'    => "<p>Every author eventually faces the same fork in the road: pursue a traditional publishing deal, or take the reins and self-publish. Neither path is objectively better — they simply optimise for different things.</p><h2>The case for traditional publishing</h2><p>A traditional deal brings an advance, an established distribution network, and the credibility of a recognised imprint. In exchange, you give up a large share of royalties and most creative control, and you wait — often 18 to 24 months from contract to shelf.</p><h2>The case for self-publishing</h2><p>Self-publishing hands you the wheel. You keep up to 70% of royalties, set your own timeline, and retain final say on cover, price and marketing. The trade-off is that every decision — and every cost — is yours.</p><blockquote>The best book is the one that actually reaches readers. Choose the path that gets yours into their hands.</blockquote><h2>How we help either way</h2><p>At Bookwright we prepare manuscripts to a professional standard regardless of route — so whether an agent or an algorithm is your gatekeeper, your book is ready.</p><ul><li>Editorial assessment and developmental editing</li><li>Query letters and submission packages for agents</li><li>Full self-publishing setup across Amazon KDP, IngramSpark and Apple Books</li></ul>",
		),
		array(
			'title'   => 'Anatomy of a Cover That Sells',
			'cat'     => 'Design',
			'excerpt' => 'A great cover does a specific job in a fraction of a second. Here is what separates covers that convert from covers that get scrolled past.',
			'body'    => "<p>Readers really do judge a book by its cover — and they do it in about a tenth of a second on a thumbnail-sized image. Your cover has one job in that instant: signal genre and promise.</p><h2>1. Genre legibility</h2><p>The single biggest mistake is a cover that fights its own genre. A cosy mystery should not look like literary fiction. Study the top 50 in your category and speak the visual language readers already trust.</p><h2>2. Typographic hierarchy</h2><p>Title, then author, then everything else. At thumbnail scale the title must remain readable. If it disappears, the cover has failed.</p><h2>3. A single focal idea</h2><p>The strongest covers commit to one image, one mood, one hook. Clutter reads as amateur.</p><p>Our design team creates three distinct concepts for every book, then refines the winner across print and ebook formats.</p>",
		),
		array(
			'title'   => 'Your Launch Week Marketing Checklist',
			'cat'     => 'Book Marketing',
			'excerpt' => 'The seven days around your release matter more than any other. Use this checklist to make launch week count.',
			'body'    => "<p>Momentum in the first week feeds the algorithms that decide who else sees your book. Preparation is everything.</p><h2>Two weeks before</h2><ul><li>Finalise your book description and keywords</li><li>Line up newsletter swaps and podcast guest spots</li><li>Schedule your email sequence</li></ul><h2>Launch day</h2><ul><li>Email your list first — they are your most likely buyers</li><li>Post to every platform with a clear call to action</li><li>Ask early readers to leave honest reviews</li></ul><h2>The week after</h2><p>Keep the drumbeat going with reader questions, behind-the-scenes content and a small paid push once you have a handful of reviews.</p>",
		),
		array(
			'title'   => 'How to Find (and Keep) the Right Editor',
			'cat'     => 'Writing Craft',
			'excerpt' => 'A good editor makes your book unmistakably yours — only sharper. Here is how to find the right fit and build a lasting partnership.',
			'body'    => "<p>Editing is not about imposing a house style on your voice. The right editor amplifies what already works and gently fixes what does not.</p><h2>Know which edit you need</h2><p>Developmental editing shapes structure and story. Line editing refines prose. Copyediting fixes grammar and consistency. Proofreading is the final polish. Ordering them out of sequence wastes money.</p><h2>Ask for a sample</h2><p>Any editor worth hiring will edit a few pages so you can feel the collaboration before committing.</p><p>Every Bookwright project pairs you with a dedicated editor and a clear, milestone-based schedule — no surprises, no ghosting.</p>",
		),
	);

	foreach ( $posts as $p ) {
		$term  = get_term_by( 'name', $p['cat'], 'category' );
		$id = wp_insert_post(
			array(
				'post_title'   => $p['title'],
				'post_content' => $p['body'],
				'post_excerpt' => $p['excerpt'],
				'post_status'  => 'publish',
				'post_type'    => 'post',
				'post_category' => $term ? array( $term->term_id ) : array(),
			)
		);
	}

	update_option( 'bookwright_posts_created', 1 );
}

/**
 * Sample portfolio projects (showcase only — no pricing).
 */
function bookwright_create_books() {
	if ( get_option( 'bookwright_books_created' ) ) {
		return;
	}

	$cats = array( 'Fiction', 'Non-Fiction', 'Memoir', 'Children', 'Business', 'Poetry' );
	foreach ( $cats as $c ) {
		if ( ! term_exists( $c, 'genre' ) ) {
			wp_insert_term( $c, 'genre' );
		}
	}

	// title, client/author, category, service provided, short description
	$projects = array(
		array( 'The Founder\'s Compass', 'Rosa Delgado', 'Business', 'Editing & Cover Design', 'A practical guide to the first thousand days of a startup, edited and designed for a confident launch.' ),
		array( 'Quiet Wealth', 'James Okoro', 'Business', 'Ghostwriting & Marketing', 'Ghostwritten from a series of interviews, then launched with a full author-branding campaign.' ),
		array( 'Saltwater Girlhood', 'Nadia Okafor', 'Memoir', 'Full Publishing', 'A luminous coming-of-age memoir taken from manuscript to print, ebook and launch.' ),
		array( 'The Clockwork Garden', 'Theo Marsh', 'Children', 'Cover & Interior Design', 'An illustrated children\'s adventure with custom cover art and a playful interior layout.' ),
		array( 'Field Notes on Wonder', 'Sofia Marín', 'Poetry', 'Editing & Publishing', 'A debut poetry collection carefully edited and typeset for both print and digital readers.' ),
		array( 'Signal & Noise', 'Priya Raman', 'Non-Fiction', 'Publishing & Marketing', 'A non-fiction title published across major platforms and supported with a targeted ad campaign.' ),
		array( 'Letters We Never Sent', 'Daniel Cho', 'Fiction', 'Ghostwriting & Editing', 'A literary novel developed with our writing team and polished through developmental and line editing.' ),
		array( 'Building a Life of Meaning', 'Amara Bright', 'Non-Fiction', 'Full Service', 'Written, edited, designed and marketed end-to-end — with the author keeping 100% ownership.' ),
	);

	$i = 0;
	foreach ( $projects as $b ) {
		list( $title, $client, $cat, $service, $desc ) = $b;
		$id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_content' => '<p>' . esc_html( $desc ) . '</p><p>' . esc_html( $client ) . ' partnered with our team for ' . esc_html( strtolower( $service ) ) . '. The author remained the sole owner and rights-holder throughout.</p>',
				'post_excerpt' => $desc,
				'post_status'  => 'publish',
				'post_type'    => 'book',
				'menu_order'   => $i,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_bw_author', $client );
			update_post_meta( $id, '_bw_service', $service );
			update_post_meta( $id, '_bw_cover', 'cover-' . ( ( $i % 6 ) + 1 ) . '.svg' );
			wp_set_object_terms( $id, $cat, 'genre' );
		}
		$i++;
	}

	update_option( 'bookwright_books_created', 1 );
}

/**
 * Build primary + footer menus and assign to locations.
 */
function bookwright_build_menus( $pages ) {
	$menu_name = 'Primary Navigation';
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );

		$items = array(
			array( 'Home', $pages['home'] ),
			array( 'About', $pages['about'] ),
			array( 'Services', $pages['services'] ),
			array( 'Pricing', $pages['pricing'] ),
			array( 'Portfolio', $pages['portfolio'] ),
			array( 'Journal', $pages['blog'] ),
			array( 'Contact', $pages['contact'] ),
		);

		foreach ( $items as $item ) {
			if ( empty( $item[1] ) ) {
				continue;
			}
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => $item[0],
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $item[1],
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				)
			);
		}

		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		$locations['footer']  = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}
