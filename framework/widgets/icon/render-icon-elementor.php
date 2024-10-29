<?php
/**
 * Render template for icon widget.
 *
 * @author     D-THEMES
 *
 * @since      1.2.1
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'icon'       => 'fas fa-star',
            'link'       => '',
            'wrap_class' => '',
        ),
        $atts
    )
);

// dynamic content
if ( ! empty( $atts['source'] ) && ! empty( $atts['dynamic_content'] ) && ! empty( $atts['dynamic_content']['source'] ) ) {
    $icon = apply_filters( 'alpus_dynamic_tags_content', '', null, $atts['dynamic_content'] );
}

if ( ! empty( $atts['link_source'] ) && ! empty( $atts['link_dynamic_content'] ) && ! empty( $atts['link_dynamic_content']['source'] ) ) {
    $link = apply_filters( 'alpus_dynamic_tags_content', '', null, $atts['link_dynamic_content'] );
}

$tag = 'div';

if ( $link ) {
    $tag = 'a';
}
$attrs = ' class="alpus-icon' . ( $wrap_class ? ' ' . esc_attr( trim( $wrap_class ) ) : '' ) . '"';

if ( $link ) {
    $attrs .= ' href="' . esc_url( $link ) . '"';
}
?>

<<?php echo esc_html( $tag ), alpus_escaped( $attrs ); ?>>
	<i class="<?php echo esc_attr( $icon ); ?>"></i>
</<?php echo esc_html( $tag ); ?>>
