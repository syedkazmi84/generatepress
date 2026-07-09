<?php
/**
 * Comments template.
 *
 * @package Bookwright
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="bw-comments" style="background:#fff;border:1px solid var(--bw-line);border-radius:var(--bw-radius);padding:34px;margin-top:34px;box-shadow:var(--bw-shadow-sm);">
	<?php if ( have_comments() ) : ?>
		<h2 class="bw-comments__title" style="margin-bottom:24px;">
			<?php
			$bw_count = get_comments_number();
			/* translators: %s: comment count. */
			printf( esc_html( _n( '%s Comment', '%s Comments', $bw_count, 'bookwright' ) ), esc_html( number_format_i18n( $bw_count ) ) );
			?>
		</h2>

		<ol class="comment-list" style="list-style:none;padding:0;">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'avatar_size' => 52,
					'short_ping'  => true,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
			)
		);
		?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'bookwright' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Leave a comment', 'bookwright' ),
			'class_submit'       => 'bw-btn bw-btn--primary',
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title" style="margin-top:10px;">',
			'title_reply_after'  => '</h3>',
		)
	);
	?>
</div>
