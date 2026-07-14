<?php
/**
 * Demo content library for Quill & Press.
 *
 * Defines every page, blog post, menu and theme setting the one-click importer
 * builds. All page bodies are assembled from the block helpers in
 * block-helpers.php so the result is native, editable WordPress editor content.
 *
 * @package Quill_Press
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build an internal URL for a demo page slug.
 */
function qp_url( $slug = '' ) {
	return home_url( '/' . ltrim( $slug, '/' ) . ( $slug ? '/' : '' ) );
}

/* -------------------------------------------------------------------------
 * Reusable content blocks
 * ---------------------------------------------------------------------- */

/**
 * Closing call-to-action band used on most pages.
 */
function qp_c_cta( $heading = 'Ready to tell your story?', $text = 'Book a free 30-minute consultation and get a tailored publishing plan for your manuscript — no obligation, no jargon.' ) {
	$inner  = qp_heading( 2, $heading, 'qp-h2', true );
	$inner .= qp_para( $text, 'qp-lead qp-narrow', true );
	$inner .= qp_buttons(
		array(
			array(
				'text' => 'Get a Free Quote',
				'url'  => qp_url( 'contact' ),
				'class' => 'qp-btn',
			),
			array(
				'text'  => 'View Pricing',
				'url'   => qp_url( 'pricing' ),
				'class' => 'qp-btn qp-btn--light',
			),
		),
		true
	);
	return qp_section( 'qp-section--ink qp-cta qp-section--lg', $inner, '820px' );
}

/**
 * The six services, reused across the site.
 */
function qp_services_list() {
	return array(
		'editing'      => array( 'icon-editing.svg', 'Editing &amp; Proofreading', 'Developmental, line, and copy editing that sharpens your voice while keeping it unmistakably yours.', 'editing' ),
		'ghostwriting' => array( 'icon-ghostwriting.svg', 'Ghostwriting', 'Have a story but not the time? Our writers turn your ideas, notes and interviews into a finished manuscript.', 'ghostwriting' ),
		'design'       => array( 'icon-design.svg', 'Cover Design', 'Scroll-stopping covers designed to sell — crafted by artists who read your genre as well as your book.', 'cover-design' ),
		'formatting'   => array( 'icon-formatting.svg', 'Formatting &amp; Typesetting', 'Print-ready interiors and flawless eBooks that look beautiful on every device and every shelf.', 'formatting' ),
		'marketing'    => array( 'icon-marketing.svg', 'Book Marketing', 'Launch strategy, Amazon optimisation and publicity that puts your book in front of real readers.', 'marketing' ),
		'distribution' => array( 'icon-distribution.svg', 'Publishing &amp; Distribution', 'Global distribution to 40,000+ retailers and libraries, with you keeping 100% of your rights and royalties.', 'distribution' ),
	);
}

/**
 * Grid of the six service cards.
 */
function qp_c_services_grid( $with_links = true ) {
	$cols = array();
	foreach ( qp_services_list() as $s ) {
		$cols[] = qp_icon_card(
			$s[0],
			$s[1],
			$s[2],
			$with_links ? 'Learn more' : '',
			$with_links ? qp_url( $s[3] ) : ''
		);
	}
	// Three rows of two on wide screens via a single 3-col grid that wraps.
	$row1 = qp_columns( array_slice( $cols, 0, 3 ), 'qp-cards', '' );
	$row2 = qp_columns( array_slice( $cols, 3, 3 ), 'qp-cards', '' );
	return $row1 . qp_spacer( 24 ) . $row2;
}

/**
 * Four-step process band content.
 */
function qp_c_process() {
	$steps = array(
		array( 'Consult', 'We learn about your book, your goals and your readers, then map the smartest route to publication.' ),
		array( 'Craft', 'Editors, designers and typesetters shape your manuscript into a polished, market-ready book.' ),
		array( 'Publish', 'We format, register ISBNs and distribute your title across print, eBook and audio channels.' ),
		array( 'Grow', 'Launch marketing, reviews and ongoing promotion keep your book selling long after release day.' ),
	);
	$cols = array();
	foreach ( $steps as $st ) {
		$cols[] = qp_heading( 3, $st[0] ) . qp_para( $st[1] );
	}
	return qp_columns( $cols, 'qp-steps', '' );
}

/**
 * Stats band inner (used inside an ink section).
 */
function qp_c_stats() {
	$stats = array(
		array( '1,200+', 'Books published' ),
		array( '4.9/5', 'Average author rating' ),
		array( '40k+', 'Retail &amp; library outlets' ),
		array( '100%', 'Rights kept by you' ),
	);
	$cols = array();
	foreach ( $stats as $s ) {
		$cols[] = qp_stat( $s[0], $s[1] );
	}
	return qp_columns( $cols, 'qp-stats', '' );
}

/**
 * Trust logo strip.
 */
function qp_c_trust() {
	$logos = array( 'logo-1.svg', 'logo-2.svg', 'logo-3.svg', 'logo-4.svg', 'logo-5.svg' );
	$cols  = array();
	foreach ( $logos as $l ) {
		$cols[] = qp_image( quillpress_img( $l ), 'Featured in', '' );
	}
	$inner  = qp_para( 'Our authors have been featured &amp; celebrated by', 'qp-eyebrow has-text-align-center', true );
	$inner .= qp_columns( $cols, '', '' );
	return qp_section( 'qp-section--paper qp-section--tight qp-trust', $inner, '980px' );
}

/* -------------------------------------------------------------------------
 * Pages
 * ---------------------------------------------------------------------- */

/**
 * HOME
 */
