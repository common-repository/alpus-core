<?php
/**
 * Core Framework
 *
 * 1. Load the plugin base
 * 2. Load the other plugin functions
 * 3. Load builders
 * 4. Load addons and shortcodes
 * 5. Critical CSS
 *
 * @author     D-THEMES
 *
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

define( 'ALPUS_CORE_PLUGINS', ALPUS_CORE_FRAMEWORK_PATH . '/plugins' );
define( 'ALPUS_CORE_PLUGINS_URI', ALPUS_CORE_FRAMEWORK_URI . '/plugins' );
define( 'ALPUS_BUILDERS', ALPUS_CORE_FRAMEWORK_PATH . '/builders' );
define( 'ALPUS_BUILDERS_URI', ALPUS_CORE_FRAMEWORK_URI . '/builders' );
define( 'ALPUS_CORE_ADDONS', ALPUS_CORE_FRAMEWORK_PATH . '/addons' );
define( 'ALPUS_CORE_ADDONS_URI', ALPUS_CORE_FRAMEWORK_URI . '/addons' );

global $pagenow;
$alpus_pages = array( 'post-new.php', 'post.php', 'index.php', 'admin-ajax.php', 'edit.php', 'admin.php', 'widgets.php' );

/* 1. Load the plugin base            */

require_once alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/common-functions.php' );
require_once alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/plugin-functions.php' );
require_once alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/plugin-actions.php' );

/*
 * Fires after framework init
 *
 * @since 1.0
 */
do_action( 'alpus_after_core_framework_init' );

/* 2. Load the other plugin functions */

if ( in_array( $pagenow, $alpus_pages ) ) {
    // @start feature: fs_pb_elementor
    if ( alpus_get_feature( 'fs_pb_elementor' ) ) {
        require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/elementor/class-alpus-core-elementor.php' );   // Elementor
    }
    // @end feature: fs_pb_elementor
}

require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/gutenberg/class-alpus-gutenberg.php' );   //Gutenberg Blocks

// @start feature: fs_plugin_acf
if ( alpus_get_feature( 'fs_plugin_acf' ) && class_exists( 'ACF' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/acf/class-alpus-core-acf.php' );                     // ACF
}
// @end feature: fs_plugin_acf

// @start feature: fs_plugin_woof
if ( alpus_get_feature( 'fs_plugin_woof' ) && class_exists( 'WOOF' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/woof/class-alpus-core-woof.php' );
}
// @end feature: fs_plugin_woof

// @start feature: fs_plugin_uni_cpo
if ( alpus_get_feature( 'fs_plugin_uni_cpo' ) && class_exists( 'Uni_Cpo' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/unicpo/class-alpus-core-unicpo.php' );
}
// @end feature: fs_plugin_uni_cpo

// @start feature: fs_plugin_yith_featured_video
if ( alpus_get_feature( 'fs_plugin_yith_featured_video' ) && class_exists( 'YITH_WC_Audio_Video' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/yith-featured-video/class-alpus-core-yith-featured-video.php' );
}
// @end feature: fs_plugin_yith_featured_video

// @start feature: fs_plugin_yith_gift_card
if ( alpus_get_feature( 'fs_plugin_yith_gift_card' ) && class_exists( 'YITH_YWGC_Gift_Card' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/yith-gift-card/class-alpus-core-yith-gift-card.php' );
}
// @end feature: fs_plugin_yith_gift_card

// @start feature: fs_plugin_yith_wishlist
if ( alpus_get_feature( 'fs_plugin_yith_wishlist' ) && defined( 'YITH_WCWL' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/yith-wishlist/class-alpus-core-yith-wishlist.php' );
}
// @end feature: fs_plugin_yith_wishlist

// @start feature: fs_plugin_yith_compare
if ( alpus_get_feature( 'fs_plugin_yith_compare' ) && defined( 'YITH_WOOCOMPARE_VERSION' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/yith-compare/class-alpus-core-yith-compare.php' );
}
// @end feature: fs_plugin_yith_compare

// @start feature: fs_plugin_wpforms
if ( alpus_get_feature( 'fs_plugin_wpforms' ) && class_exists( 'WPForms' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/wpforms/class-alpus-core-wpforms.php' );
}
// @end feature: fs_plugin_wpforms

require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/meta-box/class-alpus-admin-meta-boxes.php' );             // Meta Box

if ( class_exists( 'WooCommerce' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_PLUGINS . '/woocommerce/class-alpus-core-woocommerce.php' );             // WooCommerce
}

/*
 * Fires after loading framework plugin compatibility.
 *
 * @since 1.0
 */
do_action( 'alpus_after_core_framework_plugins' );

/* 3. Load builders                   */

if ( ! isset( $_POST['action'] ) || 'alpus_quickview' != sanitize_text_field( $_POST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    require_once alpus_core_framework_path( ALPUS_BUILDERS . '/class-alpus-builders.php' );

    // @start feature: fs_builder_sidebar
    if ( alpus_get_feature( 'fs_builder_sidebar' ) ) {
        require_once alpus_core_framework_path( ALPUS_BUILDERS . '/sidebar/class-alpus-sidebar-builder.php' );
    }
    // @end feature: fs_builder_sidebar
}

/*
 * Fires after loading framework template builder.
 *
 * @since 1.0
 */
do_action( 'alpus_after_core_framework_builders' );

/* 4. Load addons and shortcodes      */

require_once alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/addons/init.php' );
require_once alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/shortcode.php' );

/* 5. Critical CSS                    */

if ( alpus_get_feature( 'fs_critical_css_js' ) ) {
    require_once alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/critical/class-alpus-critical.php' );
}

/*
 * Fires after loading framework init.
 *
 * @since 1.0
 */
do_action( 'alpus_after_core_framework_shortcodes' );
