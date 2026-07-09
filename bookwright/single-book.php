<?php
/**
 * Single book detail page.
 *
 * @package Bookwright
 */

get_header();

while ( have_posts() ) :
	the_post();

	$bw_author = bookwright_book_meta( '_bw_author' );
	$bw_price  = bookwright_book_meta( '_bw_price' );
	$bw_rating = (int) bookwright_book_meta( '_bw_rating' );
	$bw_link   = bookwright_book_meta( '_bw_link' );
	$bw_cover  = bookwright_book_meta( '_bw_cover' );
	$bw_genre  = get_the_terms( get_the_ID(), 'genre' );
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
					<?php if ( $bw_genre && ! is_wp_error( $bw_genre ) ) : ?>
						<span class="bw-badge-soft"><?php echo esc_html( $bw_genre[0]->name ); ?></span>
					<?php endif; ?>

					<?php if ( $bw_rating ) : ?>
						<div style="margin:16px 0;color:var(--bw-gold);font-size:1.3rem;letter-spacing:2px;" aria-label="<?php echo esc_attr( $bw_rating . ' / 5' ); ?>">
							<?php echo esc_html( str_repeat( '★', $bw_rating ) . str_repeat( '☆', 5 - $bw_rating ) ); ?>
						</div>
					<?php endif; ?>

					<div class="bw-entry" style="margin-top:10px;">
						<?php the_content(); ?>
					</div>

					<div style="display:flex;align-items:center;gap:24px;margin-top:26px;flex-wrap:wrap;">
						<?php if ( $bw_price ) : ?>
							<span class="bw-plan__price" style="margin:0;"><?php echo esc_html( $bw_price ); ?></span>
						<?php endif; ?>
						<?php if ( $bw_link ) : ?>
							<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( $bw_link ); ?>"><?php esc_html_e( 'Get the book', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
						<?php endif; ?>
						<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php esc_html_e( 'Publish yours', 'bookwright' ); ?></a>
					</div>
				</div>
			</div>

			<!-- Related books -->
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
					<div class="bw-section-head bw-center"><h2><?php esc_html_e( 'More from our catalog', 'bookwright' ); ?></h2></div>
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
