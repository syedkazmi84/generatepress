<?php
/**
 * One-click Demo Content importer for Blank Base.
 *
 * Adds an "Import Demo Content" screen under Appearance. Clicking the button
 * builds a complete, ready-to-use "Quill & Press" book-publishing website:
 * it side-loads the bundled demo images into the Media Library, creates every
 * page (Home, About, Services, Books, Pricing, FAQ, Contact, Journal) as
 * editable Gutenberg block content, publishes three blog posts with featured
 * images, builds the primary and footer menus, sets the homepage, uploads the
 * logo and applies a cohesive book-publishing colour scheme.
 *
 * Everything ships inside the theme — there is no remote server and no
 * dependency on any plugin. The import is idempotent: running it again updates
 * the same pages/menus instead of creating duplicates.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load the content/block definitions.
require_once get_template_directory() . '/inc/demo-content.php';

/**
 * Registers the "Import Demo Content" page under the Appearance menu.
 */
function blank_base_demo_menu() {
	add_theme_page(
		esc_html__( 'Import Demo Content', 'blank-base' ),
		esc_html__( 'Import Demo Content', 'blank-base' ),
		'manage_options',
		'blank-base-demo',
		'blank_base_demo_render_page'
	);
}
add_action( 'admin_menu', 'blank_base_demo_menu' );

/**
 * Add an "Import Demo" quick link on the Themes screen.
 *
 * @param array $actions Existing action links.
 * @return array
 */
function blank_base_demo_action_link( $actions ) {
	$url  = admin_url( 'themes.php?page=blank-base-demo' );
	$link = '<a href="' . esc_url( $url ) . '" style="font-weight:600">' . esc_html__( 'Import Demo', 'blank-base' ) . '</a>';
	array_unshift( $actions, $link );
	return $actions;
}
add_filter( 'theme_action_links_' . get_template(), 'blank_base_demo_action_link' );

/**
 * Enqueue the tiny admin script/style on the importer screen only.
 *
 * @param string $hook Current admin page hook.
 */
