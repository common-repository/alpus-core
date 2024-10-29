<?php
/**
 * Testimonial Partial
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

/**
 * Register elementor style controls for testimonials.
 *
 * @since 1.0
 */
function alpus_elementor_testimonial_style_controls( $self ) {
    $left  = is_rtl() ? 'right' : 'left';
    $right = 'left' == $left ? 'right' : 'left';

    $self->start_controls_section(
        'testimonial_style',
        array(
            'label' => esc_html__( 'Testimonial Style', 'alpus-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        )
    );

    $self->add_control(
        'testimonial_bg_color',
        array(
            'label'     => esc_html__( 'Background Color', 'alpus-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '.elementor-element-{{ID}} .testimonial' => 'background-color: {{VALUE}};',
            ),
            'condition' => array(
                'testimonial_type!' => 'simple',
            ),
        )
    );

    $self->add_control(
        'testimonial_aside_spacing',
        array(
            'label'       => esc_html__( 'Between Spacing', 'alpus-core' ),
            'description' => esc_html__( 'Determine spacing between avatar and content.', 'alpus-core' ),
            'type'        => Controls_Manager::SLIDER,
            'size_units'  => array(
                'px',
                'rem',
            ),
            'selectors'   => array(
                '.elementor-element-{{ID}} .testimonial-aside .content'          => "margin-{$left}: {{SIZE}}{{UNIT}};",
                '.elementor-element-{{ID}} .testimonial-aside.inversed .content' => "margin-{$left}: 0;margin-{$right}: {{SIZE}}{{UNIT}};",
            ),
            'condition'   => array(
                'testimonial_type' => 'aside',
            ),
        )
    );

    $self->add_control(
        'testimonial_pd',
        array(
            'label'       => esc_html__( 'Padding', 'alpus-core' ),
            'description' => esc_html__( 'Controls the padding of testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => array(
                'px',
                'em',
                'rem',
            ),
            'selectors'   => array(
                '.elementor-element-{{ID}} .testimonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $self->add_control(
        'testimonial_border_width',
        array(
            'label'       => esc_html__( 'Border Width', 'alpus-core' ),
            'description' => esc_html__( 'Controls the border width of testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => array(
                'px',
            ),
            'selectors'   => array(
                '.elementor-element-{{ID}} .testimonial' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
            ),
        )
    );

    $self->add_control(
        'testimonial_border_radius',
        array(
            'label'       => esc_html__( 'Border Radius', 'alpus-core' ),
            'description' => esc_html__( 'Controls the border radius of testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => array(
                'px',
            ),
            'selectors'   => array(
                '{{WRAPPER}} .testimonial' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $self->add_control(
        'testimonial_border_color',
        array(
            'label'       => esc_html__( 'Border Color', 'alpus-core' ),
            'description' => esc_html__( 'Controls the border color of testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::COLOR,
            'selectors'   => array(
                '.elementor-element-{{ID}} .testimonial' => 'border-color: {{VALUE}};',
            ),
        )
    );

    $self->end_controls_section();

    $self->start_controls_section(
        'avatar_style',
        array(
            'label' => esc_html__( 'Avatar', 'alpus-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        )
    );

    $self->add_control(
        'avatar_sz',
        array(
            'label'       => esc_html__( 'Size', 'alpus-core' ),
            'description' => esc_html__( 'Controls the Avatar size.', 'alpus-core' ),
            'type'        => Controls_Manager::SLIDER,
            'size_units'  => array(
                'px',
                'rem',
                'em',
            ),
            'range'       => array(
                'px' => array(
                    'min'  => 1,
                    'max'  => 300,
                    'step' => 1,
                ),
            ),
            'selectors'   => array(
                '.elementor-element-{{ID}} .testimonial .avatar img'                                                                                              => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '.elementor-element-{{ID}} .testimonial-simple .content::after, .elementor-element-{{ID}} .testimonial-simple .content::before'                   => "{$left}: calc(2rem + {{SIZE}}{{UNIT}} / 2 - 1rem); {$right}: auto;",
                '.elementor-element-{{ID}} .testimonial-simple.inversed .content::after, .elementor-element-{{ID}} .testimonial-simple.inversed .content::before' => "{$right}: calc(3rem + {{SIZE}}{{UNIT}} / 2 - 1rem); {$left}: auto;",
                '.elementor-element-{{ID}} .avatar::before'                                                                                                       => 'font-size: {{SIZE}}{{UNIT}};',
            ),
        )
    );

    $self->add_control(
        'avatar_mg',
        array(
            'label'       => esc_html__( 'Margin', 'alpus-core' ),
            'description' => esc_html__( 'Controls the Avatar margin.', 'alpus-core' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => array(
                'px',
                'em',
                'rem',
            ),
            'selectors'   => array(
                '.elementor-element-{{ID}} .testimonial .avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $self->end_controls_section();

    $self->start_controls_section(
        'title_style',
        array(
            'label'     => esc_html__( 'Comment Title', 'alpus-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => array(
                'testimonial_type!' => 'simple',
            ),
        )
    );

    $self->add_control(
        'title_color',
        array(
            'label'       => esc_html__( 'Color', 'alpus-core' ),
            'description' => esc_html__( 'Controls the Title color.', 'alpus-core' ),
            'type'        => Controls_Manager::COLOR,
            'selectors'   => array(
                '.elementor-element-{{ID}} .comment-title' => 'color: {{VALUE}};',
            ),
        )
    );

    $self->add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'title_typography',
            'label'    => esc_html__( 'Typography', 'alpus-core' ),
            'selector' => '.elementor-element-{{ID}} .comment-title',
        )
    );

    $self->add_control(
        'title_mg',
        array(
            'label'       => esc_html__( 'Margin', 'alpus-core' ),
            'description' => esc_html__( 'Controls the Title margin.', 'alpus-core' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => array(
                'px',
                'em',
                'rem',
            ),
            'selectors'   => array(
                '.elementor-element-{{ID}} .comment-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $self->end_controls_section();

    $self->start_controls_section(
        'comment_style',
        array(
            'label' => esc_html__( 'Comment', 'alpus-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        )
    );

    $self->add_control(
        'comment_color',
        array(
            'label'       => esc_html__( 'Color', 'alpus-core' ),
            'description' => esc_html__( 'Controls the Comment color.', 'alpus-core' ),
            'type'        => Controls_Manager::COLOR,
            'selectors'   => array(
                '.elementor-element-{{ID}} .comment' => 'color: {{VALUE}};',
            ),
        )
    );

    $self->add_control(
        'comment_bg_color',
        array(
            'label'     => esc_html__( 'Background Color', 'alpus-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '.elementor-element-{{ID}} .testimonial-simple .content' => 'background-color: {{VALUE}};',
            ),
            'condition' => array(
                'testimonial_type' => 'simple',
            ),
        )
    );

    $self->add_control(
        'comment_border_color',
        array(
            'label'     => esc_html__( 'Border Color', 'alpus-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '.elementor-element-{{ID}} .testimonial.testimonial-simple .content'        => 'border-color: {{VALUE}};',
                '.elementor-element-{{ID}} .testimonial.testimonial-simple .content::after' => 'background-color: {{VALUE}};',
            ),
            'condition' => array(
                'testimonial_type' => 'simple',
            ),
        )
    );

    $self->add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'comment_typography',
            'label'    => esc_html__( 'Typography', 'alpus-core' ),
            'selector' => '.elementor-element-{{ID}} .comment',
        )
    );

    $self->add_control(
        'comment_pd',
        array(
            'label'      => esc_html__( 'Padding', 'alpus-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array(
                'px',
                'em',
                'rem',
            ),
            'selectors'  => array(
                '.elementor-element-{{ID}} .comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $self->add_control(
        'comment_mg',
        array(
            'label'      => esc_html__( 'Comment Card Spacing', 'alpus-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array(
                'px',
                'rem',
            ),
            'selectors'  => array(
                '.elementor-element-{{ID}} .content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ),
            'condition'  => array(
                'testimonial_type' => 'simple',
            ),
        )
    );

    $self->end_controls_section();

    $self->start_controls_section(
        'name_style',
        array(
            'label' => esc_html__( 'Name', 'alpus-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        )
    );

    $self->add_control(
        'name_color',
        array(
            'label'       => esc_html__( 'Color', 'alpus-core' ),
            'description' => esc_html__( 'Controls the Name color.', 'alpus-core' ),
            'type'        => Controls_Manager::COLOR,
            'selectors'   => array(
                '.elementor-element-{{ID}} .name' => 'color: {{VALUE}};',
            ),
        )
    );

    $self->add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'name_typography',
            'label'    => esc_html__( 'Name', 'alpus-core' ),
            'selector' => '.elementor-element-{{ID}} .name',
        )
    );

    $self->add_control(
        'name_mg',
        array(
            'label'       => esc_html__( 'Margin', 'alpus-core' ),
            'description' => esc_html__( 'Controls the Name margin.', 'alpus-core' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => array(
                'px',
                'em',
                'rem',
            ),
            'selectors'   => array(
                '.elementor-element-{{ID}} .name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $self->end_controls_section();

    $self->start_controls_section(
        'role_style',
        array(
            'label' => esc_html__( 'Role', 'alpus-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        )
    );

    $self->add_control(
        'role_color',
        array(
            'label'       => esc_html__( 'Color', 'alpus-core' ),
            'description' => esc_html__( 'Controls the role color', 'alpus-core' ),
            'type'        => Controls_Manager::COLOR,
            'selectors'   => array(
                '.elementor-element-{{ID}} .role' => 'color: {{VALUE}};',
            ),
        )
    );

    $self->add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'role_typography',
            'label'    => esc_html__( 'Role', 'alpus-core' ),
            'selector' => '.elementor-element-{{ID}} .role',
        )
    );

    $self->add_control(
        'role_mg',
        array(
            'label'       => esc_html__( 'Margin', 'alpus-core' ),
            'description' => esc_html__( 'Controls the role margin.', 'alpus-core' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => array(
                'px',
                'em',
                'rem',
            ),
            'selectors'   => array(
                '.elementor-element-{{ID}} .role' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $self->end_controls_section();

    $self->start_controls_section(
        'rating_style',
        array(
            'label'     => esc_html__( 'Rating', 'alpus-core' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => array(
                'testimonial_type!' => 'simple',
            ),
        )
    );

    $self->add_control(
        'rating_sz',
        array(
            'label'      => esc_html__( 'Size', 'alpus-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array(
                'px',
                'rem',
            ),
            'selectors'  => array(
                '.elementor-element-{{ID}} .ratings-full' => 'font-size: {{SIZE}}{{UNIT}};',
            ),
        )
    );

    $self->add_control(
        'rating_sp',
        array(
            'label'      => esc_html__( 'Star Spacing', 'alpus-core' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array(
                'px',
            ),
        )
    );

    $self->add_control(
        'rating_color',
        array(
            'label'     => esc_html__( 'Color', 'alpus-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '.elementor-element-{{ID}} .ratings-full .ratings::before' => 'color: {{VALUE}};',
            ),
        )
    );

    $self->add_control(
        'rating_blank_color',
        array(
            'label'     => esc_html__( 'Blank Color', 'alpus-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} .ratings-full::before' => 'color: {{VALUE}};',
            ),
        )
    );

    $self->add_control(
        'rating_mg',
        array(
            'label'      => esc_html__( 'Margin', 'alpus-core' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array(
                'px',
                'em',
                'rem',
            ),
            'selectors'  => array(
                '.elementor-element-{{ID}} .ratings-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $self->end_controls_section();
}

/**
 * Register elementor content controls for testimonials
 *
 * @since 1.0
 */
function alpus_elementor_testimonial_content_controls( $self ) {
    $self->add_control(
        'name',
        array(
            'label'       => esc_html__( 'Name', 'alpus-core' ),
            'description' => esc_html__( 'Type a commenter name.', 'alpus-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'John Doe',
        )
    );

    $self->add_control(
        'role',
        array(
            'label'       => esc_html__( 'Role', 'alpus-core' ),
            'description' => esc_html__( 'Type a commenter role.', 'alpus-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'Customer',
        )
    );

    $self->add_control(
        'comment_title',
        array(
            'label'       => esc_html__( 'Comment Title', 'alpus-core' ),
            'description' => esc_html__( 'It isn\'t displayed in the simple testimonial type. Type a title of your testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
        )
    );

    $self->add_control(
        'content',
        array(
            'label'       => esc_html__( 'Description', 'alpus-core' ),
            'description' => esc_html__( 'Type a comment of your testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::TEXTAREA,
            'rows'        => '10',
            'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.', 'alpus-core' ),
        )
    );

    $self->add_control(
        'avatar',
        array(
            'label'       => esc_html__( 'Choose Avatar', 'alpus-core' ),
            'description' => esc_html__( 'Choose a certain image for your testimonial avatar.', 'alpus-core' ),
            'type'        => Controls_Manager::MEDIA,
            'default'     => array(
                'url' => \Elementor\Utils::get_placeholder_image_src(),
            ),
        )
    );

    $self->add_control(
        'link',
        array(
            'label'       => esc_html__( 'Link', 'alpus-core' ),
            'description' => esc_html__( 'Type a certain URL for your testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__( 'https://your-link.com', 'alpus-core' ),
        )
    );

    $self->add_group_control(
        Group_Control_Image_Size::get_type(),
        array(
            'name'      => 'avatar',
            'default'   => 'full',
            'exclude'   => [ 'custom' ],
            'separator' => 'none',
        )
    );

    $self->add_control(
        'rating',
        array(
            'label'       => esc_html__( 'Rating', 'alpus-core' ),
            'description' => esc_html__( 'It isn\'t displayed in the simple testimonial type.', 'alpus-core' ),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 0,
            'max'         => 5,
            'step'        => 0.1,
            'default'     => '',
        )
    );
}

/**
 * Register elementor type controls for testimonials.
 *
 * @since 1.0
 */
function alpus_elementor_testimonial_type_controls( $self ) {
    $self->add_control(
        'testimonial_type',
        array(
            'label'       => esc_html__( 'Testimonial Type', 'alpus-core' ),
            'description' => esc_html__( 'Select a certain display type of your testimonial among Simple, Boxed and Custom types.', 'alpus-core' ),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'simple',
            'options'     => array(
                'simple' => esc_html__( 'Simple', 'alpus-core' ),
                'boxed'  => esc_html__( 'Boxed', 'alpus-core' ),
                'aside'  => esc_html__( 'Aside', 'alpus-core' ),
            ),
            'qa_selector' => '.testimonial',
        )
    );

    $self->add_control(
        'testimonial_inverse',
        array(
            'label'       => esc_html__( 'Inversed', 'alpus-core' ),
            'description' => esc_html__( 'Enables to change the alignment of your testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::SWITCHER,
            'condition'   => array(
                'testimonial_type' => array( 'simple', 'aside' ),
            ),
        )
    );

    $self->add_control(
        'avatar_pos',
        array(
            'label'       => esc_html__( 'Avatar Position', 'alpus-core' ),
            'description' => esc_html__( 'Choose the position of the avatar in the testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'top',
            'options'     => array(
                'top'    => esc_html__( 'Top', 'alpus-core' ),
                'bottom' => esc_html__( 'Bottom', 'alpus-core' ),
            ),
            'condition'   => array(
                'testimonial_type' => 'boxed',
            ),
        )
    );

    $self->add_control(
        'commenter_pos',
        array(
            'label'       => esc_html__( 'Commenter Position', 'alpus-core' ),
            'description' => esc_html__( 'Choose the position of the commenter in the testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'after',
            'options'     => array(
                'before' => esc_html__( 'Before Comment', 'alpus-core' ),
                'after'  => esc_html__( 'After Comment', 'alpus-core' ),
            ),
            'condition'   => array(
                'testimonial_type' => 'boxed',
            ),
        )
    );

    $self->add_control(
        'aside_commenter_pos',
        array(
            'label'       => esc_html__( 'Commenter Position', 'alpus-core' ),
            'description' => esc_html__( 'Choose the position of the commenter in the testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'after_avatar',
            'options'     => array(
                'after_avatar'   => esc_html( 'After Avatar', 'alpus-core' ),
                'before_comment' => esc_html__( 'Before Comment', 'alpus-core' ),
                'after_comment'  => esc_html__( 'After Comment', 'alpus-core' ),
            ),
            'condition'   => array(
                'testimonial_type' => 'aside',
            ),
        )
    );

    $self->add_control(
        'rating_pos',
        array(
            'label'       => esc_html__( 'Rating Position', 'alpus-core' ),
            'description' => esc_html__( 'Choose the position of the rating in the testimonial.', 'alpus-core' ),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'before_title',
            'options'     => array(
                'before_title'   => esc_html__( 'Before Title', 'alpus-core' ),
                'after_title'    => esc_html__( 'After Title', 'alpus-core' ),
                'before_comment' => esc_html__( 'Before Comment', 'alpus-core' ),
                'after_comment'  => esc_html__( 'After Comment', 'alpus-core' ),
            ),
            'condition'   => array(
                'testimonial_type' => array( 'boxed', 'aside' ),
            ),
        )
    );

    $self->add_control(
        'h_align',
        array(
            'label'       => esc_html__( 'Horizontal Alignment', 'alpus-core' ),
            'description' => esc_html__( 'Select the testimonial\'s horizontal alignment.', 'alpus-core' ),
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
                '.elementor-element-{{ID}} .testimonial' => 'text-align: {{VALUE}};',
            ),
            'condition'   => array(
                'testimonial_type' => array( 'boxed', 'aside' ),
            ),
        )
    );

    $self->add_control(
        'v_align',
        array(
            'label'       => esc_html__( 'Vertical Alignment', 'alpus-core' ),
            'description' => esc_html__( 'Select the testimonial\'s vertical alignment.', 'alpus-core' ),
            'type'        => Controls_Manager::CHOOSE,
            'options'     => array(
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
            'selectors'   => array(
                '.elementor-element-{{ID}} .testimonial' => 'align-items: {{VALUE}};',
            ),
            'condition'   => array(
                'testimonial_type' => 'aside',
            ),
        )
    );
}
