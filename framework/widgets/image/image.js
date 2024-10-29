/**
 * WP Alpus Core Framework
 * Alpus Gutenberg Image: Image
 *
 * @package WP Alpus Core Framework
 * @since   1.2.0
 */

window.theme = window.theme || {};

( function ( $ ) {
    /**
     * Image
     *  
     * Open Lightbox of Alpus Image Gutenberg
     *
     *  @since 1.2.0
     */
    theme.alpusgutenbergimagepopup = function ( $selector ) {

        $( 'body' ).on( 'click', $selector, function ( e ) {
            e.preventDefault();
            if ( $.magnificPopup ) {
                $.magnificPopup.open( {
                    items: {
                        src: $( this ).find( 'img' ).attr( 'src' )
                    },
                    type: 'image',
                    mainClass: 'mfp-with-zoom'
                } );
            }
        } )
    }
    $( window ).on( 'alpus_complete', function () {
        theme.alpusgutenbergimagepopup( '.alpus_img_popup' );
    } )
} )( jQuery );