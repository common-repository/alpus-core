<?php
/**
 * Alpus IconBox Widget
 *
 * Alpus Widget to display iconbox.
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
use Elementor\Group_Control_Typography;

class Alpus_Icon_Box_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_iconbox';
    }

    public function get_title() {
        return esc_html__( 'Icon Box', 'alpus-core' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon eicon-icon-box';
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'icon', 'box' );
    }

    /**
     * Get the style depends.
     *
     * @since 1.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-icon-box', ALPUS_CORE_INC_URI . '/widgets/icon-box/icon-box' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_CORE_VERSION );

        return array( 'alpus-icon-box' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'alpus-core' ),
            )
        );

        $this->add_control(
            'selected_icon',
            array(
                'label'       => esc_html__( 'Icon', 'alpus-core' ),
                'type'        => Controls_Manager::ICONS,
                'description' => esc_html__( 'Choose icon or svg from library.', 'alpus-core' ),
                'default'     => array(
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Link Url', 'alpus-core' ),
                'type'        => Controls_Manager::URL,
                'description' => esc_html__( 'Input URL where you will move when iconbox is clicked.', 'alpus-core' ),
                'default'     => array(
                    'url' => '',
                ),
            )
        );

        $this->add_control(
            'title_text',
            array(
                'label'       => esc_html__( 'Title', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'description' => esc_html__( 'Input iconbox title.', 'alpus-core' ),
                'default'     => esc_html__( 'This is the heading', 'alpus-core' ),
                'placeholder' => esc_html__( 'Enter your title', 'alpus-core' ),
                'label_block' => true,
                'dynamic'     => array(
                    'active' => true,
                ),
            )
        );

        $this->add_control(
            'title_html_tag',
            array(
                'label'       => esc_html__( 'Title HTML Tag', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'description' => esc_html__( 'Select the HTML Title tag from H1 to H6 and P tag too.', 'alpus-core' ),
                'options'     => array(
                    'h1'  => 'H1',
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                ),
                'default'     => 'h3',
            )
        );

        $this->add_control(
            'description_text',
            array(
                'label'       => esc_html__( 'Description', 'alpus-core' ),
                'type'        => Controls_Manager::TEXTAREA,
                'description' => esc_html__( 'Input iconbox content.', 'alpus-core' ),
                'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'alpus-core' ),
                'placeholder' => esc_html__( 'Enter your description', 'alpus-core' ),
                'rows'        => 10,
                'dynamic'     => array(
                    'active' => true,
                ),
            )
        );

        $this->add_control(
            'show_button',
            array(
                'label'     => esc_html__( 'Show Button', 'alpus-core' ),
                'type'      => Controls_Manager::SWITCHER,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'button_label',
            array(
                'label'     => esc_html__( 'Label', 'alpus-core' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Read More', 'alpus-core' ),
                'condition' => array(
                    'show_button' => 'yes',
                ),
            )
        );

        alpus_elementor_button_layout_controls( $this, 'show_button', 'yes' );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            array(
                'label' => esc_html__( 'Layout', 'alpus-core' ),
            )
        );

        $this->add_control(
            'icon_position',
            array(
                'label'       => esc_html__( 'Icon Position', 'alpus-core' ),
                'description' => esc_html__( 'Select the icon position', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'top',
                'options'     => array(
                    'top'    => array(
                        'title' => esc_html__( 'Top', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'bottom' => array(
                        'title' => esc_html__( 'Bottom', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'toggle'      => false,
            )
        );

        $this->add_responsive_control(
            'text_align',
            array(
                'label'       => esc_html__( 'Alignment', 'alpus-core' ),
                'description' => esc_html__( 'Select the content\'s alignment.', 'alpus-core' ),
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
                'selectors'   => array(
                    '.elementor-element-{{ID}} .icon-box' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'vertical_alignment',
            array(
                'label'       => esc_html__( 'Vertical Alignment', 'alpus-core' ),
                'description' => esc_html__( 'Select the iconbox vertical alignment.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => array(
                    'flex-start' => esc_html__( 'Top', 'alpus-core' ),
                    'center'     => esc_html__( 'Middle', 'alpus-core' ),
                    'flex-end'   => esc_html__( 'Bottom', 'alpus-core' ),
                ),
                'default'     => 'flex-start',
                'selectors'   => array(
                    '.elementor-element-{{ID}} .icon-box' => 'align-items: {{VALUE}};',
                ),
                'condition'   => array(
                    'icon_position!' => array( 'top', 'bottom' ),
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            array(
                'label' => esc_html__( 'Icon', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'info_box_icon_type',
            array(
                'label'       => esc_html__( 'Icon View', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'description' => esc_html__( 'Select the icon type.', 'alpus-core' ),
                'options'     => array(
                    'default' => esc_html__( 'Default', 'alpus-core' ),
                    'stacked' => esc_html__( 'Stacked', 'alpus-core' ),
                    'framed'  => esc_html__( 'Framed', 'alpus-core' ),
                ),
                'qa_selector' => '.icon-box .icon-box-feature',
            )
        );

        $this->add_control(
            'info_box_icon_shape',
            array(
                'label'       => esc_html__( 'Shape', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'description' => esc_html__( 'Select the icon shape.', 'alpus-core' ),
                'default'     => 'circle',
                'options'     => array(
                    'circle' => esc_html__( 'Circle', 'alpus-core' ),
                    ''       => esc_html__( 'Square', 'alpus-core' ),
                ),
                'condition'   => array(
                    'info_box_icon_type!' => 'default',
                ),
            )
        );

        $this->add_responsive_control(
            'info_space',
            array(
                'label'       => esc_html__( 'Spacing', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'description' => esc_html__( 'Control the space between icon and content.', 'alpus-core' ),
                'default'     => array(
                    'size' => 15,
                ),
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .position-right .icon-box-feature'  => 'margin-left: {{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} .position-left .icon-box-feature'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} .position-top .icon-box-feature'    => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} .position-bottom .icon-box-feature' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'info_size',
            array(
                'label'       => esc_html__( 'Size', 'alpus-core' ),
                'default'     => array(
                    'size' => 150,
                    'unit' => 'px',
                ),
                'description' => esc_html__( 'Control icon box size.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                ),
                'range'       => array(
                    'px'  => array(
                        'min' => 6,
                        'max' => 300,
                    ),
                    'rem' => array(
                        'min' => 6,
                        'max' => 30,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature'      => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} .icon-box-side .icon-box-feature' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'info_box_icon_type!' => 'default',
                ),
            )
        );

        $this->add_responsive_control(
            'info_icon_size',
            array(
                'label'       => esc_html__( 'Icon Size', 'alpus-core' ),
                'description' => esc_html__( 'Control icon size.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                ),
                'range'       => array(
                    'px'  => array(
                        'min' => 6,
                        'max' => 300,
                    ),
                    'rem' => array(
                        'min' => 6,
                        'max' => 30,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .icon-box-feature i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .icon-box-feature svg' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'border_width',
            array(
                'label'       => esc_html__( 'Border Width', 'alpus-core' ),
                'description' => esc_html__( 'Control icon box border width.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'info_box_icon_type' => 'framed',
                ),
            )
        );

        $this->add_control(
            'border_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'description' => esc_html__( 'Control icon box border radius.', 'alpus-core' ),
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'info_box_icon_shape' => '',
                ),
            )
        );
        $this->start_controls_tabs( 'tabs_icon_color' );
        $this->start_controls_tab(
            'tab_icon_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_control(
            'info_box_icon_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'selected_icon[library]!' => 'svg',
                ),
            )
        );

        $this->add_control(
            'info_box_svg_stroke',
            array(
                'label'     => esc_html__( 'Stroke Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature svg' => 'stroke: {{VALUE}};',
                ),
                'condition' => array(
                    'selected_icon[library]' => 'svg',
                ),
            )
        );

        $this->add_control(
            'info_box_svg_fill',
            array(
                'label'     => esc_html__( 'Fill Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature svg' => 'fill: {{VALUE}};',
                ),
                'condition' => array(
                    'selected_icon[library]' => 'svg',
                ),
            )
        );

        $this->add_control(
            'info_box_icon_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'info_box_icon_type' => 'stacked',
                ),
            )
        );

        $this->add_control(
            'info_box_icon_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'info_box_icon_type' => 'framed',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $this->add_control(
            'info_box_icon_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box:hover .icon-box-feature' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'selected_icon[library]!' => 'svg',
                ),
            )
        );

        $this->add_control(
            'info_box_svg_hover_stroke',
            array(
                'label'     => esc_html__( 'Stroke Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box:hover .icon-box-feature svg' => 'stroke: {{VALUE}};',
                ),
                'condition' => array(
                    'selected_icon[library]' => 'svg',
                ),
            )
        );

        $this->add_control(
            'info_box_svg_hover_fill',
            array(
                'label'     => esc_html__( 'Fill Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box:hover .icon-box-feature svg' => 'fill: {{VALUE}};',
                ),
                'condition' => array(
                    'selected_icon[library]' => 'svg',
                ),
            )
        );

        $this->add_control(
            'info_box_icon_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box:hover .icon-box-feature'       => 'background-color: {{VALUE}}',
                    '.elementor-element-{{ID}} .icon-box:hover .icon-box-feature:after' => 'box-shadow: 0 0 0 2px {{VALUE}}',
                ),
                'condition' => array(
                    'info_box_icon_type' => 'stacked',
                ),
            )
        );

        $this->add_control(
            'info_box_icon_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box:hover .icon-box-feature' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'info_box_icon_type' => 'framed',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'info_box_hover',
            array(
                'label'     => esc_html__( 'Animation on Hover', 'alpus-core' ),
                'type'      => Controls_Manager::SELECT,
                'separator' => 'before',
                'default'   => '',
                'options'   => array(
                    ''       => esc_html__( 'None', 'alpus-core' ),
                    'float'  => esc_html__( 'Float', 'alpus-core' ),
                    'rotate' => esc_html__( 'Rotate', 'alpus-core' ),
                    'grow'   => esc_html__( 'Grow', 'alpus-core' ),
                ),
            )
        );

        $this->add_control(
            'info_box_icon_hover',
            array(
                'label'     => esc_html__( 'Enable Overlay on Hover', 'alpus-core' ),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array(
                    'info_box_icon_type!' => 'default',
                ),
            )
        );

        $this->add_control(
            'info_box_icon_hover_overlay_color',
            array(
                'label'     => esc_html__( 'Overlay Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box .icon-box-feature:after' => 'background-color: {{VALUE}}',
                ),
                'condition' => array(
                    'info_box_icon_type!' => 'default',
                    'info_box_icon_hover' => 'yes',
                ),
            )
        );

        $this->add_control(
            'info_box_icon_shadow',
            array(
                'label'     => esc_html__( 'Enable Shadow', 'alpus-core' ),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array(
                    'info_box_icon_type!' => 'default',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            array(
                'label' => esc_html__( 'Title', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'title_space',
            array(
                'label'       => esc_html__( 'Spacing', 'alpus-core' ),
                'description' => esc_html__( 'Control space between title and description.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .icon-box-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box:hover .icon-box-title, .elementor-element-{{ID}} .icon-box:hover .icon-box-title a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .icon-box-title',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_desc',
            array(
                'label' => esc_html__( 'Description', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'desc_space',
            array(
                'label'       => esc_html__( 'Spacing', 'alpus-core' ),
                'description' => esc_html__( 'Control space between description and button.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => 20,
                ),
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .icon-box-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'show_button' => 'yes',
                ),
            )
        );

        $this->add_control(
            'desc_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box-desc, .elementor-element-{{ID}} .icon-box-desc a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'desc_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} .icon-box:hover .icon-box-desc, .elementor-element-{{ID}} .icon-box:hover .icon-box-desc a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_typography',
                'selector' => '.elementor-element-{{ID}} .icon-box-desc',
            )
        );

        $this->end_controls_section();

        alpus_elementor_button_style_controls( $this, array( 'show_button' => 'yes' ), esc_html__( 'Button', 'alpus-core' ), '', false );
    }

    protected function render() {
        $atts = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'title_text' );
        $this->add_inline_editing_attributes( 'description_text' );
        $this->add_inline_editing_attributes( 'button_label' );

        require ALPUS_CORE_INC . '/widgets/icon-box/render-icon-box-elementor.php';
    }

    protected function content_template() {
        ?>

		<#
		let wrapper_cls = ['icon-box'],
			html = '',
			iconHtml = '',
			iconSvg = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' );

		<?php
            alpus_elementor_button_template();
        ?>

		if ( 'top' != settings.icon_position && 'bottom' != settings.icon_position ) {
			wrapper_cls.push( 'icon-box-side' );
		}
		wrapper_cls.push( 'position-' + settings.icon_position );

		wrapper_cls.push( 'icon-' + settings.info_box_icon_type );
		if ( 'default' != settings.info_box_icon_type && 'yes' == settings.info_box_icon_shadow ) {
			wrapper_cls.push( 'icon-box-icon-shadow' );
		}
		if ( settings.info_box_icon_shape ) {
			wrapper_cls.push( 'shape-' + settings.info_box_icon_shape );
		}

		if ( 'default' != settings.info_box_icon_type ) {
			if ( settings.info_box_icon_hover ) {
				wrapper_cls.push( 'hover-overlay' );
				wrapper_cls.push( 'hover-' + settings.info_box_icon_type );
			}
		}
		if ( settings.info_box_hover ) {
			wrapper_cls.push( settings.info_box_hover );
		}

		var linkAttr = 'href="'  + ( settings.link.url ? settings.link.url : '#' ) + '"';
		var linkOpen = settings.link.url ? '<a class="link" ' + linkAttr + '>' : '';
		var linkClose = settings.link.url ? '</a>' : '';

		html += '<div class="' + wrapper_cls.join( ' ' ) + '">';

			if ( settings.link.url ) {
				html += linkOpen + linkClose;
			}

			iconHtml = '<div class="icon-box-feature">';

			if ( iconSvg && iconSvg.rendered ) {
				iconHtml += iconSvg.value;
			} else {
				iconHtml += linkOpen + '<i class="' + settings.selected_icon.value + '"></i>' + linkClose;
			}

			iconHtml += '</div>';

			if ( 'bottom' != settings.icon_position ) {
				html += iconHtml;
			}

			html += '<div class="icon-box-content">';

		if ( settings.title_text ) {
			view.addRenderAttribute( 'title_text', 'class', 'icon-box-title' );
			view.addInlineEditingAttributes( 'title_text' );
			var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag );
			html += linkOpen + '<' + titleHTMLTag + ' ' + view.getRenderAttributeString( 'title_text' ) + '>' + settings.title_text + '</' + titleHTMLTag + '>' + linkClose;
		}
		if ( settings.description_text ) {
			view.addRenderAttribute( 'description_text', 'class', 'icon-box-desc' );
			view.addInlineEditingAttributes( 'description_text' );
			html += '<p ' + view.getRenderAttributeString( 'description_text' ) + '>' + settings.description_text + '</p>';
		}

		if ( 'yes' == settings.show_button ) {

			var linkAttr = 'href="'  + ( settings.link.url ? settings.link.url : '#' ) + '"';

			view.addInlineEditingAttributes( 'button_label' );

			<?php
                alpus_elementor_button_template();
        ?>

			var buttonLabel = alpus_widget_button_get_label( settings, view, settings.button_label, 'button_label' );
			var buttonClass    = alpus_widget_button_get_class( settings );
			buttonClass = 'btn ' + buttonClass.join(' ');

			html  += '<a class="' + buttonClass +  '" ' + linkAttr + '>' + buttonLabel + '</a>';
		}

		html += '</div>';
		if ( 'bottom' == settings.icon_position ) {
			html += iconHtml;
		}
		html += '</div>';
		print( html );
		#>
		<?php
    }
}
