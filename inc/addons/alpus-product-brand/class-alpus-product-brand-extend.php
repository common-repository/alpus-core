<?php
/**
 * Alpus Product Brand
 *
 * @author     AlpusTheme
 *
 * @version    1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! class_exists( 'Alpus_Product_Brand_Extend' ) ) {
    class Alpus_Product_Brand_Extend extends Alpus_Base {

        /**
                 * Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            if ( ! class_exists( 'Alpus_Product_Brand_Manager' ) ) {
                add_filter( 'alpus_elementor_widgets', array( $this, 'remove_elementor_widget' ) );
            }
            add_filter( 'alpus_sidebar_widgets', array( $this, 'remove_sidebar_widget' ) );
        }

        /**
         * Remove brand sidebar widget.
         *
         * @since 1.0
         */
        public function remove_sidebar_widget( $widgets ) {
            foreach ( $widgets as $id => $widget ) {
                if ( 'brands_nav' == $widget ) {
                    unset( $widgets[ $id ] );
                    break;
                }
            }

            return $widgets;
        }

        /**
         * Remove brand elementor widget.
         *
         * @since 1.0
         */
        public function remove_elementor_widget( $widgets ) {
            foreach ( $widgets as $widget => $value ) {
                if ( 'brands' == $widget ) {
                    unset( $widgets[ $widget ] );
                    break;
                }
            }

            return $widgets;
        }
    }
}

Alpus_Product_Brand_Extend::get_instance();
