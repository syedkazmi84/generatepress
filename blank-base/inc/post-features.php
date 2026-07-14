<?php
/**
 * Single-post reading features for Blank Base.
 *
 * Reading time, reading-progress bar, auto table of contents, related posts,
 * author box and social share — each independently toggled in the Customizer
 * (Blog & Posts).
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Estimate the reading time of the current post in minutes.
 *
 * @param int|null $post_id Optional post ID. Defaults to current post.
 * @return int Minutes (minimum 1).
 */
function blank_base_get_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

if ( ! function_exists( 'blank_base_reading_time' ) ) :
	/**
	 * Output the reading-time meta item.
	 */
	function blank_base_reading_time() {
		if ( 'post' !== get_post_type() || ! get_theme_mod( 'blank_base_reading_time', true ) ) {
			return;
		}

		$minutes = blank_base_get_reading_time();

		echo '<span class="reading-time">';
		printf(
			/* translators: %d: number of minutes. */
			esc_html( _n( '%d min read', '%d min read', $minutes, 'blank-base' ) ),
			absint( $minutes )
		);
		echo '</span>';
	}
endif;

/**
 * Output the reading-progress bar on single posts (via wp_body_open).
 */
function blank_base_reading_progress_bar() {
	if ( ! is_singular( 'post' ) || ! get_theme_mod( 'blank_base_reading_progress', true ) ) {
		return;
	}
	echo '<div class="reading-progress" aria-hidden="true"><span class="reading-progress__bar"></span></div>';
}
add_action( 'wp_body_open', 'blank_base_reading_progress_bar' );

/**
 * Inject a table of contents and heading anchors into single-post content.
 *
 * @param string $content The post content.
 * @return string
 */
function blank_base_table_of_contents( $content ) {
	if ( ! is_singular( 'post' ) || ! is_main_query() || ! in_the_loop() ) {
		return $content;
	}
	if ( ! get_theme_mod( 'blank_base_toc', false ) ) {
		return $content;
	}

	if ( ! preg_match_all( '/<h([23])\b([^>]*)>(.*?)<\/h\1>/is', $content, $matches, PREG_SET_ORDER ) ) {
		return $content;
	}

	// Only worthwhile when there are a few headings.
	if ( count( $matches ) < 3 ) {
		return $content;
	}

	$used  = array();
	$items = array();

	foreach ( $matches as $match ) {
		$level = (int) $match[1];
		$attrs = $match[2];
		$text  = trim( wp_strip_all_tags( $match[3] ) );

		if ( '' === $text ) {
			continue;
		}

		// Reuse an existing id if present, otherwise generate a unique slug.
		if ( preg_match( '/\bid=("|\')(.*?)\1/i', $attrs, $id_match ) ) {
			$slug = $id_match[2];
		} else {
			$base = sanitize_title( $text );
			$slug = $base;
			$i    = 2;
			while ( isset( $used[ $slug ] ) ) {
				$slug = $base . '-' . $i;
				$i++;
			}
			// Add the id to this heading in the content.
			$new_heading = '<h' . $level . $attrs . ' id="' . esc_attr( $slug ) . '">' . $match[3] . '</h' . $level . '>';
			$content     = str_replace( $match[0], $new_heading, $content );
		}

		$used[ $slug ] = true;
		$items[]       = array(
			'level' => $level,
			'text'  => $text,
			'slug'  => $slug,
		);
	}

	if ( empty( $items ) ) {
		return $content;
	}

	$toc  = '<nav class="table-of-contents" aria-label="' . esc_attr__( 'Table of contents', 'blank-base' ) . '">';
	$toc .= '<h2 class="table-of-contents__title">' . esc_html__( 'Contents', 'blank-base' ) . '</h2><ul>';
	foreach ( $items as $item ) {
		$toc .= '<li class="toc-level-' . absint( $item['level'] ) . '"><a href="#' . esc_attr( $item['slug'] ) . '">' . esc_html( $item['text'] ) . '</a></li>';
	}
	$toc .= '</ul></nav>';

	return $toc . $content;
}
add_filter( 'the_content', 'blank_base_table_of_contents', 20 );

