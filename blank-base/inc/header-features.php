<?php
/**
 * Header and navigation features for Blank Base.
 *
 * Dismissible announcement bar and the social-links menu.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'blank_base_announcement_bar' ) ) :
	/**
	 * Output a dismissible announcement bar above the header.
	 */
	function blank_base_announcement_bar() {
		$text = get_theme_mod( 'blank_base_announcement_text', '' );

		if ( ! $text ) {
			return;
		}
		?>
		<div class="announcement-bar" role="region" aria-label="<?php esc_attr_e( 'Announcement', 'blank-base' ); ?>">
			<div class="announcement-bar__inner">
				<div class="announcement-bar__text"><?php echo wp_kses_post( $text ); ?></div>
				<button class="announcement-bar__close" type="button" aria-label="<?php esc_attr_e( 'Dismiss announcement', 'blank-base' ); ?>">&times;</button>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'blank_base_social_menu' ) ) :
	/**
	 * Output the social-links menu, if one is assigned.
	 *
	 * @param string $class Extra class for the nav wrapper.
	 */
	function blank_base_social_menu( $class = '' ) {
		if ( ! has_nav_menu( 'social' ) ) {
			return;
		}
		?>
		<nav class="social-navigation <?php echo esc_attr( $class ); ?>" aria-label="<?php esc_attr_e( 'Social links menu', 'blank-base' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'social',
					'container'      => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>
		<?php
	}
endif;
