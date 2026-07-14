<?php
/**
 * Block markup helpers.
 *
 * Small functions that return valid Gutenberg (WordPress editor) block markup
 * so the demo content stays readable and fully editable after import. Every
 * page is built from native core blocks styled by the child theme's design
 * system, so the layouts render correctly even before GenerateBlocks styling
 * is customised.
 *
 * @package Quill_Press
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Full-width section wrapper (constrained inner content).
 */
function qp_section( $classes, $inner, $content_size = '1140px' ) {
	$classes = trim( 'qp-section ' . $classes );
	$attrs   = wp_json_encode(
		array(
			'align'     => 'full',
			'className' => $classes,
			'layout'    => array(
				'type'        => 'constrained',
				'contentSize' => $content_size,
			),
		)
	);
	return "<!-- wp:group {$attrs} -->\n"
		. '<div class="wp-block-group alignfull ' . esc_attr( $classes ) . '">' . "\n"
		. $inner
		. "\n</div>\n<!-- /wp:group -->\n";
}

/**
 * Plain constrained group (no full-bleed background) — used to nest content.
 */
function qp_group( $classes, $inner, $content_size = '' ) {
	$layout = array( 'type' => 'constrained' );
	if ( $content_size ) {
		$layout['contentSize'] = $content_size;
	}
	$attrs = wp_json_encode(
		array(
			'className' => $classes,
			'layout'    => $layout,
		)
	);
	return "<!-- wp:group {$attrs} -->\n"
		. '<div class="wp-block-group ' . esc_attr( $classes ) . '">' . "\n"
		. $inner
		. "\n</div>\n<!-- /wp:group -->\n";
}

/**
 * Eyebrow label.
 */
function qp_eyebrow( $text, $center = false ) {
	$class = 'qp-eyebrow' . ( $center ? ' has-text-align-center' : '' );
	$attrs = wp_json_encode( array( 'className' => $class ) );
	return "<!-- wp:paragraph {$attrs} -->\n<p class=\"" . esc_attr( $class ) . '">' . $text . "</p>\n<!-- /wp:paragraph -->\n";
}

/**
 * Heading. $extra adds classes such as qp-display / qp-h2.
 */
function qp_heading( $level, $text, $extra = '', $center = false ) {
	$class = trim( 'wp-block-heading' . ( $center ? ' has-text-align-center' : '' ) . ' ' . $extra );
	$json  = array( 'level' => (int) $level );
	if ( $center ) {
		$json['textAlign'] = 'center';
	}
	if ( $extra ) {
		$json['className'] = trim( $extra );
	}
	$attrs = wp_json_encode( $json );
	return "<!-- wp:heading {$attrs} -->\n<h{$level} class=\"" . esc_attr( $class ) . "\">{$text}</h{$level}>\n<!-- /wp:heading -->\n";
}

/**
 * Paragraph.
 */
function qp_para( $text, $extra = '', $center = false ) {
	$class = trim( ( $center ? 'has-text-align-center ' : '' ) . $extra );
	$json  = array();
	if ( $center ) {
		$json['align'] = 'center';
	}
	if ( $extra ) {
		$json['className'] = $extra;
	}
	$open  = $json ? ' ' . wp_json_encode( $json ) : '';
	$attr  = $class ? ' class="' . esc_attr( $class ) . '"' : '';
	return "<!-- wp:paragraph{$open} -->\n<p{$attr}>{$text}</p>\n<!-- /wp:paragraph -->\n";
}

/**
 * Buttons row. $buttons = array of array( 'text','url','class' ).
 */
function qp_buttons( $buttons, $center = false ) {
	$inner = '';
	foreach ( $buttons as $b ) {
		$cls   = isset( $b['class'] ) ? $b['class'] : 'qp-btn';
		$attrs = wp_json_encode( array( 'className' => $cls ) );
		$inner .= "<!-- wp:button {$attrs} -->\n"
			. '<div class="wp-block-button ' . esc_attr( $cls ) . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $b['url'] ) . '">' . $b['text'] . "</a></div>\n"
			. "<!-- /wp:button -->\n";
	}
	$json = array();
	if ( $center ) {
		$json['layout'] = array(
			'type'           => 'flex',
			'justifyContent' => 'center',
		);
	}
	$open = $json ? ' ' . wp_json_encode( $json ) : '';
	$cls  = 'wp-block-buttons' . ( $center ? ' is-content-justification-center is-layout-flex' : '' );
	return "<!-- wp:buttons{$open} -->\n<div class=\"" . esc_attr( $cls ) . "\">\n{$inner}</div>\n<!-- /wp:buttons -->\n";
}

/**
 * Image block. $extra adds classes such as qp-icon / qp-book / qp-figure.
 */
function qp_image( $url, $alt, $extra = '', $width = 0 ) {
	$class = trim( 'wp-block-image ' . $extra );
	$json  = array();
	if ( $extra ) {
		$json['className'] = $extra;
	}
	if ( $width ) {
		$json['width'] = $width;
	}
	$open = $json ? ' ' . wp_json_encode( $json ) : '';
	$img  = '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"'
		. ( $width ? ' style="width:' . (int) $width . 'px"' : '' ) . '/>';
	return "<!-- wp:image{$open} -->\n<figure class=\"" . esc_attr( $class ) . "\">{$img}</figure>\n<!-- /wp:image -->\n";
}

/**
 * Columns wrapper. $columns = array of inner-HTML strings.
 */
