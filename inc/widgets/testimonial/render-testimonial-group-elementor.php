<?php
/**
 * Testimonials Shortcode Render
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
            // Items
            'testimonial_group_list' => array(),

            // General
            'testimonial_type'       => 'boxed',
            'star_icon'              => '',
            'rating_sp'              => array( 'size' => 0 ),

            //Testimonial Layout
            'layout_type'            => 'grid',
            'col_sp'                 => '',
            'col_cnt'                => array( 'size' => 4 ),
        ),
        $atts
    )
);

if ( ! is_array( $col_cnt ) ) {
    $col_cnt = json_decode( $col_cnt, true );
}

// Wrapper classes & attributes
$wrapper_class = array();
$wrapper_attrs = '';

$grid_space_class = alpus_get_grid_space_class( $atts );
$col_cnt          = alpus_elementor_grid_col_cnt( $atts );

if ( $grid_space_class ) {
    $wrapper_class[] = $grid_space_class;
}

if ( $col_cnt ) {
    $wrapper_class[] = alpus_get_col_class( $col_cnt );
}

if ( 'slider' == $layout_type ) {
    $wrapper_class[] = alpus_get_slider_class( $atts );
    $wrapper_class   = implode( ' ', $wrapper_class );

    $wrapper_attrs = ' data-slider-options="' . esc_attr(
        wp_json_encode(
            alpus_get_slider_attrs( $atts, $col_cnt )
        )
    ) . '"';

    echo '<div ' . alpus_escaped( $wrapper_attrs ) . ' class="testimonial-group ' . esc_attr( $wrapper_class ) . '">';
} else {
    $wrapper_class = implode( ' ', $wrapper_class );
    echo '<div class="testimonial-group ' . esc_attr( $wrapper_class ) . '">';
}

$group_settings = $atts;
unset( $group_settings['testimonial_group_list'] );

foreach ( $testimonial_group_list as $key => $item ) {
    $atts = array_merge( $group_settings, $item );
    echo '<div class="widget-testimonial-wrap">';
    require ALPUS_CORE_INC . '/widgets/testimonial/render-testimonial-elementor.php';
    echo '</div>';
}

echo '</div>';