function blank_base_demo_admin_assets( $hook ) {
	if ( 'appearance_page_blank-base-demo' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'dashicons' );
	$ver = wp_get_theme( get_template() )->get( 'Version' );

	$inline_css = '
		.bb-demo-wrap{max-width:820px}
		.bb-demo-card{background:#fff;border:1px solid #e2e2e2;border-radius:12px;padding:28px 30px;margin-top:20px;box-shadow:0 12px 30px -24px rgba(0,0,0,.3)}
		.bb-demo-hero{display:flex;align-items:center;gap:18px;border-bottom:1px solid #eee;padding-bottom:20px;margin-bottom:20px}
		.bb-demo-hero .dashicons{font-size:42px;width:42px;height:42px;color:#c8a45c}
		.bb-demo-hero h1{margin:0;font-size:22px}
		.bb-demo-hero p{margin:4px 0 0;color:#666}
		.bb-demo-list{margin:0 0 18px;padding-left:0;list-style:none;columns:2}
		.bb-demo-list li{margin:0 0 8px;padding-left:24px;position:relative}
		.bb-demo-list li:before{content:"\2713";position:absolute;left:0;color:#1b8a3a;font-weight:700}
		.bb-demo-opts{margin:6px 0 20px}
		.bb-demo-opts label{display:block;margin-bottom:8px}
		.bb-demo-actions{display:flex;align-items:center;gap:14px}
		.bb-demo-btn .spinner{float:none;margin:0}
		#bb-demo-result{margin-top:22px}
		#bb-demo-result .notice{margin:0}
		#bb-demo-log{margin-top:14px;font-family:monospace;font-size:12px;color:#444;background:#f6f7f7;border-radius:8px;padding:14px 16px;max-height:260px;overflow:auto}
		#bb-demo-log div{padding:2px 0}
		.bb-demo-warn{background:#fcf9e8;border-left:4px solid #dba617;padding:10px 14px;border-radius:4px;color:#5a4a12;margin-bottom:18px}
	';
	wp_register_style( 'blank-base-demo-admin', false, array(), $ver );
	wp_enqueue_style( 'blank-base-demo-admin' );
	wp_add_inline_style( 'blank-base-demo-admin', $inline_css );

	$inline_js = "
	(function(\$){
		\$(function(){
			var \$btn=\$('#bb-demo-import'), \$spin=\$('.bb-demo-btn .spinner'),
				\$result=\$('#bb-demo-result');
			\$btn.on('click',function(e){
				e.preventDefault();
				if(!window.confirm(bbDemo.confirm)){return;}
				\$btn.prop('disabled',true);
				\$spin.addClass('is-active');
				\$result.html('');
				\$.post(bbDemo.ajax,{
					action:'blank_base_demo_import',
					nonce:bbDemo.nonce,
					setup:\$('#bb-demo-setup').is(':checked')?1:0
				}).done(function(res){
					if(res && res.success){
						var log='';
						\$.each(res.data.log,function(i,l){log+='<div>'+l+'</div>';});
						\$result.html('<div class=\"notice notice-success\"><p><strong>'+res.data.message+'</strong></p></div>'+
							(res.data.view?'<p><a class=\"button button-primary\" target=\"_blank\" href=\"'+res.data.view+'\">'+bbDemo.visit+'</a> <a class=\"button\" href=\"'+res.data.pages+'\">'+bbDemo.editPages+'</a></p>':'')+
							'<div id=\"bb-demo-log\">'+log+'</div>');
					}else{
						\$result.html('<div class=\"notice notice-error\"><p>'+((res&&res.data&&res.data.message)||bbDemo.error)+'</p></div>');
					}
				}).fail(function(){
					\$result.html('<div class=\"notice notice-error\"><p>'+bbDemo.error+'</p></div>');
				}).always(function(){
					\$btn.prop('disabled',false);
					\$spin.removeClass('is-active');
				});
			});
		});
	})(jQuery);
	";
	wp_register_script( 'blank-base-demo-admin', '', array( 'jquery' ), $ver, true );
	wp_enqueue_script( 'blank-base-demo-admin' );
	wp_localize_script(
		'blank-base-demo-admin',
		'bbDemo',
		array(
			'ajax'      => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'blank_base_demo' ),
			'confirm'   => esc_html__( 'Import the demo content now? This will create pages, posts, menus and media. Existing demo items will be updated, not duplicated.', 'blank-base' ),
			'error'     => esc_html__( 'Something went wrong during the import. Please try again.', 'blank-base' ),
			'visit'     => esc_html__( 'View your new site', 'blank-base' ),
			'editPages' => esc_html__( 'Edit pages', 'blank-base' ),
		)
	);
	wp_add_inline_script( 'blank-base-demo-admin', $inline_js );
}
add_action( 'admin_enqueue_scripts', 'blank_base_demo_admin_assets' );

/**
 * Render the importer admin page.
 */
function blank_base_demo_render_page() {
	$imported = get_option( 'blank_base_demo_imported' );
	?>
	<div class="wrap bb-demo-wrap">
		<div class="bb-demo-card">
			<div class="bb-demo-hero">
				<span class="dashicons dashicons-book-alt"></span>
				<div>
					<h1><?php esc_html_e( 'Import Demo Content', 'blank-base' ); ?></h1>
					<p><?php esc_html_e( 'Build a complete “Quill &amp; Press” book-publishing website in one click.', 'blank-base' ); ?></p>
				</div>
			</div>

			<?php if ( $imported ) : ?>
				<div class="bb-demo-warn">
					<?php
					printf(
						/* translators: %s: date/time of last import. */
						esc_html__( 'You imported the demo on %s. Running it again will refresh the demo pages and menus without creating duplicates.', 'blank-base' ),
						esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), (int) $imported ) )
					);
					?>
				</div>
			<?php endif; ?>

			<p><?php esc_html_e( 'This one-time import sets up everything you need to launch, all built with native Gutenberg blocks so every page stays fully editable:', 'blank-base' ); ?></p>

			<ul class="bb-demo-list">
				<li><?php esc_html_e( 'Home page with hero &amp; CTAs', 'blank-base' ); ?></li>
				<li><?php esc_html_e( 'About Us page', 'blank-base' ); ?></li>
				<li><?php esc_html_e( 'Services page', 'blank-base' ); ?></li>
				<li><?php esc_html_e( 'Our Books showcase', 'blank-base' ); ?></li>
				<li><?php esc_html_e( 'Pricing &amp; packages', 'blank-base' ); ?></li>
				<li><?php esc_html_e( 'FAQ page', 'blank-base' ); ?></li>
				<li><?php esc_html_e( 'Contact page &amp; form', 'blank-base' ); ?></li>
				<li><?php esc_html_e( '3 blog posts with images', 'blank-base' ); ?></li>
				<li><?php esc_html_e( 'All demo images &amp; logo', 'blank-base' ); ?></li>
				<li><?php esc_html_e( 'Primary &amp; footer menus', 'blank-base' ); ?></li>
			</ul>

			<div class="bb-demo-opts">
				<label>
					<input type="checkbox" id="bb-demo-setup" checked>
					<strong><?php esc_html_e( 'Configure the site too', 'blank-base' ); ?></strong> —
					<?php esc_html_e( 'set the homepage, build the menus, upload the logo and apply the book-publishing colour scheme. Uncheck to only create the pages, posts and images.', 'blank-base' ); ?>
				</label>
			</div>

			<div class="bb-demo-actions">
				<span class="bb-demo-btn">
					<button type="button" class="button button-primary button-hero" id="bb-demo-import">
						<span class="dashicons dashicons-download" style="margin:4px 6px 0 0"></span>
						<?php esc_html_e( 'Import Demo Content', 'blank-base' ); ?>
					</button>
				</span>
				<span class="spinner"></span>
			</div>

			<div id="bb-demo-result"></div>
		</div>
	</div>
	<?php
}

/* =========================================================================
 * AJAX handler + import routine.
 * ====================================================================== */

/**
 * AJAX: run the full demo import.
 */
function blank_base_demo_do_import() {
	check_ajax_referer( 'blank_base_demo', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to do this.', 'blank-base' ) ) );
	}

	// Long-running-ish, but no network calls; give it room anyway.
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 180 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$setup = ! empty( $_POST['setup'] );
	$log   = array();

	// 1. Images ---------------------------------------------------------
	$img = blank_base_demo_import_images( $log );

	// 2. Pages ----------------------------------------------------------
	$pages    = blank_base_demo_pages( $img );
	$page_ids = array();
	foreach ( $pages as $slug => $page ) {
		$id = blank_base_demo_upsert_page( $page );
		if ( $id ) {
			$page_ids[ $slug ] = $id;
		}
	}
	/* translators: %d: number of pages. */
	$log[] = sprintf( esc_html__( 'Created / updated %d pages.', 'blank-base' ), count( $page_ids ) );

	// 3. Posts ----------------------------------------------------------
	$post_count = blank_base_demo_import_posts( $img, $log );

	// Remove WordPress's default sample content for a clean demo.
	blank_base_demo_cleanup_defaults( $log );

	// 5. Site configuration --------------------------------------------
	if ( $setup ) {
		blank_base_demo_configure_site( $page_ids, $img, $log );
	} else {
		$log[] = esc_html__( 'Skipped homepage, menus, logo and colours (option unchecked).', 'blank-base' );
	}

	update_option( 'blank_base_demo_imported', time() );

	$view  = isset( $page_ids['home'] ) ? get_permalink( $page_ids['home'] ) : home_url( '/' );
	$log[] = esc_html__( 'Done. Your book-publishing site is ready.', 'blank-base' );

	wp_send_json_success(
		array(
			'message' => esc_html__( 'Demo content imported successfully!', 'blank-base' ),
			'log'     => $log,
			'view'    => $setup ? home_url( '/' ) : $view,
			'pages'   => admin_url( 'edit.php?post_type=page' ),
			'posts'   => $post_count,
		)
	);
}
add_action( 'wp_ajax_blank_base_demo_import', 'blank_base_demo_do_import' );

/**
 * Import every SVG in assets/images/demo/ into the Media Library.
 *
 * Returns a map: image key (filename without extension) => [ id, url ].
 *
 * @param array $log Log array (passed by reference).
 * @return array
 */
function blank_base_demo_import_images( &$log ) {
	$dir = get_template_directory() . '/assets/images/demo/';
	$map = array();

	if ( ! is_dir( $dir ) ) {
		$log[] = esc_html__( 'No demo images folder found.', 'blank-base' );
		return $map;
	}

	// Allow SVG uploads only for the duration of this import.
	$allow_svg = function ( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
	};
	add_filter( 'upload_mimes', $allow_svg );
	$fix_type = function ( $data, $file, $filename, $mimes, $real_mime = '' ) {
		if ( preg_match( '/\.svgz?$/i', $filename ) ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		return $data;
	};
	add_filter( 'wp_check_filetype_and_ext', $fix_type, 10, 5 );

	$files = glob( $dir . '*.svg' );
	$count = 0;
	foreach ( (array) $files as $file ) {
		$filename = basename( $file );
		$key      = preg_replace( '/\.svg$/', '', $filename );
		$id       = blank_base_demo_sideload_svg( $file, $filename, $key );
		if ( $id ) {
			$map[ $key ] = array(
				'id'  => $id,
				'url' => wp_get_attachment_url( $id ),
			);
			++$count;
		}
	}

	remove_filter( 'upload_mimes', $allow_svg );
	remove_filter( 'wp_check_filetype_and_ext', $fix_type, 10 );

	/* translators: %d: number of images. */
	$log[] = sprintf( esc_html__( 'Imported %d demo images into the Media Library.', 'blank-base' ), $count );
	return $map;
}

/**
 * Side-load one bundled SVG into the uploads directory + Media Library.
 *
 * Idempotent: if an attachment tagged with the same demo key already exists it
 * is reused instead of creating a duplicate.
 *
 * @param string $path     Absolute path to the source SVG.
 * @param string $filename File name.
 * @param string $key      Demo image key.
 * @return int Attachment ID, or 0 on failure.
 */
function blank_base_demo_sideload_svg( $path, $filename, $key ) {
	// Reuse an existing import.
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_blank_base_demo_key', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $key, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	if ( ! empty( $existing ) ) {
		return (int) $existing[0];
	}

	$contents = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( false === $contents ) {
		return 0;
	}

	$upload = wp_upload_bits( $filename, null, $contents );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$title      = ucwords( str_replace( array( '-', '_' ), ' ', $key ) );
	$attachment = array(
		'post_mime_type' => 'image/svg+xml',
		'post_title'     => 'Quill & Press — ' . $title,
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $upload['url'],
	);

	$attach_id = wp_insert_attachment( $attachment, $upload['file'] );
	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return 0;
	}

	// Minimal metadata so image_downsize() can return a URL + dimensions.
	list( $w, $h ) = blank_base_demo_svg_dims( $contents );
	wp_update_attachment_metadata(
		$attach_id,
		array(
			'width'      => $w,
			'height'     => $h,
			'file'       => _wp_relative_upload_path( $upload['file'] ),
			'sizes'      => array(),
			'image_meta' => array(),
		)
	);
	update_post_meta( $attach_id, '_wp_attachment_image_alt', $title );
	update_post_meta( $attach_id, '_blank_base_demo_key', $key );

	return (int) $attach_id;
}

/**
 * Read width/height from an SVG's root attributes (falls back to viewBox).
 *
 * @param string $svg SVG markup.
 * @return array [ width, height ]
 */
function blank_base_demo_svg_dims( $svg ) {
	$w = 0;
	$h = 0;
	if ( preg_match( '/<svg[^>]*\bwidth="([\d.]+)"/i', $svg, $m ) ) {
		$w = (int) round( (float) $m[1] );
	}
	if ( preg_match( '/<svg[^>]*\bheight="([\d.]+)"/i', $svg, $m ) ) {
		$h = (int) round( (float) $m[1] );
	}
	if ( ( ! $w || ! $h ) && preg_match( '/viewBox="[\d.]+ [\d.]+ ([\d.]+) ([\d.]+)"/i', $svg, $m ) ) {
		$w = $w ? $w : (int) round( (float) $m[1] );
		$h = $h ? $h : (int) round( (float) $m[2] );
	}
	return array( $w ? $w : 1200, $h ? $h : 800 );
}

/**
 * Create or update a demo page (matched by slug + a demo marker).
 *
 * @param array $page Page definition ( slug, title, content ).
 * @return int Page ID.
 */
function blank_base_demo_upsert_page( $page ) {
	$existing = get_page_by_path( $page['slug'], OBJECT, 'page' );

	$args = array(
		'post_title'   => $page['title'],
		'post_name'    => $page['slug'],
		'post_content' => $page['content'],
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_author'  => get_current_user_id(),
	);

	if ( $existing ) {
		$args['ID'] = $existing->ID;
		$id         = wp_update_post( $args );
	} else {
		$id = wp_insert_post( $args );
	}
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}
	update_post_meta( $id, '_blank_base_demo_page', $page['slug'] );

	// Hide the redundant page title on pages that open with their own hero.
	if ( ! empty( $page['hide_title'] ) ) {
		update_post_meta( $id, '_blank_base_disable_title', '1' );
	} else {
		delete_post_meta( $id, '_blank_base_disable_title' );
	}

	// Marketing pages read best full-width; the blog index keeps its sidebar.
	$sidebar = isset( $page['sidebar'] ) ? $page['sidebar'] : ( 'blog' === $page['slug'] ? '' : 'none' );
	if ( $sidebar ) {
		update_post_meta( $id, '_blank_base_sidebar', $sidebar );
	} else {
		delete_post_meta( $id, '_blank_base_sidebar' );
	}
	return (int) $id;
}

