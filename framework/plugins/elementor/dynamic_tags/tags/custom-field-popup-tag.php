<?php
/**
 * Alpus Dynamic Tags class
 *
 * @author     D-THEMES
 *
 * @since      1.3.0
 */

use Elementor\Alpus_Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Alpus_Core_Custom_Field_Popup_Tag extends Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'alpus-custom-field-popup';
    }

    public function get_title() {
        return esc_html__( 'Popup', 'alpus-core' );
    }

    public function get_group() {
        return Alpus_Core_Dynamic_Tags::ALPUS_CORE_GROUP;
    }

    public function get_categories() {
        return array(
            Alpus_Core_Dynamic_Tags::URL_CATEGORY,
        );
    }

    protected function register_advanced_section() {
    }

    protected function register_controls() {
        $this->add_control(
            'dynamic_popup_template',
            array(
                'label'       => esc_html__( 'Popup', 'alpus-core' ),
                'type'        => Alpus_Controls_Manager::AJAXSELECT2,
                'options'     => 'popup',
                'label_block' => true,
            )
        );
    }

    public function render() {
        wp_enqueue_style( 'alpus-lightbox' );
        wp_enqueue_script( 'alpus-lightbox' );

        $atts     = $this->get_settings();
        $popup_id = $atts['dynamic_popup_template'];
        $href     = '#' . ALPUS_NAME . '-action:popup-id-' . $popup_id;

        echo alpus_escaped( $href );
    }
}
