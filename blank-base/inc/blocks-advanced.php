<?php
/**
 * Advanced blocks: enqueue + dynamic (server-rendered) Post Carousel / Slider,
 * plus striped/bordered style variations for the core Table block.
 *
 * The editor definitions live in assets/js/blocks-advanced.js and the front-end
 * behaviour in assets/js/blocks-interactive.js.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the advanced-blocks editor script.
 */
function blank_base_advanced_editor_assets() {
	$uri     = get_template_directory_uri();
	$version = wp_get_theme( get_template() )->get( 'Version' );

	wp_enqueue_script(
		'blank-base-blocks-advanced',
		$uri . '/assets/js/blocks-advanced.js',
		array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-i18n', 'wp-server-side-render' ),
		$version,
		true
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'blank-base-blocks-advanced', 'blank-base' );
	}
}
add_action( 'enqueue_block_editor_assets', 'blank_base_advanced_editor_assets' );

/**
 * Register the shared advanced-blocks stylesheet and interactive script, and
 * enqueue them on the front end and in the editor.
 */
function blank_base_advanced_register_assets() {
	$uri     = get_template_directory_uri();
	$version = wp_get_theme( get_template() )->get( 'Version' );

	wp_register_style( 'blank-base-blocks-advanced', $uri . '/assets/css/blocks-advanced.css', array(), $version );
	wp_register_script( 'blank-base-blocks-interactive', $uri . '/assets/js/blocks-interactive.js', array(), $version, true );
}
add_action( 'init', 'blank_base_advanced_register_assets' );

/**
 * Front-end enqueue.
 */
function blank_base_advanced_front_assets() {
	wp_enqueue_style( 'blank-base-blocks-advanced' );
	wp_enqueue_script( 'blank-base-blocks-interactive' );
}
add_action( 'wp_enqueue_scripts', 'blank_base_advanced_front_assets' );

/**
 * Editor also needs the block CSS so previews match the front end.
 */
function blank_base_advanced_editor_style() {
	wp_enqueue_style( 'blank-base-blocks-advanced' );
}
add_action( 'enqueue_block_assets', 'blank_base_advanced_editor_style' );

/**
 * Register the two dynamic post blocks with their render callbacks.
 */
function blank_base_register_post_blocks() {
	$attributes = array(
		'postsToShow' => array( 'type' => 'number', 'default' => 6 ),
		'columns'     => array( 'type' => 'number', 'default' => 3 ),
		'order'       => array( 'type' => 'string', 'default' => 'date' ),
		'category'    => array( 'type' => 'number', 'default' => 0 ),
		'showImage'   => array( 'type' => 'boolean', 'default' => true ),
		'showExcerpt' => array( 'type' => 'boolean', 'default' => true ),
		'autoplay'    => array( 'type' => 'number', 'default' => 0 ),
		'arrows'      => array( 'type' => 'boolean', 'default' => true ),
		'dots'        => array( 'type' => 'boolean', 'default' => true ),
		// Also declared so the block editor's ServerSideRender preview (which may
		// include the shared align / Design & Motion attributes) is not rejected
		// by the REST block-renderer with "Invalid parameter(s): attributes".
		'align'       => array( 'type' => 'string', 'default' => '' ),
		'className'   => array( 'type' => 'string', 'default' => '' ),
		'bbAlign'     => array( 'type' => 'string', 'default' => '' ),
		'bbAnim'      => array( 'type' => 'string', 'default' => '' ),
		'bbAnimDelay' => array( 'type' => 'number', 'default' => 0 ),
		'bbHover'     => array( 'type' => 'string', 'default' => '' ),
	);

	// Post Carousel (set "Slides per view" to 1 for a full-width slider).
	register_block_type(
		'blank-base/post-carousel',
		array(
			'attributes'      => $attributes,
			'render_callback' => 'blank_base_render_post_block',
		)
	);

	// Legacy: Post Slider was merged into Post Carousel. Keep its server render
	// so any already-placed Post Slider blocks still display, but hide it from
	// the block inserter.
	$slider_attrs                       = $attributes;
	$slider_attrs['columns']['default'] = 1;
	register_block_type(
		'blank-base/post-slider',
		array(
			'attributes'      => $slider_attrs,
			'render_callback' => 'blank_base_render_post_block',
			'supports'        => array( 'inserter' => false ),
		)
	);
}
add_action( 'init', 'blank_base_register_post_blocks' );

/**
 * Render callback for the Post Carousel / Post Slider blocks.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function blank_base_render_post_block( $attributes ) {
	$a = wp_parse_args(
		$attributes,
		array(
			'postsToShow' => 6,
			'columns'     => 3,
			'order'       => 'date',
			'category'    => 0,
			'showImage'   => true,
			'showExcerpt' => true,
			'autoplay'    => 0,
			'arrows'      => true,
			'dots'        => true,
		)
	);

	$orderby = in_array( $a['order'], array( 'date', 'title', 'rand' ), true ) ? $a['order'] : 'date';

	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => max( 1, min( 20, absint( $a['postsToShow'] ) ) ),
		'orderby'             => $orderby,
		'order'               => ( 'title' === $orderby ) ? 'ASC' : 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	if ( absint( $a['category'] ) > 0 ) {
		$args['cat'] = absint( $a['category'] );
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		return '';
	}

	$data = sprintf(
		' data-per-view="%d" data-autoplay="%d" data-arrows="%s" data-dots="%s"',
		max( 1, absint( $a['columns'] ) ),
		absint( $a['autoplay'] ),
		$a['arrows'] ? '1' : '0',
		$a['dots'] ? '1' : '0'
	);

	ob_start();
	echo '<div class="bb-carousel bb-post-carousel"' . $data . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<div class="bb-carousel__track">';

	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		<article class="bb-carousel__slide bb-post-card">
			<?php if ( $a['showImage'] && has_post_thumbnail() ) : ?>
				<a class="bb-post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
					<?php the_post_thumbnail( 'medium_large' ); ?>
				</a>
			<?php endif; ?>
			<div class="bb-post-card__body">
				<h3 class="bb-post-card__title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
				<div class="bb-post-card__meta"><?php echo esc_html( get_the_date() ); ?></div>
				<?php if ( $a['showExcerpt'] ) : ?>
					<p class="bb-post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
				<?php endif; ?>
				<a class="bb-post-card__more" href="<?php the_permalink(); ?>">
					<?php esc_html_e( 'Read more', 'blank-base' ); ?>
				</a>
			</div>
		</article>
		<?php
	}

	echo '</div><!-- .bb-carousel__track --></div><!-- .bb-carousel -->';
	wp_reset_postdata();

	return ob_get_clean();
}

/**
 * Register striped / bordered style variations for the core Table block.
 */
function blank_base_register_table_styles() {
	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	register_block_style(
		'core/table',
		array(
			'name'  => 'bb-striped',
			'label' => __( 'Striped (Blank Base)', 'blank-base' ),
		)
	);
	register_block_style(
		'core/table',
		array(
			'name'  => 'bb-bordered',
			'label' => __( 'Bordered (Blank Base)', 'blank-base' ),
		)
	);
}
add_action( 'init', 'blank_base_register_table_styles' );
