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
		<h1><?php esc_html_e( 'Our work', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'A selection of books we’ve helped bring to life — through ghostwriting, editing, design, publishing and marketing.', 'bookwright' ); ?></p>
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
			<div class="bw-stat-lg"><strong>750+</strong><span><?php esc_html_e( 'Books published', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong>900+</strong><span><?php esc_html_e( 'Happy authors', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong>15+</strong><span><?php esc_html_e( 'Years of experience', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong>100%</strong><span><?php esc_html_e( 'Ownership you keep', 'bookwright' ); ?></span></div>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
