<?php
/**
 * Alpus Studio Extend
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Studio_Extend' ) ) {
    /**
     * The Alpus Studio class
     *
     * @since 1.0
     */
    class Alpus_Studio_Extend {

        private $element_categories  = array();

        /**
         * Constructor
         *
         * @since 1.0
         */
        public function __construct() {
            add_filter( 'alpus_studio_vars', array( $this, 'studio_vars' ) );

            // Update framework studio categories
            add_filter( 'alpus_studio_category', array( $this, 'studio_categories' ) );
            add_filter( 'alpus_studio_big_category', array( $this, 'studio_big_categories' ) );
            add_filter( 'alpus_studio_has_children', array( $this, 'studio_has_children' ) );
            add_filter( 'alpus_studio_block_category', array( $this, 'studio_block_categories' ) );
            add_filter( 'alpus_studio_blocks_args', array( $this, 'studio_blocks_args' ) );

            // Add new studio categories
            $this->element_categories  = apply_filters( 'alpus_studio_element_category', array( 'button', 'contact_info', 'counter', 'icon_box', 'image_box', 'image_gallery', 'icon_list', 'search', 'progressbars', 'social', 'testimonials', 'video', 'pricing', 'products', 'posts', 'projects', 'team' ) );
        }

        /**
         * Extend studio vars
         *
         * @since 1.0
         */
        public function studio_vars( $vars ) {
            $vars['registered']    = class_exists( 'Alpus_Admin' ) && Alpus_Admin::get_instance()->is_registered();
            $vars['pro_title']     = esc_html__( 'Upgrade', 'alpus-core' );
            $vars['pro_desc']      = ALPUS_ENVATO_CODE ? esc_html__( 'Activate theme and get pro to access Alpus Studio Library.', 'alpus-core' ) : esc_html__( 'Purchase of Pro and theme activation are required to access Alpus Studio Library.', 'alpus-core' );
            $vars['purchase_pro']  = ALPUS_GET_PRO_URI;

            return $vars;
        }

        /**
         * Studio categories
         *
         * @since 1.0
         */
        public function studio_categories( $categories ) {
            $categories = array(
                'block'          => esc_html__( 'Block', 'alpus-core' ), // Blocks
                'demo'           => esc_html__( 'Demo', 'alpus-core' ),
                'about'          => esc_html__( 'About', 'alpus-core' ),
                'contact'        => esc_html__( 'Contact', 'alpus-core' ),
                'banner'         => esc_html__( 'Banner', 'alpus-core' ),
                'slider'         => esc_html__( 'Slider', 'alpus-core' ),
                'megamenu'       => esc_html__( 'Megamenu', 'alpus-core' ),
                'error-404'      => esc_html__( 'Error 404', 'alpus-core' ),
                'other'          => esc_html__( 'Other', 'alpus-core' ),
                'element'        => esc_html__( 'Element', 'alpus-core' ), // Elements
                'button'         => esc_html__( 'Button', 'alpus-core' ),
                'contact_info'   => esc_html__( 'Contact', 'alpus-core' ),
                'counter'        => esc_html__( 'Counter', 'alpus-core' ),
                'icon_box'       => esc_html__( 'Icon Box', 'alpus-core' ),
                'image_box'      => esc_html__( 'Image Box', 'alpus-core' ),
                'image_gallery'  => esc_html__( 'Image Gallery', 'alpus-core' ),
                'icon_list'      => esc_html__( 'Icon List', 'alpus-core' ),
                'search'         => esc_html__( 'Search', 'alpus-core' ),
                'social'         => esc_html__( 'Social Icons', 'alpus-core' ),
                'progressbars'   => esc_html__( 'Progressbars', 'alpus-core' ),
                'testimonials'   => esc_html__( 'Testimonials', 'alpus-core' ),
                'video'          => esc_html__( 'Video', 'alpus-core' ),
                'pricing'        => esc_html__( 'Pricing', 'alpus-core' ),
                'products'       => esc_html__( 'Products', 'alpus-core' ),
                'posts'          => esc_html__( 'Posts', 'alpus-core' ),
                'projects'       => esc_html__( 'Projects', 'alpus-core' ),
                'team'           => esc_html__( 'Team', 'alpus-core' ),
                'header'         => esc_html__( 'Header', 'alpus-core' ),
                'footer'         => esc_html__( 'Footer', 'alpus-core' ),
                'template'       => esc_html__( 'Template', 'alpus-core' ), // Templates
                'shop'           => esc_html__( 'Shop', 'alpus-core' ),
                'product_layout' => esc_html__( 'Single Product', 'alpus-core' ),
                'single'         => esc_html__( 'Single', 'alpus-core' ),
                'archive'        => esc_html__( 'Archive', 'alpus-core' ),
                'popup'          => esc_html__( 'Popup', 'alpus-core' ),
                'cart'           => esc_html__( 'Cart', 'alpus-core' ),
                'checkout'       => esc_html__( 'Checkout', 'alpus-core' ),
                'type'           => esc_html__( 'Post Types', 'alpus-core' ),
                'page_title_bar' => esc_html__( 'Page Title Bar', 'alpus-core' ),
                'favourites'     => esc_html__( 'Favourites', 'alpus-core' ),
                'my-templates'   => esc_html__( 'My Templates', 'alpus-core' ),
            );

            return $categories;
        }

        /**
         * Studio parent categories
         *
         * @since 1.0
         */
        public function studio_big_categories( $categories ) {
            $categories = array( 'header', 'page_title_bar', 'block', 'element', 'footer', 'popup', 'template', 'favourites', 'my-templates' );

            return $categories;
        }

        /**
         * Studio categories that have child categories
         *
         * @since 1.0
         */
        public function studio_has_children( $categories ) {
            $categories = array( 'block', 'element', 'template' );

            return $categories;
        }

        /**
         * Studio categories child of blocks category
         *
         * @since 1.0
         */
        public function studio_block_categories( $categories ) {
            $categories = array( 'demo', 'about', 'banner', 'slider', 'contact', 'megamenu', 'other' );

            return $categories;
        }

        /**
         * Studio block render args
         *
         * @since 1.0
         */
        public function studio_blocks_args( $args ) {
            $args[ 'element_categories' ] = $this->element_categories;

            return $args;
        }
    }

    new Alpus_Studio_Extend();
}
