<?php
/**
 * Single portfolio project page (showcase — no pricing/buy).
 *
 * @package Bookwright
 */

get_header();

while ( have_posts() ) :
	the_post();

	$bw_author  = bookwright_book_meta( '_bw_author' );
	$bw_service = bookwright_book_meta( '_bw_service' );
	$bw_cover   = bookwright_book_meta( '_bw_cover' );
	$bw_cat     = get_the_terms( get_the_ID(), 'genre' );
	?>
	<section class="bw-page-hero">
		<div class="bw-wrap">
			<?php bookwright_breadcrumb(); ?>
			<h1><?php the_title(); ?></h1>
			<?php if ( $bw_author ) : ?>
				<p><?php
					/* translators: %s: author name. */
					printf( esc_html__( 'by %s', 'bookwright' ), esc_html( $bw_author ) );
				?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="bw-section">
		<div class="bw-wrap">
			<div class="bw-split" style="align-items:start;">
				<div class="bw-book__cover" style="max-width:360px;margin:0 auto;box-shadow:var(--bw-shadow);">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'bookwright-cover' );
					} else {
						echo '<img src="' . bookwright_img( $bw_cover ? $bw_cover : 'cover-1.svg' ) . '" alt="' . esc_attr( get_the_title() ) . '" />';
					}
					?>
				</div>

				<div>
					<?php if ( $bw_cat && ! is_wp_error( $bw_cat ) ) : ?>
						<span class="bw-badge-soft"><?php echo esc_html( $bw_cat[0]->name ); ?></span>
					<?php endif; ?>

					<?php if ( $bw_service ) : ?>
						<p style="margin:16px 0 0;color:var(--bw-gold-dark);font-weight:600;"><?php
							/* translators: %s: service provided. */
							printf( esc_html__( 'What we did: %s', 'bookwright' ), esc_html( $bw_service ) );
						?></p>
					<?php endif; ?>

					<div class="bw-entry" style="margin-top:14px;">
						<?php the_content(); ?>
					</div>

					<div style="margin-top:26px;">
						<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php esc_html_e( 'Start your book', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
					</div>
				</div>
			</div>

			<!-- Related projects -->
			<?php
			$related = new WP_Query(
				array(
					'post_type'      => 'book',
					'posts_per_page' => 4,
					'post__not_in'   => array( get_the_ID() ),
					'orderby'        => 'rand',
				)
			);
			if ( $related->have_posts() ) :
				?>
				<div style="margin-top:80px;">
					<div class="bw-section-head bw-center"><h2><?php esc_html_e( 'More of our work', 'bookwright' ); ?></h2></div>
					<div class="bw-books">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							get_template_part( 'template-parts/book', 'card' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
