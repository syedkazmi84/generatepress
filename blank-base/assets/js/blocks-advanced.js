/**
 * Advanced Blank Base blocks (no-build): Tabs, Accordion, Image Slider,
 * Content Slider, Testimonial Carousel, Post Carousel and Post Slider.
 *
 * Interactive behaviour lives in assets/js/blocks-interactive.js on the front
 * end. Dynamic blocks (post carousel/slider) are server-rendered — see
 * inc/blocks-advanced.php.
 *
 * @package Blank_Base
 */
( function ( blocks, blockEditor, element, components, i18n, serverSideRender ) {
	'use strict';

	var el = element.createElement;
	var Fragment = element.Fragment;
	var __ = i18n.__;
	var registerBlockType = blocks.registerBlockType;
	var getBlockType = blocks.getBlockType;
	var unregisterBlockType = blocks.unregisterBlockType;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var InnerBlocks = blockEditor.InnerBlocks;
	var RichText = blockEditor.RichText;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var PanelBody = components.PanelBody;
	var RangeControl = components.RangeControl;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;
	var TextControl = components.TextControl;
	var Button = components.Button;
	var ServerSideRender = serverSideRender;

	var CATEGORY = 'blank-base';

	/* ---- Shared carousel option attributes / controls / data props. ---- */
	function carouselAttrs( defaults ) {
		defaults = defaults || {};
		return {
			perView: { type: 'number', default: defaults.perView || 1 },
			perViewTablet: { type: 'number', default: defaults.perViewTablet || 0 },
			autoplay: { type: 'number', default: 0 },
			arrows: { type: 'boolean', default: true },
			dots: { type: 'boolean', default: true },
		};
	}

	function carouselData( a ) {
		return {
			'data-per-view': String( a.perView || 1 ),
			'data-per-view-tablet': a.perViewTablet ? String( a.perViewTablet ) : '',
			'data-autoplay': String( a.autoplay || 0 ),
			'data-arrows': a.arrows ? '1' : '0',
			'data-dots': a.dots ? '1' : '0',
		};
	}

	function carouselPanel( a, set ) {
		return el(
			PanelBody,
			{ title: __( 'Carousel', 'blank-base' ), initialOpen: true },
			el( RangeControl, {
				label: __( 'Slides per view (desktop)', 'blank-base' ),
				value: a.perView,
				onChange: function ( v ) {
					set( { perView: v || 1 } );
				},
				min: 1,
				max: 6,
				step: 1,
			} ),
			el( RangeControl, {
				label: __( 'Slides per view (tablet, 0 = auto)', 'blank-base' ),
				value: a.perViewTablet,
				onChange: function ( v ) {
					set( { perViewTablet: v || 0 } );
				},
				min: 0,
				max: 4,
				step: 1,
			} ),
			el( RangeControl, {
				label: __( 'Autoplay (ms, 0 = off)', 'blank-base' ),
				value: a.autoplay,
				onChange: function ( v ) {
					set( { autoplay: v || 0 } );
				},
				min: 0,
				max: 8000,
				step: 500,
			} ),
			el( ToggleControl, {
				label: __( 'Show arrows', 'blank-base' ),
				checked: a.arrows,
				onChange: function ( v ) {
					set( { arrows: v } );
				},
			} ),
			el( ToggleControl, {
				label: __( 'Show dots', 'blank-base' ),
				checked: a.dots,
				onChange: function ( v ) {
					set( { dots: v } );
				},
			} )
		);
	}

	/* ==================================================================
	 * Tabs (parent) + Tab (child).
	 * ================================================================ */
	registerBlockType( 'blank-base/tabs', {
		apiVersion: 2,
		title: __( 'Tabs', 'blank-base' ),
		description: __( 'Tabbed content. Add a Tab for each panel.', 'blank-base' ),
		category: CATEGORY,
		icon: 'index-card',
		supports: { html: false, align: [ 'wide' ] },
		edit: function () {
			var blockProps = useBlockProps( { className: 'bb-tabs bb-tabs--editing' } );
			return el(
				'div',
				blockProps,
				el( InnerBlocks, {
					allowedBlocks: [ 'blank-base/tab' ],
					template: [ [ 'blank-base/tab' ], [ 'blank-base/tab' ] ],
					renderAppender: InnerBlocks.ButtonBlockAppender,
				} )
			);
		},
		save: function () {
			var blockProps = useBlockProps.save( { className: 'bb-tabs' } );
			return el(
				'div',
				blockProps,
				el( 'div', { className: 'bb-tabs__panels' }, el( InnerBlocks.Content ) )
			);
		},
	} );

	registerBlockType( 'blank-base/tab', {
		apiVersion: 2,
		title: __( 'Tab', 'blank-base' ),
		description: __( 'A single tab panel.', 'blank-base' ),
		category: CATEGORY,
		icon: 'index-card',
		parent: [ 'blank-base/tabs' ],
		supports: { html: false, reusable: false },
		attributes: {
			title: { type: 'string', default: __( 'Tab', 'blank-base' ) },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-tab bb-tab--editing' } );
			return el(
				'div',
				blockProps,
				el( RichText, {
					tagName: 'span',
					className: 'bb-tab__label',
					value: a.title,
					allowedFormats: [],
					onChange: function ( v ) {
						set( { title: v } );
					},
					placeholder: __( 'Tab title', 'blank-base' ),
				} ),
				el( 'div', { className: 'bb-tab__content' }, el( InnerBlocks, {} ) )
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( {
				className: 'bb-tab',
				'data-bb-title': a.title,
			} );
			return el( 'div', blockProps, el( InnerBlocks.Content ) );
		},
	} );

	/* ==================================================================
	 * Accordion (parent) + Accordion Item (child).
	 * ================================================================ */
	registerBlockType( 'blank-base/accordion', {
		apiVersion: 2,
		title: __( 'Accordion', 'blank-base' ),
		description: __( 'Collapsible sections. Add an Accordion Item for each.', 'blank-base' ),
		category: CATEGORY,
		icon: 'menu',
		supports: { html: false, align: [ 'wide' ] },
		attributes: {
			single: { type: 'boolean', default: false },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-accordion bb-accordion--editing' } );
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Accordion', 'blank-base' ), initialOpen: true },
						el( ToggleControl, {
							label: __( 'Close others when opening one', 'blank-base' ),
							checked: a.single,
							onChange: function ( v ) {
								set( { single: v } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el( InnerBlocks, {
						allowedBlocks: [ 'blank-base/accordion-item' ],
						template: [ [ 'blank-base/accordion-item' ], [ 'blank-base/accordion-item' ] ],
						renderAppender: InnerBlocks.ButtonBlockAppender,
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( {
				className: 'bb-accordion',
				'data-single': a.single ? '1' : '0',
			} );
			return el( 'div', blockProps, el( InnerBlocks.Content ) );
		},
	} );

	registerBlockType( 'blank-base/accordion-item', {
		apiVersion: 2,
		title: __( 'Accordion Item', 'blank-base' ),
		category: CATEGORY,
		icon: 'menu',
		parent: [ 'blank-base/accordion' ],
		supports: { html: false, reusable: false },
		attributes: {
			title: { type: 'string', default: __( 'Section title', 'blank-base' ) },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-accordion__item is-open bb-accordion__item--editing' } );
			return el(
				'div',
				blockProps,
				el(
					'div',
					{ className: 'bb-accordion__header' },
					el( RichText, {
						tagName: 'span',
						className: 'bb-accordion__title',
						value: a.title,
						allowedFormats: [],
						onChange: function ( v ) {
							set( { title: v } );
						},
						placeholder: __( 'Section title', 'blank-base' ),
					} )
				),
				el( 'div', { className: 'bb-accordion__panel' }, el( InnerBlocks, {} ) )
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( { className: 'bb-accordion__item' } );
			return el(
				'div',
				blockProps,
				el(
					'button',
					{ className: 'bb-accordion__header', type: 'button', 'aria-expanded': 'false' },
					el( RichText.Content, { tagName: 'span', className: 'bb-accordion__title', value: a.title } ),
					el( 'span', { className: 'bb-accordion__marker', 'aria-hidden': 'true' } )
				),
				el( 'div', { className: 'bb-accordion__panel' }, el( InnerBlocks.Content ) )
			);
		},
	} );

	/* ==================================================================
	 * Image Slider.
	 * ================================================================ */
	registerBlockType( 'blank-base/image-slider', {
		apiVersion: 2,
		title: __( 'Image Slider', 'blank-base' ),
		description: __( 'A swipeable slider of images.', 'blank-base' ),
		category: CATEGORY,
		icon: 'images-alt2',
		supports: { html: false, align: [ 'wide', 'full' ] },
		attributes: Object.assign(
			{ images: { type: 'array', default: [] } },
			carouselAttrs( { perView: 1 } )
		),
		edit: function ( props ) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-image-slider-edit' } );
			return el(
				Fragment,
				null,
				el( InspectorControls, null, carouselPanel( a, set ) ),
				el(
					'div',
					blockProps,
					el(
						MediaUploadCheck,
						null,
						el( MediaUpload, {
							allowedTypes: [ 'image' ],
							multiple: true,
							gallery: true,
							value: a.images.map( function ( i ) {
								return i.id;
							} ),
							onSelect: function ( media ) {
								set( {
									images: media.map( function ( m ) {
										return { id: m.id, url: m.url, alt: m.alt || '' };
									} ),
								} );
							},
							render: function ( o ) {
								return el(
									'div',
									{ className: 'bb-image-slider-edit__inner' },
									a.images.length
										? el(
												'div',
												{ className: 'bb-image-slider-edit__grid' },
												a.images.map( function ( img ) {
													return el( 'img', { key: img.id, src: img.url, alt: img.alt || '' } );
												} )
										  )
										: el( 'p', null, __( 'No images selected yet.', 'blank-base' ) ),
									el(
										Button,
										{ variant: 'secondary', onClick: o.open },
										a.images.length
											? __( 'Edit images', 'blank-base' )
											: __( 'Select images', 'blank-base' )
									)
								);
							},
						} )
					)
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save(
				Object.assign( { className: 'bb-carousel bb-image-slider' }, carouselData( a ) )
			);
			return el(
				'div',
				blockProps,
				el(
					'div',
					{ className: 'bb-carousel__track' },
					a.images.map( function ( img ) {
						return el(
							'figure',
							{ key: img.id, className: 'bb-carousel__slide' },
							el( 'img', { src: img.url, alt: img.alt || '' } )
						);
					} )
				)
			);
		},
	} );

	/* ==================================================================
	 * Content Slider & Testimonial Carousel — carousels of inner blocks.
	 * ================================================================ */
	function innerCarousel( name, opts ) {
		registerBlockType( name, {
			apiVersion: 2,
			title: opts.title,
			description: opts.description,
			category: CATEGORY,
			icon: opts.icon,
			supports: { html: false, align: [ 'wide', 'full' ] },
			attributes: carouselAttrs( { perView: opts.perView || 1 } ),
			edit: function ( props ) {
				var a = props.attributes;
				var set = props.setAttributes;
				var blockProps = useBlockProps( { className: 'bb-carousel bb-carousel--editing' } );
				return el(
					Fragment,
					null,
					el( InspectorControls, null, carouselPanel( a, set ) ),
					el(
						'div',
						blockProps,
						el( 'div', { className: 'bb-carousel__track' }, el( InnerBlocks, {
							allowedBlocks: opts.allowedBlocks,
							template: opts.template,
							renderAppender: InnerBlocks.ButtonBlockAppender,
						} ) )
					)
				);
			},
			save: function ( props ) {
				var a = props.attributes;
				var blockProps = useBlockProps.save(
					Object.assign( { className: 'bb-carousel' }, carouselData( a ) )
				);
				return el(
					'div',
					blockProps,
					el( 'div', { className: 'bb-carousel__track' }, el( InnerBlocks.Content ) )
				);
			},
		} );
	}

	innerCarousel( 'blank-base/content-slider', {
		title: __( 'Content Slider', 'blank-base' ),
		description: __( 'A carousel where each inner block is a slide.', 'blank-base' ),
		icon: 'slides',
		perView: 1,
		allowedBlocks: null,
		template: [ [ 'core/paragraph', { placeholder: __( 'Slide content…', 'blank-base' ) } ] ],
	} );

	innerCarousel( 'blank-base/testimonial-carousel', {
		title: __( 'Testimonial Carousel', 'blank-base' ),
		description: __( 'A carousel of Testimonial blocks.', 'blank-base' ),
		icon: 'format-quote',
		perView: 1,
		allowedBlocks: [ 'blank-base/testimonial' ],
		template: [ [ 'blank-base/testimonial' ], [ 'blank-base/testimonial' ] ],
	} );

	/* ==================================================================
	 * Post Carousel & Post Slider — dynamic (server-rendered).
	 * ================================================================ */
	function postBlock( name, opts ) {
		// These blocks are also registered server-side (for render_callback) and
		// hydrated into the editor without an edit function. Replace that stub so
		// our edit/inspector wins, regardless of registration order.
		if ( getBlockType && getBlockType( name ) ) {
			unregisterBlockType( name );
		}
		registerBlockType( name, {
			apiVersion: 2,
			title: opts.title,
			description: opts.description,
			category: CATEGORY,
			icon: opts.icon,
			supports: { html: false, align: [ 'wide', 'full' ] },
			attributes: {
				postsToShow: { type: 'number', default: 6 },
				columns: { type: 'number', default: opts.columns },
				order: { type: 'string', default: 'date' },
				category: { type: 'number', default: 0 },
				showImage: { type: 'boolean', default: true },
				showExcerpt: { type: 'boolean', default: true },
				autoplay: { type: 'number', default: 0 },
				arrows: { type: 'boolean', default: true },
				dots: { type: 'boolean', default: true },
			},
			edit: function ( props ) {
				var a = props.attributes;
				var set = props.setAttributes;
				return el(
					Fragment,
					null,
					el(
						InspectorControls,
						null,
						el(
							PanelBody,
							{ title: __( 'Query', 'blank-base' ), initialOpen: true },
							el( RangeControl, {
								label: __( 'Number of posts', 'blank-base' ),
								value: a.postsToShow,
								onChange: function ( v ) {
									set( { postsToShow: v || 1 } );
								},
								min: 1,
								max: 20,
							} ),
							el( RangeControl, {
								label: __( 'Slides per view', 'blank-base' ),
								value: a.columns,
								onChange: function ( v ) {
									set( { columns: v || 1 } );
								},
								min: 1,
								max: 4,
							} ),
							el( SelectControl, {
								label: __( 'Order by', 'blank-base' ),
								value: a.order,
								options: [
									{ label: __( 'Newest', 'blank-base' ), value: 'date' },
									{ label: __( 'Title', 'blank-base' ), value: 'title' },
									{ label: __( 'Random', 'blank-base' ), value: 'rand' },
								],
								onChange: function ( v ) {
									set( { order: v } );
								},
							} ),
							el( TextControl, {
								label: __( 'Category ID (0 = all)', 'blank-base' ),
								type: 'number',
								value: a.category,
								onChange: function ( v ) {
									set( { category: parseInt( v, 10 ) || 0 } );
								},
							} ),
							el( ToggleControl, {
								label: __( 'Show featured image', 'blank-base' ),
								checked: a.showImage,
								onChange: function ( v ) {
									set( { showImage: v } );
								},
							} ),
							el( ToggleControl, {
								label: __( 'Show excerpt', 'blank-base' ),
								checked: a.showExcerpt,
								onChange: function ( v ) {
									set( { showExcerpt: v } );
								},
							} )
						),
						el(
							PanelBody,
							{ title: __( 'Carousel', 'blank-base' ), initialOpen: false },
							el( RangeControl, {
								label: __( 'Autoplay (ms, 0 = off)', 'blank-base' ),
								value: a.autoplay,
								onChange: function ( v ) {
									set( { autoplay: v || 0 } );
								},
								min: 0,
								max: 8000,
								step: 500,
							} ),
							el( ToggleControl, {
								label: __( 'Show arrows', 'blank-base' ),
								checked: a.arrows,
								onChange: function ( v ) {
									set( { arrows: v } );
								},
							} ),
							el( ToggleControl, {
								label: __( 'Show dots', 'blank-base' ),
								checked: a.dots,
								onChange: function ( v ) {
									set( { dots: v } );
								},
							} )
						)
					),
					el(
						'div',
						useBlockProps(),
						ServerSideRender
							? el( ServerSideRender, {
									block: name,
									// Send only the attributes the server block registered.
									// Passing the shared align / Design & Motion attributes
									// would make the REST renderer reject the request with
									// "Invalid parameter(s): attributes".
									attributes: {
										postsToShow: a.postsToShow,
										columns: a.columns,
										order: a.order,
										category: a.category,
										showImage: a.showImage,
										showExcerpt: a.showExcerpt,
										autoplay: a.autoplay,
										arrows: a.arrows,
										dots: a.dots,
									},
							  } )
							: el( 'p', null, __( 'Post block preview loads on the front end.', 'blank-base' ) )
					)
				);
			},
			save: function () {
				return null;
			},
		} );
	}

	postBlock( 'blank-base/post-carousel', {
		title: __( 'Post Carousel', 'blank-base' ),
		description: __(
			'A carousel of recent posts. Set "Slides per view" to 1 for a full-width slider.',
			'blank-base'
		),
		icon: 'slides',
		columns: 3,
	} );

	// Post Slider was merged into Post Carousel (use Slides per view = 1). It is
	// no longer offered in the inserter; its server render is kept for legacy
	// content in inc/blocks-advanced.php.
}( window.wp.blocks, window.wp.blockEditor, window.wp.element, window.wp.components, window.wp.i18n, window.wp.serverSideRender ) );
