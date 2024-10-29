<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! empty( $settings['icon_primary_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . '{ --alpus-icon-primary:' . esc_html( $settings['icon_primary_selector'] ) . '; }';
}

if ( ! empty( $settings['icon_primary_hover_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . '{ --alpus-icon-primary-hover:' . esc_html( $settings['icon_primary_hover_selector'] ) . '; }';
}

if ( ! empty( $settings['icon_secondary_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . '{ --alpus-icon-secondary:' . esc_html( $settings['icon_secondary_selector'] ) . '; }';
}

if ( ! empty( $settings['icon_secondary_hover_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . '{ --alpus-icon-secondary-hover:' . esc_html( $settings['icon_secondary_hover_selector'] ) . '; }';
}

if ( ! empty( $settings['icon_spacing_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . '{ --alpus-icon-spacing:' . esc_html( $settings['icon_spacing_selector'] ) . 'px; }';
}

if ( ! empty( $settings['icon_size_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . ' .icon-box-icon{ font-size:' . esc_html( $settings['icon_size_selector'] ) . 'px; }';
}

if ( ! empty( $settings['icon_padding_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . ' .icon-box-icon{ padding:' . esc_html( $settings['icon_padding_selector'] ) . 'px; }';
}

if ( ! empty( $settings['icon_border_width_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . ' .icon-box-icon{ border-width:' . esc_html( $settings['icon_border_width_selector'] ) . 'px; }';
}

if ( ! empty( $settings['content_align_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . ', html ' . alpus_escaped( $selector ) . ' .icon-box-content{ text-align:' . esc_html( $settings['content_align_selector'] ) . '; }';
}

if ( ! empty( $settings['content_valign_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . '{ align-items:' . esc_html( $settings['content_valign_selector'] ) . '; }';
}

if ( ! empty( $settings['title_spacing_selector'] ) ) {
    echo 'html ' . alpus_escaped( $selector ) . ' .icon-box-title{ margin-bottom:' . esc_html( $settings['title_spacing_selector'] ) . 'px; }';
}