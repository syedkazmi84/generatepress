<?php
/**
 * Layout engine for Blank Base.
 *
 * Resolves the effective content/sidebar layout for the current view from a
 * three-level cascade — per-post meta box, per-context Customizer option, then
 * the global default — mirroring the flexibility of GeneratePress's layout
 * system. Also registers the per-post "Layout" meta box.
 *
 * Layout values used internally:
 *   right-sidebar | left-sidebar | both-sidebars | no-sidebar | full-width
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Map a stored "position" value (right|left|both|none|full) to an internal
 * layout slug.
 *
 * @param string $position Position value.
 * @return string
 */
function blank_base_map_position_to_layout( $position ) {
	$map = array(
		'right' => 'right-sidebar',
		'left'  => 'left-sidebar',
		'both'  => 'both-sidebars',
		'none'  => 'no-sidebar',
		'full'  => 'full-width',
	);

	return isset( $map[ $position ] ) ? $map[ $position ] : 'right-sidebar';
}

/**
 * The global default sidebar position.
 *
 * @return string right|left|both|none|full
 */
function blank_base_global_sidebar_position() {
	return get_theme_mod( 'blank_base_sidebar_position', 'right' );
}

/**
 * Resolve the effective layout for the current view.
 *
 * Cascade: post meta override → per-context Customizer setting → global default.
 * A per-context value of "default" (or an empty meta value) falls through to the
 * next level.
 *
 * @return string Internal layout slug.
 */
function blank_base_get_layout() {
	$position = '';

	// 1. Per-post meta override on singular views.
	if ( is_singular() ) {
		$meta = get_post_meta( get_the_ID(), '_blank_base_sidebar', true );
		if ( $meta && 'default' !== $meta ) {
			$position = $meta;
		}
	}

	// 2. Per-context Customizer setting.
	if ( ! $position ) {
		$context_key = '';

		if ( is_singular( 'page' ) || is_page() ) {
			$context_key = 'blank_base_page_sidebar';
		} elseif ( is_single() || is_singular() ) {
			$context_key = 'blank_base_single_sidebar';
		} elseif ( is_home() || is_front_page() ) {
			$context_key = 'blank_base_blog_sidebar';
		} elseif ( is_archive() || is_search() ) {
			$context_key = 'blank_base_archive_sidebar';
		}

		if ( $context_key ) {
			$context = get_theme_mod( $context_key, 'default' );
			if ( $context && 'default' !== $context ) {
				$position = $context;
			}
		}
	}

	// 3. Global default.
	if ( ! $position ) {
		$position = blank_base_global_sidebar_position();
	}

	$layout = blank_base_map_position_to_layout( $position );

	/**
	 * Filter the resolved layout for the current view.
	 *
	 * @param string $layout Internal layout slug.
	 */
	return apply_filters( 'blank_base_layout', $layout );
}

/**
 * The front page is rendered by front-page.php — a deliberate full-width canvas
 * built in the block editor that never outputs a sidebar. Force the no-sidebar
 * layout there so the body classes stay honest (no stray sidebar-* class, no
 * empty column) regardless of the global/context sidebar settings.
 *
 * @param string $layout Resolved layout slug.
 * @return string
 */
function blank_base_front_page_no_sidebar( $layout ) {
	if ( is_front_page() ) {
		return 'no-sidebar';
	}

	return $layout;
}
add_filter( 'blank_base_layout', 'blank_base_front_page_no_sidebar' );

/**
 * Resolve the layout that is actually rendered, collapsing any sidebar column
 * whose widget area has no active widgets.
 *
 * The configured layout (blank_base_get_layout) only expresses intent. If the
 * matching widget area is empty there is nothing to show, so the column is
 * dropped and the content reclaims that space instead of leaving an empty gap
 * beside a narrowed content column. This mirrors GeneratePress, which serves a
 * full-width layout when a sidebar has no widgets.
 *
 * Column → widget area mapping:
 *   Left column  → "Left Sidebar"  widget area (sidebar-2)
 *   Right column → "Right Sidebar" widget area (sidebar-1)
 *
 * @return string Internal layout slug.
 */
