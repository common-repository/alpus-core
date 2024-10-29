<?php
/**
 * Extending elementor partials.
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Core_Elementor_Extend' ) ) {
    class Alpus_Core_Elementor_Extend extends Alpus_Base {

        /**
                 * The Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            // Add Elementor column extensions
            require_once ALPUS_CORE_INC . '/widgets/half-container/widget-half-container-elementor.php';

            add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'load_admin_styles' ), 20 );

            add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ), 20 );

            if ( alpus_is_elementor_preview() ) {
                add_action( 'wp_enqueue_scripts', array( $this, 'load_preview_scripts' ) );
            }

            // Add Pro Widgets (virtual)
            add_filter( 'elementor/editor/localize_settings', array( $this, 'promote_pro_elements' ) );

            // Register New Controls
            add_filter( 'alpus_elementor_register_control', array( $this, 'register_controls' ) );

            // Include Partials
            add_filter( 'alpus_elementor_partials', array( $this, 'add_partials' ) );

            // Extend Widgets

            // Add Elementor widget extend functions
            $widgets = array(
                'image-gallery',
            );

            foreach ( $widgets as $widget ) {
                require_once ALPUS_CORE_INC . '/widgets/' . $widget . '/widget-' . str_replace( '_', '-', $widget ) . '-elementor-extend.php';
            }

            add_filter( 'alpus_elementor_widgets', array( $this, 'add_widgets' ) );

            // Styles
            add_action( 'wp_enqueue_scripts', array( $this, 'register_script' ), 10 );
            add_action( 'wp_enqueue_scripts', array( $this, 'add_elementor_css' ), 35 );

            add_action(
                'alpus_after_core_framework_plugins',
                function () {
                    if ( class_exists( 'Alpus_Elementor_Addon_Manager' ) && class_exists( 'Alpus_Core_Elementor' ) ) {
                        remove_action( 'elementor/elements/elements_registered', array( Alpus_Core_Elementor::get_instance(), 'register_element' ), 10 );
                    }
                }
            );
        }

        public function promote_pro_elements( $config ) {
            if ( class_exists( 'Alpus_Elementor_Addon_Manager' ) && class_exists( 'Alpus_Admin' ) && Alpus_Admin::get_instance()->is_registered() ) {
                return $config;
            }

            $promotion_widgets = array();

            if ( isset( $config['promotionWidgets'] ) ) {
                $promotion_widgets = $config['promotionWidgets'];
            }

            $combine_array = array_merge(
                $promotion_widgets,
                $this->get_pro_elements()
            );

            $config['promotionWidgets'] = $combine_array;

            return $config;
        }

        public function get_pro_elements() {
            return apply_filters(
                'alpus_pro_widgets',
                array(
                    array(
                        'name'       => 'alpus-360-degree',
                        'title'      => esc_html__( '360 Degree View', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-degree',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-animated-text',
                        'title'      => esc_html__( 'Animated Text', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-animate',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-bar-chart',
                        'title'      => esc_html__( 'Bar Chart', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-bar-chart',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-circle-progressbar',
                        'title'      => esc_html__( 'Circle Progressbar', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-counter-circle',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-contact-form',
                        'title'      => esc_html__( 'Contact Form', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-form-horizontal',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-countdown',
                        'title'      => esc_html__( 'Countdown', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-countdown',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-filter',
                        'title'      => esc_html__( 'Advanced Filter', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-filter',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-flipbox',
                        'title'      => esc_html__( 'Flipbox', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-flipbox',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-highlight',
                        'title'      => esc_html__( 'Highlight', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-highlight',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-hotspot',
                        'title'      => esc_html__( 'Hotspot', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-hotspot',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-image-compare',
                        'title'      => esc_html__( 'Image Compare', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-imagecomp',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-line-chart',
                        'title'      => esc_html__( 'Line Chart', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-line-chart',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-pie-doughnut-chart',
                        'title'      => esc_html__( 'Pie Doughnut Chart', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-pie-chart',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-polar-chart',
                        'title'      => esc_html__( 'Polar Chart', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-polar-chart',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-price-tables',
                        'title'      => esc_html__( 'Price Tables', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-price-table',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-radar-chart',
                        'title'      => esc_html__( 'Radar Chart', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-radar-chart',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-scroll-progress',
                        'title'      => esc_html__( 'Scroll Progress', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-import-export',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-scroll-button',
                        'title'      => esc_html__( 'Scroll Button', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-scroll',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-sticky-nav',
                        'title'      => esc_html__( 'Sticky Navigation', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-navigation-horizontal',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-subcategories',
                        'title'      => esc_html__( 'Subcategories List', 'alpus-core' ),
                        'icon'       => 'aalpus-elementor-widget-icon eicon-table-of-contents',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-svg-floating',
                        'title'      => esc_html__( 'Svg Floating', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-floating',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-table',
                        'title'      => esc_html__( 'Table', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-table',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-timeline',
                        'title'      => esc_html__( 'Timeline - Vertical', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-time-line',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-timeline-horizontal',
                        'title'      => esc_html__( 'Timeline - Horizontal', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon eicon-time-line alpus-time-line-horizontal',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                    array(
                        'name'       => 'alpus-price-list',
                        'title'      => esc_html__( 'Price List', 'alpus-core' ),
                        'icon'       => 'alpus-elementor-widget-icon alpus-widget-icon-pricelist',
                        'action_url' => ALPUS_GET_PRO_URI,
                        'categories' => '["alpus_widget"]',
                    ),
                )
            );
        }

        public function load_admin_styles() {
            wp_enqueue_style( 'alpus-elementor-admin-extend-style', ALPUS_CORE_INC_URI . '/plugins/elementor/assets/elementor-admin-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
        }

        /**
         * Extend additional controls
         *
         * @since 1.0
         */
        public function register_controls( $controls ) {
            if ( defined( 'ALPUS_PRO_VERSION' ) && class_exists( 'Alpus_Elementor_Addon_Manager' ) ) {
                return $controls;
            }
            $controls[] = 'select';

            return $controls;
        }

        /**
         * Register Scripts
         *
         * @since 1.0
         */
        public function register_script() {
            wp_register_script( 'easypiechart', ALPUS_CORE_URI . '/assets/js/easypiechart.min.js', array(), ALPUS_CORE_VERSION, true );
        }

        /**
         * Load extended elementor css
         *
         * @since 1.0
         */
        public function add_elementor_css() {
            wp_enqueue_style( 'alpus-elementor-extend', ALPUS_CORE_INC_URI . '/plugins/elementor/assets/elementor-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
        }

        /**
         * Load Elementor Editor Scripts
         *
         * @since 1.0
         */
        public function enqueue_editor_scripts() {
            wp_enqueue_script( 'alpus-elementor-admin-extend', ALPUS_CORE_INC_URI . '/plugins/elementor/assets/elementor-admin-extend' . ALPUS_JS_SUFFIX, array( 'jquery-core' ), ALPUS_CORE_VERSION, true );

            wp_localize_script(
                'alpus-elementor-admin-extend',
                'alpus_widget_settings',
                array(
                    'pro_activated' => class_exists( 'Alpus_Elementor_Addon_Manager' ) && class_exists( 'Alpus_Admin' ) && Alpus_Admin::get_instance()->is_registered(),
                    'pro_widgets'   => $this->get_pro_elements(),
                    'pro_button'    => ! class_exists( 'Alpus_Elementor_Addon_Manager' ) ? esc_html__( 'Get Alpus Elementor Addon', 'alpus-core' ) : esc_html__( 'Activate Now', 'alpus-core' ),
                    'purchase_pro'  => ALPUS_GET_PRO_URI,
                )
            );
        }

        public function load_preview_scripts() {
            // load needed style & script file in elementor preview
            wp_enqueue_style( 'alpus-elementor-preview-extend', ALPUS_CORE_INC_URI . '/plugins/elementor/assets/elementor-preview-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
            wp_enqueue_script( 'alpus-elementor-extend', ALPUS_CORE_INC_URI . '/plugins/elementor/assets/elementor-extend' . ALPUS_JS_SUFFIX, array( 'jquery-core' ), ALPUS_CORE_VERSION, true );
        }

        public function add_partials( $partials ) {
            $partials            = array_merge(
                array(
                    'button-extend' => true,
                    'slider-extend' => true,
                    'tab-extend'    => true,
                ),
                $partials
            );
            $partials['hotspot'] = false;
            $partials['chart']   = false;

            if ( class_exists( 'Alpus_Elementor_Addon_Manager' ) ) {
                $partials['banner'] = false;
            }

            return $partials;
        }

        /**
         * Extend elementor widgets
         *
         * @since 1.0
         */
        public function add_widgets( $widgets ) {
            $extended_widgets = array(
                'counters' => true,
                'icon-box' => true,
                'vendor'   => false,
            );

            $extended_widgets = array_merge(
                $extended_widgets,
                array(
                    '360-degree'          => false, // Pro widgets
                    'animated-text'       => false,
                    'bar-chart'           => false,
                    'circle-progressbar'  => false,
                    'contact-form'        => false,
                    'countdown'           => false,
                    'filter'              => false,
                    'flipbox'             => false,
                    'highlight'           => false,
                    'hotspot'             => false,
                    'image-compare'       => false,
                    'line-chart'          => false,
                    'pie-doughnut-chart'  => false,
                    'polar-chart'         => false,
                    'price-tables'        => false,
                    'radar-chart'         => false,
                    'scroll-progress'     => false,
                    'sticky-nav'          => false,
                    'subcategories'       => false,
                    'svg-floating'        => false,
                    'table'               => false,
                    'timeline'            => false,
                    'timeline-horizontal' => false,
                )
            );

            if ( defined( 'ALPUS_PRODUCT_BRAND_VERSION' ) ) {
                $extended_widgets = array_merge(
                    $extended_widgets,
                    array(
                        'brands' => true,
                    )
                );
            }

            if ( class_exists( 'Alpus_Elementor_Addon_Manager' ) ) {
                $extended_widgets = array_merge(
                    $extended_widgets,
                    array(
                        'banner' => false,
                    )
                );
            }

            $extended_widgets = array_merge( $widgets, $extended_widgets );

            return $extended_widgets;
        }

        /**
         ** Upgrade to Pro Notice
         */
        public static function upgrade_pro_notice( $module, $controls_manager, $widget, $option, $condition = array() ) {
            if ( defined( 'ALPUS_PRO_VERSION' ) || ! alpus_is_elementor_preview() ) {
                return;
            }

            $module->add_control(
                $option . '_pro_notice',
                array(
                    'raw'             => sprintf( esc_html__( 'This option is available%1$s in the %2$sAlpus Pro%3$s', 'alpus-core' ), '<br>', '<strong><a href="' . ALPUS_GET_PRO_URI . '" target="_blank">', '</a></strong>' ),
                    'type'            => $controls_manager,
                    'content_classes' => 'alpus-pro-notice',
                    'condition'       => $condition,
                )
            );
        }

        /**
         ** Upgrade to Elementor Addon Notice
         */
        public static function purchase_elementor_addon_notice( $module, $controls_manager, $widget, $option, $condition = array() ) {
            if ( class_exists( 'Alpus_Elementor_Addon_Manager' ) || ! alpus_is_elementor_preview() ) {
                return;
            }

            $module->add_control(
                $option . '_pro_notice',
                array(
                    'raw'             => sprintf( esc_html__( 'This option is available%1$s in the %2$s Elementor Addon%3$s', 'alpus-core' ), '<br>', '<strong><a href="' . ALPUS_GET_PRO_URI . '" target="_blank">' . ALPUS_DISPLAY_NAME, '</a></strong>' ),
                    'type'            => $controls_manager,
                    'content_classes' => 'alpus-pro-notice',
                    'condition'       => $condition,
                )
            );
        }
    }
}

Alpus_Core_Elementor_Extend::get_instance();
