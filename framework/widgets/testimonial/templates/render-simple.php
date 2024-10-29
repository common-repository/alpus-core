<?php

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
$html .= '<blockquote class="testimonial testimonial-simple' . ( 'yes' == $atts['testimonial_inverse'] ? ' inversed' : '' ) . '">';
$html .= '<div class="content">' . $content . '</div>';
$html .= '<div class="commenter">' . $image . $commenter . '</div>';
$html .= '</blockquote>';
