<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blank_Base
 */

?>
		<?php blank_base_do_element( 'after_content' ); ?>

	</div><!-- #content -->

	<?php blank_base_do_element( 'before_footer' ); ?>

	<footer id="colophon" class="site-footer">
		<?php
		blank_base_do_element( 'inside_footer' );

		// Footer widget columns (configurable count).
		blank_base_construct_footer_widgets();

		// Footer bar: copyright, footer menu and social links.
		blank_base_construct_footer_bar();
		?>
	</footer><!-- #colophon -->

	<?php blank_base_do_element( 'after_footer' ); ?>
</div><!-- #page -->

<?php if ( get_theme_mod( 'blank_base_back_to_top', true ) ) : ?>
	<button class="back-to-top" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'blank-base' ); ?>" title="<?php esc_attr_e( 'Back to top', 'blank-base' ); ?>">
		<span aria-hidden="true">&uarr;</span>
	</button>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>
