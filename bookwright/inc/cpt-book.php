<?php
/**
 * "Portfolio" custom post type + Category taxonomy.
 *
 * Showcases books the company has helped create (writing, editing, design,
 * publishing, marketing). This is a non-commercial portfolio — there are no
 * prices or buy buttons; it demonstrates the work, it does not sell books.
 *
 * The post type key stays `book` for template continuity (single-book.php,
 * archive-book.php, template-parts/book-card.php).
 *
 * @package Bookwright
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Portfolio post type.
 */
function bookwright_register_book_cpt() {
	$labels = array(
		'name'               => __( 'Portfolio', 'bookwright' ),
		'singular_name'      => __( 'Project', 'bookwright' ),
		'add_new'            => __( 'Add New Project', 'bookwright' ),
		'add_new_item'       => __( 'Add New Project', 'bookwright' ),
		'edit_item'          => __( 'Edit Project', 'bookwright' ),
		'new_item'           => __( 'New Project', 'bookwright' ),
		'view_item'          => __( 'View Project', 'bookwright' ),
		'search_items'       => __( 'Search Portfolio', 'bookwright' ),
		'not_found'          => __( 'No projects found', 'bookwright' ),
		'not_found_in_trash' => __( 'No projects found in Trash', 'bookwright' ),
		'all_items'          => __( 'All Projects', 'bookwright' ),
		'menu_name'          => __( 'Portfolio', 'bookwright' ),
	);

	register_post_type(
		'book',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 5,
			'rewrite'       => array( 'slug' => 'portfolio' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest'  => true,
		)
	);
}
add_action( 'init', 'bookwright_register_book_cpt' );

/**
 * Register the Category taxonomy (genre) for portfolio projects.
 */
function bookwright_register_genre_tax() {
	register_taxonomy(
		'genre',
		'book',
		array(
			'labels'            => array(
				'name'          => __( 'Categories', 'bookwright' ),
				'singular_name' => __( 'Category', 'bookwright' ),
				'menu_name'     => __( 'Categories', 'bookwright' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'work-category' ),
		)
	);
}
add_action( 'init', 'bookwright_register_genre_tax' );

/**
 * Project detail meta box (author/client + the service we provided).
 */
function bookwright_book_metabox() {
	add_meta_box(
		'bookwright_book_details',
		__( 'Project Details', 'bookwright' ),
		'bookwright_book_metabox_html',
		'book',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'bookwright_book_metabox' );

function bookwright_book_metabox_html( $post ) {
	wp_nonce_field( 'bookwright_save_book', 'bookwright_book_nonce' );
	$fields = array(
		'_bw_author'  => __( 'Author / client name', 'bookwright' ),
		'_bw_service' => __( 'Service provided (e.g. Ghostwriting, Cover design)', 'bookwright' ),
	);
	foreach ( $fields as $key => $label ) {
		$val = get_post_meta( $post->ID, $key, true );
		printf(
			'<p><label style="display:block;font-weight:600;margin-bottom:4px;">%s</label><input type="text" name="%s" value="%s" style="width:100%%;" /></p>',
			esc_html( $label ),
			esc_attr( $key ),
			esc_attr( $val )
		);
	}
	echo '<p style="color:#666;font-size:12px;">' . esc_html__( 'Set a Featured Image to use a real cover, or leave blank to use a bundled sample cover.', 'bookwright' ) . '</p>';
}

function bookwright_save_book_meta( $post_id ) {
	if ( ! isset( $_POST['bookwright_book_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bookwright_book_nonce'] ) ), 'bookwright_save_book' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( '_bw_author', '_bw_service' ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_book', 'bookwright_save_book_meta' );

/**
 * Helper to fetch a project meta value.
 */
function bookwright_book_meta( $key, $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_post_meta( $post_id, $key, true );
}
