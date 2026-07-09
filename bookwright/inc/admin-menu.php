<?php
/**
 * Consolidated admin menu.
 *
 * Puts all of the theme's editors (Portfolio, Services, Testimonials, Team,
 * Pricing Plans) under a single top-level "Bookwright" tab, instead of each
 * one being its own separate item in the sidebar. The custom post types attach
 * here via their `show_in_menu => 'bookwright-hub'` setting.
 *
 * @package Bookwright
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the top-level hub and rename its first submenu to "Overview".
 */
function bookwright_admin_hub_menu() {
	add_menu_page(
		__( 'Bookwright', 'bookwright' ),
		__( 'Bookwright', 'bookwright' ),
		'edit_posts',
		'bookwright-hub',
		'bookwright_admin_hub_page',
		'dashicons-book-alt',
		3
	);

	add_submenu_page(
		'bookwright-hub',
		__( 'Overview', 'bookwright' ),
		__( 'Overview', 'bookwright' ),
		'edit_posts',
		'bookwright-hub',
		'bookwright_admin_hub_page'
	);
}
add_action( 'admin_menu', 'bookwright_admin_hub_menu' );

/**
 * The hub landing page — quick links to every editable part of the site.
 */
function bookwright_admin_hub_page() {
	$content = array(
		array( 'dashicons-portfolio', __( 'Portfolio', 'bookwright' ), __( 'Books you’ve helped create.', 'bookwright' ), admin_url( 'edit.php?post_type=book' ) ),
		array( 'dashicons-hammer', __( 'Services', 'bookwright' ), __( 'Your service offerings.', 'bookwright' ), admin_url( 'edit.php?post_type=bw_service' ) ),
		array( 'dashicons-format-quote', __( 'Testimonials', 'bookwright' ), __( 'Author reviews & ratings.', 'bookwright' ), admin_url( 'edit.php?post_type=bw_testimonial' ) ),
		array( 'dashicons-groups', __( 'Team', 'bookwright' ), __( 'Team members on the About page.', 'bookwright' ), admin_url( 'edit.php?post_type=bw_team' ) ),
		array( 'dashicons-tag', __( 'Pricing Plans', 'bookwright' ), __( 'Packages on the Pricing page.', 'bookwright' ), admin_url( 'edit.php?post_type=bw_plan' ) ),
		array( 'dashicons-admin-post', __( 'Blog Posts', 'bookwright' ), __( 'Articles in the Journal.', 'bookwright' ), admin_url( 'edit.php' ) ),
	);

	$customize = array(
		array( __( 'Homepage sections', 'bookwright' ), __( 'Headings, stats, process steps, logos.', 'bookwright' ), 'bookwright_sections' ),
		array( __( 'Homepage hero', 'bookwright' ), __( 'The big headline and buttons up top.', 'bookwright' ), 'bookwright_hero' ),
		array( __( 'Contact & social', 'bookwright' ), __( 'Email, phone, address, hours, socials.', 'bookwright' ), 'bookwright_contact' ),
		array( __( 'Logo & site title', 'bookwright' ), __( 'Upload your logo and set the site name.', 'bookwright' ), 'title_tagline' ),
	);
	?>
	<div class="wrap">
		<h1 style="display:flex;align-items:center;gap:10px;">
			<span class="dashicons dashicons-book-alt" style="font-size:30px;width:30px;height:30px;"></span>
			<?php esc_html_e( 'Bookwright — Manage Your Site', 'bookwright' ); ?>
		</h1>
		<p style="font-size:14px;max-width:760px;color:#50575e;">
			<?php esc_html_e( 'Everything you can edit is here. Content lists are below; site-wide text, the hero, contact details and your logo are in the Customizer.', 'bookwright' ); ?>
		</p>

		<h2 style="margin-top:24px;"><?php esc_html_e( 'Content', 'bookwright' ); ?></h2>
		<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;max-width:1000px;">
			<?php foreach ( $content as $c ) : ?>
				<a href="<?php echo esc_url( $c[3] ); ?>" style="display:block;text-decoration:none;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:18px 20px;color:#1d2327;box-shadow:0 1px 2px rgba(0,0,0,.04);">
					<span class="dashicons <?php echo esc_attr( $c[0] ); ?>" style="color:#c08a3e;font-size:24px;width:24px;height:24px;"></span>
					<strong style="display:block;margin:8px 0 4px;font-size:15px;"><?php echo esc_html( $c[1] ); ?></strong>
					<span style="color:#646970;font-size:13px;"><?php echo esc_html( $c[2] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>

		<h2 style="margin-top:30px;"><?php esc_html_e( 'Design & text (Customizer)', 'bookwright' ); ?></h2>
		<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;max-width:1000px;">
			<?php foreach ( $customize as $cz ) : ?>
				<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=' . $cz[2] ) ); ?>" style="display:block;text-decoration:none;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:18px 20px;color:#1d2327;box-shadow:0 1px 2px rgba(0,0,0,.04);">
					<span class="dashicons dashicons-admin-customizer" style="color:#c08a3e;font-size:24px;width:24px;height:24px;"></span>
					<strong style="display:block;margin:8px 0 4px;font-size:15px;"><?php echo esc_html( $cz[0] ); ?></strong>
					<span style="color:#646970;font-size:13px;"><?php echo esc_html( $cz[1] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>

		<h2 style="margin-top:30px;"><?php esc_html_e( 'Pages & menu', 'bookwright' ); ?></h2>
		<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;max-width:1000px;">
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>" style="display:block;text-decoration:none;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:18px 20px;color:#1d2327;box-shadow:0 1px 2px rgba(0,0,0,.04);">
				<span class="dashicons dashicons-admin-page" style="color:#c08a3e;font-size:24px;width:24px;height:24px;"></span>
				<strong style="display:block;margin:8px 0 4px;font-size:15px;"><?php esc_html_e( 'Pages', 'bookwright' ); ?></strong>
				<span style="color:#646970;font-size:13px;"><?php esc_html_e( 'About, Services, Pricing, Contact…', 'bookwright' ); ?></span>
			</a>
			<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" style="display:block;text-decoration:none;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:18px 20px;color:#1d2327;box-shadow:0 1px 2px rgba(0,0,0,.04);">
				<span class="dashicons dashicons-menu" style="color:#c08a3e;font-size:24px;width:24px;height:24px;"></span>
				<strong style="display:block;margin:8px 0 4px;font-size:15px;"><?php esc_html_e( 'Menus', 'bookwright' ); ?></strong>
				<span style="color:#646970;font-size:13px;"><?php esc_html_e( 'Add pages to your navigation.', 'bookwright' ); ?></span>
			</a>
		</div>

		<p style="margin-top:28px;color:#646970;font-size:13px;max-width:760px;">
			<?php esc_html_e( 'Note: FAQs are written directly in the page templates (front-page.php, tpl-services.php, tpl-pricing.php) via Appearance → Theme File Editor.', 'bookwright' ); ?>
		</p>
	</div>
	<?php
}