function qp_page_home() {
	// Hero.
	$hero_text  = qp_eyebrow( 'Full-service book publishing' );
	$hero_text .= qp_heading( 1, 'From manuscript<br>to masterpiece.', 'qp-display' );
	$hero_text .= qp_para( 'Quill &amp; Press is the partner authors trust to edit, design, publish and market their books — beautifully, honestly, and without ever taking their rights.', 'qp-lead' );
	$hero_text .= qp_buttons(
		array(
			array( 'text' => 'Get a Free Quote', 'url' => qp_url( 'contact' ), 'class' => 'qp-btn' ),
			array( 'text' => 'Explore Services', 'url' => qp_url( 'services' ), 'class' => 'qp-btn qp-btn--light' ),
		)
	);
	$hero_badges  = '<p class="qp-badge"><b>★ 4.9</b> from 900+ authors</p> <p class="qp-badge"><b>1,200+</b> books published</p>';
	$badge_attrs  = wp_json_encode( array( 'className' => 'qp-hero-badges' ) );
	$hero_text   .= "<!-- wp:paragraph {$badge_attrs} -->\n<p class=\"qp-hero-badges\">{$hero_badges}</p>\n<!-- /wp:paragraph -->\n";
	$hero_art     = qp_image( quillpress_img( 'hero-home.svg' ), 'Open book with quill and manuscript pages', 'qp-hero-art' );
	$hero_cols    = qp_columns( array( $hero_text, $hero_art ), '', '' );
	$hero         = qp_section( 'qp-section--ink qp-hero qp-section--lg', $hero_cols, '1140px' );

	// Trust strip.
	$trust = qp_c_trust();

	// Services.
	$srv  = qp_eyebrow( 'What we do', true );
	$srv .= qp_heading( 2, 'Everything your book needs,<br>under one roof.', 'qp-h2', true );
	$srv .= qp_para( 'Pick a single service or hand us the whole journey. Either way you get a dedicated project lead and a fixed, transparent quote.', 'qp-lead qp-narrow', true );
	$srv .= qp_spacer( 32 );
	$srv .= qp_c_services_grid( true );
	$services = qp_section( 'qp-section--cream', $srv );

	// Why choose us (split).
	$why_left  = qp_eyebrow( 'Why Quill &amp; Press' );
	$why_left .= qp_heading( 2, 'A publisher that works for you — not the other way around.', 'qp-h2' );
	$why_left .= qp_para( 'We started Quill &amp; Press because too many authors were handing over their rights and royalties for a service that treated their book like a number. We do the opposite.', 'qp-lead' );
	$why_left .= qp_checklist(
		array(
			'You keep 100% of your rights and royalties — always.',
			'Fixed quotes with no surprise fees, ever.',
			'A dedicated project lead from first draft to launch day.',
			'Award-winning editors and designers in every genre.',
		)
	);
	$why_left .= qp_buttons( array( array( 'text' => 'Meet the team', 'url' => qp_url( 'about' ), 'class' => 'qp-btn qp-btn--ghost' ) ) );
	$why_right = qp_image( quillpress_img( 'about-story.svg' ), 'A publishing desk with books and manuscript', 'qp-figure' );
	$why_cols  = qp_columns( array( $why_left, $why_right ), '', '' );
	$why       = qp_section( 'qp-section--paper qp-split', $why_cols );

	// Stats.
	$stats = qp_section( 'qp-section--ink', qp_c_stats(), '980px' );

	// Process.
	$proc  = qp_eyebrow( 'How it works', true );
	$proc .= qp_heading( 2, 'A clear path to publication.', 'qp-h2', true );
	$proc .= qp_para( 'No mystery, no runaround. Four straightforward stages take your book from idea to in-hand.', 'qp-lead qp-narrow', true );
	$proc .= qp_spacer( 40 );
	$proc .= qp_c_process();
	$process = qp_section( 'qp-section--cream', $proc );

	// Portfolio preview.
	$port  = qp_eyebrow( 'Recent releases', true );
	$port .= qp_heading( 2, 'Books we&#8217;re proud to have published.', 'qp-h2', true );
	$port .= qp_spacer( 32 );
	$books = array(
		qp_book_card( 'book-1.svg', 'The Cartographer&#8217;s Daughter', 'Literary Fiction · Elena Márquez' ),
		qp_book_card( 'book-2.svg', 'Midnight in the Archive', 'Mystery · J. Hawthorne Reed' ),
		qp_book_card( 'book-4.svg', 'Scaling Signal', 'Business · Dr. Amara Wells' ),
		qp_book_card( 'book-6.svg', 'The Quiet Orbit', 'Sci-Fi · T. R. Voss' ),
	);
	$port .= qp_columns( $books, 'qp-books', '' );
	$port .= qp_spacer( 32 );
	$port .= qp_buttons( array( array( 'text' => 'See the full portfolio', 'url' => qp_url( 'portfolio' ), 'class' => 'qp-btn qp-btn--ghost' ) ), true );
	$portfolio = qp_section( 'qp-section--paper', $port );

	// Testimonials.
	$tst  = qp_eyebrow( 'Author stories', true );
	$tst .= qp_heading( 2, 'Loved by first-time and bestselling authors alike.', 'qp-h2', true );
	$tst .= qp_spacer( 32 );
	$quotes = array(
		qp_quote_card( 5, 'They took my messy 90,000-word draft and gave me back a book I actually wanted to hold. The editing was thoughtful and the cover sells itself.', 'avatar-1.svg', 'Elena Márquez', 'Author, The Cartographer&#8217;s Daughter' ),
		qp_quote_card( 5, 'I kept every penny of my royalties and still got a launch that hit #1 in my category. That combination simply doesn&#8217;t exist elsewhere.', 'avatar-2.svg', 'Dr. Amara Wells', 'Author, Scaling Signal' ),
		qp_quote_card( 5, 'As a first-time author I was terrified. My project lead walked me through every step and made the whole thing feel genuinely fun.', 'avatar-3.svg', 'Maia Okonkwo', 'Author, Bloom' ),
	);
	$tst .= qp_columns( $quotes, 'qp-quotes', '' );
	$testimonials = qp_section( 'qp-section--cream', $tst );

	return $hero . $trust . $services . $why . $stats . $process . $portfolio . $testimonials . qp_c_cta();
}

/**
 * Generic page header section.
 */
