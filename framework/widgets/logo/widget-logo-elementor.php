<?php
/**
 * Alpus Header Elementor Logo
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;

class Alpus_Logo_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_header_site_logo';
    }

    public function get_title() {
        return esc_html__( 'Site Logo', 'alpus-core' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-logo';
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'alpus', 'header', 'logo', 'site' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_logo_content',
            array(
                'label' => esc_html__( 'Site Logo', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_responsive_control(
            'logo_align',
            array(
                'label'       => esc_html__( 'Alignment', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'default'     => 'left',
                'description' => esc_html__( 'Controls the horizontal alignment of site logo.', 'alpus-core' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}}' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_logo' );

        $this->start_controls_tab(
            'tab_logo_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'logo_width',
            array(
                'label'       => esc_html__( 'Width', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem' ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 10,
                        'max'  => 300,
                    ),
                    'rem' => array(
                        'step' => 0.5,
                        'min'  => 1,
                        'max'  => 30,
                    ),
                ),
                'description' => esc_html__( 'Set the width of site logo.', 'alpus-core' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .logo img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'logo_max_width',
            array(
                'label'       => esc_html__( 'Max Width', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem' ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 10,
                        'max'  => 300,
                    ),
                    'rem' => array(
                        'step' => 0.5,
                        'min'  => 1,
                        'max'  => 30,
                    ),
                ),
                'description' => esc_html__( 'Set the max-width of site logo.', 'alpus-core' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .logo img' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_logo_sticky',
            array(
                'label' => esc_html__( 'In Sticky', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'logo_width_sticky',
            array(
                'label'       => esc_html__( 'Width in Sticky', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem' ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 10,
                        'max'  => 300,
                    ),
                    'rem' => array(
                        'step' => 0.5,
                        'min'  => 1,
                        'max'  => 30,
                    ),
                ),
                'description' => esc_html__( 'Set the width of site logo on sticky section.', 'alpus-core' ),
                'selectors'   => array(
                    '.fixed .elementor-element-{{ID}} .logo img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'logo_max_width_sticky',
            array(
                'label'       => esc_html__( 'Max Width in Sticky', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem' ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 10,
                        'max'  => 300,
                    ),
                    'rem' => array(
                        'step' => 0.5,
                        'min'  => 1,
                        'max'  => 30,
                    ),
                ),
                'description' => esc_html__( 'Set the max-width of site logo on sticky section.', 'alpus-core' ),
                'selectors'   => array(
                    '.fixed .elementor-element-{{ID}} .logo img' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/logo/render-logo.php' );
    }
}
