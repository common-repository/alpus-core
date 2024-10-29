<?php
/**
 * Alpus Addons Extend
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Addons_Extend' ) ) {
    class Alpus_Addons_Extend extends Alpus_Base {

        /**
                 * The Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            add_action( 'alpus_framework_addons', array( $this, 'extend_addons' ) );
            $this->addons_compatibility();
        }

        /**
         * Extend Addons
         *
         * @since 1.0
         */
        public function extend_addons( $request ) {
            // @start feature: fs_addon_studio
            if ( alpus_get_feature( 'fs_addon_studio' ) ) {
                if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) &&
                ( $request['doing_ajax'] || $request['is_preview'] || 'edit.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ALPUS_NAME . '_template' == sanitize_text_field( $_REQUEST['post_type'] ) || 'post.php' == $GLOBALS['pagenow'] && 'edit' == sanitize_text_field( $_REQUEST['action'] ) || 'post-new.php' == $GLOBALS['pagenow'] ) ) {
                    require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/studio/class-alpus-studio-extend.php' );
                }
            }
            // @end feature: fs_addon_studio
        }

        /**
         * Addons Compatiblity
         *
         * @since 1.0
         */
        public function addons_compatibility() {
            $addons = apply_filters(
                'alpus_addons_compatiblity',
                array(
                    'product-compare',
                    'product-brand',
                    'post-like',
                )
            );

            foreach ( $addons as $addon ) {
                require_once ALPUS_CORE_INC . '/addons/alpus-' . $addon . '/class-alpus-' . $addon . '-extend.php';
            }
        }
    }
}

Alpus_Addons_Extend::get_instance();
