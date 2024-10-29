<?php
/**
 * Banner Partial
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Alpus_Controls_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

/*
 * Register banner controls.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_banner_controls' ) ) {
    function alpus_elementor_banner_controls( $self, $mode = '' ) {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        $self->start_controls_section(
            'section_banner',
            array(
                'label' => esc_html__( 'Banner', 'alpus-core' ),
            )
        );

        if ( 'insert_number' == $mode ) {
            $self->add_control(
                'banner_insert',
                array(
                    'label'       => esc_html__( 'Banner Index', 'alpus-core' ),
                    'description' => esc_html__( 'Determines which index the banner is inserted in.', 'alpus-core' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '2',
                    'options'     => array(
                        '1'    => '1',
                        '2'    => '2',
                        '3'    => '3',
                        '4'    => '4',
                        '5'    => '5',
                        '6'    => '6',
                        '7'    => '7',
                        '8'    => '8',
                        '9'    => '9',
                        'last' => esc_html__( 'At last', 'alpus-core' ),
                    ),
                )
            );
        }

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'tabs_banner_btn_cat' );

        $repeater->start_controls_tab(
            'tab_banner_content',
            array(
                'label' => esc_html__( 'Content', 'alpus-core' ),
            )
        );

        $repeater->add_control(
            'banner_item_type',
            array(
                'label'           => esc_html__( 'Type', 'alpus-core' ),
                'description'     => esc_html__( 'Choose the content item type.', 'alpus-core' ),
                'label_block'     => true,
                'type'            => Alpus_Controls_Manager::SELECT,
                'default'         => 'text',
                'options'         => array(
                    'text'    => esc_html__( 'Text', 'alpus-core' ),
                    'button'  => esc_html__( 'Button', 'alpus-core' ),
                    'image'   => esc_html__( 'Image', 'alpus-core' ),
                    'divider' => esc_html__( 'Divider', 'alpus-core' ),
                    'hotspot' => esc_html__( 'Hotspot', 'alpus-core' ),
                ),
                'disabledOptions' => array( 'hotspot' ),
            )
        );

        Alpus_Core_Elementor_Extend::purchase_elementor_addon_notice( $repeater, Controls_Manager::RAW_HTML, 'banner', 'banner_item_type', array( 'banner_item_type' => 'hotspot' ) );

        /* Text Item */
        $repeater->add_control(
            'banner_text_content',
            array(
                'label'       => esc_html__( 'Content', 'alpus-core' ),
                'description' => esc_html__( 'Please input the text.', 'alpus-core' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Add Your Text Here', 'alpus-core' ),
                'condition'   => array(
                    'banner_item_type' => 'text',
                ),
                'dynamic'     => array(
                    'active' => true,
                ),
            )
        );

        $repeater->add_control(
            'banner_text_tag',
            array(
                'label'       => esc_html__( 'Tag', 'alpus-core' ),
                'description' => esc_html__( 'Select the HTML Heading tag from H1 to H6 and P tag too.', 'alpus-core' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 'h2',
                'options'     => array(
                    'h1'   => esc_html__( 'H1', 'alpus-core' ),
                    'h2'   => esc_html__( 'H2', 'alpus-core' ),
                    'h3'   => esc_html__( 'H3', 'alpus-core' ),
                    'h4'   => esc_html__( 'H4', 'alpus-core' ),
                    'h5'   => esc_html__( 'H5', 'alpus-core' ),
                    'h6'   => esc_html__( 'H6', 'alpus-core' ),
                    'p'    => esc_html__( 'p', 'alpus-core' ),
                    'div'  => esc_html__( 'div', 'alpus-core' ),
                    'span' => esc_html__( 'span', 'alpus-core' ),
                ),
                'condition'   => array(
                    'banner_item_type' => 'text',
                ),
            )
        );

        /* Button */
        $repeater->add_control(
            'banner_btn_text',
            array(
                'label'       => esc_html__( 'Text', 'alpus-core' ),
                'description' => esc_html__( 'Type text that will be shown on button.', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Click here', 'alpus-core' ),
                'dynamic'     => array(
                    'active' => true,
                ),
                'condition'   => array(
                    'banner_item_type' => 'button',
                ),
            )
        );

        $repeater->add_control(
            'banner_btn_link',
            array(
                'label'       => esc_html__( 'Link Url', 'alpus-core' ),
                'description' => esc_html__( 'Input URL where you will move when button is clicked.', 'alpus-core' ),
                'type'        => Controls_Manager::URL,
                'default'     => array(
                    'url' => '#',
                ),
                'dynamic'     => array(
                    'active' => true,
                ),
                'condition'   => array(
                    'banner_item_type' => 'button',
                ),
            )
        );

        alpus_elementor_button_layout_controls( $repeater, 'banner_item_type', 'button' );

        /* Image */
        $repeater->add_control(
            'banner_image',
            array(
                'label'       => esc_html__( 'Choose Image', 'alpus-core' ),
                'description' => esc_html__( 'Upload an image to display.', 'alpus-core' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array(
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ),
                'condition'   => array(
                    'banner_item_type' => 'image',
                ),
            )
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'      => 'banner_image',
                'exclude'   => [ 'custom' ],
                'default'   => 'full',
                'separator' => 'none',
                'condition' => array(
                    'banner_item_type' => 'image',
                ),
            )
        );

        $repeater->add_control(
            'img_link',
            array(
                'label'       => esc_html__( 'Link', 'alpus-core' ),
                'description' => esc_html__( 'Determines the URL which the picture will link to.', 'alpus-core' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'alpus-core' ),
                'condition'   => array(
                    'banner_item_type' => 'image',
                ),
                'show_label'  => false,
            )
        );

        $repeater->add_responsive_control(
            'banner_divider_width',
            array(
                'label'       => esc_html__( 'Width', 'alpus-core' ),
                'description' => esc_html__( 'Controls the width of the divider.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => 50,
                ),
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'condition'   => array(
                    'banner_item_type' => 'divider',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}} .divider' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $repeater->add_responsive_control(
            'banner_divider_height',
            array(
                'label'       => esc_html__( 'Height', 'alpus-core' ),
                'description' => esc_html__( 'Controls the height of the divider.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => 4,
                ),
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'condition'   => array(
                    'banner_item_type' => 'divider',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}} .divider' => 'border-top-width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $repeater->add_control(
            'banner_item_display',
            array(
                'label'       => esc_html__( 'Inline Item', 'alpus-core' ),
                'description' => esc_html__( 'Choose the display type of content item.', 'alpus-core' ),
                'separator'   => 'before',
                'type'        => Controls_Manager::SWITCHER,
                'condition'   => array( 'banner_item_type!' => 'hotspot' ),
            )
        );

        $repeater->add_control(
            'banner_item_aclass',
            array(
                'label'       => esc_html__( 'Custom Class', 'alpus-core' ),
                'description' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'alpus-core' ),
                'type'        => Controls_Manager::TEXT,
                'condition'   => array( 'banner_item_type!' => 'hotspot' ),
            )
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'tab_banner_style',
            array(
                'label' => esc_html__( 'Style', 'alpus-core' ),
            )
        );

        $repeater->add_control(
            'banner_text_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of text.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'condition'   => array(
                    'banner_item_type' => 'text',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.text, .elementor-element-{{ID}} {{CURRENT_ITEM}} .text' => 'color: {{VALUE}};',
                ),
            )
        );
        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'banner_text_typo',
                'condition' => array(
                    'banner_item_type!' => array( 'image', 'divider', 'hotspot' ),
                ),
                'selector'  => '.elementor-element-{{ID}} {{CURRENT_ITEM}}.text, .elementor-element-{{ID}} {{CURRENT_ITEM}} .text, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn, .elementor-element-{{ID}} {{CURRENT_ITEM}} .btn',
            )
        );

        $repeater->add_control(
            'divider_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of divider.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'condition'   => array(
                    'banner_item_type' => 'divider',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}} .divider' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $repeater->add_responsive_control(
            'banner_image_width',
            array(
                'label'       => esc_html__( 'Width', 'alpus-core' ),
                'description' => esc_html__( 'Set the width the image should take up.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'condition'   => array(
                    'banner_item_type' => 'image',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .banner-item{{CURRENT_ITEM}}.image, .elementor-element-{{ID}} .banner-item{{CURRENT_ITEM}} .image' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $repeater->add_control(
            'banner_btn_border_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'alpus-core' ),
                'description' => esc_html__( 'Controls the border radius.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    '%',
                ),
                'condition'   => array(
                    'banner_item_type!' => array( 'text', 'hotspot', 'button' ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}} img, .elementor-element-{{ID}} {{CURRENT_ITEM}}.divider-wrap, .elementor-element-{{ID}} {{CURRENT_ITEM}} .divider' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $repeater->add_responsive_control(
            'banner_item_margin',
            array(
                'label'       => esc_html__( 'Margin', 'alpus-core' ),
                'description' => esc_html__( 'Controls the margin of item.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%', 'em' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'banner_item_type!' => 'hotspot',
                ),
            )
        );

        $repeater->add_responsive_control(
            'banner_item_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'description' => esc_html__( 'Controls the padding of item.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%', 'em' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'banner_item_type' => 'button',
                ),
            )
        );

        $repeater->add_responsive_control(
            'banner_item_border_width',
            array(
                'label'       => esc_html__( 'Border Width', 'alpus-core' ),
                'description' => esc_html__( 'Controls the border width of item.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%', 'em' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'   => array(
                    'banner_item_type' => 'button',
                    'button_type!'     => array( 'btn-link', 'btn-gradient' ),
                ),
            )
        );

        $repeater->add_responsive_control(
            'banner_button_underline_spacing',
            array(
                'label'       => esc_html__( 'Underline Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the spacing between label and underline.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 30,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn:after' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'banner_item_type' => 'button',
                    'button_type'      => 'btn-link',
                    'link_hover_type!' => '',
                ),
            )
        );

        $repeater->add_responsive_control(
            'banner_button_icon_space',
            array(
                'label'       => esc_html__( 'Icon Spacing', 'alpus-core' ),
                'description' => esc_html__( 'Controls the spacing between label and icon.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem', 'em' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-icon-left:not(.btn-reveal-left) i'                                                                                                                                      => "margin-{$right}: {{SIZE}}{{UNIT}}; margin-{$left}: 0;",
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-icon-right:not(.btn-reveal-right) i'                                                                                                                                    => "margin-{$left}: {{SIZE}}{{UNIT}};",
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-left:hover i, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-left:active i, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-left:focus i'           => "margin-{$right}: {{SIZE}}{{UNIT}};",
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-right:hover i, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-right:active i, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-right:focus i'        => "margin-{$left}: {{SIZE}}{{UNIT}};",
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-icon-left:not(.btn-reveal-left) svg'                                                                                                                                    => "margin-{$right}: {{SIZE}}{{UNIT}}; margin-{$left}: 0;",
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-icon-right:not(.btn-reveal-right) svg'                                                                                                                                  => "margin-{$left}: {{SIZE}}{{UNIT}};",
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-left:hover svg, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-left:active svg, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-left:focus svg'     => "margin-{$right}: {{SIZE}}{{UNIT}};",
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-right:hover svg, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-right:active svg, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn-reveal-right:focus svg'  => "margin-{$left}: {{SIZE}}{{UNIT}};",
                ),
                'condition'   => array(
                    'banner_item_type' => 'button',
                    'show_icon'        => 'yes',
                ),
            )
        );

        $repeater->add_responsive_control(
            'banner_button_icon_size',
            array(
                'label'       => esc_html__( 'Icon Size', 'alpus-core' ),
                'description' => esc_html__( 'Controls the size of the icon. In pixels.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'rem', 'em' ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}} i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} {{CURRENT_ITEM}} svg' => 'width: {{SIZE}}{{UNIT}};font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    'banner_item_type' => 'button',
                    'show_icon'        => 'yes',
                ),
            )
        );

        $repeater->add_control(
            '_animation',
            array(
                'label'              => esc_html__( 'Entrance Animation', 'alpus-core' ),
                'description'        => esc_html__( 'Select the type of animation to use on the item.', 'alpus-core' ),
                'type'               => Controls_Manager::ANIMATION,
                'frontend_available' => true,
                'separator'          => 'before',
                'condition'          => array( 'banner_item_type!' => 'hotspot' ),
            )
        );

        $repeater->add_control(
            'animation_duration',
            array(
                'label'        => esc_html__( 'Animation Duration', 'alpus-core' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => '',
                'options'      => array(
                    'slow' => esc_html__( 'Slow', 'alpus-core' ),
                    ''     => esc_html__( 'Normal', 'alpus-core' ),
                    'fast' => esc_html__( 'Fast', 'alpus-core' ),
                ),
                'prefix_class' => 'animated-',
                'condition'    => array(
                    '_animation!'       => '',
                    'banner_item_type!' => 'hotspot',
                ),
            )
        );

        $repeater->add_control(
            '_animation_delay',
            array(
                'label'              => esc_html__( 'Animation Delay', 'alpus-core' ) . ' (ms)',
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0,
                'step'               => 100,
                'condition'          => array(
                    '_animation!'       => '',
                    'banner_item_type!' => 'hotspot',
                ),
                'render_type'        => 'none',
                'frontend_available' => true,
            )
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'banner_item_floating',
            array(
                'label' => esc_html__( 'Floating', 'alpus-core' ),
            )
        );

        Alpus_Core_Elementor_Extend::purchase_elementor_addon_notice( $repeater, Controls_Manager::RAW_HTML, 'banner', 'banner_item_floating', '' );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $presets = array(
            array(
                'banner_item_type'    => 'text',
                'banner_item_display' => '',
                'banner_text_content' => sprintf( esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nummy nibh %seuismod tincidunt ut laoreet dolore magna aliquam erat volutpat.', 'alpus-core' ), '<br/>' ),
                'banner_text_tag'     => 'p',
            ),
            array(
                'banner_item_type'    => 'button',
                'banner_item_display' => 'yes',
                'banner_btn_text'     => esc_html__( 'Click here', 'alpus-core' ),
                'button_type'         => '',
                'button_skin'         => 'btn-white',
            ),
        );

        alpus_elementor_banner_layout_controls( $self );

        $self->end_controls_section();

        $self->start_controls_section(
            'section_banner_content',
            array(
                'label' => esc_html__( 'Banner Content', 'alpus-core' ),
            )
        );

        $self->add_responsive_control(
            'banner_text_align',
            array(
                'label'       => esc_html__( 'Alignment', 'alpus-core' ),
                'description' => esc_html__( 'Select the content\'s alignment.', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
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
                    '.elementor-element-{{ID}} .banner-content' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'banner_item_list',
            array(
                'label'       => esc_html__( 'Content Items', 'alpus-core' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $presets,
                'title_field' => sprintf( '{{{ banner_item_type == "hotspot" ? \'%6$s\' : ( banner_item_type == "text" ? \'%1$s\' : ( banner_item_type == "image" ? \'%2$s\' : ( banner_item_type == "button" ? \'%3$s\' : \'%4$s\' ) ) ) }}}  {{{ banner_item_type == "hotspot" ? \'%7$s\' : ( banner_item_type == "text" ? banner_text_content : ( banner_item_type == "image" ? banner_image[\'url\'] : ( banner_item_type == "button" ?  banner_btn_text : \'%5$s\' ) ) ) }}}', '<i class="eicon-t-letter"></i>', '<i class="eicon-image"></i>', '<i class="eicon-button"></i>', '<i class="eicon-divider"></i>', esc_html__( 'Divider', 'alpus-core' ), '<i class="eicon-image-hotspot"></i>', esc_html__( 'Hotspot', 'alpus-core' ) ),
            )
        );

        $self->end_controls_section();

        /* Banner Style */
        alpus_elementor_banner_style_controls( $self );
    }
}

/*
 * Render banner.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_products_render_banner' ) ) {
    function alpus_products_render_banner( $self, $atts ) {
        $atts['self'] = $self;
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/banner/render-banner-elementor.php' );
    }
}

/*
 * Register elementor layout controls for section & widget banner.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_banner_layout_controls' ) ) {
    function alpus_elementor_banner_layout_controls( $self, $widget = true ) {
        $self->add_responsive_control(
            'banner_height',
            array(
                'label'       => esc_html__( 'Height', 'alpus-core' ),
                'description' => esc_html__( 'Controls height value of banner.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                    '%',
                    'vh',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 700,
                    ),
                ),
                'selectors'   => $widget ? array(
                    '.elementor-element-{{ID}} .banner' => 'height:{{SIZE}}{{UNIT}};',
                ) : array(
                    '.elementor .elementor-element-{{ID}}'             => 'height:{{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} > .elementor-container' => 'height:{{SIZE}}{{UNIT}};',
                ),
            )
        );

        $self->add_responsive_control(
            'banner_min_height',
            array(
                'label'       => esc_html__( 'Min Height', 'alpus-core' ),
                'description' => esc_html__( 'Controls min height value of banner.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                    '%',
                    'vh',
                ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 700,
                    ),
                    'rem' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    '%'   => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    'vh'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => $widget ? array(
                    '.elementor-element-{{ID}} .banner' => 'min-height:{{SIZE}}{{UNIT}};',
                ) : array(
                    '.elementor .elementor-element-{{ID}}'             => 'min-height:{{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} > .elementor-container' => 'min-height:{{SIZE}}{{UNIT}};',
                ),
            )
        );

        $self->add_responsive_control(
            'banner_max_height',
            array(
                'label'       => esc_html__( 'Max Height', 'alpus-core' ),
                'description' => esc_html__( 'Controls max height value of banner.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                    '%',
                    'vh',
                ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 700,
                    ),
                ),
                'selectors'   => $widget ? array(
                    '{{WRAPPER}}, .elementor-element-{{ID}} .banner, .elementor-element-{{ID}} img' => 'max-height:{{SIZE}}{{UNIT}};overflow:hidden;',
                ) : array(
                    '.elementor .elementor-element-{{ID}}'             => 'max-height:{{SIZE}}{{UNIT}};',
                    '.elementor-element-{{ID}} > .elementor-container' => 'max-height:{{SIZE}}{{UNIT}};',
                ),
            )
        );

        if ( $widget ) {
            $self->add_control(
                'banner_wrap',
                array(
                    'label'       => esc_html__( 'Wrap with', 'alpus-core' ),
                    'description' => esc_html__( 'Choose to wrap banner content in Fullscreen banner.', 'alpus-core' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'options'     => array(
                        ''                => esc_html__( 'None', 'alpus-core' ),
                        'container'       => esc_html__( 'Container', 'alpus-core' ),
                        'container-fluid' => esc_html__( 'Container Fluid', 'alpus-core' ),
                    ),
                )
            );
        }

        $self->add_control(
            'banner_background_color',
            array(
                'label'       => esc_html__( 'Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the background color of banner.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#eee',
                'selectors'   => array(
                    '.elementor-element-{{ID}} .banner' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $self->add_control(
            'banner_background_image',
            array(
                'label'       => esc_html__( 'Choose Image', 'alpus-core' ),
                'description' => esc_html__( 'Upload an image to display.', 'alpus-core' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => array(
                    'url' => defined( 'ALPUS_ASSETS' ) ? ( ALPUS_ASSETS . '/images/placeholders/banner-placeholder.jpg' ) : \Elementor\Utils::get_placeholder_image_src(),
                ),
            )
        );

        $self->add_responsive_control(
            'banner_img_pos',
            array(
                'label'       => esc_html__( 'Image Position (%)', 'alpus-core' ),
                'description' => esc_html__( 'Changes image position when image is larger than render area.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    '%' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .banner-img img' => 'object-position: {{SIZE}}%;',
                ),
                'condition'   => $widget ? array(
                    'parallax!'         => 'yes',
                    'background_effect' => '',
                ) : array(
                    'parallax!'            => 'yes',
                    'background_effect'    => '',
                    'video_banner_switch!' => 'yes',
                ),
            )
        );
    }
}

/*
 * Register elementor style controls for section & widget banner.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_banner_style_controls' ) ) {
    function alpus_elementor_banner_style_controls( $self, $condition_value = '', $widget = true ) {
        if ( $widget ) {
            $self->start_controls_section(
                'section_banner_style',
                array(
                    'label' => esc_html__( 'Banner Wrapper', 'alpus-core' ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                )
            );

            $self->add_control(
                'stretch_height',
                array(
                    'type'        => Controls_Manager::SWITCHER,
                    'label'       => esc_html__( 'Stretch height as Parent\'s', 'alpus-core' ),
                    'description' => esc_html__( 'You can make your banner height full of its parent', 'alpus-core' ),
                )
            );

            $self->end_controls_section();

            $self->start_controls_section(
                'banner_layer_layout',
                array(
                    'label' => esc_html__( 'Banner Content', 'alpus-core' ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                )
            );

            $self->add_responsive_control(
                'banner_width',
                array(
                    'label'       => esc_html__( 'Width', 'alpus-core' ),
                    'description' => esc_html__( 'Changes banner content width.', 'alpus-core' ),
                    'type'        => Controls_Manager::SLIDER,
                    'size_units'  => array( 'px', '%' ),
                    'range'       => array(
                        'px' => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 1000,
                        ),
                        '%'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                    ),
                    'default'     => array(
                        'unit' => '%',
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}} .banner .banner-content' => 'max-width:{{SIZE}}{{UNIT}}; width: 100%',
                    ),
                )
            );

            $self->add_responsive_control(
                'banner_content_padding',
                array(
                    'label'       => esc_html__( 'Padding', 'alpus-core' ),
                    'description' => esc_html__( 'Controls padding of banner content.', 'alpus-core' ),
                    'type'        => Controls_Manager::DIMENSIONS,
                    'default'     => array(
                        'unit' => 'px',
                    ),
                    'size_units'  => array( 'px', '%' ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}} .banner .banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $self->add_control(
                'banner_origin',
                array(
                    'label'       => esc_html__( 'Origin X, Y', 'alpus-core' ),
                    'description' => esc_html__( 'Set base point of banner content to determine content position.', 'alpus-core' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 't-mc',
                    'options'     => array(
                        't-none' => esc_html__( '------ ------', 'alpus-core' ),
                        't-m'    => esc_html__( '------ Center', 'alpus-core' ),
                        't-c'    => esc_html__( 'Center ------', 'alpus-core' ),
                        't-mc'   => esc_html__( 'Center Center', 'alpus-core' ),
                    ),
                )
            );

            $self->start_controls_tabs( 'banner_position_tabs' );

            $self->start_controls_tab(
                'banner_pos_left_tab',
                array(
                    'label' => esc_html__( 'Left', 'alpus-core' ),
                )
            );

            $self->add_responsive_control(
                'banner_left',
                array(
                    'label'       => esc_html__( 'Left Offset', 'alpus-core' ),
                    'description' => esc_html__( 'Set Left position of banner content.', 'alpus-core' ),
                    'type'        => Controls_Manager::SLIDER,
                    'size_units'  => array(
                        'px',
                        'rem',
                        '%',
                        'vw',
                    ),
                    'range'       => array(
                        'px'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 500,
                        ),
                        'rem' => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                        '%'   => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                        'vw'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                    ),
                    'default'     => array(
                        'size' => 50,
                        'unit' => '%',
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}} .banner .banner-content' => 'left:{{SIZE}}{{UNIT}};',
                    ),
                )
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                'banner_pos_top_tab',
                array(
                    'label' => esc_html__( 'Top', 'alpus-core' ),
                )
            );

            $self->add_responsive_control(
                'banner_top',
                array(
                    'label'       => esc_html__( 'Top Offset', 'alpus-core' ),
                    'description' => esc_html__( 'Set Top position of banner content.', 'alpus-core' ),
                    'type'        => Controls_Manager::SLIDER,
                    'size_units'  => array(
                        'px',
                        'rem',
                        '%',
                        'vh',
                    ),
                    'range'       => array(
                        'px'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 500,
                        ),
                        'rem' => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                        '%'   => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                        'vh'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                    ),
                    'default'     => array(
                        'size' => 50,
                        'unit' => '%',
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}} .banner .banner-content' => 'top:{{SIZE}}{{UNIT}};',
                    ),
                )
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                'banner_pos_right_tab',
                array(
                    'label' => esc_html__( 'Right', 'alpus-core' ),
                )
            );

            $self->add_responsive_control(
                'banner_right',
                array(
                    'label'       => esc_html__( 'Right Offset', 'alpus-core' ),
                    'description' => esc_html__( 'Set Right position of banner content.', 'alpus-core' ),
                    'type'        => Controls_Manager::SLIDER,
                    'size_units'  => array(
                        'px',
                        'rem',
                        '%',
                        'vw',
                    ),
                    'range'       => array(
                        'px'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 500,
                        ),
                        'rem' => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                        '%'   => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                        'vw'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}} .banner .banner-content' => 'right:{{SIZE}}{{UNIT}};',
                    ),
                )
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                'banner_pos_bottom_tab',
                array(
                    'label' => esc_html__( 'Bottom', 'alpus-core' ),
                )
            );

            $self->add_responsive_control(
                'banner_bottom',
                array(
                    'label'       => esc_html__( 'Bottom Offset', 'alpus-core' ),
                    'description' => esc_html__( 'Set Bottom position of banner content.', 'alpus-core' ),
                    'type'        => Controls_Manager::SLIDER,
                    'size_units'  => array(
                        'px',
                        'rem',
                        '%',
                        'vh',
                    ),
                    'range'       => array(
                        'px'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 500,
                        ),
                        'rem' => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                        '%'   => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                        'vh'  => array(
                            'step' => 1,
                            'min'  => 0,
                            'max'  => 100,
                        ),
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}} .banner .banner-content' => 'bottom:{{SIZE}}{{UNIT}};',
                    ),
                )
            );

            $self->end_controls_tab();

            $self->end_controls_tabs();

            $self->end_controls_section();
        }

        $self->start_controls_section(
            'banner_effect',
            $widget ? array(
                'label' => esc_html__( 'Banner Effect', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ) : array(
                'label'     => alpus_elementor_panel_heading( esc_html__( 'Banner Effect', 'alpus-core' ) ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    $condition_value       => 'banner',
                    'video_banner_switch!' => 'yes',
                ),
            )
        );

        if ( $widget ) {
            $self->add_control(
                'banner_image_effect',
                array(
                    'label' => esc_html__( 'Image Effect', 'alpus-core' ),
                    'type'  => Controls_Manager::HEADING,
                )
            );
        }

        $self->add_control(
            'overlay',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => esc_html__( 'Hover Effect', 'alpus-core' ),
                'description' => esc_html__( 'Note: Please avoid giving Hover Effect and Background Effect together.', 'alpus-core' ),
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
                'condition'   => array(
                    'parallax!' => 'yes',
                ),
            )
        );

        $self->add_control(
            'banner_overlay_color',
            array(
                'label'       => esc_html__( 'Hover Effect Color', 'alpus-core' ),
                'description' => esc_html__( 'Choose banner overlay color on hover.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .banner figure:after, .elementor-element-{{ID}} .overlay-effect:after,.elementor-element-{{ID}} .overlay-effect:before' => 'background-color: {{VALUE}};',
                ),
                'condition'   => array(
                    'overlay!'  => array( '', 'zoom', 'effect-5', 'effect-6', 'effect-7' ),
                    'parallax!' => 'yes',
                ),
            )
        );

        $self->add_control(
            'banner_overlay_color1',
            array(
                'label'       => esc_html__( 'Hover Effect Color', 'alpus-core' ),
                'description' => esc_html__( 'Choose banner overlay color on hover.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .overlay-effect-5:before, .elementor-element-{{ID}} .overlay-effect-7:before, .elementor-element-{{ID}} .overlay-effect-6+figure:before' => 'background-color: {{VALUE}};',
                ),
                'condition'   => array(
                    'overlay'   => array( 'effect-5', 'effect-6', 'effect-7' ),
                    'parallax!' => 'yes',
                ),
            )
        );

        $self->add_control(
            'overlay_border_color',
            array(
                'label'       => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Choose overlay border color on hover.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .overlay-effect-5:after, .elementor-element-{{ID}} .overlay-effect-6:after, .elementor-element-{{ID}} .overlay-effect-6:before, .elementor-element-{{ID}} .overlay-effect-7:after' => 'border-color: {{VALUE}};',
                ),
                'condition'   => array(
                    'overlay'   => array( 'effect-5', 'effect-6', 'effect-7' ),
                    'parallax!' => 'yes',
                ),
            )
        );

        $self->add_control(
            'border_angle',
            array(
                'type'      => Controls_Manager::SLIDER,
                'label'     => __( 'Border Angle', 'alpus-core' ),
                'selectors' => array(
                    '.elementor-element-{{ID}} .overlay-effect-5:after'                        => 'transform: rotate3d(0,0,1,{{SIZE}}deg) scale3d(1,0,1);',
                    '.elementor-element-{{ID}} .overlay-wrapper:hover .overlay-effect-5:after' => 'transform: rotate3d(0,0,1,{{SIZE}}deg) scale3d(1,1,1);',
                ),
                'condition' => array(
                    'overlay'   => 'effect-5',
                    'parallax!' => 'yes',
                ),
            )
        );

        $self->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'dropdown_box_shadow',
                'selector'  => '.elementor-element-{{ID}} .overlay-effect-7:after',
                'condition' => array(
                    'overlay'   => 'effect-7',
                    'parallax!' => 'yes',
                ),
            )
        );

        $self->add_control(
            'overlay_filter',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => esc_html__( 'Hover Filter Effect', 'alpus-core' ),
                'description' => esc_html__( 'Choose banner filter effect on hover.', 'alpus-core' ),
                'options'     => array(
                    ''                   => esc_html__( 'No', 'alpus-core' ),
                    'blur(4px)'          => esc_html__( 'Blur', 'alpus-core' ),
                    'brightness(1.5)'    => esc_html__( 'Brightness', 'alpus-core' ),
                    'contrast(1.5)'      => esc_html__( 'Contrast', 'alpus-core' ),
                    'grayscale(1)'       => esc_html__( 'Greyscale', 'alpus-core' ),
                    'hue-rotate(270deg)' => esc_html__( 'Hue Rotate', 'alpus-core' ),
                    'opacity(0.5)'       => esc_html__( 'Opacity', 'alpus-core' ),
                    'saturate(3)'        => esc_html__( 'Saturate', 'alpus-core' ),
                    'sepia(0.5)'         => esc_html__( 'Sepia', 'alpus-core' ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .banner img'       => 'transition: transform 1s, filter 1s;',
                    '.elementor-element-{{ID}} .banner:hover img' => 'filter: {{VALUE}};',
                ),
                'condition'   => array(
                    'parallax!' => 'yes',
                ),
            )
        );

        $self->add_control(
            'background_effect',
            array(
                'type'            => Alpus_Controls_Manager::SELECT,
                'label'           => esc_html__( 'Backgrund Effect', 'alpus-core' ),
                'options'         => array(
                    ''                   => esc_html__( 'No', 'alpus-core' ),
                    'kenBurnsToRight'    => esc_html__( 'kenBurnsRight', 'alpus-core' ),
                    'kenBurnsToLeft'     => esc_html__( 'kenBurnsLeft', 'alpus-core' ),
                    'kenBurnsToLeftTop'  => esc_html__( 'kenBurnsLeftTop', 'alpus-core' ),
                    'kenBurnsToRightTop' => esc_html__( 'kenBurnsRightTop', 'alpus-core' ),
                ),
                'disabledOptions' => array( 'kenBurnsToRight', 'kenBurnsToLeft', 'kenBurnsToLeftTop', 'kenBurnsToRightTop' ),
                'description'     => esc_html__( 'Note: Please avoid giving Hover Effect and Background Effect together.', 'alpus-core' ),
                'condition'       => array(
                    'parallax!' => 'yes',
                ),
            )
        );

        Alpus_Core_Elementor_Extend::purchase_elementor_addon_notice( $self, Controls_Manager::RAW_HTML, 'banner', 'background_effect', array( 'background_effect!' => '' ) );

        $self->add_control(
            'particle_effect',
            array(
                'type'            => Alpus_Controls_Manager::SELECT,
                'label'           => esc_html__( 'Particle Effects', 'alpus-core' ),
                'description'     => esc_html__( 'Shows animating particles over banner image. Choose from Snowfall, Sparkle.', 'alpus-core' ),
                'options'         => array(
                    ''         => esc_html__( 'No', 'alpus-core' ),
                    'snowfall' => esc_html__( 'Snowfall', 'alpus-core' ),
                    'sparkle'  => esc_html__( 'Sparkle', 'alpus-core' ),
                ),
                'disabledOptions' => array( 'snowfall', 'sparkle' ),
            )
        );

        Alpus_Core_Elementor_Extend::purchase_elementor_addon_notice( $self, Controls_Manager::RAW_HTML, 'banner', 'particle_effect', array( 'particle_effect!' => '' ) );

        $self->add_control(
            'parallax',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'Enable Parallax', 'alpus-core' ),
                'description' => esc_html__( 'Set to enable parallax effect for banner.', 'alpus-core' ),
                'condition'   => array(
                    'overlay'           => '',
                    'overlay_filter'    => '',
                    'background_effect' => '',
                ),
            )
        );

        Alpus_Core_Elementor_Extend::purchase_elementor_addon_notice( $self, Controls_Manager::RAW_HTML, 'banner', 'parallax', array( 'parallax' => 'yes' ) );

        if ( $widget ) {
            $self->add_control(
                'banner_content_effect',
                array(
                    'label'     => esc_html__( 'Content Effect', 'alpus-core' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                )
            );

            $self->add_control(
                '_content_animation',
                array(
                    'label'              => esc_html__( 'Content Entrance Animation', 'alpus-core' ),
                    'description'        => esc_html__( 'Set entrance animation for entire banner content.', 'alpus-core' ),
                    'type'               => Controls_Manager::ANIMATION,
                    'frontend_available' => true,
                )
            );

            $self->add_control(
                'content_animation_duration',
                array(
                    'label'        => esc_html__( 'Animation Duration', 'alpus-core' ),
                    'description'  => esc_html__( 'Determine how long entrance animation should be shown.', 'alpus-core' ),
                    'type'         => Controls_Manager::SELECT,
                    'default'      => '',
                    'options'      => array(
                        'slow' => esc_html__( 'Slow', 'alpus-core' ),
                        ''     => esc_html__( 'Normal', 'alpus-core' ),
                        'fast' => esc_html__( 'Fast', 'alpus-core' ),
                    ),
                    'prefix_class' => 'animated-',
                    'condition'    => array(
                        '_content_animation!' => '',
                    ),
                )
            );

            $self->add_control(
                '_content_animation_delay',
                array(
                    'label'              => esc_html__( 'Animation Delay', 'alpus-core' ) . ' (ms)',
                    'description'        => esc_html__( 'Set delay time for content entrance animation.', 'alpus-core' ),
                    'type'               => Controls_Manager::NUMBER,
                    'min'                => 0,
                    'step'               => 100,
                    'condition'          => array(
                        '_content_animation!' => '',
                    ),
                    'render_type'        => 'none',
                    'frontend_available' => true,
                )
            );
        }

        $self->end_controls_section();

        if ( ! $widget ) {
            $self->start_controls_section(
                'section_video_style',
                array(
                    'label'     => alpus_elementor_panel_heading( esc_html__( 'Banner Video', 'alpus-core' ) ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => array(
                        $condition_value      => 'banner',
                        'video_banner_switch' => 'yes',
                        'lightbox'            => 'yes',
                    ),
                )
            );

            $self->add_control(
                'aspect_ratio',
                array(
                    'label'              => esc_html__( 'Aspect Ratio', 'alpus-core' ),
                    'type'               => Controls_Manager::SELECT,
                    'options'            => array(
                        '169' => '16:9',
                        '219' => '21:9',
                        '43'  => '4:3',
                        '32'  => '3:2',
                        '11'  => '1:1',
                        '916' => '9:16',
                    ),
                    'default'            => '169',
                    'prefix_class'       => 'elementor-aspect-ratio-',
                    'frontend_available' => true,
                )
            );

            $self->add_group_control(
                Group_Control_Css_Filter::get_type(),
                array(
                    'name'     => 'video_css_filters',
                    'selector' => '#elementor-lightbox-{{ID}} .elementor-fit-aspect-ratio',
                )
            );

            $self->add_responsive_control(
                'video_border_radius',
                array(
                    'label'      => esc_html__( 'Border Radius', 'alpus-core' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array(
                        'px',
                        '%',
                        'rem',
                    ),
                    'selectors'  => array(
                        '#elementor-lightbox-{{ID}} .elementor-fit-aspect-ratio' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $self->add_control(
                'play_icon_title',
                array(
                    'label'     => esc_html__( 'Play Icon', 'alpus-core' ),
                    'type'      => Controls_Manager::HEADING,
                    'condition' => array(
                        'show_image_overlay' => 'yes',
                        'show_play_icon'     => 'yes',
                    ),
                    'separator' => 'before',
                )
            );

            $self->add_control(
                'play_icon_color',
                array(
                    'label'     => esc_html__( 'Color', 'alpus-core' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '.elementor-element-{{ID}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
                    ),
                    'condition' => array(
                        'show_image_overlay' => 'yes',
                        'show_play_icon'     => 'yes',
                    ),
                )
            );

            $self->add_responsive_control(
                'play_icon_size',
                array(
                    'label'     => esc_html__( 'Size', 'alpus-core' ),
                    'type'      => Controls_Manager::SLIDER,
                    'range'     => array(
                        'px' => array(
                            'min' => 10,
                            'max' => 300,
                        ),
                    ),
                    'selectors' => array(
                        '.elementor-element-{{ID}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
                    ),
                    'condition' => array(
                        'show_image_overlay' => 'yes',
                        'show_play_icon'     => 'yes',
                    ),
                )
            );

            $self->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                array(
                    'name'           => 'play_icon_text_shadow',
                    'selector'       => '.elementor-element-{{ID}} .elementor-custom-embed-play i',
                    'fields_options' => array(
                        'text_shadow_type' => array(
                            'label' => _x( 'Shadow', 'Text Shadow Control', 'alpus-core' ),
                        ),
                    ),
                    'condition'      => array(
                        'show_image_overlay' => 'yes',
                        'show_play_icon'     => 'yes',
                    ),
                )
            );

            $self->end_controls_section();

            $self->start_controls_section(
                'section_lightbox_style',
                array(
                    'label'     => esc_html__( 'Lightbox', 'alpus-core' ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => array(
                        'use_as'              => 'banner',
                        'video_banner_switch' => 'yes',
                        'show_image_overlay'  => 'yes',
                        'image_overlay[url]!' => '',
                        'lightbox'            => 'yes',
                    ),
                )
            );

            $self->add_control(
                'lightbox_color',
                array(
                    'label'     => esc_html__( 'Background Color', 'alpus-core' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '#elementor-lightbox-{{ID}}' => 'background-color: {{VALUE}};',
                    ),
                )
            );

            $self->add_control(
                'lightbox_ui_color',
                array(
                    'label'     => esc_html__( 'UI Color', 'alpus-core' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '#elementor-lightbox-{{ID}} .dialog-lightbox-close-button' => 'color: {{VALUE}}',
                    ),
                )
            );

            $self->add_control(
                'lightbox_ui_color_hover',
                array(
                    'label'     => esc_html__( 'UI Hover Color', 'alpus-core' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
                    ),
                    'separator' => 'after',
                )
            );

            $self->add_control(
                'lightbox_video_width',
                array(
                    'label'     => esc_html__( 'Content Width', 'alpus-core' ),
                    'type'      => Controls_Manager::SLIDER,
                    'default'   => array(
                        'unit' => '%',
                    ),
                    'range'     => array(
                        '%' => array(
                            'min' => 30,
                        ),
                    ),
                    'selectors' => array(
                        '(desktop+)#elementor-lightbox-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
                    ),
                )
            );

            $self->add_control(
                'lightbox_content_position',
                array(
                    'label'                => esc_html__( 'Content Position', 'alpus-core' ),
                    'type'                 => Controls_Manager::SELECT,
                    'frontend_available'   => true,
                    'options'              => array(
                        ''    => esc_html__( 'Center', 'alpus-core' ),
                        'top' => esc_html__( 'Top', 'alpus-core' ),
                    ),
                    'selectors'            => array(
                        '#elementor-lightbox-{{ID}} .elementor-video-container' => '{{VALUE}}; transform: translateX(-50%);',
                    ),
                    'selectors_dictionary' => array(
                        'top' => 'top: 60px',
                    ),
                )
            );

            $self->add_responsive_control(
                'lightbox_content_animation',
                array(
                    'label'              => esc_html__( 'Entrance Animation', 'alpus-core' ),
                    'type'               => Controls_Manager::ANIMATION,
                    'frontend_available' => true,
                )
            );

            $self->end_controls_section();

            $self->update_control(
                'color_link',
                array(
                    'selectors' => array(
                        '.elementor-element-{{ID}} a' => 'color: {{VALUE}}',
                    ),
                )
            );

            $self->update_control(
                'color_link_hover',
                array(
                    'selectors' => array(
                        '.elementor-element-{{ID}} a:hover' => 'color: {{VALUE}}',
                    ),
                )
            );
        }
    }
}

/*
 * Register elementor layout controls for column banner layer.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_banner_layer_layout_controls' ) ) {
    function alpus_elementor_banner_layer_layout_controls( $self, $condition_key ) {
        $self->start_controls_section(
            'banner_layer_layout',
            array(
                'label'     => alpus_elementor_panel_heading( esc_html__( 'Banner Layer', 'alpus-core' ) ),
                'tab'       => Controls_Manager::TAB_LAYOUT,
                'condition' => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );
        $self->add_control(
            'banner_text_align',
            array(
                'label'       => esc_html__( 'Text Alignment', 'alpus-core' ),
                'description' => esc_html__( 'Select the content\'s alignment.', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'options'     => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center'  => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'   => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                    'justify' => array(
                        'title' => esc_html__( 'Justify', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-justify',
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                ),
                'condition'   => array(
                    $condition_key => 'banner_layer',
                ),
                'toggle'      => false,
            )
        );

        $self->add_control(
            'banner_origin',
            array(
                'label'       => esc_html__( 'Origin', 'alpus-core' ),
                'description' => esc_html__( 'Set base point of banner content to determine content position.', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    't-m'  => array(
                        'title' => esc_html__( 'Vertical Center', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    't-c'  => array(
                        'title' => esc_html__( 'Horizontal Center', 'alpus-core' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    't-mc' => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-frame-minimize',
                    ),
                ),
                'default'     => 't-mc',
                'condition'   => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->start_controls_tabs( 'banner_position_tabs' );

        $self->start_controls_tab(
            'banner_pos_left_tab',
            array(
                'label'     => esc_html__( 'Left', 'alpus-core' ),
                'condition' => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->add_responsive_control(
            'banner_left',
            array(
                'label'       => esc_html__( 'Left Offset', 'alpus-core' ),
                'description' => esc_html__( 'Set Left position of banner content.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                    '%',
                    'vw',
                ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 500,
                    ),
                    'rem' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    '%'   => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    'vw'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'default'     => array(
                    'size' => 50,
                    'unit' => '%',
                ),
                'selectors'   => array(
                    '{{WRAPPER}}.banner-content,{{WRAPPER}}>.banner-content,{{WRAPPER}}>div>.banner-content' => 'left:{{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->end_controls_tab();

        $self->start_controls_tab(
            'banner_pos_top_tab',
            array(
                'label'     => esc_html__( 'Top', 'alpus-core' ),
                'condition' => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->add_responsive_control(
            'banner_top',
            array(
                'label'       => esc_html__( 'Top Offset', 'alpus-core' ),
                'description' => esc_html__( 'Set Top position of banner content.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                    '%',
                    'vh',
                ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 500,
                    ),
                    'rem' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    '%'   => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    'vh'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'default'     => array(
                    'size' => 50,
                    'unit' => '%',
                ),
                'selectors'   => array(
                    '{{WRAPPER}}.banner-content,{{WRAPPER}}>.banner-content,{{WRAPPER}}>div>.banner-content' => 'top:{{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->end_controls_tab();

        $self->start_controls_tab(
            'banner_pos_right_tab',
            array(
                'label'     => esc_html__( 'Right', 'alpus-core' ),
                'condition' => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->add_responsive_control(
            'banner_right',
            array(
                'label'       => esc_html__( 'Right Offset', 'alpus-core' ),
                'description' => esc_html__( 'Set Right position of banner content.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                    '%',
                    'vw',
                ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 500,
                    ),
                    'rem' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    '%'   => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    'vw'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}}.banner-content,{{WRAPPER}}>.banner-content,{{WRAPPER}}>div>.banner-content' => 'right:{{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->end_controls_tab();

        $self->start_controls_tab(
            'banner_pos_bottom_tab',
            array(
                'label'     => esc_html__( 'Bottom', 'alpus-core' ),
                'condition' => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->add_responsive_control(
            'banner_bottom',
            array(
                'label'       => esc_html__( 'Bottom Offset', 'alpus-core' ),
                'description' => esc_html__( 'Set Bottom position of banner content.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array(
                    'px',
                    'rem',
                    '%',
                    'vw',
                ),
                'range'       => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 500,
                    ),
                    'rem' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    '%'   => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    'vw'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}}.banner-content,{{WRAPPER}}>.banner-content,{{WRAPPER}}>div>.banner-content' => 'bottom:{{SIZE}}{{UNIT}};',
                ),
                'condition'   => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->end_controls_tab();

        $self->end_controls_tabs();

        $self->add_responsive_control(
            'banner_width',
            array(
                'label'       => esc_html__( 'Width', 'alpus-core' ),
                'description' => esc_html__( 'Changes banner content width.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%' ),
                'range'       => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 1000,
                    ),
                    '%'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                ),
                'separator'   => 'before',
                'selectors'   => array(
                    '{{WRAPPER}}.banner-content,{{WRAPPER}}>.banner-content,{{WRAPPER}}>div>.banner-content' => 'max-width:{{SIZE}}{{UNIT}};width: 100%;',
                ),
                'condition'   => array(
                    $condition_key => 'banner_layer',
                ),
            )
        );

        $self->end_controls_section();
    }
}
