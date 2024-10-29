/**
 * Alpus Dependent Plugin - ProductThreeSixtyViewer
 *
 * @package Alpus Core
 * @requires threesixty-slider
 * @version 1.0
 */

'use strict';

window.alpusPlugin || ( window.alpusPlugin = {} );

( function( $ ) {

	function open360DegreeView( e ) {
		e.preventDefault();
		var $button = $( this ), $loading_target = $button.closest( '.product-single-carousel-wrap' );
		if ( !$loading_target.length ) {
			// $loading_target = $button.closest( '.product-media' );
			$loading_target = $button.closest( '.woocommerce-product-gallery__image' );
		}

		alpusPlugin.doLoading( $loading_target );
		$.ajax(
			{
				type: 'POST',
				dataType: 'json',
				url: alpus_360_degree_vars.ajax_url,
				data: {
					action: "alpus_product_360_degree",
					nonce: alpus_360_degree_vars.nonce,
					id: $button.data( 'product_id' ),
				},
				success: function( response ) {
					alpusPlugin.endLoading( $loading_target );
					alpusPlugin.popup( {
						type: 'inline',
						mainClass: "product-popupbox wm-fade product-360-popup",
						preloader: false,
						items: {
							src: '<div class="alpus-360-degree">\
									<div class="d-loading"><i></i></div>\
									<ul class="product-degree-images"></ul>\
								</div>'
						},
						callbacks: {
							open: function() {
								var images = response.split( ',' );
								this.container.find( '.alpus-360-degree' ).ThreeSixty( {
									totalFrames: images.length,
									endFrame: images.length,
									currentFrame: images.length - 1,
									imgList: this.container.find( '.product-degree-images' ),
									progress: '.d-loading',
									imgArray: images,
									// speedMultiplier: 1,
									// monitorInt: 1,
									autoplayDirection: alpus_360_degree_vars.direction == 'yes' ? -1 : 1,
									playSpeed: alpus_360_degree_vars.auto_speed,
									height: alpus_360_degree_vars.height,
									width: alpus_360_degree_vars.width,
									drag: alpus_360_degree_vars.mouse_drag == 'yes' ? true : false,
									navigation: alpus_360_degree_vars.navigation == 'yes' ? true : false,
								} );
								// set background color and nav position
								this.container.find( '.alpus-360-degree' ).addClass( alpus_360_degree_vars.position );
								// add animation
								if ( alpus_360_degree_vars.animation ) {
									this.container.find( '.alpus-360-degree' ).closest( '.mfp-wrap' ).addClass( 'pers1000' );
									this.container.find( '.alpus-360-degree' ).closest( '.mfp-container' ).addClass( 'appear-animation' ).addClass( alpus_360_degree_vars.animation );
								}
							},
							beforeClose: function() {
								this.container.empty();
							}
						}
					} );
				}
			}
		);

	}
	if ( $.fn.ThreeSixty ) {
		$( document.body ).on( 'click', '.open-product-degree-viewer', open360DegreeView );
	}
} )( jQuery );
