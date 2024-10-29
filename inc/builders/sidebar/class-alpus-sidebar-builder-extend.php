<?php
/**
 * Alpus_Sidebar_Builder_Extend class
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

class Alpus_Sidebar_Builder_Extend extends Alpus_Base {

    /**
         * Constructor
         *
         * Add actions and filters for sidbar widgets.
         *
         * @since 1.0
         */
    public function __construct() {
    }

    /**
     * Add sidebar widgets for the Theme.
     *
     * @since 1.0
     */
    public function add_widgets( $widgets ) {
        $add = array();

        if ( class_exists( 'WooCommerce' ) ) {
        }

        return array_merge( $widgets, $add );
    }
}

Alpus_Sidebar_Builder_Extend::get_instance();
