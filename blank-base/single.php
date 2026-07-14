<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blank_Base
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php blank_base_do_element( 'before_main' ); ?>

		<?php
		blank_base_breadcrumbs();

		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			blank_base_social_share();
			blank_base_author_box();
			blank_base_related_posts();

			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'blank-base' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'blank-base' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>


		<?php blank_base_do_element( 'after_main' ); ?>

	</main><!-- #primary -->

<?php
get_sidebar();
get_footer();