function qp_page_header( $eyebrow, $title, $lead, $variant = 'cream', $button = true ) {
	$inner  = qp_eyebrow( $eyebrow, true );
	$inner .= qp_heading( 1, $title, 'qp-display', true );
	$inner .= qp_para( $lead, 'qp-lead qp-narrow', true );
	if ( $button ) {
		$inner .= qp_buttons( array( array( 'text' => 'Get a Free Quote', 'url' => qp_url( 'contact' ), 'class' => 'qp-btn' ) ), true );
	}
	$cls = 'cream' === $variant ? 'qp-section--cream qp-section--lg' : 'qp-section--ink qp-hero qp-section--lg';
	return qp_section( $cls, $inner, '860px' );
}

/**
 * ABOUT
 */
function qp_page_about() {
	$header = qp_page_header(
		'About Quill &amp; Press',
		'We help authors publish<br>books they&#8217;re proud of.',
		'A team of editors, designers and marketers on a mission to make honest, high-quality publishing available to every writer.',
		'cream'
	);

	// Story split.
	$left  = qp_eyebrow( 'Our story' );
	$left .= qp_heading( 2, 'Built by authors, for authors.', 'qp-h2' );
	$left .= qp_para( 'Quill &amp; Press began in 2014 in a cramped studio above a second-hand bookshop. Our founders — a frustrated novelist and a book designer — were tired of watching writers sign away their rights for cookie-cutter service and hidden fees.', 'qp-lead' );
	$left .= qp_para( 'A decade later we&#8217;ve helped more than 1,200 authors bring their books to life, from debut poets to New York Times bestsellers. What hasn&#8217;t changed is our belief: your book is yours, and it deserves craft, honesty and care at every step.' );
	$left .= qp_checklist(
		array(
			'Independent &amp; author-owned since day one.',
			'Editors and designers with 15+ years&#8217; experience.',
			'A transparent, fixed-price promise on every project.',
		)
	);
	$right = qp_image( quillpress_img( 'about-story.svg' ), 'The Quill & Press studio desk', 'qp-figure' );
	$story = qp_section( 'qp-section--paper qp-split', qp_columns( array( $left, $right ), '', '' ) );

	// Stats.
	$stats = qp_section( 'qp-section--ink', qp_c_stats(), '980px' );

	// Values.
	$val  = qp_eyebrow( 'What we stand for', true );
	$val .= qp_heading( 2, 'Four values behind every book.', 'qp-h2', true );
	$val .= qp_spacer( 32 );
	$values = array(
		qp_icon_card( 'icon-editing.svg', 'Craftsmanship', 'We treat every manuscript like it could become a classic — because one day, one of them will.' ),
		qp_icon_card( 'icon-distribution.svg', 'Partnership', 'You get a real person, a real plan and real answers. We succeed only when your book does.' ),
		qp_icon_card( 'icon-formatting.svg', 'Transparency', 'Fixed quotes, plain English, and your rights kept firmly in your hands. No fine print.' ),
		qp_icon_card( 'icon-marketing.svg', 'Results', 'Beautiful books are the start. We build the launch and marketing that get them read.' ),
	);
	$row1 = qp_columns( array_slice( $values, 0, 2 ), 'qp-cards', '' );
	$row2 = qp_columns( array_slice( $values, 2, 2 ), 'qp-cards', '' );
	$val .= $row1 . qp_spacer( 24 ) . $row2;
	$valsec = qp_section( 'qp-section--cream', $val );

	// Team.
	$team  = qp_eyebrow( 'The people', true );
	$team .= qp_heading( 2, 'Meet your publishing team.', 'qp-h2', true );
	$team .= qp_spacer( 32 );
	$members = array(
		array( 'avatar-1.svg', 'Eleanor Vance', 'Founder &amp; Editorial Director' ),
		array( 'avatar-4.svg', 'Marcus Bright', 'Creative Director, Design' ),
		array( 'avatar-3.svg', 'Priya Nair', 'Head of Author Marketing' ),
		array( 'avatar-2.svg', 'James Okoro', 'Production &amp; Distribution Lead' ),
	);
	$cols = array();
	foreach ( $members as $m ) {
		$c  = qp_image( quillpress_img( $m[0] ), $m[1], 'qp-round', 96 );
		$c .= qp_heading( 3, $m[1] );
		$c .= qp_para( $m[2], 'qp-muted' );
		$cols[] = $c;
	}
	$team .= qp_columns( $cols, 'qp-cards', '' );
	$teamsec = qp_section( 'qp-section--paper', $team );

	return $header . $story . $stats . $valsec . $teamsec . qp_c_cta();
}

/**
 * SERVICES overview
 */
function qp_page_services() {
	$header = qp_page_header(
		'Our services',
		'Publishing services<br>for every stage.',
		'Whether you need a single polish or the full journey from blank page to bestseller list, we have a service — and a specialist — for it.',
		'ink'
	);
	$grid = qp_section( 'qp-section--paper', qp_c_services_grid( true ) );

	$proc  = qp_eyebrow( 'How it works', true );
	$proc .= qp_heading( 2, 'One team, one clear process.', 'qp-h2', true );
	$proc .= qp_spacer( 40 );
	$proc .= qp_c_process();
	$process = qp_section( 'qp-section--cream', $proc );

	return $header . $grid . $process . qp_c_cta();
}

/**
 * Generic service page builder.
 */
