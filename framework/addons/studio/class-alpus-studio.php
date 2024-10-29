<?php
/**
 * Alpus Studio
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Studio' ) ) {
    /**
     * The Alpus Studio class
     *
     * @since 1.0
     */
    class Alpus_Studio {

        /**
                 * Total blocks per page
                 *
                 * @since 1.0
                 */
        private $limit = 20;

        /**
         * Block update period
         *
         * @since 1.0
         */
        private $update_period = HOUR_IN_SECONDS * 24 * 7; // a week

        /**
         * Page Builder Type
         *
         * 'e' => Elementor Page Builder
         * 'w' => WPBakery Page Builder
         */
        private $page_type = false;

        /**
         * New Template Mode
         *
         * @since 1.0
         */
        public $new_template_mode = false;

        /**
         * Main Categories
         *
         * @since 1.0
         */
        private $big_categories      = array();
        private $has_children        = array();
        private $block_categories    = array();
        private $template_categories = array();
        private $front_categories    = array();

        /**
         * Get category title
         *
         * @since 1.0
         */
        public function get_category_title( $title ) {
            $titles = apply_filters(
                'alpus_studio_category',
                array(
                    'block'          => esc_html__( 'Block', 'alpus-core' ), // Blocks
                    'demo'           => esc_html__( 'Demo', 'alpus-core' ),
                    'about'          => esc_html__( 'About', 'alpus-core' ),
                    'banner'         => esc_html__( 'Banner', 'alpus-core' ),
                    'slider'         => esc_html__( 'Slider', 'alpus-core' ),
                    'contact'        => esc_html__( 'Contact', 'alpus-core' ),
                    'counters'       => esc_html__( 'Counters', 'alpus-core' ),
                    'info_boxes'     => esc_html__( 'Info Boxes', 'alpus-core' ),
                    'testimonial'    => esc_html__( 'Testimonial', 'alpus-core' ),
                    'video'          => esc_html__( 'Video', 'alpus-core' ),
                    'megamenu'       => esc_html__( 'Megamenu', 'alpus-core' ),
                    'pricing'        => esc_html__( 'Pricing', 'alpus-core' ),
                    'other'          => esc_html__( 'Other', 'alpus-core' ),
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
                    'error-404'      => esc_html__( 'Error 404', 'alpus-core' ),
                    'type'           => esc_html__( 'Post Types', 'alpus-core' ),
                    'favourites'     => esc_html__( 'Favourites', 'alpus-core' ),
                    'my-templates'   => esc_html__( 'My Templates', 'alpus-core' ),
                )
            );

            return isset( $titles[ $title ] ) ? $titles[ $title ] : '';
        }

        /**
         * Constructor
         *
         * @since 1.0
         */
        public function __construct() {
            $this->big_categories      = apply_filters( 'alpus_studio_big_category', array( 'block', 'header', 'footer', 'popup', 'template', 'favourites', 'my-templates' ) );
            $this->has_children        = apply_filters( 'alpus_studio_has_children', array( 'block', 'template' ) );
            $this->block_categories    = apply_filters( 'alpus_studio_block_category', array( 'demo', 'about', 'banner', 'slider', 'contact', 'counters', 'info_boxes', 'testimonial', 'video', 'megamenu', 'pricing', 'other' ) );
            $this->template_categories = apply_filters( 'alpus_studio_template_category', array( 'shop', 'product_layout', 'cart', 'checkout', 'single', 'archive', 'error-404', 'type' ) );
            $this->front_categories    = apply_filters( 'alpus_studio_front_category', array( 'block', 'header', 'footer', 'product_layout', 'shop', 'single', 'archive', 'popup', 'type', 'error-404', 'cart', 'checkout', 'favourites', 'my-templates' ) );

            if ( isset( $_REQUEST['vc_editable'] ) && sanitize_text_field( $_REQUEST['vc_editable'] ) && isset( $_POST['block_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $vc_template_option_name = '';

                try {
                    $vc_template_option_name = vc_manager()->vc()->templatesPanelEditor()->getOptionName();
                    add_filter( 'pre_option_' . $vc_template_option_name, array( $this, 'render_frontend_block' ), 10, 3 );
                } catch ( Exception $e ) {
                }

                return;
            }

            if ( wp_doing_ajax() && isset( $_POST['type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $this->page_type         = sanitize_text_field( $_POST['type'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $this->new_template_mode = isset( $_POST['new_template'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            }

            add_action( 'wp_ajax_alpus_studio_import', array( $this, 'import' ) );
            add_action( 'wp_ajax_alpus_studio_filter_category', array( $this, 'filter_category' ) );
            add_action( 'wp_ajax_alpus_studio_favour_block', array( $this, 'favour_block' ) );
            add_action( 'wp_ajax_alpus_studio_save', array( $this, 'update_custom_meta_fields_in_fronteditor' ) );

            if ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] ) {
                if ( defined( 'ELEMENTOR_VERSION' ) && function_exists( 'alpus_is_elementor_preview' ) && alpus_is_elementor_preview() ) {
                    $this->page_type = 'e';
                    add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue' ), 30 );
                    add_action( 'elementor/editor/footer', array( $this, 'get_page_content' ) );
                    add_filter(
                        'alpus_builder_addon_html',
                        function ( $html ) {
                            $html[] = array(
                                'elementor' => '<li id="alpus-elementor-panel-alpus-studio"><i class="fas fa-layer-group"></i>' . sprintf( esc_html__( '%s Studio', 'alpus-core' ), ALPUS_DISPLAY_NAME ) . '</li>',
                            );

                            return $html;
                        },
                        9
                    );
                } elseif ( defined( 'WPB_VC_VERSION' ) && function_exists( 'alpus_is_wpb_preview' ) && alpus_is_wpb_preview() ) {
                    $this->page_type = 'w';
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
                    add_action( 'admin_footer', array( $this, 'get_page_content' ) );
                } elseif ( 'post.php' != $GLOBALS['pagenow'] || 'edit' == sanitize_text_field( $_REQUEST['action'] ) ) {
                    $this->page_type = 'g';
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
                    add_action( 'admin_footer', array( $this, 'get_page_content' ) );
                }

                /*
                 * Fires after setting configuration for page builder.
                 *
                 * @since 1.0
                 */
                do_action( 'alpus_studio_for_builder', $this );
            } elseif ( ! wp_doing_ajax() || ! isset( $_POST['type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $this->new_template_mode = true;

                if ( defined( 'ELEMENTOR_VERSION' ) ) {
                    $this->page_type = 'e';
                } elseif ( defined( 'WPB_VC_VERSION' ) ) {
                    $this->page_type = 'w';
                } else {
                    $this->page_type = 'g';
                }

                add_action( 'admin_footer', array( $this, 'get_page_content' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );

                /*
                 * Fires to determine page builder.
                 *
                 * @since 1.0
                 */
                do_action( 'alpus_determine_builder', $this );
            }
        }

        /**
         * Enqueue scripts and styles
         *
         * @since 1.0
         */
        public function enqueue() {
            if ( doing_action( 'elementor/editor/after_enqueue_styles' ) ) {
                wp_enqueue_style( 'alpus-admin-fonts', apply_filters( 'alpus_admin_fonts', '//fonts.googleapis.com/css?family=Poppins%3A400%2C500%2C600%2C700' ) );
            }

            if ( defined( 'ALPUS_VERSION' ) ) {
                wp_enqueue_style( 'alpus-lightbox', ALPUS_ASSETS . '/vendor/lightbox/lightbox' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPUS_VERSION );
                wp_enqueue_script( 'isotope-pkgd', ALPUS_ASSETS . '/vendor/isotope/isotope.pkgd.min.js', array( 'jquery-core', 'imagesloaded' ), '3.0.6', true );
            }
            wp_enqueue_style( 'alpus-studio', alpus_core_framework_uri( '/addons/studio/studio' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ) );
            wp_enqueue_script( 'alpus-studio', alpus_core_framework_uri( '/addons/studio/studio' . ALPUS_JS_SUFFIX ), array(), ALPUS_CORE_VERSION, true );

            $studio_vars = array(
                'wpnonce' => wp_create_nonce( 'alpus_studio_nonce' ),
                'limit'   => $this->limit,
                'texts'   => array(
                    'loading_failed'     => esc_html__( 'Loading failed!', 'alpus-core' ),
                    'importing_error'    => esc_html__( 'There was an error when importing block. Please try again later!', 'alpus-core' ),
                    'coming_soon'        => esc_html__( 'Coming Soon...', 'alpus-core' ),
                    'theme_display_name' => ALPUS_DISPLAY_NAME,
                ),
            );

            if ( $this->new_template_mode ) {
                $studio_vars['page_type'] = '';

                if ( defined( 'ELEMENTOR_VERSION' ) ) {
                    $studio_vars['page_type'] = 'e';
                } elseif ( defined( 'WPB_VC_VERSION' ) ) {
                    $studio_vars['page_type'] = 'w';
                } else {
                    $studio_vars['page_type'] = 'g';
                }
            } elseif ( isset( $_GET['post'] ) || isset( $_GET['post_id'] ) ) {
                // Add start template at start
                if ( isset( $_GET['post'] ) && (int) $_GET['post'] ) {
                    $post_id = (int) $_GET['post'];
                } else {
                    $post_id = (int) $_GET['post_id'];
                }
                $start_template = get_post_meta( $post_id, 'alpus_start_template', true );

                if ( $start_template && isset( $start_template['id'] ) && isset( $start_template['type'] ) ) {
                    $id   = (int) $start_template['id'];
                    $type = $start_template['type'];

                    if ( ( 'e' == $type && defined( 'ELEMENTOR_VERSION' ) && function_exists( 'alpus_is_elementor_preview' ) && alpus_is_elementor_preview() ) || ( 'w' == $type && defined( 'WPB_VC_VERSION' ) && function_exists( 'alpus_is_wpb_preview' ) && alpus_is_wpb_preview() ) ||
                    'g' == $type ) {
                        $studio_vars['start_template'] = $id;
                    } elseif ( 'my' == $type ) {
                        // @start feature: fs_pb_elementor
                        if ( alpus_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $id, '_elementor_edit_mode', true ) && function_exists( 'alpus_is_elementor_preview' ) && alpus_is_elementor_preview() ) {
                            $studio_vars['start_template_content'] = array(
                                'content' => json_decode( get_post_meta( $id, '_elementor_data', true ) ),
                                'meta'    => array(
                                    'page_css' => get_post_meta( $id, 'page_css', true ),
                                ),
                            );
                        }
                        // @end feature: fs_pb_elementor
                    }
                }
                delete_post_meta( $post_id, 'alpus_start_template' );
            }
            /*
             * Fiters styles and scripts in studio.
             *
             * @since 1.0
             */
            wp_localize_script( 'alpus-studio', 'alpus_studio', apply_filters( 'alpus_studio_vars', $studio_vars ) );
        }

        /**
         * Import Alpus blocks in WPB frontend editor
         *
         * @since 1.0
         */
        public function render_frontend_block( $flag, $option, $default ) {
            if ( isset( $_POST['meta'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $GLOBALS['alpus_studio_meta'] = alpus_escaped( $_POST['meta'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

                if ( isset( $_POST['meta']['page_js'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    add_filter( 'print_footer_scripts', array( $this, 'print_custom_js_frontend_editor' ) );
                }
            }

            if ( isset( $_POST['content'] ) && $_POST['content'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                return array( '1' => array( 'template' => alpus_escaped( wp_unslash( $_POST['content'] ) ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            }

            return '';
        }

        /**
         * Print custom js frontend editor.
         *
         * @since 1.0
         */
        public function print_custom_js_frontend_editor( $flag ) {
            global $alpus_studio_meta;

            if ( $alpus_studio_meta && isset( $alpus_studio_meta['page_js'] ) ) {
                echo '<script data-type="alpus-studio-custom-js">';
                echo trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', wp_unslash( $alpus_studio_meta['page_js'] ) ) );
                echo '</script>';
            }
            unset( $GLOBALS['alpus_studio_meta'] );

            return $flag;
        }

        /**
         * Import alpus blocks in Elementor, Visual Composer backend editor, WPBakery
         *
         * @since 1.0
         */
        public function import( $pure_return = false ) {
            check_ajax_referer( 'alpus_studio_nonce', 'wpnonce' );

            if ( isset( $_POST['block_id'] ) ) {
                if ( isset( $_POST['mine'] ) && 'true' == sanitize_text_field( $_POST['mine'] ) ) {
                    $id     = (int) $_POST['block_id'];
                    $result = array();

                    // @start feature: fs_pb_elementor
                    if ( 'e' == sanitize_text_field( $_POST['type'] ) && alpus_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $id, '_elementor_edit_mode', true ) ) {
                        $result = array(
                            'content' => json_decode( get_post_meta( $id, '_elementor_data', true ) ),
                            'meta'    => array(
                                'page_css' => get_post_meta( $id, 'page_css', true ),
                            ),
                        );
                    }
                    // @end feature: fs_pb_elementor

                    if ( 'g' == sanitize_text_field( $_POST['type'] ) && use_block_editor_for_post( $id ) ) {
                        $result = array(
                            'content' => rawurldecode( get_post( $id )->post_content ),
                            'meta'    => array(
                                'page_css' => get_post_meta( $id, 'page_css', true ),
                                'page_js'  => get_post_meta( $id, 'page_js', true ),
                            ),
                        );
                    }

                    /*
                     * Filters the import result.
                     *
                     * @param array $result The import result
                     * @since 1.0
                     */
                    return wp_send_json( apply_filters( 'alpus_import_result', $result ) );
                }

                require_once alpus_core_framework_path( ALPUS_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
                $importer_api = new Alpus_Importer_API();
                $args         = $importer_api->generate_args( false );
                $url          = add_query_arg( $args, $importer_api->get_url( 'studio_block_content' ) );
                $url          = add_query_arg( array( 'block_id' => ( (int) $_POST['block_id'] ) ), $url );
                $block        = $importer_api->get_response( $url );

                if ( is_wp_error( $block ) || ! $block || ! isset( $block['content'] ) ) {
                    if ( $pure_return ) {
                        return false;
                    }
                    echo wp_json_encode( array( 'error' => esc_js( __( 'Security issue found! Please try again later.', 'alpus-core' ) ) ) );
                    die();
                }
                $block_content = $block['content'];

                // process attachments
                if ( isset( $block['images'] ) ) {
                    $block_content = $this->process_posts( $block_content, $block['images'] );
                }

                // process contact forms
                if ( isset( $block['posts'] ) ) {
                    $block_content = $this->process_posts( $block_content, $block['posts'], false );
                }
                // replace urls
                $block_content = str_replace( $block['url'], get_home_url(), $block_content );

                if ( 'e' == $this->page_type ) {
                    $block_content = json_decode( $block_content, true );
                }

                $result = array( 'content' => $block_content );

                if ( isset( $block['meta'] ) && $block['meta'] ) {
                    $result['meta'] = json_decode( $block['meta'], true );
                }
                /**
                 * Filters the import result.
                 *
                 * @param array $result The import result
                 * @param array $block  The import block
                 *
                 * @since 1.0
                 */
                $result = apply_filters( 'alpus_import_result', $result, $block );

                if ( $pure_return ) {
                    return $result;
                }

                return wp_send_json( $result );
            }
        }

        /**
         * Filter category.
         *
         * @since 1.0
         */
        public function filter_category() {
            check_ajax_referer( 'alpus_studio_nonce', 'wpnonce' );

            if ( isset( $_POST['type'] ) ) {
                $this->page_type = sanitize_text_field( $_POST['type'] );
            }

            if ( isset( $_POST['full_wrapper'] ) && sanitize_text_field( $_POST['full_wrapper'] ) ) {
                $this->get_page_content();
            } else {
                $page            = isset( $_POST['page'] ) && $_POST['page'] ? (int) $_POST['page'] : 1;
                $blocks          = $this->get_blocks();
                $category_blocks = array();
                $favourites_map  = array();
                $favourites_key  = '';

                if ( 'e' == $this->page_type ) {
                    $favourites_key = 'alpus_studio_favourites_e';
                } elseif ( 'w' === $this->page_type ) {
                    $favourites_key = 'alpus_studio_favourites_w';
                } else {
                    $favourites_key = 'alpus_studio_favourites_g';
                }
                /**
                 * For theme developers
                 * Filters favourite key in terms of page builder.
                 *
                 * @param string $favourites_key The favourite key
                 * @param string $page_type      The page builder
                 *
                 * @since 1.0
                 */
                $favourites_key = apply_filters( 'alpus_favourites_key', $favourites_key, $this->page_type );

                if ( $favourites_key ) {
                    $favourites = get_option( $favourites_key );

                    if ( $favourites ) {
                        foreach ( $favourites as $favourite ) {
                            $favourites_map[ $favourite['block_id'] ] = true;
                        }
                    }
                }

                if ( ! empty( $_POST['search'] ) ) {
                    // Filtered blocks
                    $search = sanitize_text_field( $_POST['search'] );
                    $blocks = array_filter(
                        $blocks,
                        function ( $block ) use ( $search ) {
                            $pattern = '/' . $search . '/i';

                            return  false != preg_match( $pattern, $block['d'] ) || false != preg_match( $pattern, $block['t'] );
                        }
                    );
                }

                $all_blocks = $blocks;
                $all_limit  = $this->limit;

                if ( isset( $_POST['category_id'] ) ) {
                    $category_id = (int) $_POST['category_id'];

                    if ( (int) $category_id ) {
                        // Templates in given category
                        foreach ( $blocks as $block ) {
                            $categories = explode( ',', $block['c'] ?? '' );

                            if ( in_array( $category_id, $categories ) ) {
                                $category_blocks[] = $block;
                            }
                        }
                        $all_blocks      = $category_blocks;
                        $category_blocks = array_slice( $category_blocks, ( $page - 1 ) * $this->limit, $this->limit );
                    } elseif ( '*' == $category_id ) {
                        $all_blocks = $favourites;

                        if ( isset( $_POST['current_count'] ) ) {
                            $current_count   = (int) $_POST['current_count'];
                            $category_blocks = array_slice( $favourites, $current_count, $this->limit );
                            $all_limit       = $current_count;
                        } else {
                            $category_blocks = array_slice( $favourites, ( $page - 1 ) * $this->limit, $this->limit );
                        }
                    } elseif ( 'my' == $category_id ) {
                        $posts           = get_posts(
                            array(
                                'post_type'   => ALPUS_NAME . '_template',
                                'numberposts' => -1,
                            )
                        );
                        $all_blocks      = $posts;
                        $category_blocks = array_slice( $posts, ( $page - 1 ) * $this->limit, $this->limit );
                    } elseif ( 0 == $category_id ) {
                        // All blocks
                        $category_blocks = array_slice( $blocks, ( $page - 1 ) * $this->limit, $this->limit );
                    }
                }

                if ( ! empty( $_POST['search'] ) ) {
                    echo '<div id="total_pages">' . ceil( count( $all_blocks ) / $all_limit ) . '</div>';
                }

                if ( ! empty( $category_blocks ) ) {
                    $args = array(
                        'blocks'         => $category_blocks,
                        'studio'         => $this,
                        'favourites_map' => $favourites_map,
                    );
                    require alpus_core_framework_path( ALPUS_CORE_ADDONS . '/studio/blocks.php' );
                }
            }
            die;
        }

        /**
         * Favour block
         *
         * @since 1.0
         */
        public function favour_block() {
            check_ajax_referer( 'alpus_studio_nonce', 'wpnonce' );

            $block_id = isset( $_POST['block_id'] ) && $_POST['block_id'] ? (int) $_POST['block_id'] : 0;

            if ( $block_id > 0 ) {
                $favourites_key = '';

                if ( 'e' == $this->page_type ) {
                    $favourites_key = 'alpus_studio_favourites_e';
                } elseif ( 'w' == $this->page_type ) {
                    $favourites_key = 'alpus_studio_favourites_w';
                } else {
                    $favourites_key = 'alpus_studio_favourites_g';
                }
                /**
                 * For theme developers
                 * Filters favourite key in terms of page builder.
                 *
                 * @param string $favourites_key The favourite key
                 * @param string $page_type      The page builder
                 *
                 * @since 1.0
                 */
                $favourites_key = apply_filters( 'alpus_favourites_key', $favourites_key, $this->page_type );

                if ( $favourites_key ) {
                    $favourites = get_option( $favourites_key );

                    if ( ! $favourites ) {
                        $favourites = array();
                    }

                    if ( isset( $_POST['active'] ) ) {
                        if ( $_POST['active'] ) { // Add
                            $blocks = $this->get_blocks();

                            foreach ( $blocks as $block ) {
                                if ( $block_id == $block['block_id'] ) {
                                    $favourites[] = $block;
                                    update_option( $favourites_key, $favourites );
                                    die;
                                }
                            }
                        } else { // Remove
                            foreach ( $favourites as $i => $favourite ) {
                                if ( $block_id == $favourite['block_id'] ) {
                                    unset( $favourites[ $i ] );
                                    update_option( $favourites_key, $favourites );

                                    if ( isset( $_POST['current_count'] ) ) {
                                        $current_count   = (int) $_POST['current_count'];
                                        $category_blocks = array_slice( $favourites, $current_count, 1 );
                                        $favourites_map  = array();

                                        foreach ( $favourites as $favourite ) {
                                            $favourites_map[ $favourite['block_id'] ] = true;
                                        }

                                        if ( ! empty( $category_blocks ) ) {
                                            $args = array(
                                                'blocks'         => $category_blocks,
                                                'studio'         => $this,
                                                'favourites_map' => $favourites_map,
                                            );
                                            require alpus_core_framework_path( ALPUS_CORE_ADDONS . '/studio/blocks.php' );
                                        }
                                    }

                                    die;
                                }
                            }
                        }
                    }
                }
            }
            die;
        }

        /**
         * Save post meta fields such as custom css and js in frontend editor
         *
         * @since 1.0
         */
        public function update_custom_meta_fields_in_fronteditor() {
            check_ajax_referer( 'alpus_studio_nonce', 'nonce' );

            if ( isset( $_POST['fields'] ) && isset( $_POST['post_id'] ) ) {
                $post_id = intval( $_POST['post_id'] );

                foreach ( $_POST['fields'] as $key => $value ) {
                    if ( ! $value ) {
                        continue;
                    }
                    $key            = sanitize_key( $key );
                    $value          = sanitize_text_field( $value );
                    $original_value = get_post_meta( $post_id, $key, true );

                    if ( strpos( $original_value, $value ) === false ) {
                        if ( strpos( $value, $original_value ) !== false ) {
                            $original_value = '';
                        }
                        update_post_meta( $post_id, $key, $original_value . wp_strip_all_tags( wp_unslash( $value ) ) );
                    }
                }
                wp_send_json_success();
            }
        }

        /**
         * Get page content.
         *
         * @since 1.0
         */
        public function get_page_content() {
            $block_categories  = $this->get_block_categories();
            $blocks            = $this->get_blocks();
            $only_blocks_count = 0;

            if ( is_array( $blocks ) ) {
                foreach ( $blocks as $block ) {
                    $categories = explode( ',', $block['c'] ?? '' );

                    foreach ( $categories as $category ) {
                        if ( (int) $category < 8 || (int) $category > 12 ) {
                            ++ $only_blocks_count;
                            break;
                        }
                    }
                }
            }

            if ( is_array( $block_categories ) ) {
                $args = apply_filters(
                    'alpus_studio_blocks_args',
                    array(
                        'categories'          => $block_categories,
                        'page_type'           => $this->page_type,
                        'studio'              => $this,
                        'total_pages'         => ceil( count( $blocks ) / $this->limit ),
                        'total_count'         => count( $blocks ),
                        'big_categories'      => $this->big_categories,
                        'has_children'        => $this->has_children,
                        'block_categories'    => $this->block_categories,
                        'template_categories' => $this->template_categories,
                        'front_categories'    => $this->front_categories,
                        'blocks_pages'        => ceil( $only_blocks_count / $this->limit ),
                    )
                );
                require alpus_core_framework_path( ALPUS_CORE_ADDONS . '/studio/blocks-wrapper.php' );
            }
        }

        /**
         * Import related posts such as attachments and contact forms
         *
         * @since 1.0
         */
        private function process_posts( $block_content, $posts, $is_attachment = true ) {
            if ( ! trim( $posts ) ) {
                return $block_content;
            }
            $posts = json_decode( trim( $posts ), true );

            if ( empty( $posts ) ) {
                return $block_content;
            }

            if ( isset( $posts['revslider'] ) ) {
                if ( class_exists( 'RevSlider' ) ) {
                    // Importer remote API
                    require_once alpus_core_framework_path( ALPUS_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
                    $importer_api   = new Alpus_Importer_API( $posts['revslider'][0] );
                    $demo_file_path = $importer_api->get_remote_demo();

                    if ( $demo_file_path && ! is_wp_error( $demo_file_path ) ) {
                        $slider   = new RevSlider();
                        $imported = $slider->importSliderFromPost( true, false, $demo_file_path . '/' . $posts['revslider'][1] );
                        $importer_api->delete_temp_dir();

                        if ( is_array( $imported ) && $imported['success'] && isset( $imported['sliderID'] ) ) {
                            $block_content = str_replace( '{{{' . $posts['revslider'][2] . '}}}', $imported['sliderID'], $block_content );
                        }
                    }
                }
                unset( $posts['revslider'] );
            }

            // Check if image is already imported by its ID.
            $id_arr = array();

            foreach ( array_keys( $posts ) as $old_id ) {
                $id_arr[] = ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            }
            $args = array(
                'posts_per_page' => -1,
                'meta_query'     => array(
                    array(
                        'key'     => '_alpus_studio_id',
                        'value'   => $id_arr,
                        'compare' => 'IN',
                    ),
                ),
            );

            if ( $is_attachment ) {
                $args['post_type']   = 'attachment';
                $args['post_status'] = 'inherit';
            } else {
                $args['post_type']   = 'wpcf7_contact_form';
                $args['post_status'] = 'publish';
            }
            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                foreach ( $query->posts as $post ) {
                    $old_id = str_replace( ( (int) $_POST['block_id'] ) . '-', '', get_post_meta( $post->ID, '_alpus_studio_id', true ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

                    if ( 'wpcf7_contact_form' == $post->post_type ) {
                        if ( 'w' == $this->page_type ) {
                            $new_content = '[contact-form-7 id="' . $post->ID . '"]';
                        } else {
                            $new_content = '[contact-form-7 id=\\"' . $post->ID . '\\"]';
                        }
                    } else { // attachment
                        $new_content = $post->ID;
                    }

                    $block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', $new_content, $block_content );

                    unset( $posts[ $old_id ] );
                }
            }

            if ( ! empty( $posts ) ) {
                if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
                    define( 'WP_LOAD_IMPORTERS', true ); // we are loading importers
                }

                if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
                    require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                }

                if ( ! class_exists( 'WP_Import' ) ) { // if WP importer doesn't exist
                    require_once alpus_core_framework_path( ALPUS_FRAMEWORK_ADMIN . '/importer/wordpress-importer.php' );
                }

                if ( current_user_can( 'edit_posts' ) && class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {
                    $importer                    = new WP_Import();
                    $importer->fetch_attachments = true;

                    if ( $is_attachment ) {
                        add_filter( 'upload_mimes', array( $this, 'enable_svg_import' ), 99 );

                        foreach ( $posts as $old_id => $image_url ) {
                            $post_data = array(
                                'post_title'   => substr( $image_url, strrpos( $image_url, '/' ) + 1, -4 ),
                                'post_content' => '',
                                'upload_date'  => gmdate( 'Y-m-d H:i:s' ),
                                'post_status'  => 'inherit',
                            );

                            if ( 0 === strpos( $image_url, '//' ) ) {
                                $image_url = 'https:' . $image_url;
                            }
                            $import_id = $importer->process_attachment( $post_data, $image_url );

                            if ( is_wp_error( $import_id ) ) {
                                // if image does not exist
                                if ( 'e' == $this->page_type ) {
                                    // replace for svg media
                                    $block_content = str_replace( '"id":{{{' . ( (int) $old_id ) . '}}},"url":"', '"id":"","url":"', $block_content );
                                    $new_content   = '"id":"","url":""';
                                } else {
                                    $new_content = '';

                                    $GLOBALS['alpus_studio_attachment'] = $import_id;
                                    $block_content                      = preg_replace_callback(
                                        '|\{\{\{' . ( (int) $old_id ) . ':([^\}]*)\}\}\}|',
                                        function ( $match ) {
                                            return '"' . $match[1] . '":""';
                                        },
                                        $block_content
                                    );
                                    unset( $GLOBALS['alpus_studio_attachment'] );
                                }
                            } else {
                                update_post_meta( $import_id, '_alpus_studio_id', ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                                $new_content = $import_id;
                            }
                            $block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', $new_content, $block_content );
                        }
                    } else {
                        foreach ( $posts as $old_id => $old_post_data ) {
                            $post_data = array(
                                'post_title'   => sanitize_text_field( $old_post_data['title'] ),
                                'post_type'    => sanitize_text_field( $old_post_data['post_type'] ),
                                'post_content' => '',
                                'upload_date'  => gmdate( 'Y-m-d H:i:s' ),
                                'post_status'  => 'publish',
                            );
                            $post_data = wp_slash( $post_data );
                            $import_id = wp_insert_post( $post_data, true );

                            if ( is_wp_error( $import_id ) ) {
                                // if post does not exist
                                $new_content = '';
                            } else {
                                update_post_meta( $import_id, '_alpus_studio_id', ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

                                if ( isset( $old_post_data['meta'] ) ) {
                                    foreach ( $old_post_data['meta'] as $meta_key => $meta_value ) {
                                        update_post_meta( $import_id, $meta_key, $meta_value );
                                    }
                                }

                                if ( 'w' == $this->page_type ) {
                                    $new_content = '[contact-form-7 id="' . $import_id . '"]';
                                } else {
                                    $new_content = '[contact-form-7 id=\\"' . $import_id . '\\"]';
                                }
                            }
                            $block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', $new_content, $block_content );
                        }
                    }
                }
            }

            /*
             * Filters styles and scripts for related posts such as attachments and contact forms.
             *
             * @since 1.0
             */
            return apply_filters( 'alpus_studio_block_content', $block_content );
        }

        /**
         * Get block categories.
         *
         * @since 1.0
         */
        private function get_block_categories() {
            // get block categories
            if ( 'e' == $this->page_type ) {
                $transient_key = 'alpus_block_categories_e';
            } elseif ( 'w' == $this->page_type ) {
                $transient_key = 'alpus_block_categories_w';
            } else {
                $transient_key = 'alpus_block_categories_g';
            }
            /**
             * For theme developers
             * Filters transient key in terms of page builder.
             *
             * @param string $transient_key The transient key
             * @param string $page_type     The page builder
             *
             * @since 1.0
             */
            $transient_key = apply_filters( 'alpus_transient_key', $transient_key, $this->page_type );

            $block_categories = get_site_transient( $transient_key );

            if ( ! $block_categories ) {
                require_once alpus_core_framework_path( ALPUS_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
                $importer_api = new Alpus_Importer_API();

                $args             = $importer_api->generate_args( false );
                $args['limit']    = $this->limit;
                $args['type']     = $this->page_type;
                $block_categories = $importer_api->get_response( add_query_arg( $args, $importer_api->get_url( 'studio_block_categories' ) ) );

                if ( is_wp_error( $block_categories ) || ! $block_categories ) {
                    return esc_html__( 'Could not connect to the API Server! Please try again later.', 'alpus-core' );
                }
                set_site_transient( $transient_key, $block_categories, $this->update_period );
            }

            // Add favourite block category
            if ( 'e' == $this->page_type ) {
                $favourite_key = 'alpus_studio_favourites_e';
            } elseif ( 'w' == $this->page_type ) {
                $favourite_key = 'alpus_studio_favourites_w';
            } else {
                $favourite_key = 'alpus_studio_favourites_g';
            }
            /**
             * For theme developers
             * Filters favourite key in terms of page builder.
             *
             * @param string $favourites_key The favourite key
             * @param string $page_type      The page builder
             *
             * @since 1.0
             */
            $favourite_key = apply_filters( 'alpus_favourites_key', $favourite_key, $this->page_type );

            $favourites = get_option( $favourite_key );

            if ( ! $favourites ) {
                $favourites = array();
            }

            $block_categories[] = array(
                'id'    => '*',
                'title' => 'favourites',
                'count' => count( $favourites ),
                'total' => ceil( count( $favourites ) / $this->limit ),
            );

            $templates_count = wp_count_posts( ALPUS_NAME . '_template' )->publish;

            $block_categories[] = array(
                'id'    => 'my',
                'title' => 'my-templates',
                'count' => $templates_count,
                'total' => ceil( $templates_count / $this->limit ),
            );

            return $block_categories;
        }

        /**
         * Get blocks.
         *
         * @since 1.0
         */
        private function get_blocks() {
            // get blocks
            if ( 'e' == $this->page_type ) {
                $transient_key = 'alpus_blocks_e';
            } elseif ( 'w' == $this->page_type ) {
                $transient_key = 'alpus_blocks_wpb';
            } else {
                $transient_key = 'alpus_blocks_g';
            }
            /**
             * For theme developers
             * Filters transient key in terms of page builder.
             *
             * @param string $transient_key The transient key
             * @param string $page_type     The page builder
             *
             * @since 1.0
             */
            $transient_key = apply_filters( 'alpus_transient_key', $transient_key, $this->page_type );

            $blocks = get_site_transient( $transient_key );

            if ( ! $blocks ) {
                if ( ! isset( $importer_api ) ) {
                    require_once alpus_core_framework_path( ALPUS_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
                    $importer_api = new Alpus_Importer_API();
                    $args         = $importer_api->generate_args( false );
                    $args['type'] = $this->page_type;
                }
                $blocks = $importer_api->get_response( add_query_arg( $args, $importer_api->get_url( 'studio_blocks' ) ) );

                if ( is_wp_error( $blocks ) || ! $blocks ) {
                    return esc_html__( 'Could not connect to the API Server! Please try again later.', 'alpus-core' );
                }
                set_site_transient( $transient_key, $blocks, $this->update_period );
            }

            return $blocks;
        }

        public function enable_svg_import( $mimes ) {
            $mimes['svg'] = 'image/svg+xml';

            return $mimes;
        }
    }

    new Alpus_Studio();
}
