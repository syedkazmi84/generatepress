<?php
/**
 * "Book" custom post type + Genre taxonomy for the publishing catalog.
 *
 * @package Bookwright
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Book post type.
 */
function bookwright_register_book_cpt() {
	$labels = array(
		'name'               => __( 'Books', 'bookwright' ),
		'singular_name'      => __( 'Book', 'bookwright' ),
		'add_new'            => __( 'Add New Book', 'bookwright' ),
		'add_new_item'       => __( 'Add New Book', 'bookwright' ),
		'edit_item'          => __( 'Edit Book', 'bookwright' ),
		'new_item'           => __( 'New Book', 'bookwright' ),
		'view_item'          => __( 'View Book', 'bookwright' ),
		'search_items'       => __( 'Search Books', 'bookwright' ),
		'not_found'          => __( 'No books found', 'bookwright' ),
		'not_found_in_trash' => __( 'No books found in Trash', 'bookwright' ),
		'all_items'          => __( 'All Books', 'bookwright' ),
		'menu_name'          => __( 'Books', 'bookwright' ),
	);

	register_post_type(
		'book',
		array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-book-alt',
			'menu_position' => 5,
			'rewrite'      => array( 'slug' => 'books' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'bookwright_register_book_cpt' );

/**
 * Register the Genre taxonomy.
 */
function bookwright_register_genre_tax() {
	register_taxonomy(
		'genre',
		'book',
		array(
			'labels'            => array(
				'name'          => __( 'Genres', 'bookwright' ),
				'singular_name' => __( 'Genre', 'bookwright' ),
				'menu_name'     => __( 'Genres', 'bookwright' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'genre' ),
		)
	);
}
add_action( 'init', 'bookwright_register_genre_tax' );

/**
 * Book detail meta box (author name, price, buy link, rating).
 */
function bookwright_book_metabox() {
	add_meta_box(
		'bookwright_book_details',
		__( 'Book Details', 'bookwright' ),
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
		'_bw_author' => __( 'Author name', 'bookwright' ),
		'_bw_price'  => __( 'Price (e.g. $18.99)', 'bookwright' ),
		'_bw_link'   => __( 'Buy / details URL', 'bookwright' ),
		'_bw_rating' => __( 'Rating (1-5)', 'bookwright' ),
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
	foreach ( array( '_bw_author', '_bw_price', '_bw_link', '_bw_rating' ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$raw = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			if ( '_bw_link' === $key ) {
				$raw = esc_url_raw( $raw );
			}
			update_post_meta( $post_id, $key, $raw );
		}
	}
}
add_action( 'save_post_book', 'bookwright_save_book_meta' );

/**
 * Helper to fetch a book meta value.
 */
function bookwright_book_meta( $key, $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_post_meta( $post_id, $key, true );
}
