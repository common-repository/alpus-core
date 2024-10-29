/**
 * Alpus Elementor Admin
 * 
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
jQuery( document ).ready( function( $ ) {
    'use strict';

    var themeElementorAdmin = {
        init: function() {
            this.initCustomCSS();
            this.initCustomJS();
            this.addStudioButtons();
            this.initArchiveSinglePreview();
            this.initTooltip();
            this.repeaterPopup();

            elementor.hooks.addFilter( 'panel/elements/regionViews', function( panel ) {
                var categories = panel.categories.options.collection;
                var categoryIndex = categories.findIndex( {
                    name: "basic"
                } );

                var libraryIndex = categories.findIndex( {
                    name: "alpus-notice"
                } );

                if ( -1 === libraryIndex && categoryIndex ) {
                    categories.add( {
                        name: "alpus-notice",
                        title: alpus_studio.texts.theme_display_name + wp.i18n.__( ' Library', 'alpus-core' ),
                        defaultActive: 1,
                        items: []
                    }, {
                        at: categoryIndex - 1
                    } );
                }
                return panel;
            } );

            if ( typeof Marionette != 'undefined' && Marionette.ItemView && Marionette.Behavior ) {
                class alpusStudioItem extends Marionette.ItemView {
                    className() {
                        return 'elementor-panel-category-items-alpus-notice';
                    }
                    getTemplate() {
                        return '#tmpl-alpus-elementor-studio-notice';
                    }
                }

                class alpusStudioHandle extends Marionette.Behavior {
                    initialize() {
                        if ( 'alpus-notice' == this.view.options.model.get( 'name' ) ) {
                            this.view.emptyView = alpusStudioItem;
                        }
                    }
                }
                elementor.hooks.addFilter( 'panel/category/behaviors', function( behaviors ) {
                    return Object.assign( {}, behaviors, {
                        studioNotice: {
                            behaviorClass: alpusStudioHandle
                        }
                    } );
                } );
            }

            elementor.on( 'panel:init', function() {
                elementor.panel.currentView.on( 'set:page', themeElementorAdmin.panelChange );
                elementor.channels.editor.on( 'section:activated', themeElementorAdmin.changeControls );
            } );

            if ( typeof $e != 'undefined' ) {
                $e.commands.on( 'run:before', function( component, command, args ) {
                    if ( 'document/elements/delete' == command && args && args.containers && args.containers.length ) {
                        args.containers.forEach( function( cnt ) {
                            elementorFrontend.hooks.doAction( 'alpus_elementor_element_before_delete', cnt.model );
                        } );
                    }
                } );
                $e.commands.on( 'run:after', function( component, command, args ) {
                    if ( 'document/elements/create' == command && args && args.model && args.model.id ) {
                        elementorFrontend.hooks.doAction( 'alpus_elementor_element_after_add', args.model );
                    }
                } );
                $( document.body ).on( 'click', '#elementor-panel-saver-button-publish:not(.elementor-disabled)', function( e ) {
                    if ( alpus_core_vars.layout_save ) {
                        $( '#alpus-elementor-panel-alpus-studio' ).trigger( 'click' );
                        $('.blocks-section-switch [href="#layout-section"]').trigger('click');
                    }
                } );
            }
        },
        /**
         * Elementor grid layout columns in control.
         * 
         * On Desktop
         * On Tablet Landscape
         * On Tablet Portrait
         * On Mobile Landscape
         * On Mobile Portrait
         * 
         * @since 1.2.0
         */
        initLayoutColumns: function() {
            // $( '.elementor-control-col_cnt .elementor-control-title' ).text( 'Columns ( >= ' + ( elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet.value + 1 ) + 'px)' );
            // $( '.elementor-control-col_cnt_tablet .elementor-control-title' ).text( 'Columns ( >= ' + ( elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile.value + 1 ) + 'px)' );
            // $( '.elementor-control-col_cnt_mobile .elementor-control-title' ).text( 'Columns ( >= 576px )' );

            var $bgControl = $( '.elementor-control-background_background.elementor-control-type-choose.elementor-group-control.elementor-label-inline' );
            if ( $bgControl.length > 0 && $bgControl.find( '.elementor-choices label' ).length == 4 ) {
                $bgControl.removeClass( 'elementor-label-inline' ).addClass( 'elementor-label-block' );
            }
            var $imageSizeControl = $( '.elementor-group-control-image-size.elementor-label-inline' );
            if ( $imageSizeControl.length > 0 ) {
                $imageSizeControl.removeClass( 'elementor-label-inline' ).addClass( 'elementor-label-block' );
            }
        },
        /**
         * Elementor Repeater Popup
         * 
         * @since 1.2.0
         */
        repeaterPopup: function() {
            $( document.body ).on( 'mouseup', '.elementor-repeater-row-item-title', function() {
                var $this = $( this );
                var $thisControls = $this.parent().siblings( '.elementor-repeater-row-controls' );
                $thisControls.removeClass( 'editable-effect' );
                if ( window.innerHeight > $this.offset().top + 546 ) {
                    $thisControls.css( { 'top': $this.offset().top + 'px', bottom: '' } );
                } else {
                    $thisControls.css( { 'bottom': '46px', 'top': '' } );
                }
                if ( $this.closest( '.elementor-repeater-fields-wrapper' ).find( '.editable' ).length == 0 ) {
                    $thisControls.addClass( 'editable-effect' );
                }
            } ).on( 'click', function( e ) {
                if ( $( e.target ).closest( '.elementor-repeater-fields' ).length == 0 && $( e.target ).closest( '.pcr-app' ).length == 0 && !$( e.target ).hasClass( 'select2-search__field' ) ) {
                    $( '.elementor-repeater-row-controls.editable' ).removeClass( 'editable editable-effect' );
                }
            } );
        },
        /**
         * Elementor Control Description
         * 
         * @since 1.2.0
         */
        initTooltip: function() {
            $( document ).on( 'mouseover', '.elementor-control .elementor-control-input-wrapper', function() {
                var $this = $( this );
                var $description = $this.parent().siblings( '.elementor-control-field-description' );
                if ( $description.length == 0 ) {
                    $description = $this.siblings( '.elementor-control-field-description' );
                }
                $description.addClass( 'show' );
                if ( $this.closest( '.elementor-label-block' ).length || $this.closest( '.elementor-control-type-image_choose' ).length ) {
                    $description.addClass( 'block-pos' );
                }
                var $control = $this.closest( '.elementor-control' ).get( 0 );
                if ( $this.closest( '.elementor-repeater-row-controls' ).length && $control.offsetTop + $control.offsetHeight + $description.outerHeight() >= 490 ) {
                    $description.addClass( 'show-top' );
                }
            } ).on( 'mouseout', '.elementor-control .elementor-control-input-wrapper', function( e ) {
                var $this = $( this );
                var $description = $this.parent().siblings( '.elementor-control-field-description' );
                if ( $description.length == 0 ) {
                    $description = $this.siblings( '.elementor-control-field-description' );
                }
                if ( !$( e.relatedTarget ).hasClass( 'elementor-control-field-description show' ) ) {
                    $description.removeClass( 'show block-pos show-top' );
                }
            } ).on( 'mouseout', '.elementor-control .elementor-control-field-description.show', function( e ) {
                $( this ).removeClass( 'show block-pos show-top' );
            } );
        },
        initCustomCSS: function() {
            // custom page css
            var custom_css = elementor.settings.page.model.get( 'page_css' );

            setTimeout( function() {
                typeof custom_css != 'undefined' && elementorFrontend.hooks.doAction( 'refresh_page_css', custom_css );
            }, 2000 );

            $( document.body ).on( 'input', 'textarea[data-setting="page_css"]', function( e ) {
                if ( $( this ).closest( '.elementor-control' ).siblings( '.elementor-control-_alpus_custom_css' ).length ) {
                    elementor.settings.page.model.set( 'page_css', $( this ).val() );

                    $( '#elementor-panel-saver-button-publish' ).removeClass( 'elementor-disabled' );
                    $( '#elementor-panel-saver-button-save-options' ).removeClass( 'elementor-disabled' );
                }

                elementorFrontend.hooks.doAction( 'refresh_page_css', $( this ).val() );
            } )
        },
        initCustomJS: function() {
            // custom page css
            var custom_js = elementor.settings.page.model.get( 'page_js' );

            $( document.body ).on( 'input', 'textarea[data-setting="page_js"]', function( e ) {
                if ( $( this ).closest( '.elementor-control' ).siblings( '.elementor-control-_alpus_custom_js' ).length ) {
                    elementor.settings.page.model.set( 'page_js', $( this ).val() );

                    $( '#elementor-panel-saver-button-publish' ).removeClass( 'elementor-disabled' );
                    $( '#elementor-panel-saver-button-save-options' ).removeClass( 'elementor-disabled' );
                }
            } )
        },
        addStudioButtons: function() {
            // Add Studio Block Button
            var addSectionTmpl = document.getElementById( 'tmpl-elementor-add-section' );
            if ( addSectionTmpl ) {
                addSectionTmpl.textContent = addSectionTmpl.textContent.replace(
                    '<div class="elementor-add-section-area-button elementor-add-template-button',
                    '<div class="elementor-add-section-area-button elementor-studio-section-button" ' +
                    'onclick="window.parent.runStudio(this);" ' +
                    'title="Alpus Studio"><i class="alpus-mini-logo"></i><i class="eicon-insert"></i></div>\r\n' +
                    '<div class="elementor-add-section-area-button elementor-add-template-button' );
            }
        },
        panelChange: function( panel ) {
            if ( "_alpus_section_custom_css" == panel.activeSection || "_alpus_section_custom_js" == panel.activeSection ) {
                var oldName = panel.activeSection.replaceAll( '_section', '' ),
                    newName = oldName.replaceAll( '_alpus_custom', 'page' );

                if ( $( '.elementor-control-' + newName ).length ) {
                    return;
                }

                var $newControl = $( '.elementor-control-' + oldName ).clone().removeClass( 'elementor-control-' + oldName ).addClass( 'elementor-control-' + newName );

                $newControl.insertAfter( $( '.elementor-control-' + oldName ) );
                $newControl.find( 'textarea' ).attr( 'data-setting', newName ).val( elementor.settings.page.model.get( newName ) );

                if ( newName == 'page_css' ) {
                    $( '.elementor-control-page_js' ).remove();
                } else {
                    $( '.elementor-control-page_css' ).remove();
                }
            } else if ( "alpus_custom_css_settings" == panel.activeSection ) {
                $( '.elementor-control-page_css' ).val( elementor.settings.page.model.get( 'page_css' ) );
            } else if ( "alpus_custom_js_settings" == panel.activeSection ) {
                $( '.elementor-control-page_js' ).val( elementor.settings.page.model.get( 'page_js' ) );
            }
            themeElementorAdmin.initLayoutColumns();
        },
        changeControls: function( activeSection, editor ) {
            if ( "_alpus_section_custom_css" != activeSection && "_alpus_section_custom_js" != activeSection ) {
                $( '.elementor-control-page_css, .elementor-control-page_js' ).remove();
            } else {
                var oldName = activeSection.replaceAll( '_section', '' ),
                    newName = oldName.replaceAll( '_alpus_custom', 'page' ),
                    $newControl = $( '.elementor-control-' + oldName ).clone().removeClass( 'elementor-control-' + oldName ).addClass( 'elementor-control-' + newName );

                $newControl.insertAfter( $( '.elementor-control-' + oldName ) );
                $newControl.find( 'textarea' ).attr( 'data-setting', newName ).val( elementor.settings.page.model.get( newName ) );

                if ( newName == 'page_css' ) {
                    $( '.elementor-control-page_js' ).remove();
                } else {
                    $( '.elementor-control-page_css' ).remove();
                }
            }
            themeElementorAdmin.initLayoutColumns();
            themeElementorAdmin.initFlipboxWidget( activeSection, editor );
        },
        initFlipboxWidget: function( sectionName, editor ) {
            var editedElement = editor.getOption( 'editedElementView' );

            if ( alpus_core_vars.theme + '_widget_flipbox' !== editedElement.model.get( 'widgetType' ) ) {
                return;
            }

            var isSideBSection = -1 !== ['section_back_side_content', 'section_flipbox_back_style'].indexOf( sectionName );
            editedElement.$el.toggleClass( 'flipped', isSideBSection );
            var $backLayer = editedElement.$el.find( '.flipbox_back' );

            if ( isSideBSection ) {
                $backLayer.css( 'transition', 'none' );
            }

            if ( !isSideBSection ) {
                setTimeout( function() {
                    $backLayer.css( 'transition', '' );
                }, 10 );
            }
        },
        initArchiveSinglePreview: function() {
            $( document )
                .on( 'click', '.elementor-control-archive_preview_apply .elementor-button', function( e ) {
                    $.post( alpus_core_vars.ajax_url, {
                        action: 'alpus_archive_builder_preview_apply',
                        nonce: alpus_core_vars.nonce,
                        post_id: ElementorConfig.document.id,
                        mode: $( '.elementor-control-archive_preview_type select' ).val(),
                    }, function() {
                        elementor.reloadPreview();
                    } );
                } )
                .on( 'click', '.elementor-control-single_preview_apply .elementor-button', function( e ) {
                    $.post( alpus_core_vars.ajax_url, {
                        action: 'alpus_single_builder_preview_apply',
                        nonce: alpus_core_vars.nonce,
                        post_id: ElementorConfig.document.id,
                        mode: $( '.elementor-control-single_preview_type select' ).val(),
                    }, function() {
                        elementor.reloadPreview();
                    } );
                } )
        }
    }

    // Setup Alpus Elementor Admin
    elementor.on( 'frontend:init', themeElementorAdmin.init.bind( themeElementorAdmin ) );
} );