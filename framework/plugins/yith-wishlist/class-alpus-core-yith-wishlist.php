<?php
/**
 * Alpus Yith Wishlist
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! class_exists( 'Alpus_Core_Wishlist' ) ) {
    class Alpus_Core_Wishlist extends Alpus_Base {

        /**
                 * Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            // YITH Wishlist Admin Page
            add_filter( 'yith_wcwl_add_to_wishlist_options', array( $this, 'yith_wcwl_wishlist_options' ) );
        }

        public function yith_wcwl_wishlist_options( $args ) {
            $remove_options = array(
                'shop_page_section_start',
                'show_on_loop',
                'loop_position',
                'shop_page_section_end',
            );

            foreach ( $remove_options as $option ) {
                unset( $args['add_to_wishlist'][ $option ] );
            }

            return $args;
        }
    }
}

Alpus_Core_Wishlist::get_instance();
