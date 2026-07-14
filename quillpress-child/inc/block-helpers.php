<?php
/**
 * GenerateBlocks markup helpers.
 *
 * Every page is assembled from GenerateBlocks blocks — Container, Grid,
 * Headline, Button and Image (generateblocks/*). GenerateBlocks generates the
 * structural + responsive CSS from each block's attributes at render time; the
 * child theme's design system (assets/css/main.css) layers the visual styling
 * on top via qp- classes. This keeps the pages 100% GenerateBlocks and fully
 * editable in the GenerateBlocks editor after import.
 *
 * @package Quill_Press
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sequential, collision-free unique id for GenerateBlocks blocks.
 * Monotonic across a single import run, so it is always unique within a page.
 */
function qp_uid() {
	static $n = 1000;
	$n++;
	return 'qp' . base_convert( $n, 10, 36 );
}

/**
 * GenerateBlocks Container.
 *
 * $args: class, tag, outer (full|contained), inner (contained|full), width,
 *        isGrid (bool), gridWidth (number %), pad (px string), style (inline).
 */
function qp_container( $args, $inner ) {
	$d = array_merge(
		array(
			'class'     => '',
			'tag'       => 'div',
			'outer'     => 'full',
			'inner'     => 'contained',
			'width'     => 1140,
			'isGrid'    => false,
			'gridWidth' => 0,
			'pad'       => '0px',
			'style'     => '',
		),
		$args
	);

	$uid   = qp_uid();
	$attrs = array( 'uniqueId' => $uid );
	if ( 'div' !== $d['tag'] ) {
		$attrs['tagName'] = $d['tag'];
	}
	if ( $d['class'] ) {
		$attrs['className'] = $d['class'];
	}
	$attrs['outerContainer'] = $d['outer'];
	$attrs['innerContainer'] = $d['inner'];
	if ( 'contained' === $d['inner'] ) {
		$attrs['containerWidth'] = (int) $d['width'];
	}
	$attrs['spacing'] = array(
		'paddingTop'    => $d['pad'],
		'paddingRight'  => $d['pad'],
		'paddingBottom' => $d['pad'],
		'paddingLeft'   => $d['pad'],
	);
	if ( $d['isGrid'] ) {
		$attrs['isGrid'] = true;
		if ( $d['gridWidth'] ) {
			$attrs['width'] = $d['gridWidth'];
		}
	}

	$json     = wp_json_encode( $attrs );
	$tag      = 'div' !== $d['tag'] ? $d['tag'] : 'div';
	$cls_list = trim( 'gb-container gb-container-' . $uid . ' ' . $d['class'] );
	$style    = $d['style'] ? ' style="' . esc_attr( $d['style'] ) . '"' : '';
	$open     = "<!-- wp:generateblocks/container {$json} -->\n";
	$close    = "\n<!-- /wp:generateblocks/container -->\n";

	if ( $d['isGrid'] ) {
		return $open
			. '<div class="gb-grid-column gb-grid-column-' . esc_attr( $uid ) . '">'
			. '<' . $tag . ' class="' . esc_attr( $cls_list ) . '"' . $style . '><div class="gb-inside-container">' . "\n"
			. $inner
			. "\n</div></" . $tag . '></div>' . $close;
	}
	return $open
		. '<' . $tag . ' class="' . esc_attr( $cls_list ) . '"' . $style . '><div class="gb-inside-container">' . "\n"
		. $inner
		. "\n</div></" . $tag . '>' . $close;
}

/**
 * GenerateBlocks Grid holding container "grid item" children.
 */
function qp_grid( $items_inner, $wrap_class, $gap, $item_width, $per_item_class = array() ) {
	$uid   = qp_uid();
	$attrs = array( 'uniqueId' => $uid );
	if ( $wrap_class ) {
		$attrs['className'] = $wrap_class;
	}
	$attrs['horizontalGap'] = $gap;
	$attrs['verticalGap']   = $gap;
	$attrs['verticalAlignment'] = 'stretch';

	$json = wp_json_encode( $attrs );
	$cls  = trim( 'gb-grid-wrapper gb-grid-wrapper-' . $uid . ' ' . $wrap_class );

	$inner = '';
	foreach ( $items_inner as $i => $html ) {
		$cc     = isset( $per_item_class[ $i ] ) ? $per_item_class[ $i ] : '';
		$inner .= qp_container(
			array(
				'class'     => trim( $cc ),
				'isGrid'    => true,
				'gridWidth' => $item_width,
				'inner'     => 'full',
			),
			$html
		);
	}
	return "<!-- wp:generateblocks/grid {$json} -->\n<div class=\"" . esc_attr( $cls ) . "\">\n{$inner}</div>\n<!-- /wp:generateblocks/grid -->\n";
}

/**
 * Full-width section (constrained inner content). Signature-compatible with the
 * previous core-blocks helper.
 */
