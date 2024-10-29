<?php
/**
 * Alpus Yith Gift Card
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! class_exists( 'Alpus_Core_Gift_Card' ) ) {
    class Alpus_Core_Gift_Card extends Alpus_Base {

        /**
                 * Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            add_action( 'wp_footer', array( $this, 'enqueue_style' ), 19 );
            add_filter( 'ywgc_remove_gift_card_text', array( $this, 'get_remove_gift_card_text' ), 10 );

            if ( class_exists( 'YITH_YWGC_Frontend' ) ) {
                remove_action( 'wp', array( YITH_YWGC_Frontend::get_instance(), 'yith_ywgc_remove_image_zoom_support' ), 100 );
            }
        }

        /**
         * Enqueue styles
         *
         * @since 1.0
         */
        public function enqueue_style() {
            wp_enqueue_style( 'alpus-yith-gift-card-style', alpus_core_framework_uri( '/plugins/yith-gift-card/yith-gift-card' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpus-theme' ), ALPUS_CORE_VERSION );
        }

        /**
         * Change text of remove gift card button
         *
         * @since 1.0
         */
        public function get_remove_gift_card_text() {
            return esc_html__( 'Remove', 'alpus-core' );
        }
    }
}

Alpus_Core_Gift_Card::get_instance();
