<?php
/**
 * Self-hosted Template Library for Blank Base.
 *
 * Recreates the Getwid-style "Template Library" experience, but fully
 * self-contained: a button in the block editor opens a modal of ready-made
 * page sections that are served from this theme (no remote server). Selecting
 * a template inserts it into the current post.
 *
 * Templates are defined as structured block data (block name + attributes +
 * inner blocks) and exposed over a small REST endpoint. The editor script
 * builds real blocks from that data with wp.blocks.createBlock, so there is no
 * saved-markup validation to keep in sync and every template renders a live
 * preview in the modal.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper: build one template block node.
 *
 * @param string $name       Block name, e.g. "core/columns".
 * @param array  $attributes Block attributes.
 * @param array  $inner      Inner block nodes (from this helper).
 * @return array
 */
function blank_base_tl_block( $name, $attributes = array(), $inner = array() ) {
	return array(
		'name'        => $name,
		// Cast to object so empty attributes serialize to {} (not []).
		'attributes'  => (object) $attributes,
		'innerBlocks' => $inner,
	);
}

/**
 * The full library of templates.
 *
 * Add your own entries here — each becomes a card in the Template Library
 * modal. Categories group nothing programmatically yet; they are shown as a
 * label on each card.
 *
 * @return array
 */
function blank_base_get_templates() {
	$b = 'blank_base_tl_block';

	$templates = array();

	// -- Stats band: four animated counters in a row. ------------------
	$templates[] = array(
		'slug'     => 'stats-band',
		'title'    => esc_html__( 'Stats band', 'blank-base' ),
		'category' => esc_html__( 'Stats', 'blank-base' ),
		'blocks'   => array(
			$b(
				'core/columns',
				array( 'align' => 'wide' ),
				array(
					$b( 'core/column', array(), array( $b( 'blank-base/counter', array( 'number' => '10', 'suffix' => 'k+', 'label' => esc_html__( 'Active users', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/counter', array( 'number' => '4.9', 'suffix' => '/5', 'label' => esc_html__( 'Average rating', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/counter', array( 'number' => '120', 'suffix' => '+', 'label' => esc_html__( 'Countries served', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/counter', array( 'number' => '99.9', 'suffix' => '%', 'label' => esc_html__( 'Uptime', 'blank-base' ) ) ) ) ),
				)
			),
		),
	);

	// -- Skills: a heading over three progress bars. -------------------
	$templates[] = array(
		'slug'     => 'skills',
		'title'    => esc_html__( 'Skills / progress', 'blank-base' ),
		'category' => esc_html__( 'Stats', 'blank-base' ),
		'blocks'   => array(
			$b(
				'core/group',
				array( 'layout' => array( 'type' => 'constrained' ) ),
				array(
					$b( 'core/heading', array( 'level' => 2, 'content' => esc_html__( 'What we do best', 'blank-base' ) ) ),
					$b( 'blank-base/progress', array( 'label' => esc_html__( 'Design', 'blank-base' ), 'percent' => 92 ) ),
					$b( 'blank-base/progress', array( 'label' => esc_html__( 'Development', 'blank-base' ), 'percent' => 85 ) ),
					$b( 'blank-base/progress', array( 'label' => esc_html__( 'Marketing', 'blank-base' ), 'percent' => 74 ) ),
				)
			),
		),
	);

	// -- Feature trio: three icon boxes. -------------------------------
	$templates[] = array(
		'slug'     => 'feature-trio',
		'title'    => esc_html__( 'Three features', 'blank-base' ),
		'category' => esc_html__( 'Features', 'blank-base' ),
		'blocks'   => array(
			$b(
				'core/columns',
				array( 'align' => 'wide' ),
				array(
					$b( 'core/column', array(), array( $b( 'blank-base/icon-box', array( 'icon' => 'lightbulb', 'title' => esc_html__( 'Fast & lightweight', 'blank-base' ), 'text' => esc_html__( 'Built for speed with clean, minimal code so your pages load quickly on every device.', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/icon-box', array( 'icon' => 'admin-customizer', 'title' => esc_html__( 'Fully customizable', 'blank-base' ), 'text' => esc_html__( 'Shape colours, fonts and layout from the Customizer and block editor — no code required.', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/icon-box', array( 'icon' => 'universal-access', 'title' => esc_html__( 'Accessible by default', 'blank-base' ), 'text' => esc_html__( 'Semantic markup, keyboard navigation and strong contrast, right out of the box.', 'blank-base' ) ) ) ) ),
				)
			),
		),
	);

	// -- Testimonial. --------------------------------------------------
	$templates[] = array(
		'slug'     => 'testimonial',
		'title'    => esc_html__( 'Testimonial', 'blank-base' ),
		'category' => esc_html__( 'Social proof', 'blank-base' ),
		'blocks'   => array(
			$b( 'blank-base/testimonial', array( 'quote' => esc_html__( 'This is exactly what we needed — simple to set up and a pleasure to use every day.', 'blank-base' ), 'name' => esc_html__( 'Jane Rivera', 'blank-base' ), 'role' => esc_html__( 'Product Lead, Northwind', 'blank-base' ) ) ),
		),
	);

	// -- Hero: core blocks (shows the library is not limited to our blocks).
	$templates[] = array(
		'slug'     => 'hero',
		'title'    => esc_html__( 'Hero', 'blank-base' ),
		'category' => esc_html__( 'Headers', 'blank-base' ),
		'blocks'   => array(
			$b(
				'core/group',
				array(
					'align'  => 'full',
					'layout' => array( 'type' => 'constrained' ),
					'style'  => array( 'spacing' => array( 'padding' => array( 'top' => '4rem', 'bottom' => '4rem' ) ) ),
				),
				array(
					$b( 'core/heading', array( 'level' => 1, 'textAlign' => 'center', 'content' => esc_html__( 'A clear, confident headline', 'blank-base' ) ) ),
					$b( 'core/paragraph', array( 'align' => 'center', 'content' => esc_html__( 'Introduce your product or service in one sentence that tells visitors exactly what you offer and why it matters.', 'blank-base' ) ) ),
					$b(
						'core/buttons',
						array( 'layout' => array( 'type' => 'flex', 'justifyContent' => 'center' ) ),
						array(
							$b( 'core/button', array( 'text' => esc_html__( 'Get started', 'blank-base' ) ) ),
							$b( 'core/button', array( 'text' => esc_html__( 'Learn more', 'blank-base' ) ) ),
						)
					),
				)
			),
		),
	);

	// -- Pricing: three tiers, middle one featured. --------------------
	$templates[] = array(
		'slug'     => 'pricing-trio',
		'title'    => esc_html__( 'Pricing (3 tiers)', 'blank-base' ),
		'category' => esc_html__( 'Pricing', 'blank-base' ),
		'blocks'   => array(
			$b(
				'core/columns',
				array( 'align' => 'wide' ),
				array(
					$b( 'core/column', array(), array( $b( 'blank-base/pricing', array( 'plan' => esc_html__( 'Starter', 'blank-base' ), 'price' => '$9', 'features' => esc_html__( 'Up to 3 projects', 'blank-base' ) . "\n" . esc_html__( 'Email support', 'blank-base' ) . "\n" . esc_html__( 'Basic analytics', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/pricing', array( 'plan' => esc_html__( 'Pro', 'blank-base' ), 'price' => '$29', 'featured' => true, 'features' => esc_html__( 'Unlimited projects', 'blank-base' ) . "\n" . esc_html__( 'Priority support', 'blank-base' ) . "\n" . esc_html__( 'Advanced analytics', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/pricing', array( 'plan' => esc_html__( 'Team', 'blank-base' ), 'price' => '$79', 'features' => esc_html__( 'Everything in Pro', 'blank-base' ) . "\n" . esc_html__( 'Team roles', 'blank-base' ) . "\n" . esc_html__( 'SSO & audit log', 'blank-base' ) ) ) ) ),
				)
			),
		),
	);

	// -- Team: three members. ------------------------------------------
	$templates[] = array(
		'slug'     => 'team-trio',
		'title'    => esc_html__( 'Team (3 people)', 'blank-base' ),
		'category' => esc_html__( 'Team', 'blank-base' ),
		'blocks'   => array(
			$b(
				'core/columns',
				array( 'align' => 'wide' ),
				array(
					$b( 'core/column', array(), array( $b( 'blank-base/person', array( 'name' => esc_html__( 'Alex Morgan', 'blank-base' ), 'role' => esc_html__( 'Founder', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/person', array( 'name' => esc_html__( 'Sam Chen', 'blank-base' ), 'role' => esc_html__( 'Engineering', 'blank-base' ) ) ) ) ),
					$b( 'core/column', array(), array( $b( 'blank-base/person', array( 'name' => esc_html__( 'Taylor Kim', 'blank-base' ), 'role' => esc_html__( 'Design', 'blank-base' ) ) ) ) ),
				)
			),
		),
	);

	// -- FAQ: heading over three toggles. ------------------------------
	$templates[] = array(
		'slug'     => 'faq',
		'title'    => esc_html__( 'FAQ', 'blank-base' ),
		'category' => esc_html__( 'Content', 'blank-base' ),
		'blocks'   => array(
			$b(
				'core/group',
				array( 'layout' => array( 'type' => 'constrained' ) ),
				array(
					$b( 'blank-base/heading', array( 'eyebrow' => esc_html__( 'Support', 'blank-base' ), 'title' => esc_html__( 'Frequently asked questions', 'blank-base' ) ) ),
					$b( 'blank-base/toggle', array( 'question' => esc_html__( 'How do I get started?', 'blank-base' ), 'answer' => esc_html__( 'Sign up, pick a plan, and follow the onboarding — it takes a couple of minutes.', 'blank-base' ), 'open' => true ) ),
					$b( 'blank-base/toggle', array( 'question' => esc_html__( 'Can I change plans later?', 'blank-base' ), 'answer' => esc_html__( 'Yes — upgrade or downgrade at any time from your account settings.', 'blank-base' ) ) ),
					$b( 'blank-base/toggle', array( 'question' => esc_html__( 'Do you offer refunds?', 'blank-base' ), 'answer' => esc_html__( 'We offer a 30-day money-back guarantee, no questions asked.', 'blank-base' ) ) ),
				)
			),
		),
	);

	/**
	 * Filters the Template Library entries.
	 *
	 * @param array $templates Array of template definitions.
	 */
	return apply_filters( 'blank_base_templates', $templates );
}

/**
 * Register the REST route that serves the template library to the editor.
 */
function blank_base_register_template_route() {
	register_rest_route(
		'blank-base/v1',
		'/templates',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'blank_base_rest_get_templates',
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'rest_api_init', 'blank_base_register_template_route' );

/**
 * REST callback: return the template library.
 *
 * @return WP_REST_Response
 */
function blank_base_rest_get_templates() {
	return rest_ensure_response( blank_base_get_templates() );
}

/**
 * Enqueue the Template Library editor script and styles.
 */
function blank_base_template_library_assets() {
	$theme_uri = get_template_directory_uri();
	$version   = wp_get_theme( get_template() )->get( 'Version' );

	wp_enqueue_script(
		'blank-base-template-library',
		$theme_uri . '/assets/js/template-library.js',
		array(
			'wp-plugins',
			'wp-edit-post',
			'wp-element',
			'wp-components',
			'wp-data',
			'wp-blocks',
			'wp-block-editor',
			'wp-api-fetch',
			'wp-i18n',
		),
		$version,
		true
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'blank-base-template-library', 'blank-base' );
	}

	// Modal styles live in the shared block stylesheet (registered in blocks.php).
	wp_enqueue_style( 'blank-base-blocks' );
}
add_action( 'enqueue_block_editor_assets', 'blank_base_template_library_assets' );
