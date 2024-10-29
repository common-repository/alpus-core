<?php

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'disable_action'   => '',
            'icon_cls'         => '',
            'dislike_icon_cls' => '',
            'icon_pos'         => '',
            'el_class'         => '',
            'className'        => '',
        ),
        $atts
    )
);

$post_id = get_the_ID();

if ( empty( $post_id ) ) {
    return;
}

$wrap_cls = 'alpus-tb-post-like';

if ( $el_class && wp_is_json_request() ) {
    $wrap_cls .= ' ' . trim( $el_class );
}

if ( $className ) {
    $wrap_cls .= ' ' . trim( $className );
}

$icon_html = '';

$like_btn_class = isset( $_COOKIE[ 'alpus_post_likes_' . $post_id ] ) && ( json_decode( sanitize_text_field( wp_unslash( $_COOKIE[ 'alpus_post_likes_' . $post_id ] ) ), true )['action'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE[ 'alpus_post_likes_' . $post_id ] ) ), true )['action'] : 'like';

if ( ! $icon_cls ) {
    $icon_cls         = Alpus_Plugin_Options::get_option( 'alpus_post_like_icon' );
    $dislike_icon_cls = Alpus_Plugin_Options::get_option( 'alpus_post_like_active_icon' );

    if ( ! $icon_cls && ! $dislike_icon_cls ) {
        $like_btn_class =  ( 'like' == $like_btn_class ? 'alpus-like' : 'alpus-dislike' );
    }
} elseif ( ! $dislike_icon_cls ) {
    $dislike_icon_cls = Alpus_Plugin_Options::get_option( 'alpus_post_like_active_icon' );
}

if ( false === strpos( $like_btn_class, 'alpus-' ) ) {
    $class = 'like' == $like_btn_class ? $icon_cls : $dislike_icon_cls;

    if ( '' !== $class ) {
        $icon_html = '<i class="' . esc_attr( $class ) . '"></i>';
    } else {
        $icon_html = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70" width="16px" height="16px" preserveAspectRatio="none">
			<path d="' . esc_attr( 'like' == $like_btn_class ? Alpus_Post_Like::get_instance()->default_icons['like'] : Alpus_Post_Like::get_instance()->default_icons['dislike'] ) . '"></path>
		</svg>';
    }
}

$likes_count    = get_post_meta( $post_id, 'alpus_post_likes', true );
$tag            = 'a';
$wrap_cls .= ' ' . $like_btn_class;
$wrap_attrs     = '';

if ( $disable_action ) {
    $tag = 'span';
} else {
    $wrap_cls .= ' vote-link';
    $wrap_attrs .= ' href="#" data-count="' . absint( $likes_count ) . '" data-id="' . absint( $post_id ) . '" data-like-icon="' . esc_attr( $icon_cls ) . '" data-dislike-icon="' . esc_attr( $dislike_icon_cls ) . '"';
}

echo '<' . alpus_escaped( $tag ) . ' class="' . esc_attr( apply_filters( ALPUS_GUTENBERG_BLOCK_CLASS_FILTER, $wrap_cls, $atts, ALPUS_NAME . '-tb/' . ALPUS_NAME . '-post-like' ) ) . '"' . $wrap_attrs . '>';

if ( $icon_html && ! $icon_pos ) {
    echo alpus_escaped( $icon_html );
}

echo '<span class="like-count">' . absint( $likes_count ) . '</span>';

if ( $icon_html && $icon_pos ) {
    echo alpus_escaped( $icon_html );
}

echo '</' . alpus_escaped( $tag ) . '>';
