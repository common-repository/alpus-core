<?php
/**
 * The testimonial simple render.
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
$html .= '<blockquote class="testimonial testimonial-simple ' . ( 'yes' == $atts['testimonial_inverse'] ? ' inversed' : '' ) . ( ! empty( $rating ) ? '" data-rating=' . esc_attr( $rating ) : '" ' ) . '>';
$html .= '<div class="content">' . $content . '</div>';
$html .= '<div class="commenter">';
$html .= $avatar_html;
$html .= '<div class="commentor-info">';
$html .= $rating_html;
$html .= $commenter;
$html .= '</div></div>';
$html .= '</blockquote>';