function qp_build_service_page( $args ) {
	$header  = qp_eyebrow( 'Service', false );
	$hleft   = qp_eyebrow( $args['eyebrow'] );
	$hleft  .= qp_heading( 1, $args['title'], 'qp-display' );
	$hleft  .= qp_para( $args['lead'], 'qp-lead' );
	$hleft  .= qp_buttons( array( array( 'text' => 'Request a Quote', 'url' => qp_url( 'contact' ), 'class' => 'qp-btn' ) ) );
	$hart    = qp_image( quillpress_img( $args['image'] ), $args['title'], 'qp-figure' );
	$header  = qp_section( 'qp-section--cream qp-split qp-section--lg', qp_columns( array( $hleft, $hart ), '', '' ) );

	// What's included split.
	$left  = qp_eyebrow( 'What&#8217;s included' );
	$left .= qp_heading( 2, $args['included_title'], 'qp-h2' );
	$left .= qp_para( $args['included_lead'] );
	$left .= qp_checklist( $args['included'] );
	$right = qp_image( quillpress_img( $args['image2'] ), $args['title'], 'qp-figure' );
	$incl  = qp_section( 'qp-section--paper qp-split', qp_columns( array( $left, $right ), '', '' ) );

	// Highlights.
	$hl  = qp_eyebrow( 'Why it matters', true );
	$hl .= qp_heading( 2, $args['highlights_title'], 'qp-h2', true );
	$hl .= qp_spacer( 32 );
	$cols = array();
	foreach ( $args['highlights'] as $h ) {
		$cols[] = qp_icon_card( $h[0], $h[1], $h[2] );
	}
	$hl .= qp_columns( $cols, 'qp-cards', '' );
	$high = qp_section( 'qp-section--cream', $hl );

	return $header . $incl . $high . qp_c_cta( 'Let&#8217;s make your book shine.', 'Tell us about your project and we&#8217;ll send a fixed quote within two working days.' );
}

function qp_page_editing() {
	return qp_build_service_page(
		array(
			'eyebrow'        => 'Editing &amp; Proofreading',
			'title'          => 'Editing that respects your voice.',
			'lead'           => 'From big-picture structure to the final comma, our editors make your writing the best version of itself — never a different one.',
			'image'          => 'hero-home.svg',
			'image2'         => 'about-story.svg',
			'included_title' => 'Three levels of editing, one seamless process.',
			'included_lead'  => 'Not sure what your manuscript needs? Send us a sample and we&#8217;ll recommend the right level — and only the right level.',
			'included'       => array(
				'<strong>Developmental editing</strong> — structure, pacing, character and argument.',
				'<strong>Line editing</strong> — rhythm, clarity and style, sentence by sentence.',
				'<strong>Copy editing</strong> — grammar, consistency and a house-quality style sheet.',
				'<strong>Proofreading</strong> — a final, fresh-eyes pass before you go to print.',
			),
			'highlights_title' => 'Editing you can actually trust.',
			'highlights'     => array(
				array( 'icon-editing.svg', 'Genre specialists', 'Your editor is matched to your genre, so they know the conventions readers expect.' ),
				array( 'icon-formatting.svg', 'Track-changes clarity', 'Every suggestion is transparent, commented and reversible — you stay in control.' ),
				array( 'icon-distribution.svg', 'A free sample edit', 'See our editing on your own words before you commit a single penny.' ),
			),
		)
	);
}

function qp_page_ghostwriting() {
	return qp_build_service_page(
		array(
			'eyebrow'        => 'Ghostwriting',
			'title'          => 'Your story, expertly told.',
			'lead'           => 'You bring the ideas, the expertise and the lived experience. Our ghostwriters turn them into a compelling, finished manuscript in your voice.',
			'image'          => 'about-story.svg',
			'image2'         => 'hero-home.svg',
			'included_title' => 'A collaborative writing partnership.',
			'included_lead'  => 'Ghostwriting with Quill &amp; Press is a genuine collaboration — your fingerprints are on every page, minus the blank-page struggle.',
			'included'       => array(
				'Structured interviews to capture your voice and story.',
				'A detailed outline and chapter plan for your approval.',
				'Draft chapters delivered on a clear, predictable schedule.',
				'Two full revision rounds so every page sounds like you.',
			),
			'highlights_title' => 'Books written with you, not just for you.',
			'highlights'     => array(
				array( 'icon-ghostwriting.svg', 'Memoir &amp; non-fiction', 'From founder stories to family memoirs, we specialise in narrative that resonates.' ),
				array( 'icon-editing.svg', 'Your voice, kept', 'We study how you speak and write, so readers hear you — not a ghost.' ),
				array( 'icon-distribution.svg', 'Full confidentiality', 'Ironclad NDAs and complete discretion are standard on every project.' ),
			),
		)
	);
}

function qp_page_cover_design() {
	return qp_build_service_page(
		array(
			'eyebrow'        => 'Cover Design',
			'title'          => 'Covers that sell the book.',
			'lead'           => 'Readers really do judge a book by its cover. Ours are designed to stop the scroll, signal the genre and earn the click.',
			'image'          => 'hero-home.svg',
			'image2'         => 'about-story.svg',
			'included_title' => 'Design that&#8217;s as strategic as it is beautiful.',
			'included_lead'  => 'Every cover starts with your genre, your comps and your reader — not just a pretty picture.',
			'included'       => array(
				'Custom front cover with three initial concepts.',
				'Full print wrap — spine and back cover — ready for KDP &amp; IngramSpark.',
				'eBook and audiobook cover variants, correctly sized.',
				'Thumbnail testing so it pops at 100 pixels wide.',
			),
			'highlights_title' => 'Judged by its cover — in the best way.',
			'highlights'     => array(
				array( 'icon-design.svg', 'Genre-savvy artists', 'Designers who read your category and know exactly what its readers reach for.' ),
				array( 'icon-marketing.svg', 'Built to convert', 'Composition and typography tuned for the tiny thumbnail where books are really sold.' ),
				array( 'icon-formatting.svg', 'Print-perfect files', 'Bleed, spine width and colour profiles handled — no rejected uploads.' ),
			),
		)
	);
}

