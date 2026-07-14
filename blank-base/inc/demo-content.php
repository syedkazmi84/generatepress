<?php
/**
 * Demo content definitions for Blank Base.
 *
 * This file contains a tiny, dependency-free "block builder" that emits valid
 * Gutenberg (block editor) markup, plus the full set of pages and posts for the
 * bundled "Quill & Press" book-publishing demo. Everything here is data: the
 * importer in inc/demo-import.php calls these functions, replaces the image
 * placeholders with real media-library URLs/IDs and inserts the result.
 *
 * All markup is built from core blocks (group, columns, cover, heading,
 * paragraph, buttons, image, list, separator, spacer, quote) so the imported
 * pages open cleanly in the Gutenberg editor and are fully editable.
 *
 * @package Blank_Base
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * Block builder helpers.
 * ====================================================================== */

/**
 * Encode block attributes the way the block editor does (no escaped slashes).
 *
 * @param array $json Attributes.
 * @return string Leading-space JSON string, or empty when there are none.
 */
function bbd_attrs( $json ) {
	if ( empty( $json ) ) {
		return '';
	}
	return ' ' . wp_json_encode( $json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
}

/**
 * Look up an imported image URL by key.
 *
 * @param array  $img Image map ( key => [ id, url ] ).
 * @param string $k   Key.
 * @return string
 */
function bbd_url( $img, $k ) {
	return isset( $img[ $k ]['url'] ) ? $img[ $k ]['url'] : '';
}

/**
 * Look up an imported image attachment ID by key.
 *
 * @param array  $img Image map.
 * @param string $k   Key.
 * @return int
 */
function bbd_id( $img, $k ) {
	return isset( $img[ $k ]['id'] ) ? (int) $img[ $k ]['id'] : 0;
}

/**
 * Heading block.
 */
function bbd_heading( $text, $level = 2, $o = array() ) {
	$json  = array();
	$cls   = array( 'wp-block-heading' );
	$style = array();
	$tag   = 'h' . $level;

	if ( 2 !== (int) $level ) {
		$json['level'] = (int) $level;
	}
	if ( ! empty( $o['align'] ) ) {
		$json['textAlign'] = $o['align'];
		$cls[]             = 'has-text-align-' . $o['align'];
	}
	if ( ! empty( $o['fontSizeCustom'] ) ) {
		$json['style']['typography']['fontSize'] = $o['fontSizeCustom'];
		$style[]                                 = 'font-size:' . $o['fontSizeCustom'];
	}
	if ( ! empty( $o['lineHeight'] ) ) {
		$json['style']['typography']['lineHeight'] = $o['lineHeight'];
		$style[]                                   = 'line-height:' . $o['lineHeight'];
	}
	if ( ! empty( $o['color'] ) ) {
		$json['textColor'] = $o['color'];
		$cls[]             = 'has-' . $o['color'] . '-color';
		$cls[]             = 'has-text-color';
	}
	if ( ! empty( $o['size'] ) ) {
		$json['fontSize'] = $o['size'];
		$cls[]            = 'has-' . $o['size'] . '-font-size';
	}
	$stylestr = $style ? ' style="' . implode( ';', $style ) . '"' : '';
	return "<!-- wp:heading" . bbd_attrs( $json ) . " -->\n<{$tag} class=\"" . implode( ' ', $cls ) . "\"{$stylestr}>{$text}</{$tag}>\n<!-- /wp:heading -->";
}

/**
 * Paragraph block.
 */
function bbd_para( $text, $o = array() ) {
	$json  = array();
	$cls   = array();
	$style = array();

	if ( ! empty( $o['align'] ) ) {
		$json['align'] = $o['align'];
		$cls[]         = 'has-text-align-' . $o['align'];
	}
	if ( ! empty( $o['color'] ) ) {
		$json['textColor'] = $o['color'];
		$cls[]             = 'has-' . $o['color'] . '-color';
		$cls[]             = 'has-text-color';
	}
	if ( ! empty( $o['bg'] ) ) {
		$json['backgroundColor'] = $o['bg'];
		$cls[]                   = 'has-' . $o['bg'] . '-background-color';
		$cls[]                   = 'has-background';
	}
	if ( ! empty( $o['size'] ) ) {
		$json['fontSize'] = $o['size'];
		$cls[]            = 'has-' . $o['size'] . '-font-size';
	}
	if ( ! empty( $o['fontSizeCustom'] ) ) {
		$json['style']['typography']['fontSize'] = $o['fontSizeCustom'];
		$style[]                                 = 'font-size:' . $o['fontSizeCustom'];
	}
	$c        = $cls ? ' class="' . implode( ' ', $cls ) . '"' : '';
	$stylestr = $style ? ' style="' . implode( ';', $style ) . '"' : '';
	return "<!-- wp:paragraph" . bbd_attrs( $json ) . " -->\n<p{$c}{$stylestr}>{$text}</p>\n<!-- /wp:paragraph -->";
}

/**
 * Buttons wrapper.
 */
function bbd_buttons( $buttons, $justify = 'left' ) {
	$json = array();
	if ( $justify ) {
		$json['layout'] = array(
			'type'            => 'flex',
			'justifyContent'  => $justify,
		);
	}
	$inner = implode( '', $buttons );
	return "<!-- wp:buttons" . bbd_attrs( $json ) . " -->\n<div class=\"wp-block-buttons\">{$inner}</div>\n<!-- /wp:buttons -->";
}

/**
 * Single button. Styles: 'fill' (accent) or 'outline'.
 */
function bbd_button( $text, $url = '#', $o = array() ) {
	$style = isset( $o['style'] ) ? $o['style'] : 'fill';
	if ( 'outline' === $style ) {
		$tc     = isset( $o['color'] ) ? $o['color'] : 'cream';
		$json   = array( 'textColor' => $tc, 'className' => 'is-style-outline' );
		$link   = array( 'wp-block-button__link', 'has-' . $tc . '-color', 'has-text-color', 'wp-element-button' );
		return "<!-- wp:button" . bbd_attrs( $json ) . " -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"" . implode( ' ', $link ) . "\" href=\"{$url}\">{$text}</a></div>\n<!-- /wp:button -->";
	}
	$bg   = isset( $o['bg'] ) ? $o['bg'] : 'accent';
	$tc   = isset( $o['color'] ) ? $o['color'] : 'ink';
	$json = array( 'backgroundColor' => $bg, 'textColor' => $tc );
	$link = array( 'wp-block-button__link', 'has-' . $tc . '-color', 'has-' . $bg . '-background-color', 'has-text-color', 'has-background', 'wp-element-button' );
	return "<!-- wp:button" . bbd_attrs( $json ) . " -->\n<div class=\"wp-block-button\"><a class=\"" . implode( ' ', $link ) . "\" href=\"{$url}\">{$text}</a></div>\n<!-- /wp:button -->";
}

/**
 * Image block.
 */
function bbd_image( $img, $key, $o = array() ) {
	$id   = bbd_id( $img, $key );
	$url  = bbd_url( $img, $key );
	$size = isset( $o['size'] ) ? $o['size'] : 'large';
	$json = array( 'id' => $id, 'sizeSlug' => $size, 'linkDestination' => 'none' );
	$cls  = array( 'wp-block-image', 'size-' . $size );

	if ( ! empty( $o['align'] ) ) {
		$json['align'] = $o['align'];
		$cls[]         = 'align' . $o['align'];
	}
	if ( ! empty( $o['rounded'] ) ) {
		$json['className'] = 'is-style-rounded';
		$cls[]             = 'is-style-rounded';
	}
	$alt = isset( $o['alt'] ) ? esc_attr( $o['alt'] ) : '';
	return "<!-- wp:image" . bbd_attrs( $json ) . " -->\n<figure class=\"" . implode( ' ', $cls ) . "\"><img src=\"{$url}\" alt=\"{$alt}\" class=\"wp-image-{$id}\"/></figure>\n<!-- /wp:image -->";
}

/**
 * Cover block with an image background.
 */
function bbd_cover( $img, $key, $inner, $o = array() ) {
	$id      = bbd_id( $img, $key );
	$url     = bbd_url( $img, $key );
	$overlay = isset( $o['overlay'] ) ? $o['overlay'] : 'ink';
	$dim     = isset( $o['dim'] ) ? (int) $o['dim'] : 60;
	$align   = isset( $o['align'] ) ? $o['align'] : 'full';
	$minh    = isset( $o['minh'] ) ? (int) $o['minh'] : 520;
	$is_dark = ! isset( $o['isDark'] ) || $o['isDark'];

	$json = array(
		'url'           => $url,
		'id'            => $id,
		'dimRatio'      => $dim,
		'overlayColor'  => $overlay,
		'minHeight'     => $minh,
		'minHeightUnit' => 'px',
		'align'         => $align,
	);
	if ( ! $is_dark ) {
		$json['isDark'] = false;
	}
	if ( ! empty( $o['focalY'] ) ) {
		$json['focalPoint'] = array( 'x' => 0.5, 'y' => (float) $o['focalY'] );
	}

	$cls   = array( 'wp-block-cover', 'align' . $align, $is_dark ? 'is-dark' : 'is-light' );
	$spanc = 'wp-block-cover__background has-' . $overlay . '-background-color has-background-dim-' . $dim . ' has-background-dim';
	$fp    = ! empty( $o['focalY'] ) ? ' style="object-position:50% ' . ( (float) $o['focalY'] * 100 ) . '%"' : '';

	return "<!-- wp:cover" . bbd_attrs( $json ) . " -->\n"
		. "<div class=\"" . implode( ' ', $cls ) . "\" style=\"min-height:{$minh}px\">"
		. "<span aria-hidden=\"true\" class=\"{$spanc}\"></span>"
		. "<img class=\"wp-block-cover__image-background wp-image-{$id}\" alt=\"\" src=\"{$url}\"{$fp} data-object-fit=\"cover\"/>"
		. "<div class=\"wp-block-cover__inner-container\">{$inner}</div></div>\n<!-- /wp:cover -->";
}

/**
 * Group block. $o keys: layout (constrained|default|flex), align, bg, text,
 * pad (array of sides), contentSize, className, gradient.
 */
function bbd_group( $inner, $o = array() ) {
	$json   = array();
	$cls    = array( 'wp-block-group' );
	$style  = array();
	$layout = isset( $o['layout'] ) ? $o['layout'] : 'constrained';

	if ( ! empty( $o['className'] ) ) {
		$json['className'] = $o['className'];
		$cls[]             = $o['className'];
	}
	if ( ! empty( $o['align'] ) ) {
		$json['align'] = $o['align'];
		$cls[]         = 'align' . $o['align'];
	}
	if ( ! empty( $o['bg'] ) ) {
		$json['backgroundColor'] = $o['bg'];
		$cls[]                   = 'has-' . $o['bg'] . '-background-color';
		$cls[]                   = 'has-background';
	}
	if ( ! empty( $o['gradient'] ) ) {
		$json['gradient'] = $o['gradient'];
		$cls[]            = 'has-' . $o['gradient'] . '-gradient-background';
		$cls[]            = 'has-background';
	}
	if ( ! empty( $o['text'] ) ) {
		$json['textColor'] = $o['text'];
		$cls[]             = 'has-' . $o['text'] . '-color';
		$cls[]             = 'has-text-color';
	}
	if ( ! empty( $o['pad'] ) ) {
		$json['style']['spacing']['padding'] = $o['pad'];
		foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
			if ( isset( $o['pad'][ $side ] ) ) {
				$style[] = 'padding-' . $side . ':' . $o['pad'][ $side ];
			}
		}
	}
	if ( ! empty( $o['radius'] ) ) {
		$json['style']['border']['radius'] = $o['radius'];
		$style[]                           = 'border-radius:' . $o['radius'];
	}
	$json['layout'] = array( 'type' => $layout );
	if ( 'constrained' === $layout && ! empty( $o['contentSize'] ) ) {
		$json['layout']['contentSize'] = $o['contentSize'];
	}
	if ( 'flex' === $layout ) {
		$json['layout']['flexWrap'] = isset( $o['flexWrap'] ) ? $o['flexWrap'] : 'nowrap';
		if ( ! empty( $o['justify'] ) ) {
			$json['layout']['justifyContent'] = $o['justify'];
		}
	}
	$stylestr = $style ? ' style="' . implode( ';', $style ) . '"' : '';
	return "<!-- wp:group" . bbd_attrs( $json ) . " -->\n<div class=\"" . implode( ' ', $cls ) . "\"{$stylestr}>{$inner}</div>\n<!-- /wp:group -->";
}

