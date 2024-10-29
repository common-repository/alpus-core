/**
 * Alpus Elementor Preview
 * 
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 * 
 */
'use strict';

window.themeAdmin = window.themeAdmin || {};

( function ( $ ) {
    themeAdmin.themeElementorPreviewExtend = themeAdmin.themeElementorPreviewExtend || {}
    themeAdmin.themeElementorPreviewExtend.completed = false;
    themeAdmin.themeElementorPreviewExtend.fnArray = [];
    themeAdmin.themeElementorPreviewExtend.init = function () {
        var self = this;

        // for section, column slider's thumbs dots
        $( '.elementor-section > .slider-custom-html-dots' ).parent().addClass( 'flex-wrap' );
        $( '.elementor-column > .slider-custom-html-dots' ).parent().addClass( 'flex-wrap' );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/column', function ( $obj ) {
            self.completed ? self.initColumn( $obj ) : self.fnArray.push( {
                fn: self.initColumn,
                arg: $obj
            } );
        } );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', function ( $obj ) {
            self.completed ? self.initSection( $obj ) : self.fnArray.push( {
                fn: self.initSection,
                arg: $obj
            } );
        } );
    }

    themeAdmin.themeElementorPreviewExtend.onComplete = function () {
        var self = this;
        self.completed = true;
        self.initWidgets();
    }

    themeAdmin.themeElementorPreviewExtend.initWidgets = function () {

        // Widget / Circle Progressbar
        elementorFrontend.hooks.addAction( 'frontend/element_ready/' + alpus_vars.theme + '_circle_progressbar.default', function ( $obj ) {
            var circle_progressbar = $obj.find( '.circular-bar-chart' );
            circle_progressbar.themeChartCircular( circle_progressbar.data( 'plugin-options' ) );
        } );

        // Widget / Scroll Progress
        elementorFrontend.hooks.addAction( 'frontend/element_ready/' + alpus_vars.theme + '_scroll_progress.default', function ( $obj ) {
            $( document.body ).trigger( 'scroll_progress', [ $obj ] );
        } );

        // Widget / Scroll Section
        elementorFrontend.hooks.addAction( 'frontend/element_ready/' + alpus_vars.theme + '_scroll_section.default', function ( $obj ) {
            var scrollable = $obj.data( 'scrollable' );
        } );

        // Widgets for wpforms
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wpforms.default', function ( $obj ) {
            var options = $obj.children( '.alpus-elementor-widget-options' );
            $obj.removeClass( 'controls-rounded controls-xs controls-sm controls-lg label-floating' );
            if ( options.length ) {
                options = options.data( 'options' );
                if ( options ) {
                    options.size && $obj.addClass( 'controls-' + options.size );
                    options.label_floating && $obj.addClass( 'label-floating' );
                }
            }
        } );
    }

    themeAdmin.themeElementorPreviewExtend.initColumn = function ( $obj ) {
        var $column = $obj.children( '.elementor-column-wrap' );

        $column = 0 === $column.length ? $obj.children( '.elementor-widget-wrap' ) : $column;

        if ( $column.find( '.slider-wrapper' ).length && $column.siblings( '.slider-custom-html-dots' ).length ) {
            $column.parent().addClass( 'flex-wrap' );
        }
    }

    themeAdmin.themeElementorPreviewExtend.initSection = function ( $obj ) {
        var $container = $obj.children( '.elementor-container' ),
            $row = 0 == $obj.find( '.elementor-row' ).length ? $container : $container.children( '.elementor-row' );

        if ( $row.data( 'scrollable' ) ) {
            $row.parent( '.elementor-section' ).addClass( 'scroll-overlay-section' );
        } else {
            $row.parent( '.elementor-section' ).removeClass( 'scroll-overlay-section' );
        }

        if ( $obj.children( '.slider-custom-html-dots' ).length ) {
            $obj.addClass( 'flex-wrap' );
        }
    }

    /**
     * Setup AlpusElementorPreview
     */
    $( window ).on( 'load', function () {
        if ( typeof elementorFrontend != 'undefined' && typeof theme != 'undefined' ) {
            if ( elementorFrontend.hooks ) {
                themeAdmin.themeElementorPreviewExtend.init();
                themeAdmin.themeElementorPreviewExtend.onComplete();
            } else {
                elementorFrontend.on( 'components:init', function () {
                    themeAdmin.themeElementorPreviewExtend.init();
                    themeAdmin.themeElementorPreviewExtend.onComplete();
                } );
            }
        }
    } );

} )( jQuery );