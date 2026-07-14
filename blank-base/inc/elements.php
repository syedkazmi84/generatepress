<?php
/**
 * Hook Elements for Blank Base.
 *
 * A lightweight, GeneratePress-style "Elements" manager. Each Element is a
 * block-editor post that is injected into one of the theme's hook locations
 * (see inc/hooks.php) according to a display rule — the entire site, the front
 * page, all posts/pages, archives, or specific post/page IDs.
 *
 * Manage them at Appearance → Elements.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Element custom post type (under Appearance).
 */
function blank_base_register_element_cpt() {
	$labels = array(
		'name'               => esc_html__( 'Elements', 'blank-base' ),
		'singular_name'      => esc_html__( 'Element', 'blank-base' ),
		'add_new'            => esc_html__( 'Add New', 'blank-base' ),
		'add_new_item'       => esc_html__( 'Add New Element', 'blank-base' ),
		'edit_item'          => esc_html__( 'Edit Element', 'blank-base' ),
		'new_item'           => esc_html__( 'New Element', 'blank-base' ),
		'view_item'          => esc_html__( 'View Element', 'blank-base' ),
		'search_items'       => esc_html__( 'Search Elements', 'blank-base' ),
		'not_found'          => esc_html__( 'No elements found.', 'blank-base' ),
		'not_found_in_trash' => esc_html__( 'No elements found in Trash.', 'blank-base' ),
		'all_items'          => esc_html__( 'Elements', 'blank-base' ),
		'menu_name'          => esc_html__( 'Elements', 'blank-base' ),
	);

	register_post_type(
		'blank_base_element',
		array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => 'themes.php',
			'show_in_rest'    => true,
			'supports'        => array( 'title', 'editor', 'revisions' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => false,
			'query_var'       => false,
		)
	);
}
add_action( 'init', 'blank_base_register_element_cpt' );

/**
 * Human-readable labels for the hook locations shown in the Element editor.
 *
 * @return array hook => label
 */
function blank_base_element_hook_choices() {
	return array(
		// Code locations (raw output — for scripts, meta tags, analytics).
		'wp_head'                           => esc_html__( 'Site <head> — scripts, meta tags, analytics', 'blank-base' ),
		'wp_footer'                         => esc_html__( 'Site footer — before </body> (scripts)', 'blank-base' ),
		// Visible content locations.
		'blank_base_top_bar'                => esc_html__( 'Top bar (above header)', 'blank-base' ),
		'blank_base_before_header'          => esc_html__( 'Before header', 'blank-base' ),
		'blank_base_inside_header'          => esc_html__( 'Inside header', 'blank-base' ),
		'blank_base_after_header'           => esc_html__( 'After header', 'blank-base' ),
		'blank_base_before_content'         => esc_html__( 'Before content area', 'blank-base' ),
		'blank_base_before_main'            => esc_html__( 'Before main content', 'blank-base' ),
		'blank_base_before_entry_content'   => esc_html__( 'Before entry content', 'blank-base' ),
		'blank_base_after_entry_content'    => esc_html__( 'After entry content', 'blank-base' ),
		'blank_base_after_main'             => esc_html__( 'After main content', 'blank-base' ),
		'blank_base_after_content'          => esc_html__( 'After content area', 'blank-base' ),
		'blank_base_before_right_sidebar'   => esc_html__( 'Before right sidebar', 'blank-base' ),
		'blank_base_after_right_sidebar'    => esc_html__( 'After right sidebar', 'blank-base' ),
		'blank_base_before_footer'          => esc_html__( 'Before footer', 'blank-base' ),
		'blank_base_inside_footer'          => esc_html__( 'Inside footer', 'blank-base' ),
		'blank_base_footer_bar'             => esc_html__( 'Inside footer bar', 'blank-base' ),
		'blank_base_after_footer'           => esc_html__( 'After footer', 'blank-base' ),
	);
}

/**
 * Display-rule choices.
 *
 * @return array rule => label
 */
function blank_base_element_display_choices() {
	return array(
		'entire_site'  => esc_html__( 'Entire site', 'blank-base' ),
		'front_page'   => esc_html__( 'Front page only', 'blank-base' ),
		'blog'         => esc_html__( 'Blog / posts index', 'blank-base' ),
		'all_posts'    => esc_html__( 'All single posts', 'blank-base' ),
		'all_pages'    => esc_html__( 'All pages', 'blank-base' ),
		'all_archives' => esc_html__( 'All archives', 'blank-base' ),
		'specific'     => esc_html__( 'Specific IDs (below)', 'blank-base' ),
	);
}

