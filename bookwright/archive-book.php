<?php
/**
 * Portfolio archive.
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php esc_html_e( 'Our Portfolio', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'A selection of the books we’ve written, edited, designed, published and marketed with our authors.', 'bookwright' ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap">

		<?php
		// Genre filter row.
		$genres = get_terms( array( 'taxonomy' => 'genre', 'hide_empty' => true ) );
		if ( $genres && ! is_wp_error( $genres ) ) :
			?>
			<div class="bw-center" style="margin-bottom:44px;display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
				<a class="bw-badge-soft" href="<?php echo esc_url( get_post_type_archive_link( 'book' ) ); ?>"><?php esc_html_e( 'All', 'bookwright' ); ?></a>
				<?php foreach ( $genres as $g ) : ?>
					<a class="bw-badge-soft" href="<?php echo esc_url( get_term_link( $g ) ); ?>"><?php echo esc_html( $g->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="bw-books">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/book', 'card' );
				endwhile;
			else :
				esc_html_e( 'No books have been added yet.', 'bookwright' );
			endif;
			?>
		</div>

		<?php bookwright_pagination(); ?>
	</div>
</section>
<?php
get_footer();
