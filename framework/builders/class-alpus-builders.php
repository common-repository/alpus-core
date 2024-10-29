<?php
/**
 * Alpus Template
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

class Alpus_Builders extends Alpus_Base {

    /**
     * The builder Type e.g: header, footer, single product
     *
     * @since 1.0
     *
     * @var array[string]
     */
    protected $template_types = array();

    /**
     * The post id
     *
     * @since 1.0
     *
     * @var int
     */
    protected $post_id = '';

    /**
     * Constructor
     *
     * @since 1.0
     */
    public function __construct() {
        $this->_init_template_types();

        add_action( 'init', array( $this, 'init_builders' ) );

        // Print Alpus Template Builder Page's Header
        if ( current_user_can( 'edit_posts' ) && 'edit.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ALPUS_NAME . '_template' == sanitize_text_field( $_REQUEST['post_type'] ) ) {
            add_action( 'all_admin_notices', array( $this, 'print_template_dashboard_header' ) );
            add_filter( 'views_edit-' . ALPUS_NAME . '_template', array( $this, 'print_template_category_tabs' ) );
        }

        // Add "template type" column to posts table.
        add_filter( 'manage_' . ALPUS_NAME . '_template_posts_columns', array( $this, 'admin_column_header' ) );
        add_action( 'manage_' . ALPUS_NAME . '_template_posts_custom_column', array( $this, 'admin_column_content' ), 10, 2 );

        // Ajax
        add_action( 'wp_ajax_alpus_save_template', array( $this, 'save_alpus_template' ) );
        add_action( 'wp_ajax_nopriv_alpus_save_template', array( $this, 'save_alpus_template' ) );

        // Delete post meta when post is delete
        add_action( 'delete_post', array( $this, 'delete_template' ) );

        // Change Admin Post Query with alpus template types
        add_action( 'parse_query', array( $this, 'filter_template_type' ) );

        // Resources
        if ( ( isset( $_REQUEST['page'] ) && 'alpus-sidebar' == sanitize_text_field( $_REQUEST['page'] ) ) || ( isset( $_REQUEST['post_type'] ) && ALPUS_NAME . '_template' == sanitize_text_field( $_REQUEST['post_type'] ) ) ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
        }

        // Add template builder classes to body class
        add_filter( 'body_class', array( $this, 'add_body_class_for_preview' ) );

        if ( is_admin() ) {
            if ( isset( $_REQUEST['post'] ) && sanitize_text_field( $_REQUEST['post'] ) ) {
                $this->post_id = intval( $_REQUEST['post'] );

                if ( alpus_is_elementor_preview() ) {
                    add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'load_assets' ), 30 );
                    add_filter( 'alpus_core_admin_localize_vars', array( $this, 'add_addon_htmls' ) );
                }
            }
        }
    }

    /**
     * Init template types
     *
     * @since 1.0
     */
    private function _init_template_types() {
        $this->template_types = self::get_template_types();
        $rc_template_builders = get_theme_mod( 'resource_template_builders' );
        $builders_array       = json_decode( wp_unslash( empty( $rc_template_builders ) ? '' : $rc_template_builders ), true );

        if ( ! empty( $builders_array ) && is_array( $builders_array ) ) {
            foreach ( $builders_array as $key => $value ) {
                unset( $this->template_types[ $key ] );
            }
        }
        /*
         * Filters template builder types.
         *
         * @since 1.0
         */
        $this->template_types = apply_filters( 'alpus_template_types', $this->template_types );
        $this->load_template_builders();
    }

    /**
     * Load Template Builders
     *
     * @since 1.2.0
     */
    public function load_template_builders() {
        foreach ( $this->template_types as $key => $value ) {
            $file = $key;

            /*
             * Filters the template builder when it's loading.
             *
             * @since 1.0
             */
            if ( in_array( $file, apply_filters( 'alpus_exclude_builder_load', array( 'block', 'footer' ) ) ) ) {
                continue;
            }

            if ( 'product_layout' == $file ) {
                $file = 'single-product';
            }

            if ( 'shop_layout' == $file ) {
                $file = 'shop';
            }

            if ( file_exists( alpus_core_framework_path( ALPUS_BUILDERS . "/{$file}/class-alpus-{$file}-builder.php" ) ) ) {
                require_once alpus_core_framework_path( ALPUS_BUILDERS . "/{$file}/class-alpus-{$file}-builder.php" );
            }
        }
    }
    /**
     * Get template builder types.
     *
     * @since 1.2.0
     */
    public static function get_template_types() {
        $builders = array();

        // @start feature: fs_builder_block
        if ( alpus_get_feature( 'fs_builder_block' ) ) {
            $builders['block'] = esc_html__( 'Block Builder', 'alpus-core' );
        }
        // @end feature: fs_builder_block

        // @start feature: fs_builder_header
        if ( alpus_get_feature( 'fs_builder_header' ) ) {
            $builders['header'] = esc_html__( 'Header Builder', 'alpus-core' );
        }
        // @end feature: fs_builder_header

        // @start feature: fs_builder_footer
        if ( alpus_get_feature( 'fs_builder_footer' ) ) {
            $builders['footer'] = esc_html__( 'Footer Builder', 'alpus-core' );
        }
        // @end feature: fs_builder_footer

        // @start feature: fs_builder_popup
        if ( alpus_get_feature( 'fs_builder_popup' ) ) {
            $builders['popup'] = esc_html__( 'Popup Builder', 'alpus-core' );
        }
        // @end feature: fs_builder_popup

        // @start feature: fs_plugin_woocommerce
        if ( class_exists( 'WooCommerce' ) && alpus_get_feature( 'fs_plugin_woocommerce' ) ) {
            // @start feature: fs_builder_singleproduct
            if ( alpus_get_feature( 'fs_builder_singleproduct' ) ) {
                $builders['product_layout'] = esc_html__( 'Single Product Builder', 'alpus-core' );
            }
            // @end feature: fs_builder_singleproduct

            // @start feature: fs_builder_shop
            if ( alpus_get_feature( 'fs_builder_shop' ) ) {
                $builders['shop_layout'] = esc_html__( 'Shop Builder', 'alpus-core' );
            }
            // @end feature: fs_builder_shop

            // @start feature: fs_builder_cart
            if ( alpus_get_feature( 'fs_builder_cart' ) ) {
                $builders['cart'] = esc_html__( 'Cart Builder', 'alpus-core' );
            }
            // @end feature: fs_builder_cart

            // @start feature: fs_builder_checkout
            if ( alpus_get_feature( 'fs_builder_checkout' ) ) {
                $builders['checkout'] = esc_html__( 'Checkout Builder', 'alpus-core' );
            }
            // @end feature: fs_builder_checkout
        }
        // @end feature: fs_plugin_woocommerce

        // @start feature: fs_builder_single
        if ( alpus_get_feature( 'fs_builder_single' ) ) {
            $builders['single'] = esc_html__( 'Single Builder', 'alpus-core' );
        }
        // @end feature: fs_builder_single

        // @start feature: fs_builder_archive
        if ( alpus_get_feature( 'fs_builder_archive' ) ) {
            $builders['archive'] = esc_html__( 'Archive Builder', 'alpus-core' );
        }
        // @end feature: fs_builder_archive

        // @start feature: fs_builder_type
        if ( alpus_get_feature( 'fs_builder_type' ) ) {
            $builders['type'] = esc_html__( 'Type Builder', 'alpus-core' );
        }
        // @end feature: fs_builder_type

        return $builders;
    }
    /**
     * Add addon html to admin's localize vars.
     *
     * @param array $vars
     *
     * @return array $vars
     *
     * @since 1.0
     */
    public function add_addon_htmls( $vars ) {
        /*
         * Filters the addon html (ex.: custom css and js) which are adding to admin's localize vars.
         *
         * @since 1.0
         */
        $vars['builder_addons'] = apply_filters( 'alpus_builder_addon_html', array() );
        $vars['theme_url']      = esc_url( get_parent_theme_file_uri() );

        return $vars;
    }

    /**
     * Enqueue style and script
     *
     * @since 1.0
     */
    public function load_assets() {
        wp_enqueue_style( 'alpus-core-template-builder', alpus_core_framework_uri( '/builders/template-builder' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );
        wp_enqueue_script( 'alpus-core-template-builder', alpus_core_framework_uri( '/builders/template-builder' . ALPUS_JS_SUFFIX ), array(), false, true );

        // Studio Import Functions
        if ( ! isset( $_REQUEST['page'] ) || 'alpus-sidebar' != sanitize_text_field( $_REQUEST['page'] ) ) {
            wp_enqueue_script( 'alpus-studio', alpus_core_framework_uri( '/addons/studio/studio' . ALPUS_JS_SUFFIX ), array(), ALPUS_CORE_VERSION, true );
        }
    }

    /**
     * Add body class for preview.
     *
     * @param array $classes The class list
     *
     * @return array The class List
     *
     * @since 1.0
     */
    public function add_body_class_for_preview( $classes ) {
        if ( ALPUS_NAME . '_template' == get_post_type() ) {
            $template_category = get_post_meta( get_the_ID(), ALPUS_NAME . '_template_type', true );

            if ( ! $template_category ) {
                $template_category = 'block';
            }

            $classes[] = 'alpus_' . $template_category . '_template';
        }

        return $classes;
    }

    /**
     * Register new template type.
     *
     * @since 1.0
     */
    public function init_builders() {
        add_filter(
            'alpus_core_admin_localize_vars',
            function ( $vars ) {
                $vars['layout_save']                       = true;
                $vars['template_type']                     = $this->post_id ? get_post_meta( $this->post_id, ALPUS_NAME . '_template_type', true ) : 'layout';
                $vars['texts']['elementor_addon_settings'] = ALPUS_DISPLAY_NAME . esc_html__( ' Settings', 'alpus-core' );

                if ( defined( 'ALPUS_VERSION' ) && $vars['template_type'] && 'layout' != $vars['template_type'] ) {
                    $layouts = wp_json_encode( alpus_get_option( 'conditions' ) );

                    if ( false !== strpos( $layouts, '"' . $this->post_id . '"' ) ) {
                        $vars['layout_save'] = false;
                    }
                } else {
                    $vars['layout_save'] = false;
                }

                return $vars;
            }
        );

        register_post_type(
            ALPUS_NAME . '_template',
            array(
                'label'               => ALPUS_DISPLAY_NAME . esc_html__( ' Templates', 'alpus-core' ),
                'exclude_from_search' => true,
                'has_archive'         => false,
                'public'              => true,
                'supports'            => array( 'title', 'editor', 'alpus', 'alpus-core' ),
                'can_export'          => true,
                'show_in_rest'        => true,
                'show_in_menu'        => false,
            )
        );
    }

    /**
     * Hide page.
     *
     * @since 1.0
     */
    public function hide_page( $class ) {
        return $class . ' hidden';
    }

    /**
     * Print template dashboard header.
     *
     * @since 1.0
     */
    public function print_template_dashboard_header() {
        if ( class_exists( 'Alpus_Admin_Panel' ) ) {
            $this->load_assets();
            $title        = array(
                'title' => esc_html__( 'Templates Builder', 'alpus-core' ),
                'desc'  => sprintf( esc_html__( 'Build any part of your site with %1$s Template Builder. This provides an easy but powerful way to build a full site with hundreds of pre-built templates from %1$s Studio.', 'alpus-core' ), ALPUS_DISPLAY_NAME ),
            );
            $admin_config = Alpus_Admin::get_instance()->admin_config;
            Alpus_Admin_Panel::get_instance()->view_header( 'templates_builder', $admin_config, $title );
            ?>
			<div class="alpus-admin-panel-body templates-builder">
				<div class="alpus-template-actions buttons">
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . ALPUS_NAME . '_template' ) ); ?>" class="page-title-action alpus-add-new-template button button-primary button-large"><?php esc_html_e( 'Add New Template', 'alpus-core' ); ?></a>
				</div>
			<?php
            add_action( 'admin_footer', array( $this, 'print_template_dashboard_footer' ) );
        }
    }

    /**
     * Print template dashboard footer.
     *
     * @since 1.2
     */
    public function print_template_dashboard_footer() {
        echo '</div><!-- End alpus-admin-panel-body -->';
        Alpus_Admin_Panel::get_instance()->view_footer( 'templates_builder' );
    }

    /**
     * Print template category tabs.
     *
     * @since 1.0
     */
    public function print_template_category_tabs( $views = array() ) {
        echo '<div class="nav-tab-wrapper" id="alpus-template-nav">';

        $curslug = '';

        if ( isset( $_GET ) && isset( $_GET['post_type'] ) && ALPUS_NAME . '_template' == sanitize_text_field( $_GET['post_type'] ) && isset( $_GET[ ALPUS_NAME . '_template_type' ] ) ) {
            $curslug = sanitize_text_field( $_GET[ ALPUS_NAME . '_template_type' ] );
        }

        echo '<a class="nav-tab' . ( '' == $curslug ? ' nav-tab-active' : '' ) . '" href="' . admin_url( 'edit.php?post_type=' . ALPUS_NAME . '_template' ) . '">' . esc_html__( 'All Builder', 'alpus-core' ) . '</a>';

        foreach ( $this->template_types as $slug => $name ) {
            echo '<a class="nav-tab' . ( $slug == $curslug ? ' nav-tab-active' : '' ) . '" href="' . admin_url( 'edit.php?post_type=' . ALPUS_NAME . '_template&' . ALPUS_NAME . '_template_type=' . $slug ) . '">' . sprintf( esc_html__( '%s', 'alpus-core' ), $name ) . '</a>';
        }

        echo '</div>';

        wp_enqueue_style( 'alpus-lightbox' );
        wp_enqueue_script( 'alpus-lightbox' );

        ?>

		<div class="alpus-modal-overlay"></div>
		<div id="alpus_new_template" class="alpus-modal alpus-new-template-modal">
			<button class="alpus-modal-close dashicons dashicons-no-alt"></button>
			<div class="alpus-modal-box">
				<div class="alpus-modal-header">
					<h2><span class="alpus-mini-logo"></span><?php esc_html_e( 'New Template', 'alpus-core' ); ?></h2>
				</div>
				<div class="alpus-modal-body">
					<div class="alpus-new-template-form">
						<?php if ( defined( 'ALPUS_VERSION' ) ) { ?>
							<?php if ( defined( 'ELEMENTOR_VERSION' ) && alpus_get_feature( 'fs_pb_elementor' ) ) { ?>
								<label for="alpus-elementor-studio" style="display: none">
									<input type="radio" id="alpus-elementor-studio" name="alpus-studio-type" value="elementor" checked="checked">
								</label>
							<?php } ?>
						<?php } ?>
						<div class="option">
							<label for="template-type"><?php esc_html_e( 'Select Template Type', 'alpus-core' ); ?></label>
							<select class="template-type" id="template-type">
							<?php
                            foreach ( $this->template_types as $slug => $key ) {
                                echo '<option value="' . esc_attr( $slug ) . '" ' . selected( $slug, $curslug ) . '>' . esc_html( $key ) . '</option>';
                            }
        ?>
							</select>
						</div>
						<div class="option">
							<label for="template-name"><?php esc_html_e( 'Name your template', 'alpus-core' ); ?></label>
							<input type="text" id="template-name" name="template-name" class="template-name" placeholder="<?php esc_attr_e( 'Enter your template name (required)', 'alpus-core' ); ?>" />
						</div>
						<button class="button" id="alpus-create-template-type"><?php esc_html_e( 'Create Template', 'alpus-core' ); ?></button>
					</div>
				</div>
			</div>
		</div>

		<?php
        return $views;
    }

    /**
     * The admin column header.
     *
     * @since 1.0
     */
    public function admin_column_header( $defaults ) {
        $date_post = array_search( 'date', $defaults );
        $changed   = array_merge( array_slice( $defaults, 0, $date_post - 1 ), array( 'template_type' => esc_html__( 'Template Type', 'alpus-core' ) ), array_slice( $defaults, $date_post ) );

        return $changed;
    }

    /**
     * The admin column content.
     *
     * @since 1.0
     */
    public function admin_column_content( $column_name, $post_id ) {
        if ( 'template_type' === $column_name ) {
            $type = esc_attr( get_post_meta( $post_id, ALPUS_NAME . '_template_type', true ) );
            echo '<a href="' . esc_url( admin_url( 'edit.php?post_type=' . ALPUS_NAME . '_template&' . ALPUS_NAME . '_template_type=' . $type ) ) . '">' . str_replace( '_', ' ', $type ) . '</a>';
        }
    }

    /**
     * Save template.
     *
     * @since 1.0
     */
    public function save_alpus_template() {
        if ( ! check_ajax_referer( 'alpus-core-nonce', 'nonce', false ) ) {
            wp_send_json_error( 'invalid_nonce' );
        }

        if ( ! isset( $_POST['name'] ) || ! isset( $_POST['type'] ) ) {
            wp_send_json_error( esc_html__( 'no template type or name', 'alpus-core' ) );
        }

        if ( ! empty( $_POST['page_builder'] ) ) {
            $cpts = get_option( 'elementor_cpt_support' );

            if ( ! $cpts || ( is_array( $cpts ) && ! in_array( ALPUS_NAME . '_template', $cpts ) ) ) {
                $cpts[] = ALPUS_NAME . '_template';
                update_option( 'elementor_cpt_support', $cpts );
            }
        }

        $post_id = wp_insert_post(
            array(
                'post_title'  => sanitize_text_field( $_POST['name'] ),
                'post_type'   => ALPUS_NAME . '_template',
                'post_status' => 'publish',
            )
        );

        wp_save_post_revision( $post_id );
        update_post_meta( $post_id, ALPUS_NAME . '_template_type', sanitize_text_field( $_POST['type'] ) );

        if ( isset( $_POST['template_id'] ) && (int) $_POST['template_id'] && isset( $_POST['template_type'] ) && sanitize_text_field( $_POST['template_type'] ) && isset( $_POST['template_category'] ) && sanitize_text_field( $_POST['template_category'] ) ) {
            $template_type     = sanitize_text_field( $_POST['template_type'] );
            $template_category = sanitize_text_field( $_POST['template_category'] );

            update_post_meta(
                $post_id,
                'alpus_start_template',
                array(
                    'id'   => (int) $_POST['template_id'],
                    'type' => $template_type,
                )
            );
        }

        wp_send_json_success( $post_id );
    }

    /**
     * Delete template.
     *
     * @since 1.0
     */
    public function delete_template( $post_id ) {
        if ( ALPUS_NAME . '_template' == get_post_type( $post_id ) ) {
            delete_post_meta( $post_id, ALPUS_NAME . '_template_type' );
        }
    }

    /**
     * Fitler template type.
     *
     * @since 1.0
     */
    public function filter_template_type( $query ) {
        if ( is_admin() ) {
            global $pagenow;

            if ( 'edit.php' == $pagenow && isset( $_GET ) && isset( $_GET['post_type'] ) && ALPUS_NAME . '_template' == sanitize_text_field( $_GET['post_type'] ) ) {
                $template_type = '';

                if ( isset( $_GET[ ALPUS_NAME . '_template_type' ] ) && sanitize_text_field( $_GET[ ALPUS_NAME . '_template_type' ] ) ) {
                    $template_type = sanitize_text_field( $_GET[ ALPUS_NAME . '_template_type' ] );
                }

                $query->query_vars['meta_key']   = ALPUS_NAME . '_template_type';
                $query->query_vars['meta_value'] = $template_type;
            }
        }
    }
}

Alpus_Builders::get_instance();
