<?php
/**
 * Template Name: Books Catalog Page
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php esc_html_e( 'The Bookwright catalog', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'Browse titles by genre. Every book here was shaped by our editors and designers.', 'bookwright' ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap">
		<?php
		$genres = get_terms( array( 'taxonomy' => 'genre', 'hide_empty' => true ) );
		if ( $genres && ! is_wp_error( $genres ) ) :
			?>
			<div class="bw-center" style="margin-bottom:44px;display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
				<a class="bw-badge-soft" href="<?php echo esc_url( get_post_type_archive_link( 'book' ) ); ?>"><?php esc_html_e( 'All genres', 'bookwright' ); ?></a>
				<?php foreach ( $genres as $g ) : ?>
					<a class="bw-badge-soft" href="<?php echo esc_url( get_term_link( $g ) ); ?>"><?php echo esc_html( $g->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="bw-books">
			<?php
			$books = new WP_Query(
				array(
					'post_type'      => 'book',
					'posts_per_page' => 16,
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
				for ( $i = 1; $i <= 8; $i++ ) {
					echo '<article class="bw-book"><div class="bw-book__cover"><img src="' . bookwright_img( 'cover-' . ( ( $i % 6 ) + 1 ) . '.svg' ) . '" alt="" /></div><h3>' . esc_html__( 'Sample Title', 'bookwright' ) . '</h3><p class="bw-book__author">' . esc_html__( 'Author Name', 'bookwright' ) . '</p></article>';
				}
			endif;
			?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
