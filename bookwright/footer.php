<?php
/**
 * Footer template.
 *
 * @package Bookwright
 */

?>
	</main><!-- #bw-main -->

	<footer class="bw-footer">
		<div class="bw-wrap">
			<div class="bw-footer__grid">

				<div class="bw-footer__brand">
					<div class="bw-brand" style="margin-bottom:16px;">
						<img src="<?php echo bookwright_img( 'logo-white.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="44" height="44" />
						<span class="bw-brand__text">
							<span class="bw-brand__name" style="color:#fff;"><?php bloginfo( 'name' ); ?></span>
							<span class="bw-brand__tag"><?php echo esc_html( bookwright_option( 'bw_tagline', 'Publishing Services' ) ); ?></span>
						</span>
					</div>
					<p><?php echo esc_html( bookwright_option( 'bw_footer_txt', 'Bookwright is a full-service publishing studio helping authors edit, design, publish and market books the world remembers.' ) ); ?></p>
					<div class="bw-footer__social">
						<a href="<?php echo esc_url( bookwright_option( 'bw_social_tw', '#' ) ); ?>" aria-label="Twitter"><?php bookwright_icon( 'twitter' ); ?></a>
						<a href="<?php echo esc_url( bookwright_option( 'bw_social_ig', '#' ) ); ?>" aria-label="Instagram"><?php bookwright_icon( 'instagram' ); ?></a>
						<a href="<?php echo esc_url( bookwright_option( 'bw_social_fb', '#' ) ); ?>" aria-label="Facebook"><?php bookwright_icon( 'facebook' ); ?></a>
						<a href="<?php echo esc_url( bookwright_option( 'bw_social_in', '#' ) ); ?>" aria-label="LinkedIn"><?php bookwright_icon( 'linkedin' ); ?></a>
					</div>
				</div>

				<div class="bw-footer__col">
					<h4><?php esc_html_e( 'Services', 'bookwright' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'services' ) ) ); ?>"><?php esc_html_e( 'Editing', 'bookwright' ); ?></a></li>
						<li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'services' ) ) ); ?>"><?php esc_html_e( 'Cover Design', 'bookwright' ); ?></a></li>
						<li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'services' ) ) ); ?>"><?php esc_html_e( 'Publishing', 'bookwright' ); ?></a></li>
						<li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'services' ) ) ); ?>"><?php esc_html_e( 'Marketing', 'bookwright' ); ?></a></li>
					</ul>
				</div>

				<div class="bw-footer__col">
					<h4><?php esc_html_e( 'Company', 'bookwright' ); ?></h4>
					<?php
					if ( has_nav_menu( 'footer' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'menu_class'     => '',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
					} else {
						echo '<ul>';
						wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) );
						echo '</ul>';
					}
					?>
				</div>

				<div class="bw-footer__col">
					<h4><?php esc_html_e( 'Stay in the loop', 'bookwright' ); ?></h4>
					<p><?php esc_html_e( 'Craft, publishing and marketing tips — twice a month, no spam.', 'bookwright' ); ?></p>
					<form class="bw-newsletter" data-demo-form action="#" method="post">
						<label class="screen-reader-text" for="bw-news"><?php esc_html_e( 'Email address', 'bookwright' ); ?></label>
						<input id="bw-news" type="email" placeholder="<?php esc_attr_e( 'you@email.com', 'bookwright' ); ?>" required />
						<button class="bw-btn bw-btn--primary" type="submit" aria-label="<?php esc_attr_e( 'Subscribe', 'bookwright' ); ?>"><?php bookwright_icon( 'arrow' ); ?></button>
					</form>
					<p class="bw-form__note" style="display:none;font-size:.85rem;color:var(--bw-gold);"></p>
					<div style="margin-top:18px;font-size:.92rem;">
						<div><?php bookwright_icon( 'phone' ); ?> <a href="tel:<?php echo esc_attr( bookwright_option( 'bw_phone', '' ) ); ?>"><?php echo esc_html( bookwright_option( 'bw_phone', '+1 (212) 555-0139' ) ); ?></a></div>
						<div style="margin-top:6px;"><?php bookwright_icon( 'pin' ); ?> <?php echo esc_html( bookwright_option( 'bw_address', '48 Gramercy Park, New York, NY 10010' ) ); ?></div>
					</div>
				</div>

			</div>

			<div class="bw-footer__bottom">
				<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'bookwright' ); ?></span>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'bookwright' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms', 'bookwright' ); ?></a></li>
					<li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php esc_html_e( 'Contact', 'bookwright' ); ?></a></li>
				</ul>
			</div>
		</div>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
