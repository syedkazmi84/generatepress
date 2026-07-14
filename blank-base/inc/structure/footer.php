<?php
/**
 * Footer structure for Blank Base.
 *
 * Renders the footer widget columns (a configurable 0–5) and the footer bar
 * (copyright, footer menu and social links) with its own alignment option —
 * matching the footer-widget + footer-bar split found in GeneratePress.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Number of active footer widget columns configured by the user.
 *
 * @return int 0–5
 */
function blank_base_footer_widget_count() {
	$count = absint( get_theme_mod( 'blank_base_footer_widgets', 3 ) );
	return min( 5, max( 0, $count ) );
}

if ( ! function_exists( 'blank_base_construct_footer_widgets' ) ) :
	/**
	 * Output the footer widget columns.
	 */
	function blank_base_construct_footer_widgets() {
		$count = blank_base_footer_widget_count();

		if ( 0 === $count ) {
			return;
		}

		// Bail if none of the active columns actually contain widgets.
		$has_widgets = false;
		for ( $i = 1; $i <= $count; $i++ ) {
			if ( is_active_sidebar( 'footer-' . $i ) ) {
				$has_widgets = true;
				break;
			}
		}

		if ( ! $has_widgets ) {
			return;
		}
		?>
		<div class="footer-widgets footer-widgets--<?php echo esc_attr( $count ); ?>">
			<?php for ( $i = 1; $i <= $count; $i++ ) : ?>
				<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
					<div class="footer-widget-column footer-widget-column-<?php echo esc_attr( $i ); ?>">
						<?php dynamic_sidebar( 'footer-' . $i ); ?>
					</div>
				<?php endif; ?>
			<?php endfor; ?>
		</div><!-- .footer-widgets -->
		<?php
	}
endif;

if ( ! function_exists( 'blank_base_construct_footer_bar' ) ) :
	/**
	 * Output the footer bar: footer menu, social links and copyright line.
	 */
	function blank_base_construct_footer_bar() {
		if ( ! get_theme_mod( 'blank_base_footer_bar', true ) ) {
			return;
		}

		$alignment = get_theme_mod( 'blank_base_footer_bar_alignment', 'space-between' );
		?>
		<div class="footer-bar footer-bar--<?php echo esc_attr( sanitize_html_class( $alignment ) ); ?>">
			<div class="footer-bar__inner">
				<?php blank_base_do_element( 'before_footer_bar' ); ?>

				<div class="footer-bar__primary">
					<div class="site-info">
						<?php
						$copyright = get_theme_mod( 'blank_base_footer_text' );
						if ( $copyright ) {
							echo wp_kses_post( $copyright );
						} else {
							printf(
								/* translators: 1: Current year, 2: Site name. */
								esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'blank-base' ),
								esc_html( gmdate( 'Y' ) ),
								esc_html( get_bloginfo( 'name' ) )
							);
							echo ' ';
							printf(
								/* translators: %s: WordPress. */
								esc_html__( 'Proudly powered by %s.', 'blank-base' ),
								'<a href="' . esc_url( __( 'https://wordpress.org/', 'blank-base' ) ) . '">WordPress</a>'
							);
						}
						?>
					</div><!-- .site-info -->
				</div>

				<div class="footer-bar__secondary">
					<?php
					if ( has_nav_menu( 'menu-2' ) ) :
						?>
						<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'blank-base' ); ?>">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-2',
									'menu_id'        => 'footer-menu',
									'depth'          => 1,
									'container'      => false,
								)
							);
							?>
						</nav>
						<?php
					endif;

					blank_base_social_menu( 'social-navigation--footer' );
					?>
				</div>

				<?php blank_base_do_element( 'footer_bar' ); ?>
				<?php blank_base_do_element( 'after_footer_bar' ); ?>
			</div><!-- .footer-bar__inner -->
		</div><!-- .footer-bar -->
		<?php
	}
endif;