/**
 * Columns wrapper.
 */
function bbd_columns( $cols, $o = array() ) {
	$json = array();
	$cls  = array( 'wp-block-columns' );

	if ( isset( $o['stackMobile'] ) && false === $o['stackMobile'] ) {
		$json['isStackedOnMobile'] = false;
		$cls[]                     = 'is-not-stacked-on-mobile';
	}
	if ( ! empty( $o['vAlign'] ) ) {
		$json['verticalAlignment'] = $o['vAlign'];
		$cls[]                     = 'are-vertically-aligned-' . $o['vAlign'];
	}
	if ( ! empty( $o['align'] ) ) {
		$json['align'] = $o['align'];
		$cls[]         = 'align' . $o['align'];
	}
	$inner = implode( '', $cols );
	return "<!-- wp:columns" . bbd_attrs( $json ) . " -->\n<div class=\"" . implode( ' ', $cls ) . "\">{$inner}</div>\n<!-- /wp:columns -->";
}

/**
 * Single column.
 */
function bbd_column( $inner, $o = array() ) {
	$json  = array();
	$cls   = array( 'wp-block-column' );
	$style = array();

	if ( ! empty( $o['vAlign'] ) ) {
		$json['verticalAlignment'] = $o['vAlign'];
		$cls[]                     = 'is-vertically-aligned-' . $o['vAlign'];
	}
	if ( ! empty( $o['width'] ) ) {
		$json['width'] = $o['width'];
		$style[]       = 'flex-basis:' . $o['width'];
	}
	if ( ! empty( $o['bg'] ) ) {
		$json['backgroundColor'] = $o['bg'];
		$cls[]                   = 'has-' . $o['bg'] . '-background-color';
		$cls[]                   = 'has-background';
	}
	if ( ! empty( $o['text'] ) ) {
		$json['textColor'] = $o['text'];
		$cls[]             = 'has-' . $o['text'] . '-color';
		$cls[]             = 'has-text-color';
	}
	if ( ! empty( $o['pad'] ) ) {
		$json['style']['spacing']['padding'] = $o['pad'];
		foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
			if ( isset( $o['pad'][ $side ] ) ) {
				$style[] = 'padding-' . $side . ':' . $o['pad'][ $side ];
			}
		}
	}
	if ( ! empty( $o['radius'] ) ) {
		$json['style']['border']['radius'] = $o['radius'];
		$style[]                           = 'border-radius:' . $o['radius'];
	}
	$stylestr = $style ? ' style="' . implode( ';', $style ) . '"' : '';
	return "<!-- wp:column" . bbd_attrs( $json ) . " -->\n<div class=\"" . implode( ' ', $cls ) . "\"{$stylestr}>{$inner}</div>\n<!-- /wp:column -->";
}

