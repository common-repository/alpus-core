/**
 * Alpus Dependent Plugin - ProductVideoPopup
 *
 * @package Alpus Core
 * @version 1.0
 */

'use strict';

window.alpusPlugin || ( window.alpusPlugin = {} );

( function ( $ ) {
	function openVideoPopup( e ) {
		e.preventDefault();
		var $button = $(this), $loading_target = $button.closest('.product-single-carousel-wrap');		
        if ( !$loading_target.length ) {
            $loading_target = $button.closest('.product-media');
        }
		
		alpusPlugin.doLoading( $loading_target );
		$.ajax(
			{
				type: 'POST',
				dataType: 'json',
				url: alpus_product_video_vars.ajax_url,
				data: {
					action: "alpus_product_advanced_video",
					nonce: alpus_product_video_vars.nonce,
					id: $button.data( 'product_id' ),
				},
				success: function (response) {
					alpusPlugin.endLoading($loading_target);
					alpusPlugin.popup({
						type: 'inline',
						mainClass: "product-popupbox product-video-popup wm-fade",
						preloader: false,
						items: {
							src: '<div class="alpus-product-video">\
							</div>'			},
						callbacks: {
							open: function () {
								this.container.find('.alpus-product-video').append(response);
								// add animation
								this.container.find('.alpus-product-video').closest('.mfp-wrap').addClass('pers1000');
								this.container.find('.alpus-product-video').closest('.mfp-container').addClass('appear-animation').addClass(alpus_product_video_vars.animation);
							}
						}
					});
				}
			}
		);
	}
	alpusPlugin.$body.on( 'click', '.open-product-video-viewer', openVideoPopup );
} )( jQuery );
