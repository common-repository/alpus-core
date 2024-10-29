<?php

defined( 'ABSPATH' ) || die;

/*
 * Alpus Heading Widget
 *
 * Alpus Widget to display heading.
 *
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Alpus_Controls_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

class Alpus_Heading_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_heading';
    }

    public function get_title() {
        return esc_html__( 'Heading', 'alpus-core' );
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'heading', 'title', 'subtitle', 'text', 'alpus', 'dynamic' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon eicon-heading';
    }

    public function get_script_depends() {
        return array();
    }

    protected function register_controls() {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        $this->start_controls_section(
            'section_heading_title',
            array(
                'label' => esc_html__( 'Title', 'alpus-core' ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'       => esc_html__( 'Title', 'alpus-core' ),
                'description' => esc_html__( 'Type a certain heading you want to display.', 'alpus-core' ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => array(
                    'active' => true,
                ),
                'default'     => esc_html__( 'Add Your Heading Text Here', 'alpus-core' ),
                'placeholder' => esc_html__( 'Enter your title', 'alpus-core' ),
            )
        );

        $this->add_control(
            'link_url',
            array(
                'label'   => esc_html__( 'Link', 'elementor' ),
                'type'    => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'default' => array(
                    'url' => '',
                ),
            )
        );

        $this->add_control(
            'tag',
            array(
                'label'       => esc_html__( 'HTML Tag', 'alpus-core' ),
                'description' => esc_html__( 'Select the HTML Heading tag from H1 to H6 and P tag too.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => array(
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'p'    => 'p',
                    'div'  => 'div',
                ),
                'default'     => 'h2',
                'separator'   => 'after',
            )
        );

        $this->add_control(
            'decoration',
            array(
                'type'        => Alpus_Controls_Manager::IMAGE_CHOOSE,
                'label'       => esc_html__( 'Type', 'alpus-core' ),
                'default'     => '',
                'options'     => array(
                    ''      => 'assets/images/heading/heading-1.jpg',
                    'cross' => 'assets/images/heading/heading-2.jpg',
                ),
                'width'   => 2,
            )
        );

        $this->add_control(
            'white_space',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'White Space', 'alpus-core' ),
                'description' => esc_html__( 'Defines whether to be normal or nowrap for whitespace.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'title_align',
            array(
                'label'       => esc_html__( 'Title Align', 'alpus-core' ),
                'description' => esc_html__( 'Controls the alignment of title. Options are left, center and right.', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'title-left',
                'options'     => array(
                    'title-left'   => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'title-center' => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'title-right'  => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
            )
        );

        $this->add_control(
            'decoration_height',
            array(
                'label'       => esc_html__( 'Decoration Height (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the height of the decoration.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 200,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .title::before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .title::after'  => 'height: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'decoration' => 'cross',
                ),
            )
        );

        $this->add_responsive_control(
            'decoration_spacing',
            array(
                'label'       => esc_html__( 'Decoration Spacing', 'alpus-core' ),
                'description' => esc_html__( 'Controls the space between the heading and the decoration.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => -100,
                        'max'  => 100,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .title::before' => "margin-{$right}: {{SIZE}}{{UNIT}};",
                    '{{WRAPPER}} .title::after'  => "margin-{$left}: {{SIZE}}{{UNIT}};",
                ),
                'condition'   => array(
                    'decoration' => 'cross',
                ),
            )
        );

        $this->add_control(
            'border_color',
            array(
                'label'     => esc_html__( 'Decoration Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .title-cross .title::before, .elementor-element-{{ID}} .title-cross .title::after' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'decoration' => 'cross',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading_title_style',
            array(
                'label' => esc_html__( 'Title', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '.elementor-element-{{ID}} .title',
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'       => esc_html__( 'Title Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the heading color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label'       => esc_html__( 'Title Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the heading hover color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} .title:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'link_color',
            array(
                'label'       => esc_html__( 'Link Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the heading\'s <a> tag color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} .title a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'link_hover_color',
            array(
                'label'       => esc_html__( 'Link Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the heading\'s <a> tag hover color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} .title a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'background_type',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'Image Background', 'alpus-core' ),
                'description' => esc_html__( 'Defines whether to be background image heading type.', 'alpus-core' ),
                'condition'   => array( 'gradient_type!' => 'yes' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'heading_background_image',
                'types'     => array( 'classic' ),
                'selector'  => '.elementor-element-{{ID}} .title-background .title',
                'condition' => array(
                    'background_type' => 'yes',
                ),
            )
        );

        $this->add_control(
            'gradient_type',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'Gradient Background', 'alpus-core' ),
                'description' => esc_html__( 'Defines whether to be gradient heading type.', 'alpus-core' ),
                'condition'   => array( 'background_type!' => 'yes' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'heading_gradient_background',
                'types'     => array( 'gradient' ),
                'selector'  => '.elementor-element-{{ID}} .title-background .title',
                'condition' => array(
                    'gradient_type' => 'yes',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $atts         = $this->get_settings_for_display();
        $atts['self'] = $this;
        $this->add_inline_editing_attributes( 'title' );
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/heading/render-heading-elementor.php' );
    }
}
