<?php
/**
 * Render template for block widget.
 *
 * @author     D-THEMES
 *
 * @since      1.2.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'img_source'            => '',
            'img_size'              => '',
            'show_caption_selector' => '',
            'custom_caption'        => '',
            'link'                  => '',
            'lightbox'              => '',
            'link_url'              => '',
            'wrap_class'            => '',
        ),
        $atts
    )
);

if ( 'yes' == $lightbox && 'media' == $link ) {
    wp_enqueue_style( 'alpus-lightbox' );
    wp_enqueue_script( 'alpus-lightbox' );
    wp_enqueue_script( 'alpus-image-popup', alpus_core_framework_uri( '/widgets/image/image' . ALPUS_JS_SUFFIX ), array(), ALPUS_CORE_VERSION, true );
    $wrap_class .= ' alpus_img_popup ';
}

// dynamic content
if ( ! empty( $atts['image_source'] ) && isset( $atts['dynamic_content'] ) && ! empty( $atts['dynamic_content']['source'] ) ) {
    $img_source = apply_filters( 'alpus_dynamic_tags_content', '', null, $atts['dynamic_content'], 'image', $img_size );
}

if ( empty( $img_source ) ) {
    return;
}
$html = '<div class="' . esc_attr( $wrap_class ) . '">';

if ( $show_caption_selector ) {
    // Begin figure for lightbox
    $html .= '<figure>';
}

if ( 'custom' == $link && ! empty( $link_url ) ) {
    $html .= '<a href="' . ( esc_url( $link_url ) ) . '">';
    $html .= '<img' .
    ' src="' . ( $img_source['sizes'] ? esc_url( $img_source['sizes'][ $img_size ]['url'] ) : esc_url( $img_source['url'] ) ) . '"' .
    ' alt="' . ( $img_source['alt_text'] ? esc_attr( $img_source['alt_text'] ) : '' ) . '"' .
    ' width="' . ( $img_source['sizes'] ? intval( $img_source['sizes'][ $img_size ]['width'] ) : '' ) . '"' .
    ' height="' . ( $img_source['sizes'] ? intval( $img_source['sizes'][ $img_size ]['height'] ) : '' ) . '"' .
    '/>';
    $html .= '</a>';
} else {
    $html .= '<img' .
        ' src="' . ( $img_source['sizes'] ? esc_url( $img_source['sizes'][ $img_size ]['url'] ) : esc_url( $img_source['url'] ) ) . '" ' .
        ' alt="' . ( ! empty( $img_source['alt_text'] ) ? esc_attr( $img_source['alt_text'] ) : '' ) . '" ' .
        ' width="' . ( $img_source['sizes'] ? intval( $img_source['sizes'][ $img_size ]['width'] ) : '' ) . '" ' .
        ' height="' . ( $img_source['sizes'] ? intval( $img_source['sizes'][ $img_size ]['height'] ) : '' ) . '"' .
    '/>';
}

if ( $show_caption_selector ) {
    // End figure for lightbox
    $html .= '<figcaption class="alpus-gb-caption-text">' . ( 'attachment' == $show_caption_selector ? esc_html( $img_source['caption'] ) : esc_html( $custom_caption ) ) . '</figcaption>' .
    '</figure>';
}
$html .= '</div>';
echo alpus_escaped( $html );
