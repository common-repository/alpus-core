<?php

defined( 'ABSPATH' ) || die;

/*
 * Alpus Image Gallery Widget
 *
 * Alpus Widget to display image.
 *
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

class Alpus_Image_Gallery_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_imagegallery';
    }

    public function get_title() {
        return esc_html__( 'Image Gallery', 'alpus-core' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon eicon-slider-push';
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'imagegallery', 'slider', 'carousel', 'grid', 'lightbox' );
    }

    /**
     * Get style depends
     *
     * @since 1.2.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-image-gallery', alpus_core_framework_uri( '/widgets/image-gallery/image-gallery' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

        return array( 'alpus-lightbox', 'alpus-image-gallery' );
    }

    public function get_script_depends() {
        wp_register_script( 'alpus-image-gallery', alpus_core_framework_uri( '/widgets/image-gallery/image-gallery' . ALPUS_JS_SUFFIX ), array( 'jquery-core' ), ALPUS_CORE_VERSION, true );

        return array( 'swiper', 'alpus-lightbox', 'alpus-image-gallery' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_image_carousel',
            array(
                'label' => esc_html__( 'Images', 'alpus-core' ),
            )
        );

        $this->add_control(
            'images',
            array(
                'label'       => esc_html__( 'Add Images', 'alpus-core' ),
                'type'        => Controls_Manager::GALLERY,
                'default'     => array(),
                'show_label'  => false,
                'description' => esc_html__( 'Insert images from the library', 'alpus-core' ),
                'dynamic'     => array(
                    'active' => true,
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'        => 'gallery_image',
                'separator'   => 'none',
                'default'     => 'full',
                'description' => esc_html__( 'Choose proper image size', 'alpus-core' ),
                'exclude'     => [ 'custom' ],
            )
        );

        $this->add_control(
            'image_popup',
            array(
                'label'       => esc_html__( 'Enable Popup', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Allow you to use image popup.', 'alpus-core' ),
                'default'     => 'yes',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional_options',
            array(
                'label' => esc_html__( 'Layout', 'alpus-core' ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'       => esc_html__( 'Layout', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'grid',
                'toggle'      => false,
                'description' => esc_html__( 'Set gallery layout', 'alpus-core' ),
                'options'     => array(
                    'grid'     => array(
                        'title' => esc_html__( 'Grid', 'alpus-core' ),
                        'icon'  => 'eicon-column',
                    ),
                    'slider'   => array(
                        'title' => esc_html__( 'Slider', 'alpus-core' ),
                        'icon'  => 'eicon-slider-3d',
                    ),
                    'creative' => array(
                        'title' => esc_html__( 'Creative Grid', 'alpus-core' ),
                        'icon'  => 'eicon-inner-section',
                    ),
                ),
            )
        );

        alpus_elementor_grid_layout_controls( $this, 'layout_type', true, 'has_rows' );

        $this->add_control(
            'slider_image_expand',
            array(
                'label'       => esc_html__( 'Image Full Width', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Box would be filled with image', 'alpus-core' ),
                'condition'   => array(
                    'layout_type' => 'slider',
                ),
            )
        );

        $this->add_responsive_control(
            'slider_horizontal_align',
            array(
                'label'       => esc_html__( 'Horizontal Align', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'description' => esc_html__( 'Control the horizontal align of gallery', 'alpus-core' ),
                'options'     => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .slider-slide figure' => 'justify-content:{{VALUE}}',
                ),
                'condition'   => array(
                    'slider_image_expand' => '',
                    'layout_type'         => 'slider',
                ),
            )
        );

        $this->add_control(
            'grid_vertical_align',
            array(
                'label'       => esc_html__( 'Vertical Align', 'alpus-core' ),
                'description' => esc_html__( 'Control the vertical align of gallery', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Middle', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Bottom', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                    'stretch'    => array(
                        'title' => esc_html__( 'Stretch', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-stretch',
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} li' => 'display: flex; align-items:{{VALUE}};',
                ),
                'condition'   => array(
                    'layout_type' => 'grid',
                ),
            )
        );

        $this->add_control(
            'grid_image_expand',
            array(
                'label'       => esc_html__( 'Image Full Width', 'alpus-core' ),
                'description' => esc_html__( 'Box would be filled with image', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-gallery .image-gallery-item, .elementor-element-{{ID}} .image-wrap img' => 'width: 100%;',
                ),
                'condition'   => array(
                    'layout_type' => 'grid',
                ),
            )
        );

        $this->add_responsive_control(
            'grid_horizontal_align',
            array(
                'label'       => esc_html__( 'Horizontal Align', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'description' => esc_html__( 'Control the horizontal align of gallery', 'alpus-core' ),
                'options'     => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} li' => 'display: flex; justify-content:{{VALUE}}',
                ),
                'condition'   => array(
                    'grid_image_expand' => '',
                    'layout_type'       => 'grid',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_overlay',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $this->add_control(
            'overlay',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => esc_html__( 'Hover Effect', 'alpus-core' ),
                'description' => esc_html__( 'Choose image overlay effect on hover.', 'alpus-core' ),
                'options'     => array(
                    ''           => esc_html__( 'No', 'alpus-core' ),
                    'light'      => esc_html__( 'Light', 'alpus-core' ),
                    'dark'       => esc_html__( 'Dark', 'alpus-core' ),
                    'zoom'       => esc_html__( 'Zoom', 'alpus-core' ),
                    'zoom_light' => esc_html__( 'Zoom and Light', 'alpus-core' ),
                    'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpus-core' ),
                    'effect-1'   => esc_html__( 'Effect 1', 'alpus-core' ),
                    'effect-2'   => esc_html__( 'Effect 2', 'alpus-core' ),
                    'effect-3'   => esc_html__( 'Effect 3', 'alpus-core' ),
                    'effect-4'   => esc_html__( 'Effect 4', 'alpus-core' ),
                    'effect-5'   => esc_html__( 'Effect 5', 'alpus-core' ),
                    'effect-6'   => esc_html__( 'Effect 6', 'alpus-core' ),
                    'effect-7'   => esc_html__( 'Effect 7', 'alpus-core' ),
                ),
                'qa_selector' => '.image-gallery .image-gallery-item',
            )
        );

        $this->add_control(
            'overlay_color',
            array(
                'label'       => esc_html__( 'Hover Effect Color', 'alpus-core' ),
                'description' => esc_html__( 'Choose image overlay color on hover.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-gallery figure:after, .elementor-element-{{ID}} .overlay-effect:after, .elementor-element-{{ID}} .overlay-effect:before' => 'background-color: {{VALUE}};',
                ),
                'condition'   => array(
                    'overlay!' => array( '', 'zoom', 'effect-5', 'effect-6', 'effect-7' ),
                ),
            )
        );

        $this->add_control(
            'overlay_color1',
            array(
                'label'       => esc_html__( 'Hover Effect Color', 'alpus-core' ),
                'description' => esc_html__( 'Choose image overlay color on hover.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .overlay-effect-5:before, .elementor-element-{{ID}} .overlay-effect-7:before, .elementor-element-{{ID}} .overlay-effect-6+figure:before' => 'background-color: {{VALUE}};',
                ),
                'condition'   => array(
                    'overlay' => array( 'effect-5', 'effect-6', 'effect-7' ),
                ),
            )
        );

        $this->add_control(
            'overlay_border_color',
            array(
                'label'       => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Choose overlay border color on hover.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .overlay-effect-5:after, .elementor-element-{{ID}} .overlay-effect-6:after, .elementor-element-{{ID}} .overlay-effect-6:before, .elementor-element-{{ID}} .overlay-effect-7:after' => 'border-color: {{VALUE}};',
                ),
                'condition'   => array(
                    'overlay' => array( 'effect-5', 'effect-6', 'effect-7' ),
                ),
            )
        );

        $this->add_control(
            'caption_type',
            array(
                'label'       => esc_html__( 'Content', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'description' => esc_html__( 'Choose content you want to show on hover.', 'alpus-core' ),
                'options'     => array(
                    ''            => esc_html__( 'None', 'alpus-core' ),
                    'icon'        => esc_html__( 'Icon', 'alpus-core' ),
                    'title'       => esc_html__( 'Title', 'alpus-core' ),
                    'caption'     => esc_html__( 'Caption', 'alpus-core' ),
                    'description' => esc_html__( 'Description', 'alpus-core' ),
                ),
            )
        );

        $this->add_control(
            'gallery_icon',
            array(
                'label'                  => esc_html__( 'Choose Icon', 'alpus-core' ),
                'type'                   => Controls_Manager::ICONS,
                'default'                => array(
                    'value'   => ALPUS_ICON_PREFIX . '-icon-plus',
                    'library' => 'alpus-icons',
                ),
                'skin'                   => 'inline',
                'exclude_inline_options' => array( 'svg' ),
                'label_block'            => false,
                'condition'              => array(
                    'caption_type' => 'icon',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gallery_style',
            array(
                'label' => esc_html__( 'Image', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'gallery_image_border',
            array(
                'label'       => esc_html__( 'Border Radius', 'alpus-core' ),
                'description' => esc_html__( 'Control the border radius of each image', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-gallery img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gallery_caption_style',
            array(
                'label' => esc_html__( 'Hover Content', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'        => 'caption_typo',
                'description' => esc_html__( 'Controls the typography of image labels.', 'alpus-core' ),
                'selector'    => '.elementor-element-{{ID}} figcaption',
            )
        );

        $this->add_control(
            'caption_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'description' => esc_html__( 'Controls the figure caption color.', 'alpus-core' ),
                'selectors'   => array(
                    '{{WRAPPER}} figcaption' => 'color: {{VALUE}};',
                ),
            )
        );
        $this->end_controls_section();

        alpus_elementor_slider_style_controls( $this, 'layout_type' );
    }

    protected function render() {
        $atts = $this->get_settings_for_display();
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/image-gallery/render-image-gallery-elementor.php' );
    }
}
