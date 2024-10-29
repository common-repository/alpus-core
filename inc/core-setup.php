<?php
/**
 * Entrypoint of Core
 *
 * Here, proper features of theme are added or removed.
 * If framework has unnecessary features, you can remove features
 * using alpus_remove_feature.
 *
 * 1. Load the plugin base
 * 2. Load the other plugin functions
 * 3. Load builders
 * 4. Load addons and shortcodes
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

/* Define Constants                   */

define( 'ALPUS_CORE_INC', ALPUS_CORE_PATH . '/inc' );
define( 'ALPUS_CORE_INC_URI', ALPUS_CORE_URI . '/inc' );
define( 'ALPUS_CORE_INC_PATH', ALPUS_CORE_PATH . '/inc' );
define( 'ALPUS_CORE_BUILDERS', ALPUS_CORE_INC . '/builders' );

if ( ! class_exists( 'Alpus_Core_Setup' ) ) {
    class Alpus_Core_Setup extends Alpus_Base {

        /**
                 * The Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {

            /* 1. Load the core general           */

            require_once ALPUS_CORE_INC_PATH . '/general-function.php';
            require_once ALPUS_CORE_INC_PATH . '/core-actions.php';

            $this->init_admin_config();
            $this->config_extend();

            /* 2. Load plugin functions           */

            add_action( 'alpus_after_core_framework_init', array( $this, 'init_plugins' ) );

            /* 3. Load addons and shortcodes      */

            require_once ALPUS_CORE_INC_PATH . '/addons/class-alpus-addons-extend.php';

            /* 4. Load builders                   */

            if ( ! isset( $_POST['action'] ) || 'alpus_quickview' != sanitize_text_field( $_POST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                require_once ALPUS_CORE_BUILDERS . '/class-alpus-builders-extend.php';
                require_once ALPUS_CORE_BUILDERS . '/sidebar/class-alpus-sidebar-builder-extend.php';
            }

            /* 5. Init assets                */

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_theme_js' ), 40 );
        }

        /**
         * Init admin configurations
         *
         * @since 1.0
         */
        public function init_admin_config() {
            if ( current_user_can( 'manage_options' ) ) {
                require_once ALPUS_CORE_INC_PATH . '/admin/admin/class-alpus-core-admin.php';
            }
        }

        /**
         * Init plugins
         *
         * @since 1.0
         */
        public function init_plugins() {
            if ( alpus_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) ) {
                require_once ALPUS_CORE_INC_PATH . '/plugins/elementor/class-alpus-core-elementor-extend.php';
            }

            if ( alpus_get_feature( 'fs_plugin_wpforms' ) && class_exists( 'WPForms' ) ) {
                require_once ALPUS_CORE_INC_PATH . '/plugins/wpforms/class-alpus-core-wpforms.php';
            }

            if ( class_exists( 'WooCommerce' ) ) {
                require_once ALPUS_CORE_INC_PATH . '/plugins/woocommerce/class-alpus-core-woocommerce-extend.php';
            }
        }

        /**
         * Manage features configuration
         *
         * @since 1.0
         */
        public function config_extend() {
            alpus_add_feature( 'fs_plugin_wpforms' );

            // Config features
            alpus_remove_feature(
                array(
                    'fs_addon_product_360_gallery',
                    'fs_addon_product_video_popup',
                    'fs_addon_product_brand',
                    'fs_addon_product_image_comments',
                    'fs_addon_product_compare',
                    'fs_addon_custom_fonts',
                    'fs_addon_product_helpful_comments',
                    'fs_addon_product_attribute_guide',
                    'fs_addon_product_frequently_bought_together',
                    'fs_addon_product_advanced_swatch',
                    'fs_addon_gdpr',
                )
            );

            if ( ! defined( 'ALPUS_PRO_VERSION' ) ) {
                alpus_remove_feature(
                    array(
                        'fs_addon_product_custom_tabs',
                        'fs_addon_skeleton',
                        'fs_addon_lazyload_image',
                        'fs_addon_lazyload_menu',
                        'fs_addon_live_search',
                        'fs_addon_product_buy_now',
                        'fs_addon_ai_generator',

                        // Pro builders
                        'fs_builder_singleproduct',
                        'fs_builder_shop',
                        'fs_builder_cart',
                        'fs_builder_checkout',
                        'fs_builder_single',
                        'fs_builder_archive',
                        'fs_builder_type',
                        'fs_builder_popup',

                        'fs_critical_css_js',
                    )
                );
            }
        }

        /**
         * Enqueue theme js at last.
         *
         * @since 1.0
         */
        public function enqueue_theme_js() {
            if ( ! defined( 'ALPUS_PRO_VERSION' ) ) {
                wp_deregister_script( 'alpus-ajax' );
            }
        }
    }
}

Alpus_Core_Setup::get_instance();
