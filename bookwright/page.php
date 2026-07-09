<?php
/**
 * Default page template.
 *
 * @package Bookwright
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="bw-page-hero">
		<div class="bw-wrap">
			<?php bookwright_breadcrumb(); ?>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<section class="bw-section">
		<div class="bw-wrap">
			<div class="bw-content-grid bw-content-grid--full" style="max-width:820px;margin:0 auto;">
				<article <?php post_class( 'bw-single' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="bw-single__hero"><?php the_post_thumbnail( 'full' ); ?></div>
					<?php endif; ?>
					<div class="bw-single__body">
						<div class="bw-entry">
							<?php
							the_content();
							wp_link_pages(
								array(
									'before' => '<div class="bw-pagelinks">' . esc_html__( 'Pages:', 'bookwright' ),
									'after'  => '</div>',
								)
							);
							?>
						</div>
					</div>
				</article>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
