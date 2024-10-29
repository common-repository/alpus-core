<?php

defined( 'ABSPATH' ) || die;

/*
 * Alpus Button Widget
 *
 * Alpus Widget to display button.
 *
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;

class Alpus_Button_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_button';
    }

    public function get_title() {
        return esc_html__( 'Button', 'alpus-core' );
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'Button', 'link', 'alpus' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-button';
    }

    public function get_script_depends() {
        return array();
    }

    public function register_controls() {
        $this->start_controls_section(
            'section_button',
            array(
                'label' => esc_html__( 'Button Options', 'alpus-core' ),
            )
        );

        $this->add_control(
            'label',
            array(
                'label'       => esc_html__( 'Text', 'alpus-core' ),
                'description' => esc_html__( 'Type text that will be shown on button.', 'alpus-core' ),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array(
                    'active' => true,
                ),
                'default'     => esc_html__( 'Click here', 'alpus-core' ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Button Url', 'alpus-core' ),
                'description' => esc_html__( 'Input URL where you will move when button is clicked.', 'alpus-core' ),
                'label_block' => true,
                'type'        => Controls_Manager::URL,
                'dynamic'     => array(
                    'active' => true,
                ),
                'default'     => array(
                    'url' => '',
                ),
            )
        );

        $this->add_control(
            'button_expand',
            array(
                'label'       => esc_html__( 'Expand', 'alpus-core' ),
                'description' => esc_html__( 'Makes button\'s width 100% full.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
            )
        );

        $this->add_responsive_control(
            'button_align',
            array(
                'label'       => esc_html__( 'Alignment', 'alpus-core' ),
                'description' => esc_html__( 'Controls button\'s alignment. Choose from Left, Center, Right.', 'alpus-core' ),
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
                    '.elementor-element-{{ID}} .elementor-widget-container' => 'text-align: {{VALUE}}',
                ),
                'condition'   => array(
                    'button_expand!' => 'yes',
                ),
            )
        );

        alpus_elementor_button_layout_controls( $this );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_video_button',
            array(
                'label' => esc_html__( 'Video Options', 'alpus-core' ),
            )
        );

        $this->add_control(
            'play_btn',
            array(
                'label'       => esc_html__( 'Use as a play button in section', 'alpus-core' ),
                'description' => esc_html__( 'You can play video whenever you enable video option in parent section widget using as banner.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_off'   => esc_html__( 'Off', 'alpus-core' ),
                'label_on'    => esc_html__( 'On', 'alpus-core' ),
                'condition'   => array(
                    'video_btn' => '',
                ),
            )
        );

        $this->add_control(
            'video_btn',
            array(
                'label'       => esc_html__( 'Use as video button', 'alpus-core' ),
                'description' => esc_html__( 'You can play video on lightbox.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_off'   => esc_html__( 'Off', 'alpus-core' ),
                'label_on'    => esc_html__( 'On', 'alpus-core' ),
                'default'     => '',
                'condition'   => array(
                    'play_btn' => '',
                ),
            )
        );

        $this->add_control(
            'video_url',
            array(
                'label'       => esc_html__( 'Video url', 'alpus-core' ),
                'description' => esc_html__( 'Type a certain URL of a video you want to upload.', 'alpus-core' ),
                'type'        => Controls_Manager::URL,
                'condition'   => array(
                    'video_btn' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        alpus_elementor_button_style_controls( $this );
    }

    public function render() {
        $atts         = $this->get_settings_for_display();
        $atts['self'] = $this;
        $this->add_inline_editing_attributes( 'label' );
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/button/render-button-elementor.php' );
    }
}
