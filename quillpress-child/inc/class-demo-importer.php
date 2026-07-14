<?php
/**
 * One-click demo importer for Quill & Press.
 *
 * Adds "Appearance → Import Demo" with a single "Import All" button that builds
 * the entire book-publishing website: pages, blog posts, menus, front page and
 * theme settings. Safe to run more than once — existing demo items are updated
 * in place rather than duplicated.
 *
 * @package Quill_Press
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Quill_Press_Demo_Importer {

	const META_KEY   = '_quillpress_demo';
	const IMG_META   = '_quillpress_demo_img';
	const DONE_OPT   = 'quillpress_demo_imported';
	const MENU_SLUG  = 'quillpress-import';

	protected static $instance = null;
	protected $slug_to_id = array();

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function __construct() {
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_post_quillpress_import', array( $this, 'handle_import' ) );
		add_action( 'admin_notices', array( $this, 'activation_notice' ) );
	}

	/* --------------------------------------------------------------- UI */

	public function register_page() {
		add_theme_page(
			__( 'Import Demo', 'quillpress' ),
			__( 'Import Demo', 'quillpress' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'render_page' )
		);
	}

	public function activation_notice() {
		if ( get_option( self::DONE_OPT ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_' . self::MENU_SLUG === $screen->id ) {
			return;
		}
		$url = admin_url( 'themes.php?page=' . self::MENU_SLUG );
		echo '<div class="notice notice-info is-dismissible"><p><strong>Quill &amp; Press</strong> is ready. '
			. '<a href="' . esc_url( $url ) . '">Import the demo website</a> to build every page with one click.</p></div>';
	}

	public function render_page() {
		$done    = get_option( self::DONE_OPT );
		$status  = isset( $_GET['imported'] ) ? sanitize_text_field( wp_unslash( $_GET['imported'] ) ) : '';
		$counts  = get_transient( 'quillpress_import_log' );
		$pages   = quillpress_demo_pages();
		$img_url = QUILLPRESS_URI . '/assets/images/';
		?>
		<div class="wrap quillpress-importer">
			<style>
				.quillpress-importer{max-width:920px}
				.qpi-hero{background:linear-gradient(160deg,#16203a,#1e2c4f);color:#fff;border-radius:16px;padding:34px 36px;margin:20px 0;position:relative;overflow:hidden}
				.qpi-hero:after{content:"";position:absolute;right:-40px;top:-40px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,#c8a24c55,transparent 70%)}
				.qpi-hero h1{color:#fff;font-size:26px;margin:0 0 6px}
				.qpi-hero p{color:#d6dcea;max-width:560px;font-size:15px;margin:0}
				.qpi-card{background:#fff;border:1px solid #e2e4e9;border-radius:14px;padding:26px 30px;margin:18px 0;box-shadow:0 10px 30px -22px rgba(20,30,55,.5)}
				.qpi-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:12px;margin:16px 0}
				.qpi-item{display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f7f4ec;border:1px solid #ece3d0;border-radius:10px;font-size:13px;font-weight:600;color:#26303f}
				.qpi-item .dashicons{color:#b08a35}
				.qpi-btn{background:linear-gradient(180deg,#c8a24c,#b08a35);border:0;color:#16203a;font-weight:700;font-size:15px;padding:14px 30px;border-radius:999px;cursor:pointer;box-shadow:0 14px 26px -14px rgba(176,138,53,.9)}
				.qpi-btn:hover{transform:translateY(-1px)}
				.qpi-btn--ghost{background:#fff;border:1px solid #16203a;color:#16203a;box-shadow:none}
				.qpi-actions{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-top:8px}
				.qpi-note{color:#61708a;font-size:13px;margin-top:14px}
				.qpi-success{background:#edf7ee;border:1px solid #bfe3c4;border-radius:12px;padding:18px 22px;margin:16px 0;color:#1e4620}
				.qpi-success a{font-weight:700}
				.qpi-thumbs{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
				.qpi-thumbs img{height:52px;width:52px;object-fit:contain;background:#f7f4ec;border:1px solid #ece3d0;border-radius:10px;padding:6px}
			</style>

			<div class="qpi-hero">
				<h1>Quill &amp; Press — One-Click Website Importer</h1>
				<p>Build the complete book-publishing-services website — every page, the blog, menus and settings — in a single click. Bundled artwork is included, so it&#8217;s ready the moment it finishes.</p>
			</div>

			<?php if ( 'success' === $status ) : ?>
				<div class="qpi-success">
					<p style="margin:0 0 8px"><strong>✓ Import complete!</strong> Your website has been built.
						<?php if ( is_array( $counts ) ) : ?>
							Created/updated <strong><?php echo (int) $counts['pages']; ?></strong> pages,
							<strong><?php echo (int) $counts['posts']; ?></strong> posts and
							<strong><?php echo (int) $counts['menus']; ?></strong> menus.
						<?php endif; ?>
					</p>
					<p style="margin:0">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">View your website →</a>
						&nbsp;·&nbsp;
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>">Edit the pages</a>
					</p>
				</div>
			<?php endif; ?>

			<div class="qpi-card">
				<h2 style="margin-top:0">What gets imported</h2>
				<div class="qpi-grid">
					<?php foreach ( $pages as $p ) : ?>
						<div class="qpi-item"><span class="dashicons dashicons-media-document"></span><?php echo wp_kses_post( $p['title'] ); ?></div>
					<?php endforeach; ?>
					<div class="qpi-item"><span class="dashicons dashicons-admin-comments"></span>3 Blog posts</div>
					<div class="qpi-item"><span class="dashicons dashicons-menu"></span>Header + footer menus</div>
					<div class="qpi-item"><span class="dashicons dashicons-admin-home"></span>Homepage &amp; settings</div>
				</div>

				<div class="qpi-thumbs">
					<?php foreach ( array( 'hero-home.svg', 'book-1.svg', 'book-2.svg', 'book-4.svg', 'icon-editing.svg', 'icon-design.svg', 'icon-marketing.svg', 'avatar-1.svg' ) as $t ) : ?>
						<img src="<?php echo esc_url( $img_url . $t ); ?>" alt="">
					<?php endforeach; ?>
				</div>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:22px">
					<input type="hidden" name="action" value="quillpress_import">
					<?php wp_nonce_field( 'quillpress_import_action', 'quillpress_import_nonce' ); ?>
					<div class="qpi-actions">
						<button type="submit" class="qpi-btn">
							<?php echo $done ? '↻ Re-import All Content' : '⬇ Import All Content'; ?>
						</button>
						<?php if ( $done ) : ?>
							<a class="qpi-btn qpi-btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" style="text-decoration:none;display:inline-block">View Website</a>
						<?php endif; ?>
					</div>
					<p class="qpi-note">Running this again refreshes the demo pages to their original content. Your other posts and pages are never touched. Requires the GeneratePress theme (active) and the GenerateBlocks plugin.</p>
				</form>
			</div>
		</div>
		<?php
	}

	/* ----------------------------------------------------------- Import */

	public function handle_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Insufficient permissions.' );
		}
		check_admin_referer( 'quillpress_import_action', 'quillpress_import_nonce' );

		$log = $this->run_import();

		set_transient( 'quillpress_import_log', $log, 300 );
		update_option( self::DONE_OPT, time() );

		wp_safe_redirect( admin_url( 'themes.php?page=' . self::MENU_SLUG . '&imported=success' ) );
		exit;
	}

	public function run_import() {
		$log = array(
			'pages' => 0,
			'posts' => 0,
			'menus' => 0,
		);

		// Pretty permalinks so /slug/ URLs used in the content work.
		if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
			update_option( 'permalink_structure', '/%postname%/' );
			flush_rewrite_rules( false );
		}

		// 1. Pages.
		$front_id = 0;
		$blog_id  = 0;
		foreach ( quillpress_demo_pages() as $def ) {
			$content = isset( $def['builder'] ) && function_exists( $def['builder'] )
				? call_user_func( $def['builder'] )
				: ( isset( $def['content'] ) ? $def['content'] : '' );

			$parent_id = 0;
			if ( isset( $def['parent'] ) && isset( $this->slug_to_id[ $def['parent'] ] ) ) {
				$parent_id = $this->slug_to_id[ $def['parent'] ];
			}

			$id = $this->upsert_page( $def['title'], $def['slug'], $content, $parent_id, empty( $def['builder'] ) && isset( $def['posts_page'] ) );
			if ( $id ) {
				$this->slug_to_id[ $def['slug'] ] = $id;
				$log['pages']++;
				if ( ! empty( $def['front'] ) ) {
					$front_id = $id;
				}
				if ( ! empty( $def['posts_page'] ) ) {
					$blog_id = $id;
				}
			}
		}

		// 2. Front page + posts page.
		if ( $front_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_id );
		}
		if ( $blog_id ) {
			update_option( 'page_for_posts', $blog_id );
		}

		// 3. Blog posts + categories + featured images.
		foreach ( quillpress_demo_posts() as $post_def ) {
			if ( $this->upsert_post( $post_def ) ) {
				$log['posts']++;
			}
		}

		// 4. Menus.
		$log['menus'] += $this->build_primary_menu();
		$log['menus'] += $this->build_footer_menu();

		// 5. Site identity + light theme settings.
		update_option( 'blogname', 'Quill & Press' );
		update_option( 'blogdescription', 'Full-service book publishing' );
		$this->apply_theme_settings();

		// Remove the default "Sample Page" and "Hello World" clutter.
		$this->trash_default_content();

		return $log;
	}

	/* -------------------------------------------------------- Utilities */

	protected function find_by_meta( $post_type, $slug ) {
		$found = get_posts(
			array(
				'post_type'   => $post_type,
				'post_status' => 'any',
				'numberposts' => 1,
				'meta_key'    => self::META_KEY,
				'meta_value'  => $slug,
				'fields'      => 'ids',
			)
		);
		return $found ? (int) $found[0] : 0;
	}

	protected function upsert_page( $title, $slug, $content, $parent_id = 0, $is_posts_page = false ) {
		$existing = $this->find_by_meta( 'page', $slug );
		$data     = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_parent'  => $parent_id,
			'comment_status' => 'closed',
		);
		if ( $existing ) {
			$data['ID'] = $existing;
			$id         = wp_update_post( wp_slash( $data ) );
		} else {
			$id = wp_insert_post( wp_slash( $data ) );
		}
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, self::META_KEY, $slug );
			// Landing pages: no sidebar, full-width content, hide the auto title.
			if ( ! $is_posts_page ) {
				update_post_meta( $id, '_generate-sidebar-layout-meta', 'no-sidebar' );
				update_post_meta( $id, '_generate-full-width-content', 'true' );
				update_post_meta( $id, '_qp_hide_title', 1 );
			}
			return (int) $id;
		}
		return 0;
	}

	protected function upsert_post( $def ) {
		$existing = $this->find_by_meta( 'post', $def['slug'] );
		$data     = array(
			'post_title'   => $def['title'],
			'post_name'    => $def['slug'],
			'post_content' => $def['content'],
			'post_excerpt' => isset( $def['excerpt'] ) ? $def['excerpt'] : '',
			'post_status'  => 'publish',
			'post_type'    => 'post',
		);
		if ( $existing ) {
			$data['ID'] = $existing;
			$id         = wp_update_post( wp_slash( $data ) );
		} else {
			$id = wp_insert_post( wp_slash( $data ) );
		}
		if ( ! $id || is_wp_error( $id ) ) {
			return false;
		}
		update_post_meta( $id, self::META_KEY, $def['slug'] );

		// Category.
		if ( ! empty( $def['category'] ) ) {
			$term = term_exists( $def['category'], 'category' );
			if ( ! $term ) {
				$term = wp_insert_term( $def['category'], 'category' );
			}
			if ( ! is_wp_error( $term ) ) {
				wp_set_post_categories( $id, array( (int) $term['term_id'] ), false );
			}
		}

		// Featured image (bundled SVG banner).
		if ( ! empty( $def['banner'] ) ) {
			$att = $this->sideload_image( $def['banner'], $id, 800, 450 );
			if ( $att ) {
				set_post_thumbnail( $id, $att );
			}
		}
		return true;
	}

	/**
	 * Copy a bundled SVG into the media library and return the attachment ID.
	 */
	protected function sideload_image( $filename, $parent_id, $w = 0, $h = 0 ) {
		// Reuse if already imported.
		$found = get_posts(
			array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'numberposts' => 1,
				'meta_key'    => self::IMG_META,
				'meta_value'  => $filename,
				'fields'      => 'ids',
			)
		);
		if ( $found ) {
			return (int) $found[0];
		}

		$src = QUILLPRESS_DIR . '/assets/images/' . $filename;
		if ( ! file_exists( $src ) ) {
			return 0;
		}
		$uploads = wp_upload_dir();
		if ( ! empty( $uploads['error'] ) ) {
			return 0;
		}
		$dest_name = 'quillpress-' . $filename;
		$dest      = trailingslashit( $uploads['path'] ) . $dest_name;
		if ( ! copy( $src, $dest ) ) {
			return 0;
		}

		$attachment = array(
			'post_mime_type' => 'image/svg+xml',
			'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);
		$att_id = wp_insert_attachment( $attachment, $dest, $parent_id );
		if ( is_wp_error( $att_id ) || ! $att_id ) {
			return 0;
		}
		update_post_meta( $att_id, self::IMG_META, $filename );
		update_post_meta( $att_id, '_wp_attached_file', ltrim( trailingslashit( $uploads['subdir'] ) . $dest_name, '/' ) );
		wp_update_attachment_metadata(
			$att_id,
			array(
				'width'  => $w,
				'height' => $h,
				'file'   => ltrim( trailingslashit( $uploads['subdir'] ) . $dest_name, '/' ),
			)
		);
		return (int) $att_id;
	}

	/**
	 * Build (or rebuild) the primary navigation menu.
	 */
	protected function build_primary_menu() {
		$name = 'Primary Menu';
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			// Clear existing items for a clean rebuild.
			$items = wp_get_nav_menu_items( $menu->term_id );
			if ( $items ) {
				foreach ( $items as $it ) {
					wp_delete_post( $it->ID, true );
				}
			}
			$menu_id = $menu->term_id;
		} else {
			$menu_id = wp_create_nav_menu( $name );
		}
		if ( is_wp_error( $menu_id ) ) {
			return 0;
		}

		foreach ( quillpress_demo_menu() as $item ) {
			$parent_item = $this->add_menu_link( $menu_id, $item, 0 );
			if ( ! empty( $item['children'] ) ) {
				foreach ( $item['children'] as $child ) {
					$this->add_menu_link( $menu_id, $child, $parent_item );
				}
			}
		}

		$this->assign_menu_location( 'primary', $menu_id );
		return 1;
	}

	protected function build_footer_menu() {
		$name = 'Footer Menu';
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			$items = wp_get_nav_menu_items( $menu->term_id );
			if ( $items ) {
				foreach ( $items as $it ) {
					wp_delete_post( $it->ID, true );
				}
			}
			$menu_id = $menu->term_id;
		} else {
			$menu_id = wp_create_nav_menu( $name );
		}
		if ( is_wp_error( $menu_id ) ) {
			return 0;
		}
		foreach ( quillpress_demo_footer_menu() as $item ) {
			$this->add_menu_link( $menu_id, $item, 0 );
		}
		$this->assign_menu_location( 'quillpress_footer', $menu_id );
		return 1;
	}

	protected function add_menu_link( $menu_id, $item, $parent = 0 ) {
		$page_id = isset( $this->slug_to_id[ $item['slug'] ] ) ? $this->slug_to_id[ $item['slug'] ] : 0;
		$args    = array(
			'menu-item-title'     => wp_specialchars_decode( $item['label'] ),
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent,
		);
		if ( $page_id ) {
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = $page_id;
			$args['menu-item-type']      = 'post_type';
		} else {
			$args['menu-item-url'] = home_url( '/' . $item['slug'] . '/' );
		}
		if ( ! empty( $item['cta'] ) ) {
			$args['menu-item-classes'] = 'qp-nav-cta';
		}
		$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );
		return is_wp_error( $item_id ) ? 0 : $item_id;
	}

	protected function assign_menu_location( $location, $menu_id ) {
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		if ( ! is_array( $locations ) ) {
			$locations = array();
		}
		$locations[ $location ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * Light GeneratePress settings so the header/typography match the demo.
	 */
	protected function apply_theme_settings() {
		$settings = get_option( 'generate_settings', array() );
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}
		$settings = array_merge(
			$settings,
			array(
				'nav_layout_setting'   => 'fluid-nav',
				'nav_inner_width'      => 'contained',
				'nav_position_setting' => 'nav-float-right',
				'container_width'      => '1200',
			)
		);
		update_option( 'generate_settings', $settings );
	}

	protected function trash_default_content() {
		$sample = get_page_by_path( 'sample-page' );
		if ( $sample && ! get_post_meta( $sample->ID, self::META_KEY, true ) ) {
			wp_trash_post( $sample->ID );
		}
		$hello = get_post( 1 );
		if ( $hello && 'post' === $hello->post_type && 'hello-world' === $hello->post_name ) {
			wp_trash_post( 1 );
		}
	}
}
