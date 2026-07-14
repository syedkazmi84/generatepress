<?php
/**
 * Primary navigation structure for Blank Base.
 *
 * Renders the primary menu and positions it according to the "Navigation
 * Location" Customizer option (inside the header, below the header, or above
 * the header). Also applies the chosen alignment, dropdown trigger (hover or
 * click) and mobile menu style (dropdown or off-canvas).
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The configured navigation location.
 *
 * @return string float-right|float-left|nav-center|below-header|above-header
 */
function blank_base_nav_location() {
	return get_theme_mod( 'blank_base_nav_location', 'float-right' );
}

if ( ! function_exists( 'blank_base_primary_navigation' ) ) :
	/**
	 * Output the primary navigation for a given zone.
	 *
	 * The navigation only renders in the zone that matches its configured
	 * location, so the same function can be attached to the before/after header
	 * hooks and called inline within the header without duplicating output.
	 *
	 * @param string $zone inside-header|below-header|above-header.
	 */
	function blank_base_primary_navigation( $zone = 'inside-header' ) {
		if ( ! has_nav_menu( 'menu-1' ) ) {
			return;
		}

		$location      = blank_base_nav_location();
		$inside_zones  = array( 'float-right', 'float-left', 'nav-center' );
		$target_zone   = in_array( $location, $inside_zones, true ) ? 'inside-header' : $location;

		if ( $zone !== $target_zone ) {
			return;
		}

		$alignment = get_theme_mod( 'blank_base_nav_alignment', 'left' );
		$dropdown  = get_theme_mod( 'blank_base_nav_dropdown', 'hover' );
		$mobile    = get_theme_mod( 'blank_base_mobile_menu', 'dropdown' );

		$classes = array(
			'main-navigation',
			'nav--' . sanitize_html_class( $location ),
			'nav-align--' . sanitize_html_class( $alignment ),
			'nav-dropdown--' . sanitize_html_class( $dropdown ),
			'mobile-menu--' . sanitize_html_class( $mobile ),
		);

		// "Full" navigation bars (below/above header) get their own wrapper for
		// full-width backgrounds.
		$is_bar = in_array( $location, array( 'below-header', 'above-header' ), true );

		if ( $is_bar ) {
			echo '<div class="navigation-bar navigation-bar--' . esc_attr( $location ) . '">';
			echo '<div class="navigation-bar__inner">';
		}
		?>
		<nav id="site-navigation" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" aria-label="<?php esc_attr_e( 'Primary menu', 'blank-base' ); ?>">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="menu-toggle__bars" aria-hidden="true"></span>
				<span class="menu-toggle__label"><?php esc_html_e( 'Menu', 'blank-base' ); ?></span>
			</button>
			<?php blank_base_do_element( 'before_navigation' ); ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'menu nav-menu',
					'container'      => false,
				)
			);

			blank_base_do_element( 'inside_navigation' );
			?>
			<button class="menu-close" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'blank-base' ); ?>">&times;</button>
		</nav><!-- #site-navigation -->
		<?php
		blank_base_do_element( 'after_navigation' );

		if ( $is_bar ) {
			echo '</div><!-- .navigation-bar__inner -->';
			echo '</div><!-- .navigation-bar -->';
		}
	}
endif;

/**
 * Attach the navigation to the before/after header hooks when it is configured
 * to sit above or below the header. Inside-header placement is rendered by the
 * header template directly.
 */
function blank_base_position_navigation() {
	$location = blank_base_nav_location();

	if ( 'below-header' === $location ) {
		add_action(
			'blank_base_after_header',
			function () {
				blank_base_primary_navigation( 'below-header' );
			}
		);
	} elseif ( 'above-header' === $location ) {
		add_action(
			'blank_base_before_header',
			function () {
				blank_base_primary_navigation( 'above-header' );
			}
		);
	}
}
add_action( 'wp', 'blank_base_position_navigation' );
