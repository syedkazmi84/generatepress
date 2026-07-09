<?php
/**
 * Generic archive (categories, tags, dates, author).
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php the_archive_title(); ?></h1>
		<?php the_archive_description( '<p>', '</p>' ); ?>
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
								<p class="bw-article__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
								<a class="bw-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Continue reading', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
							</div>
						</article>
						<?php
					endwhile;
					bookwright_pagination();
					?>
				<?php else : ?>
					<div class="bw-card"><p><?php esc_html_e( 'Nothing found in this archive yet.', 'bookwright' ); ?></p></div>
				<?php endif; ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>
<?php
get_footer();