function qp_section( $classes, $inner, $content_size = '1140px' ) {
	return qp_container(
		array(
			'class' => trim( 'qp-section ' . $classes ),
			'tag'   => 'section',
			'outer' => 'full',
			'inner' => 'contained',
			'width' => (int) $content_size,
			'pad'   => '0px',
		),
		$inner
	);
}

/**
 * Plain constrained group used to nest content.
 */
function qp_group( $classes, $inner, $content_size = '' ) {
	$width = $content_size ? (int) $content_size : 1140;
	$inner_type = $content_size ? 'contained' : 'full';
	return qp_container(
		array(
			'class' => $classes,
			'outer' => 'contained',
			'inner' => $inner_type,
			'width' => $width,
			'pad'   => '0px',
		),
		$inner
	);
}

/**
 * A thin divider rendered as an empty GenerateBlocks container.
 */
function qp_divider() {
	return qp_container(
		array(
			'class' => 'qp-hr',
			'outer' => 'contained',
			'inner' => 'full',
			'pad'   => '0px',
		),
		''
	);
}

/**
 * Eyebrow label (Headline element = p).
 */
function qp_eyebrow( $text, $center = false ) {
	return qp_para( $text, 'qp-eyebrow', $center );
}

/**
 * Heading — GenerateBlocks Headline.
 */
function qp_heading( $level, $text, $extra = '', $center = false ) {
	$uid = qp_uid();
	$cls = trim( $extra . ( $center ? ' has-text-align-center' : '' ) );
	$attrs = array(
		'uniqueId' => $uid,
		'element'  => 'h' . (int) $level,
	);
	if ( $cls ) {
		$attrs['className'] = $cls;
	}
	$json    = wp_json_encode( $attrs );
	$htmlcls = trim( 'gb-headline gb-headline-' . $uid . ' ' . $cls );
	return "<!-- wp:generateblocks/headline {$json} -->\n<h{$level} class=\"" . esc_attr( $htmlcls ) . "\">{$text}</h{$level}>\n<!-- /wp:generateblocks/headline -->\n";
}

/**
 * Paragraph — GenerateBlocks Headline with element = p.
 */
function qp_para( $text, $extra = '', $center = false ) {
	$uid = qp_uid();
	$cls = trim( $extra . ( $center ? ' has-text-align-center' : '' ) );
	$attrs = array(
		'uniqueId' => $uid,
		'element'  => 'p',
	);
	if ( $cls ) {
		$attrs['className'] = $cls;
	}
	$json    = wp_json_encode( $attrs );
	$htmlcls = trim( 'gb-headline gb-headline-' . $uid . ' ' . $cls );
	return "<!-- wp:generateblocks/headline {$json} -->\n<p class=\"" . esc_attr( $htmlcls ) . "\">{$text}</p>\n<!-- /wp:generateblocks/headline -->\n";
}

/**
 * Buttons — GenerateBlocks Button Container + Buttons.
 * $buttons = array of array( 'text','url','class' ).
 */
function qp_buttons( $buttons, $center = false ) {
	$uid    = qp_uid();
	$wattrs = array( 'uniqueId' => $uid );
	if ( $center ) {
		$wattrs['className'] = 'qp-btns-center';
	}
	$wjson = wp_json_encode( $wattrs );
	$wcls  = trim( 'gb-button-wrapper gb-button-wrapper-' . $uid . ( $center ? ' qp-btns-center' : '' ) );

	$inner = '';
	foreach ( $buttons as $b ) {
		$bu     = qp_uid();
		$cls    = isset( $b['class'] ) ? $b['class'] : 'qp-btn';
		$battrs = array(
			'uniqueId' => $bu,
			'text'     => $b['text'],
			'url'      => $b['url'],
		);
		if ( $cls ) {
			$battrs['className'] = $cls;
		}
		$bjson  = wp_json_encode( $battrs );
		$bcls   = trim( 'gb-button gb-button-' . $bu . ' ' . $cls );
		$inner .= "<!-- wp:generateblocks/button {$bjson} -->\n<a class=\"" . esc_attr( $bcls ) . '" href="' . esc_url( $b['url'] ) . '">' . $b['text'] . "</a>\n<!-- /wp:generateblocks/button -->\n";
	}
	return "<!-- wp:generateblocks/button-container {$wjson} -->\n<div class=\"" . esc_attr( $wcls ) . "\">\n{$inner}</div>\n<!-- /wp:generateblocks/button-container -->\n";
}

/**
 * Image — GenerateBlocks Image (references a URL directly).
 */
