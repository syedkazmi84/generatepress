<?php
/**
 * Search results.
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Results for “%s”', 'bookwright' ), esc_html( get_search_query() ) );
		?></h1>
		<p><?php
			global $wp_query;
			/* translators: %d: number of results. */
			printf( esc_html( _n( '%d result found.', '%d results found.', (int) $wp_query->found_posts, 'bookwright' ) ), (int) $wp_query->found_posts );
		?></p>
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
							<div class="bw-article__body">
								<div class="bw-article__meta"><?php echo esc_html( get_post_type() ); ?> · <?php echo esc_html( get_the_date() ); ?></div>
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p class="bw-article__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
								<a class="bw-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
							</div>
						</article>
						<?php
					endwhile;
					bookwright_pagination();
					?>
				<?php else : ?>
					<div class="bw-card">
						<h2><?php esc_html_e( 'No matches found', 'bookwright' ); ?></h2>
						<p><?php esc_html_e( 'Try different keywords or browse the journal.', 'bookwright' ); ?></p>
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
