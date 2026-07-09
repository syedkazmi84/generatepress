<?php
/**
 * Genre taxonomy archive — shows books in the catalog grid.
 *
 * @package Bookwright
 */

get_header();
$term = get_queried_object();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php echo esc_html( $term ? $term->name : __( 'Genre', 'bookwright' ) ); ?></h1>
		<p><?php echo esc_html( $term && $term->description ? $term->description : __( 'Books in this genre from the Bookwright catalog.', 'bookwright' ) ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-center" style="margin-bottom:44px;display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
			<a class="bw-badge-soft" href="<?php echo esc_url( get_post_type_archive_link( 'book' ) ); ?>"><?php esc_html_e( 'All genres', 'bookwright' ); ?></a>
			<?php
			$genres = get_terms( array( 'taxonomy' => 'genre', 'hide_empty' => true ) );
			if ( $genres && ! is_wp_error( $genres ) ) {
				foreach ( $genres as $g ) {
					echo '<a class="bw-badge-soft" href="' . esc_url( get_term_link( $g ) ) . '">' . esc_html( $g->name ) . '</a>';
				}
			}
			?>
		</div>

		<div class="bw-books">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/book', 'card' );
				endwhile;
			else :
				esc_html_e( 'No books in this genre yet.', 'bookwright' );
			endif;
			?>
		</div>

		<?php bookwright_pagination(); ?>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
