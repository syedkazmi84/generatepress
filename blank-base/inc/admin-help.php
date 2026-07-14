<?php
/**
 * "Blank Base Help" admin page.
 *
 * A built-in reference under Appearance → Blank Base so the animation classes,
 * mega-menu class and patterns are documented right in the dashboard — no
 * need to memorise anything.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the help page under the Appearance menu.
 */
function blank_base_register_help_page() {
	add_theme_page(
		esc_html__( 'Blank Base Help', 'blank-base' ),
		esc_html__( 'Blank Base', 'blank-base' ),
		'edit_theme_options',
		'blank-base-help',
		'blank_base_render_help_page'
	);
}
add_action( 'admin_menu', 'blank_base_register_help_page' );

/**
 * Add a "Help" action link on the Themes screen for quick access.
 *
 * @param array $actions Existing action links.
 * @return array
 */
function blank_base_theme_action_link( $actions ) {
	$url  = admin_url( 'themes.php?page=blank-base-help' );
	$link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Help', 'blank-base' ) . '</a>';
	array_unshift( $actions, $link );
	return $actions;
}
add_filter( 'theme_action_links_' . get_template(), 'blank_base_theme_action_link' );

/**
 * Render a simple two-column reference table.
 *
 * @param string $heading Section heading.
 * @param array  $rows    Array of [ code, description ] pairs.
 */
function blank_base_help_table( $heading, $rows ) {
	echo '<h2>' . esc_html( $heading ) . '</h2>';
	echo '<table class="widefat striped" style="max-width:820px;margin-bottom:2em">';
	echo '<thead><tr><th style="width:230px">' . esc_html__( 'Class / name', 'blank-base' ) . '</th><th>' . esc_html__( 'What it does', 'blank-base' ) . '</th></tr></thead><tbody>';
	foreach ( $rows as $row ) {
		echo '<tr><td><code>' . esc_html( $row[0] ) . '</code></td><td>' . esc_html( $row[1] ) . '</td></tr>';
	}
	echo '</tbody></table>';
}

/**
 * Render the help page.
 */
