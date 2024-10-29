<?php
/**
 * Alpus Brands Widget
 *
 * Alpus Widget to display brands.
 *
 * @author     D-THEMES
 *
 * @since      1.2.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Alpus_Controls_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Alpus_Brands_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_brands';
    }

    public function get_title() {
        return esc_html__( 'Brands', 'alpus-core' );
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'brand', 'product', 'alpus' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-brand';
    }

    /**
     * Get style dependency
     *
     * @since 1.2.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-brands', alpus_core_framework_uri( '/widgets/brands/brands' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

        return array( 'alpus-brands' );
    }

    /**
     * Get script dependency
     *
     * @since 1.2.0
     */
    public function get_script_depends() {
        return array( 'swiper' );
    }

    protected function register_controls() {
        $left  = is_rtl() ? 'right' : 'left';
        $right = 'left' == $left ? 'right' : 'left';

        $this->start_controls_section(
            'section_layout',
            array(
                'label' => esc_html__( 'Layout', 'alpus-core' ),
            )
        );

        $this->add_control(
            'brand_type',
            array(
                'label'       => esc_html__( 'Skin', 'alpus-core' ),
                'description' => esc_html__( 'Choose your favourite skin.', 'alpus-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '3',
                'options'     => array(
                    '1' => esc_html__( 'Type 1', 'alpus-core' ),
                    '2' => esc_html__( 'Type 2', 'alpus-core' ),
                    '3' => esc_html__( 'Type 3', 'alpus-core' ),
                ),
            )
        );

        $this->add_control(
            'show_brand_rating',
            array(
                'label'       => esc_html__( 'Show Brand Rating', 'alpus-core' ),
                'description' => esc_html__( 'Display the brand rating.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => 'yes',
                'condition'   => array(
                    'brand_type' => array( '2', '3' ),
                ),
            )
        );

        $this->add_control(
            'show_brand_products',
            array(
                'label'       => esc_html__( 'Show Brand Products', 'alpus-core' ),
                'description' => esc_html__( 'Display the brand products.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'condition'   => array(
                    'brand_type' => array( '3' ),
                ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'       => esc_html__( 'Layout', 'alpus-core' ),
                'description' => esc_html__( 'Choose brands layout type: Grid, Slider', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'toggle'      => false,
                'separator'   => 'before',
                'default'     => 'grid',
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

        alpus_elementor_grid_layout_controls( $this, 'layout_type', false, 'has_rows' );

        $this->add_control(
            'count',
            array(
                'type'        => Controls_Manager::NUMBER,
                'label'       => esc_html__( 'Brands Per Page', 'alpus-core' ),
                'description' => esc_html__( '0 or no value will show all brands.', 'alpus-core' ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'        => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
                'exclude'     => [ 'custom' ],
                'separator'   => 'before',
                'description' => esc_html__( 'Select image size for your site.', 'alpus-core' ),
                'default'     => 'woocommerce_thumbnail',
            )
        );

        $this->add_control(
            'slider_image_expand',
            array(
                'label'       => esc_html__( 'Image Full Width', 'alpus-core' ),
                'description' => esc_html__( 'Allow images to fill with whole content.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'condition'   => array(
                    'brand_type'  => '1',
                    'layout_type' => 'slider',
                ),
            )
        );

        $this->add_control(
            'slider_horizontal_align',
            array(
                'label'       => esc_html__( 'Horizontal Align', 'alpus-core' ),
                'description' => esc_html__( 'Choose horizontal alignment of items.', 'alpus-core' ),
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
                    '.elementor-element-{{ID}} figure' => 'display: flex; justify-content:{{VALUE}}',
                ),
                'condition'   => array(
                    'brand_type'          => '1',
                    'slider_image_expand' => '',
                    'layout_type'         => 'slider',
                ),
            )
        );

        $this->add_control(
            'slider_vertical_align',
            array(
                'label'       => esc_html__( 'Vertical Align', 'alpus-core' ),
                'description' => esc_html__( 'Choose vertical alignment of items. Choose from Top, Middle, Bottom, Stretch.', 'alpus-core' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'top'         => array(
                        'title' => esc_html__( 'Top', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'middle'      => array(
                        'title' => esc_html__( 'Middle', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'bottom'      => array(
                        'title' => esc_html__( 'Bottom', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                    'same-height' => array(
                        'title' => esc_html__( 'Stretch', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-stretch',
                    ),
                ),
                'condition'   => array(
                    'brand_type'  => '1',
                    'layout_type' => 'slider',
                ),
            )
        );

        $this->add_control(
            'grid_image_expand',
            array(
                'label'       => esc_html__( 'Image Full Width', 'alpus-core' ),
                'description' => esc_html__( 'Allow images to fill with whole content.', 'alpus-core' ),
                'type'        => Controls_Manager::SWITCHER,
                'selectors'   => array(
                    '.elementor-element-{{ID}} figure a, .elementor-element-{{ID}} figure img' => 'width: 100%;',
                ),
                'condition'   => array(
                    'brand_type'  => '1',
                    'layout_type' => 'grid',
                ),
            )
        );

        $this->add_control(
            'grid_horizontal_align',
            array(
                'label'     => esc_html__( 'Horizontal Align', 'alpus-core' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
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
                'selectors' => array(
                    '.elementor-element-{{ID}} figure' => 'display: flex; justify-content:{{VALUE}}',
                ),
                'condition' => array(
                    'brand_type'        => '1',
                    'grid_image_expand' => '',
                    'layout_type'       => 'grid',
                ),
            )
        );

        $this->add_control(
            'grid_vertical_align',
            array(
                'label'       => esc_html__( 'Vertical Align', 'alpus-core' ),
                'description' => esc_html__( 'Choose vertical alignment of items.', 'alpus-core' ),
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
                    'stretch'    => array(
                        'title' => esc_html__( 'Stretch', 'alpus-core' ),
                        'icon'  => 'eicon-v-align-stretch',
                    ),
                ),
                'selectors'   => array(
                    '.elementor-element-{{ID}} figure' => 'display: flex; align-items:{{VALUE}}; height: 100%;',
                ),
                'condition'   => array(
                    'brand_type'  => '1',
                    'layout_type' => 'grid',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_brands',
            array(
                'label' => esc_html__( 'Query', 'alpus-core' ),
            )
        );

        $this->add_control(
            'brands',
            array(
                'label'       => esc_html__( 'Select Brands', 'alpus-core' ),
                'description' => esc_html__( 'Choose brands you want to display.', 'alpus-core' ),
                'type'        => Alpus_Controls_Manager::AJAXSELECT2,
                'options'     => 'product_brand',
                'label_block' => true,
                'multiple'    => true,
            )
        );

        $this->add_control(
            'hide_empty',
            array(
                'type'        => Controls_Manager::SWITCHER,
                'label'       => esc_html__( 'Hide Empty Ones', 'alpus-core' ),
                'description' => esc_html__( 'Hide brand without any products', 'alpus-core' ),
            )
        );

        $this->add_control(
            'orderby',
            array(
                'type'        => Controls_Manager::SELECT,
                'label'       => esc_html__( 'Order By', 'alpus-core' ),
                'description' => esc_html__( 'Defines how brands should be ordered: Default, ID, Name, Slug, Modified and so on.', 'alpus-core' ),
                'default'     => 'name',
                'options'     => array(
                    'name'        => esc_html__( 'Name', 'alpus-core' ),
                    'id'          => esc_html__( 'ID', 'alpus-core' ),
                    'slug'        => esc_html__( 'Slug', 'alpus-core' ),
                    'modified'    => esc_html__( 'Modified', 'alpus-core' ),
                    'count'       => esc_html__( 'Product Count', 'alpus-core' ),
                    'parent'      => esc_html__( 'Parent', 'alpus-core' ),
                    'description' => esc_html__( 'Description', 'alpus-core' ),
                    'term_group'  => esc_html__( 'Term Group', 'alpus-core' ),
                ),
            )
        );

        $this->add_control(
            'orderway',
            array(
                'type'        => Controls_Manager::CHOOSE,
                'label'       => esc_html__( 'Order Way', 'alpus-core' ),
                'description' => esc_html__( 'Defines brands ordering type: Ascending or Descending.', 'alpus-core' ),
                'toggle'      => false,
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
            )
        );

        $this->end_controls_section();

        // Add brand style
        $this->start_controls_section(
            'section_brand_style',
            array(
                'label'     => esc_html__( 'Brand Style', 'alpus-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'brand_type' => array( '2', '3' ),
                ),
            )
        );

        // Style for Brand name
        $this->add_control(
            'style_brand_name',
            array(
                'label' => esc_html__( 'Colors', 'alpus-core' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_control(
            'brand_name_default_color',
            array(
                'label'       => esc_html__( 'Brand Name Color', 'alpus-core' ),
                'description' => esc_html__( 'Set brand name color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .brand-name a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'brand_name_hover_color',
            array(
                'label'       => esc_html__( 'Brand Name Hover Color', 'alpus-core' ),
                'description' => esc_html__( 'Set brand name hover color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .brand-name a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'brand_product_count_color',
            array(
                'label'       => esc_html__( 'Product Count Color', 'alpus-core' ),
                'description' => esc_html__( 'Set brand products count color.', 'alpus-core' ),
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '.elementor-element-{{ID}} .brand-product-count' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'style_brand_size',
            array(
                'label'     => esc_html__( 'Size', 'alpus-core' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'style_font_size',
            array(
                'label'      => esc_html__( 'Brand Font Size', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'rem' ),
                'range'      => array(
                    'px'  => array(
                        'step' => 1,
                        'min'  => 0,
                        'max'  => 100,
                    ),
                    'rem' => array(
                        'step' => .5,
                        'min'  => 0,
                        'max'  => 10,
                    ),
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} .brand-widget' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'brand_image_size',
            array(
                'label'     => esc_html__( 'Image Size (px)', 'alpus-core' ),
                'type'      => Controls_Manager::NUMBER,
                'selectors' => array(
                    '.elementor-element-{{ID}} .brand-widget .brand-logo' => 'max-width: {{SIZE}}px; object-fit: cover;',
                    '.elementor-element-{{ID}} .brand-widget .brand-info' => 'max-width: calc(100% - {{SIZE}}px);',
                ),
                'condition' => array(
                    'brand_type' => array( '3' ),
                ),
            )
        );

        $this->add_control(
            'brand_image_space',
            array(
                'label'     => esc_html__( 'Image Space (px)', 'alpus-core' ),
                'type'      => Controls_Manager::NUMBER,
                'selectors' => array(
                    '.elementor-element-{{ID}} .brand-widget .brand-info' => "margin-{$left}: {{SIZE}}px;",
                ),
                'condition' => array(
                    'brand_type' => array( '3' ),
                ),
            )
        );

        $this->end_controls_section();

        alpus_elementor_slider_style_controls( $this, 'layout_type' );
    }

    protected function render() {
        $atts = $this->get_settings_for_display();
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/brands/render-brands-elementor.php' );
    }
}
