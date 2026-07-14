/**
 * Blank Base custom blocks (no-build).
 *
 * Authored in plain JavaScript against the wp.* globals so the theme needs no
 * webpack/npm build step. Every block is grouped under the theme's own
 * inserter category ("blank-base") so they appear together under your brand.
 *
 * The Counter and Progress Bar blocks output the same .bb-counter / .bb-skill
 * markup that assets/js/theme.js animates on the front end, so the count-up
 * and bar-fill animations work automatically.
 *
 * @package Blank_Base
 */
( function ( blocks, blockEditor, element, components, i18n, hooks ) {
	'use strict';

	var el = element.createElement;
	var Fragment = element.Fragment;
	var __ = i18n.__;
	var registerBlockType = blocks.registerBlockType;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var RichText = blockEditor.RichText;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var RangeControl = components.RangeControl;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;
	var Button = components.Button;
	var DateTimePicker = components.DateTimePicker;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var URLInputButton = blockEditor.URLInputButton;
	var ColorPalette = blockEditor.ColorPalette;

	var CATEGORY = 'blank-base';

	/**
	 * Small helper: an image chooser (media library button + preview) used by
	 * blocks that carry an image.
	 *
	 * @param {Object}   a             Attributes ({ mediaUrl, mediaId }).
	 * @param {Function} setAttributes Setter.
	 * @param {string}   className     Wrapper class for the preview image.
	 * @return {Object} Element.
	 */
	function imageControl( a, setAttributes, className ) {
		return el(
			MediaUploadCheck,
			null,
			el( MediaUpload, {
				allowedTypes: [ 'image' ],
				value: a.mediaId,
				onSelect: function ( media ) {
					setAttributes( {
						mediaUrl: media.url,
						mediaId: media.id,
						mediaAlt: media.alt || '',
					} );
				},
				render: function ( o ) {
					return el(
						'div',
						{ className: 'bb-media-control' },
						a.mediaUrl
							? el( 'img', { src: a.mediaUrl, alt: '', className: className } )
							: null,
						el(
							Button,
							{ variant: 'secondary', onClick: o.open },
							a.mediaUrl ? __( 'Replace image', 'blank-base' ) : __( 'Add image', 'blank-base' )
						)
					);
				},
			} )
		);
	}

	/* ====================================================================
	 * Shared "Design & Motion" controls for EVERY Blank Base block.
	 *
	 * Added with block filters rather than per block: content alignment, an
	 * entrance animation (+ delay, reusing the theme's scroll-reveal classes)
	 * and a hover effect. All defaults are empty, so a block left untouched
	 * saves exactly as before — existing content stays valid. The animation /
	 * hover / alignment classes are added to the SAVED (front-end) markup; the
	 * entrance animation plays on scroll via assets/js/theme.js.
	 * ================================================================== */
	function bbIsOurs( name ) {
		return typeof name === 'string' && name.indexOf( 'blank-base/' ) === 0;
	}

	var BB_SHARED_ATTRS = {
		bbAlign: { type: 'string', default: '' },
		bbAnim: { type: 'string', default: '' },
		bbAnimDelay: { type: 'number', default: 0 },
		bbHover: { type: 'string', default: '' },
	};

	hooks.addFilter( 'blocks.registerBlockType', 'blank-base/shared-attributes', function ( settings, name ) {
		if ( ! bbIsOurs( name ) ) {
			return settings;
		}
		settings.attributes = Object.assign( {}, settings.attributes, BB_SHARED_ATTRS );
		return settings;
	} );

	function bbSharedClasses( a ) {
		var c = [];
		if ( a.bbAlign ) {
			c.push( 'bb-b-align-' + a.bbAlign );
		}
		if ( a.bbHover ) {
			c.push( 'bb-hover-' + a.bbHover );
		}
		if ( a.bbAnim ) {
			c.push( 'bb-animate' );
			if ( a.bbAnim === 'fade' ) {
				c.push( 'bb-fade' );
			} else if ( a.bbAnim === 'zoom' ) {
				c.push( 'bb-zoom' );
			} else if ( a.bbAnim === 'left' ) {
				c.push( 'bb-from-left' );
			} else if ( a.bbAnim === 'right' ) {
				c.push( 'bb-from-right' );
			} else if ( a.bbAnim === 'top' ) {
				c.push( 'bb-from-top' );
			}
			if ( a.bbAnimDelay > 0 ) {
				c.push( 'bb-delay-' + a.bbAnimDelay );
			}
		}
		return c;
	}

	hooks.addFilter( 'blocks.getSaveContent.extraProps', 'blank-base/shared-save', function ( props, blockType, attributes ) {
		if ( ! bbIsOurs( blockType.name ) ) {
			return props;
		}
		var extra = bbSharedClasses( attributes );
		if ( extra.length ) {
			props.className = ( props.className ? props.className + ' ' : '' ) + extra.join( ' ' );
		}
		return props;
	} );

	hooks.addFilter( 'editor.BlockEdit', 'blank-base/shared-controls', function ( BlockEdit ) {
		return function ( props ) {
			if ( ! bbIsOurs( props.name ) ) {
				return el( BlockEdit, props );
			}
			var a = props.attributes;
			var set = props.setAttributes;
			return el(
				Fragment,
				null,
				el( BlockEdit, props ),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Design & Motion', 'blank-base' ), initialOpen: false },
						el( SelectControl, {
							label: __( 'Content alignment', 'blank-base' ),
							value: a.bbAlign,
							options: [
								{ label: __( 'Default', 'blank-base' ), value: '' },
								{ label: __( 'Left', 'blank-base' ), value: 'left' },
								{ label: __( 'Center', 'blank-base' ), value: 'center' },
								{ label: __( 'Right', 'blank-base' ), value: 'right' },
							],
							onChange: function ( v ) {
								set( { bbAlign: v } );
							},
						} ),
						el( SelectControl, {
							label: __( 'Entrance animation', 'blank-base' ),
							help: __( 'Plays on the front end when the block scrolls into view.', 'blank-base' ),
							value: a.bbAnim,
							options: [
								{ label: __( 'None', 'blank-base' ), value: '' },
								{ label: __( 'Fade in', 'blank-base' ), value: 'fade' },
								{ label: __( 'Rise up', 'blank-base' ), value: 'rise' },
								{ label: __( 'Zoom in', 'blank-base' ), value: 'zoom' },
								{ label: __( 'Slide from left', 'blank-base' ), value: 'left' },
								{ label: __( 'Slide from right', 'blank-base' ), value: 'right' },
								{ label: __( 'Drop from top', 'blank-base' ), value: 'top' },
							],
							onChange: function ( v ) {
								set( { bbAnim: v } );
							},
						} ),
						a.bbAnim
							? el( RangeControl, {
									label: __( 'Animation delay (steps)', 'blank-base' ),
									value: a.bbAnimDelay,
									onChange: function ( v ) {
										set( { bbAnimDelay: v || 0 } );
									},
									min: 0,
									max: 3,
									step: 1,
							  } )
							: null,
						el( SelectControl, {
							label: __( 'Hover effect', 'blank-base' ),
							value: a.bbHover,
							options: [
								{ label: __( 'None', 'blank-base' ), value: '' },
								{ label: __( 'Lift', 'blank-base' ), value: 'lift' },
								{ label: __( 'Grow', 'blank-base' ), value: 'grow' },
								{ label: __( 'Shadow', 'blank-base' ), value: 'shadow' },
							],
							onChange: function ( v ) {
								set( { bbHover: v } );
							},
						} )
					)
				)
			);
		};
	} );

	/* Helpers for the enhanced Icon Box. */
	function bbIconboxRoot( a ) {
		var c = [ 'bb-iconbox' ];
		if ( a.iconStyle ) {
			c.push( 'bb-iconbox--icon-' + a.iconStyle );
		}
		if ( a.layout ) {
			c.push( 'bb-iconbox--layout-' + a.layout );
		}
		return c.join( ' ' );
	}
	function bbIconboxIconStyle( a ) {
		var s = {};
		if ( a.iconSize ) {
			s.fontSize = a.iconSize + 'px';
		}
		if ( a.iconColor ) {
			s.color = a.iconColor;
		}
		if ( a.iconBg ) {
			s.background = a.iconBg;
		}
		return Object.keys( s ).length ? s : undefined;
	}

	/* Helpers for the enhanced Testimonial. */
	function bbTestimonialRoot( a ) {
		var c = [ 'bb-testimonial' ];
		if ( a.layout ) {
			c.push( 'bb-testimonial--layout-' + a.layout );
		}
		if ( a.avatarShape ) {
			c.push( 'bb-testimonial--avatar-' + a.avatarShape );
		}
		return c.join( ' ' );
	}
	function bbAvatarStyle( a ) {
		if ( a.avatarSize ) {
			return { width: a.avatarSize + 'px', height: a.avatarSize + 'px' };
		}
		return undefined;
	}
	function bbColorStyle( color ) {
		return color ? { color: color } : undefined;
	}

	/* ====================================================================
	 * Counter — an animated number that counts up when scrolled into view.
	 * ================================================================== */
	registerBlockType( 'blank-base/counter', {
		apiVersion: 2,
		title: __( 'Counter', 'blank-base' ),
		description: __(
			'A big number that counts up when it scrolls into view, with a label beneath.',
			'blank-base'
		),
		category: CATEGORY,
		icon: 'chart-bar',
		keywords: [ __( 'stat', 'blank-base' ), __( 'number', 'blank-base' ), __( 'count', 'blank-base' ) ],
		supports: {
			html: false,
			align: [ 'wide', 'full' ],
			color: { text: true, background: false, link: false },
			spacing: { margin: true, padding: true },
			typography: { fontSize: true },
		},
		attributes: {
			prefix: { type: 'string', default: '' },
			number: { type: 'string', default: '10' },
			suffix: { type: 'string', default: 'k+' },
			label: { type: 'string', default: __( 'Active users', 'blank-base' ) },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-counter-block' } );

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Counter', 'blank-base' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Prefix', 'blank-base' ),
							value: a.prefix,
							onChange: function ( v ) {
								setAttributes( { prefix: v } );
							},
							help: __( 'Optional text before the number, e.g. "$".', 'blank-base' ),
						} ),
						el( TextControl, {
							label: __( 'Number', 'blank-base' ),
							value: a.number,
							onChange: function ( v ) {
								setAttributes( { number: v } );
							},
							help: __(
								'The target value. Use a decimal (e.g. 4.9) to count up with decimals.',
								'blank-base'
							),
						} ),
						el( TextControl, {
							label: __( 'Suffix', 'blank-base' ),
							value: a.suffix,
							onChange: function ( v ) {
								setAttributes( { suffix: v } );
							},
							help: __( 'Optional text after the number, e.g. "k+", "%", "/5".', 'blank-base' ),
						} )
					)
				),
				el(
					'div',
					blockProps,
					el(
						'div',
						{ className: 'bb-counter' },
						( a.prefix || '' ) + ( a.number || '' ) + ( a.suffix || '' )
					),
					el( RichText, {
						tagName: 'div',
						className: 'bb-counter__label',
						value: a.label,
						allowedFormats: [],
						onChange: function ( v ) {
							setAttributes( { label: v } );
						},
						placeholder: __( 'Label', 'blank-base' ),
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( { className: 'bb-counter-block' } );
			return el(
				'div',
				blockProps,
				el(
					'div',
					{ className: 'bb-counter' },
					( a.prefix || '' ) + ( a.number || '' ) + ( a.suffix || '' )
				),
				el( RichText.Content, {
					tagName: 'div',
					className: 'bb-counter__label',
					value: a.label,
				} )
			);
		},
	} );

	/* ====================================================================
	 * Progress Bar — a labelled bar that fills to a percentage on scroll.
	 * ================================================================== */
	registerBlockType( 'blank-base/progress', {
		apiVersion: 2,
		title: __( 'Progress Bar', 'blank-base' ),
		description: __(
			'A labelled bar that animates to a percentage when it scrolls into view.',
			'blank-base'
		),
		category: CATEGORY,
		icon: 'performance',
		keywords: [ __( 'skill', 'blank-base' ), __( 'bar', 'blank-base' ), __( 'percent', 'blank-base' ) ],
		supports: {
			html: false,
			color: { text: true, background: false, link: false },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			label: { type: 'string', default: __( 'Design', 'blank-base' ) },
			percent: { type: 'number', default: 92 },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-skill-block' } );
			var pct = Math.max( 0, Math.min( 100, a.percent ) );

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Progress Bar', 'blank-base' ), initialOpen: true },
						el( RangeControl, {
							label: __( 'Percentage', 'blank-base' ),
							value: a.percent,
							min: 0,
							max: 100,
							onChange: function ( v ) {
								setAttributes( { percent: v === undefined ? 0 : v } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el(
						'div',
						{ className: 'bb-skill__label' },
						el( RichText, {
							tagName: 'span',
							value: a.label,
							allowedFormats: [],
							onChange: function ( v ) {
								setAttributes( { label: v } );
							},
							placeholder: __( 'Label', 'blank-base' ),
						} ),
						el( 'span', null, pct + '%' )
					),
					el(
						'div',
						{ className: 'bb-skill__track' },
						el( 'div', {
							className: 'bb-skill__fill',
							style: { width: pct + '%' },
						} )
					)
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var pct = Math.max( 0, Math.min( 100, a.percent ) );
			var blockProps = useBlockProps.save( { className: 'bb-skill' } );
			// Plain "Label 92%" text — theme.js parses this and builds the
			// animated bar on the front end.
			return el( 'p', blockProps, ( a.label || '' ) + ' ' + pct + '%' );
		},
	} );

	/* ====================================================================
	 * Icon Box — a Dashicon above a heading and a line of text.
	 * ================================================================== */
	registerBlockType( 'blank-base/icon-box', {
		apiVersion: 2,
		title: __( 'Icon Box', 'blank-base' ),
		description: __( 'A Dashicon above a heading and a short description.', 'blank-base' ),
		category: CATEGORY,
		icon: 'star-filled',
		keywords: [ __( 'feature', 'blank-base' ), __( 'icon', 'blank-base' ), __( 'service', 'blank-base' ) ],
		supports: {
			html: false,
			align: [ 'wide' ],
			color: { text: true, background: true, link: true },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			icon: { type: 'string', default: 'star-filled' },
			title: { type: 'string', default: __( 'Feature title', 'blank-base' ) },
			text: {
				type: 'string',
				default: __( 'A short sentence describing this feature or benefit.', 'blank-base' ),
			},
			iconStyle: { type: 'string', default: '' },
			iconSize: { type: 'number', default: 0 },
			iconColor: { type: 'string', default: '' },
			iconBg: { type: 'string', default: '' },
			titleTag: { type: 'string', default: 'h3' },
			layout: { type: 'string', default: '' },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: bbIconboxRoot( a ) } );

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Icon', 'blank-base' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Dashicon name', 'blank-base' ),
							value: a.icon,
							onChange: function ( v ) {
								setAttributes( { icon: v.replace( /^dashicons-/, '' ).trim() } );
							},
							help: __(
								'A Dashicon slug without the "dashicons-" prefix, e.g. star-filled, heart, awards, shield.',
								'blank-base'
							),
						} ),
						el( SelectControl, {
							label: __( 'Icon style', 'blank-base' ),
							value: a.iconStyle,
							options: [
								{ label: __( 'Plain', 'blank-base' ), value: '' },
								{ label: __( 'Circle', 'blank-base' ), value: 'circle' },
								{ label: __( 'Square', 'blank-base' ), value: 'square' },
							],
							onChange: function ( v ) {
								setAttributes( { iconStyle: v } );
							},
						} ),
						el( RangeControl, {
							label: __( 'Icon size (px, 0 = default)', 'blank-base' ),
							value: a.iconSize,
							onChange: function ( v ) {
								setAttributes( { iconSize: v || 0 } );
							},
							min: 0,
							max: 120,
							step: 2,
						} ),
						el( SelectControl, {
							label: __( 'Layout', 'blank-base' ),
							value: a.layout,
							options: [
								{ label: __( 'Icon on top', 'blank-base' ), value: '' },
								{ label: __( 'Icon on left', 'blank-base' ), value: 'left' },
								{ label: __( 'Icon on right', 'blank-base' ), value: 'right' },
							],
							onChange: function ( v ) {
								setAttributes( { layout: v } );
							},
						} ),
						el( SelectControl, {
							label: __( 'Title tag', 'blank-base' ),
							value: a.titleTag,
							options: [
								{ label: 'H2', value: 'h2' },
								{ label: 'H3', value: 'h3' },
								{ label: 'H4', value: 'h4' },
								{ label: 'H5', value: 'h5' },
								{ label: 'H6', value: 'h6' },
							],
							onChange: function ( v ) {
								setAttributes( { titleTag: v } );
							},
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Icon colors', 'blank-base' ), initialOpen: false },
						el( 'p', { className: 'bb-control-label' }, __( 'Icon color', 'blank-base' ) ),
						el( ColorPalette, {
							value: a.iconColor,
							onChange: function ( v ) {
								setAttributes( { iconColor: v || '' } );
							},
						} ),
						el( 'p', { className: 'bb-control-label' }, __( 'Icon background', 'blank-base' ) ),
						el( ColorPalette, {
							value: a.iconBg,
							onChange: function ( v ) {
								setAttributes( { iconBg: v || '' } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el( 'span', {
						className: 'bb-iconbox__icon dashicons dashicons-' + ( a.icon || 'star-filled' ),
						style: bbIconboxIconStyle( a ),
					} ),
					el( RichText, {
						tagName: a.titleTag || 'h3',
						className: 'bb-iconbox__title',
						value: a.title,
						onChange: function ( v ) {
							setAttributes( { title: v } );
						},
						placeholder: __( 'Heading', 'blank-base' ),
					} ),
					el( RichText, {
						tagName: 'p',
						className: 'bb-iconbox__text',
						value: a.text,
						onChange: function ( v ) {
							setAttributes( { text: v } );
						},
						placeholder: __( 'Description', 'blank-base' ),
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( { className: bbIconboxRoot( a ) } );
			return el(
				'div',
				blockProps,
				el( 'span', {
					className: 'bb-iconbox__icon dashicons dashicons-' + ( a.icon || 'star-filled' ),
					style: bbIconboxIconStyle( a ),
				} ),
				el( RichText.Content, {
					tagName: a.titleTag || 'h3',
					className: 'bb-iconbox__title',
					value: a.title,
				} ),
				el( RichText.Content, {
					tagName: 'p',
					className: 'bb-iconbox__text',
					value: a.text,
				} )
			);
		},
	} );

	/* ====================================================================
	 * Testimonial — a centered customer quote with attribution.
	 * ================================================================== */
	registerBlockType( 'blank-base/testimonial', {
		apiVersion: 2,
		title: __( 'Testimonial', 'blank-base' ),
		description: __( 'A customer quote with a photo, name and role.', 'blank-base' ),
		category: CATEGORY,
		icon: 'format-quote',
		keywords: [ __( 'quote', 'blank-base' ), __( 'review', 'blank-base' ) ],
		supports: {
			html: false,
			align: [ 'wide' ],
			color: { text: true, background: true, link: false },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			mediaUrl: { type: 'string', default: '' },
			mediaId: { type: 'number' },
			mediaAlt: { type: 'string', default: '' },
			quote: {
				type: 'string',
				default: __(
					'This is exactly what we needed — simple to set up and a pleasure to use every day.',
					'blank-base'
				),
			},
			name: { type: 'string', default: __( 'Jane Rivera', 'blank-base' ) },
			role: { type: 'string', default: __( 'Product Lead, Northwind', 'blank-base' ) },
			avatarShape: { type: 'string', default: '' },
			avatarSize: { type: 'number', default: 0 },
			layout: { type: 'string', default: '' },
			nameColor: { type: 'string', default: '' },
			roleColor: { type: 'string', default: '' },
			quoteColor: { type: 'string', default: '' },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: bbTestimonialRoot( a ) } );

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Photo', 'blank-base' ), initialOpen: true },
						imageControl( a, setAttributes, 'bb-testimonial__avatar' ),
						el( SelectControl, {
							label: __( 'Photo shape', 'blank-base' ),
							value: a.avatarShape,
							options: [
								{ label: __( 'Default', 'blank-base' ), value: '' },
								{ label: __( 'Circle', 'blank-base' ), value: 'circle' },
								{ label: __( 'Rounded', 'blank-base' ), value: 'rounded' },
								{ label: __( 'Square', 'blank-base' ), value: 'square' },
							],
							onChange: function ( v ) {
								setAttributes( { avatarShape: v } );
							},
						} ),
						el( RangeControl, {
							label: __( 'Photo size (px, 0 = default)', 'blank-base' ),
							value: a.avatarSize,
							onChange: function ( v ) {
								setAttributes( { avatarSize: v || 0 } );
							},
							min: 0,
							max: 160,
							step: 4,
						} ),
						el( SelectControl, {
							label: __( 'Layout', 'blank-base' ),
							value: a.layout,
							options: [
								{ label: __( 'Stacked (photo on top)', 'blank-base' ), value: '' },
								{ label: __( 'Photo on left', 'blank-base' ), value: 'left' },
								{ label: __( 'Photo on right', 'blank-base' ), value: 'right' },
							],
							onChange: function ( v ) {
								setAttributes( { layout: v } );
							},
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Text colors', 'blank-base' ), initialOpen: false },
						el( 'p', { className: 'bb-control-label' }, __( 'Quote color', 'blank-base' ) ),
						el( ColorPalette, {
							value: a.quoteColor,
							onChange: function ( v ) {
								setAttributes( { quoteColor: v || '' } );
							},
						} ),
						el( 'p', { className: 'bb-control-label' }, __( 'Name color', 'blank-base' ) ),
						el( ColorPalette, {
							value: a.nameColor,
							onChange: function ( v ) {
								setAttributes( { nameColor: v || '' } );
							},
						} ),
						el( 'p', { className: 'bb-control-label' }, __( 'Role color', 'blank-base' ) ),
						el( ColorPalette, {
							value: a.roleColor,
							onChange: function ( v ) {
								setAttributes( { roleColor: v || '' } );
							},
						} )
					)
				),
				el(
					'figure',
					blockProps,
					a.mediaUrl
						? el( 'img', {
								className: 'bb-testimonial__avatar',
								src: a.mediaUrl,
								alt: a.mediaAlt,
								style: bbAvatarStyle( a ),
						  } )
						: null,
					el( RichText, {
						tagName: 'blockquote',
						className: 'bb-testimonial__quote',
						value: a.quote,
						style: bbColorStyle( a.quoteColor ),
						onChange: function ( v ) {
							setAttributes( { quote: v } );
						},
						placeholder: __( 'Quote…', 'blank-base' ),
					} ),
					el(
						'figcaption',
						{ className: 'bb-testimonial__cite' },
						el( RichText, {
							tagName: 'span',
							className: 'bb-testimonial__name',
							value: a.name,
							allowedFormats: [],
							style: bbColorStyle( a.nameColor ),
							onChange: function ( v ) {
								setAttributes( { name: v } );
							},
							placeholder: __( 'Name', 'blank-base' ),
						} ),
						el( RichText, {
							tagName: 'span',
							className: 'bb-testimonial__role',
							value: a.role,
							allowedFormats: [],
							style: bbColorStyle( a.roleColor ),
							onChange: function ( v ) {
								setAttributes( { role: v } );
							},
							placeholder: __( 'Role', 'blank-base' ),
						} )
					)
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( { className: bbTestimonialRoot( a ) } );
			return el(
				'figure',
				blockProps,
				a.mediaUrl
					? el( 'img', {
							className: 'bb-testimonial__avatar',
							src: a.mediaUrl,
							alt: a.mediaAlt,
							style: bbAvatarStyle( a ),
					  } )
					: null,
				el( RichText.Content, {
					tagName: 'blockquote',
					className: 'bb-testimonial__quote',
					value: a.quote,
					style: bbColorStyle( a.quoteColor ),
				} ),
				el(
					'figcaption',
					{ className: 'bb-testimonial__cite' },
					el( RichText.Content, {
						tagName: 'span',
						className: 'bb-testimonial__name',
						value: a.name,
						style: bbColorStyle( a.nameColor ),
					} ),
					el( RichText.Content, {
						tagName: 'span',
						className: 'bb-testimonial__role',
						value: a.role,
						style: bbColorStyle( a.roleColor ),
					} )
				)
			);
		},
		// Keep testimonials saved before the avatar was added valid.
		deprecated: [
			{
				attributes: {
					quote: { type: 'string' },
					name: { type: 'string' },
					role: { type: 'string' },
				},
				save: function ( props ) {
					var a = props.attributes;
					var blockProps = useBlockProps.save( { className: 'bb-testimonial' } );
					return el(
						'figure',
						blockProps,
						el( RichText.Content, {
							tagName: 'blockquote',
							className: 'bb-testimonial__quote',
							value: a.quote,
						} ),
						el(
							'figcaption',
							{ className: 'bb-testimonial__cite' },
							el( RichText.Content, {
								tagName: 'span',
								className: 'bb-testimonial__name',
								value: a.name,
							} ),
							el( RichText.Content, {
								tagName: 'span',
								className: 'bb-testimonial__role',
								value: a.role,
							} )
						)
					);
				},
			},
		],
	} );
	/* ====================================================================
	 * Advanced Heading — an eyebrow line, a heading and a short divider.
	 * ================================================================== */
	registerBlockType( 'blank-base/heading', {
		apiVersion: 2,
		title: __( 'Advanced Heading', 'blank-base' ),
		description: __( 'An eyebrow line, a heading and an underline accent.', 'blank-base' ),
		category: CATEGORY,
		icon: 'heading',
		supports: {
			html: false,
			align: [ 'wide', 'full' ],
			color: { text: true, background: false },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			eyebrow: { type: 'string', default: __( 'Introducing', 'blank-base' ) },
			title: { type: 'string', default: __( 'A headline that sets the scene', 'blank-base' ) },
			level: { type: 'number', default: 2 },
			showDivider: { type: 'boolean', default: true },
			textAlign: { type: 'string', default: 'center' },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var Tag = 'h' + a.level;
			var blockProps = useBlockProps( {
				className: 'bb-heading has-text-align-' + a.textAlign,
			} );
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Heading', 'blank-base' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Heading level', 'blank-base' ),
							value: String( a.level ),
							options: [
								{ label: 'H1', value: '1' },
								{ label: 'H2', value: '2' },
								{ label: 'H3', value: '3' },
								{ label: 'H4', value: '4' },
							],
							onChange: function ( v ) {
								setAttributes( { level: parseInt( v, 10 ) } );
							},
						} ),
						el( SelectControl, {
							label: __( 'Alignment', 'blank-base' ),
							value: a.textAlign,
							options: [
								{ label: __( 'Left', 'blank-base' ), value: 'left' },
								{ label: __( 'Center', 'blank-base' ), value: 'center' },
								{ label: __( 'Right', 'blank-base' ), value: 'right' },
							],
							onChange: function ( v ) {
								setAttributes( { textAlign: v } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Show divider', 'blank-base' ),
							checked: a.showDivider,
							onChange: function ( v ) {
								setAttributes( { showDivider: v } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el( RichText, {
						tagName: 'p',
						className: 'bb-heading__eyebrow',
						value: a.eyebrow,
						allowedFormats: [],
						onChange: function ( v ) {
							setAttributes( { eyebrow: v } );
						},
						placeholder: __( 'Eyebrow', 'blank-base' ),
					} ),
					el( RichText, {
						tagName: Tag,
						className: 'bb-heading__title',
						value: a.title,
						onChange: function ( v ) {
							setAttributes( { title: v } );
						},
						placeholder: __( 'Heading', 'blank-base' ),
					} ),
					a.showDivider ? el( 'span', { className: 'bb-heading__divider' } ) : null
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var Tag = 'h' + a.level;
			var blockProps = useBlockProps.save( {
				className: 'bb-heading has-text-align-' + a.textAlign,
			} );
			return el(
				'div',
				blockProps,
				a.eyebrow
					? el( RichText.Content, {
							tagName: 'p',
							className: 'bb-heading__eyebrow',
							value: a.eyebrow,
					  } )
					: null,
				el( RichText.Content, {
					tagName: Tag,
					className: 'bb-heading__title',
					value: a.title,
				} ),
				a.showDivider ? el( 'span', { className: 'bb-heading__divider' } ) : null
			);
		},
	} );

	/* ====================================================================
	 * Icon — a single Dashicon.
	 * ================================================================== */
	registerBlockType( 'blank-base/icon', {
		apiVersion: 2,
		title: __( 'Icon', 'blank-base' ),
		description: __( 'A single Dashicon at the size and colour you choose.', 'blank-base' ),
		category: CATEGORY,
		icon: 'star-filled',
		supports: {
			html: false,
			align: true,
			color: { text: true, background: false },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			icon: { type: 'string', default: 'star-filled' },
			size: { type: 'number', default: 48 },
			align: { type: 'string', default: 'center' },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( {
				className: 'bb-icon has-text-align-' + ( a.align || 'center' ),
			} );
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Icon', 'blank-base' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Dashicon name', 'blank-base' ),
							value: a.icon,
							onChange: function ( v ) {
								setAttributes( { icon: v.replace( /^dashicons-/, '' ).trim() } );
							},
							help: __( 'e.g. star-filled, heart, awards, shield, email.', 'blank-base' ),
						} ),
						el( RangeControl, {
							label: __( 'Size (px)', 'blank-base' ),
							value: a.size,
							min: 16,
							max: 160,
							onChange: function ( v ) {
								setAttributes( { size: v === undefined ? 48 : v } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el( 'span', {
						className: 'dashicons dashicons-' + ( a.icon || 'star-filled' ),
						style: { fontSize: a.size + 'px', width: 'auto', height: 'auto' },
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( {
				className: 'bb-icon has-text-align-' + ( a.align || 'center' ),
			} );
			return el(
				'div',
				blockProps,
				el( 'span', {
					className: 'dashicons dashicons-' + ( a.icon || 'star-filled' ),
					style: { fontSize: a.size + 'px', width: 'auto', height: 'auto' },
				} )
			);
		},
	} );

	/* ====================================================================
	 * Image Box — an image above a heading and text.
	 * ================================================================== */
	registerBlockType( 'blank-base/image-box', {
		apiVersion: 2,
		title: __( 'Image Box', 'blank-base' ),
		description: __( 'An image above a heading and a short description.', 'blank-base' ),
		category: CATEGORY,
		icon: 'format-image',
		supports: {
			html: false,
			align: [ 'wide' ],
			color: { text: true, background: true, link: true },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			mediaUrl: { type: 'string', default: '' },
			mediaId: { type: 'number' },
			mediaAlt: { type: 'string', default: '' },
			title: { type: 'string', default: __( 'Image box title', 'blank-base' ) },
			text: { type: 'string', default: __( 'A short line of supporting text.', 'blank-base' ) },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-imagebox' } );
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Image', 'blank-base' ), initialOpen: true },
						imageControl( a, setAttributes, 'bb-imagebox__img' )
					)
				),
				el(
					'div',
					blockProps,
					a.mediaUrl
						? el( 'img', { className: 'bb-imagebox__img', src: a.mediaUrl, alt: a.mediaAlt } )
						: el( 'div', { className: 'bb-imagebox__placeholder' }, imageControl( a, setAttributes, 'bb-imagebox__img' ) ),
					el( RichText, {
						tagName: 'h3',
						className: 'bb-imagebox__title',
						value: a.title,
						onChange: function ( v ) {
							setAttributes( { title: v } );
						},
						placeholder: __( 'Heading', 'blank-base' ),
					} ),
					el( RichText, {
						tagName: 'p',
						className: 'bb-imagebox__text',
						value: a.text,
						onChange: function ( v ) {
							setAttributes( { text: v } );
						},
						placeholder: __( 'Description', 'blank-base' ),
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( { className: 'bb-imagebox' } );
			return el(
				'div',
				blockProps,
				a.mediaUrl
					? el( 'img', { className: 'bb-imagebox__img', src: a.mediaUrl, alt: a.mediaAlt } )
					: null,
				el( RichText.Content, { tagName: 'h3', className: 'bb-imagebox__title', value: a.title } ),
				el( RichText.Content, { tagName: 'p', className: 'bb-imagebox__text', value: a.text } )
			);
		},
	} );

	/* ====================================================================
	 * Pricing Box — a single pricing tier.
	 * ================================================================== */
	registerBlockType( 'blank-base/pricing', {
		apiVersion: 2,
		title: __( 'Pricing Box', 'blank-base' ),
		description: __( 'A single pricing plan with features and a button.', 'blank-base' ),
		category: CATEGORY,
		icon: 'money-alt',
		supports: {
			html: false,
			color: { text: true, background: true, link: true },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			plan: { type: 'string', default: __( 'Starter', 'blank-base' ) },
			price: { type: 'string', default: '$9' },
			period: { type: 'string', default: __( '/mo', 'blank-base' ) },
			features: { type: 'string', default: __( 'Everything to begin\nUp to 3 projects\nEmail support', 'blank-base' ) },
			buttonText: { type: 'string', default: __( 'Choose plan', 'blank-base' ) },
			buttonUrl: { type: 'string', default: '#' },
			featured: { type: 'boolean', default: false },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( {
				className: 'bb-pricing' + ( a.featured ? ' is-featured' : '' ),
			} );
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Plan', 'blank-base' ), initialOpen: true },
						el( ToggleControl, {
							label: __( 'Highlight as featured', 'blank-base' ),
							checked: a.featured,
							onChange: function ( v ) {
								setAttributes( { featured: v } );
							},
						} ),
						el( TextControl, {
							label: __( 'Button URL', 'blank-base' ),
							value: a.buttonUrl,
							onChange: function ( v ) {
								setAttributes( { buttonUrl: v } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el( RichText, {
						tagName: 'div',
						className: 'bb-pricing__plan',
						value: a.plan,
						allowedFormats: [],
						onChange: function ( v ) {
							setAttributes( { plan: v } );
						},
						placeholder: __( 'Plan name', 'blank-base' ),
					} ),
					el(
						'div',
						{ className: 'bb-pricing__price' },
						el( RichText, {
							tagName: 'span',
							className: 'bb-pricing__amount',
							value: a.price,
							allowedFormats: [],
							onChange: function ( v ) {
								setAttributes( { price: v } );
							},
							placeholder: '$9',
						} ),
						el( RichText, {
							tagName: 'span',
							className: 'bb-pricing__period',
							value: a.period,
							allowedFormats: [],
							onChange: function ( v ) {
								setAttributes( { period: v } );
							},
							placeholder: '/mo',
						} )
					),
					el( RichText, {
						tagName: 'ul',
						multiline: 'li',
						className: 'bb-pricing__features',
						value: a.features,
						onChange: function ( v ) {
							setAttributes( { features: v } );
						},
						placeholder: __( 'Add features…', 'blank-base' ),
					} ),
					el(
						'div',
						{ className: 'bb-pricing__cta' },
						el( RichText, {
							tagName: 'span',
							className: 'bb-pricing__button',
							value: a.buttonText,
							allowedFormats: [],
							onChange: function ( v ) {
								setAttributes( { buttonText: v } );
							},
							placeholder: __( 'Button', 'blank-base' ),
						} )
					)
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( {
				className: 'bb-pricing' + ( a.featured ? ' is-featured' : '' ),
			} );
			return el(
				'div',
				blockProps,
				el( RichText.Content, { tagName: 'div', className: 'bb-pricing__plan', value: a.plan } ),
				el(
					'div',
					{ className: 'bb-pricing__price' },
					el( RichText.Content, { tagName: 'span', className: 'bb-pricing__amount', value: a.price } ),
					el( RichText.Content, { tagName: 'span', className: 'bb-pricing__period', value: a.period } )
				),
				el( RichText.Content, {
					tagName: 'ul',
					multiline: 'li',
					className: 'bb-pricing__features',
					value: a.features,
				} ),
				el(
					'div',
					{ className: 'bb-pricing__cta' },
					el(
						'a',
						{ className: 'bb-pricing__button', href: a.buttonUrl || '#' },
						a.buttonText
					)
				)
			);
		},
	} );

	/* ====================================================================
	 * Team Member — a photo, a name and a role.
	 * ================================================================== */
	registerBlockType( 'blank-base/person', {
		apiVersion: 2,
		title: __( 'Team Member', 'blank-base' ),
		description: __( 'A photo above a name, role and short bio.', 'blank-base' ),
		category: CATEGORY,
		icon: 'admin-users',
		supports: {
			html: false,
			color: { text: true, background: true },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			mediaUrl: { type: 'string', default: '' },
			mediaId: { type: 'number' },
			mediaAlt: { type: 'string', default: '' },
			name: { type: 'string', default: __( 'Alex Morgan', 'blank-base' ) },
			role: { type: 'string', default: __( 'Founder', 'blank-base' ) },
			bio: { type: 'string', default: __( 'A sentence about this person and what they do.', 'blank-base' ) },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-person' } );
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Photo', 'blank-base' ), initialOpen: true },
						imageControl( a, setAttributes, 'bb-person__photo' )
					)
				),
				el(
					'div',
					blockProps,
					a.mediaUrl
						? el( 'img', { className: 'bb-person__photo', src: a.mediaUrl, alt: a.mediaAlt } )
						: el( 'div', { className: 'bb-person__placeholder' }, imageControl( a, setAttributes, 'bb-person__photo' ) ),
					el( RichText, {
						tagName: 'h3',
						className: 'bb-person__name',
						value: a.name,
						allowedFormats: [],
						onChange: function ( v ) {
							setAttributes( { name: v } );
						},
						placeholder: __( 'Name', 'blank-base' ),
					} ),
					el( RichText, {
						tagName: 'p',
						className: 'bb-person__role',
						value: a.role,
						allowedFormats: [],
						onChange: function ( v ) {
							setAttributes( { role: v } );
						},
						placeholder: __( 'Role', 'blank-base' ),
					} ),
					el( RichText, {
						tagName: 'p',
						className: 'bb-person__bio',
						value: a.bio,
						onChange: function ( v ) {
							setAttributes( { bio: v } );
						},
						placeholder: __( 'Short bio', 'blank-base' ),
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( { className: 'bb-person' } );
			return el(
				'div',
				blockProps,
				a.mediaUrl
					? el( 'img', { className: 'bb-person__photo', src: a.mediaUrl, alt: a.mediaAlt } )
					: null,
				el( RichText.Content, { tagName: 'h3', className: 'bb-person__name', value: a.name } ),
				el( RichText.Content, { tagName: 'p', className: 'bb-person__role', value: a.role } ),
				el( RichText.Content, { tagName: 'p', className: 'bb-person__bio', value: a.bio } )
			);
		},
	} );

	/* ====================================================================
	 * Toggle / FAQ — a native <details> disclosure (accessible, no JS).
	 * ================================================================== */
	registerBlockType( 'blank-base/toggle', {
		apiVersion: 2,
		title: __( 'Toggle / FAQ', 'blank-base' ),
		description: __( 'A question that expands to reveal its answer. Stack several for an FAQ.', 'blank-base' ),
		category: CATEGORY,
		icon: 'plus-alt2',
		supports: {
			html: false,
			align: [ 'wide' ],
			color: { text: true, background: true },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			question: { type: 'string', default: __( 'Frequently asked question?', 'blank-base' ) },
			answer: { type: 'string', default: __( 'The answer goes here. Keep it concise and helpful.', 'blank-base' ) },
			open: { type: 'boolean', default: false },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-toggle' } );
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Toggle', 'blank-base' ), initialOpen: true },
						el( ToggleControl, {
							label: __( 'Open by default', 'blank-base' ),
							checked: a.open,
							onChange: function ( v ) {
								setAttributes( { open: v } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el( RichText, {
						tagName: 'div',
						className: 'bb-toggle__q',
						value: a.question,
						allowedFormats: [],
						onChange: function ( v ) {
							setAttributes( { question: v } );
						},
						placeholder: __( 'Question', 'blank-base' ),
					} ),
					el( RichText, {
						tagName: 'div',
						className: 'bb-toggle__a',
						value: a.answer,
						onChange: function ( v ) {
							setAttributes( { answer: v } );
						},
						placeholder: __( 'Answer', 'blank-base' ),
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( { className: 'bb-toggle' } );
			return el(
				'details',
				Object.assign( {}, blockProps, a.open ? { open: true } : {} ),
				el( RichText.Content, { tagName: 'summary', className: 'bb-toggle__q', value: a.question } ),
				el( RichText.Content, { tagName: 'div', className: 'bb-toggle__a', value: a.answer } )
			);
		},
	} );

	/* ====================================================================
	 * Countdown — a live countdown to a date (animated on the front end).
	 * ================================================================== */
	registerBlockType( 'blank-base/countdown', {
		apiVersion: 2,
		title: __( 'Countdown', 'blank-base' ),
		description: __( 'A live countdown timer to a date and time you set.', 'blank-base' ),
		category: CATEGORY,
		icon: 'clock',
		supports: {
			html: false,
			align: [ 'wide' ],
			color: { text: true, background: true },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			deadline: { type: 'string', default: '' },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'bb-countdown' } );
			var units = [
				[ __( 'Days', 'blank-base' ), '00' ],
				[ __( 'Hours', 'blank-base' ), '00' ],
				[ __( 'Minutes', 'blank-base' ), '00' ],
				[ __( 'Seconds', 'blank-base' ), '00' ],
			];
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Countdown target', 'blank-base' ), initialOpen: true },
						el( DateTimePicker, {
							currentDate: a.deadline || undefined,
							onChange: function ( v ) {
								setAttributes( { deadline: v } );
							},
							is12Hour: true,
						} )
					)
				),
				el(
					'div',
					blockProps,
					units.map( function ( u, i ) {
						return el(
							'div',
							{ key: i, className: 'bb-countdown__unit' },
							el( 'span', { className: 'bb-countdown__num' }, u[ 1 ] ),
							el( 'span', { className: 'bb-countdown__label' }, u[ 0 ] )
						);
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var blockProps = useBlockProps.save( { className: 'bb-countdown' } );
			var units = [
				[ __( 'Days', 'blank-base' ), 'days' ],
				[ __( 'Hours', 'blank-base' ), 'hours' ],
				[ __( 'Minutes', 'blank-base' ), 'minutes' ],
				[ __( 'Seconds', 'blank-base' ), 'seconds' ],
			];
			return el(
				'div',
				Object.assign( {}, blockProps, { 'data-deadline': a.deadline || '' } ),
				units.map( function ( u, i ) {
					return el(
						'div',
						{ key: i, className: 'bb-countdown__unit' },
						el( 'span', { className: 'bb-countdown__num', 'data-unit': u[ 1 ] }, '00' ),
						el( 'span', { className: 'bb-countdown__label' }, u[ 0 ] )
					);
				} )
			);
		},
	} );

	/* ====================================================================
	 * Circle Progress — an SVG ring that fills to a percentage on scroll.
	 * ================================================================== */
	registerBlockType( 'blank-base/circle', {
		apiVersion: 2,
		title: __( 'Circle Progress', 'blank-base' ),
		description: __( 'A circular progress ring that fills and counts up when scrolled into view.', 'blank-base' ),
		category: CATEGORY,
		icon: 'marker',
		supports: {
			html: false,
			color: { text: true, background: false },
			spacing: { margin: true, padding: true },
		},
		attributes: {
			percent: { type: 'number', default: 75 },
			label: { type: 'string', default: __( 'Completion', 'blank-base' ) },
		},
		edit: function ( props ) {
			var a = props.attributes;
			var setAttributes = props.setAttributes;
			var pct = Math.max( 0, Math.min( 100, a.percent ) );
			var blockProps = useBlockProps( { className: 'bb-circle' } );
			var circ = 339.292;
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Circle Progress', 'blank-base' ), initialOpen: true },
						el( RangeControl, {
							label: __( 'Percentage', 'blank-base' ),
							value: a.percent,
							min: 0,
							max: 100,
							onChange: function ( v ) {
								setAttributes( { percent: v === undefined ? 0 : v } );
							},
						} )
					)
				),
				el(
					'div',
					blockProps,
					el(
						'svg',
						{ className: 'bb-circle__svg', viewBox: '0 0 120 120' },
						el( 'circle', { className: 'bb-circle__bg', cx: 60, cy: 60, r: 54 } ),
						el( 'circle', {
							className: 'bb-circle__bar',
							cx: 60,
							cy: 60,
							r: 54,
							style: {
								strokeDasharray: circ,
								strokeDashoffset: circ * ( 1 - pct / 100 ),
							},
						} )
					),
					el( 'span', { className: 'bb-circle__num' }, pct + '%' ),
					el( RichText, {
						tagName: 'span',
						className: 'bb-circle__label',
						value: a.label,
						allowedFormats: [],
						onChange: function ( v ) {
							setAttributes( { label: v } );
						},
						placeholder: __( 'Label', 'blank-base' ),
					} )
				)
			);
		},
		save: function ( props ) {
			var a = props.attributes;
			var pct = Math.max( 0, Math.min( 100, a.percent ) );
			var blockProps = useBlockProps.save( { className: 'bb-circle' } );
			var circ = 339.292;
			return el(
				'div',
				Object.assign( {}, blockProps, { 'data-percent': pct } ),
				el(
					'svg',
					{ className: 'bb-circle__svg', viewBox: '0 0 120 120' },
					el( 'circle', { className: 'bb-circle__bg', cx: 60, cy: 60, r: 54 } ),
					el( 'circle', {
						className: 'bb-circle__bar',
						cx: 60,
						cy: 60,
						r: 54,
						style: { strokeDasharray: circ, strokeDashoffset: circ },
					} )
				),
				el( 'span', { className: 'bb-circle__num' }, '0%' ),
				el( RichText.Content, { tagName: 'span', className: 'bb-circle__label', value: a.label } )
			);
		},
	} );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element, window.wp.components, window.wp.i18n, window.wp.hooks );
