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

class Alpus_Core_Custom_Image_Tag extends Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'alpus-custom-image';
    }

    public function get_title() {
        return esc_html__( 'Custom Image', 'alpus-core' );
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
                'type'    => Elementor\Controls_Manager::SELECT,
                'default' => 'featured',
                'options' => $this->get_objects(),
            )
        );

        //Add acf field
        do_action( 'alpus_dynamic_extra_fields', $this, 'image' );
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

        $image_id  = '';
        $image_url = '';
        $atts      = $this->get_settings();

        switch ( $atts['dynamic_field_source'] ) {
            case 'featured':
                global $post;
                $image_id = get_post_thumbnail_id( $post->ID );
                break;

            case 'user_avatar':
                $current_user = wp_get_current_user();

                if ( $current_user ) {
                    $image_url = get_avatar_url( $current_user->ID );
                }
                break;
            default:
                /**
                 * Filters the content for dynamic extra fields.
                 *
                 * @since 1.0
                 */
                $image_id = apply_filters( 'alpus_dynamic_extra_fields_content', null, $atts, 'image' );

                if ( is_array( $image_id ) && count( $image_id ) ) {
                    $image_id = $image_id[0];
                }
                break;
        }

        do_action( 'alpus_core_dynamic_after_render' );

        if ( ! $image_id && ! $image_url ) {
            return $atts['fallback'];
        }

        return array(
            'id'  => $image_id,
            'url' => $image_id ? wp_get_attachment_image_src( $image_id, 'full' )[0] : $image_url,
        );
    }

    public function get_objects() {
        $objects = array(
            'featured'    => esc_html__( 'Featured Image', 'alpus-core' ),
            'user_avatar' => esc_html__( 'User Avatar', 'alpus-core' ),
        );

        /*
         * Filters the object adding to dynamic field.
         *
         * @since 1.0
         */
        return apply_filters( 'alpus_dynamic_field_object', $objects );
    }
}