function blank_base_render_help_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Blank Base — Help &amp; Reference', 'blank-base' ); ?></h1>
		<div style="max-width:820px;background:#fff;border:1px solid #e2e2e2;border-left:4px solid #c8a45c;border-radius:8px;padding:18px 22px;margin:16px 0 24px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
			<span class="dashicons dashicons-book-alt" style="font-size:32px;width:32px;height:32px;color:#c8a45c"></span>
			<div style="flex:1;min-width:240px">
				<strong style="font-size:15px"><?php esc_html_e( 'New here? Import the demo content.', 'blank-base' ); ?></strong><br>
				<span style="color:#666"><?php esc_html_e( 'Build a complete book-publishing website — pages, posts, images, menus and colours — in one click.', 'blank-base' ); ?></span>
			</div>
			<a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'themes.php?page=blank-base-demo' ) ); ?>"><?php esc_html_e( 'Import Demo Content', 'blank-base' ); ?></a>
		</div>

		<p style="max-width:820px">
			<?php esc_html_e( 'Everything below is optional. The quickest way to animate a block is to select it and choose an "Animate: …" option from the Styles panel — no classes to remember. The classes here are for finer control via a block\'s Advanced → Additional CSS class(es) field.', 'blank-base' ); ?>
		</p>

		<?php
		blank_base_help_table(
			esc_html__( 'Scroll animations — one-click (Styles panel)', 'blank-base' ),
			array(
				array( 'Animate: Rise up', esc_html__( 'Fades and rises into view.', 'blank-base' ) ),
				array( 'Animate: Fade in', esc_html__( 'Fades in with no movement.', 'blank-base' ) ),
				array( 'Animate: Zoom in', esc_html__( 'Scales up into view.', 'blank-base' ) ),
				array( 'Animate: Slide from left', esc_html__( 'Slides in from the left.', 'blank-base' ) ),
				array( 'Animate: Slide from right', esc_html__( 'Slides in from the right.', 'blank-base' ) ),
			)
		);

		blank_base_help_table(
			esc_html__( 'Scroll animations — utility classes (any block)', 'blank-base' ),
			array(
				array( 'bb-animate', esc_html__( 'Required. Fades and rises in (the default).', 'blank-base' ) ),
				array( 'bb-fade', esc_html__( 'Fade only, no movement. Combine with bb-animate.', 'blank-base' ) ),
				array( 'bb-from-top', esc_html__( 'Comes down from above.', 'blank-base' ) ),
				array( 'bb-from-left', esc_html__( 'Slides in from the left.', 'blank-base' ) ),
				array( 'bb-from-right', esc_html__( 'Slides in from the right.', 'blank-base' ) ),
				array( 'bb-zoom', esc_html__( 'Scales up into view.', 'blank-base' ) ),
				array( 'bb-delay-1 / bb-delay-2 / bb-delay-3', esc_html__( 'Adds a 0.15 / 0.3 / 0.45s delay (for staggering).', 'blank-base' ) ),
			)
		);
		?>
		<p style="max-width:820px;margin-bottom:2em">
			<em><?php esc_html_e( 'Example: add "bb-animate bb-from-left bb-delay-2" to a block. Animations respect the visitor\'s reduced-motion setting and never hide content if JavaScript is off.', 'blank-base' ); ?></em>
		</p>

		<?php
		blank_base_help_table(
			esc_html__( 'Navigation', 'blank-base' ),
			array(
				array( 'mega-menu', esc_html__( 'Add to a top-level menu item (enable CSS Classes via Screen Options in Appearance → Menus) to make its submenu span full width in columns.', 'blank-base' ) ),
			)
		);

		blank_base_help_table(
			esc_html__( 'Interactive patterns', 'blank-base' ),
			array(
				array( 'Animated stats counter', esc_html__( 'Edit the number as a normal Heading, e.g. "4.9/5" or "10k+".', 'blank-base' ) ),
				array( 'Skill bars', esc_html__( 'Edit each bar as a normal Paragraph, e.g. "Design 92%".', 'blank-base' ) ),
				array( 'Testimonial slider', esc_html__( 'A swipeable carousel with previous/next controls.', 'blank-base' ) ),
			)
		);

		blank_base_help_table(
			esc_html__( 'Layout &amp; design options (Appearance → Customize)', 'blank-base' ),
			array(
				array( 'Theme Options → Sidebar Position', esc_html__( 'Global default layout: right, left, both, none or full width. Override it per context and per post.', 'blank-base' ) ),
				array( 'Theme Options → Content Container', esc_html__( 'Boxed (max width) or full width, plus an adjustable sidebar width.', 'blank-base' ) ),
				array( 'Per-context sidebars', esc_html__( 'Separate sidebar layouts for the blog, single posts, pages and archives.', 'blank-base' ) ),
				array( 'Blank Base Layout meta box', esc_html__( 'On each post/page: override the sidebar layout and optionally hide the content title.', 'blank-base' ) ),
				array( 'Primary Navigation', esc_html__( 'Navigation location, hover/click sub-menus, off-canvas mobile menu, alignment and colors.', 'blank-base' ) ),
				array( 'Colors', esc_html__( 'Per-element colors for header, navigation, content, buttons, footer widgets and footer bar.', 'blank-base' ) ),
				array( 'Typography', esc_html__( 'Fonts, weights, line-height, transforms, per-heading H1–H6 sizes, site title and nav typography.', 'blank-base' ) ),
				array( 'Footer', esc_html__( '0–5 footer widget columns and a footer bar with its own layout.', 'blank-base' ) ),
			)
		);

		blank_base_help_table(
			esc_html__( 'Theme hooks (for child themes &amp; plugins)', 'blank-base' ),
			array(
				array( 'blank_base_before_header / after_header', esc_html__( 'Fires immediately before and after the site header.', 'blank-base' ) ),
				array( 'blank_base_inside_header', esc_html__( 'Inside the header actions, after the branding and search.', 'blank-base' ) ),
				array( 'blank_base_before_navigation / after_navigation', esc_html__( 'Around the primary navigation menu.', 'blank-base' ) ),
				array( 'blank_base_before_content / after_content', esc_html__( 'Just inside #content, before and after the columns.', 'blank-base' ) ),
				array( 'blank_base_before_main / after_main', esc_html__( 'Top and bottom of the main content column.', 'blank-base' ) ),
				array( 'blank_base_before_right_sidebar / after_right_sidebar', esc_html__( 'Around the right sidebar widget area.', 'blank-base' ) ),
				array( 'blank_base_before_left_sidebar / after_left_sidebar', esc_html__( 'Around the left sidebar widget area (left-sidebar and both-sidebars layouts).', 'blank-base' ) ),
				array( 'blank_base_before_entry_content / after_entry_content', esc_html__( 'Around the post/page content.', 'blank-base' ) ),
				array( 'blank_base_before_footer / after_footer', esc_html__( 'Around the site footer.', 'blank-base' ) ),
				array( 'blank_base_footer_bar', esc_html__( 'Inside the footer bar row.', 'blank-base' ) ),
				array( 'blank_base_top_bar', esc_html__( 'Very top of the page, above the header.', 'blank-base' ) ),
			)
		);
		?>
		<p style="max-width:820px;margin-bottom:0.5em">
			<em><?php esc_html_e( 'Attach markup to any hook with add_action(), e.g. add_action( \'blank_base_after_header\', \'my_banner\' );', 'blank-base' ); ?></em>
		</p>
		<p style="max-width:820px;margin-bottom:2em">
			<strong><?php esc_html_e( 'No code needed:', 'blank-base' ); ?></strong>
			<?php esc_html_e( 'Go to Appearance → Elements to build a block of content and drop it into any of these hooks, with display rules (entire site, front page, all posts/pages, archives or specific IDs) — no child theme required.', 'blank-base' ); ?>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=blank_base_element' ) ); ?>"><?php esc_html_e( 'Open Elements', 'blank-base' ); ?></a>
		</p>

		<h2><?php esc_html_e( 'Patterns', 'blank-base' ); ?></h2>
		<p style="max-width:820px">
			<?php esc_html_e( 'Insert any of the patterns from the block inserter (look under the "Blank Base" and "Blank Base: Pages" categories).', 'blank-base' ); ?>
		</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Open the Customizer', 'blank-base' ); ?></a>
		</p>
	</div>
	<?php
}
