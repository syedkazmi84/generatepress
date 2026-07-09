<?php
/**
 * Search form.
 *
 * @package Bookwright
 */

?>
<form role="search" method="get" class="bw-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="bw-s"><?php esc_html_e( 'Search for:', 'bookwright' ); ?></label>
	<div style="display:flex;gap:8px;">
		<input type="search" id="bw-s" class="bw-input" placeholder="<?php esc_attr_e( 'Search…', 'bookwright' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		<button type="submit" class="bw-btn bw-btn--primary" aria-label="<?php esc_attr_e( 'Search', 'bookwright' ); ?>"><?php bookwright_icon( 'search' ); ?></button>
	</div>
</form>
