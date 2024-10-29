<?php
/**
 * Woof Compatibility
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! class_exists( 'Alpus_WOOF' ) ) {
    /**
     * Alpus Woof Class
     */
    class Alpus_WOOF extends Alpus_Base {

        protected $counter;

        /**
         * Main Class construct
         *
         * @since 1.0
         */
        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 50 );
        }

        /**
         * Custom style for WooF
         *
         * @since 1.0
         */
        public function enqueue_scripts() {
            wp_enqueue_style( 'alpus-woof-style', alpus_framework_uri( '/plugins/woof/woof' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpus-style' ), ALPUS_VERSION );
        }
    }
}

Alpus_WOOF::get_instance();
