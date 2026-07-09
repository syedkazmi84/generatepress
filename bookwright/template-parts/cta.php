<?php
/**
 * Reusable closing call-to-action band.
 *
 * @package Bookwright
 */

?>
<section class="bw-section bw-section--tight">
	<div class="bw-wrap">
		<div class="bw-cta">
			<span class="bw-eyebrow" style="color:var(--bw-gold);"><?php esc_html_e( 'Let’s begin', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Your book deserves a proper launch', 'bookwright' ); ?></h2>
			<p><?php esc_html_e( 'Tell us about your project and get a free, no-obligation quote within one business day.', 'bookwright' ); ?></p>
			<div class="bw-cta__actions">
				<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php esc_html_e( 'Start your book', 'bookwright' ); ?></a>
				<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'pricing' ) ) ); ?>"><?php esc_html_e( 'View pricing', 'bookwright' ); ?></a>
			</div>
		</div>
	</div>
</section>
