<?php
/**
 * Alpus Common Elementor
 *
 * Enhanced elementor base common widget that gives you all the advanced options of the basic.
 * Added Alpus custom CSS and JS control
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Alpus_Common_Elementor_Widget extends \Elementor\Widget_Common {

    /**
         * Default Settings for WP Alpus Effect
         *
         * @since 1.0
         */
    public $default_addional_settings = array();

    public function __construct( array $data = array(), array $args = null ) {
        /*
         * Filters default settings.
         *
         * @since 1.0
         */
        $this->default_additional_settings = apply_filters( 'alpus_dr_settings', array() );
        parent::__construct( $data, $args );
        add_action( 'elementor/frontend/widget/before_render', array( $this, 'widget_before_render' ) );
        add_action( 'elementor/widget/before_render_content', array( $this, 'widget_before_render_content' ) );
        /*
         * Fires after alpus common elementor widget construct.
         *
         * @since 1.0
         */
        do_action( 'alpus_common_elementor_widget_actions', $this );
    }

    protected function register_controls() {
        parent::register_controls();

        alpus_elementor_addon_controls( $this );
    }

    public function widget_before_render( $widget ) {
        $settings = $widget->get_settings_for_display();
        $widget->add_render_attribute(
            '_wrapper',
            alpus_get_elementor_addon_options( $settings )
        );
    }

    /**
     * prints other widget html before rendering
     *
     * @since 1.0
     */
    public function widget_before_render_content( $widget ) {
        $data     = $widget->get_data();
        $settings = $data['settings'];
        /*
         * Fires after rendering effect addons such as duplex and ribbon.
         *
         * @since 1.0
         */
        do_action( 'alpus_elementor_addon_render', $settings, $widget->get_ID() );
    }
}
