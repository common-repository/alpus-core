<?php
/**
 * Alpus WooCommerce plugin compatibility.
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

class Alpus_Core_WooCommerce_Extend extends Alpus_Base {

    /**
         * Constructor
         *
         * @since 1.0
         */
    public function __construct() {
        if ( ! empty( $_REQUEST['action'] ) && 'elementor' == sanitize_text_field( $_REQUEST['action'] ) && is_admin() ) {
            add_action( 'init', array( $this, 'init' ), 8 );
        } else {
            $this->init();
        }
    }

    /**
     * Init WooCommerce actions
     *
     * @since 1.0
     */
    public function init() {
        add_action(
            'alpus_after_framework',
            function () {
                remove_filter( 'alpus_breadcrumb_args', 'alpus_single_prev_next_product', 10 );
            }
        );
    }
}

Alpus_Core_WooCommerce_Extend::get_instance();
