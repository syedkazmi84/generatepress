<?php
/**
 * Blog sidebar.
 *
 * @package Bookwright
 */

?>
<aside class="bw-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'bookwright' ); ?>">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else : ?>

		<section class="widget">
			<h3 class="widget-title"><?php esc_html_e( 'Search', 'bookwright' ); ?></h3>
			<?php get_search_form(); ?>
		</section>

		<section class="widget">
			<h3 class="widget-title"><?php esc_html_e( 'Categories', 'bookwright' ); ?></h3>
			<ul><?php wp_list_categories( array( 'title_li' => '', 'show_count' => true ) ); ?></ul>
		</section>

		<section class="widget">
			<h3 class="widget-title"><?php esc_html_e( 'Recent Posts', 'bookwright' ); ?></h3>
			<ul>
				<?php
				$recent = wp_get_recent_posts( array( 'numberposts' => 5, 'post_status' => 'publish' ) );
				foreach ( $recent as $r ) {
					echo '<li><a href="' . esc_url( get_permalink( $r['ID'] ) ) . '">' . esc_html( $r['post_title'] ) . '</a></li>';
				}
				?>
			</ul>
		</section>

	<?php endif; ?>

	<section class="widget bw-cta-card">
		<h3 class="widget-title"><?php esc_html_e( 'Ready to publish?', 'bookwright' ); ?></h3>
		<p><?php esc_html_e( 'Get a free, no-obligation quote for your manuscript.', 'bookwright' ); ?></p>
		<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" style="margin-top:8px;"><?php esc_html_e( 'Get a quote', 'bookwright' ); ?></a>
	</section>
</aside>
