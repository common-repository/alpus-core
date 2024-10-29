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

class Alpus_Core_Custom_Image_Woo_Tag extends Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'alpus-custom-image-woo';
    }

    public function get_title() {
        return esc_html__( 'WooCommerce', 'alpus-core' );
    }

    public function get_group() {
        return Alpus_Core_Dynamic_Tags::ALPUS_CORE_GROUP;
    }

    public function get_categories() {
        return array(
            Alpus_Core_Dynamic_Tags::IMAGE_CATEGORY,
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

        do_action( 'alpus_core_dynamic_before_render' );

        //Add acf field
        do_action( 'alpus_dynamic_extra_fields', $this, 'image', 'woo' );

        do_action( 'alpus_core_dynamic_after_render' );
    }

    public function register_advanced_section() {
        $this->start_controls_section(
            'advanced',
            array(
                'label' => esc_html__( 'Advanced', 'alpus-core' ),
            )
        );

        $this->add_control(
            'fallback',
            array(
                'label' => esc_html__( 'Fallback', 'alpus-core' ),
                'type'  => Elementor\Controls_Manager::MEDIA,
            )
        );

        $this->end_controls_section();
    }

    public function get_value( array $options = array() ) {
        if ( is_404() ) {
            return;
        }

        do_action( 'alpus_core_dynamic_before_render' );

        $image_id = '';
        $atts     = $this->get_settings();

        /**
         * Filters the content for dynamic extra fields.
         *
         * @since 1.0
         */
        $image_id = apply_filters( 'alpus_dynamic_extra_fields_content', null, $atts, 'image' );

        if ( is_array( $image_id ) && count( $image_id ) ) {
            $image_id = $image_id[0];
        }

        do_action( 'alpus_core_dynamic_after_render' );

        if ( ! $image_id ) {
            return $atts['fallback'];
        }

        return array(
            'id'  => $image_id,
            'url' => wp_get_attachment_image_src( $image_id, 'full' )[0],
        );
    }
}
