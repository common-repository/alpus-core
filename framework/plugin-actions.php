<?php
/**
 * Plugin Actions, Filters
 *
 * @author     D-THEMES
 *
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

add_filter( 'template_include', 'alpus_control_maintenance', 9999 );
add_action( 'admin_bar_menu', 'alpus_add_maintenance_notice', 999 );
add_action( 'after_setup_theme', 'alpus_setup_make_script_async' );
add_action( 'wp_enqueue_scripts', 'alpus_enqueue_core_framework_scripts', 2 );
add_action( 'admin_print_footer_scripts', 'alpus_print_footer_scripts', 30 );
add_action( 'wp_ajax_alpus_load_creative_layout', 'alpus_load_creative_layout' );
add_action( 'wp_ajax_nopriv_alpus_load_creative_layout', 'alpus_load_creative_layout' );
add_action( 'alpus_importer_before_import_dummy', 'alpus_before_import_dummy' );

// update image srcset meta
add_filter( 'wp_calculate_image_srcset', 'alpus_image_srcset_filter_sizes', 10, 2 );


// search custom post types
add_filter( 'alpus_search_content_types', 'alpus_search_content_types' );

if ( ! function_exists( 'alpus_control_maintenance' ) ) {
    /**
     * Control the maintenance mode
     *
     * @since 1.3.0
     */
    function alpus_control_maintenance( $template ) {
        if ( is_user_logged_in() && current_user_can( 'edit_published_posts' ) ) {
            return $template;
        }

        if ( empty( alpus_get_option( 'is_maintenance' ) ) ) {
            return $template;
        }

        $abspath        = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, ABSPATH );
        $included_files = get_included_files();

        if ( in_array( $abspath . 'wp-login.php', $included_files ) ||
            in_array( $abspath . 'wp-register.php', $included_files ) ||
            ( isset( $_GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'wp-login.php' ) ||
            $_SERVER['PHP_SELF'] == '/wp-login.php' ) {
            return $template;
        }
        $fallback  = '<h2>';
        $fallback .= esc_html__( 'This is default maintenance page. Please create a new page and set it as \'Maintenance Page\' in Theme Option.', 'alpus-core' );
        $fallback .= '</h2>';

        $maintenance_page = alpus_get_option( 'maintenance_page' );

        if ( $maintenance_page ) {
            if ( is_page( $maintenance_page ) ) {
                status_header( 503 ); // Common causes are a server that is down for maintenance or that is overloaded.
                nocache_headers();

                return $template;
            }

            if ( wp_redirect( get_permalink( $maintenance_page ) ) ) {
                exit;
            }
        }

        /*
         * Output a fallback message if redirect failed or no page selected
         */
        status_header( 503 );
        nocache_headers();
        exit( $fallback );
    }
}

if ( ! function_exists( 'alpus_add_maintenance_notice' ) ) {
    /**
     * Add info to admin bar about maintenance notice
     *
     * @since 1.3.0
     */
    function alpus_add_maintenance_notice( $admin_bar ) {
        if ( function_exists( 'alpus_get_option' ) && ! empty( alpus_get_option( 'is_maintenance' ) ) ) {
            $admin_bar->add_menu(
                array(
                    'id'     => 'alpus-maintenance',
                    'parent' => 'top-secondary',
                    'title'  => sprintf( __( '%1$sMaintenance Mode Enabled%2$s', 'alpus-core' ), '<span style="color: red">', '</span>' ),
                    'href'   => '#',
                )
            );
        }

        return $admin_bar;
    }
}

if ( ! function_exists( 'alpus_print_footer_scripts' ) ) {
    /**
     * Print footer scripts
     *
     * @since 1.0
     */
    function alpus_print_footer_scripts() {
        echo '<script id="alpus-core-admin-js-extra">';
        echo 'var alpus_core_vars = ' . wp_json_encode(
            apply_filters(
                'alpus_core_admin_localize_vars',
                array(
                    'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
                    'nonce'      => wp_create_nonce( 'alpus-core-nonce' ),
                    'assets_url' => ALPUS_CORE_URI,
                    'theme'      => ALPUS_NAME,
                )
            )
        ) . ';';
        echo '</script>';
    }
}

