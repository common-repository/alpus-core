<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/*
 * Alpus Image Box Widget
 *
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

class Alpus_Image_Box_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_imagebox';
    }

    public function get_title() {
        return esc_html__( 'Image Box', 'alpus-core' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon eicon-image-box';
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'image box', 'imagebox', 'feature', 'member', 'alpus' );
    }

    /**
     * Get the style depends.
     *
     * @since 1.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-image-box', ALPUS_CORE_INC_URI . '/widgets/image-box/image-box' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_CORE_VERSION );

        return array( 'alpus-image-box' );
    }

    public function get_script_depends() {
        return array();
    }

    protected function register_controls() {
        $this->start_controls_section(
            'image_content',
            array(
                'label' => esc_html__( 'Image', 'alpus-core' ),
            )
        );

        $this->add_control(
            'type',
            array(
                'label'       => esc_html__( 'Type', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => array(
                    ''        => esc_html__( 'Default', 'alpus-core' ),
                    'gallery' => esc_html__( 'Gallery', 'alpus-core' ),
                    'card'    => esc_html__( 'Card', 'alpus-core' ),
                    'popup'   => esc_html__( 'Popup', 'alpus-core' ),
                ),
                'qa_selector' => '.image-box',
            )
        );

        $this->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Choose Image', 'alpus-core' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array(
                    'active' => true,
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'      => 'image',
                'default'   => 'full',
                'separator' => 'none',
            )
        );

        $this->add_control(
            'image_box_img_shape',
            array(
                'label'     => esc_html__( 'Shape', 'alpus-core' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '',
                'options'   => array(
                    'circle' => esc_html__( 'Circle', 'alpus-core' ),
                    ''       => esc_html__( 'Square', 'alpus-core' ),
                ),
                'separator' => 'before',
                'condition' => array(
                    'type' => '',
                ),
            )
        );

        $this->add_control(
            'image_position',
            array(
                'label'     => esc_html__( 'Image Position', 'alpus-core' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'top',
                'options'   => array(
                    'left'  => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'top'   => array(
                        'title' => esc_html__( 'Top', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'toggle'    => false,
                'condition' => array(
                    'type' => '',
                ),
            )
        );

        $this->add_control(
            'overlay',
            array(
                'type'    => Controls_Manager::SELECT,
                'label'   => esc_html__( 'Overlay on Hover', 'alpus-core' ),
                'default' => '',
                'options' => array(
                    ''           => esc_html__( 'No', 'alpus-core' ),
                    'light'      => esc_html__( 'Light', 'alpus-core' ),
                    'dark'       => esc_html__( 'Dark', 'alpus-core' ),
                    'zoom'       => esc_html__( 'Zoom', 'alpus-core' ),
                    'zoom_light' => esc_html__( 'Zoom and Light', 'alpus-core' ),
                    'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpus-core' ),
                ),
            )
        );

        $this->add_control(
            'img_style',
            array(
                'type'      => Controls_Manager::SELECT,
                'label'     => esc_html__( 'Image Style', 'alpus-core' ),
                'default'   => '',
                'options'   => array(
                    ''        => esc_html__( 'No', 'alpus-core' ),
                    'style-1' => esc_html__( 'Style 1', 'alpus-core' ),
                    'style-2' => esc_html__( 'Style 2', 'alpus-core' ),
                    'style-3' => esc_html__( 'Style 3', 'alpus-core' ),
                    'style-4' => esc_html__( 'Style 4', 'alpus-core' ),
                ),
                'condition' => array(
                    'type'    => '',
                    'overlay' => '',
                ),
            )
        );

        $this->add_control(
            'image_style_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} figure' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'type'       => '',
                    'img_style!' => '',
                    'overlay'    => '',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_tab',
            array(
                'label' => esc_html__( 'Content', 'alpus-core' ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'   => esc_html__( 'Link Url', 'alpus-core' ),
                'type'    => Controls_Manager::URL,
                'default' => array(
                    'url' => '',
                ),
                'dynamic' => array(
                    'active' => true,
                ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'       => esc_html__( 'Title', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'This is the title', 'alpus-core' ),
                'label_block' => true,
                'dynamic'     => array(
                    'active' => true,
                ),
            )
        );

        $this->add_control(
            'title_html_tag',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'alpus-core' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'  => 'H1',
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                ),
                'default' => 'h3',
            )
        );

        $this->add_control(
            'content',
            array(
                'label'     => esc_html__( 'Description', 'alpus-core' ),
                'type'      => Controls_Manager::TEXTAREA,
                'rows'      => '10',
                'default'   => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'alpus-core' ),
                'condition' => array(
                    'type!' => 'popup',
                ),
                'dynamic'   => array(
                    'active' => true,
                ),
            )
        );

        $this->add_responsive_control(
            'imagebox_align',
            array(
                'label'     => esc_html__( 'Alignment', 'alpus-core' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
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
                'default'   => 'center',
                'selectors' => array(
                    '.elementor-element-{{ID}} .image-box' => 'text-align: {{VALUE}};',
                ),
                'condition' => array(
                    'type!' => 'gallery',
                ),
            )
        );

        $this->add_control(
            'btn_links',
            array(
                'label'     => esc_html__( 'Show Buttons', 'alpus-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'external_link' => esc_html__( 'External Link', 'alpus-core' ),
                    'popup_link'    => esc_html__( 'Image Popup Link', 'alpus-core' ),
                    'all'           => esc_html__( 'Both', 'alpus-core' ),
                ),
                'default'   => 'external_link',
                'multiple'  => true,
                'condition' => array(
                    'type' => array( 'gallery', 'card' ),
                ),
            )
        );

        $this->add_control(
            'gallery_popup_btn_icon',
            array(
                'label'     => esc_html__( 'Popup Link Icon', 'alpus-core' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => array(
                    'value'   => ALPUS_ICON_PREFIX . '-icon-search-plus',
                    'library' => 'alpus-icons',
                ),
                'condition' => array(
                    'type'       => array( 'gallery', 'card' ),
                    'btn_links!' => 'external_link',
                ),
            )
        );

        $this->add_control(
            'gallery_btn_icon',
            array(
                'label'     => esc_html__( 'External Link Icon', 'alpus-core' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => array(
                    'value'   => ALPUS_ICON_PREFIX . '-icon-long-arrow-right',
                    'library' => 'alpus-icons',
                ),
                'condition' => array(
                    'type'       => array( 'gallery', 'card' ),
                    'btn_links!' => 'popup_link',
                ),
            )
        );

        $this->add_responsive_control(
            'gallery_btn_icon_size',
            array(
                'label'     => esc_html__( 'Icon Size (px)', 'alpus-core' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 50,
                    ),
                ),
                'selectors' => array(
                    '.elementor-element-{{ID}} .content-hover .btn:before' => 'font-size: {{SIZE}}px;',
                ),
                'condition' => array(
                    'type' => array( 'gallery', 'card' ),
                ),
            )
        );

        $this->add_control(
            'show_button',
            array(
                'label'     => esc_html__( 'Show Button', 'alpus-core' ),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array(
                    'type!' => array( 'gallery', 'card' ),
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_box_btn_layout',
            array(
                'label'     => esc_html__( 'Button', 'alpus-core' ),
                'condition' => array(
                    'type!'       => array( 'gallery', 'card' ),
                    'show_button' => 'yes',
                ),
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
            'general_style',
            array(
                'label' => esc_html__( 'General', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'img_box_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .image-box' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'box_padding',
            array(
                'label'      => esc_html__( 'Padding', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .image-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .image-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'imagebox_box_shadow',
                'selector'  => '.elementor-element-{{ID}} .image-box',
                'condition' => array(
                    'type!' => 'popup',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            array(
                'label'     => esc_html__( 'Image', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'type' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'image_box_image_size',
            array(
                'label'      => esc_html__( 'Image Size', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'rem', '%' ),
                'range'      => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 300,
                    ),
                    'rem' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 30,
                    ),
                    '%'   => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 100,
                    ),
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} figure' => 'width: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_space',
            array(
                'label'     => esc_html__( 'Spacing', 'alpus-core' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => array(
                    'size' => 15,
                ),
                'range'     => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '.elementor-element-{{ID}} .position-right .image-box-content' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} .position-left .image-box-content'  => 'margin-left: {{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} .position-top .image-box-content'   => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style',
            array(
                'label'     => esc_html__( 'Content', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'type' => 'popup',
                ),
            )
        );

        $this->add_control(
            'content_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .image-box-content' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_offset',
            array(
                'label'      => esc_html__( 'Offset', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'rem', '%' ),
                'range'      => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 100,
                    ),
                    'rem' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 10,
                    ),
                    '%'   => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 100,
                    ),
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .image-box-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_pad',
            array(
                'label'      => esc_html__( 'Padding', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .image-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'content_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .image-box-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style',
            array(
                'label' => esc_html__( 'Title', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .image-box .title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .image-box .title',
            )
        );

        $this->add_responsive_control(
            'title_mg',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .image-box .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'description_style',
            array(
                'label'     => esc_html__( 'Description', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'type!' => 'popup',
                ),
            )
        );

        $this->add_control(
            'description_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .image-box .content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'description_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .image-box .content',
            )
        );

        $this->add_responsive_control(
            'description_mg',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .image-box .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'button_style',
            array(
                'label'     => esc_html__( 'Button', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'type'        => array( '', 'popup' ),
                    'show_button' => 'yes',
                ),
            )
        );

        alpus_elementor_button_style_controls( $this, array( 'show_button', 'yes' ), esc_html__( 'Button', 'alpus-core' ), '', false, false );

        $this->end_controls_section();

        $this->start_controls_section(
            'round_button_style',
            array(
                'label'     => esc_html__( 'Button', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'type' => array( 'gallery', 'card' ),
                ),
            )
        );

        $this->add_responsive_control(
            'round_btn_size',
            array(
                'label'      => esc_html__( 'Button Size', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                    'rem',
                ),
                'range'      => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 100,
                    ),
                    'rem' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 10,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .content-hover .btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'round_btn_margin',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%',
                    'rem',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .content-hover .btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'round_btn_border_width',
            array(
                'label'      => esc_html__( 'Border Width', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%',
                    'rem',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .content-hover .btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
            )
        );

        $this->start_controls_tabs( 'round_tabs_btn_cat' );

        $this->start_controls_tab(
            'round_tab_btn_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_control(
            'round_btn_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'round_btn_back_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'round_btn_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'round_btn_box_shadow',
                'selector' => '.elementor-element-{{ID}} .btn',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'round_tab_btn_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $this->add_control(
            'round_btn_color_hover',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'round_btn_back_color_hover',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'round_btn_border_color_hover',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn:hover' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'round_btn_box_shadow_hover',
                'selector' => '.elementor-element-{{ID}} .btn:hover',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'round_tab_btn_active',
            array(
                'label' => esc_html__( 'Active', 'alpus-core' ),
            )
        );

        $this->add_control(
            'round_btn_color_active',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn:not(:focus):active, .elementor-element-{{ID}} .btn:focus' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'round_btn_back_color_active',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn:not(:focus):active, .elementor-element-{{ID}} .btn:focus' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'round_btn_border_color_active',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .btn:not(:focus):active, .elementor-element-{{ID}} .btn:focus' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'round_btn_box_shadow_active',
                'selector' => '.elementor-element-{{ID}} .btn:active, .elementor-element-{{ID}} .btn:focus',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $atts = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'title' );
        $this->add_inline_editing_attributes( 'content' );
        $this->add_inline_editing_attributes( 'button_label' );

        require ALPUS_CORE_INC . '/widgets/image-box/render-image-box-elementor.php';
    }

    protected function content_template() {
        ?>
		<#
		var wrapper_cls = 'image-box',
			overlay_cls = '';

		var imageHtml = '';

		if ( settings.overlay ) {
			if ( 'light' == settings.overlay ) {
				overlay_cls = ' overlay-light';
			}
			if ( 'dark' == settings.overlay ) {
				overlay_cls = ' overlay-dark';
			}
			if ( 'zoom' == settings.overlay ) {
				overlay_cls = ' overlay-zoom';
			}
			if ( 'zoom_light' == settings.overlay ) {
				overlay_cls = ' overlay-zoom overlay-light';
			}
			if ( 'zoom_dark' == settings.overlay ) {
				overlay_cls = ' overlay-zoom overlay-dark';
			}
		}

		if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			var imageHtml = '<img src="' + image_url + '"/>';

			imageHtml = '<figure>' + imageHtml + '</figure>';
		}

		var linkAttr = 'href="'  + ( settings.link.url ? settings.link.url : '#' ) + '"';
		var linkOpen = settings.link.url ? '<a ' + linkAttr + ( settings.overlay ? ' class="'+ overlay_cls + '"' : ''  ) + '>' : '';
		var linkClose = settings.link.url ? '</a>' : '';

		var titleHtml = '';

		if ( settings.title ) {
			view.addRenderAttribute( 'title', 'class', 'title' );
			view.addInlineEditingAttributes( 'title' );
			var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag );
			titleHtml = '<' + titleHTMLTag + ' ' + view.getRenderAttributeString( 'title' ) + '>' + settings.title + '</' + titleHTMLTag + '>';
		}

		var contentHtml = '';
		if ( settings.content ) {
			view.addRenderAttribute( 'content', 'class', 'content' );
			view.addInlineEditingAttributes( 'content' );
			contentHtml = '<p ' + view.getRenderAttributeString( 'content' ) + '>' + settings.content + '</p>';
		}

		var buttonHtml  = '';

		if ( 'yes' == settings.show_button ) {

			view.addInlineEditingAttributes( 'button_label' );

			<?php
                alpus_elementor_button_template();
        ?>

			var buttonLabel = alpus_widget_button_get_label( settings, view, settings.button_label, 'button_label' );
			var buttonClass    = alpus_widget_button_get_class( settings );
			buttonClass = 'btn ' + buttonClass.join(' ');

			buttonHtml  = '<a class="' + buttonClass +  '" ' + linkAttr + '>' + buttonLabel + '</a>';
		}

		if ( settings.type ) {
			wrapper_cls += ' image-box-' + settings.type;

			if ( 'card' == settings.type ) {
				wrapper_cls += ' image-box-gallery';
			}
		} else {
			wrapper_cls += ' position-' + settings.image_position;

			if ( 'top' != settings.image_position ) {
				wrapper_cls += ' image-box-side';
			}
		}
		if ( ! settings.overlay && settings.img_style && ! settings.type ) {
			wrapper_cls += ' image-' + settings.img_style + ( 'style-1' != settings.img_style ? ' image-style-transform' : '' );
		}
		if ( ! settings.type && settings.image_box_img_shape ) {
			wrapper_cls += ' image-shape-circle';
		}
		if (settings.overlay && !settings.link.url) {
			wrapper_cls += overlay_cls;
		}

		var html = '<div class="' + wrapper_cls +  '">';

		var actionHtml = '';
		if ('external_link' != settings.btn_links && settings.image.url) {
			actionHtml += '<a href="' + settings.image.url + '" class="btn btn-image-popup btn-ellipse ' + ( settings.gallery_popup_btn_icon.value ? settings.gallery_popup_btn_icon.value : 'a-icon-search-plus' ) + '">' + '</a>';
		}
		if ('popup_link' != settings.btn_links) {
			actionHtml += '<a ' + linkAttr + ' class="btn btn-ellipse ' + ( settings.gallery_btn_icon.value ? settings.gallery_btn_icon.value : 'a-icon-long-arrow-right' ) + '">' + '</a>';
		}

		if ( ! settings.type ) {
			html += linkOpen + imageHtml + linkClose;
			html += '<div class="image-box-content">' + titleHtml + contentHtml + buttonHtml + '</div>';
		} else if ( 'gallery' == settings.type ) {
			html += linkOpen + imageHtml + linkClose;
			html += '<div class="image-box-info">' + titleHtml + contentHtml + '</div>';
			html += '<div class="content-hover">' + actionHtml + '</div>';
		} else if ( 'card' == settings.type ) {
			html += linkOpen + imageHtml + linkClose;
			html += '<div class="image-box-info">' + titleHtml + contentHtml + '</div>';
			html += '<div class="image-box-action content-hover">' + actionHtml + '</div>';
		} else {
			html += linkOpen + imageHtml + linkClose;
			html += '<div class="image-box-content">' + titleHtml + buttonHtml + '</div>';
		}

		html += '</div>';

		print( html );
		#>
		<?php
    }
}
