<?php
/**
 * Alpus Header Elementor Mobile Menu Toggle
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;

class Alpus_Header_Mmenu_Toggle_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_header_mmenu_toggle';
    }

    public function get_title() {
        return esc_html__( 'Mobile Menu Toggle', 'alpus-core' );
    }

    public function get_icon() {
        return ALPUS_ICON_PREFIX . '-icon-hamburger alpus-elementor-widget-icon';
    }

    public function get_categories() {
        return array( 'alpus_header_widget' );
    }

    public function get_keywords() {
        return array( 'header', 'alpus', 'toggle', 'menu', 'mobile', 'button' );
    }

    public function get_script_depends() {
        $depends = array();

        if ( alpus_is_elementor_preview() ) {
            $depends[] = 'alpus-elementor-js';
        }

        return $depends;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_toggle_content',
            array(
                'label' => esc_html__( 'Mobile Menu Toggle', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'icon_description',
            array(
                'raw'             => sprintf( esc_html__( 'You can customize to show menu on mobile in %1$sCustomize Panel/Menus/Mobile Menu%2$s', 'alpus-core' ), '<a href="' . wp_customize_url() . '#mobile_menu" data-target="mobile_menu" data-type="section" target="_blank">', '</a>.' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'alpus-notice notice-warning',
            )
        );

        $this->add_control(
            'icon',
            array(
                'label'                  => esc_html__( 'Icon', 'alpus-core' ),
                'type'                   => Controls_Manager::ICONS,
                'default'                => array(
                    'value'   => ALPUS_ICON_PREFIX . '-icon-hamburger',
                    'library' => 'alpus-icons',
                ),
                'skin'                   => 'inline',
                'exclude_inline_options' => array( 'svg' ),
                'label_block'            => false,
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'       => esc_html__( 'Icon Size', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} a.mobile-menu-toggle i' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'description' => esc_html__( 'Controls the icon size.', 'alpus-core' ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style',
            array(
                'label' => esc_html__( 'Mobile Menu Toggle', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'toggle_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'description' => esc_html__( 'Controls the padding value of toggle.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'toggle_border',
            array(
                'label'       => esc_html__( 'Border Width', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
                'description' => esc_html__( 'Controls the border width of toggle.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'toggle_border_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
                'description' => esc_html__( 'Controls the border radius of toggle.', 'alpus-core' ),
            )
        );

        $this->start_controls_tabs( 'tabs_toggle_color' );
        $this->start_controls_tab(
            'tab_toggle_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_control(
            'toggle_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_back_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_toggle_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $this->add_control(
            'toggle_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_hover_back_color',
            array(
                'label'     => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_hover_border_color',
            array(
                'label'     => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .mobile-menu-toggle:hover' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $atts     = array(
            'icon_class' => $settings['icon']['value'],
        );
        require alpus_core_framework_path( ALPUS_BUILDERS . '/header/widgets/mmenu-toggle/render-mmenu-toggle-elementor.php' );
    }
}