if ( ! function_exists( 'alpus_enqueue_core_framework_scripts' ) ) {
    /**
     * Enqueue framework required scripts
     *
     * @since 1.2.0
     */
    function alpus_enqueue_core_framework_scripts() {
        wp_register_script( 'jquery-floating', alpus_core_framework_uri( '/assets/js/jquery.floating.min.js' ), array( 'jquery-core' ), false, true );
        wp_register_script( 'jquery-skrollr', alpus_core_framework_uri( '/assets/js/skrollr.min.js' ), array(), '0.6.30', true );
        wp_register_script( 'alpus-chart-lib', alpus_core_framework_uri( '/assets/js/chart.min.js' ), array(), false, true );
        wp_register_script( 'three-sixty', alpus_core_framework_uri( '/assets/js/threesixty.min.js' ), array(), false, true );
        wp_register_script( 'jquery-countdown', alpus_core_framework_uri( '/assets/js/jquery.countdown.min.js' ), array(), false, true );
    }
}

if ( ! function_exists( 'alpus_setup_make_script_async' ) ) {
    /**
     * Add a filter to make scripts async.
     *
     * @since 1.0
     */
    function alpus_setup_make_script_async() {
        // Set scripts as async
        if ( ! alpus_is_wpb_preview() && function_exists( 'alpus_get_option' ) && alpus_get_option( 'resource_async_js' ) ) {
            add_filter( 'script_loader_tag', 'alpus_make_script_async', 10, 2 );
        }
    }
}

if ( ! function_exists( 'alpus_make_script_async' ) ) {
    /**
     * Set scripts as async
     *
     * @since 1.0
     *
     * @param string $tag
     * @param string $handle
     *
     * @return string Async script tag
     */
    function alpus_make_script_async( $tag, $handle ) {
        $async_scripts = apply_filters(
            'alpus_async_scripts',
            array(
                'jquery-autocomplete',
                'jquery-countdown',
                'alpus-lightbox',
                'jquery-cookie',
                'alpus-framework-async',
                'alpus-theme',
                'alpus-shop',
                'alpus-woocommerce',
                'alpus-single-product',
                'alpus-ajax',
                'alpus-countdown',
                'alpus-shop-show-type',
            )
        );

        if ( in_array( $handle, $async_scripts ) ) {
            return str_replace( ' src', ' async="async" src', $tag );
        }

        return $tag;
    }
}

add_filter(
    'alpus_core_filter_doing_ajax',
    function () {
        // check ajax doing on others
        return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && mb_strtolower( sanitize_text_field( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) == 'xmlhttprequest' ) ? true : false;
    }
);

if ( ! function_exists( 'alpus_load_creative_layout' ) ) {
    function alpus_load_creative_layout() {
        

        $mode = isset( $_POST['mode'] ) ? (int) $_POST['mode'] : 0; // phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

        if ( $mode ) {
            echo wp_json_encode( alpus_creative_layout( $mode ) );
        } else {
            echo wp_json_encode( array() );
        }

        exit();

        // phpcs:enable
    }
}

if ( ! function_exists( 'alpus_image_srcset_filter_sizes' ) ) {
    /**
     * Remove srcset in img tag.
     *
     * @since 1.2.0
     */
    function alpus_image_srcset_filter_sizes( $sources, $size_array ) {
        foreach ( $sources as $width => $source ) {
            if ( isset( $source['descriptor'] ) && 'w' == $source['descriptor'] && ( $width < apply_filters( 'alpus_mini_screen_size', 320 ) || (int) $width > (int) $size_array[0] ) ) {
                unset( $sources[ $width ] );
            }
        }

        return $sources;
    }
}


if ( ! function_exists( 'alpus_before_import_dummy' ) ) {
    /**
     * Before import demo dummy content
     *
     * @since 1.2.0
     */
    function alpus_before_import_dummy() {
        add_filter( 'upload_mimes', function ( $mimes ) {
            $mimes['svg'] = 'image/svg+xml';

            return $mimes;
        }, 99 );
    }
}

if ( ! function_exists( 'alpus_search_content_types' ) ) {
    /**
     * Search content types
     *
     * @since 1.3.0
     */
    function alpus_search_content_types( $post_types ) {
        // Get post types to search.
        $post_types = array(
            '' => esc_html__( 'All', 'alpus-core' ),
        );
        /**
         * Filters the exclude post type.
         *
         * @param array the post type array
         *
         * @since 1.0
         */
        $post_types_exclude   = apply_filters( 'alpus_condition_exclude_post_types', array( 'page', 'e-landing-page', ALPUS_NAME . '_template', 'attachment', 'elementor_library' ) );
        $available_post_types = get_post_types( array( 'public' => true ), 'objects' );

        foreach ( $available_post_types as $post_type_slug => $post_type ) {
            if ( ! in_array( $post_type_slug, $post_types_exclude ) ) {
                $post_types[ $post_type_slug ] = $post_type->labels->name;
            }
        }

        return $post_types;
    }
}