/**
 * Spacer block.
 */
function bbd_spacer( $height = '48px' ) {
	return "<!-- wp:spacer" . bbd_attrs( array( 'height' => $height ) ) . " -->\n<div style=\"height:{$height}\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->";
}

/**
 * Separator block.
 */
function bbd_separator( $o = array() ) {
	$json = array();
	$cls  = array( 'wp-block-separator', 'has-alpha-channel-opacity' );
	if ( ! empty( $o['color'] ) ) {
		$json['backgroundColor'] = $o['color'];
		$json['className']       = 'is-style-wide';
		$cls[]                   = 'has-text-color';
		$cls[]                   = 'has-' . $o['color'] . '-color';
		$cls[]                   = 'has-alpha-channel-opacity';
		$cls[]                   = 'has-' . $o['color'] . '-background-color';
		$cls[]                   = 'has-background';
		$cls                     = array( 'wp-block-separator', 'has-text-color', 'has-' . $o['color'] . '-color', 'has-alpha-channel-opacity', 'has-' . $o['color'] . '-background-color', 'has-background', 'is-style-wide' );
	}
	return "<!-- wp:separator" . bbd_attrs( $json ) . " -->\n<hr class=\"" . implode( ' ', $cls ) . "\"/>\n<!-- /wp:separator -->";
}

/**
 * Unordered list block.
 */
function bbd_list( $items, $o = array() ) {
	$li = '';
	foreach ( $items as $item ) {
		$li .= "<!-- wp:list-item -->\n<li>{$item}</li>\n<!-- /wp:list-item -->\n";
	}
	$json = array();
	$cls  = array( 'wp-block-list' );
	if ( ! empty( $o['className'] ) ) {
		$json['className'] = $o['className'];
		$cls[]             = $o['className'];
	}
	return "<!-- wp:list" . bbd_attrs( $json ) . " -->\n<ul class=\"" . implode( ' ', $cls ) . "\">{$li}</ul>\n<!-- /wp:list -->";
}

/**
 * Quote block.
 */
function bbd_quote( $text, $cite = '' ) {
	$citemk = $cite ? "<cite>{$cite}</cite>" : '';
	return "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\">" . bbd_para( $text ) . $citemk . "</blockquote>\n<!-- /wp:quote -->";
}

/* =========================================================================
 * Reusable section patterns for the demo.
 * ====================================================================== */

/**
 * A section eyebrow + heading + intro, centered.
 */
function bbd_section_head( $eyebrow, $title, $intro = '', $o = array() ) {
	$color   = isset( $o['onDark'] ) && $o['onDark'] ? 'accent' : 'secondary';
	$tcolor  = isset( $o['onDark'] ) && $o['onDark'] ? 'cream' : '';
	$icolor  = isset( $o['onDark'] ) && $o['onDark'] ? 'cream' : 'muted';
	$out  = bbd_para( '<strong>' . $eyebrow . '</strong>', array( 'align' => 'center', 'color' => $color, 'size' => 'small' ) );
	$out .= bbd_heading( $title, 2, array_filter( array( 'align' => 'center', 'color' => $tcolor, 'fontSizeCustom' => 'clamp(1.9rem, 3.5vw, 2.75rem)' ) ) );
	if ( $intro ) {
		$out .= bbd_para( $intro, array( 'align' => 'center', 'color' => $icolor, 'size' => 'large' ) );
	}
	return $out;
}

/**
 * A feature card: image on top, heading, text — inside a white rounded column.
 */
function bbd_service_card( $img, $key, $title, $text, $link = '#' ) {
	$inner  = bbd_image( $img, $key, array( 'size' => 'medium', 'rounded' => false ) );
	$inner .= bbd_heading( $title, 3, array( 'fontSizeCustom' => '1.4rem' ) );
	$inner .= bbd_para( $text, array( 'color' => 'muted' ) );
	$inner .= bbd_para( '<a href="' . $link . '"><strong>Learn more →</strong></a>', array( 'color' => 'secondary' ) );
	return bbd_column(
		$inner,
		array(
			'bg'     => 'background',
			'pad'    => array( 'top' => '0', 'right' => '0', 'bottom' => '1.5rem', 'left' => '0' ),
			'radius' => '14px',
		)
	);
}

/**
 * A single stat: big gold number + label, for dark bands.
 */
function bbd_stat( $number, $label ) {
	$inner  = bbd_heading( $number, 2, array( 'align' => 'center', 'color' => 'accent', 'fontSizeCustom' => 'clamp(2.4rem, 5vw, 3.4rem)' ) );
	$inner .= bbd_para( $label, array( 'align' => 'center', 'color' => 'cream' ) );
	return bbd_column( $inner );
}

/**
 * A testimonial card.
 */
function bbd_testimonial_card( $img, $key, $quote, $name, $role ) {
	$inner  = bbd_para( '★★★★★', array( 'color' => 'accent' ) );
	$inner .= bbd_para( '“' . $quote . '”', array( 'size' => 'large' ) );
	$avatar = bbd_column( bbd_image( $img, $key, array( 'size' => 'thumbnail', 'rounded' => true ) ), array( 'width' => '64px' ) );
	$who    = bbd_column( bbd_para( '<strong>' . $name . '</strong><br>' . $role, array( 'size' => 'small', 'color' => 'muted' ) ), array( 'vAlign' => 'center' ) );
	$inner .= bbd_columns( array( $avatar, $who ), array( 'vAlign' => 'center' ) );
	return bbd_column(
		$inner,
		array(
			'bg'     => 'background',
			'pad'    => array( 'top' => '1.75rem', 'right' => '1.75rem', 'bottom' => '1.5rem', 'left' => '1.75rem' ),
			'radius' => '14px',
		)
	);
}

/**
 * A book cover with title/author caption.
 */
