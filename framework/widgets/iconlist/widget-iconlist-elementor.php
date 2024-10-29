<?php
/**
 * Alpus Icon List Widget
 *
 * Alpus Widget to display icon list.
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpus_IconList_Elementor_Widget extends \Elementor\Widget_Icon_List {

    public function get_name() {
        return ALPUS_NAME . '_widget_iconlist';
    }

    public function get_group_name() {
        return 'icon-list';
    }

    public function get_title() {
        return esc_html__( 'Icon List', 'alpus-core' );
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'icon list', 'icon', 'list', 'alpus', 'menu' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon eicon-bullet-list';
    }

    /**
     * Get the style depends.
     *
     * @since 4.1
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-iconlist', alpus_core_framework_uri( '/widgets/iconlist/iconlist' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

        return array( 'alpus-iconlist' );
    }

    public function get_script_depends() {
        return array();
    }

    /**
     * Register icon list widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 4.0
     */
    protected function register_controls() {
        parent::register_controls();
        $this->update_control(
            'view',
            array(
                'description' => esc_html__( 'Select a certain layout type of your list among Default and Inline types.', 'alpus-core' ),
            )
        );

        $this->remove_control(
            'link_click'
        );

        $this->start_controls_section(
            'section_ordered',
            array(
                'label' => esc_html__( 'Ordered List', 'alpus-core' ),
            )
        );
        $this->add_control(
            'ordered_list',
            array(
                'label'       => esc_html__( 'Ordered List', 'alpus-core' ),
                'description' => esc_html__( 'Toggle for making your list ordered or not. *Please remove icons before setting this option.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_off'   => esc_html__( 'Off', 'alpus-core' ),
                'label_on'    => esc_html__( 'On', 'alpus-core' ),
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-item' => 'display: list-item;',
                ),
            )
        );
        $this->add_control(
            'list_style',
            array(
                'label'       => esc_html__( 'List Style', 'alpus-core' ),
                'description' => esc_html__( 'Select a certain list style for your ordered list.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => array(
                    'circle'               => esc_html__( 'Circle', 'alpus-core' ),
                    'decimal'              => esc_html__( 'Decimal', 'alpus-core' ),
                    'decimal-leading-zero' => esc_html__( 'Decimal Leading Zero', 'alpus-core' ),
                    'lower-alpus'          => esc_html__( 'Lower-alpus', 'alpus-core' ),
                    'upper-alpus'          => esc_html__( 'Upper-alpus', 'alpus-core' ),
                    'disc'                 => esc_html__( 'Disc', 'alpus-core' ),
                    'square'               => esc_html__( 'Square', 'alpus-core' ),
                ),
                'default'     => 'circle',
                'separator'   => 'before',
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-items' => 'list-style: {{VALUE}};',
                ),
                'condition'   => array(
                    'ordered_list' => 'yes',
                ),
            )
        );
        $this->end_controls_section();

        $this->update_control(
            'space_between',
            array(
                'default'     => array(
                    'size' => 10,
                ),
                'description' => esc_html__( 'Controls the space between your list items.', 'alpus-core' ),
            )
        );
        $this->update_responsive_control(
            'icon_align',
            array(
                'label'       => esc_html__( 'Horizontal Alignment', 'alpus-core' ),
                'description' => esc_html__( 'Controls the horizontal alignment of your lists.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'divider',
            array(
                'description' => esc_html__( 'Toggle for making your list items have dividers or not.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'divider_style',
            array(
                'description' => esc_html__( 'Controls the divider style.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'divider_weight',
            array(
                'description' => esc_html__( 'Controls the divider height.', 'alpus-core' ),
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:after'                  => 'bottom: calc(-1 * {{SIZE}}{{UNIT}} / 2)',
                    '{{WRAPPER}} .elementor-inline-items .elementor-icon-list-item:not(:last-child):after'                                 => 'border-left-width: {{SIZE}}{{UNIT}}',
                ),
            )
        );
        $this->update_control(
            'divider_width',
            array(
                'description' => esc_html__( 'Controls the divider width.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'divider_height',
            array(
                'description' => esc_html__( 'Controls the divider height in the inline type.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'divider_color',
            array(
                'description' => esc_html__( 'Controls the divider color.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'icon_color',
            array(
                'description' => esc_html__( 'Controls the icon color.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'icon_color_hover',
            array(
                'description' => esc_html__( 'Controls the icon hover color.', 'alpus-core' ),
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-item:hover > .elementor-icon-list-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-item .elementor-icon-list-icon i'         => 'transition: .3s;',
                    '{{WRAPPER}} .elementor-icon-list-item a:hover .elementor-icon-list-icon i' => 'color: {{VALUE}};',
                ),
            )
        );
        $this->update_control(
            'icon_size',
            array(
                'description' => esc_html__( 'Controls the icon size.', 'alpus-core' ),
            )
        );
        $this->add_responsive_control(
            'icon_lineheight',
            array(
                'label'       => esc_html__( 'Line Height', 'alpus-core' ),
                'description' => esc_html__( 'Controls the icon line height.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'em' ),
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-icon i'   => 'line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-icon-list-icon svg' => 'height: {{SIZE}}{{UNIT}};',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'icon_size',
                ),
            )
        );
        $this->update_control(
            'icon_color_hover_transition',
            array(
                'default' => array(
                    'unit' => 's',
                    'size' => '',
                ),
            )
        );
        $this->remove_control(
            'icon_self_vertical_align'
        );
        $this->remove_control(
            'icon_vertical_offset'
        );

        $this->add_responsive_control(
            'bg_size',
            array(
                'label'       => esc_html__( 'Background Size', 'alpus-core' ),
                'description' => esc_html__( 'Controls the icon background size.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 25,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'display: inline-flex; justify-content: center; align-items: center; max-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'icon_lineheight',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_v_align',
            array(
                'label'     => esc_html__( 'Vertical Alignment', 'alpus-core' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
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
                ),
                'selectors' => array(
                    '.elementor-element-{{ID}} .elementor-icon-list-items .elementor-icon-list-item' => 'align-items: {{VALUE}}',
                ),
                'condition' => array(
                    'ordered_list!' => 'yes',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'before',
                    'of' => 'divider',
                ),
            )
        );

        $this->add_control(
            'bg_color',
            array(
                'label'       => esc_html__( 'Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the icon background color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'background-color: {{VALUE}};',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'bg_size',
                ),
            )
        );
        $this->add_control(
            'bg_color_hover',
            array(
                'label'       => esc_html__( 'Background Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the icon background hover color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-item .elementor-icon-list-icon'       => 'transition: .3s;',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'bg_color',
                ),
            )
        );
        $this->add_control(
            'border_style',
            array(
                'label'     => esc_html__( 'Border Style', 'alpus-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'none'   => esc_html__( 'None', 'alpus-core' ),
                    'solid'  => esc_html__( 'Solid', 'alpus-core' ),
                    'double' => esc_html__( 'Double', 'alpus-core' ),
                    'dotted' => esc_html__( 'Dotted', 'alpus-core' ),
                    'dashed' => esc_html__( 'Dashed', 'alpus-core' ),
                ),
                'default'   => 'none',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'border-style: {{VALUE}}',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'bg_color_hover',
                ),
            )
        );
        $this->add_control(
            'br_color',
            array(
                'label'       => esc_html__( 'Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the icon border color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'border-color: {{VALUE}};',
                ),
                'condition'   => array(
                    'border_style!' => 'none',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'border_style',
                ),
            )
        );
        $this->add_control(
            'br_color_hover',
            array(
                'label'       => esc_html__( 'Border Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the icon border hover color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-item .elementor-icon-list-icon i'     => 'transition: .3s;',
                ),
                'condition'   => array(
                    'border_style!' => 'none',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'br_color',
                ),
            )
        );
        $this->add_control(
            'border_width',
            array(
                'label'       => esc_html__( 'Border Width', 'alpus-core' ),
                'description' => esc_html__( 'Controls the icon border width.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'border_style!' => 'none',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'br_color_hover',
                ),
            )
        );
        $this->add_control(
            'border_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'alpus-core' ),
                'description' => esc_html__( 'Controls the icon border radius.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'border_style!' => 'none',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'after',
                    'of' => 'border_width',
                ),
            )
        );
        $this->remove_control(
            'icon_self_align'
        );
        $this->update_control(
            'text_color',
            array(
                'description' => esc_html__( 'Controls the list text color.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'text_color_hover',
            array(
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-item:hover > .elementor-icon-list-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-item .elementor-icon-list-text'         => 'transition: color .3s;',
                    '{{WRAPPER}} .elementor-icon-list-item a:hover .elementor-icon-list-text' => 'color: {{VALUE}};',
                ),
                'description' => esc_html__( 'Controls the list text hover color.', 'alpus-core' ),
            )
        );
        $this->update_control(
            'text_color_hover_transition',
            array(
                'default' => array(
                    'unit' => 's',
                    'size' => '',
                ),
            )
        );
        $this->update_control(
            'text_indent',
            array(
                'separator'   => '',
                'selectors'   => [
                    '{{WRAPPER}} .elementor-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'description' => esc_html__( 'Controls the spacing between icon and text.', 'alpus-core' ),
            )
        );

        $this->start_controls_section(
            'section_marker',
            array(
                'label'     => esc_html__( 'Marker', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'ordered_list' => 'yes',
                ),
            )
        );

        $this->add_control(
            'marker_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the marker color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-item::marker' => 'color: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'marker_color_hover',
            array(
                'label'       => esc_html__( 'Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the marker hover color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .elementor-icon-list-item:hover::marker' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-item::marker'       => 'transition: color .3s;',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'marker_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-list-item::marker',
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $atts = $this->get_settings_for_display();
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/iconlist/render-iconlist-elementor.php' );
    }

    protected function content_template() {
        ?>
		<#
			view.addRenderAttribute( 'icon_list', 'class', 'elementor-icon-list-items' );
			view.addRenderAttribute( 'list_item', 'class', 'elementor-icon-list-item' );

			if ( 'inline' == settings.view ) {
				view.addRenderAttribute( 'icon_list', 'class', 'elementor-inline-items' );
				view.addRenderAttribute( 'list_item', 'class', 'elementor-inline-item' );
			}
			var iconsHTML = {},
				migrated = {};
		#>
		<# if ( settings.icon_list ) { #>
			<ul {{{ view.getRenderAttributeString( 'icon_list' ) }}}>
			<# _.each( settings.icon_list, function( item, index ) {

					var iconTextKey = view.getRepeaterSettingKey( 'text', 'icon_list', index );

					view.addRenderAttribute( iconTextKey, 'class', 'elementor-icon-list-text' );

					view.addInlineEditingAttributes( iconTextKey ); #>

					<li {{{ view.getRenderAttributeString( 'list_item' ) }}}>
						<# if ( item.link && item.link.url ) { #>
							<a href="{{ item.link.url }}">
						<# } #>
						<# if ( item.icon || item.selected_icon.value ) { #>
						<span class="elementor-icon-list-icon">
							<#
								iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
								migrated[ index ] = elementor.helpers.isIconMigrated( item, 'selected_icon' );
								if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.icon || migrated[ index ] ) ) { #>
									{{{ iconsHTML[ index ].value }}}
								<# } else { #>
									<i class="{{ item.icon }}" aria-hidden="true"></i>
								<# }
							#>
						</span>
						<# } #>
						<span {{{ view.getRenderAttributeString( iconTextKey ) }}}>{{{ item.text }}}</span>
						<# if ( item.link && item.link.url ) { #>
							</a>
						<# } #>
					</li>
				<#
				} ); #>
			</ul>
		<#	} #>

		<?php
    }
}