/**
 * Register the Element settings meta box.
 */
function blank_base_element_meta_box() {
	add_meta_box(
		'blank_base_element_settings',
		esc_html__( 'Element Settings', 'blank-base' ),
		'blank_base_render_element_meta_box',
		'blank_base_element',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'blank_base_element_meta_box' );

/**
 * Render the Element settings meta box.
 *
 * @param WP_Post $post Current element.
 */
function blank_base_render_element_meta_box( $post ) {
	wp_nonce_field( 'blank_base_element_meta', 'blank_base_element_nonce' );

	$hook        = get_post_meta( $post->ID, '_blank_base_hook', true );
	$priority    = get_post_meta( $post->ID, '_blank_base_priority', true );
	$display     = get_post_meta( $post->ID, '_blank_base_display', true );
	$display_ids = get_post_meta( $post->ID, '_blank_base_display_ids', true );
	$exclude_ids = get_post_meta( $post->ID, '_blank_base_exclude_ids', true );

	if ( ! $hook ) {
		$hook = 'blank_base_after_header';
	}
	if ( '' === $priority ) {
		$priority = 10;
	}
	if ( ! $display ) {
		$display = 'entire_site';
	}
	?>
	<p>
		<label for="blank_base_hook" style="display:block;font-weight:600;"><?php esc_html_e( 'Hook location', 'blank-base' ); ?></label>
		<select name="blank_base_hook" id="blank_base_hook" style="width:100%;">
			<?php foreach ( blank_base_element_hook_choices() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $hook, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php esc_html_e( 'The two "Site <head>/footer" options output raw code — paste a script, style or meta tag into a Custom HTML block for analytics, verification tags, etc.', 'blank-base' ); ?></span>
	</p>
	<p>
		<label for="blank_base_priority" style="display:block;font-weight:600;"><?php esc_html_e( 'Priority', 'blank-base' ); ?></label>
		<input type="number" name="blank_base_priority" id="blank_base_priority" value="<?php echo esc_attr( $priority ); ?>" min="1" max="999" step="1" style="width:100%;" />
		<span class="description"><?php esc_html_e( 'Lower numbers output earlier.', 'blank-base' ); ?></span>
	</p>
	<p>
		<label for="blank_base_display" style="display:block;font-weight:600;"><?php esc_html_e( 'Display on', 'blank-base' ); ?></label>
		<select name="blank_base_display" id="blank_base_display" style="width:100%;">
			<?php foreach ( blank_base_element_display_choices() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $display, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="blank_base_display_ids" style="display:block;font-weight:600;"><?php esc_html_e( 'Specific IDs', 'blank-base' ); ?></label>
		<input type="text" name="blank_base_display_ids" id="blank_base_display_ids" value="<?php echo esc_attr( $display_ids ); ?>" placeholder="e.g. 12, 34, 56" style="width:100%;" />
		<span class="description"><?php esc_html_e( 'Used when "Display on" is set to Specific IDs. Comma-separated post/page IDs.', 'blank-base' ); ?></span>
	</p>
	<p>
		<label for="blank_base_exclude_ids" style="display:block;font-weight:600;"><?php esc_html_e( 'Exclude IDs', 'blank-base' ); ?></label>
		<input type="text" name="blank_base_exclude_ids" id="blank_base_exclude_ids" value="<?php echo esc_attr( $exclude_ids ); ?>" placeholder="e.g. 78, 90" style="width:100%;" />
		<span class="description"><?php esc_html_e( 'Never show on these post/page IDs.', 'blank-base' ); ?></span>
	</p>
	<?php
}

/**
 * Save the Element settings.
 *
 * @param int $post_id Element ID.
 */
function blank_base_save_element_meta( $post_id ) {
	if ( ! isset( $_POST['blank_base_element_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['blank_base_element_nonce'] ) ), 'blank_base_element_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Hook (validate against the known list).
	if ( isset( $_POST['blank_base_hook'] ) ) {
		$hook = sanitize_key( wp_unslash( $_POST['blank_base_hook'] ) );
		if ( array_key_exists( $hook, blank_base_element_hook_choices() ) ) {
			update_post_meta( $post_id, '_blank_base_hook', $hook );
		}
	}

	// Priority.
	if ( isset( $_POST['blank_base_priority'] ) ) {
		$priority = absint( wp_unslash( $_POST['blank_base_priority'] ) );
		update_post_meta( $post_id, '_blank_base_priority', $priority ? $priority : 10 );
	}

	// Display rule.
	if ( isset( $_POST['blank_base_display'] ) ) {
		$display = sanitize_key( wp_unslash( $_POST['blank_base_display'] ) );
		if ( array_key_exists( $display, blank_base_element_display_choices() ) ) {
			update_post_meta( $post_id, '_blank_base_display', $display );
		}
	}

	// ID lists (store a normalised comma list of positive integers).
	foreach ( array( 'blank_base_display_ids' => '_blank_base_display_ids', 'blank_base_exclude_ids' => '_blank_base_exclude_ids' ) as $field => $meta_key ) {
		if ( isset( $_POST[ $field ] ) ) {
			$raw   = sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
			$ids   = array_filter( array_map( 'absint', array_map( 'trim', explode( ',', $raw ) ) ) );
			update_post_meta( $post_id, $meta_key, implode( ',', $ids ) );
		}
	}
}
add_action( 'save_post_blank_base_element', 'blank_base_save_element_meta' );

/**
 * Decide whether an element should display on the current view.
 *
 * @param int $element_id Element post ID.
 * @return bool
 */
function blank_base_element_should_display( $element_id ) {
	$rule    = get_post_meta( $element_id, '_blank_base_display', true );
	$rule    = $rule ? $rule : 'entire_site';
	$current = get_queried_object_id();

	// Exclusions win.
	$exclude = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $element_id, '_blank_base_exclude_ids', true ) ) ) );
	if ( $current && in_array( $current, $exclude, true ) ) {
		return false;
	}

	switch ( $rule ) {
		case 'entire_site':
			$match = true;
			break;
		case 'front_page':
			$match = is_front_page();
			break;
		case 'blog':
			$match = is_home();
			break;
		case 'all_posts':
			$match = is_singular( 'post' );
			break;
		case 'all_pages':
			$match = is_page();
			break;
		case 'all_archives':
			$match = ( is_archive() || is_category() || is_tag() || is_author() || is_date() );
			break;
		case 'specific':
			$ids   = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $element_id, '_blank_base_display_ids', true ) ) ) );
			$match = ( $current && in_array( $current, $ids, true ) );
			break;
		default:
			$match = false;
			break;
	}

	/**
	 * Filter whether a Hook Element displays on the current view.
	 *
	 * @param bool   $match      Whether it matches.
	 * @param int    $element_id Element ID.
	 * @param string $rule       Display rule.
	 */
	return (bool) apply_filters( 'blank_base_element_display', $match, $element_id, $rule );
}

