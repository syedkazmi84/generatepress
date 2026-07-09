<?php
/**
 * Book catalog card.
 *
 * @package Bookwright
 */

$bw_author = bookwright_book_meta( '_bw_author' );
$bw_price  = bookwright_book_meta( '_bw_price' );
$bw_rating = (int) bookwright_book_meta( '_bw_rating' );
$bw_cover  = bookwright_book_meta( '_bw_cover' );
$bw_genre  = get_the_terms( get_the_ID(), 'genre' );
?>
<article <?php post_class( 'bw-book' ); ?>>
	<a href="<?php the_permalink(); ?>" class="bw-book__cover">
		<?php if ( $bw_genre && ! is_wp_error( $bw_genre ) ) : ?>
			<span class="bw-book__tag"><?php echo esc_html( $bw_genre[0]->name ); ?></span>
		<?php endif; ?>
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'bookwright-cover', array( 'loading' => 'lazy' ) );
		} else {
			$cover = $bw_cover ? $bw_cover : 'cover-1.svg';
			echo '<img src="' . bookwright_img( $cover ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy" />';
		}
		?>
	</a>
	<h3><a href="<?php the_permalink(); ?>" style="color:var(--bw-ink);"><?php the_title(); ?></a></h3>
	<?php if ( $bw_author ) : ?>
		<p class="bw-book__author"><?php echo esc_html( $bw_author ); ?></p>
	<?php endif; ?>
	<div class="bw-book__meta">
		<?php if ( $bw_rating ) : ?>
			<span class="bw-book__stars" aria-label="<?php echo esc_attr( $bw_rating . ' / 5' ); ?>"><?php echo esc_html( str_repeat( '★', $bw_rating ) . str_repeat( '☆', 5 - $bw_rating ) ); ?></span>
		<?php endif; ?>
		<?php if ( $bw_price ) : ?>
			<strong style="color:var(--bw-ink);"><?php echo esc_html( $bw_price ); ?></strong>
		<?php endif; ?>
	</div>
</article>
