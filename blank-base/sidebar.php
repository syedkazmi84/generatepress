<?php
/**
 * The sidebar containing the main widget area(s).
 *
 * Renders the right and/or left sidebar according to the layout resolved by the
 * layout engine (see inc/layout.php). Flexbox ordering positions each column;
 * see the sidebar rules in style.css.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blank_Base
 */

$blank_base_layout = function_exists( 'blank_base_effective_layout' ) ? blank_base_effective_layout() : 'right-sidebar';

// No sidebar for no-sidebar / full-width layouts (and for any layout whose
// widget areas turned out to be empty — the effective layout already collapsed
// those to no-sidebar).
if ( in_array( $blank_base_layout, array( 'no-sidebar', 'full-width' ), true ) ) {
	return;
}

$blank_base_show_left  = in_array( $blank_base_layout, array( 'left-sidebar', 'both-sidebars' ), true );
$blank_base_show_right = in_array( $blank_base_layout, array( 'right-sidebar', 'both-sidebars' ), true );

// Left column → the "Left Sidebar" widget area (sidebar-2). Flexbox ordering in
// style.css positions it before the content for single left and both-sidebars
// layouts, so it is output first here.
if ( $blank_base_show_left && is_active_sidebar( 'sidebar-2' ) ) :
	blank_base_do_element( 'before_left_sidebar' );
	?>
	<aside id="tertiary" class="widget-area widget-area--secondary" aria-label="<?php esc_attr_e( 'Secondary Sidebar', 'blank-base' ); ?>">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</aside><!-- #tertiary -->
	<?php
	blank_base_do_element( 'after_left_sidebar' );
endif;

// Right column → the "Right Sidebar" widget area (sidebar-1).
if ( $blank_base_show_right && is_active_sidebar( 'sidebar-1' ) ) :
	blank_base_do_element( 'before_right_sidebar' );
	?>
	<aside id="secondary" class="widget-area widget-area--primary" aria-label="<?php esc_attr_e( 'Sidebar', 'blank-base' ); ?>">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside><!-- #secondary -->
	<?php
	blank_base_do_element( 'after_right_sidebar' );
endif;
