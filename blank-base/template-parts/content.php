<?php
/**
 * Template part for displaying posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blank_Base
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<header class="entry-header">
		<?php
		if ( function_exists( 'blank_base_title_is_hidden' ) && blank_base_title_is_hidden() ) :
			// Title hidden via the per-post layout meta box; render nothing.
			$blank_base_hide_title = true;
		elseif ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				blank_base_posted_on();
				blank_base_posted_by();
				blank_base_reading_time();
				?>
			</div><!-- .entry-meta -->
			<?php
		endif;
		?>
	</header><!-- .entry-header -->

	<?php blank_base_post_thumbnail(); ?>

	<?php blank_base_do_element( 'before_entry_content' ); ?>

	<div class="entry-content">
		<?php
		if ( is_singular() ) :
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers. */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'blank-base' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blank-base' ),
					'after'  => '</div>',
				)
			);
		else :
			the_excerpt();
			?>
			<p class="read-more">
				<a href="<?php the_permalink(); ?>" class="more-link">
					<?php
					printf(
						/* translators: %s: Name of current post. Only visible to screen readers. */
						wp_kses( __( 'Read more<span class="screen-reader-text"> "%s"</span>', 'blank-base' ), array( 'span' => array( 'class' => array() ) ) ),
						wp_kses_post( get_the_title() )
					);
					?>
				</a>
			</p>
			<?php
		endif;
		?>
	</div><!-- .entry-content -->

	<?php blank_base_do_element( 'after_entry_content' ); ?>

	<footer class="entry-footer">
		<?php blank_base_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
