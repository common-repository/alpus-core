/**
 * Alpus Studio Library
 *
 * @author     D-THEMES
 * @package    WP Alpus Core Framework
 * @subpackage Core
 * @since      1.0
 */
'use strict';

/**
 * In visual composer, loading start template is earlier than initialization of vc editor
 * for low level servers. So to delay this loading, increase this.
 */
window.alpus_vc_studio_delay = 1500;
window.themeCoreAdmin || ( window.themeCoreAdmin = {} );
( function( $ ) {

	/**
	 * Global Function
	 * 
	 * @since 1.2.1
	 */
	themeCoreAdmin.selectBlock = function() {
		var $this = $( this ),
			category = $( this ).parent().data( 'category' );

		if ( parseInt( category ) ) {
			if ( category % 23 == 1 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'header' );
			} else if ( category % 23 == 2 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'footer' );
			} else if ( category % 23 == 3 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'popup' );
			} else if ( category % 23 == 4 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'single' );
			} else if ( category % 23 == 5 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'archive' );
			} else if ( category % 23 == 6 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'shop' );
			} else if ( category % 23 == 7 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'product_layout' );
			} else if ( category % 23 == 8 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'cart' );
			} else if ( category % 23 == 9 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'checkout' );
			} else if ( category % 23 == 10 ) {
				$( '.alpus-new-template-form .template-type' ).val( 'type' );
			} else {
				$( '.alpus-new-template-form .template-type' ).val( 'block' );
			}
		} else {
			$( '.alpus-new-template-form .template-type' ).val( category );
		}

		$( '.blocks-wrapper .block.selected' ).removeClass( 'selected' );
		$( '#alpus-new-template-id' ).val( $this.parent().data( 'id' ) );
		if ( $( '.blocks-wrapper .block-category-my-templates.active' ).length )
			$( '#alpus-new-template-type' ).val( 'my' );
		else {
			if ( $( '#alpus-elementor-studio' ).is( ':checked' ) )
				$( '#alpus-new-template-type' ).val( 'e' );
			else if ( $( '#alpus-wpbakery-studio' ).is( ':checked' ) )
				$( '#alpus-new-template-type' ).val( 'w' );
			if ( $( '.alpus-new-template-form .template-type' ).val() == 'type' )
				$( '#alpus-new-template-type' ).val( 'g' );
		}
		$( '#alpus-new-template-name' ).val( $this.closest( '.block' ).addClass( 'selected' ).find( '.block-title' ).text() );
		$( '.blocks-wrapper, .blocks-overlay' ).addClass( 'closed' );
	}

	var $addStudioSection = false; // Add studio block section when add is triggered
	$( document ).ready( function() {
		if ( $( document.body ).hasClass( 'elementor-editor-active' ) && typeof elementor != 'undefined' ) {
			// Alpus Elementor Studio
			window.runStudio = function( addButton ) {
				$( '#alpus-elementor-panel-alpus-studio' ).trigger( 'click' );
				addButton && ( $addStudioSection = $( addButton ).closest( '.elementor-add-section' ) );
			}
			elementor.on( 'document:loaded', setupStudioBlocks );
		}
	} );
	// New Template Studio for Gutenberg
	$( window ).on( 'load', function() {
		if ( !$( document.body ).hasClass( 'elementor-editor-active' ) || typeof elementor == 'undefined' ) {
			setupStudioBlocks();
		}
	} )

	function setupStudioBlocks() {

		var search = '',
			page_type = 'e';
		deployStudio();
		$( document.body ).find( '#wpb_visual_composer' ).length > 0 && ( page_type = 'w' );
		$( document.body ).hasClass( 'vc_inline-shortcode-edit-form' ) && ( page_type = 'w' );
		$( document.body ).hasClass( 'block-editor-page' ) && ( page_type = 'g' );
		var alpus_blocks_cur_page = 1;

		function wpbMergeContent( response, block_id ) {
			if ( response && response.content ) {
				if ( typeof vc != 'undefined' && vc.storage ) { // WPBakery backend editor
					vc.storage.append( response.content );
					vc.shortcodes.fetch( {
						reset: !0
					} ), _.delay( function() {
						window.vc.undoRedoApi.unlock();
					}, 50 );
				} else if ( window.vc_iframe_src ) { // WPBakery frontend editor
					var render_data = { action: 'vc_frontend_load_template', block_id: block_id, content: response.content, wpnonce: alpus_studio.wpnonce, template_unique_id: '1', template_type: 'my_templates', vc_inline: true, _vcnonce: window.vcAdminNonce };
					if ( response.meta ) {
						render_data.meta = response.meta;
					}
					$.ajax( {
						url: window.vc_iframe_src.replace( /&amp;/g, '&' ),
						type: 'post',
						data: render_data,
						success: function( html ) {
							var template, data;
							_.each( $( html ), function( element ) {
								if ( 'vc_template-data' === element.id ) {
									try {
										data = JSON.parse( element.innerHTML );
									} catch ( err ) { }
								}
								if ( 'vc_template-html' === element.id ) {
									template = element.innerHTML;
								}
							} );
							if ( template && data ) {
								vc.builder.buildFromTemplate( template, data );
								vc.closeActivePanel();
							}
						},
					} );
				}
			}

			if ( response && response.meta ) {
				if ( response.meta.page_css && $( ".postbox-container #wpb_visual_composer" ).length > 0 ) {
					$( '#vc_post-custom-css' ).val( $( '#vc_post-custom-css' ).val() + response.meta.page_css );
					$( '#vc_ui-panel-post-settings' ).css( 'display', 'none' );
					$( '#vc_post-settings-button' ).trigger( 'click' );
					$( '#vc_ui-panel-post-settings .vc_ui-panel-footer .vc_ui-button-fw' ).trigger( 'click' );
					$( '#vc_ui-panel-post-settings' ).css( 'display', '' );
				}
				if ( response.meta.page_js && $( "#page_js" ).length > 0 ) {
					$( "#page_js" ).val( $( "#page_js" ).val() + response.meta.page_js );
				}
				if ( window.vc_iframe_src ) {
					if ( typeof alpus_studio['meta_fields'] == 'undefined' ) {
						alpus_studio['meta_fields'] = {};
					}
					if ( response.meta.page_css ) {
						$( '#vc_post-custom-css' ).val( $( '#vc_post-custom-css' ).val() + response.meta.page_css );
						$( '#vc_ui-panel-post-settings' ).css( 'display', 'none' );
						$( '#vc_post-settings-button' ).trigger( 'click' );
						$( '#vc_ui-panel-post-settings .vc_ui-panel-footer .vc_ui-button-fw' ).trigger( 'click' );
						$( '#vc_ui-panel-post-settings' ).css( 'display', '' );
					}
					if ( response.meta.page_js ) {

						if ( typeof alpus_studio['meta_fields']['page_js'] == 'undefined' )
							alpus_studio['meta_fields']['page_js'] = '';
						if ( alpus_studio['meta_fields']['page_js'].indexOf( response.meta.page_js ) === -1 )
							alpus_studio['meta_fields']['page_js'] += response.meta.page_js;
					}
				}
			}
			if ( response && response.error ) {
				alert( response.error );
			}
		}

		function mergeContent( response ) {
			if ( response ) {
				if ( response.content ) {
					var addID = function( content ) {
						Array.isArray( content ) &&
							content.forEach( function( item, i ) {
								item.elements && addID( item.elements );
								item.elType && ( content[i].id = elementorCommon.helpers.getUniqueId() );
							} );
					};

					if ( Array.isArray( response.content ) ) {
						var isAllWidgets = true;
						response.content.forEach( function( element ) {
							if ( element.elType != 'widget' ) {
								isAllWidgets = false;
								return false;
							}
						} );
						if ( isAllWidgets ) {
							response.content = [{
								elType: 'section',
								elements: [{
									elType: 'column',
									elements: response.content
								}]
							}];
						} else {
							response.content.forEach( function( element, i ) {
								if ( 'widget' == element.elType ) {
									response.content[i] = {
										elType: 'section',
										elements: [{
											elType: 'column',
											elements: element
										}]
									};
								} else if ( 'column' == element.elType ) {
									response.content[i] = {
										elType: 'section',
										elements: element
									};
								}
							} )
						}
					}

					addID( response.content );

					// import studio block to end or add-section
					elementor.getPreviewView().addChildModel( response.content,
						$addStudioSection && $addStudioSection.parent().hasClass( 'elementor-section-wrap' ) ? (
							$addStudioSection.find( '.elementor-add-section-close' ).trigger( 'click' ), {
								at: $addStudioSection.index()
							} ) : {}
					);

					// active save button or save elementor
					if ( elementor.saver && elementor.saver.footerSaver && elementor.saver.footerSaver.activateSaveButtons ) {
						elementor.saver.footerSaver.activateSaveButtons( document, 'publish' );
					} else {
						$e.run( 'document/save/publish' );
					}
				}
				if ( response.meta ) {
					for ( var key in response.meta ) {
						var value = response.meta[key].replace( '/<script.*?\/script>/s', '' ),
							key_data = elementor.settings.page.model.get( key );
						if ( typeof key_data == 'undefined' ) {
							key_data = '';
						}
						if ( !key_data || key_data.indexOf( value ) === -1 ) {
							elementor.settings.page.model.set( key, key_data + value );
						}
						if ( 'page_css' == key ) {
							elementorFrontend.hooks.doAction( 'refresh_page_css', key_data + value );
							$( 'textarea[data-setting="page_css"]' ).val( key_data + value );
						}
					}
				}
				if ( response.error ) {
					alert( response.error );
				}
			}
		}

		function gutenbergMergeContent( response ) {
			if ( response ) {
				if ( response.content ) {
					var blocks = wp.blocks.parse( response.content );
					if ( blocks && blocks.length ) {
						var editor = wp.data.dispatch( 'core/block-editor' );
						editor.insertBlocks( blocks );
					}
				}
				if ( response.meta ) {
					var $page_css = $( '#page_css' );
					var $page_js = $( '#page_js' );
					if ( response.meta.page_css ) {
						$page_css.val( $page_css.val() + '\r\n' + response.meta.page_css );
						$page_css.trigger( 'change' );
					}
					if ( response.meta.page_js ) {
						$page_js.val( $page_js.val() + '\r\n' + response.meta.page_js );
						$page_js.trigger( 'change' );
					}
				}
				if ( response.error ) {
					alert( response.error );
				}
			}
		}

		function showBlocks( e, cur_page, is_search, searchPos = '' ) {
			e.preventDefault();
			var searchPos = searchPos;
			// if still loading
			if ( $( '.blocks-wrapper' ).hasClass( 'loading' ) ) {
				return false;
			}

			var $this = $( this ),
				$search = $( '.blocks-wrapper .demo-filter input[type="search"]' );

			// if toggle is clicked
			if ( e.target.tagName == 'I' ) { // Toggle children
				$this.siblings( 'ul' ).stop().slideToggle( 200 );
				$this.children( 'i' ).toggleClass( $( e.target ).data( 'toggle' ) );
				return false;
			}

			// if active category is clicked
			if ( $this.hasClass( 'active' ) && !$this.parent().hasClass( 'filtered' ) && ( typeof cur_page == 'undefined' || cur_page == 1 ) && ( $search.val() == search ) ) {
				return false;
			}

			if ( is_search ) {
				search = $search.val();
			} else {
				search = '';
			}

			var $list = $( '.blocks-wrapper .blocks-list' ),
				$categories = $( '.blocks-wrapper .block-categories' );
			var $candidateBlocks = $( '#alpus-studio-candidate-blocks' );
			if ( $candidateBlocks.length && searchPos == 'widget-search' && $candidateBlocks.hasClass( 'loading' ) ) {
				return false;
			}
			// if top category is clicked
			if ( typeof $this.data( 'filter-by' ) == 'undefined' && !$this.parent( '.filtered' ).length ) {
				if ( $this.hasClass( 'all' ) ) { // Show all categories
					$categories.removeClass( 'hide' );
					$list.siblings( '.coming-soon' ).remove();

				} else { // Show empty category
					$categories.addClass( 'hide' );
					$list.isotope( 'remove', $list.children() ).css( 'height', '' );
					$list.siblings( '.coming-soon' ).length || $list.before( '<div class="coming-soon">' + alpus_studio.texts.coming_soon + '</div>' );
				}
				$( '.blocks-wrapper .category-list a' ).removeClass( 'active' );
				$this.addClass( 'active' );
			} else {
				alpus_blocks_cur_page = typeof cur_page == 'undefined' ? 1 : parseInt( cur_page, 10 );

				if ( alpus_blocks_cur_page > 1 ) {
					if ( !$categories.hasClass( 'hide' ) ) {
						return;
					}
					$( '.blocks-wrapper' ).addClass( 'infiniteloading' );
				}

				if ( !$categories.hasClass( 'hide' ) ) {
					$list.isotope( 'remove', $list.children() );
					$categories.addClass( 'hide' );
				}

				$list.siblings( '.coming-soon' ).remove();

				var cat = $this.data( 'filter-by' ),
					catTitle = typeof $this.data('title') != undefined ? $this.data('title') : '',
					loaddata = {
						action: 'alpus_studio_filter_category',
						category_id: searchPos == 'widget-search' ? 0 : cat,
						wpnonce: alpus_studio.wpnonce,
						page: alpus_blocks_cur_page,
						type: page_type,
						search: search
					};

				if ( !$( document.body ).hasClass( 'elementor-editor-active' ) && !( $( document.body ).hasClass( 'vc_inline-shortcode-edit-form' ) || $( document.body ).find( '#wpb_visual_composer' ).length > 0 ) && !$( document.body ).hasClass( 'block-editor-page' ) ) {
					loaddata.new_template = true;
				}
				if ( $( '.blocks-wrapper .block-category-favourites.active' ).length && alpus_blocks_cur_page > 1 ) {
					loaddata.current_count = $list.data( 'isotope' ).items.length;
				}
				$( '.blocks-wrapper' ).addClass( 'loading' );
				if ( searchPos == 'widget-search' ) {
					$candidateBlocks.addClass( 'loading' );
				}
				if ( !$( '#alpus-studio-candidate-blocks' ).length ) {
					$( '#elementor-panel-elements-wrapper' ).addClass( 'infiniteloading' );
				}
				$.ajax( {
					url: ajaxurl,
					type: 'post',
					dataType: 'html',
					data: loaddata,
					success: function( response ) {
						$( '#elementor-panel-elements-wrapper' ).removeClass( 'infiniteloading' );
						if ( 'error' == response ) {
							$( '.blocks-wrapper' ).removeClass( 'loading' ).removeClass( 'infiniteloading' );
							$candidateBlocks.removeClass( 'loading' ).removeClass( 'infiniteloading' );
							return;
						}

						var $response = $( response );

						// demo filter
						var total_page = $response.filter( '#total_pages' ).text();
						if ( total_page ) {
							$this.data( 'total-page', parseInt( total_page, 10 ) );
						}
						$response = $response.filter( '.block' );

						var newItems = $response;
						if ( searchPos == 'widget-search' ) {
							if ( $candidateBlocks.length ) {
								$candidateBlocks.append( newItems.clone() );
							} else {
								$( '#elementor-panel-elements-wrapper' ).append( newItems.clone().wrapAll( '<div class="blocks-list" id="alpus-studio-candidate-blocks"></div>' ).parent() );
							}
							$( '#alpus-studio-candidate-blocks' ).removeClass( 'loading' ).removeClass( 'infiniteloading' );
						} else {
							let filterVal = '';
							if ( $( '#elementor-panel-elements-search-input' ).length ) {
								filterVal = $( '#elementor-panel-elements-search-input' ).val();
							}
							if ( $candidateBlocks.length && $( '.blocks-section .demo-filter input[type=search]' ).val() == filterVal ) {
								$candidateBlocks.append( newItems.clone() );
							}
						}

						// first page
						if ( alpus_blocks_cur_page === 1 ) {
							$list.isotope( 'remove', $list.children() );
							if ( searchPos == 'widget-search' ) {
								$list.children().remove();
							}
						}

						// make category active
						$( '.blocks-wrapper .category-list a' ).removeClass( 'active' );
						if ( $this.parent().hasClass( 'category-has-children' ) ) {
							var $icon = $this.children( 'i' ),
								$cat_list = $this.siblings( 'ul' );
							if ( !$icon.hasClass( $icon.data( 'toggle' ).split( ' ' )[0] ) ) {
								$cat_list.stop().slideDown( 200 );
								$icon.toggleClass( $icon.data( 'toggle' ) );
							}
							$cat_list.find( 'li a' ).removeClass( 'active' );
							$cat_list.find( 'li:first-child a' ).addClass( 'active' );
						} else {
							if ( $this.parent().hasClass( 'filtered' ) ) {
								$this.parent().siblings().find( '.all' ).addClass( 'active' );
							} else {
								$this.addClass( 'active' );
							}
						}

						// layout
						$response.imagesLoaded( function() {
							if ( searchPos != 'widget-search' ) {
								$list.append( $response ).isotope( 'appended', $response ).isotope( 'layout' );
							} else {
								$list.children().css( { 'transition-duration': '' } );
							}

							$( '.blocks-wrapper' ).removeClass( 'loading' ).removeClass( 'infiniteloading' );
							$( '.blocks-wrapper .blocks-section' ).trigger( 'scroll' );
						} );
						
                        $list.attr('class', 'blocks-list column-3' + (catTitle ? (' ' + catTitle) : '') + (search ? (' ' + search) : ''));
					}
				} ).fail( function() {
					alert( alpus_studio.texts.loading_failed );
					$( '.blocks-wrapper' ).removeClass( 'loading' ).removeClass( 'infiniteloading' );
					$candidateBlocks.removeClass( 'loading' ).removeClass( 'infiniteloading' );
				} );
			}
		}

		function importBlock( block_id, callback, $obj ) {
			var jqxhr = $.ajax( {
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'alpus_studio_import',
					block_id: block_id,
					wpnonce: alpus_studio.wpnonce,
					type: page_type,
					mine: 'my' == $( '.blocks-wrapper .category-list a.active' ).data( 'filter-by' )
				},
				success: function( response ) {
					if ( page_type == 'e' ) {
						mergeContent( response );
					} else if ( page_type === 'w' ) {
						wpbMergeContent( response, block_id );
					} else {
						gutenbergMergeContent( response );
					}
					$obj && $obj.addClass( 'imported' );
				},
				failure: function() {
					alert( alpus_studio.texts.importing_error );
				}
			} );
			callback && jqxhr.always( callback );
		}

		function importBlockHandler( e ) {
			e.preventDefault();
			var $this = $( this ),
				$block = $this.closest( '.block' );
			$this.attr( 'disabled', 'disabled' );
			$block.addClass( 'doing' );

			importBlock( $this.parent().data( 'id' ), function() {
				$this.prop( 'disabled', false );
				$block.removeClass( 'doing' );
			}, $block );
		}

		function favourBlock() {
			var $this = $( this ),
				$block = $this.closest( '.block' ).addClass( 'doing' ),
				$list = $( '.blocks-wrapper .blocks-list' ),
				$count = $( '.blocks-wrapper .block-category-favourites span' ),
				favourdata = {
					action: 'alpus_studio_favour_block',
					wpnonce: alpus_studio.wpnonce,
					block_id: $this.parent().data( 'id' ),
					type: page_type,
					active: $block.hasClass( 'favour' ) ? 0 : 1,
				};

			if ( $( '.blocks-wrapper .block-category-favourites.active' ).length ) {
				favourdata.current_count = $list.data( 'isotope' ).items.length;
			}

			$.post( ajaxurl, favourdata, function( response ) {
				$block.toggleClass( 'favour' );

				var count = ( parseInt( $count.text().replace( '(', '' ).replace( ')', '' ) ) + ( $block.hasClass( 'favour' ) ? 1 : -1 ) );
				$count.text( '(' + count + ')' ).parent().data( 'total-page', Math.ceil( count / alpus_studio.limit ) );

				if ( typeof favourdata.current_count != 'undefined' ) {
					var $response = $( response );

					$list.isotope( 'remove', $block );
					if ( response && response.trim() ) {
						$list.append( $response ).isotope( 'appended', $response );
					}
					$list.isotope( 'layout' );
					alpus_blocks_cur_page = Math.ceil( favourdata.current_count / alpus_studio.limit );
				}

			} ).always( function() {
				$block.removeClass( 'doing' );
			} );
		}

		function saveMetaField( e ) {
			if ( $( '.postbox-container #wpb_visual_composer' ).length == 0 && alpus_studio['meta_fields'] && vc_post_id ) {
				$.ajax( {
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: { action: 'alpus_studio_save', post_id: vc_post_id, nonce: alpus_studio.wpnonce, fields: alpus_studio['meta_fields'] }
				} );
			}
		}
		function resetSelected() {
			$( '.blocks-wrapper .block.selected' ).removeClass( 'selected' );
			$( '#alpus-new-template-id' ).val( '' );
			$( '#alpus-new-template-type' ).val( '' );
			$( '#alpus-new-template-name' ).val( '' );
		}

		function submitSearchForm( e ) {
			if ( e.keyCode == 13 ) {
				$( this ).closest( 'form' ).trigger( 'submit' );
			}
		}

		function doFilter( e, cur_page, searchPos = '' ) {
			e.preventDefault();
			var $this = $( this );
			if ( typeof cur_page == 'undefined' ) {
				cur_page = 1;
			}

			var $activeCat = $( '.blocks-wrapper .category-list a.active' );

			if ( $this.find( 'input[type="search"]' ).val() || $activeCat.length ) {
				( $activeCat.length && !$activeCat.hasClass( 'all' ) ) ? $activeCat.trigger( 'click', [cur_page, true, searchPos] ) : $( '.blocks-wrapper .filtered>a' ).trigger( 'click', [cur_page, true, searchPos] );
			} else {
				$( '.blocks-wrapper .all' ).trigger( 'click' );
			}
			$this.attr( 'disabled', 'disabled' );
		}

		function openCategory( e ) {
			if ( this.getAttribute( 'data-category' ) ) {
				$( '.blocks-wrapper .block-category-' + this.getAttribute( 'data-category' ) ).trigger( 'click' );
			}
			e.preventDefault();
		}

		function closeStudio() {
			$( '.blocks-wrapper, .blocks-overlay' ).addClass( 'closed' );
		}

		function switchSection( e ) {
			e.preventDefault();

			var $this = $( this ),
				$target = $( $this.attr( 'href' ) );

			if ( !$target.length || $this.hasClass( 'active' ) ) return;

			$this.siblings( '.active' ).removeClass( 'active' );
			$this.addClass( 'active' );
			$target.siblings( '.active' ).removeClass( 'active' );
			$target.addClass( 'active' );
		}

		function deployStudio() {
			$( '#alpus_studio_blocks_wrapper_template' ).after( $( '#alpus_studio_blocks_wrapper_template' ).text() ).remove();
			if ( $( '.blocks-wrapper .blocks-list' ).length ) {
				$( document.body )
					.on( 'click', '.blocks-wrapper .category-list a', showBlocks )
					.on( 'click', '.blocks-list .import', importBlockHandler )
					.on( 'click', '.blocks-wrapper .blocks-list .select', themeCoreAdmin.selectBlock )
					.on( 'click', '.blocks-list .favourite', favourBlock )
					.on( 'click', '.blocks-wrapper .mfp-close, .blocks-overlay', closeStudio )
					.on( 'click', '.blocks-wrapper .block-category', openCategory )
					.on( 'keydown', '.blocks-wrapper input', submitSearchForm )
					.on( 'submit', '.blocks-wrapper .demo-filter form', doFilter )
					.on( 'click', '#vc_button-update', saveMetaField )
					.on( 'change', '#alpus-elementor-studio', resetSelected )
					.on( 'change', '#alpus-wpbakery-studio', resetSelected )
					.on( 'click', '.blocks-wrapper a.section-switch', switchSection );
			}
			$( '.blocks-wrapper img[data-original]' ).each( function() {
				$( this ).attr( 'src', $( this ).data( 'original' ) );
				$( this ).removeAttr( 'data-original' );
			} );

			$( '.blocks-wrapper' ).imagesLoaded( function() {
				setTimeout( function() {
					if ( !$( '.blocks-wrapper .blocks-list' ).hasClass( 'initialized' ) ) {
						$( '.blocks-wrapper .blocks-list' ).addClass( 'initialized' ).isotope( {
							itemSelector: '.block',
							layoutMode: 'masonry'
						} );

						$( '.blocks-wrapper .blocks-section' ).on( 'scroll', function() {
							var $this = $( this ),
								$wrapper = $this.closest( '.blocks-wrapper' );
							if ( $wrapper.length ) {
								var top = $this.children().offset().top + $this.children().height() - $this.offset().top - $this.height(),
									total = parseInt( $wrapper.find( '.category-list a.active' ).data( 'total-page' ), 10 ),
									isAllActive = false;

								if ( search && $wrapper.find( '.category-list a.active' ).hasClass( 'all' ) ) {
									isAllActive = true;
									total = parseInt( $wrapper.find( '.category-list .filtered a' ).data( 'total-page' ), 10 );
								}

								if ( top <= 10 && !$wrapper.hasClass( 'loading' ) && total >= alpus_blocks_cur_page + 1 ) {
									var filterBy = $wrapper.find( '.category-list a.active' ).data( 'filter-by' );
									if ( search || parseInt( filterBy, 10 ) || 'blocks' == filterBy || '*' == filterBy || 'my' == filterBy ) {
										if ( isAllActive ) {
											$wrapper.find( '.category-list .filtered>a' ).trigger( 'click', [alpus_blocks_cur_page + 1, search ? true : false] );
										} else {
											$wrapper.find( '.category-list a.active' ).trigger( 'click', [alpus_blocks_cur_page + 1, search ? true : false] );
										}
									} else if ( 'all' != filterBy ) {
										$wrapper.find( '.demo-filter .btn' ).trigger( 'click', [alpus_blocks_cur_page + 1] );
									}
								}
							}
						} );

						$( '.blocks-wrapper .blocks-section' ).trigger( 'scroll' );
					}
					$( '.blocks-wrapper .blocks-list' ).isotope( 'layout' );
				}, 100 );
			} );
		}

		function openStudio( e ) {
			e.preventDefault();
			$( '.blocks-wrapper, .blocks-overlay' ).removeClass( 'closed' );
		}

		function confirmPageType( e ) {
			var new_type = '';
			if ( 'type' == $( e.target.closest( '.alpus-new-template-form' ) ).find( '.template-type' ).val() ) {
				new_type = 'g';
			} else {
				new_type = 'e';
			}
			if ( page_type != new_type ) {
				page_type = new_type;

				$( '.blocks-wrapper' ).addClass( 'loading' );

				$.ajax( {
					url: ajaxurl,
					type: 'post',
					dataType: 'html',
					data: {
						action: 'alpus_studio_filter_category',
						wpnonce: alpus_studio.wpnonce,
						page: 1,
						type: page_type,
						full_wrapper: true,
						new_template: true
					},
					success: function( response ) {
						if ( 'error' != response ) {
							var $response = $( response ),
								$list = $( 'blocks-wrapper .blocks-list' );

							$list.hasClass( 'initialized' ) && $list.isotope( 'remove', $list.children() ).css( 'height', '' );
							$( '.blocks-wrapper .block-categories.hide' ).removeClass( 'hide' );
							$( '.blocks-wrapper .category-list' ).html( $( $response.html() ).find( '.category-list' ).html() );
						}
						$( '.blocks-wrapper' ).removeClass( 'loading' );
					}
				} ).fail( function() {
					alert( alpus_studio.texts.loading_failed );
					$( '.blocks-wrapper' ).removeClass( 'loading' );
				} );
			}
			openStudio.call( this, e );
		}

		function importStartTemplate() {
			alpus_studio.start_template && importBlock( parseInt( alpus_studio.start_template ) );
			if ( alpus_studio.start_template_content ) {
				if ( 'e' == page_type ) {
					mergeContent( alpus_studio.start_template_content );
				} else if ( 'w' == page_type ) {
					wpbMergeContent( alpus_studio.start_template_content );
				} else {
					gutenbergMergeContent( alpus_studio.start_template_content );
				}
			}
		}

		if ( $( '.blocks-wrapper' ).length ) {
			$( '#elementor-panel' ).on( 'mousewheel', '#elementor-panel-content-wrapper', function() {
				var $candidateBlocks = $( '#alpus-studio-candidate-blocks' );
				if ( $candidateBlocks.length && $( '.blocks-section .demo-filter input[type=search]' ).val() ) {
					var top = $candidateBlocks.offset().top - $( this ).offset().top + $candidateBlocks.height() - $( this ).height();
					if ( top <= 10 && !$candidateBlocks.hasClass( 'loading' ) ) {

						var $wrapper = $( '.blocks-wrapper' );
						if ( $wrapper.length ) {
							var total = parseInt( $wrapper.find( '.category-list a.active' ).data( 'total-page' ), 10 ), isAllActive = false;

							if ( $wrapper.find( '.category-list a.active' ).hasClass( 'all' ) ) {
								isAllActive = true;
								total = parseInt( $wrapper.find( '.category-list .filtered a' ).data( 'total-page' ), 10 );
							}

							if ( total >= alpus_blocks_cur_page + 1 ) {
								if ( isAllActive ) {
									$wrapper.find( '.category-list .filtered>a' ).trigger( 'click', [alpus_blocks_cur_page + 1, true, 'widget-search'] );
								} else {
									$wrapper.find( '.category-list a.active' ).trigger( 'click', [alpus_blocks_cur_page + 1, true, 'widget-search'] );
								}
								$candidateBlocks.addClass( 'infiniteloading' );
							}
						}
					}
				}
			} );
			$( document.body ).on( 'input', '#elementor-panel-elements-search-input', _.debounce( function() {
				var $this = $( this );
				if ( $this.val().length < 3 ) {
					return;
				}
				$( '#alpus-studio-candidate-blocks' ).remove();
				$( '.blocks-section .demo-filter input[type=search]' ).val( $this.val() );
				$( '.blocks-wrapper .demo-filter form' ).trigger( 'submit', [1, 'widget-search'] );
			}, 150 ) );
			// Studio candidate preview for elementor preview
			$( 'body' ).on( 'mouseenter', '#elementor-panel-inner #alpus-studio-candidate-blocks > .block', function( e ) {
				var $this = $( this ),
					$img = $this.find( 'img' ),
					$title = $this.find( '.block-title' );
				if ( !$( 'body' ).find( '.candidate-preivew' ).length ) {
					$( '#elementor-panel-inner' ).prepend( '<div class="candidate-preivew"><figure class="candidate-preview-image"></figure><div class="candidate-preview-title"></div></div>' );
				}

				$( '.candidate-preview-image' ).empty().prepend( $img.clone() );
				$( '.candidate-preview-title' ).empty().prepend( $title.clone() );
				$( '.candidate-preivew' ).addClass( 'active' );
				setTimeout( function() {
					$( '.candidate-preview-image' ).delay( 300 ).addClass( 'active' );
				}, 100 );
			} ).on( 'mouseleave', '#elementor-panel-inner #alpus-studio-candidate-blocks > .block', function( e ) {
				$( '.candidate-preivew' ).removeClass( 'active' );
				$( '.candidate-preview-image' ).removeClass( 'active' );
			} );
		}

		$( document.body )
			.on( 'click', '#alpus-elementor-panel-alpus-studio, #vce-alpus-studio-trigger, #wpb-alpus-studio-trigger, #gutenberg-alpus-studio-trigger', openStudio )
			.on( 'click', '#alpus-new-studio-trigger', confirmPageType )

		importStartTemplate();

		// Add studio toogler in Gutenberg Editor bar
		setTimeout( function() {
			if ( typeof alpus_core_vars.template_type != 'undefined' && alpus_core_vars.template_type == 'type' ) {
				$( '#editor .edit-post-header-toolbar__left' ).append( '<span id="gutenberg-alpus-studio-trigger" class="components-button has-icon is-primary" title="' + alpus_studio.texts.theme_display_name + ' Studio"><i class="alpus-mini-logo"></i></span>' );
			}
		}, 100 );

		// Open studio block popup when template is empty
		var _jQuery = typeof document.getElementById( 'elementor-preview-iframe' ) != 'undefined' && document.getElementById( 'elementor-preview-iframe' ) ? document.getElementById( 'elementor-preview-iframe' ).contentWindow.jQuery : '';
		if ( _jQuery ) {
			var $active_editor = _jQuery( '.elementor-edit-area-active' );
		}
		if ( typeof $active_editor != 'undefined' && $active_editor.length ) { // elementor editor
			if ( $active_editor.find( '.elementor-section-wrap' ).children().length == 0 ) { // empty
				$( '#alpus-elementor-panel-alpus-studio' ).trigger( 'click' );
				
				if ( window.top.alpus_core_vars && window.top.alpus_core_vars.template_type && 
					( window.top.alpus_core_vars.template_type != 'block' ) ) {
					$('#studio-section .block-categories [data-category="' + window.top.alpus_core_vars.template_type + '"]').trigger('click');
				}
			}
		} else if ( $( 'body' ).hasClass( 'block-editor-page' ) && $( '.block-editor' ).length && typeof alpus_core_vars != 'undefined' && alpus_core_vars.template_type == 'type' ) {
			setTimeout( function() {
				if ( $( '.is-root-container>.wp-block:not(.block-list-appender)' ).length == 0 ) {
					$( '#gutenberg-alpus-studio-trigger' ).trigger( 'click' );
					$('#studio-section .block-categories [data-category="type"]').trigger('click');
				}
			}, 150 );
		}
	}
} )( jQuery );