if ( ! function_exists( 'blank_base_social_share' ) ) :
	/**
	 * Output social-share links for the current post (no third-party scripts).
	 */
	function blank_base_social_share() {
		if ( ! is_singular( 'post' ) || ! get_theme_mod( 'blank_base_social_share', true ) ) {
			return;
		}

		$url   = rawurlencode( get_permalink() );
		$title = rawurlencode( get_the_title() );

		$links = array(
			'x'        => array(
				'label' => esc_html__( 'Share on X', 'blank-base' ),
				'url'   => "https://twitter.com/intent/tweet?url={$url}&text={$title}",
				'text'  => 'X',
			),
			'facebook' => array(
				'label' => esc_html__( 'Share on Facebook', 'blank-base' ),
				'url'   => "https://www.facebook.com/sharer/sharer.php?u={$url}",
				'text'  => 'Facebook',
			),
			'linkedin' => array(
				'label' => esc_html__( 'Share on LinkedIn', 'blank-base' ),
				'url'   => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
				'text'  => 'LinkedIn',
			),
			'email'    => array(
				'label' => esc_html__( 'Share by email', 'blank-base' ),
				'url'   => "mailto:?subject={$title}&body={$url}",
				'text'  => esc_html__( 'Email', 'blank-base' ),
			),
		);

		echo '<div class="social-share"><span class="social-share__label">' . esc_html__( 'Share', 'blank-base' ) . '</span><ul class="social-share__list">';
		foreach ( $links as $key => $link ) {
			printf(
				'<li><a class="social-share__link social-share__link--%1$s" href="%2$s" target="_blank" rel="noopener noreferrer nofollow" aria-label="%3$s">%4$s</a></li>',
				esc_attr( $key ),
				esc_url( $link['url'] ),
				esc_attr( $link['label'] ),
				esc_html( $link['text'] )
			);
		}
		echo '</ul></div>';
	}
endif;

if ( ! function_exists( 'blank_base_author_box' ) ) :
	/**
	 * Output an author box after single-post content.
	 */
	function blank_base_author_box() {
		if ( ! is_singular( 'post' ) || ! get_theme_mod( 'blank_base_author_box', true ) ) {
			return;
		}

		$author_id   = get_the_author_meta( 'ID' );
		$description = get_the_author_meta( 'description' );
		?>
		<section class="author-box">
			<div class="author-box__avatar"><?php echo get_avatar( $author_id, 72 ); ?></div>
			<div class="author-box__content">
				<h2 class="author-box__name">
					<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a>
				</h2>
				<?php if ( $description ) : ?>
					<p class="author-box__bio"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
				<a class="author-box__more" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
					<?php esc_html_e( 'View all posts', 'blank-base' ); ?>
				</a>
			</div>
		</section>
		<?php
	}
endif;

if ( ! function_exists( 'blank_base_related_posts' ) ) :
	/**
	 * Output up to three related posts based on shared categories.
	 */
	function blank_base_related_posts() {
		if ( ! is_singular( 'post' ) || ! get_theme_mod( 'blank_base_related_posts', true ) ) {
			return;
		}

		$categories = wp_get_post_categories( get_the_ID() );
		if ( empty( $categories ) ) {
			return;
		}

		$related = new WP_Query(
			array(
				'category__in'        => $categories,
				'post__not_in'        => array( get_the_ID() ),
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => 1,
				'no_found_rows'       => true,
			)
		);

		if ( ! $related->have_posts() ) {
			wp_reset_postdata();
			return;
		}
		?>
		<section class="related-posts">
			<h2 class="related-posts__title"><?php esc_html_e( 'Related posts', 'blank-base' ); ?></h2>
			<div class="related-posts__grid">
				<?php
				while ( $related->have_posts() ) :
					$related->the_post();
					?>
					<article class="related-post">
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="related-post__thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						<?php endif; ?>
						<h3 class="related-post__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<span class="related-post__date"><?php echo esc_html( get_the_date() ); ?></span>
					</article>
					<?php
				endwhile;
				?>
			</div>
		</section>
		<?php
		wp_reset_postdata();
	}
endif;
