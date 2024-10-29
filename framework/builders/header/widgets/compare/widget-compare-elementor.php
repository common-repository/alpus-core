<?php
/**
 * Alpus Header Elementor Compare
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpus_Header_Compare_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_header_compare';
    }

    public function get_title() {
        return esc_html__( 'Compare', 'alpus-core' );
    }

    public function get_icon() {
        return ALPUS_ICON_PREFIX . '-icon-compare alpus-elementor-widget-icon';
    }

    public function get_categories() {
        return array( 'alpus_header_widget' );
    }

    public function get_keywords() {
        return array( 'header', 'alpus', 'compare', 'shop' );
    }

    public function get_script_depends() {
        $depends = array();

        if ( alpus_is_elementor_preview() ) {
            $depends[] = 'alpus-elementor-js';
        }

        return $depends;
    }
    protected function register_controls() {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' === $left ? 'right' : 'left';

        $this->start_controls_section(
            'section_compare_content',
            array(
                'label' => esc_html__( 'Compare', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'type',
            array(
                'label'   => esc_html__( 'Compare Type', 'alpus-core' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'inline',
                'options' => array(
                    'block'  => array(
                        'title' => esc_html__( 'Block', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                    'inline' => array(
                        'title' => esc_html__( 'Inline', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
            )
        );

        $this->add_control(
            'minicompare',
            array(
                'label'       => esc_html__( 'Compare Items', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => array(
                    ''          => esc_html__( 'Do not show', 'alpus-core' ),
                    'dropdown'  => esc_html__( 'Dropdown', 'alpus-core' ),
                    'offcanvas' => esc_html__( 'Off-Canvas', 'alpus-core' ),
                ),

                'description' => esc_html__( 'Select the way to show a mini-compare list.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'show_icon',
            array(
                'label'     => esc_html__( 'Show Icon', 'alpus-core' ),
                'default'   => 'yes',
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array(
                    'show_label' => 'yes',
                ),
            )
        );

        $this->add_control(
            'icon',
            array(
                'label'                  => esc_html__( 'Icon', 'alpus-core' ),
                'type'                   => Controls_Manager::ICONS,
                'default'                => array(
                    'value'   => ALPUS_ICON_PREFIX . '-icon-compare',
                    'library' => 'alpus-icons',
                ),
                'condition'              => array(
                    'show_icon' => 'yes',
                ),
                'skin'                   => 'inline',
                'exclude_inline_options' => array( 'svg' ),
                'label_block'            => false,
            )
        );

        $this->add_control(
            'icon_pos',
            array(
                'label'       => esc_html__( 'Show Icon Before', 'alpus-core' ),
                'default'     => 'yes',
                'type'        => Controls_Manager::SWITCHER,
                'conditions'  => array(
                    'relation' => 'and',
                    'terms'    => array(
                        array(
                            'name'     => 'show_icon',
                            'operator' => '==',
                            'value'    => 'yes',
                        ),
                        array(
                            'relation' => 'or',
                            'terms'    => array(
                                array(
                                    'name'     => 'type',
                                    'operator' => '==',
                                    'value'    => 'inline',
                                ),
                                array(
                                    'name'     => 'type',
                                    'operator' => '==',
                                    'value'    => '',
                                ),
                            ),
                        ),
                    ),
                ),
                'description' => esc_html__( 'Set the comapre icon before or after label.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'show_badge',
            array(
                'label'     => esc_html__( 'Show Badge', 'alpus-core' ),
                'default'   => 'yes',
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array(
                    'show_icon' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_label',
            array(
                'label'     => esc_html__( 'Show Label', 'alpus-core' ),
                'default'   => 'yes',
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array(
                    'show_icon' => 'yes',
                ),
            )
        );

        $this->add_control(
            'label',
            array(
                'label'       => esc_html__( 'Compare Label', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Compare', 'alpus-core' ),
                'condition'   => array(
                    'show_label' => 'yes',
                ),
                'description' => esc_html__( 'Set the text of compare label.', 'alpus-core' ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_compare_style',
            array(
                'label' => esc_html__( 'Compare', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'compare_typography',
                'selector'  => '.elementor-element-{{ID}} .offcanvas-open',
                'condition' => array(
                    'show_label' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'compare_icon',
            array(
                'label'       => esc_html__( 'Icon Size (px)', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .offcanvas-open i' => 'font-size: {{SIZE}}px;',
                ),
                'condition'   => array(
                    'show_icon' => 'yes',
                ),
                'description' => esc_html__( 'Control the size of compare icon.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'compare_icon_space',
            array(
                'label'       => esc_html__( 'Icon Space (px)', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .block-type i + span'                 => 'margin-top: {{SIZE}}px;',
                    '.elementor-element-{{ID}} .offcanvas-open.inline-type i + span' => "margin-{$left}: {{SIZE}}px;",
                    '.elementor-element-{{ID}} .offcanvas-open.inline-type span + i' => "margin-{$left}: {{SIZE}}px;",
                ),
                'condition'   => array(
                    'show_icon' => 'yes',
                ),
                'description' => esc_html__( 'Control the space between icon and label.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'compare_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .offcanvas-open' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'compare_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .offcanvas-open:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_compare_dropdown_style',
            array(
                'label'     => esc_html__( 'Dropdown', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'minicompare' => 'dropdown',
                ),
            )
        );

        $this->add_responsive_control(
            'dropdown_position',
            array(
                'label'      => esc_html__( 'Dropdown Position', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .dropdown-box' => "{$left}: {{SIZE}}{{UNIT}}; {$right}: auto;",
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_compare_badge_style',
            array(
                'label'     => esc_html__( 'Badge', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_badge' => 'yes',
                ),
            )
        );

        $this->add_control(
            'badge_size',
            array(
                'label'       => esc_html__( 'Badge Size', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .offcanvas-open .compare-count' => 'font-size: {{SIZE}}px;',
                ),
                'description' => esc_html__( 'Control the size of badge item.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'badge_h_position',
            array(
                'label'       => esc_html__( 'Horizontal Position', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .offcanvas-open .compare-count' => "{$left}: {{SIZE}}{{UNIT}};",
                ),
                'description' => esc_html__( 'Control the horizontal position of badge item.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'badge_v_position',
            array(
                'label'       => esc_html__( 'Vertical Position', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .offcanvas-open .compare-count' => 'top: {{SIZE}}{{UNIT}};',
                ),
                'description' => esc_html__( 'Control the vertical position of badge item.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'badge_badge_bg_color',
            array(
                'label'     => esc_html__( 'Badge Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .offcanvas-open .compare-count' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'badge_badge_color',
            array(
                'label'     => esc_html__( 'Badge Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .offcanvas-open .compare-count' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $atts     = array(
            'type'        => $settings['type'],
            'show_icon'   => isset( $settings['show_icon'] ) ? 'yes' === $settings['show_icon'] : true,
            'icon_pos'    => $settings['icon_pos'],
            'show_badge'  => 'yes' === $settings['show_badge'],
            'show_label'  => isset( $settings['show_label'] ) ? 'yes' === $settings['show_label'] : true,
            'icon'        => ! empty( $settings['icon']['value'] ) ? $settings['icon']['value'] : ALPUS_ICON_PREFIX . '-icon-compare',
            'label'       => ! empty( $settings['label'] ) ? $settings['label'] : esc_html__( 'Compare', 'alpus-core' ),
            'minicompare' => $settings['minicompare'],
        );
        require alpus_core_framework_path( ALPUS_BUILDERS . '/header/widgets/compare/render-compare-elementor.php' );
    }
}
