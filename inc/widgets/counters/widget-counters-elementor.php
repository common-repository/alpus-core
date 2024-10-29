<?php
/**
 * Counters Element
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
use ELementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Alpus_Counters_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_counters';
    }

    public function get_title() {
        return esc_html__( 'Counters', 'alpus-core' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon eicon-counter';
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'counter' );
    }

    /**
     * Get the style depends.
     *
     * @since 1.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-counters', ALPUS_CORE_INC_URI . '/widgets/counters/counters' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_CORE_VERSION );

        return array( 'alpus-counters' );
    }

    public function get_script_depends() {
        $scripts = array( 'jquery-count-to' );

        if ( alpus_is_elementor_preview() || isset( $_REQUEST['elementor-preview'] ) ) {
            $scripts[] = 'alpus-elementor-js';
        }

        return $scripts;
    }

    protected function register_controls() {
        $right = is_rtl() ? 'left' : 'right';

        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Counters', 'alpus-core' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon',
            array(
                'label' => esc_html__( 'Icon', 'alpus-core' ),
                'type'  => Controls_Manager::ICONS,
            )
        );

        $repeater->add_control(
            'target',
            array(
                'label'   => esc_html__( 'Target Value', 'alpus-core' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '99',
            )
        );

        $repeater->add_control(
            'prefix',
            array(
                'label' => esc_html__( 'Number Prefix', 'alpus-core' ),
                'type'  => Controls_Manager::TEXT,
            )
        );

        $repeater->add_control(
            'suffix',
            array(
                'label' => esc_html__( 'Number Suffix', 'alpus-core' ),
                'type'  => Controls_Manager::TEXT,
            )
        );

        $repeater->add_control(
            'title',
            array(
                'label'       => esc_html__( 'Title', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Label', 'alpus-core' ),
            )
        );

        $repeater->add_control(
            'desc',
            array(
                'label'       => esc_html__( 'Description', 'alpus-core' ),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Description Here...', 'alpus-core' ),
            )
        );

        $presets = array(
            array(
                'target' => 35,
                'suffix' => '+',
                'title'  => esc_html__( 'Years Experience', 'alpus-core' ),
                'desc'   => esc_html__( 'Lorem ipsum dolor sit amet, conctetur adipisci elit viverra erat orci.', 'alpus-core' ),
            ),
            array(
                'target' => 250,
                'suffix' => '+',
                'title'  => esc_html__( 'Satisfied Clients', 'alpus-core' ),
                'desc'   => esc_html__( 'Lorem ipsum dolor sit amet, conctetur adipisci elit viverra erat orci.', 'alpus-core' ),
            ),
            array(
                'target' => 300,
                'suffix' => '+',
                'title'  => esc_html__( 'Project Done', 'alpus-core' ),
                'desc'   => esc_html__( 'Lorem ipsum dolor sit amet, conctetur adipisci elit viverra erat orci.', 'alpus-core' ),
            ),
        );

        $this->add_control(
            'counters_list',
            array(
                'label'       => esc_html__( 'Counters', 'alpus-core' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $presets,
                'title_field' => '{{prefix}}{{target}}{{suffix}}',
            )
        );

        $this->add_control(
            'title_html_tag',
            array(
                'label'     => esc_html__( 'Title HTML Tag', 'alpus-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'h1'  => 'H1',
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                ),
                'default'   => 'h3',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'time',
            array(
                'label'       => esc_html__( 'Counter Rolling Time', 'alpus-core' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 3,
                'min'         => 1,
                'description' => esc_html__( 'Seconds of counter rolling', 'alpus-core' ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'layout',
            array(
                'label' => esc_html__( 'Layout', 'alpus-core' ),
            )
        );

        // $this->add_control(
        // 	'layout_type',
        // 	array(
        // 		'label'   => esc_html__( 'Layout', 'alpus-core' ),
        // 		'type'    => Controls_Manager::HIDDEN,
        // 		'default' => 'grid',
        // 	)
        // );

        $this->add_control(
            'layout_type',
            array(
                'label'       => esc_html__( 'Layout', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'grid',
                'toggle'      => false,
                'description' => esc_html__( 'Set gallery layout', 'alpus-core' ),
                'options'     => array(
                    'grid'   => array(
                        'title' => esc_html__( 'Grid', 'alpus-core' ),
                        'icon'  => 'eicon-column',
                    ),
                    'slider' => array(
                        'title' => esc_html__( 'Slider', 'alpus-core' ),
                        'icon'  => 'eicon-slider-3d',
                    ),
                ),
            )
        );

        alpus_elementor_grid_layout_controls( $this, 'layout_type', false, '', 3 );

        alpus_elementor_slider_layout_controls( $this, 'layout_type' );

        $this->add_control(
            'count_position',
            array(
                'label'     => esc_html__( 'Count Position', 'alpus-core' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'top',
                'separator' => 'before',
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
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label'     => esc_html__( 'Text Alignment', 'alpus-core' ),
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
                    '.elementor-element-{{ID}} .counter' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'horizontal_align',
            array(
                'label'     => esc_html__( 'Horizontal Align', 'alpus-core' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'default'   => 'center',
                'selectors' => array(
                    '.elementor-element-{{ID}} .counter' => 'justify-content: {{VALUE}};',
                ),
                'condition' => array(
                    'count_position!' => 'top',
                ),
            )
        );

        $this->add_control(
            'vertical_align',
            array(
                'label'     => esc_html__( 'Vertical Align', 'alpus-core' ),
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
                    '.elementor-element-{{ID}} .counter' => 'align-items: {{VALUE}};',
                ),
                'condition' => array(
                    'count_position!' => 'top',
                ),
            )
        );

        $this->add_control(
            'show_dividers',
            array(
                'label'     => esc_html__( 'Show Dividers', 'alpus-core' ),
                'type'      => Controls_Manager::SWITCHER,
                'separator' => 'before',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'general_style',
            array(
                'label' => esc_html__( 'General', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'counter_background',
                'selector' => '.elementor-element-{{ID}} .counter',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'dropdown_box_shadow',
                'selector' => '.elementor-element-{{ID}} .counter',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'box_border',
                'selector'  => '.elementor-element-{{ID}} .counter',
                'condition' => array(
                    'show_dividers!' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'padding',
            array(
                'label'      => esc_html__( 'Padding', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'count_number',
            array(
                'label' => esc_html__( 'Count', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'number_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .counter .counter-number',
            )
        );

        $this->add_control(
            'number_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .counter .counter-number' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'number_margin',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .counter .counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'count_prefix',
            array(
                'label' => esc_html__( 'Prefix', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'prefix_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .counter .counter-number-prefix',
            )
        );

        $this->add_control(
            'prefix_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .counter .counter-number-prefix' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'prefix_margin',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .counter .counter-number-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'count_suffix',
            array(
                'label' => esc_html__( 'Suffix', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'suffix_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .counter .counter-number-suffix',
            )
        );

        $this->add_control(
            'suffix_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .counter .counter-number-suffix' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'suffix_margin',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .counter .counter-number-suffix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'count_icon',
            array(
                'label' => esc_html__( 'Icon', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} i'   => 'font-size: {{SIZE}}px;',
                    '.elementor-element-{{ID}} svg' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .counters i' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'svg_stroke',
            array(
                'label'     => esc_html__( 'Stroke Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} svg' => 'stroke: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'svg_fill',
            array(
                'label'     => esc_html__( 'Fill Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '.elementor-element-{{ID}} svg' => 'fill: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_margin',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} i'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.elementor-element-{{ID}} svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'count_title',
            array(
                'label' => esc_html__( 'Title', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .count-title',
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .count-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .count-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'count_desc',
            array(
                'label' => esc_html__( 'Description', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .count-desc',
            )
        );

        $this->add_control(
            'desc_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.elementor-element-{{ID}} .count-desc' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_margin',
            array(
                'label'      => esc_html__( 'Margin', 'alpus-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    'em',
                    'rem',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .count-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'count_divider',
            array(
                'label'     => esc_html__( 'Divider', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_dividers' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_width',
            array(
                'label'      => esc_html__( 'Width', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .counter:after' => 'width: {{SIZE}}{{UNIT}}; margin-' . $right . ': calc(-{{SIZE}}{{UNIT}} / 2);',
                ),
                'condition'  => array(
                    'show_dividers' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_height',
            array(
                'label'      => esc_html__( 'Height', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .counter:after' => 'height: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'show_dividers' => 'yes',
                ),
            )
        );

        $this->add_control(
            'divider_color',
            array(
                'label'     => esc_html__( 'Color', 'alpus-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .counter:after' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_dividers' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        alpus_elementor_slider_style_controls( $this, 'layout_type' );
    }

    protected function render() {
        $atts = $this->get_settings_for_display();

        require ALPUS_CORE_INC . '/widgets/counters/render-counters-elementor.php';
    }

    protected function content_template() {
        ?>
		<#
		var wrapper_cls = 'counters',
			wrapper_attrs = '',
			extra_class = '',
			extra_attrs = '',
			grid_space_class = alpus_get_grid_space_class( settings ),
			col_cnt          = alpus_elementor_grid_col_cnt( settings );

		if ( 'yes' == settings.show_dividers ) {
			wrapper_cls += ' counters-separated';
		}

		if ( grid_space_class ) {
			wrapper_cls += ' ' +  grid_space_class;
		}

		<?php
            alpus_elementor_grid_template();
        ?>
		
		if ( col_cnt ) {
			wrapper_cls += ' ' + alpus_get_col_class( col_cnt );
		}

		var counter_cls = 'counter';

		if ( 'top' != settings.count_position ) {
			counter_cls += ' counter-side position-' + settings.count_position;
		}
		wrapper_cls += ' ' + alpus_get_col_class( col_cnt );

		var table_cls  = 'price-table';
		table_cls += ' ' + settings.price_table_type + '-type';
		if ( 'yes' == settings.feature_divider ) {
			table_cls += ' features-separated';
		}

		if ( 'slider' == settings.layout_type ) {
			<?php
                alpus_elementor_slider_template();
        ?>

			extra_attrs += ' data-slider-class="' + extra_class + '"';
			wrapper_cls += extra_class;
			wrapper_attrs += ' ' + extra_attrs;
			extra_class  = '';

			var html = '<div "' + wrapper_attrs +  '" class="' + wrapper_cls +  '">';

		} else {
			var html = '<div class="' + wrapper_cls +  '">';
		}

		_.each( settings.counters_list, function( counter, index ) {
			html += '<div class="grid-col">';
			html += '<div class="' + counter_cls + '">';
			html += '<div class="counter-number">';
			
			icon_html = elementor.helpers.renderIcon( view, counter.icon, { 'aria-hidden': true }, 'i' , 'object' );
			
			if ( icon_html && icon_html.rendered ) {
				html += icon_html.value;
			} else {
				html += '<i class="' + counter.icon.value + '"></i>';
			}

			if ( counter.prefix ) {
				html += '<span class="counter-number-prefix">' + counter.prefix + '</span>';
			}
			var target = 0;
			if ( counter.target ) {
				target = counter.target;
			} else {
				target = 99;
			}
			html += '<span class="count-to" data-speed="' + ( settings.time ? settings.time : 1 ) * 1000 + '" data-to="' + target + '"></span>';

			if ( counter.suffix ) {
				html += '<span class="counter-number-suffix">' + counter.suffix + '</span>';
			}

			html += '</div>';

			if ( counter.title || counter.desc ) {
				html += '<div class="counter-content">';
				if ( counter.title ) {
					var settingKey = view.getRepeaterSettingKey( 'title', 'counters_list', index );
					view.addRenderAttribute( settingKey, 'class', 'count-title' );
					view.addInlineEditingAttributes( settingKey );
					var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag );
					html += '<' + titleHTMLTag + ' ' + view.getRenderAttributeString( settingKey ) + '>' + counter.title + '</' + titleHTMLTag + '>';
				}
				if ( counter.desc ) {
					var settingKey = view.getRepeaterSettingKey( 'desc', 'counters_list', index );
					view.addRenderAttribute( settingKey, 'class', 'count-desc' );
					view.addInlineEditingAttributes( settingKey );
					html += '<p ' + view.getRenderAttributeString( settingKey ) + '>' + counter.desc + '</p>';
				}
				html += '</div>';
			}

			html += '</div>';
			html += '</div>';
		} );

		html += '</div>';

		print( html );
		#>
		<?php
    }
}
