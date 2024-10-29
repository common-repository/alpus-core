/**
 * Alpus Elementor Admin Extend
 * 
 * @package Alpus Core
 * @since 1.0
 */

'use strict';

var themeElementorAdmin = window.themeElementorAdmin || {};

( function ( $ ) {
    themeElementorAdmin.activeSection = null;
    themeElementorAdmin.editedElement = null;

    themeElementorAdmin.init = function () {
        var self = this;

        window.elementor.on( 'preview:loaded', function () {
            elementor.$preview[ 0 ].contentWindow.themeElementorAdmin = themeElementorAdmin;
        } );
    }

    // This is view for the alpus pro widgets
    elementor.hooks.addFilter( "panel/elements/regionViews", function ( panel ) {

        if ( alpus_widget_settings.pro_activated || alpus_widget_settings.pro_widgets.length <= 0 )
            return panel;

        var alpusProWidgetHandle,
            elementsView = panel.elements.view,
            categoriesView = panel.categories.view;

        alpusProWidgetHandle = {
            isProWidget: function () {
                return 0 === this.model.get( "name" ).indexOf( "alpus-" ) && this.$el.hasClass( 'elementor-element--promotion' );
            },

            getElementObj: function ( key ) {

                var widgetObj = alpus_widget_settings.pro_widgets.find( function ( widget, index ) {
                    if ( widget.name == key )
                        return true;
                } );

                return widgetObj;

            },

            onMouseDown: function () {

                void this.constructor.__super__.onMouseDown.call( this );

                if ( !this.isProWidget() ) {
                    return;
                }

                var widgetObject = this.getElementObj( this.model.get( "name" ) );

                elementor.promotion.showDialog( {
                    title: sprintf( wp.i18n.__( '%s', 'alpus-core' ), this.model.get( "title" ) ),
                    content: sprintf( wp.i18n.__( 'Use %s widget and our more pro widgets to extend your toolbox and build sites faster and more attractive.', 'alpus-core' ), this.model.get( "title" ) ),
                    position: {
                      blockStart: '-7'
                    },
                    targetElement: this.el,
                    actionButton: {
                        url: widgetObject.action_url,
                        text: alpus_widget_settings.pro_button,
                        classes: ['elementor-button', 'go-pro', 'alpus-pro-btn']
                    }
                } )
            }
        }

        panel.elements.view = elementsView.extend( {
            childView: elementsView.prototype.childView.extend( alpusProWidgetHandle )
        } );

        panel.categories.view = categoriesView.extend( {
            childView: categoriesView.prototype.childView.extend( {
                childView: categoriesView.prototype.childView.prototype.childView.extend( alpusProWidgetHandle )
            } )
        } );

        return panel;

    } );

    // Add context menu to import block from clipboard block id
    // elementor.hooks.addFilter('elements/context-menu/groups', function( groups, elType ) {
    //     var controlSign = '^';
    //     groups.push({
    //         name: 'pasteStudio',
    //         actions: [{
    //             name: 'pasteStudio',
    //             icon: 'eicon-library-download',
    //             title: function title() {
    //                 return wp.i18n.__('Paste from Alpus studio', 'alpus-core');
    //             },
    //             callback: function callback() {
    //                 var textarea = document.createElement("textarea");
    //                 textarea.contentEditable = true;
    //                 $(textarea).appendTo($('#elementor-panel-elements-search-wrapper'));
    //                 textarea.focus();
    //                 document.execCommand("paste");
    //                 // retrieve the pasted text with textarea.textContent
    //                 // remove textarea from the document
    //                 console.log(textarea.textContent);
    //             },
    //         }]
    //     });
    //     return groups;  
    // } );

    $( window ).on( 'elementor:init', function () {
        themeElementorAdmin.init();
    } );
} )( jQuery );
