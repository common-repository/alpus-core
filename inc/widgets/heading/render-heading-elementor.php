<?php

defined( 'ABSPATH' ) || die;

/*
 * Alpus Heading Widget Render
 *
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 */

extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'dynamic_content'          => 'title',
            'title'                    => '',
            'link_dynamic_content'     => '',
            'link_url'                 => '',
            'tag'                      => 'h2',
            'decoration'               => '',
            'white_space'              => '',
            'title_align'              => '',
            'title_align_laptop'       => '',
            'title_align_tablet_extra' => '',
            'title_align_tablet'       => '',
            'title_align_mobile_extra' => '',
            'title_align_mobile'       => '',
            'background_type'          => '',
            'gradient_type'            => '',
            'wrap_class'               => '',

            // For elementor inline editing
            'self'                 => '',
        ),
        $atts
    )
);

$html = '';

$class = ! empty( $class ) ? $class . ' title elementor-heading-title' : 'title elementor-heading-title';

if ( $decoration ) {
    $wrap_class .= ' title-' . $decoration;
}

if ( $background_type || $gradient_type ) {
    $wrap_class .= ' title-background';
}

if ( $background_type ) {
    $wrap_class .= ' title-background-image';
}

if ( $gradient_type ) {
    $wrap_class .= ' title-gradient';
}

if ( $white_space ) {
    $wrap_class .= ' ws-nowrap';
}

if ( $title_align ) {
    $wrap_class .= ' ' . $title_align;
}

if ( $title_align && $title_align_tablet_extra ) {
    $wrap_class .= ' ' . str_replace( '-', '-xl-', $title_align_tablet_extra );
}

if ( $title_align && $title_align_tablet ) {
    $wrap_class .= ' ' . str_replace( '-', '-lg-', $title_align_tablet );
}

if ( $title_align && $title_align_mobile_extra ) {
    $wrap_class .= ' ' . str_replace( '-', '-md-', $title_align_mobile_extra );
}

if ( $title_align && $title_align_mobile ) {
    $wrap_class .= ' ' . str_replace( '-', '-sm-', $title_align_mobile );
}

$html .= '<div class="title-wrapper ' . esc_attr( $wrap_class ) . '">';

if ( $self ) {
    $self->add_render_attribute( 'title', 'class', $class );
}

if ( $title ) {
    // dynamic content
    if ( ! empty( $atts['text_source'] ) && isset( $atts['dynamic_content'] ) && ! empty( $atts['dynamic_content']['source'] ) ) {
        $title = apply_filters( 'alpus_dynamic_tags_content', '', null, $atts['dynamic_content'] );
    }

    if ( $self ) {
        $html .= sprintf( '%1$s<%2$s ' . $self->get_render_attribute_string( 'title' ) . '>%3$s</%2$s>%4$s', $link_url['url'] ? '<a href=' . esc_url( $link_url['url'] ) . ' class="w-100">' : '', esc_html( $tag ), alpus_strip_script_tags( $title ), $link_url['url'] ? '</a>' : '' );
    } else {
        $heading_link = '';

        if ( ! empty( $atts['add_link'] ) && ! empty( $link_dynamic_content ) && ! empty( $link_dynamic_content['source'] ) ) {
            $heading_link = apply_filters( 'alpus_dynamic_tags_content', '', null, $link_dynamic_content );
        }

        if ( $heading_link ) {
            $html .= sprintf( '<a class="w-100" href="%1$s"><%2$s class="' . $class . '">%3$s</%2$s></a>', esc_url( $heading_link ), esc_html( $tag ), alpus_strip_script_tags( $title ) );
        } else {
            $html .= sprintf( '<%1$s class="' . $class . '">%2$s</%1$s>', esc_html( $tag ), alpus_strip_script_tags( $title ) );
        }
    }
}

$html .= '</div>';

echo do_shortcode( $html );
