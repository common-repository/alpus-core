<?php

defined( 'ABSPATH' ) || die;

/*
 * Alpus Elementor Posts Grid Widget
 *
 * Alpus Elementor widget to display posts or terms with the type built by post type builder.
 *
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

use Elementor\Alpus_Controls_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpus_Posts_Grid_Elementor_Widget extends Elementor\Widget_Base {

    public function get_name() {
        return  ALPUS_NAME . '_widget_posts_grid';
    }

    public function get_title() {
        return __( 'Posts Grid', 'alpus-core' );
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'post', 'product', 'shop', 'term', 'category', 'taxonomy', 'type', 'card', 'builder', 'custom', 'portfolio', 'member', 'event', 'project' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-post';
    }

    public function get_script_depends() {
        $depends = array( 'swiper' );

        if ( alpus_is_elementor_preview() ) {
            $depends[] = 'alpus-elementor-js';

            if ( function_exists( 'alpus_get_option' ) && alpus_get_option( 'compare_available' ) ) {
                wp_register_script( 'alpus-product-compare', alpus_core_framework_uri( '/addons/product-compare/product-compare' . ALPUS_JS_SUFFIX ), array( 'alpus-framework-async' ), ALPUS_CORE_VERSION, true );
                $depends[] = 'alpus-product-compare';
            }
        }

        return $depends;
    }

    /**
     * Get Style depends.
     *
     * @since 1.2.0
     */
    public function get_style_depends() {
        $depends = array();

        if ( function_exists( 'alpus_is_elementor_preview' ) && alpus_is_elementor_preview() ) {
            if ( ! wp_style_is( 'alpus-tab', 'registered' ) ) {
                wp_register_style( 'alpus-tab', alpus_core_framework_uri( '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );
            }

            if ( ! wp_style_is( 'alpus-product', 'registered' ) ) {
                wp_register_style( 'alpus-product', alpus_core_framework_uri( '/widgets/products/product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );
            }

            if ( ! wp_style_is( 'alpus-theme-single-product', 'registered' ) && defined( 'ALPUS_VERSION' ) ) {
                wp_register_style( 'alpus-theme-single-product', ALPUS_ASSETS . '/css/pages/single-product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_CORE_VERSION );
            }

            if ( ! wp_style_is( 'alpus-post', 'registered' ) ) {
                wp_register_style( 'alpus-post', alpus_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );
            }

            if ( ! wp_style_is( 'alpus-product-category', 'registered' ) ) {
                wp_register_style( 'alpus-product-category', alpus_core_framework_uri( '/widgets/categories/category' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );
            }

            if ( function_exists( 'alpus_get_option' ) && alpus_get_option( 'compare_available' ) ) {
                wp_register_style( 'alpus-product-compare', alpus_core_framework_uri( '/addons/product-compare/product-compare' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_VERSION );
            }

            $depends = array( 'alpus-tab', 'alpus-product', 'alpus-theme-single-product', 'alpus-post', 'alpus-product-category', 'alpus-product-compare' );
        }

        if ( ! wp_style_is( 'alpus-type-builder', 'registered' ) ) {
            wp_register_style( 'alpus-type-builder', alpus_core_framework_uri( '/builders/type/type-builder' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );
        }
        $depends[] = 'alpus-type-builder';

        return $depends;
    }

    protected function register_controls() {
        $post_types          = get_post_types(
            array(
                'public'            => true,
                'show_in_nav_menus' => true,
            ),
            'objects',
            'and'
        );
        $disabled_post_types = array( 'attachment', ALPUS_NAME . '_template', 'page', 'e-landing-page' );

        foreach ( $disabled_post_types as $disabled ) {
            unset( $post_types[ $disabled ] );
        }

        foreach ( $post_types as $key => $p_type ) {
            $post_types[ $key ] = esc_html( $p_type->label );
        }
        $post_types = apply_filters( 'alpus_posts_grid_post_types', $post_types );

        $taxes = get_taxonomies( array(), 'objects' );
        unset( $taxes['post_format'], $taxes['product_visibility'] );

        foreach ( $taxes as $tax_name => $tax ) {
            $taxes[ $tax_name ] = esc_html( $tax->label );
        }
        $taxes = apply_filters( 'alpus_posts_grid_taxonomies', $taxes );

        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' === $left ? 'right' : 'left';

        $status_values = array(
            ''         => __( 'All', 'alpus-core' ),
            'featured' => __( 'Featured', 'alpus-core' ),
            'on_sale'  => __( 'On Sale', 'alpus-core' ),
            'viewed'   => __( 'Recently Viewed', 'alpus-core' ),
        );

        $this->start_controls_section(
            'section_selector',
            array(
                'label' => __( 'Posts Selector', 'alpus-core' ),
            )
        );

        $this->add_control(
            'builder_id',
            array(
                'type'        => Alpus_Controls_Manager::AJAXSELECT2,
                'label'       => __( 'Post Layout', 'alpus-core' ),
                /* translators: starting and ending A tag which redirects to post type builder. */
                'description' => sprintf( __( 'Please select a saved Post Layout template which was built using post type builder. Please create a new Post Layout template in %1$sTemplates Builder%2$s', 'alpus-core' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=' . ALPUS_NAME . '_template&' . ALPUS_NAME . '_template_type=type' ) ) . '">', '</a>' ),
                'options'     => 'type',
                'label_block' => true,
                'add_default' => true,
            )
        );

        $this->add_control(
            'source',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => __( 'Content Source', 'alpus-core' ),
                'options'     => array(
                    ''      => __( 'Posts', 'alpus-core' ),
                    'terms' => __( 'Terms', 'alpus-core' ),
                ),
                'description' => __( 'Please select the content type which you would like to show.', 'alpus-core' ),
                'default'     => '',
            )
        );

        $this->add_control(
            'post_type',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => __( 'Post Type', 'alpus-core' ),
                'description' => __( 'Please select a post type of posts to display.', 'alpus-core' ),
                'options'     => $post_types,
                'condition'   => array(
                    'source' => '',
                ),
            )
        );

        $this->add_control(
            'product_status',
            array(
                'label'     => __( 'Product Status', 'alpus-core' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '',
                'options'   => $status_values,
                'condition' => array(
                    'source'    => '',
                    'post_type' => 'product',
                ),
            )
        );

        $this->add_control(
            'post_tax',
            array(
                'type'        => Alpus_Controls_Manager::AJAXSELECT2,
                'label'       => __( 'Taxonomy', 'alpus-core' ),
                'description' => __( 'Please select a post taxonomy to pull posts from.', 'alpus-core' ),
                'options'     => '%post_type%_alltax',
                'label_block' => true,
                'condition'   => array(
                    'source' => '',
                ),
            )
        );

        $this->add_control(
            'post_terms',
            array(
                'type'        => Alpus_Controls_Manager::AJAXSELECT2,
                'label'       => __( 'Terms', 'alpus-core' ),
                'description' => __( 'Please select a post terms to pull posts from.', 'alpus-core' ),
                'options'     => '%post_tax%_allterm',
                'multiple'    => 'true',
                'label_block' => true,
                'condition'   => array(
                    'source'    => '',
                    'post_tax!' => '',
                ),
            )
        );

        $this->add_control(
            'tax',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => __( 'Taxonomy', 'alpus-core' ),
                'description' => __( 'Please select a taxonomy to use.', 'alpus-core' ),
                'options'     => $taxes,
                'condition'   => array(
                    'source' => 'terms',
                ),
                'default'     => '',
            )
        );

        $this->add_control(
            'terms',
            array(
                'type'        => Alpus_Controls_Manager::AJAXSELECT2,
                'label'       => __( 'Terms', 'alpus-core' ),
                'description' => __( 'Please select terms to display.', 'alpus-core' ),
                'options'     => '%tax%_allterm',
                'multiple'    => 'true',
                'label_block' => true,
                'condition'   => array(
                    'source' => 'terms',
                    'tax!'   => '',
                ),
            )
        );

        $this->add_control(
            'count',
            array(
                'type'  => Controls_Manager::SLIDER,
                'label' => __( 'Count', 'alpus-core' ),
                'range' => array(
                    'px' => array(
                        'step' => 1,
                        'min'  => 1,
                        'max'  => 100,
                    ),
                ),
            )
        );

        $this->add_control(
            'hide_empty',
            array(
                'type'      => Controls_Manager::SWITCHER,
                'label'     => __( 'Hide empty', 'alpus-core' ),
                'condition' => array(
                    'source' => 'terms',
                ),
            )
        );

        $this->add_control(
            'orderby',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => __( 'Order by', 'alpus-core' ),
                'options'     => array(
                    ''               => esc_html__( 'Default', 'alpus-core' ),
                    'ID'             => esc_html__( 'ID', 'alpus-core' ),
                    'title'          => esc_html__( 'Name', 'alpus-core' ),
                    'date'           => esc_html__( 'Date', 'alpus-core' ),
                    'modified'       => esc_html__( 'Modified', 'alpus-core' ),
                    'price'          => esc_html__( 'Price', 'alpus-core' ),
                    'rand'           => esc_html__( 'Random', 'alpus-core' ),
                    'rating'         => esc_html__( 'Rating', 'alpus-core' ),
                    'comment_count'  => esc_html__( 'Comment count', 'alpus-core' ),
                    'popularity'     => esc_html__( 'Total Sales', 'alpus-core' ),
                    'wishqty'        => esc_html__( 'Wish', 'alpus-core' ),
                    'sale_date_to'   => esc_html__( 'Sale End Date', 'alpus-core' ),
                    'sale_date_from' => esc_html__( 'Sale Start Date', 'alpus-core' ),
                ),
                'description' => __( 'Price, Rating, Total Sales, Wish, Sale End Date and Sale Start Date values work for only product post type.', 'alpus-core' ),
                'condition'   => array(
                    'source' => '',
                ),
            )
        );

        $this->add_control(
            'orderby_term',
            array(
                'type'      => Controls_Manager::SELECT,
                'label'     => __( 'Order by', 'alpus-core' ),
                'options'   => array(
                    ''            => __( 'Default', 'alpus-core' ),
                    'name'        => __( 'Title', 'alpus-core' ),
                    'term_id'     => __( 'ID', 'alpus-core' ),
                    'count'       => __( 'Post Count', 'alpus-core' ),
                    'none'        => __( 'None', 'alpus-core' ),
                    'parent'      => __( 'Parent', 'alpus-core' ),
                    'description' => __( 'Description', 'alpus-core' ),
                    'term_group'  => __( 'Term Group', 'alpus-core' ),
                ),
                'default'   => '',
                'condition' => array(
                    'source' => 'terms',
                ),
            )
        );

        $this->add_control(
            'orderway',
            array(
                'type'        => Controls_Manager::CHOOSE,
                'label'       => __( 'Order', 'alpus-core' ),
                'default'     => 'ASC',
                'options'     => array(
                    'ASC'  => array(
                        'title' => esc_html__( 'Ascending', 'alpus-core' ),
                        'icon'  => 'alpus-order-asc alpus-choose-type',
                    ),
                    'DESC' => array(
                        'title' => esc_html__( 'Descending', 'alpus-core' ),
                        'icon'  => 'alpus-order-desc alpus-choose-type',
                    ),
                ),
                /* translators: %s: Wordpres codex page */
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'alpus-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            array(
                'label' => __( 'Posts Layout', 'alpus-core' ),
            )
        );

        $this->add_control(
            'view',
            array(
                'label'   => __( 'View', 'alpus-core' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'grid',
                'toggle'  => false,
                'options' => array(
                    'grid'     => array(
                        'title' => esc_html__( 'Grid', 'alpus-core' ),
                        'icon'  => 'eicon-column',
                    ),
                    'slider'   => array(
                        'title' => esc_html__( 'Slider', 'alpus-core' ),
                        'icon'  => 'eicon-slider-3d',
                    ),
                    'creative' => array(
                        'title' => esc_html__( 'Creative Grid', 'alpus-core' ),
                        'icon'  => 'eicon-inner-section',
                    ),
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            array(
                'name'    => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
                'exclude' => [ 'custom' ],
                'default' => 'alpus-post-small',
            )
        );

        alpus_elementor_grid_layout_controls( $this, 'view', true, 'posts-grid' );

        alpus_elementor_slider_layout_controls( $this, 'view' );

        $this->end_controls_section();

        $this->start_controls_section(
            'post_filter_section',
            array(
                'label' => esc_html__( 'Post Load', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        if ( ! alpus_get_option( 'archive_ajax' ) ) {
            $this->add_control(
                'notice_post_ajax',
                array(
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( __( 'Please enable Ajax Filter option %1$sTheme Options / Features / Ajax Filter%2$s.', 'alpus-core' ), '<a href="' . esc_url( admin_url( 'customize.php#ajax_filter' ) ) . '" target="_blank">', '</a>' ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                )
            );
        } else {
            alpus_elementor_loadmore_layout_controls( $this, 'view' );
        }

        $this->add_control(
            'filter_cat',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'Filter by Category', 'alpus-core' ),
                'description' => esc_html__( 'Defines whether to show or hide category filters above products.', 'alpus-core' ),
                'condition'   => array(
                    'source' => '',
                ),
            )
        );

        $this->add_control(
            'filter_cat_tax',
            array(
                'type'        => Alpus_Controls_Manager::AJAXSELECT2,
                'label'       => __( 'Taxonomy', 'alpus-core' ),
                'description' => __( 'Please select a post taxonomy to be used as category filter.', 'alpus-core' ),
                'options'     => '%post_type%_alltax',
                'label_block' => true,
                'condition'   => array(
                    'source'     => '',
                    'filter_cat' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_all_filter',
            array(
                'type'      => Controls_Manager::SWITCHER,
                'label'     => esc_html__( 'Show "All" Filter', 'alpus-core' ),
                'default'   => 'yes',
                'condition' => array(
                    'source'     => '',
                    'filter_cat' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        alpus_elementor_slider_style_controls( $this, 'view' );

        $this->start_controls_section(
            'post_grid_style',
            array(
                'label' => esc_html__( 'Post Grid Style', 'alpus-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'grid_item_spacing',
            array(
                'label'       => esc_html__( 'Row Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the row spacing of each grid item.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .alpus-tb-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'filter_style',
            array(
                'label'     => esc_html__( 'Filters Style', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'filter_cat' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .nav-filter',
            )
        );

        $this->add_control(
            'filter_align',
            array(
                'label'       => esc_html__( 'Alignment', 'alpus-core' ),
                'description' => esc_html__( 'Controls filters\'s alignment. Choose from Left, Center, Right.', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-filters' => 'justify-content: {{VALUE}}',
                ),
            )
        );

        $this->start_controls_tabs( 'filter_cats' );

        $this->start_controls_tab(
            'filter_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_control(
            'filter_normal_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of the filters.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-filter' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_active',
            array(
                'label' => esc_html__( 'Hover/Active', 'alpus-core' ),
            )
        );

        $this->add_control(
            'filter_active_color',
            array(
                'label'       => esc_html__( 'Active Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the active and hover color of the filters.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-filter.active, .elementor-element-{{ID}} .nav-filter:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'filter_between_spacing',
            array(
                'label'       => esc_html__( 'Space Between (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the spacing between filters.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-filters li:not(:last-child)' => "margin-{$right}: {{SIZE}}{{UNIT}};",
                    '.elementor-element-{{ID}} .nav-filters li'                  => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_spacing',
            array(
                'label'       => esc_html__( 'Bottom Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the spacing of the filters.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .nav-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pagination_style',
            array(
                'label'     => esc_html__( 'Pagination Style', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'loadmore_type' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'pagination_align',
            array(
                'label'       => esc_html__( 'Horizontal Align', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'description' => esc_html__( 'Control the horizontal align of pagination part.', 'alpus-core' ),
                'options'     => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Center', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Right', 'alpus-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .pagination' => 'justify-content:{{VALUE}}',
                ),
                'condition'   => array(
                    'loadmore_type!' => 'button',
                ),
            )
        );

        $this->add_control(
            'pagination_margin',
            array(
                'label'       => esc_html__( 'Margin', 'alpus-core' ),
                'description' => esc_html__( 'Set custom margin of pagination part.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array(
                    'px',
                    '%',
                    'em',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .pagination, .elementor-element-{{ID}} .btn-load' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'load_more_style',
            array(
                'label'     => esc_html__( 'Load More Button Style', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'loadmore_type' => 'button',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'load_more_typography',
                'label'    => esc_html__( 'Typography', 'alpus-core' ),
                'selector' => '.elementor-element-{{ID}} .btn-load',
            )
        );

        $this->add_control(
            'load_more_padding',
            array(
                'label'       => esc_html__( 'Padding', 'alpus-core' ),
                'description' => esc_html__( 'Controls padding value of button.', 'alpus-core' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array(
                    'px',
                    '%',
                    'em',
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .btn-load' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'load_more_spacing',
            array(
                'label'       => esc_html__( 'Spacing (px)', 'alpus-core' ),
                'description' => esc_html__( 'Controls the spacing of load more button.', 'alpus-core' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} .btn-load' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_btn_cat' );

        $this->start_controls_tab(
            'tab_btn_normal',
            array(
                'label' => esc_html__( 'Normal', 'alpus-core' ),
            )
        );

        $this->add_control(
            'load_more_color',
            array(
                'label'       => esc_html__( 'Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .btn-load' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'load_more_back_color',
            array(
                'label'       => esc_html__( 'Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the background color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .btn-load' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'load_more_border_color',
            array(
                'label'       => esc_html__( 'Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the border color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .btn-load' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_btn_hover',
            array(
                'label' => esc_html__( 'Hover', 'alpus-core' ),
            )
        );

        $this->add_control(
            'load_more_color_hover',
            array(
                'label'       => esc_html__( 'Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .btn-load:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'load_more_back_color_hover',
            array(
                'label'       => esc_html__( 'Hover Background Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover background color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .btn-load:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'load_more_border_color_hover',
            array(
                'label'       => esc_html__( 'Hover Border Color', 'alpus-core' ),
                'description' => esc_html__( 'Controls the hover border color of the button.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .btn-load:hover' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $atts = $this->get_settings_for_display();

        if ( is_array( $atts['count'] ) ) {
            if ( isset( $atts['count']['size'] ) ) {
                $atts['count'] = $atts['count']['size'];
            } else {
                $atts['count'] = '';
            }
        }

        if ( is_array( $atts['col_cnt'] ) ) {
            if ( isset( $atts['col_cnt']['size'] ) ) {
                $atts['col_cnt'] = $atts['col_cnt']['size'];
            } else {
                $atts['col_cnt'] = '';
            }
        }
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/posts-grid/render-posts-grid.php' );
    }
}
