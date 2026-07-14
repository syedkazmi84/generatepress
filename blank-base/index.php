<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css). It is used to
 * display a page when nothing more specific matches a query, e.g. it puts
 * together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blank_Base
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php blank_base_do_element( 'before_main' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="page-header">
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<div class="post-list">
			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a
				 * file called content-___.php (where ___ is the Post Type name)
				 * and that will be used instead.
				 */
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
