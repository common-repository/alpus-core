<?php
/**
 * The testimonial boxed horzontal render.
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
$html .= '<blockquote class="testimonial testimonial-boxed testimonial-aside ' . esc_attr( $atts['h_align'] ) . ( ! empty( $rating ) ? '" data-rating=' . esc_attr( $rating ) : '" ' ) . '>';

if ( 'after' === $commenter_pos ) {
    $html .= '<div class="content">';
    $html .= $content;
    $html .= '</div>';
}
$html .= '<div class="commentor">';
$html .= $avatar_html;
$html .= '<div class="commentor-info">';
$html .= $rating_html;
$html .= $commenter;
$html .= '</div></div>';

if ( 'before' === $commenter_pos ) {
    $html .= '<div class="content">';
    $html .= $content;
    $html .= '</div>';
}

$html .= '</blockquote>';
