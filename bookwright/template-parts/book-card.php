<?php
/**
 * Portfolio project card (showcase only — no pricing).
 *
 * @package Bookwright
 */

$bw_author  = bookwright_book_meta( '_bw_author' );
$bw_service = bookwright_book_meta( '_bw_service' );
$bw_cover   = bookwright_book_meta( '_bw_cover' );
$bw_cat     = get_the_terms( get_the_ID(), 'genre' );
?>
<article <?php post_class( 'bw-book' ); ?>>
	<a href="<?php the_permalink(); ?>" class="bw-book__cover">
		<?php if ( $bw_service ) : ?>
			<span class="bw-book__tag"><?php echo esc_html( $bw_service ); ?></span>
		<?php elseif ( $bw_cat && ! is_wp_error( $bw_cat ) ) : ?>
			<span class="bw-book__tag"><?php echo esc_html( $bw_cat[0]->name ); ?></span>
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
</article>
