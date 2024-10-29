<?php
/**
 * Header mobile menu toggle template
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

// disable if mobile menu has no any items
if ( ! function_exists( 'alpus_get_option' ) || ! alpus_get_option( 'mobile_menu_items' ) ) {
    return;
}

extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'icon_class' => '',
        ),
        $atts
    )
);
?>
<a href="#" class="mobile-menu-toggle d-lg-none" aria-label="<?php esc_attr_e( 'Mobile Menu', 'alpus-core' ); ?>">
	<i class="<?php echo esc_attr( $icon_class ? $icon_class : ALPUS_ICON_PREFIX . '-icon-hamburger', 'alpus-core' ); ?>"></i>
</a>
<?php
