<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/*
 * Alpus Image Box Widget
 *
 *
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
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
        return 'alpus-elementor-widget-icon alpus-widget-icon-imagebox';
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
     * @since 1.2.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-image-box', alpus_core_framework_uri( '/widgets/image-box/image-box' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

        return array( 'alpus-image-box' );
    }

    public function get_script_depends() {
        return array();
    }

    protected function register_controls() {
        $this->start_controls_section(
            'imagebox_content',
            array(
                'label' => esc_html__( 'Image Box', 'alpus-core' ),
            )
        );

        $this->add_control(
            'type',
            array(
                'label'       => esc_html__( 'Imagebox Type', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'description' => esc_html__( 'Choose your imagebox display type from 3 default ones.', 'alpus-core' ),
                'default'     => '',
                'options'     => array(
                    ''      => esc_html__( 'Default', 'alpus-core' ),
                    'outer' => esc_html__( 'Outer Title', 'alpus-core' ),
                    'inner' => esc_html__( 'Inner Title', 'alpus-core' ),
                ),
            )
        );

        $this->add_control(
            'image',
            array(
                'label'       => esc_html__( 'Choose Image', 'alpus-core' ),
                'description' => esc_html__( 'Choose your image from the library.', 'alpus-core' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array(
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ),
                'separator'   => 'before',
                'dynamic'     => array(
                    'active' => false,
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'        => 'image',
                'default'     => 'full',
                'description' => esc_html__( 'Select proper image size from several sizes.', 'alpus-core' ),
                'separator'   => 'none',
                'exclude'     => [ 'custom' ],
            )
        );

        $this->add_control(
            'title',
            array(
                'label'       => esc_html__( 'Title', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'description' => esc_html__( 'Input imagebox title.', 'alpus-core' ),
                'default'     => esc_html__( 'Input Title Here', 'alpus-core' ),
                'separator'   => 'before',
                'dynamic'     => array(
                    'active' => false,
                ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Link Url', 'alpus-core' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => array(
                    'active' => true,
                ),
                'description' => esc_html__( 'Input URL where you will be access to when you click imagebox title.', 'alpus-core' ),
                'placeholder' => 'https://your-link.com',
                'default'     => array(
                    'url' => '',
                ),
            )
        );

        $this->add_control(
            'subtitle',
            array(
                'label'       => esc_html__( 'Subtitle', 'alpus-core' ),
                'description' => esc_html__( 'Input imagebox subtitle', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Input Subtitle Here', 'alpus-core' ),
                'separator'   => 'before',
                'dynamic'     => array(
                    'active' => false,
                ),
            )
        );

        $this->add_control(
            'content',
            array(
                'label'       => esc_html__( 'Content', 'alpus-core' ),
                'description' => esc_html__( 'Input any content(even custom html) for your imagebox.', 'alpus-core' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => '10',
                'default'     => '<div class="social-icons">
									<a href="#" class="social-icon framed social-facebook"><i class="fab fa-facebook-f"></i></a>
									<a href="#" class="social-icon framed social-twitter"><i class="fab fa-twitter"></i></a>
									<a href="#" class="social-icon framed social-linkedin"><i class="fab fa-linkedin-in"></i></a>
								</div>',
            )
        );

        $this->add_responsive_control(
            'invisible_top',
            array(
                'label'       => esc_html__( 'Content Top Offset', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'description' => esc_html__( 'This is available to control space between subtitle and content when imagebox is hovered.', 'alpus-core' ),
                'default'     => array(
                    'size' => 0,
                    'unit' => 'rem',
                ),
                'size_units'  => array(
                    'rem',
                    'px',
                ),
                'range'       => array(
                    'rem' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 30,
                    ),
                ),
                'condition'   => array( 'type' => 'inner' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} figure:hover .overlay-transparent' => 'padding-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'imagebox_align',
            array(
                'label'       => esc_html__( 'Alignment', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'description' => esc_html__( 'Controls the horizontal alignment of your imagebox.', 'alpus-core' ),
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
                'default'     => 'center',
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-box' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'imagebox_style',
            array(
                'label' => esc_html__( 'Image Box Style', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'imagebox_title_style',
            array(
                'label' => esc_html__( 'Title', 'alpus-core' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_control(
            'imagebox_title_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of your imagebox title.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
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

        $this->add_control(
            'imagebox_title_spacing',
            array(
                'label'       => esc_html__( 'Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the bottom space of imagebox title.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-box .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'imagebox_subtitle_style',
            array(
                'label'     => esc_html__( 'Subtitle', 'alpus-core' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'imagebox_subtitle_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of imagebox subtitle.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-box .subtitle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .image-box .subtitle',
            )
        );

        $this->add_control(
            'imagebox_subtitle_spacing',
            array(
                'label'       => esc_html__( 'Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the bottom space of imagebox subtitle.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-box .subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'imagebox_content_style',
            array(
                'label'     => esc_html__( 'Content', 'alpus-core' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'imagebox_content_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of imagebox description.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-box .content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .image-box .content',
            )
        );

        $this->add_control(
            'imagebox_content_spacing',
            array(
                'label'       => esc_html__( 'Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Control the bottom space of imagebox description.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-box .content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'imagebox_overlay',
            array(
                'label'     => esc_html__( 'Image Box Overlay', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'type!' => '',
                ),
            )
        );

        $this->add_control(
            'imagebox_overlay_color',
            array(
                'label'       => esc_html__( 'Overlay Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the overlay color of your imagebox.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .image-box.inner figure:hover' => '--alpus-primary-color-op-80: {{VALUE}};',
                    '.elementor-element-{{ID}} .image-box.outer .overlay '    => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $atts = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'title' );
        $this->add_inline_editing_attributes( 'subtitle' );
        $this->add_inline_editing_attributes( 'content' );

        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/image-box/render-image-box-elementor.php' );
    }
}
