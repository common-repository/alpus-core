<?php
/**
 * Alpus Template
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Builders_Extend' ) ) {
    class Alpus_Builders_Extend {

        /**
         * The Constructor
         *
         * @since 1.0
         */
        public function __construct() {
            require_once ALPUS_CORE_INC . '/builders/header/class-alpus-header-builder-extend.php';
            add_filter( 'alpus_core_admin_localize_vars', array( $this, 'localize_vars' ), 20 );
        }

        public function localize_vars( $vars ) {
            if ( ! defined( 'ALPUS_PRO_VERSION' ) || ! class_exists( 'Alpus_Admin' ) || ! Alpus_Admin::get_instance()->is_registered() ) {
                $vars['layout_save'] = false;
            }

            return $vars;
        }
    }

    new Alpus_Builders_Extend();
}