function blank_base_effective_layout() {
	$layout = blank_base_get_layout();

	// No-sidebar and full-width never render a widget column.
	if ( in_array( $layout, array( 'no-sidebar', 'full-width' ), true ) ) {
		return $layout;
	}

	$wants_left  = in_array( $layout, array( 'left-sidebar', 'both-sidebars' ), true );
	$wants_right = in_array( $layout, array( 'right-sidebar', 'both-sidebars' ), true );

	// A column only survives when its widget area actually has widgets.
	$has_left  = $wants_left && is_active_sidebar( 'sidebar-2' );
	$has_right = $wants_right && is_active_sidebar( 'sidebar-1' );

	if ( $has_left && $has_right ) {
		return 'both-sidebars';
	}
	if ( $has_left ) {
		return 'left-sidebar';
	}
	if ( $has_right ) {
		return 'right-sidebar';
	}

	return 'no-sidebar';
}

/**
 * Whether the current layout includes a right-hand sidebar column.
 *
 * @return bool
 */
function blank_base_has_right_sidebar() {
	return in_array( blank_base_effective_layout(), array( 'right-sidebar', 'both-sidebars' ), true );
}

/**
 * Whether the current layout includes a left-hand sidebar column.
 *
 * @return bool
 */
function blank_base_has_left_sidebar() {
	return in_array( blank_base_effective_layout(), array( 'left-sidebar', 'both-sidebars' ), true );
}

/**
 * The content container type: 'boxed' (max-width) or 'full-width' (edge to edge).
 *
 * @return string
 */
function blank_base_content_layout() {
	return get_theme_mod( 'blank_base_content_layout', 'boxed' );
}

/**
 * Add layout-related classes to the body element.
 *
 * @param array $classes Body classes.
 * @return array
 */
function blank_base_layout_body_classes( $classes ) {
	// Use the effective layout so a configured-but-empty sidebar collapses to a
	// full-width content column instead of leaving a blank gap.
	$layout = blank_base_effective_layout();

	// Remove any legacy sidebar classes added elsewhere to avoid duplicates.
	$classes = array_diff( $classes, array( 'has-sidebar', 'no-sidebar', 'sidebar-right', 'sidebar-left', 'both-sidebars' ) );

	switch ( $layout ) {
		case 'right-sidebar':
			$classes[] = 'has-sidebar';
			$classes[] = 'sidebar-right';
			break;
		case 'left-sidebar':
			$classes[] = 'has-sidebar';
			$classes[] = 'sidebar-left';
			break;
		case 'both-sidebars':
			$classes[] = 'has-sidebar';
			$classes[] = 'both-sidebars';
			break;
		default:
			$classes[] = 'no-sidebar';
			break;
	}

	if ( 'full-width' === $layout ) {
		$classes[] = 'content-edge-to-edge';
	}

	$classes[] = 'container-' . sanitize_html_class( blank_base_content_layout() );
	$classes[] = 'nav-' . sanitize_html_class( get_theme_mod( 'blank_base_nav_location', 'float-right' ) );

	return $classes;
}
add_filter( 'body_class', 'blank_base_layout_body_classes' );

/* -------------------------------------------------------------------------
 * Per-post "Layout" meta box.
 * ---------------------------------------------------------------------- */

/**
 * Register the layout meta box on public post types.
 */
