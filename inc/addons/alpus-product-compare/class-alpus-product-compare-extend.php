<?php
/**
 * Alpus Product Compare Extend
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Product_Compare_Extend' ) ) {
    class Alpus_Product_Compare_Extend extends Alpus_Base {

        /**
                 * The Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            add_filter( 'alpus_product_compare_general_options', array( $this, 'remove_options' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );
            add_filter( 'alpus_customize_fields', array( $this, 'remove_customize_panel' ) );
        }

        /**
         * Enqueue script
         *
         * @since 1.0
         */
        public function enqueue_scripts() {
            wp_enqueue_style( 'alpus-product-compare-extend', ALPUS_CORE_INC_URI . '/addons/alpus-product-compare/product-compare-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_CORE_VERSION );
        }

        /**
         * Remove compare customize panel in theme option
         *
         * @since 1.0
         */
        public function remove_customize_panel( $fields ) {
            unset( $fields['cs_woo_compare_advanced'] );

            return $fields;
        }

        /**
         * Remove position options in plugin options
         *
         * @since 1.0
         */
        public function remove_options( $generals ) {
            unset( $generals['alpus_compare_button_label'] );
            unset( $generals['alpus_compare_position_in_loop'] );
            unset( $generals['alpus_compare_position_in_sp'] );
            unset( $generals['alpus_compare_button_style_start'] );
            unset( $generals['alpus_compare_button_color'] );
            unset( $generals['alpus_compare_button_bg_color'] );
            unset( $generals['alpus_compare_button_bd_color'] );
            unset( $generals['alpus_compare_button_style_end'] );

            return $generals;
        }
    }
}

Alpus_Product_Compare_Extend::get_instance();
