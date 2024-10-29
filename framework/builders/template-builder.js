/**
 * Alpus Core Admin Library
 * 
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
'use strict';
window.themeCoreAdmin || ( window.themeCoreAdmin = {} );

/**
 * Metabox Management
 * - show/hide metaboxes when post format is changed
 */
( function ( $ ) {
    // Public Properties
    themeCoreAdmin.Metabox = function () {
        var initColorPicker = function () {
            if ( $.fn.wpColorPicker ) {
                $( 'input.alpus-color-picker:not(.wp-color-picker)' ).wpColorPicker();
            }
        };

        var changePostFormat = function () {
            //media embed code area
            var post_type = $( '.editor-post-format select' );

            if ( post_type == 'video' ) {
                $( '#featured_video' ).closest( '.rwmb-field' ).removeClass( 'hidden' );
                $( '[name="supported_images"]' ).closest( '.rwmb-field' ).addClass( 'hidden' );
            } else {
                $( '#featured_video' ).closest( '.rwmb-field' ).addClass( 'hidden' );
                $( '[name="supported_images"]' ).closest( '.rwmb-field' ).removeClass( 'hidden' );
            }
        };

        $( window ).on( 'load', changePostFormat );
        $( window ).on( 'load', initColorPicker );
        $( 'body' ).on( 'change', '.editor-post-format select', changePostFormat );

        // metabox condition
        var condition_fields = {};
        $( '.rwmb-meta-box .alpus-metabox-condition' ).each( function () {
            var $this = $( this ),
                condition = $this.data( 'condition' ).split( '=' );
            if ( typeof condition_fields[ condition[ 0 ] ] == 'undefined' ) {
                condition_fields[ condition[ 0 ] ] = {};
            }
            if ( typeof condition_fields[ condition[ 0 ] ][ condition[ 1 ] ] == 'undefined' ) {
                condition_fields[ condition[ 0 ] ][ condition[ 1 ] ] = [];
            }
            condition_fields[ condition[ 0 ] ][ condition[ 1 ] ].push( $this );
        } );

        var triggerFn = function ( $condition_fields, $required_obj ) {
            for ( var val in $condition_fields ) {
                var $obj_arr = $condition_fields[ val ];
                if ( $required_obj.val() == val ) {
                    $obj_arr.forEach( function ( $obj ) {
                        $obj.closest( '.rwmb-row' ).slideDown();
                    } );
                } else {
                    $obj_arr.forEach( function ( $obj ) {
                        $obj.closest( '.rwmb-row' ).slideUp();
                    } );
                }
            }
        };

        for ( var id_name in condition_fields ) {
            var $required_obj = $( '#' + id_name );
            if ( !$required_obj.length ) {
                continue;
            }

            triggerFn( condition_fields[ id_name ], $required_obj );
            $required_obj.on( 'change', function () {
                triggerFn( condition_fields[ id_name ], $required_obj );
            } );
        }
    }

    /**
     * Sidebar Builder
     * - register new sidebar
     * - remove registered sidebar
     */
    themeCoreAdmin.Sidebar = function () {
        var addSidebar = function () {
            var name = prompt( "Widget Area Name" ),
                slug = '',
                maxnum = -1,
                $this = $( this );

            if ( !name ) {
                return;
            }

            $this.attr( 'disabled', 'disabled' );

            slug = name.toLowerCase().replace( /(\W|_)+/g, '-' );
            if ( '-' == slug[ 0 ] && '-' == slug[ slug.length - 1 ] ) {
                slug = slug.slice( 1, -1 );
            } else if ( '-' == slug[ 0 ] ) {
                slug = slug.slice( 1 );
            } else if ( '-' == slug[ slug.length - 1 ] ) {
                slug = slug.slice( 0, -1 );
            }
            if ( alpus_core_vars.sidebars ) {
                var slugs = Object.keys( alpus_core_vars.sidebars );
                slugs.forEach( function ( item ) {
                    if ( 0 === item.indexOf( slug ) ) {
                        var num = item.replace( slug, '' );

                        if ( '' == num ) {
                            maxnum = Math.max( maxnum, 0 );
                        } else if ( Number( num.slice( 1 ) ) ) {
                            maxnum = Math.max( maxnum, Number( num.slice( 1 ) ) );
                        }
                    }
                } )
            }

            if ( maxnum >= 0 ) {
                slug = slug + '-' + ( maxnum + 1 );
            }

            $.ajax( {
                url: alpus_core_vars.ajax_url,
                data: {
                    action: 'alpus_add_widget_area',
                    nonce: alpus_core_vars.nonce,
                    name: name,
                    slug: slug
                },
                type: 'post',
                success: function ( response ) {
                    alpus_core_vars.sidebars[ slug ] = name;
                    var $url = '';
                    if ( response.data.url ) {
                        $url = response.data.url;
                    }
                    $( '<tr id="' + slug + '" class="sidebar"><td class="title column-title">' + ( $url ? '<a href="' + $url + '">' : '' ) + name + ( $url ? '</a>' : '' ) + '</td><td class="slug column-slug">' + slug + '</td><td class="remove column-remove"><a href="#">Remove</a></td></tr>' )
                        .appendTo( $( '#sidebar_table tbody#the-list' ) )
                        .hide().fadeIn();

                    $this.removeAttr( 'disabled' );
                }
            } ).fail( function ( response ) {
                console.log( response );
            } );
        };

        var removeSidebar = function () {
            if ( !confirm( "Do you want to remove this sidebar?" ) ) {
                return;
            }

            var $this = $( this ),
                slug = $this.closest( 'tr' ).find( '.column-slug' ).text();

            $.ajax( {
                url: alpus_core_vars.ajax_url,
                data: {
                    action: 'alpus_remove_widget_area',
                    nonce: alpus_core_vars.nonce,
                    slug: slug
                },
                type: 'post',
                success: function ( response ) {
                    delete alpus_core_vars.sidebars[ slug ];

                    $this.closest( 'tr' ).fadeOut( function () {
                        $( this ).remove();
                    } );

                    $this.removeAttr( 'disabled' );
                }
            } ).fail( function ( response ) {
                console.log( response );
            } );
        };

        $( 'body' ).on( 'click', '.alpus-wrap #add_widget_area', addSidebar );
        $( 'body' ).on( 'click', '#sidebar_table .column-remove > a', removeSidebar );
    }

    /**
     * Template Wizard
     * - show template wizard popup before you create a new template
     * - start from prebuilt template or blank
     */
    themeCoreAdmin.TemplateWizard = ( function () {

        function closeModalByClickOverlay() {
            closeModal( $( this ).next( '.alpus-modal' ) );
        }

        function closeModalByClickCloseButton() {
            closeModal( $( this ).parent( '.alpus-modal' ) );
        }

        function openModal( selector ) {
            var $modal = $( selector );
            $modal = $modal.add( $modal.prev( '.alpus-modal-overlay' ) );

            $modal.addClass( 'alpus-modal-open' );
            setTimeout( function () {
                $modal.addClass( 'alpus-modal-fade' );
            } );
        }

        function closeModal( $modal ) {
            $modal = $modal.add( $modal.prev( '.alpus-modal-overlay' ) );

            $modal.removeClass( 'alpus-modal-fade' );
            setTimeout( function () {
                $modal.removeClass( 'alpus-modal-open' );
            }, 50 );
        }

        function showTemplateWizard( e ) {

            e.preventDefault();

            openModal( '#alpus_new_template' );

            $( '.template-name' ).focus();

            $( '#alpus-new-template-id' ).add( '#alpus-new-template-type' ).add( '#alpus-new-template-name' ).val( '' );
        };

        function createNewTemplate( e ) {
            var name = $( '.alpus-modal .template-name' ).val();
            if ( !name ) {
                $( '.alpus-modal .template-name' ).focus();
                return;
            }

            $.ajax( {
                url: alpus_core_vars.ajax_url,
                data: {
                    action: 'alpus_save_template',
                    nonce: alpus_core_vars.nonce,
                    type: $( '.alpus-modal .template-type' ).val(),
                    name: name,
                    template_id: $( '#alpus-new-template-id' ).val(),
                    template_type: $( '#alpus-new-template-type' ).val(),
                    template_category: $( '.alpus-new-template-form .template-type' ).val(),
                    page_builder: $( '#alpus-elementor-studio' ).length ? 'elementor' : ''
                },
                type: 'post',
                success: function ( response ) {
                    var post_id = parseInt( response.data );
                    if ( $( '#alpus-elementor-studio' ).length ) {
                        window.location.href = $( '.alpus-add-new-template' )
                            .attr( 'href' )
                            .replace(
                                'edit.php?post_type=' + alpus_admin_vars.theme + '_template',
                                ( post_id && 'type' != $( '.alpus-modal .template-type' ).val() ) ? ( 'post.php?post=' + post_id + '&action=elementor'
                                ) : 'post.php?post=' + post_id + '&action=edit&post_type=' + alpus_admin_vars.theme + '_template'
                            );
                    } else {
                        window.location.href = $( '.alpus-add-new-template' )
                            .attr( 'href' )
                            .replace(
                                'edit.php?post_type=' + alpus_admin_vars.theme + '_template',
                                ( post_id && 'post.php?post=' + post_id + '&action=edit&post_type=' + alpus_admin_vars.theme + '_template' )
                            );
                    }
                }
            } ).fail( function ( response ) {
                console.log( response );
            } );
        };

        function triggerCreateAction( e ) {
            if ( 13 == e.keyCode && $( '.alpus-modal #alpus-create-template-type' ).length ) {
                createNewTemplate();
                return;
            }
        };

        return {
            init: function () {
                $( document.body )
                    .on( 'click', '.alpus-add-new-template', showTemplateWizard )
                    .on( 'click', '.alpus-modal-close', closeModalByClickCloseButton )
                    .on( 'click', '.alpus-modal-overlay', closeModalByClickOverlay )
                    .on( 'click', '.alpus-modal #alpus-create-template-type', createNewTemplate )
                    .on( 'keydown', triggerCreateAction )
            }
        }
    } )();

    /**
     * Page Builder Addons
     * - studio
     * - template condition
     */
    themeCoreAdmin.BuilderAddons = function () {
        var insertElementorAddons = function () {
            var firstLoad = true;

            if ( $( document.body ).hasClass( 'elementor-editor-active' ) && typeof elementor != 'undefined' ) {
                elementor.on( 'panel:init', function () {
                    var content = '<div id="alpus-elementor-addons" class="elementor-panel-footer-tool tooltip-target">\
                        <span class="alpus-elementor-addons-toggle" data-tooltip="' + alpus_core_vars.texts.elementor_addon_settings + '">\
                        <i class="alpus-mini-logo"></i></span><div class="dropdown-box"><ul class="options">';

                    if ( alpus_core_vars.builder_addons.length ) {
                        alpus_core_vars.builder_addons.forEach( function ( value ) {
                            if ( value[ 'elementor' ] ) {
                                content += value[ 'elementor' ];
                            }
                        } );
                    }

                    content += '</ul></div></div>';
                    $( content ).insertAfter( '#elementor-panel-footer-saver-preview' )
                        .find( '.alpus-elementor-addons-toggle' ).tipsy( {
                            gravity: 's',
                            title: function title() {
                                return this.getAttribute( 'data-tooltip' );
                            }
                        } );
                } );


                elementor.on( 'document:loaded', function () {
                    if ( firstLoad ) {
                        $( 'body' )
                            // Show Alpus Elementor Addon
                            .on( 'click', '.alpus-elementor-addons-toggle', function ( e ) {
                                $( this ).siblings( '.dropdown-box' ).toggleClass( 'show' );
                                $( this ).toggleClass( 'dropdown-active' );
                            } )
                            .on( 'click', function ( e ) {
                                $( '#alpus-elementor-addons .dropdown-box' ).removeClass( 'show' );
                                $( '.alpus-elementor-addons-toggle.dropdown-active' ).removeClass( 'dropdown-active' );
                            } )
                            .on( 'click', '#alpus-elementor-addons', function ( e ) {
                                e.stopPropagation();
                            } )
                            .on( 'click', '#alpus-custom-css', function ( e ) { // open custom css code panel
                                $( '#elementor-panel-footer-settings' ).trigger( 'click' );
                                $( '.elementor-tab-control-advanced a' ).trigger( 'click' );
                            } )
                            .on( 'click', '#alpus-custom-js', function ( e ) { // open custom js code panel
                                $( '#elementor-panel-footer-settings' ).trigger( 'click' );
                                $( '.elementor-tab-control-advanced a' ).trigger( 'click' );
                                setTimeout( function () {
                                    $( '.elementor-control-alpus_custom_js_settings .elementor-panel-heading' ).trigger( 'click' );
                                }, 100 );
                            } )
                            .on( 'click', '#alpus-edit-area', function ( e ) { // open edit area resize panel
                                $( '#elementor-panel-footer-settings' ).trigger( 'click' );
                                $( '.elementor-control-alpus_edit_area .elementor-section-toggle' ).trigger( 'click' );
                            } )
                        
                        firstLoad = false;
                    }
                } )
            }
        };

        var insertWPBAddons = function () {
            if ( $( document.body ).hasClass( 'vc_editor vc_inline-shortcode-edit-form' ) || $( '#post-body #wpb_visual_composer' ).length ) {
                // Alpus WPBakery Addons

                var initPopupOptionsPanel = function () {
                    var changePopupOptions = function () {
                        if ( !vc.$frame_body ) {
                            vc.alpus_popup_options_view.hide();
                            return;
                        }

                        var $wrapper = $( this ).closest( '.vc_ui-alpus-panel' ),
                            width = $wrapper.find( '#vc_popup-width-field' ).val(),
                            hPos = $wrapper.find( '#vc_popup-h_pos-field' ).val(),
                            vPos = $wrapper.find( '#vc_popup-v_pos-field' ).val(),
                            border = $wrapper.find( '#vc_popup-border-field' ).val(),
                            top = $wrapper.find( '#vc_popup-margin-top-field' ).val(),
                            right = $wrapper.find( '#vc_popup-margin-right-field' ).val(),
                            bottom = $wrapper.find( '#vc_popup-margin-bottom-field' ).val(),
                            left = $wrapper.find( '#vc_popup-margin-left-field' ).val();

                        vc.$frame_body.find( '.mfp-alpus .mfp-content' ).css( { justifyContent: hPos, alignItems: vPos } );
                        vc.$frame_body.find( '.mfp-alpus .popup' ).css( { width: ( width ? Number( width ) + 'px' : '600px' ), marginTop: ( top ? top + 'px' : '' ), marginRight: ( right ? right + 'px' : '' ), marginBottom: ( bottom ? bottom + 'px' : '' ), marginLeft: ( left ? left + 'px' : '' ) } );
                        vc.$frame_body.find( '.mfp-alpus .alpus-popup-content' ).css( { borderRadius: ( border ? Number( border ) + 'px' : '' ) } );
                    };

                    vc.AlpusPopupOptionsUIPanelEditor = vc.PostSettingsPanelView.vcExtendUI( vc.HelperPanelViewHeaderFooter ).vcExtendUI( vc.HelperPanelViewResizable ).vcExtendUI( vc.HelperPanelViewDraggable ).vcExtendUI( {
                        panelName: "alpus_popup_options",
                        uiEvents: {
                            setSize: "setEditorSize",
                            show: "setEditorSize"
                        },
                        setSize: function () {
                            this.trigger( "setSize" )
                        },
                        setDefaultHeightSettings: function () {
                            this.$el.css( "height", "70vh" )
                        },
                        setEditorSize: function () {
                            this.editor.setSizeResizable()
                        }
                    } );

                    vc.alpus_popup_options_view = new vc.AlpusPopupOptionsUIPanelEditor( {
                        el: "#vc_ui-panel-alpus-popup-options"
                    } );

                    if ( window.vc.ShortcodesBuilder ) {
                        window.vc.ShortcodesBuilder.prototype.save = function ( status ) { // update WPB save function
                            var string = this.getContent(),
                                data = {
                                    action: "vc_save"
                                };
                            data.vc_post_custom_css = window.vc.$custom_css.val();
                            data.content = this.wpautop( string );
                            status && ( data.post_status = status,
                                $( ".vc_button_save_draft" ).hide( 100 ),
                                $( "#vc_button-update" ).text( window.i18nLocale.update_all ) ),
                                window.vc.update_title && ( data.post_title = this.getTitle()
                                );

                            var $wrapper = $( '#vc_ui-panel-alpus-popup-options' ),
                                width = $wrapper.find( '#vc_popup-width-field' ).val(),
                                hPos = $wrapper.find( '#vc_popup-h_pos-field' ).val(),
                                vPos = $wrapper.find( '#vc_popup-v_pos-field' ).val(),
                                border = $wrapper.find( '#vc_popup-border-field' ).val(),
                                top = $wrapper.find( '#vc_popup-margin-top-field' ).val(),
                                right = $wrapper.find( '#vc_popup-margin-right-field' ).val(),
                                bottom = $wrapper.find( '#vc_popup-margin-bottom-field' ).val(),
                                left = $wrapper.find( '#vc_popup-margin-left-field' ).val(),
                                animation = $wrapper.find( '#vc_popup-animation-field' ).val(),
                                duration = $wrapper.find( '#vc_popup-anim-duration-field' ).val();

                            data.alpus_popup_options = {
                                width: ( width ? width : 600 ),
                                h_pos: ( hPos ? hPos : 'center' ),
                                v_pos: ( vPos ? vPos : 'center' ),
                                border: ( border ? border : '0' ),
                                top: ( top ? top : '' ),
                                right: ( right ? right : '' ),
                                bottom: ( bottom ? bottom : '' ),
                                left: ( left ? left : '' ),
                                popup_animation: animation,
                                popup_anim_duration: ( duration ? duration : 400 )
                            };

                            this.ajax( data ).done( function () {
                                window.vc.unsetDataChanged(),
                                    window.vc.showMessage( window.i18nLocale.vc_successfully_updated || "Successfully updated!" )
                            } );
                        };
                    }

                    $( 'body' )
                        .on( 'click', '.alpus-wpb-addons #alpus-popup-options', function ( e ) {
                            e && e.preventDefault && e.preventDefault();
                            vc.alpus_popup_options_view.render().show();
                        } )
                        .on( 'click', '#vc_ui-panel-alpus-popup-options .vc_ui-button[data-vc-ui-element="button-save"]', changePopupOptions );
                };

                // Init Alpus Panels
                if ( alpus_core_vars.wpb_preview_panels ) {
                    Object.keys( alpus_core_vars.wpb_preview_panels ).forEach( function ( key ) {
                        $( '#vc_ui-panel-row-layout' ).before( $( alpus_core_vars.wpb_preview_panels[ key ] ) );
                    } )
                }

                if ( $( '.alpus-wpb-addons #alpus-popup-options' ).length ) {
                    initPopupOptionsPanel();
                }
            }
        };

        insertElementorAddons();
        insertWPBAddons();
    }

    /* Alpus Core Admin Initialize */
    $( document ).on( 'ready', function () {

        themeCoreAdmin.Metabox();
        ( 'undefined' !== typeof alpus_core_vars.sidebars ) && themeCoreAdmin.Sidebar();
        themeCoreAdmin.TemplateWizard.init();
        ( 'undefined' !== typeof alpus_core_vars.condition_network ) && themeCoreAdmin.TemplateCondition();
        themeCoreAdmin.BuilderAddons();
    } );
} )( jQuery );
