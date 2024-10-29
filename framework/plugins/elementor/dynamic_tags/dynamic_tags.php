<?php
/**
 * Alpus Dynamic Tags class
 *
 * @author     D-THEMES
 *
 * @since      1.0
 *
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Core_Dynamic_Tags' ) ) {
    class Alpus_Core_Dynamic_Tags extends Elementor\Modules\DynamicTags\Module {

        /**
                 * Base dynamic tag group.
                 *
                 * @since 1.0
                 */
        const ALPUS_CORE_GROUP = 'alpus';

        public function __construct() {
            parent::__construct();

            add_action( 'alpus_core_dynamic_before_render', array( $this, 'before_render' ), 10, 2 );
            add_action( 'alpus_core_dynamic_after_render', array( $this, 'after_render' ), 10, 2 );
        }

        public function get_tag_classes_names() {
            $tags = array(
                'Alpus_Core_Custom_Field_Post_User_Tag',
                'Alpus_Core_Custom_Link_Post_User_Tag',
                'Alpus_Core_Custom_Field_Taxonomies_Tag',
                'Alpus_Core_Custom_Field_Meta_Data_Tag',
                'Alpus_Core_Custom_Image_Post_User_Tag',
                'Alpus_Core_Custom_Image_Meta_Data_Tag',
                // 'Alpus_Core_Custom_Image_Tag',
                // 'Alpus_Core_Custom_Field_Tag',
            );

            $builders_array = json_decode( wp_unslash( function_exists( 'alpus_get_option' ) ? alpus_get_option( 'resource_template_builders' ) : '' ), true );

            if ( empty( $builders_array['popup'] ) ) {
                $tags[] = 'Alpus_Core_Custom_Field_Popup_Tag';
            }

            /*
             * Filters the tags which added dynamically.
             *
             * @since 1.0
             */
            return apply_filters( 'alpus_dynamic_tags', $tags );
        }

        public function get_groups() {
            return array(
                self::ALPUS_CORE_GROUP => array(
                    'title' => ALPUS_DISPLAY_NAME . esc_html__( ' Dynamic Tags', 'alpus-core' ),
                ),
            );
        }

        /**
         * Register tags.
         *
         * Add all the available dynamic tags.
         *
         * @since  2.0.0
         *
         * @param Manager $dynamic_tags
         */
        public function register_tags( $dynamic_tags ) {
            foreach ( $this->get_tag_classes_names() as $tag_class ) {
                $file     = str_replace( 'Alpus_Core_', '', $tag_class );
                $file     = str_replace( '_', '-', strtolower( $file ) ) . '.php';
                $filepath = alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/elementor/dynamic_tags/tags/' . $file );

                if ( file_exists( $filepath ) ) {
                    require_once $filepath;
                }

                if ( class_exists( $tag_class ) ) {
                    $dynamic_tags->register( new $tag_class() );
                }
            }

            do_action( 'alpus_dynamic_tags_register', $dynamic_tags );
        }

        /**
         * Set current post type
         *
         * @since 1.0.0
         */
        public function before_render( $post_type = '', $id = '' ) {
            global $post;

            if ( ! $post_type ) {
                $post_type = get_post_type();
            }

            if ( ! $id && $post ) {
                $id = $post->ID;
            }

            if ( ALPUS_NAME . '_template' == $post_type && isset( $id ) ) {
                if ( 'single' == get_post_meta( $id, ALPUS_NAME . '_template_type', true ) ) {
                    /**
                     * Filters the preview for editor and template.
                     *
                     * @since 1.0
                     */
                    $single = apply_filters( 'alpus_single_builder_set_preview', false );
                } elseif ( 'archive' == get_post_meta( $id, ALPUS_NAME . '_template_type', true ) ) {
                    /**
                     * Filters the preview for editor and template view.
                     *
                     * @since 1.0
                     */
                    $archive = apply_filters( 'alpus_archive_builder_set_preview', false );
                } elseif ( 'product_layout' == get_post_meta( $id, ALPUS_NAME . '_template_type', true ) ) {
                    /**
                     * Filters post products in single product builder
                     *
                     * @since 1.0
                     */
                    $product = apply_filters( 'alpus_single_product_builder_set_preview', false );
                }
            }
        }

        /**
         * Reset current post type
         *
         * @since 1.0.0
         */
        public function after_render( $post_type = '', $id = '' ) {
            global $post;

            if ( ! $post_type ) {
                $post_type = get_post_type();
            }

            if ( ! $id && $post ) {
                $id = $post->ID;
            }

            if ( ALPUS_NAME . '_template' == $post_type && isset( $id ) ) {
                if ( 'single' == get_post_meta( $id, ALPUS_NAME . '_template_type', true ) ) {
                    do_action( 'alpus_single_builder_unset_preview' );
                } elseif ( 'archive' == get_post_meta( $id, ALPUS_NAME . '_template_type', true ) ) {
                    do_action( 'alpus_archive_builder_unset_preview' );
                } elseif ( 'product_layout' == get_post_meta( $id, ALPUS_NAME . '_template_type', true ) ) {
                    do_action( 'alpus_single_product_builder_unset_preview' );
                }
            }
        }
    }
    new Alpus_Core_Dynamic_Tags();
}
