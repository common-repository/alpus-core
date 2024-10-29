<?php

/**
 * alpus_remove_gutenberg_iconbox
 *
 * Remove icon box from gutenberg blocks
 *
 * @param array $vars
 *
 * @return array
 *
 * @since 1.0
 */

/*
 * alpus_wc_dropdown_variation_attribute_options_html
 *
 * Return variation attribute option HTML.
 *
 * @param string $html
 * @param array $args
 * @return string
 *
 * @since 1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
/*
 * Filters the default column spacing.
 *
 * @since 1.0
 */
add_filter( 'alpus_col_default', 'alpus_col_default' );
// Favicon
add_action( 'admin_head', 'alpus_print_favicon' );

function alpus_col_default( $default ) {
    return 'lg';
}

if ( ! function_exists( 'alpus_print_favicon' ) ) {
    function alpus_print_favicon() {
        $favicon = function_exists( 'alpus_get_option' ) ? alpus_get_option( 'site_icon' ) : '';

        if ( ! empty( $favicon['url'] ) ) {
            echo '<link rel="shortcut icon" href="' . esc_url( $favicon['url'] ) . '" type="image/x-icon" />';
        }
    }
}
