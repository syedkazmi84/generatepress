<?php
/**
 * The template for displaying the blog posts index.
 *
 * This is the template that displays the latest posts page. It is used when a
 * static front page is set and a separate "Posts page" is chosen in
 * Settings → Reading, and also as the default site home. If you want to
 * override this template, create your own home.php in a child theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#home-page-display
 *
 * @package Blank_Base
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php blank_base_do_element( 'before_main' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php if ( have_posts() && ! is_front_page() && get_the_title( get_option( 'page_for_posts' ) ) ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ); ?></h1>
				</header>
			<?php endif; ?>

			<div class="post-list">
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;
			?>
			</div><!-- .post-list -->
			<?php

			the_posts_navigation(
				array(
					'prev_text' => esc_html__( 'Older posts', 'blank-base' ),
					'next_text' => esc_html__( 'Newer posts', 'blank-base' ),
				)
			);

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>


		<?php blank_base_do_element( 'after_main' ); ?>

	</main><!-- #primary -->

<?php
get_sidebar();
get_footer();