function qp_page_formatting() {
	return qp_build_service_page(
		array(
			'eyebrow'        => 'Formatting &amp; Typesetting',
			'title'          => 'Interiors worth turning the page for.',
			'lead'           => 'Professional typesetting makes your book comfortable to read and unmistakably premium — in print and on every screen.',
			'image'          => 'about-story.svg',
			'image2'         => 'hero-home.svg',
			'included_title' => 'Print and digital, done right.',
			'included_lead'  => 'We hand-craft your interior layout instead of running it through a template, so the reading experience feels effortless.',
			'included'       => array(
				'Print-ready PDF typeset to your trim size.',
				'Reflowable ePub and Kindle files, validated and clean.',
				'Custom chapter openers, drop caps and running heads.',
				'Tables, images and footnotes placed with care.',
			),
			'highlights_title' => 'Typesetting readers feel but never notice.',
			'highlights'     => array(
				array( 'icon-formatting.svg', 'Typographic craft', 'Considered fonts, spacing and hierarchy that make long reads a pleasure.' ),
				array( 'icon-distribution.svg', 'Every device', 'Your eBook looks right on Kindle, Kobo, Apple Books and everything between.' ),
				array( 'icon-editing.svg', 'Zero upload headaches', 'Files that pass retailer validation the first time, guaranteed.' ),
			),
		)
	);
}

function qp_page_marketing() {
	return qp_build_service_page(
		array(
			'eyebrow'        => 'Book Marketing',
			'title'          => 'Launches that find real readers.',
			'lead'           => 'A great book nobody knows about is a tragedy. Our marketing team builds the launch and momentum your title deserves.',
			'image'          => 'hero-home.svg',
			'image2'         => 'about-story.svg',
			'included_title' => 'A launch plan built around your book.',
			'included_lead'  => 'From pre-orders to post-launch, we run the campaigns that turn browsers into buyers and buyers into fans.',
			'included'       => array(
				'Amazon &amp; retailer listing optimisation (the "book SEO" that matters).',
				'Launch-team and advance-reader-copy management.',
				'Editorial reviews, podcasts and media outreach.',
				'Targeted Amazon &amp; Meta ad campaigns, fully managed.',
			),
			'highlights_title' => 'Marketing that moves the needle.',
			'highlights'     => array(
				array( 'icon-marketing.svg', 'Data-driven', 'Every campaign is tracked, reported and tuned against real sales data.' ),
				array( 'icon-distribution.svg', 'Reader-first', 'We find the exact readers who already love books like yours.' ),
				array( 'icon-design.svg', 'Assets included', 'Ad creative, social graphics and a launch page designed to convert.' ),
			),
		)
	);
}

function qp_page_distribution() {
	return qp_build_service_page(
		array(
			'eyebrow'        => 'Publishing &amp; Distribution',
			'title'          => 'Everywhere readers buy books.',
			'lead'           => 'We handle the unglamorous but essential machinery of publishing — ISBNs, metadata and global distribution — so your book is available everywhere.',
			'image'          => 'about-story.svg',
			'image2'         => 'hero-home.svg',
			'included_title' => 'Global reach, with your rights intact.',
			'included_lead'  => 'You keep 100% of your rights and royalties. We simply make sure your book reaches every shelf, digital and physical.',
			'included'       => array(
				'Print-on-demand &amp; distribution to 40,000+ retailers and libraries.',
				'eBook distribution to Amazon, Apple, Kobo, Google &amp; more.',
				'ISBN registration and complete metadata setup.',
				'Audiobook production and distribution options.',
			),
			'highlights_title' => 'The reach of a big publisher, on your terms.',
			'highlights'     => array(
				array( 'icon-distribution.svg', 'Truly global', 'Physical print in local markets means faster, cheaper delivery for readers worldwide.' ),
				array( 'icon-formatting.svg', 'Metadata mastery', 'The right categories and keywords so the right readers actually find you.' ),
				array( 'icon-marketing.svg', '100% royalties', 'No rights grabs, no hidden cuts — your earnings are yours.' ),
			),
		)
	);
}

/**
 * PORTFOLIO
 */
function qp_page_portfolio() {
	$header = qp_page_header(
		'Our work',
		'Books we helped<br>into the world.',
		'A small selection of the 1,200+ titles we&#8217;ve edited, designed, published and promoted across every genre.',
		'cream',
		false
	);

	$books = array(
		qp_book_card( 'book-1.svg', 'The Cartographer&#8217;s Daughter', 'Literary Fiction · Elena Márquez' ),
		qp_book_card( 'book-2.svg', 'Midnight in the Archive', 'Mystery &amp; Thriller · J. Hawthorne Reed' ),
		qp_book_card( 'book-3.svg', 'Bloom', 'Poetry · Maia Okonkwo' ),
		qp_book_card( 'book-4.svg', 'Scaling Signal', 'Business · Dr. Amara Wells' ),
		qp_book_card( 'book-5.svg', 'Saltwater Memory', 'Memoir · Nils Andersen' ),
		qp_book_card( 'book-6.svg', 'The Quiet Orbit', 'Science Fiction · T. R. Voss' ),
	);
	$row1 = qp_columns( array_slice( $books, 0, 3 ), 'qp-books', '' );
	$row2 = qp_columns( array_slice( $books, 3, 3 ), 'qp-books', '' );
	$grid = qp_section( 'qp-section--paper', $row1 . qp_spacer( 40 ) . $row2 );

	$stats = qp_section( 'qp-section--ink', qp_c_stats(), '980px' );

	return $header . $grid . $stats . qp_c_cta( 'Your book could be next.', 'Let&#8217;s talk about how we can bring your manuscript to readers everywhere.' );
}

/**
 * PRICING
 */
