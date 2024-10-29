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

class Alpus_Core_Custom_Field_Meta_Box_Tag extends Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'alpus-custom-field-meta-box';
    }

    public function get_title() {
        return esc_html__( 'Meta Box', 'alpus-core' );
    }

    public function get_group() {
        return Alpus_Core_Dynamic_Tags::ALPUS_CORE_GROUP;
    }

    public function get_categories() {
        return array(
            Alpus_Core_Dynamic_Tags::TEXT_CATEGORY,
            Alpus_Core_Dynamic_Tags::NUMBER_CATEGORY,
            Alpus_Core_Dynamic_Tags::URL_CATEGORY,
            Alpus_Core_Dynamic_Tags::POST_META_CATEGORY,
            Alpus_Core_Dynamic_Tags::COLOR_CATEGORY,
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

        //Add meta box field
        do_action( 'alpus_dynamic_extra_fields', $this, 'field', 'meta-box' );

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

        if ( is_array( $ret ) ) {
            $temp_content = '';

            if ( count( $ret ) > 1 ) {
                foreach ( $ret as $value ) {
                    $temp_content .= (string) $value;
                }
            } else {
                $temp_content .= isset( $ret[0] ) ? (string) $ret[0] : '';
            }
            $ret = $temp_content;
        }

        if ( ! is_wp_error( $ret ) ) {
            echo alpus_strip_script_tags( $ret );
        }

        do_action( 'alpus_core_dynamic_after_render' );
    }
}
