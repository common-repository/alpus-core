<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! empty( $settings['decoration_spacing_selector'] ) ) {
    echo alpus_escaped( $selector ) . '.title-cross .title:after{ margin-left: ' . esc_html( $settings['decoration_spacing_selector'] ) . '; }' . alpus_escaped( $selector ) . ' .title-cross .title:before{ margin-right:' . esc_html( $settings['decoration_spacing_selector'] ) . '; }';
}

if ( ! empty( $settings['border_color_selector'] ) ) {
    echo alpus_escaped( $selector ) . '.title-cross .title:before,' . alpus_escaped( $selector ) . '.title-cross .title:after{ background-color: ' . esc_html( $settings['border_color_selector'] ) . '; }';
}
