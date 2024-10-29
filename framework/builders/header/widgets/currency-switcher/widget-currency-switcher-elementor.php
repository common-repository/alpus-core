<?php
/**
 * Alpus Header Elementor Currency Switcher
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Alpus_Header_Currency_Switcher_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_header_currency_switcher';
    }

    public function get_title() {
        return esc_html__( 'Currency Switcher', 'alpus-core' );
    }

    public function get_icon() {
        return 'fas fa-comment-dollar alpus-elementor-widget-icon';
    }

    public function get_categories() {
        return array( 'alpus_header_widget' );
    }

    public function get_keywords() {
        return array( 'header', 'switcher', 'currency', 'alpus', 'multi', 'price', 'usd', 'euro' );
    }

    public function get_script_depends() {
        $depends = array();

        if ( alpus_is_elementor_preview() ) {
            $depends[] = 'alpus-elementor-js';
        }

        return $depends;
    }

    protected function register_controls() {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        $this->start_controls_section(
            'section_toggle_style',
            array(
                'label' => esc_html__( 'Switcher Toggle', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'toggle_typography',
                'selector' => '.elementor-element-{{ID}} .switcher .switcher-toggle',
            )
        );

        $this->add_responsive_control(
            'toggle_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .switcher .switcher-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'description' => esc_html__( 'Controls the padding value of toggle.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'toggle_border',
            array(
                'label'       => esc_html__( 'Border Width', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .switcher .switcher-toggle' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
                'description' => esc_html__( 'Controls border width of toggle.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'toggle_border_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .switcher .switcher-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'description' => esc_html__( 'Controls border radius of toggle.', 'alpus-core' ),
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
                    '.elementor-element-{{ID}} .switcher .switcher-toggle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_back_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .switcher .switcher-toggle' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .switcher .switcher-toggle' => 'border-color: {{VALUE}};',
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
                    '.elementor-element-{{ID}} .menu.switcher > li:hover > a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_hover_back_color',
            array(
                'label'     => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu.switcher > li:hover > a' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_hover_border_color',
            array(
                'label'     => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu.switcher > li:hover > a' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            array(
                'label' => esc_html__( 'Currency item', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'item_typography',
                'selector' => '.elementor-element-{{ID}} .switcher ul a',
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .switcher ul a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'description' => esc_html__( 'Controls the padding value of currency item.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'item_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .switcher ul a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'item_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .switcher ul > li:hover a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dropdown_style',
            array(
                'label' => esc_html__( 'Dropdown Box', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'dropdown_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .menu.switcher ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'description' => esc_html__( 'Controls the padding value of dropdown box.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'dropdown_position',
            array(
                'label'       => esc_html__( 'Position', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .menu.switcher ul'         => "{$left}: {{SIZE}}{{UNIT}}; {$right}: auto;",
                    '.elementor-element-{{ID}} .menu.switcher >li::after' => 'transform:translate3d(calc( -50% + {{SIZE}}{{UNIT}} ), 0, 0)',
                ),
                'description' => esc_html__( 'Controls position value of dropdown box.', 'alpus-core' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'dropdown_box_shadow',
                'selector' => '.elementor-element-{{ID}} .switcher ul',
            )
        );

        $this->add_control(
            'dropdown_bg',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .switcher ul'        => 'background: {{VALUE}};',
                    '.elementor-element-{{ID}} .switcher >li:after' => 'border-bottom-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        require alpus_core_framework_path( ALPUS_BUILDERS . '/header/widgets/currency-switcher/render-currency-switcher-elementor.php' );
    }
}