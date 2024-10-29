<?php
/**
 * Core Framework Shortcodes
 *
 * @author     D-THEMES
 *
 * @version    1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
add_shortcode( ALPUS_NAME . '_year', 'alpus_shortcode_year' );
add_shortcode( ALPUS_NAME . '_products', 'alpus_shortcode_product' );
add_shortcode( ALPUS_NAME . '_product_category', 'alpus_shortcode_product_category' );
add_shortcode( ALPUS_NAME . '_posts', 'alpus_shortcode_posts' );
add_shortcode( ALPUS_NAME . '_block', 'alpus_shortcode_block' );
add_shortcode( ALPUS_NAME . '_menu', 'alpus_shortcode_menu' );
add_shortcode( ALPUS_NAME . '_linked_products', 'alpus_shortcode_linked_product' );
add_shortcode( ALPUS_NAME . '_breadcrumb', 'alpus_shortcode_breadcrumb' );
add_shortcode( ALPUS_NAME . '_filter', 'alpus_shortcode_filter' );
add_shortcode( ALPUS_NAME . '_vendors', 'alpus_shorcode_vendors' );

function alpus_shortcode_year() {
    return gmdate( 'Y' );
}

function alpus_shortcode_product( $atts, $content = null ) {
    ob_start();
    require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/products/render-products.php', $atts );

    return ob_get_clean();
}

function alpus_shortcode_product_category( $atts, $content = null ) {
    ob_start();
    require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/categories/render-categories.php', $atts );

    return ob_get_clean();
}

function alpus_shortcode_posts( $atts, $content = null ) {
    ob_start();
    require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/posts/render-posts.php', $atts );

    return ob_get_clean();
}

function alpus_shortcode_block( $atts, $content = null ) {
    ob_start();
    require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/block/render-block.php', $atts );

    return ob_get_clean();
}

function alpus_shortcode_menu( $atts, $content = null ) {
    ob_start();
    require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/menu/render-menu.php', $atts );

    return ob_get_clean();
}

function alpus_shortcode_linked_product( $atts, $content = null ) {
    ob_start();

    /*
     * Filters post products in single product builder.
     *
     * @since 1.0
     */
    if ( apply_filters( 'alpus_single_product_builder_set_preview', false ) ) {
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/products/render-products.php', $atts );
        do_action( 'alpus_single_product_builder_unset_preview' );
    }

    return ob_get_clean();
}

function alpus_shortcode_breadcrumb( $atts, $content = null ) {
    ob_start();
    require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/breadcrumb/render-breadcrumb.php', $atts );

    return ob_get_clean();
}

function alpus_shortcode_filter( $settings, $content = null ) {
    ob_start();
    require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/filter/render-filter.php', $atts );

    return ob_get_clean();
}

function alpus_shortcode_vendors( $atts, $content = null ) {
    ob_start();
    require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/vendor/render-vendor.php', $atts );

    return ob_get_clean();
}
