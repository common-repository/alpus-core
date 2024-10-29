<?php

defined( 'ABSPATH' ) || die;

/*
 * Alpus Menu Widget
 *
 * Alpus Widget to display menu.
 *
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Alpus_Controls_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Alpus_Menu_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_menu';
    }

    public function get_title() {
        return esc_html__( 'Menu', 'alpus-core' );
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-menu';
    }

    public function get_keywords() {
        return array( 'menu', 'alpus' );
    }

    public function get_script_depends() {
        return array();
    }

    /**
     * Get menu items.
     *
     * @return array Menu Items
     */
    public function get_menu_items() {
        $menu_items = array();
        $menus      = wp_get_nav_menus();

        foreach ( $menus as $key => $item ) {
            $menu_items[ $item->term_id ] = $item->name;
        }

        return $menu_items;
    }

    protected function register_controls() {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        $this->start_controls_section(
            'section_menu',
            array(
                'label' => esc_html__( 'Menu', 'alpus-core' ),
            )
        );

        $this->add_control(
            'menu_id',
            array(
                'label'       => esc_html__( 'Select Menu', 'alpus-core' ),
                'description' => esc_html__( 'Select certain menu you want to place among menus have been created.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $this->get_menu_items(),
            )
        );

        $this->add_control(
            'type',
            array(
                'label'           => esc_html__( 'Select Type', 'alpus-core' ),
                'description'     => esc_html__( 'Select certain type you want to display among fashionable types.', 'alpus-core' ),
                'type'            => Alpus_Controls_Manager::SELECT,
                /*
                 * Filters menu widget default style.
                 *
                 * @since 1.0
                 */
                'default'         => apply_filters( 'alpus_menu_widget_default', 'horizontal' ),
                'options'         => array(
                    'horizontal'  => esc_html__( 'Horizontal', 'alpus-core' ),
                    'vertical'    => esc_html__( 'Vertical', 'alpus-core' ),
                    'collapsible' => esc_html__( 'Vertical Collapsible', 'alpus-core' ),
                    'dropdown'    => esc_html__( 'Toggle Dropdown', 'alpus-core' ),
                    'flyout'      => esc_html__( 'Flyout', 'alpus-core' ),
                ),
                'disabledOptions' => array( 'flyout' ),
            )
        );

        Alpus_Core_Elementor_Extend::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'menu', 'type', array( 'type' => 'flyout' ) );

        $this->add_responsive_control(
            'width',
            array(
                'label'       => esc_html__( 'Width (px)', 'alpus-core' ),
                'description' => esc_html__( 'Type a number of your menu’s width.', 'alpus-core' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 300,
                'condition'   => array(
                    'type!' => 'horizontal',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .menu, .elementor-element-{{ID}} .toggle-menu' => 'width: {{VALUE}}px;',
                ),
            )
        );

        $this->add_control(
            'underline',
            array(
                'label'       => esc_html__( 'Underline on hover', 'alpus-core' ),
                'description' => esc_html__( 'Gives underline style to your menu items on hover.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'condition'   => array(
                    'type!' => 'dropdown',
                ),
            )
        );

        $this->add_control(
            'label',
            array(
                'label'       => esc_html__( 'Toggle Label', 'alpus-core' ),
                'description' => esc_html__( 'Type a toggle label.', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Browse Categories', 'alpus-core' ),
                'condition'   => array(
                    'type' => 'dropdown',
                ),
            )
        );

        $this->add_control(
            'icon',
            array(
                'label'                  => esc_html__( 'Toggle Icon', 'alpus-core' ),
                'description'            => esc_html__( 'Choose a toggle icon.', 'alpus-core' ),
                'skin'                   => 'inline',
                'exclude_inline_options' => array( 'svg' ),
                'label_block'            => false,
                'type'                   => Controls_Manager::ICONS,
                'default'                => array(
                    'value'   => ALPUS_ICON_PREFIX . '-icon-category',
                    'library' => 'alpus-icons',
                ),
                'condition'              => array(
                    'type' => 'dropdown',
                ),
            )
        );

        $this->add_control(
            'no_bd',
            array(
                'label'       => esc_html__( 'No Border', 'alpus-core' ),
                'description' => esc_html__( 'Toggle Menu Dropdown will have no border.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'condition'   => array(
                    'type' => 'dropdown',
                ),
            )
        );

        $this->add_control(
            'show_home',
            array(
                'label'       => esc_html__( 'Show on Homepage', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Menu Dropdown will be shown in homepage.', 'alpus-core' ),
                'condition'   => array(
                    'type' => 'dropdown',
                ),
            )
        );

        $this->add_control(
            'show_page',
            array(
                'label'       => esc_html__( 'Show on ALL Pages', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Menu Dropdown will be shown after loading in all pages.', 'alpus-core' ),
                'condition'   => array(
                    'type' => 'dropdown',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style',
            array(
                'label'     => esc_html__( 'Menu Toggle', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'type' => 'dropdown',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'toggle_typography',
                'selector' => '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle',
            )
        );

        $this->start_controls_tabs( 'toggle_color_tab' );
        $this->start_controls_tab(
            'toggle_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_control(
            'toggle_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_back_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'toggle_border[left]!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'toggle_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $this->add_control(
            'toggle_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .toggle-menu:hover .dropdown-menu-toggle, .elementor-element-{{ID}} .toggle-menu.show .dropdown-menu-toggle, .home .elementor-section:not(.fixed) .elementor-element-{{ID}} .show-home .dropdown-menu-toggle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_hover_back_color',
            array(
                'label'     => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .toggle-menu:hover .dropdown-menu-toggle, .elementor-element-{{ID}} .toggle-menu.show .dropdown-menu-toggle, .home .elementor-section:not(.fixed) .elementor-element-{{ID}} .show-home .dropdown-menu-toggle' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_hover_border_color',
            array(
                'label'     => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .toggle-menu:hover .dropdown-menu-toggle, .elementor-element-{{ID}} .toggle-menu.show .dropdown-menu-toggle, .home .elementor-section:not(.fixed) .elementor-element-{{ID}} .show-home .dropdown-menu-toggle' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'toggle_border[left]!' => '',
                ),
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'toggle_icon',
            array(
                'label'       => esc_html__( 'Icon Size (px)', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle i' => 'font-size: {{SIZE}}px;',
                ),
                'qa_selector' => '.toggle-menu .dropdown-menu-toggle i',
                'separator'   => 'before',
            )
        );

        $this->add_control(
            'toggle_icon_space',
            array(
                'label'      => esc_html__( 'Icon Space (px)', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle i + span' => "margin-{$left}: {{SIZE}}px;",
                ),
            )
        );

        $this->add_control(
            'toggle_border',
            array(
                'label'      => esc_html__( 'Border Width', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'rem' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
            )
        );

        $this->add_control(
            'toggle_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'toggle_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'qa_selector' => '.toggle-menu .dropdown-menu-toggle',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_ancestor_style',
            array(
                'label' => esc_html__( 'Menu Ancestor', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'ancestor_typography',
                'selector' => '.elementor-element-{{ID}} .menu > li > a',
            )
        );

        $this->start_controls_tabs( 'ancestor_color_tab' );
        $this->start_controls_tab(
            'ancestor_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_control(
            'ancestor_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu > li > a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'ancestor_back_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu > li > a' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'ancestor_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu > li > a' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'ancestor_border[left]!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ancestor_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $this->add_control(
            'ancestor_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu > li:hover > a'                 => 'color: {{VALUE}};',
                    '.elementor-element-{{ID}} .menu > .current-menu-item > a'       => 'color: {{VALUE}};',
                    '.elementor-element-{{ID}} .menu > li.current-menu-ancestor > a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'ancestor_hover_back_color',
            array(
                'label'     => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu > li:hover > a'               => 'background-color: {{VALUE}};',
                    '.elementor-element-{{ID}} .menu > .current-menu-item > a'     => 'background-color: {{VALUE}};',
                    '.elementor-element-{{ID}} .menu > .current-menu-ancestor > a' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'ancestor_hover_border_color',
            array(
                'label'     => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu > li:hover > a'               => 'border-color: {{VALUE}};',
                    '.elementor-element-{{ID}} .menu > .current-menu-item > a'     => 'border-color: {{VALUE}};',
                    '.elementor-element-{{ID}} .menu > .current-menu-ancestor > a' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'ancestor_border[left]!' => '',
                ),
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'ancestor_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .menu > li > a'                                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.elementor-element-{{ID}} .collapsible-menu>li>a>.toggle-btn' => "{$right}: {{RIGHT}}{{UNIT}}",
                    '{{WRAPPER}} .vertical-menu>li>a:after'                        => "{$right}: {{RIGHT}}{{UNIT}}",
                ),
                'separator'   => 'before',
                'qa_selector' => '.menu > li:nth-child(2) > a, .collapsible-menu>li>a>.toggle-btn',
            )
        );

        $this->add_responsive_control(
            'ancestor_sticky_padding',
            array(
                'label'       => esc_html__( 'Padding in Sticky', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '.sticky-content.fixed .elementor-element-{{ID}} .menu > li > a'                     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.sticky-content.fixed .elementor-element-{{ID}} .collapsible-menu>li>a>.toggle-btn' => "{$right}: {{RIGHT}}{{UNIT}}",
                    '.sticky-content.fixed .elementor-element-{{ID}} .vertical-menu>li>a:after'          => "{$right}: {{RIGHT}}{{UNIT}}",
                ),
                'qa_selector' => '.sticky-content.fixed .menu > li:nth-child(2) > a, .sticky-content.fixed .collapsible-menu>li>a>.toggle-btn',
            )
        );

        $this->add_responsive_control(
            'ancestor_margin',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'rem', '%' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .menu > li'            => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.elementor-element-{{ID}} .menu > li:last-child' => 'margin-' . $right . ': 0;',
                ),
                'condition'  => array(
                    'type' => 'horizontal',
                ),
            )
        );

        $this->add_responsive_control(
            'ancestor_margin2',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'rem', '%' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .menu > li'            => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.elementor-element-{{ID}} .menu > li:last-child' => 'margin-bottom: 0;',
                ),
                'condition'  => array(
                    'type!' => 'horizontal',
                ),
            )
        );

        $this->add_control(
            'ancestor_border',
            array(
                'label'      => esc_html__( 'Border Width', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'rem' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .menu > li > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
            )
        );

        $this->add_control(
            'ancestor_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .menu > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'underline_width',
            array(
                'label'       => esc_html__( 'Active Underline Height (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the height of underline.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'separator'   => 'before',
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .menu-active-underline > li > a:before' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'underline' => 'yes',
                    'type!'     => array(
                        'dropdown',
                        'flyout',
                    ),
                ),
            )
        );

        $this->add_responsive_control(
            'underline_pos',
            array(
                'label'       => esc_html__( 'Active Underline Position (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the position of underline from bottom of the menu item.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .menu-active-underline > li > a:before' => 'bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'underline' => 'yes',
                    'type!'     => array(
                        'dropdown',
                        'flyout',
                    ),
                ),
            )
        );

        $this->add_control(
            'underline_color',
            array(
                'label'     => esc_html__( 'Active Underline Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-active-underline > li > a:before' => 'color: {{VALUE}};',
                ),
                'condition'   => array(
                    'underline' => 'yes',
                    'type!'     => array(
                        'dropdown',
                        'flyout',
                    ),
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_submenu_style',
            array(
                'label' => esc_html__( 'Submenu Item', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'submenu_typography',
                'selector' => '.elementor-element-{{ID}} li ul',
            )
        );

        $this->start_controls_tabs( 'submenu_color_tab' );
        $this->start_controls_tab(
            'submenu_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_control(
            'submenu_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} li li > a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'submenu_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} li li > a' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'submenu_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $this->add_control(
            'submenu_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} li li:hover > a:not(.nolink)' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'submenu_hover_bg_color',
            array(
                'label'     => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} li li:hover > a:not(.nolink)' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'submenu_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'separator'   => 'before',
                'size_units'  => array( 'px', 'rem', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} li li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'qa_selector' => '.menu li li:nth-child(2) a',
            )
        );

        $this->add_control(
            'submenu_border_width',
            array(
                'label'      => esc_html__( 'Separator Height (px)', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} li li'            => 'border: 1px solid; border-width: 0 0 {{SIZE}}{{UNIT}} 0;',
                    '.elementor-element-{{ID}} li li:last-child' => 'border: none;',
                ),
            )
        );

        $this->add_control(
            'submenu_border_color',
            array(
                'label'     => esc_html__( 'Separator Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} li li' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dropdown_style',
            array(
                'label' => esc_html__( 'Menu Dropdown Box', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'dropdown_padding',
            array(
                'label'      => esc_html__( 'Padding', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'rem', '%' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .toggle-menu .menu, .elementor-element-{{ID}} .menu > li ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'dropdown_bg',
            array(
                'label'       => esc_html__( 'Background', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .toggle-menu .menu, .elementor-element-{{ID}} .menu li > ul'                           => 'background-color: {{VALUE}}',
                    '.elementor-element-{{ID}} .menu > .menu-item-has-children::after, .elementor-element-{{ID}} .toggle-menu::after' => 'border-bottom-color:  {{VALUE}}',
                    '.elementor-element-{{ID}} .menu.vertical-menu > .menu-item-has-children::after'                                  => "border-bottom-color: transparent; border-{$right}-color: {{VALUE}}",
                ),
                'qa_selector' => '.toggle-menu .menu, .menu>li>ul, .collapsible-menu',
            )
        );

        $this->add_control(
            'dropdown_border',
            array(
                'label'      => esc_html__( 'Border Width', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'rem', '%' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .menu > li ul' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
                'condition'  => array(
                    'type!' => 'dropdown',
                ),
            )
        );

        $this->add_control(
            'dropdown_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .menu > li ul' => 'border-color: {{VALUE}}',
                ),
                'condition' => array(
                    'type!' => 'dropdown',
                ),
            )
        );

        $this->add_control(
            'dropdown_bd_color',
            array(
                'label'     => esc_html__( 'Border Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .has-border ul.menu' => 'border-color: {{VALUE}}',
                    '.elementor-element-{{ID}} .has-border::before' => 'border-bottom-color: {{VALUE}} !important',
                ),
                'condition' => array(
                    'type'   => 'dropdown',
                    'no_bd!' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'dropdown_box_shadow',
                'selector' => '{{WRAPPER}} .dropdown-box, {{WRAPPER}} .show .dropdown-box, {{WRAPPER}} .menu>li>ul, {{WRAPPER}} .menu ul:not(.megamenu) ul,  .home {{WRAPPER}} .show-home .dropdown-box',
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $atts = $this->get_settings_for_display();
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/menu/render-menu.php' );
    }
}