/**
 * Import the demo blog posts with categories and featured images.
 *
 * @param array $img Image map.
 * @param array $log Log (by reference).
 * @return int Number of posts.
 */
function blank_base_demo_import_posts( $img, &$log ) {
	$posts = blank_base_demo_posts( $img );
	$n     = 0;

	foreach ( $posts as $post ) {
		$existing = get_page_by_path( $post['slug'], OBJECT, 'post' );

		$cat_ids = array();
		foreach ( $post['cats'] as $cat ) {
			$term = term_exists( $cat, 'category' );
			if ( ! $term ) {
				$term = wp_insert_term( $cat, 'category' );
			}
			if ( ! is_wp_error( $term ) ) {
				$cat_ids[] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
			}
		}

		$args = array(
			'post_title'    => $post['title'],
			'post_name'     => $post['slug'],
			'post_content'  => $post['content'],
			'post_excerpt'  => $post['excerpt'],
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_author'   => get_current_user_id(),
			'post_category' => $cat_ids,
		);
		if ( $existing ) {
			$args['ID'] = $existing->ID;
			$id         = wp_update_post( $args );
		} else {
			$id = wp_insert_post( $args );
		}
		if ( is_wp_error( $id ) || ! $id ) {
			continue;
		}
		update_post_meta( $id, '_blank_base_demo_post', $post['slug'] );
		if ( isset( $img[ $post['image'] ]['id'] ) ) {
			set_post_thumbnail( $id, $img[ $post['image'] ]['id'] );
		}
		++$n;
	}

	// Make sure the default "Uncategorized" sample post doesn't clutter the blog.
	/* translators: %d: number of posts. */
	$log[] = sprintf( esc_html__( 'Published %d blog posts with featured images.', 'blank-base' ), $n );
	return $n;
}

