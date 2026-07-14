<?php
/**
 * The template for displaying archive pages.
 *
 * Used for category, tag, author, date, custom post type and custom taxonomy
 * archives when a more specific template is not available.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blank_Base
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php blank_base_do_element( 'before_main' ); ?>

		<?php blank_base_breadcrumbs(); ?>

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<div class="post-list">
			<?php
			// Start the Loop.
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