function bbd_book_card( $img, $key, $title, $author, $genre ) {
	$inner  = bbd_image( $img, $key, array( 'size' => 'medium' ) );
	$inner .= bbd_para( '<strong>' . $title . '</strong>', array( 'align' => 'center' ) );
	$meta   = $genre ? $author . ' · ' . $genre : $author;
	$inner .= bbd_para( $meta, array( 'align' => 'center', 'color' => 'muted', 'size' => 'small' ) );
	return bbd_column( $inner );
}

/* =========================================================================
 * The demo pages.
 * ====================================================================== */

/**
 * Build the full set of demo pages.
 *
 * Each entry: slug, title, front (bool), blog (bool for posts page),
 * template (optional), content (block markup string).
 *
 * @param array $img Imported image map.
 * @return array
 */
function blank_base_demo_pages( $img ) {
	$pages = array();

	/* ---------------------------------------------------------------- HOME */
	$home  = '';

	// Hero.
	$hero_inner  = bbd_para( '<strong>QUILL &amp; PRESS · BOOK PUBLISHING SERVICES</strong>', array( 'align' => 'center', 'color' => 'accent', 'size' => 'small' ) );
	$hero_inner .= bbd_heading( 'From manuscript to masterpiece', 1, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(2.4rem, 5.5vw, 4rem)', 'lineHeight' => '1.1' ) );
	$hero_inner .= bbd_para( 'We help authors edit, design, publish and market beautiful books that readers love — and bookstores stock. Full-service publishing, guided by people who love books.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) );
	$hero_inner .= bbd_buttons(
		array(
			bbd_button( 'Start your book', '/contact/', array( 'style' => 'fill' ) ),
			bbd_button( 'View our services', '/services/', array( 'style' => 'outline', 'color' => 'cream' ) ),
		),
		'center'
	);
	$home .= bbd_cover( $img, 'hero', $hero_inner, array( 'overlay' => 'ink', 'dim' => 60, 'minh' => 620 ) );

	// Trusted-by / intro strip.
	$home .= bbd_group(
		bbd_para( 'Trusted by <strong>1,200+ authors</strong> across fiction, non-fiction, children\'s and academic publishing — with books distributed to Amazon, Apple Books, Barnes &amp; Noble, and independent bookstores worldwide.', array( 'align' => 'center', 'color' => 'muted', 'size' => 'large' ) ),
		array( 'pad' => array( 'top' => '3rem', 'bottom' => '1rem' ) )
	);

	// Services grid.
	$svc_cards = bbd_columns(
		array(
			bbd_service_card( $img, 'service-editing', 'Editing &amp; Proofreading', 'Developmental, line and copy editing that sharpens your story while keeping your voice unmistakably yours.', '/services/' ),
			bbd_service_card( $img, 'service-design', 'Cover &amp; Interior Design', 'Award-worthy covers and typeset interiors designed to sell — in print and on every e-reader.', '/services/' ),
			bbd_service_card( $img, 'service-formatting', 'Formatting &amp; Typesetting', 'Print-ready files and reflowable eBooks, formatted to the exact specs of every retailer and printer.', '/services/' ),
		)
	);
	$svc_cards2 = bbd_columns(
		array(
			bbd_service_card( $img, 'service-marketing', 'Marketing &amp; Launch', 'Launch plans, ad campaigns and press outreach that put your book in front of the right readers.', '/services/' ),
			bbd_service_card( $img, 'service-distribution', 'Global Distribution', 'Wide distribution to 40,000+ retailers and libraries, plus print-on-demand in 100+ countries.', '/services/' ),
			bbd_service_card( $img, 'service-consulting', 'Author Consulting', 'One-to-one guidance on rights, royalties, pricing and building a career you can publish on.', '/services/' ),
		)
	);
	$home .= bbd_group(
		bbd_section_head( 'WHAT WE DO', 'Everything your book needs, under one roof', 'Pick a full publishing package or just the services you need. Either way, you keep 100% of your rights and royalties.' )
		. bbd_spacer( '20px' ) . $svc_cards . bbd_spacer( '24px' ) . $svc_cards2,
		array( 'bg' => 'cream', 'align' => 'full', 'pad' => array( 'top' => '4.5rem', 'bottom' => '5rem' ) )
	);

	// Stats band.
	$stats = bbd_columns(
		array(
			bbd_stat( '1,200+', 'Books published' ),
			bbd_stat( '30+', 'Bestseller lists' ),
			bbd_stat( '4.9/5', 'Author rating' ),
			bbd_stat( '100%', 'Rights kept by you' ),
		),
		array( 'stackMobile' => false )
	);
	$home .= bbd_cover( $img, 'cta', $stats, array( 'overlay' => 'ink', 'dim' => 80, 'minh' => 300 ) );

	// Featured books.
	$books = bbd_columns(
		array(
			bbd_book_card( $img, 'book-1', 'The Long Horizon', 'Maya Ellison', 'Literary Fiction' ),
			bbd_book_card( $img, 'book-2', 'Tidewater Songs', 'J. R. Harlow', 'Poetry' ),
			bbd_book_card( $img, 'book-3', 'Midnight Archive', 'L. Santoro', 'Mystery' ),
			bbd_book_card( $img, 'book-4', 'Rooted', 'Priya Nair', 'Memoir' ),
		),
		array( 'stackMobile' => false )
	);
	$home .= bbd_group(
		bbd_section_head( 'FROM OUR SHELVES', 'Books we\'re proud to have published', 'A few recent titles our team edited, designed and launched.' )
		. bbd_spacer( '24px' ) . $books . bbd_spacer( '28px' )
		. bbd_buttons( array( bbd_button( 'Browse the full catalog', '/books/', array( 'style' => 'fill' ) ) ), 'center' ),
		array( 'align' => 'full', 'pad' => array( 'top' => '4.5rem', 'bottom' => '5rem' ) )
	);

	// Process.
	$steps = bbd_columns(
		array(
			bbd_column( bbd_heading( '01', 3, array( 'color' => 'accent', 'fontSizeCustom' => '2rem' ) ) . bbd_heading( 'Plan', 4, array( 'fontSizeCustom' => '1.2rem' ) ) . bbd_para( 'A free consult to map your goals, timeline and the right package.', array( 'color' => 'muted' ) ) ),
			bbd_column( bbd_heading( '02', 3, array( 'color' => 'accent', 'fontSizeCustom' => '2rem' ) ) . bbd_heading( 'Perfect', 4, array( 'fontSizeCustom' => '1.2rem' ) ) . bbd_para( 'Editing, design and formatting until every page shines.', array( 'color' => 'muted' ) ) ),
			bbd_column( bbd_heading( '03', 3, array( 'color' => 'accent', 'fontSizeCustom' => '2rem' ) ) . bbd_heading( 'Publish', 4, array( 'fontSizeCustom' => '1.2rem' ) ) . bbd_para( 'We distribute worldwide in print, eBook and audio formats.', array( 'color' => 'muted' ) ) ),
			bbd_column( bbd_heading( '04', 3, array( 'color' => 'accent', 'fontSizeCustom' => '2rem' ) ) . bbd_heading( 'Promote', 4, array( 'fontSizeCustom' => '1.2rem' ) ) . bbd_para( 'A launch campaign that finds and grows your readership.', array( 'color' => 'muted' ) ) ),
		)
	);
	$home .= bbd_group(
		bbd_section_head( 'HOW IT WORKS', 'Four simple steps to a published book' ) . bbd_spacer( '24px' ) . $steps,
		array( 'bg' => 'cream', 'align' => 'full', 'pad' => array( 'top' => '4.5rem', 'bottom' => '5rem' ) )
	);

	// Testimonials.
	$tst = bbd_columns(
		array(
			bbd_testimonial_card( $img, 'avatar-1', 'Quill &amp; Press turned my messy draft into a book I\'m genuinely proud of. It hit the bestseller list in week one.', 'Maya Ellison', 'Author, The Long Horizon' ),
			bbd_testimonial_card( $img, 'avatar-2', 'The most professional, transparent team I\'ve worked with. I kept my rights and every royalty — and never felt alone.', 'J. R. Harlow', 'Poet, Tidewater Songs' ),
		)
	);
	$home .= bbd_group(
		bbd_section_head( 'AUTHOR STORIES', 'Loved by the authors we publish' ) . bbd_spacer( '24px' ) . $tst,
		array( 'align' => 'full', 'pad' => array( 'top' => '4.5rem', 'bottom' => '5rem' ) )
	);

	// CTA.
	$cta_inner  = bbd_heading( 'Your story deserves to be read', 2, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(2rem, 4vw, 3rem)' ) );
	$cta_inner .= bbd_para( 'Book a free, no-obligation consultation and get a custom publishing plan within 48 hours.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) );
	$cta_inner .= bbd_buttons( array( bbd_button( 'Get your free consultation', '/contact/', array( 'style' => 'fill' ) ) ), 'center' );
	$home .= bbd_cover( $img, 'cta', $cta_inner, array( 'overlay' => 'ink', 'dim' => 70, 'minh' => 420 ) );

	$pages['home'] = array(
		'slug'       => 'home',
		'title'      => 'Home',
		'front'      => true,
		'hide_title' => true,
		'content'    => $home,
	);

	/* --------------------------------------------------------------- ABOUT */
	$about  = bbd_cover(
		$img,
		'cta',
		bbd_heading( 'About Quill &amp; Press', 1, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(2.2rem, 5vw, 3.4rem)' ) )
		. bbd_para( 'A modern publishing partner for independent authors.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) ),
		array( 'overlay' => 'ink', 'dim' => 65, 'minh' => 360 )
	);
	$about .= bbd_group(
		bbd_columns(
			array(
				bbd_column( bbd_image( $img, 'about', array( 'size' => 'large', 'rounded' => false ) ), array( 'width' => '48%', 'vAlign' => 'center' ) ),
				bbd_column(
					bbd_para( '<strong>OUR STORY</strong>', array( 'color' => 'secondary', 'size' => 'small' ) )
					. bbd_heading( 'Publishing built around authors, not gatekeepers', 2, array( 'fontSizeCustom' => 'clamp(1.8rem, 3vw, 2.5rem)' ) )
					. bbd_para( 'Quill &amp; Press was founded in 2011 by a small group of editors and designers who were tired of watching great manuscripts get lost in slush piles. We believed authors deserved the craft of traditional publishing without giving up their rights, their royalties or their voice.', array( 'color' => 'muted' ) )
					. bbd_para( 'Today we\'re a full-service team of editors, designers, marketers and distribution specialists who have helped launch more than 1,200 titles — from debut novels to award-winning non-fiction. We treat every book as if it were our own.', array( 'color' => 'muted' ) )
					. bbd_buttons( array( bbd_button( 'Work with us', '/contact/', array( 'style' => 'fill' ) ) ), 'left' ),
					array( 'width' => '52%', 'vAlign' => 'center' )
				),
			),
			array( 'vAlign' => 'center' )
		),
		array( 'pad' => array( 'top' => '4.5rem', 'bottom' => '2rem' ) )
	);

	// Values.
	$values = bbd_columns(
		array(
			bbd_column( bbd_heading( 'Author-first', 3, array( 'fontSizeCustom' => '1.3rem' ) ) . bbd_para( 'You keep 100% of your rights and royalties. Always. We succeed only when you do.', array( 'color' => 'muted' ) ) ),
			bbd_column( bbd_heading( 'Craft over shortcuts', 3, array( 'fontSizeCustom' => '1.3rem' ) ) . bbd_para( 'Real human editors and designers — no templated, one-size-fits-all publishing here.', array( 'color' => 'muted' ) ) ),
			bbd_column( bbd_heading( 'Radical transparency', 3, array( 'fontSizeCustom' => '1.3rem' ) ) . bbd_para( 'Clear pricing, honest timelines and monthly sales reports you can actually understand.', array( 'color' => 'muted' ) ) ),
		)
	);
	$about .= bbd_group(
		bbd_section_head( 'WHAT WE BELIEVE', 'The values behind every book' ) . bbd_spacer( '20px' ) . $values,
		array( 'bg' => 'cream', 'align' => 'full', 'pad' => array( 'top' => '4.5rem', 'bottom' => '5rem' ) )
	);

	// Team.
	$team = bbd_columns(
		array(
			bbd_book_card( $img, 'avatar-3', 'Eleanor Vance', 'Founder &amp; Publisher', '' ),
			bbd_book_card( $img, 'avatar-4', 'Marcus Okonkwo', 'Editorial Director', '' ),
			bbd_book_card( $img, 'avatar-5', 'Sofia Reyes', 'Head of Design', '' ),
			bbd_book_card( $img, 'avatar-6', 'Daniel Kim', 'Marketing Lead', '' ),
		),
		array( 'stackMobile' => false )
	);
	// Replace the "author · genre" caption line intent: team uses name + role only,
	// bbd_book_card prints author + ' · ' + genre; pass role as author, '' genre.
	$about .= bbd_group(
		bbd_section_head( 'THE TEAM', 'The people in your corner' ) . bbd_spacer( '24px' ) . $team,
		array( 'align' => 'full', 'pad' => array( 'top' => '4.5rem', 'bottom' => '5rem' ) )
	);

	$pages['about'] = array( 'slug' => 'about', 'title' => 'About Us', 'hide_title' => true, 'content' => $about );

	/* ------------------------------------------------------------ SERVICES */
	$services  = bbd_cover(
		$img,
		'hero',
		bbd_heading( 'Publishing Services', 1, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(2.2rem, 5vw, 3.4rem)' ) )
		. bbd_para( 'Choose a complete package or à la carte — you\'re always in control.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) ),
		array( 'overlay' => 'ink', 'dim' => 62, 'minh' => 380 )
	);

	$service_rows = array(
		array( 'service-editing', 'Editing &amp; Proofreading', 'Great books are made in the edit. Our editors work in three passes — developmental, line and copy — plus a final proofread, so your manuscript is structurally sound, stylistically sharp and error-free.', array( 'Developmental &amp; structural editing', 'Line &amp; copy editing', 'Professional proofreading', 'Fact-checking &amp; style-sheet' ), false ),
		array( 'service-design', 'Cover &amp; Interior Design', 'A cover has one second to earn a click. We craft custom covers and typeset interiors that look at home on any bestseller shelf and read beautifully in print and on screen.', array( 'Custom cover design (3 concepts)', 'Print &amp; eBook interior layout', 'Typography &amp; chapter styling', 'Illustrations &amp; author photos' ), true ),
		array( 'service-formatting', 'Formatting &amp; Typesetting', 'Every retailer and printer wants files just so. We deliver print-ready PDFs and validated, reflowable eBooks that pass every store\'s technical review the first time.', array( 'Print-ready PDF (any trim size)', 'EPUB &amp; Kindle files', 'Retailer spec compliance', 'Large-print &amp; accessible editions' ), false ),
		array( 'service-marketing', 'Marketing &amp; Book Launch', 'Publishing is only half the job. We build a launch that finds readers — from pre-orders and ARC campaigns to advertising, press and a website that converts browsers into buyers.', array( 'Launch strategy &amp; timeline', 'Amazon &amp; Meta ad campaigns', 'ARC &amp; review outreach', 'Author website &amp; media kit' ), true ),
		array( 'service-distribution', 'Global Distribution', 'Get your book everywhere readers shop. We distribute to 40,000+ retailers and libraries and print on demand in more than 100 countries — with transparent, monthly royalty reporting.', array( 'Amazon, Apple, Kobo, B&amp;N', 'Library &amp; academic channels', 'Print-on-demand worldwide', 'Monthly sales &amp; royalty reports' ), false ),
		array( 'service-consulting', 'Author Consulting', 'Not sure where to start? Book time with a publishing strategist for candid, experienced advice on rights, pricing, series planning and building a long-term writing career.', array( 'Rights &amp; royalties guidance', 'Pricing &amp; series strategy', 'Self vs. hybrid publishing', 'Career &amp; backlist planning' ), true ),
	);

	$srv_body = '';
	foreach ( $service_rows as $i => $row ) {
		list( $key, $title, $desc, $items, $flip ) = $row;
		$text_col = bbd_column(
			bbd_heading( $title, 2, array( 'fontSizeCustom' => 'clamp(1.6rem, 3vw, 2.2rem)' ) )
			. bbd_para( $desc, array( 'color' => 'muted' ) )
			. bbd_list( $items, array( 'className' => 'bb-check-list' ) ),
			array( 'width' => '52%', 'vAlign' => 'center' )
		);
		$img_col = bbd_column( bbd_image( $img, $key, array( 'size' => 'large' ) ), array( 'width' => '48%', 'vAlign' => 'center' ) );
		$cols    = $flip ? array( $img_col, $text_col ) : array( $text_col, $img_col );
		$bg      = ( $i % 2 === 1 ) ? 'cream' : '';
		$srv_body .= bbd_group(
			bbd_columns( $cols, array( 'vAlign' => 'center' ) ),
			array_filter( array( 'align' => 'full', 'bg' => $bg, 'pad' => array( 'top' => '3.5rem', 'bottom' => '3.5rem' ) ) )
		);
	}
	$services .= $srv_body;

	// Pricing CTA.
	$services .= bbd_cover(
		$img,
		'cta',
		bbd_heading( 'Not sure which services you need?', 2, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(1.8rem, 3.5vw, 2.6rem)' ) )
		. bbd_para( 'Tell us about your book and we\'ll recommend the right path — no pressure, no jargon.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) )
		. bbd_buttons(
			array(
				bbd_button( 'See packages &amp; pricing', '/pricing/', array( 'style' => 'fill' ) ),
				bbd_button( 'Talk to us', '/contact/', array( 'style' => 'outline', 'color' => 'cream' ) ),
			),
			'center'
		),
		array( 'overlay' => 'ink', 'dim' => 72, 'minh' => 380 )
	);

	$pages['services'] = array( 'slug' => 'services', 'title' => 'Services', 'hide_title' => true, 'content' => $services );

	/* --------------------------------------------------------------- BOOKS */
	$books_page  = bbd_cover(
		$img,
		'cta',
		bbd_heading( 'Our Bookshelf', 1, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(2.2rem, 5vw, 3.4rem)' ) )
		. bbd_para( 'A selection of titles we\'ve edited, designed and launched into the world.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) ),
		array( 'overlay' => 'ink', 'dim' => 65, 'minh' => 360 )
	);
	$shelf1 = bbd_columns(
		array(
			bbd_book_card( $img, 'book-1', 'The Long Horizon', 'Maya Ellison', 'Literary Fiction' ),
			bbd_book_card( $img, 'book-2', 'Tidewater Songs', 'J. R. Harlow', 'Poetry' ),
			bbd_book_card( $img, 'book-3', 'Midnight Archive', 'L. Santoro', 'Mystery' ),
		)
	);
	$shelf2 = bbd_columns(
		array(
			bbd_book_card( $img, 'book-4', 'Rooted', 'Priya Nair', 'Memoir' ),
			bbd_book_card( $img, 'book-5', 'Paper Moons', 'D. Okonkwo', 'Fantasy' ),
			bbd_book_card( $img, 'book-6', 'Static Bloom', 'E. Vance', 'Sci-Fi' ),
		)
	);
	$books_page .= bbd_group(
		bbd_section_head( 'RECENT TITLES', 'Books from every genre' )
		. bbd_spacer( '24px' ) . $shelf1 . bbd_spacer( '20px' ) . $shelf2,
		array( 'align' => 'full', 'pad' => array( 'top' => '4.5rem', 'bottom' => '4rem' ) )
	);
	$books_page .= bbd_group(
		bbd_section_head( 'YOUR BOOK NEXT', 'Ready to see your title on this shelf?', 'Join more than 1,200 authors who\'ve published with Quill &amp; Press.' )
		. bbd_buttons( array( bbd_button( 'Start your book', '/contact/', array( 'style' => 'fill' ) ) ), 'center' ),
		array( 'bg' => 'cream', 'align' => 'full', 'pad' => array( 'top' => '4rem', 'bottom' => '4.5rem' ) )
	);
	$pages['books'] = array( 'slug' => 'books', 'title' => 'Our Books', 'hide_title' => true, 'content' => $books_page );

	/* ------------------------------------------------------------- PRICING */
	$pricing  = bbd_cover(
		$img,
		'hero',
		bbd_heading( 'Packages &amp; Pricing', 1, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(2.2rem, 5vw, 3.4rem)' ) )
		. bbd_para( 'Transparent, all-inclusive publishing packages. No hidden fees, no lost rights.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) ),
		array( 'overlay' => 'ink', 'dim' => 62, 'minh' => 380 )
	);

	$tier = function ( $name, $price, $blurb, $features, $featured = false ) {
		$inner  = bbd_para( '<strong>' . $name . '</strong>', array( 'align' => 'center', 'color' => $featured ? 'accent' : 'secondary' ) );
		$inner .= bbd_heading( $price, 2, array( 'align' => 'center', 'color' => $featured ? 'cream' : '', 'fontSizeCustom' => 'clamp(2.2rem, 4vw, 3rem)' ) );
		$inner .= bbd_para( $blurb, array( 'align' => 'center', 'color' => $featured ? 'cream' : 'muted', 'size' => 'small' ) );
		$inner .= bbd_separator( $featured ? array( 'color' => 'accent' ) : array() );
		$inner .= bbd_list( $features, array( 'className' => 'bb-check-list' ) );
		$inner .= bbd_spacer( '12px' );
		$inner .= bbd_buttons( array( bbd_button( 'Choose ' . $name, '/contact/', array( 'style' => $featured ? 'fill' : 'outline', 'color' => $featured ? 'ink' : 'ink' ) ) ), 'center' );
		return bbd_column(
			$inner,
			array(
				'bg'     => $featured ? 'ink' : 'background',
				'text'   => $featured ? 'cream' : '',
				'pad'    => array( 'top' => '2.25rem', 'right' => '1.75rem', 'bottom' => '2rem', 'left' => '1.75rem' ),
				'radius' => '16px',
			)
		);
	};

	$tiers = bbd_columns(
		array(
			$tier( 'Essential', '$1,499', 'For authors who want a professional foundation.', array( 'Copy editing &amp; proofread', 'Custom eBook cover', 'eBook formatting (EPUB + Kindle)', 'Distribution to major eBook stores', 'Monthly royalty reports' ) ),
			$tier( 'Complete', '$3,999', 'Our most popular full-service package.', array( 'Everything in Essential', 'Developmental + line editing', 'Print &amp; eBook cover design', 'Print-ready formatting', 'Global print &amp; eBook distribution', 'Launch marketing plan' ), true ),
			$tier( 'Bestseller', '$7,499', 'For authors going all-in on their launch.', array( 'Everything in Complete', 'Ad campaign management', 'Publicity &amp; review outreach', 'Author website &amp; media kit', 'Audiobook production', 'Dedicated publishing manager' ) ),
		),
		array( 'stackMobile' => false )
	);
	$pricing .= bbd_group(
		bbd_section_head( 'CHOOSE YOUR PATH', 'Simple packages, no surprises', 'Every package keeps 100% of your rights and royalties. Need something custom? Just ask.' )
		. bbd_spacer( '24px' ) . $tiers . bbd_spacer( '16px' )
		. bbd_para( 'Prefer to pay as you go? All services are also available à la carte. <a href="/services/"><strong>See individual services →</strong></a>', array( 'align' => 'center', 'color' => 'muted' ) ),
		array( 'align' => 'full', 'pad' => array( 'top' => '4.5rem', 'bottom' => '5rem' ) )
	);

	// FAQ teaser + CTA on pricing.
	$pricing .= bbd_group(
		bbd_section_head( 'STILL DECIDING?', 'We\'ll help you choose' )
		. bbd_buttons(
			array(
				bbd_button( 'Read the FAQ', '/faq/', array( 'style' => 'outline', 'color' => 'ink' ) ),
				bbd_button( 'Book a free call', '/contact/', array( 'style' => 'fill' ) ),
			),
			'center'
		),
		array( 'bg' => 'cream', 'align' => 'full', 'pad' => array( 'top' => '4rem', 'bottom' => '4.5rem' ) )
	);

	$pages['pricing'] = array( 'slug' => 'pricing', 'title' => 'Pricing', 'hide_title' => true, 'content' => $pricing );

	/* ----------------------------------------------------------------- FAQ */
	$faqs = array(
		array( 'Do I keep the rights to my book?', 'Absolutely. Unlike traditional publishers, Quill &amp; Press never takes your rights. You own your work, your ISBN options and 100% of your royalties — we simply provide the services to publish it professionally.' ),
		array( 'How long does the publishing process take?', 'Most books move from manuscript to published in 12–16 weeks, depending on the package and the amount of editing needed. During your free consultation we\'ll build a realistic timeline around your goals.' ),
		array( 'Where will my book be sold?', 'Everywhere readers shop. We distribute to Amazon, Apple Books, Kobo, Barnes &amp; Noble, Google Play and 40,000+ retailers and libraries, with print-on-demand in over 100 countries.' ),
		array( 'What formats do you produce?', 'Print (paperback and hardcover), reflowable eBooks (EPUB and Kindle), and optional audiobooks. Accessible and large-print editions are available too.' ),
		array( 'Can I buy just one service?', 'Yes. Every service — editing, design, formatting, marketing, distribution and consulting — is available à la carte. Many authors start with editing or a cover and add more later.' ),
		array( 'How are royalties paid?', 'Retailers pay us, we report every sale in a clear monthly dashboard, and we pass 100% of your royalties to you. There are no ongoing commissions on your sales.' ),
	);
	$faq_body = '';
	foreach ( $faqs as $qa ) {
		$faq_body .= bbd_group(
			bbd_heading( $qa[0], 3, array( 'fontSizeCustom' => '1.3rem' ) ) . bbd_para( $qa[1], array( 'color' => 'muted' ) ),
			array( 'bg' => 'cream', 'pad' => array( 'top' => '1.5rem', 'right' => '1.75rem', 'bottom' => '1.25rem', 'left' => '1.75rem' ), 'radius' => '12px' )
		) . bbd_spacer( '16px' );
	}
	$faq  = bbd_cover(
		$img,
		'cta',
		bbd_heading( 'Frequently Asked Questions', 1, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(2rem, 4.5vw, 3.2rem)' ) )
		. bbd_para( 'Everything you need to know before you publish with us.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) ),
		array( 'overlay' => 'ink', 'dim' => 65, 'minh' => 340 )
	);
	$faq .= bbd_group(
		$faq_body . bbd_spacer( '8px' )
		. bbd_para( 'Still have a question? <a href="/contact/"><strong>Get in touch →</strong></a>', array( 'align' => 'center', 'color' => 'secondary', 'size' => 'large' ) ),
		array( 'pad' => array( 'top' => '4rem', 'bottom' => '4.5rem' ), 'contentSize' => '48rem' )
	);
	$pages['faq'] = array( 'slug' => 'faq', 'title' => 'FAQ', 'hide_title' => true, 'content' => $faq );

	/* ------------------------------------------------------------- CONTACT */
	$contact  = bbd_cover(
		$img,
		'hero',
		bbd_heading( 'Let\'s publish your book', 1, array( 'align' => 'center', 'color' => 'cream', 'fontSizeCustom' => 'clamp(2.2rem, 5vw, 3.4rem)' ) )
		. bbd_para( 'Tell us about your project and get a custom plan within 48 hours.', array( 'align' => 'center', 'color' => 'cream', 'size' => 'large' ) ),
		array( 'overlay' => 'ink', 'dim' => 62, 'minh' => 360 )
	);

	$contact_form = '<!-- wp:html -->' . "\n" . blank_base_demo_contact_form_html() . "\n" . '<!-- /wp:html -->';

	$info_col = bbd_column(
		bbd_heading( 'Talk to a real publisher', 2, array( 'fontSizeCustom' => '1.8rem' ) )
		. bbd_para( 'No call centres, no bots. Reach the team directly and we\'ll reply within one business day.', array( 'color' => 'muted' ) )
		. bbd_para( '<strong>Email</strong><br>hello@quillandpress.example', array() )
		. bbd_para( '<strong>Phone</strong><br>+1 (555) 018-2264', array() )
		. bbd_para( '<strong>Studio</strong><br>114 Compositor Lane<br>Portland, OR 97204', array() )
		. bbd_para( '<strong>Hours</strong><br>Mon–Fri, 9am–6pm PT', array() ),
		array( 'width' => '40%' )
	);
	$form_col = bbd_column( $contact_form, array( 'width' => '60%' ) );
	$contact .= bbd_group(
		bbd_columns( array( $info_col, $form_col ), array( 'vAlign' => 'top' ) ),
		array( 'pad' => array( 'top' => '4.5rem', 'bottom' => '5rem' ), 'contentSize' => '60rem' )
	);
	$pages['contact'] = array( 'slug' => 'contact', 'title' => 'Contact', 'hide_title' => true, 'content' => $contact );

	/* ---------------------------------------------------------------- BLOG */
	$pages['blog'] = array(
		'slug'    => 'blog',
		'title'   => 'Journal',
		'blog'    => true,
		'content' => bbd_para( 'Tips, guides and stories from the world of independent book publishing.', array( 'align' => 'center', 'color' => 'muted', 'size' => 'large' ) ),
	);

	return $pages;
}

/**
 * A styled, self-contained HTML contact form for the demo (no plugin needed).
 *
 * @return string
 */
function blank_base_demo_contact_form_html() {
	return '<form class="bb-demo-form" method="post" action="#" onsubmit="return false;">'
		. '<div class="bb-demo-form__row"><label>Your name<input type="text" name="name" placeholder="Jane Author"></label></div>'
		. '<div class="bb-demo-form__row"><label>Email<input type="email" name="email" placeholder="jane@example.com"></label></div>'
		. '<div class="bb-demo-form__row"><label>Book title<input type="text" name="title" placeholder="My Untitled Manuscript"></label></div>'
		. '<div class="bb-demo-form__row"><label>Genre'
		. '<select name="genre"><option>Fiction</option><option>Non-fiction</option><option>Poetry</option><option>Children\'s</option><option>Memoir</option><option>Academic</option></select></label></div>'
		. '<div class="bb-demo-form__row"><label>Tell us about your book<textarea name="message" rows="5" placeholder="Where are you in the process, and how can we help?"></textarea></label></div>'
		. '<button type="submit" class="bb-demo-form__submit">Request my free plan</button>'
		. '<p class="bb-demo-form__note">This is a demo form. Connect your favourite forms plugin to receive submissions.</p>'
		. '</form>';
}

/**
 * Build the demo blog posts.
 *
 * @param array $img Imported image map.
 * @return array
 */
function blank_base_demo_posts( $img ) {
	$posts = array();

	$p1  = bbd_para( 'Your cover is the single most important marketing asset your book has. Before a reader opens to page one — before they even read the blurb — the cover has already made a promise about genre, tone and quality. Here\'s how to make that promise count.', array( 'size' => 'large' ) );
	$p1 .= bbd_heading( 'Design for the thumbnail first', 2 );
	$p1 .= bbd_para( 'The majority of books are now discovered as postage-stamp-sized thumbnails on a phone screen. If your title isn\'t legible at that size, the cover isn\'t working. Bold type, strong contrast and a single focal image almost always beat a busy, detailed illustration.', array() );
	$p1 .= bbd_heading( 'Signal the genre instantly', 2 );
	$p1 .= bbd_para( 'Readers browse by genre conventions. A thriller cover and a cozy romance cover speak completely different visual languages. Study the current bestsellers in your category and design something that belongs on the same shelf — then find one distinctive element to stand out.', array() );
	$p1 .= bbd_list( array( 'Use no more than two typefaces.', 'Keep the author name legible but secondary (unless you\'re a brand-name author).', 'Test the cover in greyscale — if it still reads, your contrast is strong.' ) );
	$p1 .= bbd_quote( 'A great cover doesn\'t just decorate a book — it sells it before a single word is read.', 'Sofia Reyes, Head of Design' );
	$posts[] = array(
		'title'   => '7 things every bestselling book cover gets right',
		'slug'    => 'bestselling-book-cover',
		'excerpt' => 'Your cover has one second to earn a reader\'s attention. Here\'s how the best covers make that second count.',
		'image'   => 'blog-1',
		'cats'    => array( 'Design' ),
		'content' => $p1,
	);

	$p2  = bbd_para( 'The line between self-publishing and traditional publishing has blurred into a spectrum. Knowing where you fit on it is the difference between a book that quietly disappears and one that finds its readers.', array( 'size' => 'large' ) );
	$p2 .= bbd_heading( 'Self-publishing', 2 );
	$p2 .= bbd_para( 'You control everything and keep all royalties — but you also shoulder every cost and decision, from hiring editors to buying ads. It rewards authors who are willing to run their book like a small business.', array() );
	$p2 .= bbd_heading( 'Traditional publishing', 2 );
	$p2 .= bbd_para( 'A publisher covers costs and lends prestige and bookstore reach, but you give up rights, creative control and the large majority of your royalties — and you may wait years for a deal that never comes.', array() );
	$p2 .= bbd_heading( 'The hybrid path', 2 );
	$p2 .= bbd_para( 'Hybrid publishing — the model we champion at Quill &amp; Press — combines the craft and distribution of traditional publishing with the speed, control and royalties of self-publishing. You invest in professional services, and you keep your rights.', array() );
	$posts[] = array(
		'title'   => 'Self, traditional or hybrid: which publishing path is right for you?',
		'slug'    => 'publishing-paths-compared',
		'excerpt' => 'Three routes to a published book, and an honest look at what each one really costs you.',
		'image'   => 'blog-2',
		'cats'    => array( 'Publishing' ),
		'content' => $p2,
	);

	$p3  = bbd_para( 'A book launch isn\'t a single day — it\'s a 90-day campaign that starts long before your release date. Here\'s the timeline our marketing team uses for every title.', array( 'size' => 'large' ) );
	$p3 .= bbd_heading( '8 weeks out: build the foundation', 2 );
	$p3 .= bbd_para( 'Finalise your cover, set up your author website, and open pre-orders. Start collecting email subscribers with a free sample chapter — your launch-day buyers will come from this list.', array() );
	$p3 .= bbd_heading( '4 weeks out: rally your readers', 2 );
	$p3 .= bbd_para( 'Send advance review copies to reviewers and early readers. A book with 20+ honest reviews on launch day converts dramatically better than one with none.', array() );
	$p3 .= bbd_heading( 'Launch week: concentrate the fire', 2 );
	$p3 .= bbd_para( 'Cluster your emails, ads and social posts into a tight window. Retailers reward concentrated sales with visibility, which drives further organic sales — the flywheel every author wants.', array() );
	$p3 .= bbd_list( array( 'Line up your reviews before launch, not after.', 'Pick one primary retailer to focus your rankings.', 'Keep advertising for 30 days post-launch to sustain momentum.' ) );
	$posts[] = array(
		'title'   => 'The 90-day book launch plan that actually works',
		'slug'    => 'book-launch-plan',
		'excerpt' => 'A week-by-week marketing timeline to turn your release date into real, lasting sales.',
		'image'   => 'blog-3',
		'cats'    => array( 'Marketing' ),
		'content' => $p3,
	);

	return $posts;
}
