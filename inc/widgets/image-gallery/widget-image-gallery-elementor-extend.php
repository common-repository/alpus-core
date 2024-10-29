<?php

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
/*
 * Alpus Image Gallery Widget Extend
 *
 * Alpus Widget to display image.
 *
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;

add_action(
    'elementor/element/' . ALPUS_NAME . '_widget_imagegallery/gallery_style/after_section_end',
    function ( $self ) {
        $self->add_responsive_control(
            'img_max_width',
            array(
                'label'      => esc_html__( 'Max Width', 'alpus-core' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'unit' => 'px',
                ),
                'size_units' => array(
                    'px',
                    'rem',
                    '%',
                    'vh',
                ),
                'selectors'  => array(
                    '.elementor-element-{{ID}} img' => 'max-width:{{SIZE}}{{UNIT}}; width: 100%',
                ),
            ),
            array(
                'position' => array(
                    'at' => 'before',
                    'of' => 'gallery_image_border',
                ),
            )
        );
    }
);
