<?php
/**
 * 404 template.
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<h1><?php esc_html_e( '404', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'This page has wandered off the shelf.', 'bookwright' ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap bw-center">
		<p class="bw-lead" style="margin:0 auto 26px;"><?php esc_html_e( 'The page you’re looking for doesn’t exist or has moved. Try a search, or head back to safe ground.', 'bookwright' ); ?></p>
		<div style="max-width:460px;margin:0 auto 30px;"><?php get_search_form(); ?></div>
		<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'bookwright' ); ?></a>
	</div>
</section>
<?php
get_footer();
