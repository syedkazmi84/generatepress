<?php
/**
 * Template Name: Portfolio Page
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php esc_html_e( 'Our portfolio', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'A shelf of recent titles we edited, designed, published and launched with their authors.', 'bookwright' ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-books">
			<?php
			$books = new WP_Query(
				array(
					'post_type'      => 'book',
					'posts_per_page' => 12,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				)
			);
			if ( $books->have_posts() ) :
				while ( $books->have_posts() ) :
					$books->the_post();
					get_template_part( 'template-parts/book', 'card' );
				endwhile;
				wp_reset_postdata();
			else :
				// Static fallback covers.
				for ( $i = 1; $i <= 6; $i++ ) {
					echo '<article class="bw-book"><div class="bw-book__cover"><img src="' . bookwright_img( 'cover-' . $i . '.svg' ) . '" alt="" /></div><h3>' . esc_html__( 'Sample Title', 'bookwright' ) . '</h3><p class="bw-book__author">' . esc_html__( 'Author Name', 'bookwright' ) . '</p></article>';
				}
			endif;
			?>
		</div>
	</div>
</section>

<!-- Results band -->
<section class="bw-section bw-section--tight bw-section--ink">
	<div class="bw-wrap">
		<div class="bw-stats-band">
			<div class="bw-stat-lg"><strong data-count="1200" data-suffix="+">1200+</strong><span><?php esc_html_e( 'Titles delivered', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="35" data-suffix="+">35+</strong><span><?php esc_html_e( 'Bestseller lists', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="18" data-suffix="+">18+</strong><span><?php esc_html_e( 'Awards won', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="4" data-suffix=".9">4.9</strong><span><?php esc_html_e( 'Avg. author rating', 'bookwright' ); ?></span></div>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
