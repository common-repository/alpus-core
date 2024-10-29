<?php
/**
 * Button Partial Extend
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

/*
 * Register elementor style controls for button.
 */
if ( ! function_exists( 'alpus_elementor_button_style_controls' ) ) {
    function alpus_elementor_button_style_controls( $self, $condition = array(), $section_heading = '', $name_prefix = '', $repeater = false, $section = true, $layout_exists = true ) {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        if ( $section ) {
            $self->start_controls_section(
                $name_prefix . 'section_button_style',
                array(
                    'label'     => $section_heading ? $section_heading : esc_html__( 'Button', 'alpus-core' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => $condition,
                )
            );
        }

        if ( ! $repeater ) {
            $self->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => $name_prefix . 'button_typography',
                    'label'    => esc_html__( 'Label Typography', 'alpus-core' ),
                    'scheme'   => Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn',
                )
            );
        }

        $self->add_responsive_control(
            $name_prefix . 'btn_min_width',
            array(
                'label'      => esc_html__( 'Min Width', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 400,
                    ),
                ),
                'size_units' => array(
                    'px',
                    '%',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn' => 'min-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        if ( ! $repeater ) {
            $self->add_responsive_control(
                $name_prefix . 'btn_padding',
                array(
                    'label'       => esc_html__( 'Padding', 'alpus-core' ),
                    'description' => esc_html__( 'Controls padding value of button.', 'alpus-core' ),
                    'type'        => Controls_Manager::DIMENSIONS,
                    'size_units'  => array(
                        'px',
                        '%',
                        'em',
                        'rem',
                    ),
                    'selectors'   => array(
                        '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $self->add_responsive_control(
                $name_prefix . 'btn_border_radius',
                array(
                    'label'      => esc_html__( 'Border Radius', 'alpus-core' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array(
                        'px',
                        '%',
                        'em',
                    ),
                    'condition'  => array(
                        $name_prefix . 'button_type!' => 'btn-outline2',
                    ),
                    'selectors'  => array(
                        '.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );
        }

        $self->add_responsive_control(
            $name_prefix . 'btn_border_width',
            array(
                'label'       => esc_html__( 'Border Width', 'alpus-core' ),
                'description' => esc_html__( 'Controls border width of buttons.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array(
                    'px',
                    '%',
                    'em',
                ),
                'condition'   => array(
                    $name_prefix . 'button_type!' => 'btn-outline2',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'underline_spacing',
            array(
                'label'       => esc_html__( 'Underline Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the spacing between label and underline.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 30,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn:after' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    $name_prefix . 'button_type'      => 'btn-link',
                    $name_prefix . 'link_hover_type!' => '',
                ),
            )
        );

        if ( ! $repeater ) {
            $self->start_controls_tabs( $name_prefix . 'tabs_btn_cat' );

            $self->start_controls_tab(
                $name_prefix . 'tab_btn_normal',
                array(
                    'label' => esc_html__( 'Normal', 'alpus-core' ),
                )
            );
        }

        $self->add_control(
            $name_prefix . 'btn_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn' => 'color: {{VALUE}};',
                ),
                'conditions'  => $layout_exists ? array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => $name_prefix . 'button_type',
                            'operator' => '!=',
                            'value'    => 'btn-gradient',
                        ),
                    ),
                ) : array(),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_back_color',
            array(
                'label'       => esc_html__( 'Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the background color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn' => 'background-color: {{VALUE}};',
                ),
                'conditions'  => $layout_exists ? array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => $name_prefix . 'button_type',
                            'operator' => '!=',
                            'value'    => 'btn-gradient',
                        ),
                    ),
                ) : array(),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_border_color',
            array(
                'label'       => esc_html__( 'Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the border color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn'                                                                                                          => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:before, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:after' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $self->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => $name_prefix . 'btn_box_shadow',
                'selector'  => '.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn',
                'condition' => array(
                    $name_prefix . 'button_type!' => 'btn-outline2',
                ),
            )
        );

        if ( ! $repeater ) {
            $self->end_controls_tab();

            $self->start_controls_tab(
                $name_prefix . 'tab_btn_hover',
                array(
                    'label' => esc_html__( 'Hover', 'alpus-core' ),
                )
            );
        }

        $self->add_control(
            $name_prefix . 'btn_color_hover',
            array(
                'label'       => esc_html__( 'Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn:hover' => 'color: {{VALUE}};',
                ),
                'conditions'  => $layout_exists ? array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => $name_prefix . 'button_type',
                            'operator' => '!=',
                            'value'    => 'btn-gradient',
                        ),
                    ),
                ) : array(),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_back_color_hover',
            array(
                'label'       => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover background color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn:hover' => 'background-color: {{VALUE}};',
                ),
                'conditions'  => $layout_exists ? array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => $name_prefix . 'button_type',
                            'operator' => '!=',
                            'value'    => 'btn-gradient',
                        ),
                    ),
                ) : array(),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_border_color_hover',
            array(
                'label'       => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover border color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn:hover'                                                                                                                => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:hover:before, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:hover:after' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $self->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => $name_prefix . 'btn_box_shadow_hover',
                'selector'  => '.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn:hover',
                'condition' => array(
                    $name_prefix . 'button_type!' => 'btn-outline2',
                ),
            )
        );

        if ( ! $repeater ) {
            $self->end_controls_tab();

            $self->start_controls_tab(
                $name_prefix . 'tab_btn_active',
                array(
                    'label' => esc_html__( 'Active', 'alpus-core' ),
                )
            );
        }

        $self->add_control(
            $name_prefix . 'btn_color_active',
            array(
                'label'      => esc_html__( 'Active Color', 'alpus-core' ),
                'type'       => Controls_Manager::COLOR,
                'selectors'  => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn:not(:focus):active, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn:focus' => 'color: {{VALUE}};',
                ),
                'conditions' => $layout_exists ? array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => $name_prefix . 'button_type',
                            'operator' => '!=',
                            'value'    => 'btn-gradient',
                        ),
                    ),
                ) : array(),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_back_color_active',
            array(
                'label'      => esc_html__( 'Active Background Color', 'alpus-core' ),
                'type'       => Controls_Manager::COLOR,
                'selectors'  => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn:not(:focus):active, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn:focus' => 'background-color: {{VALUE}};',
                ),
                'conditions' => $layout_exists ? array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => $name_prefix . 'button_type',
                            'operator' => '!=',
                            'value'    => 'btn-gradient',
                        ),
                    ),
                ) : array(),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_border_color_active',
            array(
                'label'     => esc_html__( 'Active Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn:not(:focus):active, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn:focus'                                                                                                                                                                                                                    => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:active:before, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:active:after, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:focus:before, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:focus:after' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $self->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => $name_prefix . 'btn_box_shadow_active',
                'selector'  => '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn:active, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn:focus',
                'condition' => array(
                    $name_prefix . 'button_type!' => 'btn-outline2',
                ),
            )
        );

        if ( ! $repeater ) {
            $self->end_controls_tab();

            $self->end_controls_tabs();
        }

        if ( $section ) {
            $self->end_controls_section();
        }

        if ( $section ) {
            if ( empty( $condition ) || ! is_array( $condition ) ) {
                $condition = array();
            }

            $self->start_controls_section(
                $name_prefix . 'section_button_icon_style',
                array(
                    'label'     => esc_html__( 'Button Icon Style', 'alpus-core' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => array_merge(
                        array(
                            $name_prefix . 'show_icon' => 'yes',
                        ),
                        $condition
                    ),
                )
            );
        }

        $self->add_responsive_control(
            $name_prefix . 'icon_space',
            array(
                'label'       => esc_html__( 'Icon Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the spacing between label and icon.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 30,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn-icon-left:not(.btn-reveal-left)' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ' i'                                                                                                                                                                                                                                                                    => "margin-{$right}: {{SIZE}}px; margin-{$left}: 0;",
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.btn-icon-right:not(.btn-reveal-right)' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ' i'                                                                                                                                                                                                                                                                  => "margin-{$left}: {{SIZE}}px;",
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-left:hover i, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-left:active i, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-left:focus i'     => "margin-{$right}: {{SIZE}}px;",
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-right:hover i, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-right:active i, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-right:focus i'  => "margin-{$left}: {{SIZE}}px;",

                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn-icon-left:not(.btn-reveal-left)' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ' svg'                                                                                                                                                                                                                                                                        => "margin-{$right}: {{SIZE}}px; margin-{$left}: 0;",
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn-icon-right:not(.btn-reveal-right)' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ' svg'                                                                                                                                                                                                                                                                      => "margin-{$left}: {{SIZE}}px;",
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-left:hover svg, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-left:active svg, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-left:focus svg'     => "margin-{$right}: {{SIZE}}px;",
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-right:hover svg, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-right:active svg, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' ' ) . '.btn-reveal-right:focus svg'  => "margin-{$left}: {{SIZE}}px;",
                ),
            )
        );

        $self->add_responsive_control(
            $name_prefix . 'icon_size',
            array(
                'label'       => esc_html__( 'Icon Size (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the size of the icon. In pixels.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 50,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ( $repeater ? '{{CURRENT_ITEM}}' : '.btn' ) . ' i'     => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} ' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ( $repeater ? '{{CURRENT_ITEM}}' : '.btn' ) . ' svg'   => 'width: {{SIZE}}px;font-size: {{SIZE}}px;',
                    '{{WRAPPER}} ' . ( $repeater ? '{{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' .btn' ) . ' i'   => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} ' . ( $repeater ? '{{CURRENT_ITEM}}' : '' ) . ( $name_prefix ? ' .btn-' . $name_prefix : ' .btn' ) . ' svg' => 'width: {{SIZE}}px;font-size: {{SIZE}}px;',
                ),
            )
        );

        if ( $section ) {
            $self->end_controls_section();
        }
    }
}
