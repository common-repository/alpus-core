<?php
/**
 * Alpus Elementor Custom Advanced Tab
 *
 * @author     AlpusTheme
 *
 * @version    1.0
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alpus_Widget_Advanced_Tabs' ) && ! class_exists( 'ALPUS_ELEMENTOR_ADDON' ) ) {
    /**
     * Advanced Alpus Advanced Tab
     *
     * @since 1.0
     */
    class Alpus_Widget_Advanced_Tabs extends Alpus_Base {

        const TAB_CUSTOM = 'alpus_custom_tab';

        private $custom_tabs;

        /**
         * The Constructor.
         *
         * @since 1.0
         */
        public function __construct() {
            // Init Custom Tabs
            $this->init_custom_tabs();
            $this->register_custom_tabs();
            $this->add_addon_sections();
        }

        /**
         * Init custom tab.
         *
         * @since 1.0
         */
        private function init_custom_tabs() {
            $this->custom_tabs = array();

            $this->custom_tabs[ $this::TAB_CUSTOM ] = ALPUS_DISPLAY_NAME;

            /*
             * Filters custom tabs.
             *
             * @since 1.0
             */
            $this->custom_tabs = apply_filters( 'alpus_init_custom_tabs', $this->custom_tabs );
        }

        /**
         * Register custom tab.
         *
         * @since 1.0
         */
        public function register_custom_tabs() {
            foreach ( $this->custom_tabs as $key => $value ) {
                Elementor\Controls_Manager::add_tab( $key, $value );
            }
        }

        /**
         * Add addon sections.
         *
         * @since 1.0
         */
        public function add_addon_sections() {
            /**
             * Filters sections which added on in elementor.
             *
             * @since 1.0
             */
            $sections = apply_filters( 'alpus_elementor_addon_sections', array( 'floating', 'duplex', 'ribbon', 'mask', 'custom' ) );

            foreach ( $sections as $section ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ELEMENTOR . '/tabs/' . $section . '/class-alpus-' . $section . '-elementor.php' );
            }
        }
    }
    Alpus_Widget_Advanced_Tabs::get_instance();
}
