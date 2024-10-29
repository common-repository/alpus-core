<?php
/**
 * Alpus Header Elementor Wishlist
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpus_Header_Wishlist_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_header_wishlist';
    }

    public function get_title() {
        return esc_html__( 'Wishlist', 'alpus-core' );
    }

    public function get_icon() {
        return ALPUS_ICON_PREFIX . '-icon-heart  alpus-elementor-widget-icon';
    }

    public function get_categories() {
        return array( 'alpus_header_widget' );
    }

    public function get_keywords() {
        return array( 'header', 'alpus', 'wish', 'love', 'like', 'list' );
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
        $right = 'left' == $left ? 'right' : 'left';

        $this->start_controls_section(
            'section_wishlist_content',
            array(
                'label' => esc_html__( 'Wishlist', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'type',
            array(
                'label'   => esc_html__( 'Wishlist Type', 'alpus-core' ),
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
            'miniwishlist',
            array(
                'label'       => esc_html__( 'Wishlist Items', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => array(
                    ''          => esc_html__( 'Do not show', 'alpus-core' ),
                    'dropdown'  => esc_html__( 'Dropdown', 'alpus-core' ),
                    'offcanvas' => esc_html__( 'Off-Canvas', 'alpus-core' ),
                ),
                'description' => esc_html__( 'Control the way to show a mini-wishlist list.', 'alpus-core' ),
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
                'label'       => esc_html__( 'Label', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Wishlist', 'alpus-core' ),
                'condition'   => array(
                    'show_label' => 'yes',
                ),
                'description' => esc_html__( 'Set the text of wishlist label.', 'alpus-core' ),
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
                    'value'   => ALPUS_ICON_PREFIX . '-icon-heart',
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
                'description' => esc_html__( 'Set the wishlist icon before or after label.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'show_count',
            array(
                'label'     => esc_html__( 'Show Badge', 'alpus-core' ),
                'default'   => '',
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array(
                    'show_icon' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_wishlist_style',
            array(
                'label' => esc_html__( 'Wishlist', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'wishlist_typography',
                'selector'  => '.elementor-element-{{ID}} .wishlist',
                'condition' => array(
                    'show_label' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'wishlist_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .elementor-widget-container > .wishlist, .elementor-element-{{ID}} .wishlist-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'conditions'  => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'show_label',
                            'operator' => '==',
                            'value'    => 'yes',
                        ),
                        array(
                            'name'     => 'show_icon',
                            'operator' => '==',
                            'value'    => 'yes',
                        ),
                    ),
                ),
                'description' => esc_html__( 'Control the padding value of wishlist.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'account_icon',
            array(
                'label'       => esc_html__( 'Icon Size (px)', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .wishlist i' => 'font-size: {{SIZE}}px;',
                ),
                'condition'   => array(
                    'show_icon' => 'yes',
                ),
                'description' => esc_html__( 'Control the size of wishlist icon.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'account_icon_space',
            array(
                'label'       => esc_html__( 'Icon Space (px)', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .wishlist.block-type i + span'  => 'margin-top: {{SIZE}}px;',
                    '.elementor-element-{{ID}} .wishlist.inline-type i + span' => "margin-{$left}: {{SIZE}}px;",
                    '.elementor-element-{{ID}} .wishlist.inline-type span + i' => "margin-{$left}: {{SIZE}}px;",
                ),
                'condition'   => array(
                    'show_icon'  => 'yes',
                    'show_label' => 'yes',
                ),
                'description' => esc_html__( 'Control the space between icon and label.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'account_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .wishlist' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'account_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .wishlist-dropdown:hover .wishlist' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_wishlist_dropdown_style',
            array(
                'label'     => esc_html__( 'Dropdown', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'miniwishlist' => 'dropdown',
                ),
            )
        );

        $this->add_responsive_control(
            'dropdown_position',
            array(
                'label'       => esc_html__( 'Dropdown Position', 'alpus-core' ),
                'description' => esc_html__( 'Left offset of dropdown', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .dropdown-box' => "{$left}: {{SIZE}}{{UNIT}}; {$right}: auto;",
                ),
                'description' => esc_html__( 'Control the horizontal position of dropdown box.', 'alpus-core' ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_wishlist_badge_style',
            array(
                'label'     => esc_html__( 'Badge', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_count' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'badge_size',
            array(
                'label'       => esc_html__( 'Badge Size', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .wishlist .wish-count' => 'font-size: {{SIZE}}px;',
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
                    '.elementor-element-{{ID}} .wishlist .wish-count' => "{$left}: {{SIZE}}{{UNIT}};",
                ),
                'description' => esc_html__( 'Control the horizontal position of badge.', 'alpus-core' ),
            )
        );

        $this->add_responsive_control(
            'badge_v_position',
            array(
                'label'       => esc_html__( 'Vertical Position', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .wishlist .wish-count' => 'top: {{SIZE}}{{UNIT}};',
                ),
                'description' => esc_html__( 'Control the vertical position of badge.', 'alpus-core' ),
            )
        );

        $this->add_control(
            'badge_count_bg_color',
            array(
                'label'     => esc_html__( 'Count Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .wishlist .wish-count' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'badge_count_color',
            array(
                'label'     => esc_html__( 'Count Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .wishlist .wish-count' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $atts     = array(
            'type'         => $settings['type'],
            'show_label'   => isset( $settings['show_label'] ) ? 'yes' == $settings['show_label'] : true,
            'show_count'   => 'yes' == $settings['show_count'],
            'show_icon'    => isset( $settings['show_icon'] ) ? 'yes' == $settings['show_icon'] : true,
            'icon_pos'     => 'yes' == $settings['icon_pos'],
            'label'        => ! empty( $settings['label'] ) ? $settings['label'] : esc_html__( 'Wishlist', 'alpus-core' ),
            'icon'         => ! empty( $settings['icon']['value'] ) ? $settings['icon']['value'] : ALPUS_ICON_PREFIX . '-icon-heart',
            'miniwishlist' => $settings['miniwishlist'],
        );
        require alpus_core_framework_path( ALPUS_BUILDERS . '/header/widgets/wishlist/render-wishlist-elementor.php' );
    }
}
