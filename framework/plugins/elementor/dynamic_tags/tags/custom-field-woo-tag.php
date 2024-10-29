<?php
/**
 * Alpus Dynamic Tags class
 *
 * @author     D-THEMES
 *
 * @since      1.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Alpus_Core_Custom_Field_Woo_Tag extends Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'alpus-custom-field-woo';
    }

    public function get_title() {
        return esc_html__( 'WooCommerce', 'alpus-core' );
    }

    public function get_group() {
        return Alpus_Core_Dynamic_Tags::ALPUS_CORE_GROUP;
    }

    public function get_categories() {
        return array(
            Alpus_Core_Dynamic_Tags::TEXT_CATEGORY,
            Alpus_Core_Dynamic_Tags::NUMBER_CATEGORY,
        );
    }

    protected function register_controls() {
        $this->add_control(
            'dynamic_field_source',
            array(
                'label'   => esc_html__( 'Source', 'alpus-core' ),
                'type'    => Elementor\Controls_Manager::HIDDEN,
                'default' => 'woo',
            )
        );

        /*
         * Fires before set current post type.
         *
         * @since 1.0
         */
        do_action( 'alpus_core_dynamic_before_render' );

        //Add WooCommerce field
        do_action( 'alpus_dynamic_extra_fields', $this, 'field', 'woo' );

        /*
         * Fires after set current post type.
         *
         * @since 1.0
         */
        do_action( 'alpus_core_dynamic_after_render' );
    }

    public function render() {
        if ( is_404() ) {
            return;
        }

        /*
         * Fires before set current post type.
         *
         * @since 1.0
         */
        do_action( 'alpus_core_dynamic_before_render' );

        $post_id = get_the_ID();
        $atts    = $this->get_settings();
        $ret     = '';

        /**
         * Filters the content for dynamic extra fields.
         *
         * @since 1.0
         */
        $ret = apply_filters( 'alpus_dynamic_extra_fields_content', null, $atts, 'field' );

        if ( ! is_wp_error( $ret ) ) {
            echo alpus_strip_script_tags( $ret );
        }

        /*
         * Fires after set current post type.
         *
         * @since 1.0
         */
        do_action( 'alpus_core_dynamic_after_render' );
    }
}
