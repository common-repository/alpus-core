<?php
/**
 * Core Framework Addons
 *
 * 1. Load addons
 * 2. Addons List
 *
 * @author     D-THEMES
 *
 * @version    1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
add_action(
    'alpus_framework_addons',
    function ( $request ) {
        // @start feature: fs_addon_walker
        if ( alpus_get_feature( 'fs_addon_walker' ) ) {
            if ( 'nav-menus.php' == $GLOBALS['pagenow'] || $request['customize_preview'] || $request['doing_ajax'] ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/walker/class-alpus-walker.php' );
            }
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/walker/class-alpus-walker-nav-menu.php' );
        }
        // @end feature: fs_addon_walker

        // @start feature: fs_addon_skeleton
        if ( alpus_get_feature( 'fs_addon_skeleton' ) && ( ! $request['doing_ajax'] && ! $request['customize_preview'] && ! $request['is_preview'] && function_exists( 'alpus_get_option' ) && alpus_get_option( 'skeleton_screen' ) && ! isset( $_REQUEST['only_posts'] ) ) ) {
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/skeleton/class-alpus-skeleton.php' );
        }
        // @end feature: fs_addon_skeleton

        // @start feature: fs_addon_lazyload_image
        if ( alpus_get_feature( 'fs_addon_lazyload_image' ) ) {
            add_filter( 'wp_lazy_loading_enabled', 'alpus_disable_wp_lazyload_img', 10, 2 );
            function alpus_disable_wp_lazyload_img( $default, $tag_name ) {
                return 'img' == $tag_name ? false : $default;
            }

            if ( ! $request['is_admin'] && ! $request['customize_preview'] && ! $request['doing_ajax'] && function_exists( 'alpus_get_option' ) && alpus_get_option( 'lazyload' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/lazyload-images/class-alpus-lazyload-images.php' );
            }
        }
        // @end feature: fs_addon_lazyload_image

        // @start feature: fs_addon_lazyload_menu
        if ( alpus_get_feature( 'fs_addon_lazyload_menu' ) && $request['is_admin'] ) {
            if ( $request['customize_preview'] ) {
                add_action( 'customize_save_after', 'alpus_lazyload_menu_update' );
            }

            if ( 'post.php' == $GLOBALS['pagenow'] ) {
                add_action( 'save_post', 'alpus_lazyload_menu_update' );
            }
            add_action( 'wp_update_nav_menu_item', 'alpus_lazyload_menu_update', 10, 3 );

            if ( ! function_exists( 'alpus_lazyload_menu_update' ) ) {
                function alpus_lazyload_menu_update() {
                    set_theme_mod( 'menu_last_time', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
                }
            }
        }
        // @end feature: fs_addon_lazyload_menu

        // @start feature: fs_addon_live_search
        if ( alpus_get_feature( 'fs_addon_live_search' ) && function_exists( 'alpus_get_option' ) && alpus_get_option( 'live_search' ) ) {
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/live-search/class-alpus-live-search.php' );
        }
        // @end feature: fs_addon_live_search

        // @start feature: fs_addon_share
        if ( alpus_get_feature( 'fs_addon_share' ) ) {
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/share/class-alpus-share.php' );
        }

        // @end feature: fs_addon_share
        // @start feature: fs_plugin_woocommerce
        if ( class_exists( 'WooCommerce' ) && alpus_get_feature( 'fs_plugin_woocommerce' ) ) {
            // @start feature: fs_addon_product_helpful_comments
            if ( alpus_get_feature( 'fs_addon_product_helpful_comments' ) && 'yes' == get_option( 'woocommerce_enable_reviews' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-helpful-comments/class-alpus-helpful-comments.php' );
            }
            // @end feature: fs_addon_product_helpful_comments

            // @start feature: fs_addon_product_ordering
            if ( alpus_get_feature( 'fs_addon_product_ordering' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-ordering/class-alpus-product-ordering.php' );
            }
            // @end feature: fs_addon_product_ordering

            // @start feature: fs_addon_product_brand
            if ( alpus_get_feature( 'fs_addon_product_brand' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-brand/class-alpus-product-brand.php' );
            }
            // @end feature: fs_addon_product_brand

            // @start feature: fs_addon_product_360_gallery
            if ( alpus_get_feature( 'fs_addon_product_360_gallery' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-360-gallery/class-alpus-product-360-gallery.php' );
            }
            // @end feature: fs_addon_product_360_gallery

            // @start feature: fs_addon_product_video_popup
            if ( alpus_get_feature( 'fs_addon_product_video_popup' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-video-popup/class-alpus-product-video-popup.php' );
            }
            // @end feature: fs_addon_product_video_popup

            // @start feature: fs_addon_product_image_comments
            if ( alpus_get_feature( 'fs_addon_product_image_comments' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-image-comments/class-alpus-product-image-comment.php' );

                if ( $request['can_manage'] ) {
                    require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-image-comments/class-alpus-product-image-comment-admin.php' );
                }
            }
            // @end feature: fs_addon_product_image_comments

            // @start feature: fs_addon_product_compare
            if ( alpus_get_feature( 'fs_addon_product_compare' ) && ! defined( 'YITH_WOOCOMPARE_VERSION' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-compare/class-alpus-product-compare.php' );
            }
            // @end feature: fs_addon_product_compare

            // @start feature: fs_addon_product_attribute_guide
            if ( alpus_get_feature( 'fs_addon_product_attribute_guide' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-attribute-guide/class-product-attribute-guide.php' );
            }
            // @end feature: fs_addon_product_attribute_guide

            // @start feature: fs_addon_product_advanced_swatch
            if ( alpus_get_feature( 'fs_addon_product_advanced_swatch' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-advanced-swatch/class-alpus-advanced-swatch.php' );

                if ( function_exists( 'alpus_get_option' ) && alpus_get_option( 'advanced_swatch' ) && $request['is_admin'] && ( 'edit-tags.php' == $GLOBALS['pagenow'] || $request['doing_ajax'] || $request['product_edit_page'] ||
                    ! empty( $_POST['action'] ) && 'editpost' == sanitize_text_field( $_POST['action'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-advanced-swatch/class-alpus-advanced-swatch-tab.php' );
                }
            }
            // @end feature: fs_addon_product_advanced_swatch

            // @start feature: fs_addon_product_custom_tabs
            if ( alpus_get_feature( 'fs_addon_product_custom_tabs' ) ) {
                if ( $request['is_admin'] && ( $request['doing_ajax'] || $request['product_edit_page'] ) ) {
                    require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-custom-tab/class-alpus-product-custom-tab-admin.php' );
                }
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-data-addons/class-alpus-product-data-addons.php' );
            }
            // @end feature: fs_addon_product_custom_tabs

            // @start feature: fs_addon_product_frequently_bought_together
            if ( alpus_get_feature( 'fs_addon_product_frequently_bought_together' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-frequently-bought-together/class-alpus-pfbt.php' );

                if ( function_exists( 'alpus_get_option' ) && alpus_get_option( 'product_fbt' ) && $request['is_admin'] && ( $request['doing_ajax'] || $request['product_edit_page'] ||
                    ! empty( $_POST['action'] ) && 'editpost' == sanitize_text_field( $_POST['action'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-frequently-bought-together/class-alpus-pfbt-admin.php' );
                }
            }
            // @end feature: fs_addon_product_frequently_bought_together

            // @start feature: fs_addon_product_buy_now
            if ( alpus_get_feature( 'fs_addon_product_buy_now' ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/product-buy-now/class-alpus-product-buy-now.php' );
            }
            // @end feature: fs_addon_product_buy_now
        }
        // @end feature: fs_plugin_woocommerce

        // @start feature: fs_addon_studio
        if ( alpus_get_feature( 'fs_addon_studio' ) ) {
            if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) &&
            ( $request['doing_ajax'] || $request['is_preview'] || 'edit.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ALPUS_NAME . '_template' == sanitize_text_field( $_REQUEST['post_type'] ) || 'post.php' == $GLOBALS['pagenow'] && 'edit' == sanitize_text_field( $_REQUEST['action'] ) || 'post-new.php' == $GLOBALS['pagenow'] ) ) {
                require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/studio/class-alpus-studio.php' );
            }
        }
        // @end feature: fs_addon_studio

        // @start feature: fs_addon_comments_pagination
        if ( alpus_get_feature( 'fs_addon_comments_pagination' ) ) {
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/comments-pagination/class-alpus-comments-pagination.php' );
        }
        // @end feature: fs_addon_comments_pagination

        // @start feature: fs_addon_minicart_quantity_input
        if ( alpus_get_feature( 'fs_addon_minicart_quantity_input' ) ) {
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/minicart-quantity-input/class-alpus-minicart-quantity-input.php' );
        }
        // @end feature: fs_addon_minicart_quantity_input

        // @start feature: fs_addon_gdpr
        if ( alpus_get_feature( 'fs_addon_gdpr' ) ) {
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/gdpr/class-alpus-gdpr.php' );
        }
        // @end feature: fs_addon_gdpr

        // @start feature: fs_addon_custom_fonts
        if ( alpus_get_feature( 'fs_addon_custom_fonts' ) ) {
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/custom-fonts/class-alpus-custom-fonts.php' );
        }
        // @end feature: fs_addon_custom_fonts

        // @start feature: fs_addon_ai_generator
        if ( alpus_get_feature( 'fs_addon_ai_generator' ) ) {
            require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/ai-generator/class-alpus-content-generator.php' );
        }
        // @end feature: fs_addon_ai_generator

        require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/breadcrumb/class-alpus-breadcrumb.php' );
    }
);