function qp_image( $url, $alt, $extra = '', $width = 0 ) {
	$uid   = qp_uid();
	$attrs = array(
		'uniqueId' => $uid,
		'mediaUrl' => $url,
	);
	if ( $extra ) {
		$attrs['className'] = $extra;
	}
	$json    = wp_json_encode( $attrs );
	$figcls  = trim( 'gb-block-image gb-block-image-' . $uid . ' ' . $extra );
	$style   = $width ? ' style="width:' . (int) $width . 'px"' : '';
	return "<!-- wp:generateblocks/image {$json} -->\n<figure class=\"" . esc_attr( $figcls ) . "\"><img class=\"gb-image gb-image-" . esc_attr( $uid ) . '" src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . "\"{$style}/></figure>\n<!-- /wp:generateblocks/image -->\n";
}

/**
 * Checkmark list rendered as GenerateBlocks paragraphs (one per item).
 */
function qp_checklist( $items, $extra = 'qp-check' ) {
	$out = '';
	foreach ( $items as $it ) {
		$out .= qp_para( $it, 'qp-check-item' );
	}
	return qp_group( trim( $extra ), $out );
}

/**
 * Vertical spacer rendered as a GenerateBlocks container (top padding).
 */
function qp_spacer( $height = 40 ) {
	$uid   = qp_uid();
	$attrs = array(
		'uniqueId'  => $uid,
		'className' => 'qp-spacer',
		'spacing'   => array(
			'paddingTop'    => (int) $height . 'px',
			'paddingRight'  => '0px',
			'paddingBottom' => '0px',
			'paddingLeft'   => '0px',
		),
	);
	$json = wp_json_encode( $attrs );
	return "<!-- wp:generateblocks/container {$json} -->\n<div class=\"gb-container gb-container-" . esc_attr( $uid ) . " qp-spacer\"><div class=\"gb-inside-container\"></div></div>\n<!-- /wp:generateblocks/container -->\n";
}

/**
 * Columns wrapper — mapped onto a GenerateBlocks Grid. Signature-compatible.
 */
function qp_columns( $columns, $wrap_class = '', $col_class = '', $col_attrs = array() ) {
	$n = count( $columns );
	if ( $n < 1 ) {
		return '';
	}
	$width = round( 100 / $n, 2 );
	$per   = array();
	foreach ( $columns as $i => $c ) {
		$cc = $col_class;
		if ( isset( $col_attrs[ $i ]['class'] ) ) {
			$cc = trim( $cc . ' ' . $col_attrs[ $i ]['class'] );
		}
		$per[ $i ] = $cc;
	}
	return qp_grid( $columns, $wrap_class, 24, $width, $per );
}

/* -------------------------------------------------------------------------
 * Higher-level component builders (return the inner content of a grid item)
 * ---------------------------------------------------------------------- */

function qp_icon_card( $icon_file, $title, $text, $link_text = '', $link_url = '' ) {
	$html  = qp_image( quillpress_img( $icon_file ), $title, 'qp-icon' );
	$html .= qp_heading( 3, $title );
	$html .= qp_para( $text );
	if ( $link_text ) {
		$html .= qp_para( '<a class="qp-cardlink" href="' . esc_url( $link_url ) . '">' . $link_text . '</a>', 'qp-cardmore' );
	}
	return $html;
}

function qp_quote_card( $stars, $quote, $avatar_file, $name, $role ) {
	$html   = qp_para( str_repeat( '★', $stars ), 'qp-stars' );
	$html  .= qp_para( $quote );
	$author = qp_image( quillpress_img( $avatar_file ), $name, 'qp-avatar' )
		. qp_para( "<strong>{$name}</strong><br><span>{$role}</span>", 'qp-author-name' );
	$html  .= qp_group( 'qp-author', $author );
	return $html;
}

function qp_stat( $number, $label ) {
	return qp_heading( 3, $number, 'qp-stat-num' ) . qp_para( $label, 'qp-stat-label' );
}

function qp_book_card( $cover_file, $title, $meta ) {
	$html  = qp_image( quillpress_img( $cover_file ), $title, 'qp-book' );
	$html .= qp_para( "<strong>{$title}</strong>", 'qp-book-title' );
	$html .= qp_para( $meta, 'qp-book-meta' );
	return $html;
}

/**
 * FAQ item rendered as a GenerateBlocks card (question + answer).
 */
function qp_faq_item( $q, $a ) {
	$inner = qp_heading( 3, $q, 'qp-faq-q' ) . qp_para( $a, 'qp-faq-a' );
	return qp_container(
		array(
			'class' => 'qp-faq-item',
			'outer' => 'contained',
			'inner' => 'full',
			'pad'   => '0px',
		),
		$inner
	);
}

function qp_price_card( $name, $desc, $price, $per, $features, $cta_text, $cta_url, $featured = false ) {
	$html  = qp_para( "<strong>{$name}</strong>", 'qp-plan-name' );
	$html .= qp_para( $desc, 'qp-plan-desc' );
	$html .= qp_para( $price . ' <small>' . $per . '</small>', 'qp-plan-price' );
	$html .= qp_divider();
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
