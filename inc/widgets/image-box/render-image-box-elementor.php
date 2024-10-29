<?php
/**
 * The image box elementor render.
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'type'                   => '',
            'title'                  => esc_html__( 'Input Title Here', 'alpus-core' ),
            'content'                => '',
            'image'                  => array( 'url' => '' ),
            'thumbnail'              => 'full',
            'page_builder'           => '',
            'link'                   => '',
            'btn_links'              => 'external_link',
            'gallery_popup_btn_icon' => array(),
            'gallery_btn_icon'       => array(),
            'show_button'            => '',
            'button_label'           => '',
            'show_shadow'            => '',
            'image_box_img_shape'    => '',
            'image_position'         => 'top',
            'img_style'              => '',
            'overlay'                => '',
            'title_html_tag'         => 'h3',
        ),
        $atts
    )
);

$html = '';

$wrapper_cls = 'image-box';

if ( $overlay ) {
    $overlay_cls = alpus_get_overlay_class( $overlay );
}

$image_html = Elementor\Group_Control_Image_Size::get_attachment_image_html( $atts, 'image' );

$link_attr = 'href="' . esc_url( $link['url'] ? $link['url'] : '#' ) . '"' . ( empty( $link['is_external'] ) ? '' : ' target="nofollow"' ) . ( empty( $link['nofollow'] ) ? '' : ' rel="_blank"' ) . '';

$link_open  = empty( $link['url'] ) ? '' : '<a ' . $link_attr . ( $overlay ? ' class="' . esc_attr( $overlay_cls ) . '"' : '' ) . '>';
$link_close = empty( $link['url'] ) ? '' : '</a>';

if ( $link && $title && 'gallery' != $type ) {
    $title = $link_open . $title . $link_close;
}

$title_html   = '';
$content_html = '';

if ( $title ) {
    $this->add_render_attribute( 'title', 'class', 'title' );
    $title_html = '<' . $title_html_tag . ' ' . $this->get_render_attribute_string( 'title' ) . '>' . alpus_strip_script_tags( $title ) . '</' . $title_html_tag . '>';
}

if ( $content ) {
    $this->add_render_attribute( 'content', 'class', 'content' );
    $content_html = '<p ' . $this->get_render_attribute_string( 'content' ) . '>' . alpus_strip_script_tags( $content ) . '</p>';
}
$button_html = '';

if ( 'yes' == $show_button && $button_label ) {
    $button_label = alpus_widget_button_get_label( $atts, $this, $button_label, 'button_label' );
    $class[]      = 'btn';
    $class[]      = implode( ' ', alpus_widget_button_get_class( $atts ) );

    $this->add_inline_editing_attributes( 'button_label' );

    $button_html = sprintf( '<a class="' . esc_attr( implode( ' ', $class ) ) . '" href="' . ( empty( $link['url'] ) ? '#' : esc_url( $link['url'] ) ) . '" ' . ( ! empty( $link['is_external'] ) ? ' target="nofollow"' : '' ) . ( ! empty( $link['nofollow'] ) ? ' rel="_blank"' : '' ) . '>%1$s</a>', alpus_strip_script_tags( $button_label ) );
}

if ( $type ) {
    $wrapper_cls .= ' image-box-' . $type;

    if ( 'card' == $type ) {
        $wrapper_cls .= ' image-box-gallery';
    }
} else {
    $wrapper_cls .= ' position-' . $image_position;

    if ( 'top' != $image_position ) {
        $wrapper_cls .= ' image-box-side';
    }
}

if ( ! $overlay && ! $type && $img_style ) {
    $wrapper_cls .= ' image-' . $img_style . ( 'style-1' != $img_style ? ' image-style-transform' : '' );
}

if ( $image_box_img_shape ) {
    $wrapper_cls .= ' image-shape-circle';
}

if ( $overlay && empty( $link['url'] ) ) {
    $wrapper_cls .= ' ' . $overlay_cls;
}
$html = '<div class="' . esc_attr( $wrapper_cls ) . '">';

$action_html = '';

if ( 'external_link' != $btn_links && ! empty( $image['url'] ) ) {
    $action_html .= '<a href="' . $image['url'] . '" class="btn btn-image-popup btn-ellipse ' . ( ! empty( $gallery_popup_btn_icon['value'] ) ? $gallery_popup_btn_icon['value'] : ( ALPUS_ICON_PREFIX . '-icon-search-plus' ) ) . '"></a>';
}

if ( 'popup_link' != $btn_links ) {
    $action_html .= '<a ' . $link_attr . ' class="btn btn-ellipse ' . ( ! empty( $gallery_btn_icon['value'] ) ? $gallery_btn_icon['value'] : ( ALPUS_ICON_PREFIX . '-icon-long-arrow-right' ) ) . '"></a>';
}

if ( ! $type ) {
    $html .= $link_open . '<figure>' . $image_html . '</figure>' . $link_close;
    $html .= '<div class="image-box-content">' . $title_html . $content_html . $button_html . '</div>';
} elseif ( 'gallery' == $type ) {
    $html .= $link_open . '<figure>' . $image_html . '</figure>' . $link_close;
    $html .= '<div class="image-box-info">' . $title_html . $content_html . '</div>';
    $html .= '<div class="content-hover">' . $action_html . '</div>';
} elseif ( 'card' == $type ) {
    $html .= $link_open . '<figure>' . $image_html . '</figure>' . $link_close;
    $html .= '<div class="image-box-info">' . $title_html . $content_html . '</div>';
    $html .= '<div class="image-box-action content-hover">' . $action_html . '</div>';
} else {
    $html .= $link_open . '<figure>' . $image_html . '</figure>' . $link_close;
    $html .= '<div class="image-box-content">' . $title_html . $button_html . '</div>';
}

$html .= '</div>';

echo alpus_escaped( $html );
