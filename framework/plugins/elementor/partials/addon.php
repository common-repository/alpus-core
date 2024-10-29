<?php
/**
 * Addon Partial
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! function_exists( 'alpus_elementor_addon_controls' ) ) {
    /**
     * Register elementor custom addons for elements and widgets.
     *
     * @since 1.0
     */
    function alpus_elementor_addon_controls( $self, $source = '' ) {
        /*
         * Fires after add elementor addon controls.
         *
         * @since 1.0
         */
        do_action( 'alpus_elementor_addon_controls', $self, $source );
    }
}