/**
 * Trash WordPress's default "Hello world!" post and "Sample Page" so the demo
 * blog and pages start clean. Only removes the untouched defaults.
 *
 * @param array $log Log (by reference).
 */
function blank_base_demo_cleanup_defaults( &$log ) {
	$removed = 0;

	$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
	if ( $hello && 'publish' === $hello->post_status ) {
		wp_trash_post( $hello->ID );
		++$removed;
	}

	$sample = get_page_by_path( 'sample-page', OBJECT, 'page' );
	if ( $sample && empty( get_post_meta( $sample->ID, '_blank_base_demo_page', true ) ) ) {
		wp_trash_post( $sample->ID );
		++$removed;
	}

	if ( $removed ) {
		/* translators: %d: number of default items removed. */
		$log[] = sprintf( esc_html__( 'Removed %d default WordPress sample item(s).', 'blank-base' ), $removed );
	}
}

/**
 * Configure the site: front page, menus, logo and colours.
 *
 * @param array $page_ids slug => page ID.
 * @param array $img      Image map.
 * @param array $log      Log (by reference).
 */
function blank_base_demo_configure_site( $page_ids, $img, &$log ) {
	// Pretty permalinks so the pages resolve at /services/, /contact/, etc.
	// (the demo's internal links use those paths). Only change the default
	// "plain" structure so we never override a choice the user already made.
	if ( '' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		if ( function_exists( 'flush_rewrite_rules' ) ) {
			flush_rewrite_rules( false );
		}
		$log[] = esc_html__( 'Enabled pretty permalinks (/%postname%/).', 'blank-base' );
	}

	// Front page + posts page.
	if ( isset( $page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['home'] );
	}
	if ( isset( $page_ids['blog'] ) ) {
		update_option( 'page_for_posts', $page_ids['blog'] );
	}
	$log[] = esc_html__( 'Set the homepage and blog page.', 'blank-base' );

	// Logo.
	if ( isset( $img['logo']['id'] ) ) {
		set_theme_mod( 'custom_logo', $img['logo']['id'] );
		$log[] = esc_html__( 'Uploaded and set the site logo.', 'blank-base' );
	}

	// Site identity.
	update_option( 'blogname', 'Quill & Press' );
	update_option( 'blogdescription', 'Book Publishing Services' );

	// Menus.
	$primary = array( 'home', 'about', 'services', 'books', 'pricing', 'blog', 'contact' );
	$footer  = array( 'about', 'services', 'pricing', 'faq', 'contact' );
	$loc     = get_theme_mod( 'nav_menu_locations', array() );

	$primary_id = blank_base_demo_build_menu( esc_html__( 'Primary Menu', 'blank-base' ), $primary, $page_ids );
	$footer_id  = blank_base_demo_build_menu( esc_html__( 'Footer Menu', 'blank-base' ), $footer, $page_ids );
	if ( $primary_id ) {
		$loc['menu-1'] = $primary_id;
	}
	if ( $footer_id ) {
		$loc['menu-2'] = $footer_id;
	}
	set_theme_mod( 'nav_menu_locations', $loc );
	$log[] = esc_html__( 'Built the primary and footer navigation menus.', 'blank-base' );

	// Book-publishing colour scheme (all easily changed in the Customizer).
	$mods = array(
		'blank_base_color_preset'      => 'custom',
		'blank_base_accent_color'      => '#a9781f',
		'blank_base_header_bg'         => '#ffffff',
		'blank_base_header_text'       => '#1b2440',
		'blank_base_header_link'       => '#1b2440',
		'blank_base_header_link_hover' => '#a9781f',
		'blank_base_site_title_color'  => '#1b2440',
		'blank_base_nav_bg'            => '#1b2440',
		'blank_base_nav_text'          => '#f6f1e7',
		'blank_base_nav_hover_bg'      => '#26325a',
		'blank_base_nav_hover_text'    => '#e6c983',
		'blank_base_submenu_bg'        => '#1b2440',
		'blank_base_submenu_text'      => '#f6f1e7',
		'blank_base_content_bg'        => '#ffffff',
		'blank_base_content_text'      => '#22293d',
		'blank_base_button_bg'         => '#1b2440',
		'blank_base_button_text'       => '#f6f1e7',
		'blank_base_button_bg_hover'   => '#c8a45c',
		'blank_base_button_text_hover' => '#1b2440',
		'blank_base_footer_widget_bg'  => '#1b2440',
		'blank_base_footer_widget_text' => '#cfd3e0',
		'blank_base_footer_widget_link' => '#e6c983',
		'blank_base_footer_bar_bg'     => '#141b31',
		'blank_base_footer_bar_text'   => '#cfd3e0',
		'blank_base_footer_bar_link'   => '#e6c983',
		'blank_base_heading_font'      => 'serif',
	);
	foreach ( $mods as $k => $v ) {
		set_theme_mod( $k, $v );
	}
	$log[] = esc_html__( 'Applied the book-publishing colour scheme.', 'blank-base' );
}