function qp_page_pricing() {
	$header = qp_page_header(
		'Pricing',
		'Simple, transparent<br>publishing packages.',
		'Fixed prices, no hidden fees, and you keep every penny of your royalties. Mix and match services, or choose a complete package below.',
		'cream',
		false
	);

	$plans = array(
		qp_price_card(
			'Starter',
			'For authors who just need a professional polish.',
			'$899',
			'one-time',
			array( 'Copy editing &amp; proofreading', 'eBook formatting', 'Basic cover design', 'Retailer upload guidance' ),
			'Choose Starter',
			qp_url( 'contact' ),
			false
		),
		qp_price_card(
			'Signature',
			'Our most popular end-to-end publishing package.',
			'$2,499',
			'one-time',
			array( 'Everything in Starter', 'Line &amp; developmental editing', 'Custom print + eBook cover', 'Print &amp; eBook typesetting', 'Global distribution setup', 'Launch marketing kit' ),
			'Choose Signature',
			qp_url( 'contact' ),
			true
		),
		qp_price_card(
			'Bestseller',
			'The full team behind a serious launch.',
			'$4,999',
			'one-time',
			array( 'Everything in Signature', 'Managed ad campaigns', 'Publicity &amp; review outreach', 'Audiobook production', 'Dedicated launch manager', '12 months of support' ),
			'Choose Bestseller',
			qp_url( 'contact' ),
			false
		),
	);
	$featured_attr = array( array(), array( 'class' => 'qp-price-featured' ), array() );
	$grid = qp_columns( $plans, 'qp-pricing', '', $featured_attr );
	$pricing = qp_section( 'qp-section--paper', $grid );

	// Note + mini FAQ.
	$faq  = qp_eyebrow( 'Good to know', true );
	$faq .= qp_heading( 2, 'Pricing questions, answered.', 'qp-h2', true );
	$faq .= qp_spacer( 24 );
	$items  = qp_faq_item( 'Can I buy services individually?', 'Absolutely. Every service — editing, design, formatting, marketing and distribution — can be purchased on its own with its own fixed quote.' );
	$items .= qp_faq_item( 'Are there any royalties or hidden fees?', 'Never. You pay a one-time, fixed price and keep 100% of your royalties and rights. What you see here is what you pay.' );
	$items .= qp_faq_item( 'What if my book needs something not listed?', 'Send us the details and we&#8217;ll build a custom quote. Roughly a third of our projects are tailored packages.' );
	$faq .= qp_group( 'qp-faq', $items, '760px' );
	$faqsec = qp_section( 'qp-section--cream', $faq, '860px' );

	return $header . $pricing . $faqsec . qp_c_cta();
}

/**
 * TESTIMONIALS
 */
function qp_page_testimonials() {
	$header = qp_page_header(
		'Testimonials',
		'Authors who&#8217;d<br>publish with us again.',
		'We measure success one book — and one happy author — at a time. Here&#8217;s what a few of them have to say.',
		'cream',
		false
	);

	$quotes = array(
		qp_quote_card( 5, 'They took my messy 90,000-word draft and gave me back a book I actually wanted to hold. Thoughtful editing, a cover that sells itself.', 'avatar-1.svg', 'Elena Márquez', 'Literary Fiction' ),
		qp_quote_card( 5, 'I kept every penny of my royalties and still hit #1 in my category. That combination simply doesn&#8217;t exist elsewhere.', 'avatar-2.svg', 'Dr. Amara Wells', 'Business Non-fiction' ),
		qp_quote_card( 5, 'As a first-time author I was terrified. My project lead made the whole thing feel genuinely fun.', 'avatar-3.svg', 'Maia Okonkwo', 'Poetry' ),
		qp_quote_card( 5, 'The ghostwriting team captured my voice so well that my own brother thought I&#8217;d written every word. Extraordinary.', 'avatar-4.svg', 'Nils Andersen', 'Memoir' ),
		qp_quote_card( 5, 'Their marketing turned a quiet release into a three-week category bestseller. The ad campaigns paid for themselves twice over.', 'avatar-2.svg', 'T. R. Voss', 'Science Fiction' ),
		qp_quote_card( 5, 'Honest advice, beautiful design and not a single hidden fee. I&#8217;ve already signed up for book two.', 'avatar-1.svg', 'J. Hawthorne Reed', 'Mystery &amp; Thriller' ),
	);
	$row1 = qp_columns( array_slice( $quotes, 0, 3 ), 'qp-quotes', '' );
	$row2 = qp_columns( array_slice( $quotes, 3, 3 ), 'qp-quotes', '' );
	$grid = qp_section( 'qp-section--paper', $row1 . qp_spacer( 24 ) . $row2 );

	$stats = qp_section( 'qp-section--ink', qp_c_stats(), '980px' );

	return $header . $grid . $stats . qp_c_cta();
}

/**
 * FAQ
 */
function qp_page_faq() {
	$header = qp_page_header(
		'FAQ',
		'Frequently asked<br>questions.',
		'Everything you might want to know before you trust us with your book. Still curious? We&#8217;re one message away.',
		'cream',
		false
	);

	$items  = qp_faq_item( 'Do I keep the rights to my book?', 'Always. Quill &amp; Press never takes ownership of your work or your royalties. We provide services; you keep 100% of your rights.' );
	$items .= qp_faq_item( 'How much does it cost to publish a book?', 'It depends on what your manuscript needs. Individual services start at a few hundred dollars, and our complete packages range from $899 to $4,999. Every quote is fixed and given upfront.' );
	$items .= qp_faq_item( 'How long does the whole process take?', 'A typical full project runs 10–16 weeks, depending on length and the level of editing required. Single services like formatting or cover design can be turned around in one to two weeks.' );
	$items .= qp_faq_item( 'Can I choose just one service?', 'Yes. Many authors come to us for a single service — a cover, an edit, or a marketing campaign — and every one can be booked on its own.' );
	$items .= qp_faq_item( 'Will my book be available in bookstores?', 'Yes. Through our print and eBook distribution your title becomes available to 40,000+ retailers and libraries worldwide, including Amazon, Barnes &amp; Noble and independent stores by order.' );
	$items .= qp_faq_item( 'Do you offer a sample edit before I commit?', 'We do. Send us a short sample and we&#8217;ll return a free edited excerpt so you can experience our work on your own words first.' );
	$items .= qp_faq_item( 'What genres do you work with?', 'All of them — literary and commercial fiction, memoir, business, self-help, poetry, children&#8217;s, sci-fi, romance and more. Your specialists are always matched to your genre.' );
	$items .= qp_faq_item( 'How do we get started?', 'Request a free quote through our contact page. We&#8217;ll set up a short call, learn about your book, and send a tailored plan within two working days.' );
	$faq = qp_section( 'qp-section--paper qp-faq', qp_group( 'qp-faq', $items, '820px' ), '860px' );

	return $header . $faq . qp_c_cta();
}