function blank_base_add_layout_meta_box() {
	$post_types = get_post_types( array( 'public' => true ), 'names' );
	unset( $post_types['attachment'] );

	foreach ( $post_types as $post_type ) {
		add_meta_box(
			'blank_base_layout',
			esc_html__( 'Blank Base Layout', 'blank-base' ),
			'blank_base_render_layout_meta_box',
			$post_type,
			'side',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'blank_base_add_layout_meta_box' );

/**
 * Render the layout meta box.
 *
 * @param WP_Post $post Current post.
 */
function blank_base_render_layout_meta_box( $post ) {
	wp_nonce_field( 'blank_base_layout_meta', 'blank_base_layout_nonce' );

	$sidebar       = get_post_meta( $post->ID, '_blank_base_sidebar', true );
	$disable_title = get_post_meta( $post->ID, '_blank_base_disable_title', true );
	$breadcrumbs   = get_post_meta( $post->ID, '_blank_base_breadcrumbs', true );

	if ( ! $sidebar ) {
		$sidebar = 'default';
	}

	if ( ! $breadcrumbs ) {
		$breadcrumbs = 'default';
	}

	$choices = array(
		'default' => esc_html__( 'Default (from Customizer)', 'blank-base' ),
		'right'   => esc_html__( 'Right sidebar', 'blank-base' ),
		'left'    => esc_html__( 'Left sidebar', 'blank-base' ),
		'both'    => esc_html__( 'Both sidebars', 'blank-base' ),
		'none'    => esc_html__( 'No sidebar', 'blank-base' ),
		'full'    => esc_html__( 'Full width (edge to edge)', 'blank-base' ),
	);

	$breadcrumb_choices = array(
		'default' => esc_html__( 'Default (from Customizer)', 'blank-base' ),
		'show'    => esc_html__( 'Show breadcrumbs', 'blank-base' ),
		'hide'    => esc_html__( 'Hide breadcrumbs', 'blank-base' ),
	);
	?>
	<p>
		<label for="blank_base_sidebar_layout" style="display:block;font-weight:600;margin-bottom:4px;">
			<?php esc_html_e( 'Sidebar Layout', 'blank-base' ); ?>
		</label>
		<select name="blank_base_sidebar_layout" id="blank_base_sidebar_layout" style="width:100%;">
			<?php foreach ( $choices as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $sidebar, $value ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="blank_base_breadcrumbs_display" style="display:block;font-weight:600;margin-bottom:4px;">
			<?php esc_html_e( 'Breadcrumbs', 'blank-base' ); ?>
		</label>
		<select name="blank_base_breadcrumbs_display" id="blank_base_breadcrumbs_display" style="width:100%;">
			<?php foreach ( $breadcrumb_choices as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $breadcrumbs, $value ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="blank_base_disable_title">
			<input type="checkbox" name="blank_base_disable_title" id="blank_base_disable_title" value="1" <?php checked( $disable_title, '1' ); ?> />
			<?php esc_html_e( 'Hide the content title', 'blank-base' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Save the layout meta box values.
 *
 * @param int $post_id Post ID.
 */
function blank_base_save_layout_meta_box( $post_id ) {
	if ( ! isset( $_POST['blank_base_layout_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['blank_base_layout_nonce'] ) ), 'blank_base_layout_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Sidebar layout.
	$allowed = array( 'default', 'right', 'left', 'both', 'none', 'full' );
	if ( isset( $_POST['blank_base_sidebar_layout'] ) ) {
		$value = sanitize_key( wp_unslash( $_POST['blank_base_sidebar_layout'] ) );
		if ( in_array( $value, $allowed, true ) && 'default' !== $value ) {
			update_post_meta( $post_id, '_blank_base_sidebar', $value );
		} else {
			delete_post_meta( $post_id, '_blank_base_sidebar' );
		}
	}

	// Disable title.
	if ( isset( $_POST['blank_base_disable_title'] ) ) {
		update_post_meta( $post_id, '_blank_base_disable_title', '1' );
	} else {
		delete_post_meta( $post_id, '_blank_base_disable_title' );
	}

	// Breadcrumbs display (per page/post override of the Customizer default).
	$breadcrumb_allowed = array( 'default', 'show', 'hide' );
	if ( isset( $_POST['blank_base_breadcrumbs_display'] ) ) {
		$breadcrumb_value = sanitize_key( wp_unslash( $_POST['blank_base_breadcrumbs_display'] ) );
		if ( in_array( $breadcrumb_value, $breadcrumb_allowed, true ) && 'default' !== $breadcrumb_value ) {
			update_post_meta( $post_id, '_blank_base_breadcrumbs', $breadcrumb_value );
		} else {
			delete_post_meta( $post_id, '_blank_base_breadcrumbs' );
		}
	}
}
add_action( 'save_post', 'blank_base_save_layout_meta_box' );

/**
 * Whether the content title should be hidden for the current singular view.
 *
 * @return bool
 */
function blank_base_title_is_hidden() {
	if ( ! is_singular() ) {
		return false;
	}

	return '1' === get_post_meta( get_the_ID(), '_blank_base_disable_title', true );
}
