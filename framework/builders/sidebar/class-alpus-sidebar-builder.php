<?php
/**
 * Alpus_Sidebar_Builder class
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;
define( 'ALPUS_SIDEBAR_BUILDER', ALPUS_BUILDERS . '/sidebar' );

class Alpus_Sidebar_Builder extends Alpus_Base {

    /**
         * Constructor
         *
         * @since 1.0
         */
    public function __construct() {
        if ( isset( $_GET['page'] ) && 'alpus-sidebar' == sanitize_text_field( $_GET['page'] ) ) {
            add_filter( 'alpus_core_admin_localize_vars', array( $this, 'add_localize_vars' ) );
        }
        $this->_init_sidebars();

        add_action( 'widgets_init', array( $this, 'add_widgets' ) );

        // Compatabilities
        add_filter( 'widget_nav_menu_args', array( $this, 'make_collapsible_menus' ), 10, 4 );

        // Ajax
        add_action( 'wp_ajax_alpus_add_widget_area', array( $this, 'add_sidebar' ) );
        add_action( 'wp_ajax_nopriv_alpus_add_widget_area', array( $this, 'add_sidebar' ) );
        add_action( 'wp_ajax_alpus_remove_widget_area', array( $this, 'remove_sidebar' ) );
        add_action( 'wp_ajax_nopriv_alpus_remove_widget_area', array( $this, 'remove_sidebar' ) );
    }

    /**
     * Add widgets
     *
     * @since 1.0
     */
    public function add_widgets() {
        $widgets = array(
            'block',       // @feature: fs_sidebar_block
            'posts_nav',   // @feature: fs_sidebar_posts_nav
        );

        // @start feature: fs_plugin_woocommerce
        if ( class_exists( 'WooCommerce' ) ) {
            $widgets[] = 'price_filter';    // @feature: fs_sidebar_price_filter
            $widgets[] = 'filter_clean';    // @feature: fs_sidebar_filter_clean

            // @start feature: fs_addon_product_brand
            if ( ( function_exists( 'alpus_get_option' ) && alpus_get_option( 'brand' ) ) ) {
                $widgets[] = 'brands_nav';   // @feature: fs_sidebar_brands_nav
            }
            // @end feature: fs_addon_product_brand
        }
        // @end feature: fs_plugin_woocommerce
        /**
         * Filters the widgets adding to sidebar
         *
         * @since 1.0
         */
        $widgets = apply_filters( 'alpus_sidebar_widgets', $widgets );

        foreach ( $widgets as $widget ) {
            include_once alpus_core_framework_path( ALPUS_BUILDERS . '/sidebar/widgets/' . str_replace( '_', '-', $widget ) . '/widget-' . str_replace( '_', '-', $widget ) . '-sidebar.php' );
            register_widget( 'Alpus_' . ucwords( $widget, '_' ) . '_Sidebar_Widget' );
        }
    }

    /**
     * Make collapsible menus.
     *
     * @since 1.0
     */
    public function make_collapsible_menus( $nav_menu_args, $menu, $args, $instance ) {
        $nav_menu_args['items_wrap'] = '<ul id="%1$s" class="menu collapsible-menu">%3$s</ul>';

        return $nav_menu_args;
    }

    /**
     * Init sidebars.
     *
     * @since 1.0
     */
    private function _init_sidebars() {
        $sidebars = get_option( 'alpus_sidebars' );

        if ( $sidebars ) {
            $sidebars       = json_decode( $sidebars, true );
            $this->sidebars = $sidebars;
        } else {
            $this->sidebars = array();
        }
    }

    /**
     * Add localize vars.
     *
     * @since 1.0
     */
    public function add_localize_vars( $vars ) {
        $vars['sidebars']  = $this->sidebars;
        $vars['admin_url'] = esc_url( admin_url() );

        return $vars;
    }

    /**
     * Sidebar View
     *
     * @since 1.0
     */
    public function sidebar_view() {
        if ( class_exists( 'Alpus_Admin_Panel' ) ) {
            $title        = array(
                'title' => esc_html__( 'Sidebars Builder', 'alpus-core' ),
                'desc'  => esc_html__( 'This enables you to add unlimited widget areas for your stunning site and remove unnecessary sidebars.', 'alpus-core' ),
            );
            $admin_config = Alpus_Admin::get_instance()->admin_config;
            Alpus_Admin_Panel::get_instance()->view_header( 'sidebars_builder', $admin_config, $title );
            ?>
			<button id="add_widget_area" class="alpus-sidebar-action button button-primary button-large"><?php esc_html_e( 'Add New Sidebar', 'alpus-core' ); ?></button>

			<div class="alpus-admin-panel-body sidebars-builder">
				<table class="wp-list-table widefat" id="sidebar_table">
					<thead>
						<tr>
							<th scope="col" id="title" class="manage-column column-title column-primary"><?php esc_html_e( 'Title', 'alpus-core' ); ?></th>
							<th scope="col" id="slug" class="manage-column column-slug"><?php esc_html_e( 'Slug', 'alpus-core' ); ?></th>
							<th scope="col" id="remove" class="manage-column column-remove"><?php esc_html_e( 'Action', 'alpus-core' ); ?></th>
						</tr>
					</thead>
					<tbody id="the-list">
					<?php
                    global $wp_registered_sidebars;
            $default_sidebars = array();

            foreach ( $wp_registered_sidebars as $key => $value ) {
                echo '<tr id="' . alpus_escaped( $key ) . '" class="sidebar">';
                echo '<td class="title column-title"><a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">' . esc_html( $value['name'] ) . '</a></td>';
                echo '<td class="slug column-slug">' . alpus_escaped( $key ) . '</td>';
                echo '<td class="remove column-remove">' . ( in_array( $key, array_keys( $this->sidebars ) ) ? '<a href="#">' . esc_html__( 'Remove', 'alpus-core' ) . '</a>' : esc_html__( 'Unremovable', 'alpus-core' ) ) . '</td>';
                echo '</tr>';
            }
            ?>
					</tbody>
				</table>
			</div>
				<?php
                Alpus_Admin_Panel::get_instance()->view_footer( 'sidebars', $admin_config );
        }
    }

    /**
     * Add sidebar
     *
     * @since 1.0
     */
    public function add_sidebar() {
        if ( ! check_ajax_referer( 'alpus-core-nonce', 'nonce', false ) ) {
            wp_send_json_error( 'invalid_nonce' );
        }

        if ( isset( $_POST['slug'] ) && isset( $_POST['name'] ) ) {
            $this->sidebars[ $_POST['slug'] ] = sanitize_text_field( $_POST['name'] );

            update_option( 'alpus_sidebars', json_encode( $this->sidebars ) );

            if ( admin_url( 'widgets.php' ) ) {
                wp_send_json_success(
                    array(
                        'url' => admin_url( 'widgets.php' ),
                    )
                );
            }
            wp_send_json_success( esc_html__( 'succesfully registered', 'alpus-core' ) );
        } else {
            wp_send_json_error( 'no sidebar name or slug' );
        }
    }

    /**
     * Remove sidebar
     *
     * @since 1.0
     */
    public function remove_sidebar() {
        if ( ! check_ajax_referer( 'alpus-core-nonce', 'nonce', false ) ) {
            wp_send_json_error( 'invalid_nonce' );
        }

        if ( isset( $_POST['slug'] ) ) {
            unset( $this->sidebars[ sanitize_text_field( $_POST['slug'] ) ] );

            update_option( 'alpus_sidebars', json_encode( $this->sidebars ) );

            wp_send_json_success( esc_html__( 'succesfully removed', 'alpus-core' ) );
        } else {
            wp_send_json_error( 'no sidebar name or slug' );
        }
    }
}

Alpus_Sidebar_Builder::get_instance();
