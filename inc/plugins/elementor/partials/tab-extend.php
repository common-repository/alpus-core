<?php
/**
 * Tab Partial Extend
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/*
 * Register elementor tab style controls
 */
if ( ! function_exists( 'alpus_elementor_tab_style_controls' ) ) {
    function alpus_elementor_tab_style_controls( $self, $condition_key = '' ) {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        $self->start_controls_section(
            'tab_style',
            array_merge(
                array(
                    'label' => alpus_elementor_panel_heading( esc_html__( 'Tab', 'alpus-core' ) ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ),
                $condition_key ? array(
                    'condition' => array(
                        $condition_key => 'tab',
                    ),
                ) : array()
            )
        );

        $self->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tab_nav_typography',
                'label'    => esc_html__( 'Nav Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .nav .nav-link',
            )
        );

        $self->add_responsive_control(
            'tab_nav_space',
            array(
                'label'       => esc_html__( 'Nav Space', 'alpus-core' ),
                'description' => esc_html__( 'Set the space between tab titles.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem', 'em' ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .tab' => '--alpus-tab-item-spacing: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $self->add_control(
            'tab_nav_border_radius',
            array(
                'label'       => esc_html__( 'Nav Border Radius', 'alpus-core' ),
                'description' => esc_html__( 'Set the border radius of tab titles.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-title-radius: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $self->add_control(
            'tab_nav_border_width',
            array(
                'label'       => esc_html__( 'Title Border Width', 'alpus-core' ),
                'description' => esc_html__( 'Set the underline(overline) width of tab titles.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem', 'em' ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 10,
                    ),
                    'rem' => array(
                        'step' => 0.1,
                        'min'  => 0,
                        'max'  => 1,
                    ),
                    'em'  => array(
                        'step' => 0.1,
                        'min'  => 0,
                        'max'  => 1,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-nav-border-width: {{SIZE}}{{UNIT}}',
                ),
                'condition'   => array(
                    'tab_type' => array( 'underline', 'border' ),
                ),
            )
        );

        $self->add_control(
            'tab_border_width',
            array(
                'label'       => esc_html__( 'Tab Border Width', 'alpus-core' ),
                'description' => esc_html__( 'Set the border width of tab.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem', 'em' ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 10,
                    ),
                    'rem' => array(
                        'step' => 0.1,
                        'min'  => 0,
                        'max'  => 1,
                    ),
                    'em'  => array(
                        'step' => 0.1,
                        'min'  => 0,
                        'max'  => 1,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-border-width: {{SIZE}}{{UNIT}}',
                ),
                'condition'   => array(
                    'tab_type' => array( 'underline', 'border' ),
                ),
            )
        );

        $self->add_control(
            'tab_solid_border_width',
            array(
                'label'       => esc_html__( 'Nav Border Width', 'alpus-core' ),
                'description' => esc_html__( 'Set the border width of nav.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem', 'em' ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 10,
                    ),
                    'rem' => array(
                        'step' => 0.1,
                        'min'  => 0,
                        'max'  => 1,
                    ),
                    'em'  => array(
                        'step' => 0.1,
                        'min'  => 0,
                        'max'  => 1,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab-solid .nav-link' => '--alpus-tab-nav-border-width: {{SIZE}}{{UNIT}}',
                ),
                'condition'   => array(
                    'tab_type' => array( 'solid' ),
                ),
            )
        );

        $self->add_responsive_control(
            'tab_nav_padding',
            array(
                'label'       => esc_html__( 'Nav Padding', 'alpus-core' ),
                'description' => esc_html__( 'Set the padding value of tab titles.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-title-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $self->add_control(
            'tab_border_color',
            array(
                'label'       => esc_html__( 'Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the border color skin of tab.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-border-color: {{VALUE}};',
                ),
            )
        );

        $self->start_controls_tabs( 'icon_bg_color' );

        $self->start_controls_tab(
            'tab_color_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $self->add_control(
            'tab_color',
            array(
                'label'       => esc_html__( 'Nav Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the normal color skin of tab titles.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-link'     => 'color: {{VALUE}};',
                    '.elementor-element-{{ID}} .nav-link svg' => 'fill: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'tab_bg_color',
            array(
                'label'       => esc_html__( 'Nav Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the background color of tab.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-background: {{VALUE}};',
                ),
            )
        );

        $self->end_controls_tab();

        $self->start_controls_tab(
            'tab_color_active',
            array(
                'label' => esc_html__( 'Active', 'alpus-core' ),
            )
        );

        $self->add_control(
            'tab_active_color',
            array(
                'label'       => esc_html__( 'Nav Active Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the active color skin of tab titles.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-active-color: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'tab_active_bg_color',
            array(
                'label'       => esc_html__( 'Nav Active Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the active background color of tab.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-active-background: {{VALUE}};',
                ),
            )
        );

        $self->end_controls_tab();

        $self->end_controls_tabs();

        $self->add_responsive_control(
            'tab_icon_padding',
            array(
                'label'       => esc_html__( 'Icon Padding', 'alpus-core' ),
                'description' => esc_html__( 'Set the padding value of tab title icons.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'separator'   => 'before',
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav .nav-link' => '--alpus-tab-icon-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $self->add_responsive_control(
            'tab_icon_border_radius',
            array(
                'label'       => esc_html__( 'Icon Border Radius', 'alpus-core' ),
                'description' => esc_html__( 'Set the border radius value of tab title icons.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-link i, .elementor-element-{{ID}} .nav-link svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $self->start_controls_tabs( 'tabs_icon_bg_color' );

        $self->start_controls_tab(
            'tab_icon_color_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $self->add_control(
            'tab_icon_color',
            array(
                'label'       => esc_html__( 'Icon Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the normal color of tab title icons.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-link i'   => 'color: {{VALUE}};',
                    '.elementor-element-{{ID}} .nav-link svg' => 'fill: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'tab_icon_bg_color',
            array(
                'label'       => esc_html__( 'Icon Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the background color of tab icons.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav .nav-link' => '--alpus-tab-icon-background: {{VALUE}};',
                ),
            )
        );

        $self->end_controls_tab();

        $self->start_controls_tab(
            'tab_icon_color_active',
            array(
                'label' => esc_html__( 'Active', 'alpus-core' ),
            )
        );

        $self->add_control(
            'tab_icon_active_color',
            array(
                'label'       => esc_html__( 'Nav Active Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the active color of tab title icons.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-link' => '--alpus-tab-icon-active-color: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'tab_icon_active_bg_color',
            array(
                'label'       => esc_html__( 'Nav Active Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Set the active background color of tab icons.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-link' => '--alpus-tab-icon-active-background: {{VALUE}};',
                ),
            )
        );

        $self->end_controls_tab();

        $self->end_controls_tabs();

        $self->add_responsive_control(
            'tab_content_padding',
            array(
                'label'       => esc_html__( 'Content Padding', 'alpus-core' ),
                'description' => esc_html__( 'Set the padding value of tab content.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'separator'   => 'before',
                'selectors'   => array(
                    '.elementor-element-{{ID}} .tab' => '--alpus-tab-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $self->end_controls_section();
    }
}
