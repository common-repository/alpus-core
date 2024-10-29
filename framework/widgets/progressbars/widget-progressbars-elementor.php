<?php
/**
 * Progressbars Element
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Alpus_Progressbars_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_progressbars';
    }

    public function get_title() {
        return esc_html__( 'Progress Bars', 'alpus-core' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-progressbar';
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    /**
     * Get style depends
     *
     * @since 1.2.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-progressbar', alpus_core_framework_uri( '/widgets/progressbars/progressbar' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

        return array( 'alpus-progressbar' );
    }

    /**
     * Get script dependency
     *
     * @since 1.2.0
     */
    public function get_script_depends() {
        wp_register_script( 'alpus-progress-bar', alpus_core_framework_uri( '/widgets/progressbars/progressbar' . ALPUS_JS_SUFFIX ), array( 'jquery-core' ), ALPUS_CORE_VERSION, true );

        return array( 'alpus-progress-bar' );
    }

    public function get_keywords() {
        return array( 'progress bar' );
    }

    protected function register_controls() {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        $this->start_controls_section(
            'section_progress',
            array(
                'label' => esc_html__( 'Progress Bars', 'alpus-core' ),
            )
        );

        $this->add_control(
            'text_pos',
            array(
                'label'       => esc_html__( 'Text Position', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'description' => esc_html__( 'Control text position in progressbar', 'alpus-core' ),
                'default'     => 'outer',
                'options'     => array(
                    'inner' => esc_html__( 'Inner', 'alpus-core' ),
                    'outer' => esc_html__( 'Outer', 'alpus-core' ),
                ),
            )
        );
        $this->add_control(
            'display_percentage',
            array(
                'label'       => esc_html__( 'Display Percentage', 'alpus-core' ),
                'description' => esc_html__( 'Display percentage in progressbar', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => 'yes',
            )
        );
        $this->add_control(
            'percentage_pos',
            array(
                'label'       => esc_html__( 'Percentage Position', 'alpus-core' ),
                'description' => esc_html__( 'Determine percentage position in progressbar', 'alpus-core' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'options'     => array(
                    ''            => esc_html__( 'End of Bar', 'alpus-core' ),
                    'percent'     => esc_html__( 'End of Percent', 'alpus-core' ),
                    'after_title' => esc_html__( 'After Title', 'alpus-core' ),
                ),
                'condition'   => array(
                    'display_percentage' => 'yes',
                ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            array(
                'label'       => esc_html__( 'Title', 'alpus-core' ),
                'description' => esc_html__( 'Enter progressbar title', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title', 'alpus-core' ),
                'default'     => esc_html__( 'Performance', 'alpus-core' ),
                'label_block' => true,
            )
        );

        $repeater->add_control(
            'progress_skin',
            array(
                'label'       => esc_html__( 'Skin', 'alpus-core' ),
                'description' => esc_html__( 'Choose your favorite skin among of 10 ones', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'options'     => array(
                    'default'   => esc_html__( 'Default', 'alpus-core' ),
                    'primary'   => esc_html__( 'Primary', 'alpus-core' ),
                    'secondary' => esc_html__( 'Secondary', 'alpus-core' ),
                    'dark'      => esc_html__( 'Dark', 'alpus-core' ),
                    'white'     => esc_html__( 'White', 'alpus-core' ),
                    'success'   => esc_html__( 'Success', 'alpus-core' ),
                    'info'      => esc_html__( 'Info', 'alpus-core' ),
                    'warning'   => esc_html__( 'Warning', 'alpus-core' ),
                    'danger'    => esc_html__( 'Danger', 'alpus-core' ),
                ),
            )
        );

        $repeater->add_control(
            'percent',
            array(
                'label'       => esc_html__( 'Percentage', 'alpus-core' ),
                'description' => esc_html__( 'Input percentage of progressbar.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => 50,
                    'unit' => '%',
                ),
            )
        );

        $this->add_control(
            'progressbars_list',
            array(
                'label'       => esc_html__( 'Progressbars', 'alpus-core' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'title'         => esc_html__( 'Performance', 'alpus-core' ),
                        'progress_skin' => 'default',
                        'percent'       => array(
                            'size' => 86,
                            'unit' => '%',
                        ),
                    ),
                    array(
                        'title'         => esc_html__( 'Average Improvements', 'alpus-core' ),
                        'progress_skin' => 'default',
                        'percent'       => array(
                            'size' => 98,
                            'unit' => '%',
                        ),
                    ),
                    array(
                        'title'         => esc_html__( 'Promotion', 'alpus-core' ),
                        'progress_skin' => 'default',
                        'percent'       => array(
                            'size' => 69,
                            'unit' => '%',
                        ),
                    ),
                ),
                'title_field' => '{{{ title }}}',
            )
        );

        $this->add_responsive_control(
            'spacing',
            array(
                'label'       => esc_html__( 'Spacing', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'description' => esc_html__( 'Controls the gap space between progressbars.', 'alpus-core' ),
                'default'     => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .progress-wrapper:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_progress_style',
            array(
                'label' => esc_html__( 'Progress Bar', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'effect',
            array(
                'label'       => esc_html__( 'Effect', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'description' => esc_html__( 'Choose your favourite effect for progressbars.', 'alpus-core' ),
                'options'     => array(
                    ''           => esc_html__( 'None', 'alpus-core' ),
                    'indicating' => esc_html__( 'Indicating', 'alpus-core' ),
                    'animated'   => esc_html__( 'Animated', 'alpus-core' ),
                ),
            )
        );

        $this->add_control(
            'bar_bg_color',
            array(
                'label'       => esc_html__( 'Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Sets the background color of progressbar.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} .progress-wrapper' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'           => 'bar_color',
                'selector'       => '.elementor-element-{{ID}} .progress-bar',
                'description'    => esc_html__( 'Sets the color of active portion in progressbar.', 'alpus-core' ),
                'exclude'        => array( 'image' ),
                'fields_options' => array(
                    'background'     => array(
                        'label' => esc_html__( 'Progress Color Type', 'alpus-core' ),
                    ),
                    'gradient_angle' => array(
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: transparent; background-size:100%; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ),
                ),
                'condition'      => array(
                    'effect!' => 'animated',
                ),
            )
        );

        $this->add_control(
            'bar_color_animated',
            array(
                'label'       => esc_html__( 'Progress Color', 'alpus-core' ),
                'description' => esc_html__( 'Sets the color of active portion in progressbar.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} .progress-bar' => 'background-color: {{VALUE}};',
                ),
                'condition'   => array(
                    'effect' => 'animated',
                ),
            )
        );

        $this->add_control(
            'bar_height',
            array(
                'label'       => esc_html__( 'Height', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'description' => esc_html__( 'Controls the height of progressbars.', 'alpus-core' ),
                'selectors'   => array(
                    '{{WRAPPER}} .progress-wrapper'             => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .progress-wrapper .inner-text' => 'line-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'bar_border_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'description' => esc_html__( 'Controls the border radius of progressbars.', 'alpus-core' ),
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .progress-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'bar_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'description' => esc_html__( 'Controls the padding dimensions of progressbars.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%', 'rem' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .progress-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'text_pos' => 'inner',
                ),
            )
        );

        $this->add_control(
            'title_heading',
            array(
                'label'     => esc_html__( 'Title', 'alpus-core' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'       => esc_html__( 'Text Color', 'alpus-core' ),
                'description' => esc_html__( 'Sets the color of progressbar title.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'        => 'typography',
                'description' => esc_html__( 'Controls the typography of progressbar title.', 'alpus-core' ),
                'selector'    => '{{WRAPPER}} .title',
            )
        );

        $this->add_control(
            'title_spacing',
            array(
                'label'       => esc_html__( 'Spacing before Percentage', 'alpus-core' ),
                'description' => esc_html__( 'Controls the gap space between title and percentage.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .title' => "margin-{$right}: {{SIZE}}{{UNIT}};",
                ),
                'condition'   => array(
                    'percentage_pos' => 'after_title',
                ),
            )
        );

        $this->add_control(
            'title_spacing_bar',
            array(
                'label'       => esc_html__( 'Spacing before Bar', 'alpus-core' ),
                'description' => esc_html__( 'Controls the gap space between title and progressbar.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .title-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'text_pos' => 'outer',
                ),
            )
        );

        $this->add_control(
            'percentage_heading',
            array(
                'label'     => esc_html__( 'Percentage', 'alpus-core' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'percentage_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Sets the color of percentage.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} .progress-percentage' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'        => 'percentage_typography',
                'description' => esc_html__( 'Controls the typography of percentage.', 'alpus-core' ),
                'selector'    => '{{WRAPPER}} .progress-percentage',
            )
        );

        $this->end_controls_section();
    }

    protected function content_template() {
        ?>
		<#
		var addClass = 'progress-bars';
		if ( settings.effect ) {
			addClass += ' progress-' + settings.effect;
		}
		if ( 'inner' == settings.text_pos ) {
			addClass += ' progress-inner-text';
		}
		if ( 'percent' == settings.percentage_pos ) {
			addClass += ' percent-end-progress';
		} else if ( 'after_title' == settings.percentage_pos ) {
			addClass += ' percent-end-title';
		} else {
			addClass += ' percent-end-bar';
		}
		#>
		<div class="{{{addClass}}}">
		<#
		_.each( settings.progressbars_list, function( item, index ) {
			var progress_percentage = 0;
			if ( ! isNaN( item.percent.size ) ) {
				progress_percentage = 100 < item.percent.size ? 100 : item.percent.size;
			}

			view.addRenderAttribute( 'title', {
				'class': 'title'
			} );

			var title_key = view.getRepeaterSettingKey( 'title', 'progressbars_list', index );
			view.addRenderAttribute( title_key, 'class', 'title' );
			view.addInlineEditingAttributes( title_key );

			view.addRenderAttribute( index + 'wrapper', {
				'class': [ 'progress-wrapper'],
				'data-value': progress_percentage,
			} );

			view.addRenderAttribute(index, 'class', 'progress-bar bg-' + item.progress_skin );

			#>
			<# if ( 'outer' == settings.text_pos ) { 
				if ( 'yes' == settings.display_percentage ) {
					#>
				<div class="title-wrapper">
					<#
				}
				#>
					<span {{{ view.getRenderAttributeString( title_key ) }}}>{{{ item.title }}}</span><#
				if ( 'yes' == settings.display_percentage ) {#>
					<span class="progress-percentage">{{{ progress_percentage }}}%</span></div>
				<#}
			} #>
			<div {{{ view.getRenderAttributeString( index + 'wrapper' ) }}}>
				<div {{{ view.getRenderAttributeString( index ) }}}>
					<# if ( 'inner' == settings.text_pos ) { #>
						<span {{{ view.getRenderAttributeString( title_key ) }}}>{{{ item.title }}}</span>
					<# } #>
					<# if ( 'yes' == settings.display_percentage && 'inner' == settings.text_pos ) { #>
						<span class="progress-percentage">{{{ progress_percentage }}}%</span>
					<# } #>
				</div>
			</div>
		<#
		} );
		#>
		</div>
		<?php
    }

    protected function render() {
        $atts = $this->get_settings_for_display();
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/progressbars/render-progressbars-elementor.php' );
    }
}