/**
 * CONTACT
 */
function qp_page_contact() {
	$header = qp_page_header(
		'Contact',
		'Let&#8217;s talk<br>about your book.',
		'Tell us where you are in the journey and we&#8217;ll send a friendly, no-obligation plan and quote within two working days.',
		'ink',
		false
	);

	// Contact tiles.
	$tiles = array(
		qp_image( quillpress_img( 'icon-mail.svg' ), 'Email', 'qp-icon' ) . qp_heading( 3, 'Email us' ) . qp_para( 'hello@quillandpress.com' ),
		qp_image( quillpress_img( 'icon-phone.svg' ), 'Phone', 'qp-icon' ) . qp_heading( 3, 'Call us' ) . qp_para( '+1 (555) 018-2244' ),
		qp_image( quillpress_img( 'icon-pin.svg' ), 'Studio', 'qp-icon' ) . qp_heading( 3, 'Visit' ) . qp_para( '14 Chapter Lane, Portland, OR' ),
		qp_image( quillpress_img( 'icon-clock.svg' ), 'Hours', 'qp-icon' ) . qp_heading( 3, 'Hours' ) . qp_para( 'Mon–Fri · 9am–6pm PT' ),
	);
	$tilesec = qp_section( 'qp-section--paper', qp_columns( $tiles, 'qp-contact-tiles', '' ) );

	// Form + intro split.
	$left  = qp_eyebrow( 'Get a free quote' );
	$left .= qp_heading( 2, 'Start your book&#8217;s next chapter.', 'qp-h2' );
	$left .= qp_para( 'Fill in a few details about your project. Prefer email? Reach us any time at <strong>hello@quillandpress.com</strong> — a real human replies to every message.', 'qp-lead' );
	$left .= qp_checklist( array( 'A reply within one working day.', 'A fixed quote within two.', 'No pressure, no obligation.' ) );

	$form_html  = '<form class="qp-form" method="post" action="#" onsubmit="return false;">';
	$form_html .= '<div class="qp-form-row"><label>Your name<input type="text" name="qp-name" placeholder="Jane Author"></label>';
	$form_html .= '<label>Email<input type="email" name="qp-email" placeholder="jane@email.com"></label></div>';
	$form_html .= '<label>What can we help with?<select name="qp-service"><option>Full publishing package</option><option>Editing &amp; proofreading</option><option>Cover design</option><option>Formatting &amp; typesetting</option><option>Book marketing</option><option>Distribution</option><option>Something else</option></select></label>';
	$form_html .= '<label>Tell us about your book<textarea name="qp-message" rows="5" placeholder="Genre, word count, where you are in the process…"></textarea></label>';
	$form_html .= '<button type="submit" class="qp-form-btn">Send my request</button>';
	$form_html .= '<p class="qp-form-note">Demo form — connect Contact Form 7, WPForms or Fluent Forms to make it live.</p>';
	$form_html .= '</form>';
	$right = "<!-- wp:html -->\n{$form_html}\n<!-- /wp:html -->\n";

	$formsec = qp_section( 'qp-section--cream qp-split', qp_columns( array( $left, $right ), '', '' ) );

	return $header . $tilesec . $formsec;
}

/* -------------------------------------------------------------------------
 * Definitions consumed by the importer
 * ---------------------------------------------------------------------- */

/**
 * All demo pages. Order matters for the menu.
 */
function quillpress_demo_pages() {
	return array(
		array( 'title' => 'Home', 'slug' => 'home', 'builder' => 'qp_page_home', 'front' => true ),
		array( 'title' => 'About', 'slug' => 'about', 'builder' => 'qp_page_about' ),
		array( 'title' => 'Services', 'slug' => 'services', 'builder' => 'qp_page_services' ),
		array( 'title' => 'Editing &amp; Proofreading', 'slug' => 'editing', 'builder' => 'qp_page_editing', 'parent' => 'services' ),
		array( 'title' => 'Ghostwriting', 'slug' => 'ghostwriting', 'builder' => 'qp_page_ghostwriting', 'parent' => 'services' ),
		array( 'title' => 'Cover Design', 'slug' => 'cover-design', 'builder' => 'qp_page_cover_design', 'parent' => 'services' ),
		array( 'title' => 'Formatting &amp; Typesetting', 'slug' => 'formatting', 'builder' => 'qp_page_formatting', 'parent' => 'services' ),
		array( 'title' => 'Book Marketing', 'slug' => 'marketing', 'builder' => 'qp_page_marketing', 'parent' => 'services' ),
		array( 'title' => 'Publishing &amp; Distribution', 'slug' => 'distribution', 'builder' => 'qp_page_distribution', 'parent' => 'services' ),
		array( 'title' => 'Portfolio', 'slug' => 'portfolio', 'builder' => 'qp_page_portfolio' ),
		array( 'title' => 'Pricing', 'slug' => 'pricing', 'builder' => 'qp_page_pricing' ),
		array( 'title' => 'Testimonials', 'slug' => 'testimonials', 'builder' => 'qp_page_testimonials' ),
		array( 'title' => 'FAQ', 'slug' => 'faq', 'builder' => 'qp_page_faq' ),
		array( 'title' => 'Contact', 'slug' => 'contact', 'builder' => 'qp_page_contact' ),
		array( 'title' => 'Blog', 'slug' => 'blog', 'content' => '', 'posts_page' => true ),
	);
}

/**
 * Demo blog posts.
 */
