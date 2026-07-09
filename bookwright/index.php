<?php
/**
 * Main template — blog index & fallback.
 *
 * @package Bookwright
 */

get_header();

$bw_is_blog_home = ( is_home() || is_front_page() );
?>

<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1>
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} elseif ( is_search() ) {
				/* translators: %s: search query. */
				printf( esc_html__( 'Search: %s', 'bookwright' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			} else {
				esc_html_e( 'The Journal', 'bookwright' );
			}
			?>
		</h1>
		<p><?php esc_html_e( 'Practical guides on writing, editing, design, publishing and marketing your book.', 'bookwright' ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-content-grid">
			<div class="bw-content-main">
				<?php if ( have_posts() ) : ?>
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article <?php post_class( 'bw-article' ); ?>>
							<a href="<?php the_permalink(); ?>" class="bw-article__thumb"><?php bookwright_thumbnail(); ?></a>
							<div class="bw-article__body">
								<div class="bw-article__meta"><?php bookwright_post_meta(); ?></div>
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p class="bw-article__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
								<a class="bw-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Continue reading', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
							</div>
						</article>
						<?php
					endwhile;

					bookwright_pagination();
					?>
				<?php else : ?>
					<div class="bw-card">
						<h2><?php esc_html_e( 'Nothing here yet', 'bookwright' ); ?></h2>
						<p><?php esc_html_e( 'No posts were found. Try a different search or check back soon.', 'bookwright' ); ?></p>
						<?php get_search_form(); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</section>

<?php
get_footer();
