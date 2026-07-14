<?php
/**
 * Template Name: Blank Canvas (No Header/Footer)
 *
 * A bare template with no site header, footer, sidebar or container — just the
 * page content. Ideal for landing pages, coming-soon pages or fully
 * block-built layouts. wp_head()/wp_footer() still fire so plugins and the
 * block editor work normally.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Blank_Base
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'blank-canvas' ); ?>>
<?php wp_body_open(); ?>

<main id="primary" class="site-main site-main--canvas">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blank-base' ),
				'after'  => '</div>',
			)
		);
	endwhile;
	?>
</main>

<?php wp_footer(); ?>
</body>
</html>