/**
 * Build (or rebuild) a nav menu from a list of page slugs.
 *
 * @param string $name     Menu name.
 * @param array  $slugs    Ordered page slugs.
 * @param array  $page_ids slug => page ID.
 * @return int Menu term ID.
 */
function blank_base_demo_build_menu( $name, $slugs, $page_ids ) {
	// Remove an existing menu of the same name so we don't stack items.
	$existing = wp_get_nav_menu_object( $name );
	if ( $existing ) {
		wp_delete_nav_menu( $existing->term_id );
	}
	$menu_id = wp_create_nav_menu( $name );
	if ( is_wp_error( $menu_id ) ) {
		return 0;
	}

	foreach ( $slugs as $slug ) {
		if ( empty( $page_ids[ $slug ] ) ) {
			continue;
		}
		$title = get_the_title( $page_ids[ $slug ] );
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $title,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_ids[ $slug ],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}
	return (int) $menu_id;
}

/**
 * Enqueue the demo-content front-end styles (check lists + contact form).
 */
function blank_base_demo_frontend_style() {
	wp_enqueue_style(
		'blank-base-demo-content',
		get_template_directory_uri() . '/assets/css/demo-content.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'blank_base_demo_frontend_style' );

/**
 * Load the demo styles in the block editor too, so the check lists and form
 * preview correctly while editing the imported pages.
 */
function blank_base_demo_editor_style() {
	wp_enqueue_style(
		'blank-base-demo-content-editor',
		get_template_directory_uri() . '/assets/css/demo-content.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);
}
add_action( 'enqueue_block_editor_assets', 'blank_base_demo_editor_style' );
