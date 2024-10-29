<?php
/**
 * Alpus MiniCart Quantity Input
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_MiniCart_Quantity_Input' ) ) {
    class Alpus_MiniCart_Quantity_Input extends Alpus_Base {

        /**
                 * Main Class construct
                 *
                 * @since 1.0
                 */
        public function __construct() {
            // Enqueue scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );

            add_action( 'wp_ajax_alpus_update_cart_item', array( $this, 'update_cart_item' ) );
            add_action( 'wp_ajax_nopriv_alpus_update_cart_item', array( $this, 'update_cart_item' ) );
        }

        /**
         * Enqueue scripts
         *
         * @since 1.0
         */
        public function enqueue_scripts() {
            wp_register_style( 'alpus-minicart-quantity-input', alpus_core_framework_uri( '/addons/minicart-quantity-input/minicart-quantity-input.min.css' ), null, ALPUS_CORE_VERSION, 'all' );
            wp_register_script( 'alpus-minicart-quantity-input', alpus_core_framework_uri( '/addons/minicart-quantity-input/minicart-quantity-input' . ALPUS_JS_SUFFIX ), array( 'alpus-framework-async' ), ALPUS_CORE_VERSION, true );
        }

        /**
         * Change quantity of cart item
         *
         * @since 1.0
         */
        public function update_cart_item() {
            $cart_item_key = sanitize_text_field( $_REQUEST['cart_item_key'] );
            $quantity      = sanitize_text_field( $_REQUEST['quantity'] );

            WC()->cart->set_quantity( $cart_item_key, $quantity );
        }
    }
}

Alpus_MiniCart_Quantity_Input::get_instance();
