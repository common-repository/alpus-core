<?php
/**
 * Alpus Yith Wishlist
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! class_exists( 'Alpus_Core_Compare' ) ) {
    class Alpus_Core_Compare extends Alpus_Base {

        /**
                 * Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            add_action( 'wp_footer', array( $this, 'enqueue_style' ), 19 );
            add_filter( 'alpus_critical_css', array( $this, 'remove_critical_css' ) );
        }

        /**
         * Enqueue styles
         *
         * @since 1.0
         */
        public function enqueue_style() {
            wp_enqueue_style( 'alpus-yith-compare-style', alpus_core_framework_uri( '/plugins/yith-compare/yith-compare' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpus-theme' ), ALPUS_CORE_VERSION );
        }

        /**
         * Remove critical css when compare popup loading
         *
         * @since 1.0
         */
        public function remove_critical_css( $css ) {
            if ( isset( $_REQUEST['action'] ) && 'yith-woocompare-view-table' == sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) {
                $css = '';
            }

            return $css;
        }
    }
}

Alpus_Core_Compare::get_instance();