function quillpress_demo_posts() {
	$p = array();

	$c  = qp_para( 'A polished manuscript is the single biggest factor in whether readers finish — and recommend — your book. Before you invest in a cover or a launch, it pays to get the words right.', 'qp-lead' );
	$c .= qp_heading( 2, 'Start with structure, not spelling', 'qp-h2' );
	$c .= qp_para( 'It&#8217;s tempting to jump straight to proofreading, but fixing typos in a chapter you&#8217;ll later delete is wasted effort. Begin with a developmental pass: does every chapter earn its place? Does the pacing hold? Only once the architecture is sound should you sweat the commas.' );
	$c .= qp_heading( 2, 'Read it the way a reader will', 'qp-h2' );
	$c .= qp_para( 'Print your manuscript, or load it onto an e-reader, and read it in as few sittings as possible. Distance from the screen you wrote it on reveals rough transitions and repeated phrases you&#8217;d otherwise skim past.' );
	$c .= qp_checklist( array( 'Run a structural edit before a line edit.', 'Read a printed copy at least once.', 'Keep a running style sheet of your choices.', 'Give it a week to rest before the final pass.' ) );
	$c .= qp_para( 'When you&#8217;re ready for a professional eye, our editors offer a free sample edit so you can see exactly how we&#8217;d treat your words.' );
	$p[] = array(
		'title'   => 'Getting Your Manuscript Ready for Publication',
		'slug'    => 'manuscript-ready-for-publication',
		'excerpt' => 'A polished manuscript is the biggest factor in whether readers finish your book. Here&#8217;s how to prepare yours the right way.',
		'content' => $c,
		'banner'  => 'post-1.svg',
		'category'=> 'Editing',
	);

	$c  = qp_para( 'You&#8217;ve published the book — congratulations. Now comes the part most authors dread: helping readers find it. The good news is that effective book marketing is less about shouting and more about showing up in the right places.', 'qp-lead' );
	$c .= qp_heading( 2, 'Ten tactics that actually move copies', 'qp-h2' );
	$c .= qp_para( 'None of these require a huge budget — just consistency and a clear sense of who your reader is.' );
	$c .= qp_checklist(
		array(
			'Optimise your Amazon categories and keywords.',
			'Build a launch team of early reviewers.',
			'Collect reader emails from day one.',
			'Pitch niche podcasts in your book&#8217;s subject.',
			'Run modest, well-targeted Amazon ads.',
			'Guest-post where your readers already gather.',
			'Turn your best lines into shareable graphics.',
			'Ask happy readers for honest reviews.',
			'Price strategically for launch week.',
			'Keep marketing long after release day.',
		)
	);
	$c .= qp_para( 'Overwhelmed? That&#8217;s exactly what our marketing team is for. We&#8217;ll build and run the launch while you get back to writing the next one.' );
	$p[] = array(
		'title'   => '10 Ways to Market Your Book on a Budget',
		'slug'    => 'market-your-book-on-a-budget',
		'excerpt' => 'Effective book marketing is less about shouting and more about showing up in the right places. Here are ten tactics that work.',
		'content' => $c,
		'banner'  => 'post-2.svg',
		'category'=> 'Marketing',
	);

	$c  = qp_para( 'One of the first big decisions every author faces is how to publish: chase a traditional deal, or publish independently? Neither is &#8220;better&#8221; — they&#8217;re simply different roads, and the right one depends on your goals.', 'qp-lead' );
	$c .= qp_heading( 2, 'What you trade, and what you gain', 'qp-h2' );
	$c .= qp_para( 'Traditional publishing offers an advance, prestige and a team — but usually costs you most of your royalties, your rights and years of waiting. Independent publishing hands you control, speed and far higher per-copy earnings, in exchange for taking on (or hiring out) the production and marketing yourself.' );
	$c .= qp_heading( 2, 'The modern middle path', 'qp-h2' );
	$c .= qp_para( 'Increasingly, authors choose a third route: publish independently, but hire professional partners for the parts a publisher used to handle. You keep your rights and royalties while still getting editing, design and distribution that rival any imprint. That&#8217;s precisely the model Quill &amp; Press was built around.' );
	$c .= qp_para( 'Whichever path calls to you, going in with clear eyes — and the right team — is what turns a manuscript into a book readers love.' );
	$p[] = array(
		'title'   => 'Traditional vs. Self-Publishing: Which Is Right for You?',
		'slug'    => 'traditional-vs-self-publishing',
		'excerpt' => 'Neither path is better — they&#8217;re different roads. Here&#8217;s how to choose the one that fits your goals as an author.',
		'content' => $c,
		'banner'  => 'post-3.svg',
		'category'=> 'Publishing',
	);

	return $p;
}

/**
 * Primary menu structure (slug => label). "cta" marks the button item.
 */
function quillpress_demo_menu() {
	return array(
		array( 'slug' => 'home', 'label' => 'Home' ),
		array( 'slug' => 'about', 'label' => 'About' ),
		array( 'slug' => 'services', 'label' => 'Services', 'children' => array(
			array( 'slug' => 'editing', 'label' => 'Editing &amp; Proofreading' ),
			array( 'slug' => 'ghostwriting', 'label' => 'Ghostwriting' ),
			array( 'slug' => 'cover-design', 'label' => 'Cover Design' ),
			array( 'slug' => 'formatting', 'label' => 'Formatting &amp; Typesetting' ),
			array( 'slug' => 'marketing', 'label' => 'Book Marketing' ),
			array( 'slug' => 'distribution', 'label' => 'Publishing &amp; Distribution' ),
		) ),
		array( 'slug' => 'portfolio', 'label' => 'Portfolio' ),
		array( 'slug' => 'pricing', 'label' => 'Pricing' ),
		array( 'slug' => 'blog', 'label' => 'Blog' ),
		array( 'slug' => 'contact', 'label' => 'Get a Quote', 'cta' => true ),
	);
}

/**
 * Footer menu structure.
 */
function quillpress_demo_footer_menu() {
	return array(
		array( 'slug' => 'about', 'label' => 'About' ),
		array( 'slug' => 'services', 'label' => 'Services' ),
		array( 'slug' => 'portfolio', 'label' => 'Portfolio' ),
		array( 'slug' => 'pricing', 'label' => 'Pricing' ),
		array( 'slug' => 'faq', 'label' => 'FAQ' ),
		array( 'slug' => 'contact', 'label' => 'Contact' ),
	);
}