function qp_columns( $columns, $wrap_class = '', $col_class = '', $col_attrs = array() ) {
	$inner = '';
	foreach ( $columns as $i => $c ) {
		$cc    = $col_class;
		if ( isset( $col_attrs[ $i ]['class'] ) ) {
			$cc = trim( $cc . ' ' . $col_attrs[ $i ]['class'] );
		}
		$json  = array();
		if ( $cc ) {
			$json['className'] = $cc;
		}
		$open  = $json ? ' ' . wp_json_encode( $json ) : '';
		$cls   = trim( 'wp-block-column ' . $cc );
		$inner .= "<!-- wp:column{$open} -->\n<div class=\"" . esc_attr( $cls ) . "\">\n{$c}</div>\n<!-- /wp:column -->\n";
	}
	$json = array();
	if ( $wrap_class ) {
		$json['className'] = $wrap_class;
	}
	$open = $json ? ' ' . wp_json_encode( $json ) : '';
	$cls  = trim( 'wp-block-columns ' . $wrap_class );
	return "<!-- wp:columns{$open} -->\n<div class=\"" . esc_attr( $cls ) . "\">\n{$inner}</div>\n<!-- /wp:columns -->\n";
}

/**
 * Checkmark list. $items = array of strings.
 */
function qp_checklist( $items, $extra = 'qp-check' ) {
	$lis = '';
	foreach ( $items as $it ) {
		$lis .= "<!-- wp:list-item -->\n<li>{$it}</li>\n<!-- /wp:list-item -->\n";
	}
	$attrs = wp_json_encode( array( 'className' => $extra ) );
	return "<!-- wp:list {$attrs} -->\n<ul class=\"wp-block-list " . esc_attr( $extra ) . "\">\n{$lis}</ul>\n<!-- /wp:list -->\n";
}

/**
 * Spacer.
 */
function qp_spacer( $height = 40 ) {
	$attrs = wp_json_encode( array( 'height' => $height . 'px' ) );
	return "<!-- wp:spacer {$attrs} -->\n<div style=\"height:{$height}px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n";
}

/**
 * A single "icon card" column inner (icon tile + heading + text + link).
 */
function qp_icon_card( $icon_file, $title, $text, $link_text = '', $link_url = '' ) {
	$html  = qp_image( quillpress_img( $icon_file ), $title, 'qp-icon' );
	$html .= qp_heading( 3, $title );
	$html .= qp_para( $text );
	if ( $link_text ) {
		$attrs = wp_json_encode( array( 'className' => 'qp-cardmore' ) );
		$html .= "<!-- wp:paragraph {$attrs} -->\n<p class=\"qp-cardmore\"><a class=\"qp-cardlink\" href=\"" . esc_url( $link_url ) . "\">{$link_text}</a></p>\n<!-- /wp:paragraph -->\n";
	}
	return $html;
}

/**
 * A testimonial / quote column inner.
 */
function qp_quote_card( $stars, $quote, $avatar_file, $name, $role ) {
	$html  = qp_para( str_repeat( '★', $stars ), 'qp-stars' );
	$html .= qp_para( $quote );
	$author = qp_image( quillpress_img( $avatar_file ), $name, '' )
		. qp_para( "<strong>{$name}</strong><br><span>{$role}</span>" );
	$html  .= qp_group( 'qp-author', $author );
	return $html;
}

/**
 * A stat column inner (big number + label).
 */
function qp_stat( $number, $label ) {
	$html  = qp_heading( 3, $number, 'qp-stat-num' );
	$attrs = wp_json_encode( array( 'className' => 'qp-stat-label' ) );
	$html .= "<!-- wp:paragraph {$attrs} -->\n<p class=\"qp-stat-label\">{$label}</p>\n<!-- /wp:paragraph -->\n";
	return $html;
}

/**
 * A book cover column inner (cover + title + meta).
 */
function qp_book_card( $cover_file, $title, $meta ) {
	$html  = qp_image( quillpress_img( $cover_file ), $title, 'qp-book' );
	$html .= qp_para( "<strong>{$title}</strong>", 'qp-book-title' );
	$html .= qp_para( $meta, 'qp-book-meta' );
	return $html;
}

/**
 * FAQ item using the native Details block.
 */
function qp_faq_item( $q, $a ) {
	$summary = "<!-- wp:paragraph {\"placeholder\":\"Type / to add a hidden block\"} -->\n<p>{$a}</p>\n<!-- /wp:paragraph -->";
	return "<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>{$q}</summary>\n{$summary}\n</details>\n<!-- /wp:details -->\n";
}

/**
 * A pricing plan column inner.
 */
function qp_price_card( $name, $desc, $price, $per, $features, $cta_text, $cta_url, $featured = false ) {
	$html  = qp_para( "<strong>{$name}</strong>", 'qp-plan-name' );
	$html .= qp_para( $desc, 'qp-plan-desc' );
	$html .= qp_para( $price . ' <small>' . $per . '</small>', 'qp-plan-price' );
	$html .= "<!-- wp:separator -->\n<hr class=\"wp-block-separator has-alpha-channel-opacity\"/>\n<!-- /wp:separator -->\n";
	$html .= qp_checklist( $features );
	$html .= qp_buttons(
		array(
			array(
				'text'  => $cta_text,
				'url'   => $cta_url,
				'class' => $featured ? 'qp-btn' : 'qp-btn qp-btn--ghost',
			),
		)
	);
	return $html;
}
