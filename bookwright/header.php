<?php
/**
 * Header template.
 *
 * @package Bookwright
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="bw-skip-link screen-reader-text" href="#bw-main"><?php esc_html_e( 'Skip to content', 'bookwright' ); ?></a>

<div id="page" class="bw-site">

	<!-- Top bar -->
	<div class="bw-topbar">
		<div class="bw-wrap">
			<div class="bw-topbar__meta">
				<span><?php bookwright_icon( 'mail' ); ?> <a href="mailto:<?php echo esc_attr( bookwright_option( 'bw_email', 'hello@yourpublishing.com' ) ); ?>"><?php echo esc_html( bookwright_option( 'bw_email', 'hello@yourpublishing.com' ) ); ?></a></span>
				<span><?php bookwright_icon( 'clock' ); ?> <?php echo esc_html( bookwright_option( 'bw_hours', 'Mon–Sat · 8:00 AM–6:00 PM' ) ); ?></span>
			</div>
			<div class="bw-topbar__social">
				<a href="<?php echo esc_url( bookwright_option( 'bw_social_tw', '#' ) ); ?>" aria-label="Twitter"><?php bookwright_icon( 'twitter' ); ?></a>
				<a href="<?php echo esc_url( bookwright_option( 'bw_social_ig', '#' ) ); ?>" aria-label="Instagram"><?php bookwright_icon( 'instagram' ); ?></a>
				<a href="<?php echo esc_url( bookwright_option( 'bw_social_fb', '#' ) ); ?>" aria-label="Facebook"><?php bookwright_icon( 'facebook' ); ?></a>
				<a href="<?php echo esc_url( bookwright_option( 'bw_social_in', '#' ) ); ?>" aria-label="LinkedIn"><?php bookwright_icon( 'linkedin' ); ?></a>
			</div>
		</div>
	</div>

	<!-- Header -->
	<header class="bw-header">
		<div class="bw-wrap">
			<div class="bw-brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bw-brand__link" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<img src="<?php echo bookwright_img( 'logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="44" height="44" />
						<span class="bw-brand__text">
							<span class="bw-brand__name"><?php bloginfo( 'name' ); ?></span>
							<span class="bw-brand__tag"><?php echo esc_html( bookwright_option( 'bw_tagline', 'Book Publishing Services' ) ); ?></span>
						</span>
					</a>
				<?php endif; ?>
			</div>

			<button class="bw-nav-toggle" aria-controls="bw-primary-nav" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'bookwright' ); ?></span>
				<span></span><span></span><span></span>
			</button>

			<nav id="bw-primary-nav" class="bw-nav" aria-label="<?php esc_attr_e( 'Primary', 'bookwright' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'bw-menu',
							'depth'          => 2,
							'fallback_cb'    => false,
						)
					);
				} else {
					echo '<ul class="bw-menu">';
					wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) );
					echo '</ul>';
				}
				?>
				<a class="bw-btn bw-btn--primary bw-header__cta" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>">
					<?php esc_html_e( 'Get a quote', 'bookwright' ); ?>
				</a>
			</nav>
		</div>
	</header>

	<main id="bw-main" class="bw-main">
