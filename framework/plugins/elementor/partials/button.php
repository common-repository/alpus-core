<?php
/**
 * Button Partial
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/*
 * Register elementor layout controls for button.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_button_layout_controls' ) ) {
    function alpus_elementor_button_layout_controls( $self, $condition_key = '', $condition_value = 'yes', $name_prefix = '', $repeater = false ) {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        $self->add_control(
            $name_prefix . 'button_type',
            array(
                'label'       => esc_html__( 'Type', 'alpus-core' ),
                'description' => esc_html__( 'Choose button type. Choose from Default, Solid, Outline, Link and gradient.', 'alpus-core' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => array(
                    ''             => esc_html__( 'Default', 'alpus-core' ),
                    'btn-gradient' => esc_html__( 'Gradient', 'alpus-core' ),
                    'btn-outline'  => esc_html__( 'Outline1', 'alpus-core' ),
                    'btn-outline2' => esc_html__( 'Outline2', 'alpus-core' ),
                    'btn-link'     => esc_html__( 'Link', 'alpus-core' ),
                ),
                'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
            )
        );

        $self->add_control(
            $name_prefix . 'button_skin',
            array(
                'label'       => esc_html__( 'Skin', 'alpus-core' ),
                'description' => esc_html__( 'Choose color skin of buttons. Choose from Default, Primary, Secondary, Alert, Success, Dark, White.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'btn-primary',
                'options'     => array(
                    ''              => esc_html__( 'Default', 'alpus-core' ),
                    'btn-primary'   => esc_html__( 'Primary', 'alpus-core' ),
                    'btn-secondary' => esc_html__( 'Secondary', 'alpus-core' ),
                    'btn-warning'   => esc_html__( 'Warning', 'alpus-core' ),
                    'btn-danger'    => esc_html__( 'Danger', 'alpus-core' ),
                    'btn-success'   => esc_html__( 'Success', 'alpus-core' ),
                    'btn-dark'      => esc_html__( 'Dark', 'alpus-core' ),
                    'btn-white'     => esc_html__( 'White', 'alpus-core' ),
                ),
                'condition'   => $condition_key ? array(
                    $condition_key                => $condition_value,
                    $name_prefix . 'button_type!' => 'btn-gradient',
                ) : array(
                    $name_prefix . 'button_type!' => 'btn-gradient',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'button_gradient_skin',
            array(
                'label'       => esc_html__( 'Skin', 'alpus-core' ),
                'description' => esc_html__( 'Choose gradient color skin of gradient buttons.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'btn-gra-default',
                'options'     => array(
                    'btn-gra-default' => esc_html__( 'Default', 'alpus-core' ),
                    'btn-gra-blue'    => esc_html__( 'Blue', 'alpus-core' ),
                    'btn-gra-orange'  => esc_html__( 'Orange', 'alpus-core' ),
                    'btn-gra-pink'    => esc_html__( 'Pink', 'alpus-core' ),
                    'btn-gra-green'   => esc_html__( 'Green', 'alpus-core' ),
                    'btn-gra-dark'    => esc_html__( 'Dark', 'alpus-core' ),
                ),
                'condition'   => $condition_key ? array(
                    $condition_key               => $condition_value,
                    $name_prefix . 'button_type' => 'btn-gradient',
                ) : array(
                    $name_prefix . 'button_type' => 'btn-gradient',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'text_hover_effect',
            array(
                'label'       => esc_html__( 'Text Hover Effect', 'alpus-core' ),
                'description' => esc_html__( 'Choose hover effect of button text. Choose from 5 hover effects.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => array(
                    ''                      => __( 'No Effect', 'alpus-core' ),
                    'btn-text-switch-left'  => __( 'Switch Left', 'alpus-core' ),
                    // 'btn-text-switch-up'    => __( 'Switch Up', 'alpus-core' ),
                    'btn-text-marquee-left' => __( 'Marquee Left', 'alpus-core' ),
                    // 'btn-text-marquee-up'   => __( 'Marquee Up', 'alpus-core' ),
                    // 'btn-text-marquee-down' => __( 'Marquee Down', 'alpus-core' ),
                ),
                'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
            )
        );

        $self->add_control(
            $name_prefix . 'button_size',
            array(
                'label'       => esc_html__( 'Size', 'alpus-core' ),
                'description' => esc_html__( 'Choose button size. Choose from Small, Medium, Normal, Large.', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'btn-sm' => array(
                        'title' => esc_html__( 'Small', 'alpus-core' ),
                        'icon'  => 'alpus-size-sm alpus-choose-type',
                    ),
                    'btn-md' => array(
                        'title' => esc_html__( 'Medium', 'alpus-core' ),
                        'icon'  => 'alpus-size-md alpus-choose-type',
                    ),
                    'btn-lg' => array(
                        'title' => esc_html__( 'Large', 'alpus-core' ),
                        'icon'  => 'alpus-size-lg alpus-choose-type',
                    ),
                    'btn-xl' => array(
                        'title' => esc_html__( 'Extra Large', 'alpus-core' ),
                        'icon'  => 'alpus-size-xl alpus-choose-type',
                    ),
                ),
                'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
            )
        );

        $self->add_control(
            $name_prefix . 'link_hover_type',
            array(
                'label'       => esc_html__( 'Underline Type', 'alpus-core' ),
                'description' => esc_html__( 'Choose hover underline effect of Link type buttons. Choose from 3 underline effects.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => array(
                    ''                 => esc_html__( 'None', 'alpus-core' ),
                    'btn-underline sm' => esc_html__( 'Underline1', 'alpus-core' ),
                    'btn-underline '   => esc_html__( 'Underline2', 'alpus-core' ),
                    'btn-underline lg' => esc_html__( 'Underline3', 'alpus-core' ),
                ),
                'condition'   => $condition_key ? array(
                    $condition_key               => $condition_value,
                    $name_prefix . 'button_type' => 'btn-link',
                ) : array(
                    $name_prefix . 'button_type' => 'btn-link',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'show_underline',
            array(
                'label'       => esc_html__( 'Always Show Underline', 'alpus-core' ),
                'description' => esc_html__( 'Allow to show underline always on the button.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'condition'   => $condition_key ? array(
                    $condition_key                    => $condition_value,
                    $name_prefix . 'button_type'      => 'btn-link',
                    $name_prefix . 'link_hover_type!' => '',
                ) : array(
                    $name_prefix . 'button_type'      => 'btn-link',
                    $name_prefix . 'link_hover_type!' => '',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'shadow',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => esc_html__( 'Box Shadow', 'alpus-core' ),
                'description' => esc_html__( 'Choose box shadow effect for button. Choose from 3 shadow effects.', 'alpus-core' ),
                'label_block' => true,
                'default'     => '',
                'options'     => array(
                    ''              => esc_html__( 'None', 'alpus-core' ),
                    'btn-shadow-sm' => esc_html__( 'Shadow 1', 'alpus-core' ),
                    'btn-shadow'    => esc_html__( 'Shadow 2', 'alpus-core' ),
                    'btn-shadow-lg' => esc_html__( 'Shadow 3', 'alpus-core' ),
                ),
                'condition'   => $condition_key ? array(
                    $condition_key                => $condition_value,
                    $name_prefix . 'button_type!' => array( 'btn-link', 'btn-outline', 'btn-outline2' ),
                ) : array(
                    $name_prefix . 'button_type!' => array( 'btn-link', 'btn-outline', 'btn-outline2' ),
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'button_border',
            array(
                'label'       => esc_html__( 'Border Style', 'alpus-core' ),
                'description' => esc_html__( 'Choose border style of Default, Solid and Outline buttons. Choose from Default, Square, Rounded, Ellipse.', 'alpus-core' ),
                'label_block' => true,
                'type'        => Controls_Manager::CHOOSE,
                'default'     => '',
                'options'     => array(
                    ''            => array(
                        'title' => esc_html__( 'Square', 'alpus-core' ),
                        'icon'  => 'alpus-diagram alpus-diagram-square',
                    ),
                    'btn-rounded' => array(
                        'title' => esc_html__( 'Rounded', 'alpus-core' ),
                        'icon'  => 'alpus-diagram alpus-diagram-rounded',
                    ),
                    'btn-ellipse' => array(
                        'title' => esc_html__( 'Ellipse', 'alpus-core' ),
                        'icon'  => 'alpus-diagram alpus-diagram-ellipse',
                    ),
                    'btn-circle'  => array(
                        'title' => esc_html__( '50%', 'alpus-core' ),
                        'icon'  => 'alpus-choose-type alpus-diagram-circle',
                    ),
                ),
                'condition'   => $condition_key ? array(
                    $condition_key                => $condition_value,
                    $name_prefix . 'button_type!' => array( 'btn-link', 'btn-outline2' ),
                ) : array(
                    $name_prefix . 'button_type!' => array( 'btn-link', 'btn-outline2' ),
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'show_icon',
            array(
                'label'       => esc_html__( 'Show Icon?', 'alpus-core' ),
                'description' => esc_html__( 'Allow to show icon before or after button text.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
            )
        );

        $self->add_control(
            $name_prefix . 'icon',
            array(
                'label'       => esc_html__( 'Icon', 'alpus-core' ),
                'description' => esc_html__( 'Choose icon from icon library that will be shown with button text.', 'alpus-core' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => array(
                    'value'   => ALPUS_ICON_PREFIX . '-icon-long-arrow-right',
                    'library' => 'alpus-icons',
                ),
                'condition'   => $condition_key ? array(
                    $condition_key             => $condition_value,
                    $name_prefix . 'show_icon' => 'yes',
                ) : array(
                    $name_prefix . 'show_icon' => 'yes',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'icon_pos',
            array(
                'label'       => esc_html__( 'Icon Position', 'alpus-core' ),
                'description' => esc_html__( 'Choose where to show icon with button text. Choose from Before/After.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'after',
                'options'     => array(
                    'after'  => esc_html__( 'After', 'alpus-core' ),
                    'before' => esc_html__( 'Before', 'alpus-core' ),
                ),
                'condition'   => $condition_key ? array(
                    $condition_key             => $condition_value,
                    $name_prefix . 'show_icon' => 'yes',
                ) : array(
                    $name_prefix . 'show_icon' => 'yes',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'icon_hover_effect',
            array(
                'label'       => esc_html__( 'Icon Hover Effect', 'alpus-core' ),
                'description' => esc_html__( 'Choose hover effect of buttons with icon. Choose from 3 hover effects.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => array(
                    ''                => esc_html__( 'none', 'alpus-core' ),
                    'btn-slide-left'  => esc_html__( 'Slide Left', 'alpus-core' ),
                    'btn-slide-right' => esc_html__( 'Slide Right', 'alpus-core' ),
                    'btn-slide-up'    => esc_html__( 'Slide Up', 'alpus-core' ),
                    'btn-slide-down'  => esc_html__( 'Slide Down', 'alpus-core' ),
                    'btn-reveal'      => esc_html__( 'Reveal', 'alpus-core' ),
                ),
                'condition'   => $condition_key ? array(
                    $condition_key             => $condition_value,
                    $name_prefix . 'show_icon' => 'yes',
                ) : array(
                    $name_prefix . 'show_icon' => 'yes',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'icon_hover_effect_infinite',
            array(
                'label'       => esc_html__( 'Animation Infinite', 'alpus-core' ),
                'description' => esc_html__( 'Determines whether icons should be animated once or several times for buttons with icon.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'condition'   => $condition_key ? array(
                    $condition_key                      => $condition_value,
                    $name_prefix . 'show_icon'          => 'yes',
                    $name_prefix . 'icon_hover_effect!' => array( '', 'btn-reveal', 'btn-reveal' ),
                ) : array(
                    $name_prefix . 'show_icon'          => 'yes',
                    $name_prefix . 'icon_hover_effect!' => array( '', 'btn-reveal', 'btn-reveal' ),
                ),
            )
        );

        if ( ALPUS_NAME . '_widget_button' == $self->get_name() ) {
            $self->add_control(
                $name_prefix . 'line_break',
                array(
                    'label'       => esc_html__( 'Text Overflow', 'alpus-core' ),
                    'description' => esc_html__( 'Prevents the button text from placing in several rows.', 'alpus-core' ),
                    'type'        => Controls_Manager::CHOOSE,
                    'default'     => 'nowrap',
                    'options'     => array(
                        'nowrap' => array(
                            'title' => esc_html__( 'No Wrap', 'alpus-core' ),
                            'icon'  => 'eicon-flex eicon-nowrap',
                        ),
                        'normal' => array(
                            'title' => esc_html__( 'Wrap', 'alpus-core' ),
                            'icon'  => 'eicon-flex eicon-wrap',
                        ),
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ' span' => 'white-space: {{VALUE}};',
                    ),
                    'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
                )
            );

            $self->add_control(
                $name_prefix . 'btn_class',
                array(
                    'label'       => esc_html__( 'Custom Class', 'alpus-core' ),
                    'description' => esc_html__( 'Input custom classes without dot to give specific styles.', 'alpus-core' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::TEXT,
                    'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
                )
            );
        }
    }
}

/*
 * Register elementor style controls for button.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_button_style_controls' ) ) {
    function alpus_elementor_button_style_controls( $self, $name_prefix = '', $repeater = false, $tab = 'style' ) {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';
        $self->start_controls_section(
            $name_prefix . 'section_button_style',
            array(
                'label' => esc_html__( 'Button', 'alpus-core' ),
                'tab'   => 'style' == $tab ? Controls_Manager::TAB_STYLE : Controls_Manager::TAB_CONTENT,
            )
        );

        $self->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => $name_prefix . 'button_typography',
                'label'    => esc_html__( 'Text Typography', 'alpus-core' ),
                'selector' => '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn',
            )
        );

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
                ),
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

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
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
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

        $self->start_controls_tabs( $name_prefix . 'tabs_btn_cat' );

        $self->start_controls_tab(
            $name_prefix . 'tab_btn_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn' => 'color: {{VALUE}};fill: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_back_color',
            array(
                'label'       => esc_html__( 'Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the background color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn' => 'background-color: {{VALUE}};',
                ),
                'condition'   => array(
                    $name_prefix . 'button_type!' => array( 'btn-link', 'btn-gradient' ),
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_border_color',
            array(
                'label'       => esc_html__( 'Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the border color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn'                                                                                                          => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:before, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:after' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $self->end_controls_tab();

        $self->start_controls_tab(
            $name_prefix . 'tab_btn_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_color_hover',
            array(
                'label'       => esc_html__( 'Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_back_color_hover',
            array(
                'label'       => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover background color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn:hover' => 'background-color: {{VALUE}};',
                ),
                'condition'   => array(
                    $name_prefix . 'button_type!' => array( 'btn-link', 'btn-gradient' ),
                ),
            )
        );

        $self->add_control(
            $name_prefix . 'btn_border_color_hover',
            array(
                'label'       => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover border color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn:hover'                                                                                                                => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:hover:before, {{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn.btn-outline2:hover:after' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $self->end_controls_tab();

        $self->end_controls_tabs();

        $self->end_controls_section();

        $self->start_controls_section(
            $name_prefix . 'section_button_icon_style',
            array(
                'label'     => esc_html__( 'Button Icon Style', 'alpus-core' ),
                'tab'       => 'style' == $tab ? Controls_Manager::TAB_STYLE : Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    $name_prefix . 'show_icon' => 'yes',
                ),
            )
        );

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
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn-icon-left:not(.btn-reveal-left)' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ' i'                                                                                                                                                                                                                                                                    => "margin-{$right}: {{SIZE}}px; margin-{$left}: 0;",
                    '{{WRAPPER}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .btn-icon-right:not(.btn-reveal-right)' . ( $name_prefix ? '.btn-' . $name_prefix : '' ) . ' i'                                                                                                                                                                                                                                                                  => "margin-{$left}: {{SIZE}}px;",
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

        $self->end_controls_section();
    }
}

if ( ! function_exists( 'alpus_elementor_button_template' ) ) {
    function alpus_elementor_button_template() {
        ?>
	function alpus_widget_button_get_class(settings, prefix = '') {
		var ret = [];
		if ( prefix ) {
			ret.push( 'btn-' + prefix );
		}
		if ( settings[ prefix + 'button_type' ] ) {
			ret.push( settings[ prefix + 'button_type' ] );
		}
		if ( settings[ prefix + 'link_hover_type' ] ) {
			ret.push( settings[ prefix + 'link_hover_type' ] );

			if ( 'yes' == settings[ prefix + 'show_underline' ] ) {
				ret.push( 'btn-underline-show' );
			}
		}
		if ( settings[ prefix + 'text_hover_effect' ] ) {
			ret.push( 'btn-text-hover-effect ' + settings[ prefix + 'text_hover_effect' ] );
		}
		if ( settings[ prefix + 'button_size' ] ) {
			ret.push( settings[ prefix + 'button_size' ] );
		}
		if ( settings[ prefix + 'shadow' ] ) {
			ret.push( settings[ prefix + 'shadow' ] );
		}
		if ( settings[ prefix + 'button_border' ] ) {
			ret.push( settings[ prefix + 'button_border' ] );
		}
		if ( ( ! settings[ prefix + 'button_type' ] || 'btn-gradient' != settings[ prefix + 'button_type' ] ) && settings[ prefix + 'button_skin' ]  ) {
			ret.push( settings[ prefix + 'button_skin' ] );
		}
		if ( settings[ prefix + 'button_type' ] && 'btn-gradient' == settings[ prefix + 'button_type' ] && settings[ prefix + 'button_gradient_skin' ] ) {
			ret.push( settings[ prefix + 'button_gradient_skin' ] );
		}
		if ( settings[ prefix + 'btn_class' ] ) {
			ret.push( settings[ prefix + 'btn_class' ] );
		}
		if ( 'yes' == settings[ prefix + 'icon_hover_effect_infinite' ] ) {
			ret.push( 'btn-infinite' );
		}

		if ( 'yes' == settings[ prefix + 'show_icon' ] && settings[ prefix + 'icon' ] && settings[ prefix + 'icon' ]['value'] ) {
			if ( 'before' == settings[ prefix + 'icon_pos' ] ) {
				ret.push( 'btn-icon-left' );
				if( settings[ prefix + 'icon_hover_effect' ] && settings[ prefix + 'icon_hover_effect' ] == 'btn-reveal' ) {
					ret.push( 'btn-reveal-left' );
				}
			} else {
				ret.push( 'btn-icon-right' );
				if( settings[ prefix + 'icon_hover_effect' ] && settings[ prefix + 'icon_hover_effect' ] == 'btn-reveal' ) {
					ret.push( 'btn-reveal-right' );
				}
			}
			if ( settings[ prefix + 'icon_hover_effect' ] && settings[ prefix + 'icon_hover_effect' ] != 'btn-reveal' ) {
				ret.push( settings[ prefix + 'icon_hover_effect' ] );
			}
		}
		return ret;
	}

	function alpus_widget_button_get_label( settings, self, label, inline_key = '', prefix = '' ) {
		label = '<span' +  ( inline_key ? ( ' ' + self.getRenderAttributeString( inline_key ) ) : '' ) + ( settings[ prefix + 'text_hover_effect' ] ? ( ' data-text="' + label + '"' ) : '' ) + '>' + label + '</span>';
		if (  'yes' == settings[ prefix + 'show_icon' ] && settings[ prefix + 'icon' ] && settings[ prefix + 'icon' ]['value'] ) {
			if( settings[ prefix + 'icon' ]['library'] && 'svg' == settings[ prefix + 'icon' ]['library'] ) {
				var svgHtml = elementor.helpers.renderIcon( view, settings[ prefix + 'icon' ], { 'aria-hidden': true } );
			}
			if ( 'before' == settings[ prefix + 'icon_pos' ] ) {
				if ( typeof svgHtml != 'undefined' ) {
					label = svgHtml.value + label;
				} else {
					label = '<i class="' + settings[ prefix + 'icon' ]['value'] + '"></i>' + label;
				}
			} else {
				if ( typeof svgHtml != 'undefined' ) {
					label += svgHtml.value;
				} else {
					label += '<i class="' + settings[ prefix + 'icon' ]['value'] + '"></i>';
				}
			}
		}
		return label;
	}
		<?php
    }
}