/**
 * Whether a hook is a "code" location (wp_head / wp_footer). Code locations are
 * output raw — no wrapper element and no auto-paragraphs — so scripts, style
 * and meta tags pass through intact.
 *
 * @param string $hook Hook name.
 * @return bool
 */
function blank_base_element_is_code_hook( $hook ) {
	return in_array( $hook, array( 'wp_head', 'wp_footer' ), true );
}

/**
 * Render a single element's content.
 *
 * Visible-content elements are wrapped in a div and filtered through
 * the_content so blocks and shortcodes resolve. Code elements (wp_head /
 * wp_footer) output their blocks raw — no wrapper, no wpautop — so a
 * <script>/<style>/<meta> pasted into a Custom HTML block is emitted verbatim.
 *
 * @param WP_Post $element Element post.
 * @param bool    $raw     Whether to output raw code (for wp_head / wp_footer).
 */
function blank_base_render_element( $element, $raw = false ) {
	if ( ! blank_base_element_should_display( $element->ID ) ) {
		return;
	}

	if ( $raw ) {
		// Render blocks (so a Custom HTML block resolves to its raw markup) but
		// skip the div wrapper and wpautop, which would be invalid in <head>.
		echo do_blocks( $element->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	echo '<div class="blank-base-element blank-base-element-' . absint( $element->ID ) . '">';
	echo apply_filters( 'the_content', $element->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';
}

/**
 * Transient key for the cached element-to-hook map.
 */
const BLANK_BASE_ELEMENT_HOOK_CACHE = 'blank_base_element_hooks';

/**
 * Build the map of published elements to the hooks they attach to.
 *
 * The result is cached in a transient so the get_posts() query only runs when
 * the cache is cold (busted whenever an element is saved, trashed or deleted).
 * When no elements are published at all, we short-circuit to an empty array so
 * ordinary requests do no element work.
 *
 * @return array List of [ id, hook, priority, raw ] records.
 */
function blank_base_get_element_hook_map() {
	$cached = get_transient( BLANK_BASE_ELEMENT_HOOK_CACHE );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$map = array();

	$counts = wp_count_posts( 'blank_base_element' );
	$total  = isset( $counts->publish ) ? (int) $counts->publish : 0;

	if ( $total > 0 ) {
		$element_ids = get_posts(
			array(
				'post_type'        => 'blank_base_element',
				'post_status'      => 'publish',
				'numberposts'      => 100,
				'fields'           => 'ids',
				'suppress_filters' => false,
			)
		);

		$valid_hooks = blank_base_element_hook_choices();

		foreach ( $element_ids as $element_id ) {
			$hook = get_post_meta( $element_id, '_blank_base_hook', true );

			if ( ! $hook || ! array_key_exists( $hook, $valid_hooks ) ) {
				continue;
			}

			$priority = get_post_meta( $element_id, '_blank_base_priority', true );
			$priority = ( '' === $priority ) ? 10 : absint( $priority );

			$map[] = array(
				'id'       => (int) $element_id,
				'hook'     => $hook,
				'priority' => $priority,
				'raw'      => blank_base_element_is_code_hook( $hook ),
			);
		}
	}

	set_transient( BLANK_BASE_ELEMENT_HOOK_CACHE, $map, DAY_IN_SECONDS );

	return $map;
}

/**
 * Attach all published elements to their configured hooks on the front end.
 */
function blank_base_register_elements() {
	if ( is_admin() ) {
		return;
	}

	foreach ( blank_base_get_element_hook_map() as $item ) {
		$element_id = $item['id'];
		$raw        = $item['raw'];

		add_action(
			$item['hook'],
			function () use ( $element_id, $raw ) {
				$element = get_post( $element_id );
				if ( $element instanceof WP_Post ) {
					blank_base_render_element( $element, $raw );
				}
			},
			$item['priority']
		);
	}
}
add_action( 'template_redirect', 'blank_base_register_elements' );

/**
 * Clear the cached element-to-hook map so the next front-end request rebuilds it.
 */
function blank_base_flush_element_hook_cache() {
	delete_transient( BLANK_BASE_ELEMENT_HOOK_CACHE );
}
add_action( 'save_post_blank_base_element', 'blank_base_flush_element_hook_cache' );

/**
 * Flush the element cache on trash/untrash/delete, guarding by post type since
 * these hooks fire for every post type. The optional $post argument is used
 * when available (e.g. deleted_post, where the row is already gone and
 * get_post_type() would fail).
 *
 * @param int          $post_id Post being changed.
 * @param WP_Post|null $post    Post object, when the hook provides one.
 */
function blank_base_flush_element_hook_cache_by_id( $post_id, $post = null ) {
	$post_type = ( $post instanceof WP_Post ) ? $post->post_type : get_post_type( $post_id );

	if ( 'blank_base_element' === $post_type ) {
		blank_base_flush_element_hook_cache();
	}
}
add_action( 'trashed_post', 'blank_base_flush_element_hook_cache_by_id', 10, 2 );
add_action( 'untrashed_post', 'blank_base_flush_element_hook_cache_by_id', 10, 2 );
add_action( 'deleted_post', 'blank_base_flush_element_hook_cache_by_id', 10, 2 );

/**
 * Add "Hook" and "Display" columns to the Elements list table.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function blank_base_element_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['blank_base_hook_col']    = esc_html__( 'Hook', 'blank-base' );
			$new['blank_base_display_col'] = esc_html__( 'Display', 'blank-base' );
		}
	}
	return $new;
}
add_filter( 'manage_blank_base_element_posts_columns', 'blank_base_element_columns' );

/**
 * Populate the custom Elements columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Element ID.
 */
function blank_base_element_column_content( $column, $post_id ) {
	if ( 'blank_base_hook_col' === $column ) {
		$hook    = get_post_meta( $post_id, '_blank_base_hook', true );
		$choices = blank_base_element_hook_choices();
		echo esc_html( isset( $choices[ $hook ] ) ? $choices[ $hook ] : '—' );
	}
	if ( 'blank_base_display_col' === $column ) {
		$display = get_post_meta( $post_id, '_blank_base_display', true );
		$choices = blank_base_element_display_choices();
		echo esc_html( isset( $choices[ $display ] ) ? $choices[ $display ] : '—' );
	}
}
add_action( 'manage_blank_base_element_posts_custom_column', 'blank_base_element_column_content', 10, 2 );
