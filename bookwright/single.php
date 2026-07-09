<?php
/**
 * Single blog post.
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
			<p><?php bookwright_post_meta(); ?></p>
		</div>
	</section>

	<section class="bw-section">
		<div class="bw-wrap">
			<div class="bw-content-grid">
				<div class="bw-content-main">
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

							<?php if ( has_tag() ) : ?>
								<div class="bw-tags"><?php the_tags( '', '' ); ?></div>
							<?php endif; ?>
						</div>
					</article>

					<div class="bw-postnav">
						<?php
						$prev = get_previous_post();
						$next = get_next_post();
						if ( $prev ) {
							echo '<a class="bw-card" href="' . esc_url( get_permalink( $prev ) ) . '"><small>' . esc_html__( '← Previous', 'bookwright' ) . '</small><br><strong>' . esc_html( get_the_title( $prev ) ) . '</strong></a>';
						}
						if ( $next ) {
							echo '<a class="bw-card" style="text-align:right;" href="' . esc_url( get_permalink( $next ) ) . '"><small>' . esc_html__( 'Next →', 'bookwright' ) . '</small><br><strong>' . esc_html( get_the_title( $next ) ) . '</strong></a>';
						}
						?>
					</div>

					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</div>

				<?php get_sidebar(); ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
