<?php
/**
 * Template for displaying the search form.
 *
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package Blank_Base
 */

$blank_base_unique_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $blank_base_unique_id ); ?>">
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'blank-base' ); ?></span>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $blank_base_unique_id ); ?>"
		class="search-field"
		placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'blank-base' ); ?>"
		value="<?php echo get_search_query(); ?>"
		name="s"
	/>
	<button type="submit" class="search-submit">
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'blank-base' ); ?></span>
		<?php echo esc_html_x( 'Search', 'submit button', 'blank-base' ); ?>
	</button>
</form>
