<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/*
 * Alpus Testimonial Widget
 *
 * Alpus Widget to display testimonial.
 *
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Alpus_Testimonial_Group_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_testimonial_group';
    }

    public function get_title() {
        return esc_html__( 'Testimonials', 'alpus-core' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-testimonial';
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'testimonial', 'rating', 'comment', 'review', 'customer', 'slider', 'grid', 'group' );
    }

    /**
     * Get Style depends.
     *
     * @since 1.2.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-testimonial', alpus_core_framework_uri( '/widgets/testimonial/testimonial' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

        return array( 'alpus-testimonial' );
    }

    public function get_script_depends() {
        return array();
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_testimonial_group',
            array(
                'label' => esc_html__( 'Testimonials', 'alpus-core' ),
            )
        );

        $repeater = new Repeater();

        alpus_elementor_testimonial_content_controls( $repeater );

        $presets = array(
            array(
                'name'          => esc_html__( 'John Doe', 'alpus-core' ),
                'role'          => esc_html__( 'Programmer', 'alpus-core' ),
                'comment_title' => '',
                'content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.', 'alpus-core' ),
            ),
            array(
                'name'          => esc_html__( 'Henry Harry', 'alpus-core' ),
                'role'          => esc_html__( 'Banker', 'alpus-core' ),
                'comment_title' => '',
                'content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.', 'alpus-core' ),
            ),
            array(
                'name'          => esc_html__( 'Tom Jakson', 'alpus-core' ),
                'role'          => esc_html__( 'Vendor', 'alpus-core' ),
                'comment_title' => '',
                'content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.', 'alpus-core' ),
            ),
        );

        $this->add_control(
            'testimonial_group_list',
            array(
                'label'   => esc_html__( 'Testimonial Group', 'alpus-core' ),
                'type'    => Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'default' => $presets,
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_testimonials_layout',
            array(
                'label' => esc_html__( 'Testimonials Layout', 'alpus-core' ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'       => esc_html__( 'Testimonials Layout', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'grid',
                'options'     => array(
                    'grid'   => array(
                        'title' => esc_html__( 'Grid', 'alpus-core' ),
                        'icon'  => 'eicon-column',
                    ),
                    'slider' => array(
                        'title' => esc_html__( 'Slider', 'alpus-core' ),
                        'icon'  => 'eicon-slider-3d',
                    ),
                ),
                'qa_selector' => '.testimonial-group',
            )
        );

        alpus_elementor_grid_layout_controls( $this, 'layout_type' );

        $this->end_controls_section();

        $this->start_controls_section(
            'testimonial_general',
            array(
                'label' => esc_html__( 'Testimonial Type', 'alpus-core' ),
            )
        );

        alpus_elementor_testimonial_type_controls( $this );

        $this->add_control(
            'star_icon',
            array(
                'label'   => esc_html__( 'Star Icon', 'alpus-core' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''        => 'Theme',
                    'fa-icon' => 'Font Awesome',
                ),
            )
        );

        $this->end_controls_section();

        alpus_elementor_testimonial_style_controls( $this );

        alpus_elementor_slider_style_controls( $this, 'layout_type' );
    }

    protected function render() {
        $atts = $this->get_settings_for_display();
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/testimonial/render-testimonial-group-elementor.php' );
    }
}
