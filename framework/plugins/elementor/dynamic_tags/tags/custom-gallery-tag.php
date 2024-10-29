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
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Alpus_Core_Custom_Gallery_Tag extends Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'alpus-custom-gallery';
    }

    public function get_title() {
        return esc_html__( 'Meta Box', 'alpus-core' );
    }

    public function get_group() {
        return Alpus_Core_Dynamic_Tags::ALPUS_CORE_GROUP;
    }

    public function get_categories() {
        return array(
            Alpus_Core_Dynamic_Tags::GALLERY_CATEGORY,
        );
    }

    protected function register_controls() {
        $this->add_control(
            'dynamic_field_source',
            array(
                'label'   => esc_html__( 'Source', 'alpus-core' ),
                'type'    => Elementor\Controls_Manager::HIDDEN,
                'default' => 'meta-box',
            )
        );

        /*
         * Fires before set current post type.
         *
         * @since 1.0
         */
        do_action( 'alpus_core_dynamic_before_render' );

        //Add metabox field
        do_action( 'alpus_dynamic_extra_fields', $this, 'image', 'meta-box' );

        /*
         * Fires after set current post type.
         *
         * @since 1.0
         */
        do_action( 'alpus_core_dynamic_after_render' );
    }

    public function get_value( array $options = array() ) {
        if ( is_404() ) {
            return;
        }

        /*
         * Fires before set current post type.
         *
         * @since 1.0
         */
        do_action( 'alpus_core_dynamic_before_render' );

        /**
         * Filters the content for dynamic extra fields.
         *
         * @since 1.0
         */
        $image_id = apply_filters( 'alpus_dynamic_extra_fields_content', null, $this->get_settings(), 'image' );

        /*
         * Fires after set current post type.
         *
         * @since 1.0
         */
        do_action( 'alpus_core_dynamic_after_render' );

        if ( ! $image_id ) {
            return array();
        }

        return array_map(
            function ( $item ) {
                return array( 'id' => $item );
            },
            $image_id
        );
    }
}
