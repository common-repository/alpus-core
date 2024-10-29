<?php
/**
 * Slider Partial Extend
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
use Elementor\Controls_Manager;
use Elementor\Repeater;

/*
 * Register elementor style controls for slider.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_slider_style_controls' ) ) {
    function alpus_elementor_slider_style_controls( $self, $condition_key = '', $widget = true ) {
        $left  = is_rtl() ? 'right' : 'left';
        $right = is_rtl() ? 'left' : 'right';

        if ( empty( $condition_key ) ) {
            $self->start_controls_section(
                'slider_style',
                array(
                    'label' => $widget ? esc_html__( 'Slider', 'alpus-core' ) : alpus_elementor_panel_heading( esc_html__( 'Slider', 'alpus-core' ) ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                )
            );
        } else {
            $self->start_controls_section(
                'slider_style',
                array(
                    'label'     => $widget ? esc_html__( 'Slider', 'alpus-core' ) : alpus_elementor_panel_heading( esc_html__( 'Slider', 'alpus-core' ) ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => array(
                        $condition_key => 'slider',
                    ),
                )
            );
        }
        $self->add_control(
            'style_heading_slider_options',
            array(
                'label' => esc_html__( 'Slider Options', 'alpus-core' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $self->add_control(
            'centered',
            array(
                'label'       => esc_html__( 'Centered Slider', 'alpus-core' ),
                'description' => esc_html__( 'Displays a slide at center of your slider container.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
            )
        );

        $self->add_control(
            'loop',
            array(
                'label'       => esc_html__( 'Enable Loop', 'alpus-core' ),
                'description' => esc_html__( 'Makes slides of slider play sliding infinitely.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
            )
        );

        $self->add_control(
            'autoplay',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'Autoplay', 'alpus-core' ),
                'description' => esc_html__( 'Enables each slides play sliding automatically.', 'alpus-core' ),
                'condition'   => array(
                    'loop' => 'yes',
                ),
            )
        );

        $self->add_control(
            'autoplay_timeout',
            array(
                'type'        => Controls_Manager::NUMBER,
                'label'       => esc_html__( 'Autoplay Timeout', 'alpus-core' ),
                'description' => esc_html__( 'Controls how long each slides should be shown.', 'alpus-core' ),
                'default'     => 5000,
                'condition'   => array(
                    'autoplay' => 'yes',
                    'loop'     => 'yes',
                ),
            )
        );

        $self->add_control(
            'autoheight',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'Auto Height', 'alpus-core' ),
                'description' => esc_html__( 'Makes each slides have their own height. Slides could have different height.', 'alpus-core' ),
            )
        );

        $self->add_control(
            'disable_mouse_drag',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'Disable Mouse Drag', 'alpus-core' ),
                'description' => esc_html__( 'Disable ability move slider by grabbing it with mouse or by touching it with finger.', 'alpus-core' ),
            )
        );

        $self->add_control(
            'effect',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => esc_html__( 'Transition Effect', 'alpus-core' ),
                'description' => esc_html__( 'Transition Effect when slide changes.', 'alpus-core' ),
                'default'     => 'slide',
                'options'     => array(
                    'slide'     => esc_html__( 'Slide', 'alpus-core' ),
                    'fade'      => esc_html__( 'Fade', 'alpus-core' ),
                    'cube'      => esc_html__( 'Cube', 'alpus-core' ),
                    'coverflow' => esc_html__( 'Coverflow', 'alpus-core' ),
                    'flip'      => esc_html__( 'Flip', 'alpus-core' ),
                ),
            )
        );

        $self->add_control(
            'speed',
            array(
                'type'        => Controls_Manager::NUMBER,
                'label'       => esc_html__( 'Transition Speed', 'alpus-core' ),
                'description' => esc_html__( 'Controls how long it takes to change to the next slide.', 'alpus-core' ),
            )
        );

        $self->add_control(
            'style_heading_nav',
            array(
                'label'     => esc_html__( 'Navs', 'alpus-core' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $self->add_control(
            'show_nav',
            array(
                'label'       => esc_html__( 'Show Nav', 'alpus-core' ),
                'description' => esc_html__( 'Determine whether to show/hide slider navigations.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
            )
        );

        $self->add_control(
            'nav_hide',
            array(
                'label'       => esc_html__( 'Nav Auto Hide', 'alpus-core' ),
                'description' => esc_html__( 'Hides slider navs automatically and show them only if mouse is over.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => '',
                'condition'   => array(
                    'show_nav' => 'yes',
                ),
            )
        );

        $self->add_control(
            'nav_type',
            array(
                'label'       => esc_html__( 'Nav Type', 'alpus-core' ),
                'description' => esc_html__( 'Choose from icon presets of slider nav. Choose from Simple, Circle, Full.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'simple',
                'options'     => array(
                    'simple' => esc_html__( 'Simple', 'alpus-core' ),
                    'circle' => esc_html__( 'Circle', 'alpus-core' ),
                    'full'   => esc_html__( 'Full', 'alpus-core' ),
                ),
                'condition'   => array(
                    'show_nav' => 'yes',
                ),
            )
        );

        $self->add_control(
            'nav_pos',
            array(
                'label'       => esc_html__( 'Nav Position', 'alpus-core' ),
                'description' => esc_html__( 'Choose position of slider navs. Choose from Inner, Outer, Top, Bottom.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => array(
                    'inner'  => esc_html__( 'Inner', 'alpus-core' ),
                    ''       => esc_html__( 'Outer', 'alpus-core' ),
                    'top'    => esc_html__( 'Top', 'alpus-core' ),
                    'bottom' => esc_html__( 'Bottom', 'alpus-core' ),
                ),
                'condition'   => array(
                    'nav_type!' => 'full',
                    'show_nav'  => 'yes',
                ),
            )
        );

        $self->add_responsive_control(
            'nav_h_position',
            array(
                'label'       => esc_html__( 'Nav Horizontal Position', 'alpus-core' ),
                'description' => esc_html__( 'Controls horizontal position of slider navs.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -500,
                        'max'  => 500,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button-prev' => ( is_rtl() ? 'right' : 'left' ) . ': {{SIZE}}{{UNIT}}',
                    '.elementor-element-{{ID}} .slider-container .slider-button-next' => ( is_rtl() ? 'left' : 'right' ) . ': {{SIZE}}{{UNIT}}',
                ),
                'condition'   => array(
                    'nav_type!' => 'full',
                    'nav_pos'   => 'inner',
                    'show_nav'  => 'yes',
                ),
            )
        );

        $self->add_responsive_control(
            'nav_outer_h_position',
            array(
                'label'       => esc_html__( 'Nav Horizontal Position', 'alpus-core' ),
                'description' => esc_html__( 'Controls horizontal position of slider navs.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -500,
                        'max'  => 500,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-nav-outer' => '--alpus-nav-outer-pos: {{SIZE}}{{UNIT}}',
                ),
                'condition'   => array(
                    'nav_type!' => 'full',
                    'nav_pos'   => '',
                    'show_nav'  => 'yes',
                ),
            )
        );

        $self->add_responsive_control(
            'nav_top_h_position',
            array(
                'label'       => esc_html__( 'Nav Horizontal Position', 'alpus-core' ),
                'description' => esc_html__( 'Controls horizontal position of slider navs.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -500,
                        'max'  => 500,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button'                                                                                                          => ( is_rtl() ? 'left' : 'right' ) . ': {{SIZE}}{{UNIT}}',
                    '.elementor-element-{{ID}} .slider-nav-circle.slider-nav-bottom .slider-button-prev, .elementor-element-{{ID}} .slider-nav-circle.slider-nav-top .slider-button-prev' => ( is_rtl() ? 'left' : 'right' ) . ': calc({{SIZE}}{{UNIT}} + .2em)',
                ),
                'condition'   => array(
                    'nav_type!' => 'full',
                    'nav_pos'   => array( 'top', 'bottom' ),
                    'show_nav'  => 'yes',
                ),
            )
        );

        $self->add_responsive_control(
            'nav_v_position_top',
            array(
                'label'       => esc_html__( 'Nav Vertical Position', 'alpus-core' ),
                'description' => esc_html__( 'Controls vertical position of slider navs.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -500,
                        'max'  => 500,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button' => 'top: {{SIZE}}{{UNIT}}',
                ),
                'condition'   => array(
                    'nav_type!' => 'full',
                    'nav_pos'   => 'top',
                    'show_nav'  => 'yes',
                ),
            )
        );

        $self->add_responsive_control(
            'nav_v_position_bottom',
            array(
                'label'       => esc_html__( 'Nav Vertical Position', 'alpus-core' ),
                'description' => esc_html__( 'Controls vertical position of slider navs.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -500,
                        'max'  => 500,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button' => 'bottom: {{SIZE}}{{UNIT}}',
                ),
                'condition'   => array(
                    'nav_type!' => 'full',
                    'nav_pos'   => 'bottom',
                    'show_nav'  => 'yes',
                ),
            )
        );

        $self->add_responsive_control(
            'nav_v_position',
            array(
                'label'       => esc_html__( 'Nav Vertical Position', 'alpus-core' ),
                'description' => esc_html__( 'Controls vertical position of slider navs.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -500,
                        'max'  => 500,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-button' => 'top: {{SIZE}}{{UNIT}}; transform: none;',
                ),
                'condition'   => array(
                    'nav_type!' => 'full',
                    'nav_pos!'  => array( 'top', 'bottom' ),
                    'show_nav'  => 'yes',
                ),
            )
        );
        $self->add_responsive_control(
            'slider_nav_size',
            array(
                'type'        => Controls_Manager::SLIDER,
                'label'       => esc_html__( 'Nav Size', 'alpus-core' ),
                'description' => esc_html__( 'Controls the nav size.', 'alpus-core' ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 10,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-button' => 'font-size: {{SIZE}}px',
                ),
                'condition'   => array(
                    'show_nav' => 'yes',
                ),
            )
        );

        $self->add_responsive_control(
            'slider_nav_spacing',
            array(
                'type'        => Controls_Manager::SLIDER,
                'label'       => esc_html__( 'Space Between', 'alpus-core' ),
                'description' => esc_html__( 'Controls the nav spacing.', 'alpus-core' ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 10,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-button-prev:before' => 'margin-left: -{{SIZE}}px',
                    '.elementor-element-{{ID}} .slider-button-next:before' => 'margin-right: -{{SIZE}}px',
                ),
                'condition'   => array(
                    'show_nav' => 'yes',
                    'nav_pos'  => array( 'top', 'bottom' ),
                ),
            )
        );

        $self->start_controls_tabs(
            'tabs_nav_style',
            array(
                'condition' => array(
                    'show_nav' => 'yes',
                ),
            )
        );

        $self->start_controls_tab(
            'tab_nav_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $self->add_control(
            'nav_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button' => 'color: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'nav_back_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'nav_type!' => 'simple',
                ),
            )
        );

        $self->add_control(
            'nav_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'nav_type' => 'circle',
                ),
            )
        );
        $self->end_controls_tab();

        $self->start_controls_tab(
            'tab_nav_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $self->add_control(
            'nav_color_hover',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-button:not(.disabled):hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'nav_back_color_hover',
            array(
                'label'     => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-button:not(.disabled):hover' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'nav_type!' => 'simple',
                ),
            )
        );

        $self->add_control(
            'nav_border_color_hover',
            array(
                'label'     => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button:not(.disabled):hover' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'nav_type' => 'circle',
                ),
            )
        );

        $self->end_controls_tab();

        $self->start_controls_tab(
            'tab_nav_disabled',
            array(
                'label'     => esc_html__( 'Disabled', 'alpus-core' ),
                'condition' => array(
                    'nav_type!' => 'full',
                ),
            )
        );

        $self->add_control(
            'nav_color_disabled',
            array(
                'label'     => esc_html__( 'Disabled Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-button.disabled' => 'color: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'nav_back_color_disabled',
            array(
                'label'     => esc_html__( 'Disabled Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-button.disabled' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'nav_type!' => 'simple',
                ),
            )
        );

        $self->add_control(
            'nav_border_color_disabled',
            array(
                'label'     => esc_html__( 'Disabled Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .slider-container .slider-button.disabled' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'nav_type' => 'circle',
                ),
            )
        );

        $self->end_controls_tab();

        $self->end_controls_tabs();

        $self->add_control(
            'style_heading_dot',
            array(
                'label'     => esc_html__( 'Dots', 'alpus-core' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $self->add_control(
            'show_dots',
            array(
                'label'       => esc_html__( 'Show Dots', 'alpus-core' ),
                'description' => esc_html__( 'Determine whether to show/hide slider dots.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
            )
        );

        $dot_default = array(
            'show_dots' => 'yes',
        );

        if ( 'use_as' == $condition_key ) {
            $self->add_control(
                'dots_type',
                array(
                    'label'       => esc_html__( 'Custom Dots Type', 'alpus-core' ),
                    'description' => esc_html__( 'Select one of custom dot types.', 'alpus-core' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'options'     => array(
                        ''      => esc_html__( 'Default', 'alpus-core' ),
                        'thumb' => esc_html__( 'Thumbnail', 'alpus-core' ),
                        'html'  => esc_html__( 'Custom HTML', 'alpus-core' ),
                    ),
                    'condition'   => array(
                        'show_dots' => 'yes',
                        // 'col_cnt'   => 1,
                    ),
                )
            );

            $self->add_control(
                'thumbs',
                array(
                    'label'       => esc_html__( 'Add Thumbnails', 'alpus-core' ),
                    'description' => esc_html__( 'Choose thumbnail images which represent each slides.', 'alpus-core' ),
                    'type'        => Controls_Manager::GALLERY,
                    'default'     => array(),
                    'show_label'  => false,
                    'condition'   => array(
                        'dots_type' => 'thumb',
                        'show_dots' => 'yes',
                    ),
                )
            );
            $repeater = new Repeater();

            $repeater->add_control(
                'dot_html',
                array(
                    'label'       => esc_html__( 'Custom HTML', 'alpus-core' ),
                    'description' => esc_html__( 'Please input custom html for dot.', 'alpus-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array(
                        'active' => true,
                    ),
                )
            );

            $self->add_control(
                'custom_dot_htmls',
                array(
                    'label'       => esc_html__( 'Add Custom HTMLs', 'alpus-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $repeater->get_controls(),
                    'title_field' => '{{dot_html}}',
                    'condition'   => array(
                        'dots_type' => 'html',
                        'show_dots' => 'yes',
                    ),
                )
            );

            $self->add_responsive_control(
                'dots_thumb_spacing',
                array(
                    'label'      => esc_html__( 'Dots Spacing', 'alpus-core' ),
                    'type'       => Controls_Manager::SLIDER,
                    'default'    => array(
                        'unit' => 'px',
                        'size' => '25',
                    ),
                    'size_units' => array(
                        'px',
                        '%',
                    ),
                    'range'      => array(
                        'px' => array(
                            'step' => 1,
                            'min'  => -200,
                            'max'  => 200,
                        ),
                        '%'  => array(
                            'step' => 1,
                            'min'  => -100,
                            'max'  => 100,
                        ),
                    ),
                    'condition'  => array(
                        'dots_type!' => '',
                        'show_dots'  => 'yes',
                    ),
                    'selectors'  => array(
                        '.elementor-element-{{ID}} .slider-thumb-dots .slider-pagination-bullet'       => "margin-{$right}: {{SIZE}}{{UNIT}};",
                        '.elementor-element-{{ID}} .slider-custom-html-dots .slider-pagination-bullet' => "margin-{$right}: {{SIZE}}{{UNIT}};",
                    ),
                )
            );

            $dot_default = array(
                'dots_type' => '',
                'show_dots' => 'yes',
            );
        }

        $self->add_control(
            'dots_skin',
            array(
                'label'       => esc_html__( 'Dots Skin', 'alpus-core' ),
                'description' => esc_html__( 'Controls the dots skin.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => array(
                    ''      => esc_html__( 'Default', 'alpus-core' ),
                    'white' => esc_html__( 'White', 'alpus-core' ),
                    'grey'  => esc_html__( 'Grey', 'alpus-core' ),
                    'dark'  => esc_html__( 'Dark', 'alpus-core' ),
                ),
                'condition'   => $dot_default,
            )
        );

        if ( 'section' == $self->get_name() ) {
            $self->add_control(
                'dots_pos',
                array(
                    'label'       => esc_html__( 'Dots Position', 'alpus-core' ),
                    'description' => esc_html__( 'Choose position of slider dots and image dots.', 'alpus-core' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'inner',
                    'options'     => array(
                        'inner'  => esc_html__( 'Inner', 'alpus-core' ),
                        'custom' => esc_html__( 'Custom', 'alpus-core' ),
                    ),
                    'condition'   => array(
                        'dots_type' => '',
                        'show_dots' => 'yes',
                    ),
                )
            );
        } else {
            $self->add_control(
                'dots_pos',
                array(
                    'label'       => esc_html__( 'Dots Position', 'alpus-core' ),
                    'description' => esc_html__( 'Choose position of slider dots and image dots.', 'alpus-core' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'options'     => array(
                        'inner'  => esc_html__( 'Inner', 'alpus-core' ),
                        ''       => esc_html__( 'Close', 'alpus-core' ),
                        'outer'  => esc_html__( 'Outer', 'alpus-core' ),
                        'custom' => esc_html__( 'Custom', 'alpus-core' ),
                    ),
                    'condition'   => 'column' == $self->get_name() ? array(
                        'dots_type' => '',
                        'show_dots' => 'yes',
                    ) : array(
                        'show_dots' => 'yes',
                    ),
                )
            );
        }

        $self->add_responsive_control(
            'dots_h_position',
            array(
                'label'      => esc_html__( 'Dot Vertical Position', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'unit' => 'px',
                    'size' => '25',
                ),
                'size_units' => array(
                    'px',
                    '%',
                ),
                'range'      => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -200,
                        'max'  => 200,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .slider-pagination' => 'display: flex; position: absolute; bottom: {{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} .slider-thumb-dots' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => 'use_as' == $condition_key ? array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'relation' => 'and',
                            'terms'    => array(
                                array(
                                    'name'     => 'dots_pos',
                                    'operator' => '==',
                                    'value'    => 'custom',
                                ),
                                array(
                                    'name'     => 'show_dots',
                                    'operator' => '==',
                                    'value'    => 'yes',
                                ),
                            ),
                        ),
                        array(
                            'relation' => 'and',
                            'terms'    => array(
                                array(
                                    'name'     => 'dots_type',
                                    'operator' => '!=',
                                    'value'    => '',
                                ),
                                array(
                                    'name'     => 'show_dots',
                                    'operator' => '==',
                                    'value'    => 'yes',
                                ),
                            ),
                        ),
                    ),
                ) : array(
                    'relation' => 'and',
                    'terms'    => array(
                        array(
                            'name'     => 'dots_pos',
                            'operator' => '==',
                            'value'    => 'custom',
                        ),
                        array(
                            'name'     => 'show_dots',
                            'operator' => '==',
                            'value'    => 'yes',
                        ),
                    ),
                ),
            )
        );

        $self->add_responsive_control(
            'dots_v_position',
            array(
                'label'      => esc_html__( 'Dot Horizontal Position', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'unit' => '%',
                    'size' => '50',
                ),
                'size_units' => array(
                    'px',
                    '%',
                ),
                'range'      => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -200,
                        'max'  => 200,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .slider-pagination' => 'display: flex; position: absolute; left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    '.elementor-element-{{ID}} .slider-thumb-dots' => "margin-{$left}: {{SIZE}}{{UNIT}};",
                ),
                'conditions' => 'use_as' == $condition_key ? array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'relation' => 'and',
                            'terms'    => array(
                                array(
                                    'name'     => 'dots_pos',
                                    'operator' => '==',
                                    'value'    => 'custom',
                                ),
                                array(
                                    'name'     => 'show_dots',
                                    'operator' => '==',
                                    'value'    => 'yes',
                                ),
                            ),
                        ),
                        array(
                            'relation' => 'and',
                            'terms'    => array(
                                array(
                                    'name'     => 'dots_type',
                                    'operator' => '!=',
                                    'value'    => '',
                                ),
                                array(
                                    'name'     => 'show_dots',
                                    'operator' => '==',
                                    'value'    => 'yes',
                                ),
                            ),
                        ),
                    ),
                ) : array(
                    'relation' => 'and',
                    'terms'    => array(
                        array(
                            'name'     => 'dots_pos',
                            'operator' => '==',
                            'value'    => 'custom',
                        ),
                        array(
                            'name'     => 'show_dots',
                            'operator' => '==',
                            'value'    => 'yes',
                        ),
                    ),
                ),
            )
        );

        $self->add_responsive_control(
            'slider_dots_size',
            array(
                'type'        => Controls_Manager::SLIDER,
                'label'       => esc_html__( 'Dots Size', 'alpus-core' ),
                'description' => esc_html__( 'Controls the size of slider dots.', 'alpus-core' ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 5,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-pagination .slider-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    '.elementor-element-{{ID}} .slider-thumb-dots .slider-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    '.elementor-element-{{ID}} .slider-pagination ~ .slider-thumb-dots'      => 'margin-top: calc(-{{SIZE}}{{UNIT}} / 2)',
                ),
                'condition'   => 'use_as' == $condition_key ? array(
                    'dots_type!' => 'html',
                    'show_dots'  => 'yes',
                ) : array(
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->add_responsive_control(
            'slider_dots_size2',
            array(
                'type'        => Controls_Manager::DIMENSIONS,
                'label'       => esc_html__( 'Dots Size', 'alpus-core' ),
                'description' => esc_html__( 'Controls the size of slider dots.', 'alpus-core' ),
                'size_units'  => array( 'px', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}} .slider-custom-html-dots .slider-pagination-bullet' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'dots_type' => 'html',
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->start_controls_tabs(
            'dot_style_tabs',
            array(
                'condition' => array(
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->start_controls_tab(
            'dot_normal_style_tab',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $self->add_control(
            'dot_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'dots_type' => 'html',
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->add_control(
            'dot_back_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet' => 'background-color: {{VALUE}};',
                ),
                'condition' => 'use_as' == $condition_key ? array(
                    'dots_type!' => 'thumb',
                    'show_dots'  => 'yes',
                ) : array(
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->add_control(
            'dot_bd_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'dots_type' => 'html',
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->end_controls_tab();

        $self->start_controls_tab(
            'dot_hover_style_tab',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $self->add_control(
            'dot_color_hover',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet:hover'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet.active' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'dots_type' => 'html',
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->add_control(
            'dot_back_color_hover',
            array(
                'label'     => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet:hover'  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet.active' => 'background-color: {{VALUE}};',
                ),
                'condition' => 'use_as' == $condition_key ? array(
                    'dots_type!' => 'thumb',
                    'show_dots'  => 'yes',
                ) : array(
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->add_control(
            'dot_bd_color_hover',
            array(
                'label'     => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet:hover'  => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .slider-pagination .slider-pagination-bullet.active' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'dots_type' => 'html',
                    'show_dots' => 'yes',
                ),
            )
        );

        $self->end_controls_tab();
        $self->end_controls_tabs();

        $self->end_controls_section();
    }
}

/*
 * Elementor content-template for slider.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_slider_template' ) ) {
    function alpus_elementor_slider_template() {
        wp_enqueue_script( 'swiper' );
        ?>
		var breakpoints = <?php echo wp_json_encode( alpus_get_breakpoints() ); ?>;
		var extra_options = {};

		extra_class += ' slider-wrapper';

		// Layout
		if ( 'lg' == settings.col_sp || 'xs' == settings.col_sp || 'sm' == settings.col_sp || 'no' == settings.col_sp ) {
			extra_class += ' gutter-' + settings.col_sp;
		}

		var col_cnt = 'function' == typeof alpus_get_responsive_cols ? alpus_get_responsive_cols({
			xxl: typeof settings.col_cnt_widescreen != 'undefined' ? settings.col_cnt_widescreen : 0, 
			xlg: typeof settings.col_cnt != 'undefined' ? settings.col_cnt : 0,
			xl: typeof settings.col_cnt_laptop != 'undefined' ? settings.col_cnt_laptop : 0,
			lg: typeof settings.col_cnt_tablet_extra != 'undefined' ? settings.col_cnt_tablet_extra : 0,
			md: typeof settings.col_cnt_tablet != 'undefined' ? settings.col_cnt_tablet : 0,
			sm: typeof settings.col_cnt_mobile_extra != 'undefined' ? settings.col_cnt_mobile_extra : 0,
			min: typeof settings.col_cnt_mobile != 'undefined' ? settings.col_cnt_mobile : 0,
		}) : {
			xxl: typeof settings.col_cnt_widescreen != 'undefined' ? settings.col_cnt_widescreen : 0,
			xlg: typeof settings.col_cnt != 'undefined' ? settings.col_cnt : 0,
			xl: typeof settings.col_cnt_laptop != 'undefined' ? settings.col_cnt_laptop : 0,
			lg: typeof settings.col_cnt_tablet_extra != 'undefined' ? settings.col_cnt_tablet_extra : 0,
			md: typeof settings.col_cnt_tablet != 'undefined' ? settings.col_cnt_tablet : 0,
			sm: typeof settings.col_cnt_mobile_extra != 'undefined' ? settings.col_cnt_mobile_extra : 0,
			min: typeof settings.col_cnt_mobile != 'undefined' ? settings.col_cnt_mobile : 0,
		};
		extra_class += ' ' + alpus_get_col_class( col_cnt );

		// Nav & Dot

		var statusClass = '';

		if ( 'full' == settings.nav_type ) {
			statusClass += ' slider-nav-full';
		} else {
			if ( 'circle' == settings.nav_type ) {
				statusClass += ' slider-nav-circle';
			}
			if ( 'top' == settings.nav_pos ) {
				statusClass += ' slider-nav-top';
			} else if ( 'bottom' == settings.nav_pos ) {
				statusClass += ' slider-nav-bottom';
			} else if ( 'inner' != settings.nav_pos ) {
				statusClass += ' slider-nav-outer';
			}
		}
		if ( 'yes' == settings.nav_hide ) {
			statusClass += ' slider-nav-fade';
		}
		if ( settings.dots_skin ) {
			statusClass += ' slider-dots-' + settings.dots_skin;
		}
		if ( 'inner' == settings.dots_pos ) {
			statusClass += ' slider-dots-inner';
		}
		if ( 'outer' == settings.dots_pos ) {
			statusClass += ' slider-dots-outer';
		}
		if ( 'yes' == settings.fullheight ) {
			statusClass += ' slider-full-height';
		}
		if ( 'yes' == settings.box_shadow_slider ) {
			statusClass += ' slider-shadow';
		}

		if ( 'top' == settings.slider_vertical_align ||
			'middle' == settings.slider_vertical_align ||
			'bottom' == settings.slider_vertical_align ||
			'same-height' == settings.slider_vertical_align ) {
			statusClass += ' slider-' + settings.slider_vertical_align;
		}

		extra_options['navigation'] = 'yes' == settings.show_nav;
		extra_options['pagination'] = 'yes' == settings.show_dots;
		if ( 'no' !== settings.col_sp ) {
			if ( 'lg' == settings.col_sp ) {
				extra_options['spaceBetween'] = 30;
			}
			else if ( 'md' == settings.col_sp ) {
				extra_options['spaceBetween'] = 20;
			}
			else if ( 'sm' == settings.col_sp ) {
				extra_options['spaceBetween'] = 10;
			}
			else if ( 'xs' == settings.col_sp ) {
				extra_options['spaceBetween'] = 2;
			}
			else if ('' == settings.col_sp && '' == settings.col_sp_custom.size) {
				extra_options['spaceBetween'] = 15;
			}
			else {
				extra_options['spaceBetween'] = settings.col_sp_custom.size;
			}
		}
		extra_options['spaceBetween'] = elementorFrontend.hooks.applyFilters('alpus_slider_gap', extra_options['spaceBetween'], settings.col_sp);
		if( 'yes' == settings.loop ) {
			extra_options['loop'] = true;
		}
		if ( 'yes' == settings.autoplay ) {
			extra_options['autoplay'] = true;
			extra_options['autoplayHoverPause'] = true;
		}
		if ( 5000 != settings.autoplay_timeout ) {
			extra_options['autoplayTimeout'] = settings.autoplay_timeout;
		}
		if ( 'yes' == settings.autoheight) {
			extra_options['autoHeight'] = true;
		}
		if ( 'yes' == settings.autoheight) {
			extra_options['autoHeight'] = true;
		}
		if( 'yes' == settings.disable_mouse_drag ) {
			extra_options['allowTouchMove'] = false;
		}
		if ( settings.effect ) {
			extra_options['effect'] = settings.effect;
		}
		if ( settings.speed ) {
			extra_options['speed'] = settings.speed;
		}

		if ( settings.dots_type ) {
			extra_options['dotsContainer'] = 'preview';
		}

		var responsive = {};
		for ( var w in col_cnt ) {
			responsive[ breakpoints[ w ] ] = {
				slidesPerView: col_cnt[w]
			}
		}
		extra_options['statusClass'] = statusClass;

		if ( col_cnt.xlg ) {
			extra_options['slidesPerView'] = col_cnt.xlg;
		} else if ( col_cnt.xl ) {
			extra_options['slidesPerView'] = col_cnt.xl;
		} else if ( col_cnt.lg ) {
			extra_options['slidesPerView'] = col_cnt.lg;
		}
		extra_options.breakpoints = responsive;

		extra_attrs += ' data-slider-options="' + JSON.stringify( extra_options ).replaceAll('"', '\'') + '"';
		<?php
    }
}
