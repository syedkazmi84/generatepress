<?php
/**
 * The front page template.
 *
 * This template is used when a static front page is assigned in
 * Settings → Reading. When the front page is set to display the latest posts,
 * WordPress falls back to home.php / index.php automatically.
 *
 * All content is dynamic: the site owner builds the front page in the block
 * editor and it is rendered here.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package Blank_Base
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php blank_base_do_element( 'before_main' ); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile;
		?>


		<?php blank_base_do_element( 'after_main' ); ?>

	</main><!-- #primary -->

<?php
get_footer();
