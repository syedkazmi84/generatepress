/**
 * Blank Base — Template Library (editor).
 *
 * Adds a "Template Library" item to the editor's options (⋮) menu. Clicking it
 * opens a modal of ready-made sections served from this theme over the
 * blank-base/v1/templates REST route. Each card renders a live preview of the
 * real blocks; "Insert" drops a fresh copy into the post.
 *
 * Blocks are built from structured data with wp.blocks.createBlock, so there is
 * no saved-markup to validate and previews always match what gets inserted.
 *
 * @package Blank_Base
 */
( function ( plugins, editPost, element, components, data, blocks, blockEditor, apiFetch, i18n ) {
	'use strict';

	var el = element.createElement;
	var useState = element.useState;
	var Fragment = element.Fragment;
	var registerPlugin = plugins.registerPlugin;
	var PluginMoreMenuItem = editPost.PluginMoreMenuItem;
	var Modal = components.Modal;
	var Button = components.Button;
	var Spinner = components.Spinner;
	var BlockPreview = blockEditor.BlockPreview;
	var __ = i18n.__;

	/**
	 * Recursively build real block instances from template node data.
	 *
	 * @param {Array} nodes Template block nodes.
	 * @return {Array} Block instances.
	 */
	function buildBlocks( nodes ) {
		if ( ! nodes || ! nodes.length ) {
			return [];
		}
		return nodes.map( function ( node ) {
			return blocks.createBlock(
				node.name,
				node.attributes || {},
				buildBlocks( node.innerBlocks )
			);
		} );
	}

	function Library() {
		var openState = useState( false );
		var isOpen = openState[ 0 ];
		var setOpen = openState[ 1 ];

		var tplState = useState( null ); // null = not loaded yet.
		var templates = tplState[ 0 ];
		var setTemplates = tplState[ 1 ];

		function load() {
			if ( templates !== null ) {
				return;
			}
			apiFetch( { path: '/blank-base/v1/templates' } )
				.then( function ( res ) {
					setTemplates( res || [] );
				} )
				.catch( function () {
					setTemplates( [] );
				} );
		}

		function openLibrary() {
			setOpen( true );
			load();
		}

		function insert( tpl ) {
			try {
				var built = buildBlocks( tpl.blocks );
				data.dispatch( 'core/block-editor' ).insertBlocks( built );
			} catch ( e ) {
				// eslint-disable-next-line no-console
				window.console.error( 'Template Library: could not insert template', e );
			}
			setOpen( false );
		}

		var menuItem = el(
			PluginMoreMenuItem,
			{ icon: 'layout', onClick: openLibrary },
			__( 'Template Library', 'blank-base' )
		);

		if ( ! isOpen ) {
			return menuItem;
		}

		var body;
		if ( templates === null ) {
			body = el( 'div', { className: 'bb-template-library__loading' }, el( Spinner ) );
		} else if ( ! templates.length ) {
			body = el( 'p', null, __( 'No templates found.', 'blank-base' ) );
		} else {
			body = el(
				'div',
				{ className: 'bb-template-library__grid' },
				templates.map( function ( tpl ) {
					var preview;
					try {
						preview = el( BlockPreview, {
							blocks: buildBlocks( tpl.blocks ),
							viewportWidth: 1200,
						} );
					} catch ( e ) {
						preview = null;
					}
					return el(
						'div',
						{ key: tpl.slug, className: 'bb-template-library__item' },
						el( 'div', { className: 'bb-template-library__preview' }, preview ),
						el(
							'div',
							{ className: 'bb-template-library__meta' },
							el(
								'div',
								null,
								el( 'span', { className: 'bb-template-library__title' }, tpl.title ),
								tpl.category
									? el( 'span', { className: 'bb-template-library__cat' }, tpl.category )
									: null
							),
							el(
								Button,
								{
									variant: 'primary',
									onClick: function () {
										insert( tpl );
									},
								},
								__( 'Insert', 'blank-base' )
							)
						)
					);
				} )
			);
		}

		return el(
			Fragment,
			null,
			menuItem,
			el(
				Modal,
				{
					title: __( 'Template Library', 'blank-base' ),
					className: 'bb-template-library',
					onRequestClose: function () {
						setOpen( false );
					},
				},
				body
			)
		);
	}

	registerPlugin( 'blank-base-template-library', { render: Library } );
} )(
	window.wp.plugins,
	window.wp.editPost,
	window.wp.element,
	window.wp.components,
	window.wp.data,
	window.wp.blocks,
	window.wp.blockEditor,
	window.wp.apiFetch,
	window.wp.i18n
);
