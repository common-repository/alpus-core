<?php
/**
 * Render template for block widget.
 *
 * @author     D-THEMES
 *
 * @since      1.0.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! post_type_exists( ALPUS_NAME . '_template' ) ) {
    return;
}

extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'name'    => '',
            'is_menu' => false,
        ),
        $atts
    )
);

if ( ! $name ) {
    return;
}

// Get post ID.
$post_id = 0;

if ( is_numeric( $name ) ) {
    $post_id = absint( $name );
} else {
    global $wpdb;
    $post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_name = %s", ALPUS_NAME . '_template', $name ) );
}
$post_id = (int) $post_id;

if ( $post_id ) {
    // Polylang
    if ( function_exists( 'pll_get_post' ) && pll_get_post( $post_id ) ) {
        $lang_id = pll_get_post( $post_id );

        if ( $lang_id ) {
            $post_id = $lang_id;
        }
    }

    // WPML
    if ( function_exists( 'icl_object_id' ) ) {
        $lang_id = icl_object_id( $post_id, ALPUS_NAME . '_template', false, ICL_LANGUAGE_CODE );

        if ( $lang_id ) {
            $post_id = $lang_id;
        }
    }

    $the_post = get_post( $post_id, null, 'display' );

    if ( isset( $the_post ) && 'publish' != $the_post->post_status ) {
        return;
    }

    if ( $the_post ) {
        global $alpus_layout;

        // Tooltip to edit
        $edit_link = '';

        if ( current_user_can( 'edit_pages' ) && ! is_customize_preview() &&
            ( ! function_exists( 'alpus_is_elementor_preview' ) || ! alpus_is_elementor_preview() ) &&
            ( ! function_exists( 'alpus_is_wpb_preview' ) || ! alpus_is_wpb_preview() ) &&
            apply_filters( 'alpus_show_templates_edit_link', true ) ) {
            if ( defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $post_id, '_elementor_edit_mode', true ) ) {
                $edit_link = admin_url( 'post.php?post=' . $post_id . '&action=elementor' );
            } else {
                $edit_link = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
            }

            $builder_type = get_post_meta( $post_id, ALPUS_NAME . '_template_type', true );

            if ( ! $builder_type ) {
                $builder_type = esc_html__( 'Template', 'alpus-core' );
            }
        }

        if ( defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $post_id, '_elementor_edit_mode', true ) ) {
            /*
             * Fires before rendering elementor block content.
             *
             * @since 1.0
             */

            do_action( 'alpus_before_elementor_block_content', $the_post, ALPUS_NAME . '_template' );

            if ( ! alpus_is_elementor_preview() || ! isset( $_REQUEST['elementor-preview'] ) || sanitize_text_field( $_REQUEST['elementor-preview'] ) != $post_id ) { // Check if current elementor block is editing
                if ( ! ( $alpus_layout && isset( $alpus_layout['used_blocks'] ) && isset( $alpus_layout['used_blocks'][ $post_id ] ) && ! empty( $alpus_layout['used_blocks'][ $post_id ]['css'] ) ) ) {
                    if ( 'internal' !== get_option( 'elementor_css_print_method' ) ) {
                        $block_css = get_post_meta( (int) $post_id, 'page_css', true );

                        if ( $block_css ) {
                            $block_css = function_exists( 'alpus_minify_css' ) ? alpus_minify_css( $block_css ) : $block_css;
                        }

                        $upload        = wp_upload_dir();
                        $upload_dir    = $upload['basedir'];
                        $upload_url    = $upload['baseurl'];
                        $post_css_path = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $post_id . '.css' );

                        if ( file_exists( $post_css_path ) ) {
                            global $alpus_layout;

                            if ( empty( $alpus_layout['used_blocks'] ) ) {
                                $alpus_layout['used_blocks'] = array();
                            }
                            $alpus_layout['used_blocks'][ $post_id ] = array(
                                'css' => false,
                                'js'  => false,
                            );
                        } else {
                            if ( ! alpus_is_elementor_preview() && ( ! wp_doing_ajax() || empty( $is_menu ) ) ) {
                                $css_file = new Elementor\Core\Files\CSS\Post( $post_id );
                                $css_file->print_css();
                            }

                            if ( $block_css ) {
                                $style  = '';
                                $style .= '<style id="block_' . (int) $post_id . '_css">';
                                $style .= $block_css;
                                $style .= '</style>';

                                /*
                                 * Filters the style for elementor block.
                                 *
                                 * @since 1.0
                                 */
                                echo apply_filters( 'alpus_elementor_block_style', alpus_filter_inline_css( $style, false ) );
                            }
                        }
                    }
                }

                // load block js in theme-assets.php file
                if ( ! ( $alpus_layout && isset( $alpus_layout['used_blocks'] ) && isset( $alpus_layout['used_blocks'][ $post_id ] ) ) ) {
                    $block_js = get_post_meta( (int) $post_id, 'page_js', true );

                    if ( $block_js ) {
                        $style  = '';
                        $style .= '<script id="block_' . (int) $post_id . '_js">';

                        $block_js = alpus_strip_script_tags( $block_js );

                        $style .= $block_js;
                        $style .= '</script>';

                        /*
                         * Filters the script for elementor block.
                         *
                         * @since 1.0
                         */
                        echo apply_filters( 'alpus_elementor_block_script', $style );
                    }
                }
            }
            $el_attr  = '';
            $el_class = '';

            if ( alpus_is_elementor_preview() && is_single( $post_id ) && ! ( ALPUS_NAME . '_template' == get_post_type( $post_id ) && 'product_layout' == get_post_meta( $post_id, ALPUS_NAME . '_template_type', true ) ) ) {
                $el_attr = ' data-el-class="elementor-' . (int) $post_id . '"';
            } else {
                $el_class = ' elementor-' . (int) $post_id;
            }

            if ( $edit_link ) {
                /* translators: template name */
                echo '<div class="alpus-edit-link d-none" data-title="' . sprintf( esc_html__( 'Edit %1$s: %2$s', 'alpus-core' ), esc_attr( str_replace( '_', ' ', $builder_type ) ), esc_attr( get_the_title( $post_id ) ) ) . '" data-link="' . esc_url( $edit_link ) . '"></div>';
            }
            echo '<div class="alpus-block"' . alpus_escaped( $el_attr ) . ' data-block-id="' . (int) $post_id . '">';

            /*
             * Filters lazyload images.
             *
             * @since 1.0
             */
            echo apply_filters( 'alpus_lazyload_images', Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id ) );

            if ( ! ( $alpus_layout && isset( $alpus_layout['used_blocks'] ) && isset( $alpus_layout['used_blocks'][ $post_id ] ) && $alpus_layout['used_blocks'][ $post_id ]['css'] ) && 'internal' == get_option( 'elementor_css_print_method' ) ) {
                $block_css = get_post_meta( (int) $post_id, 'page_css', true );

                if ( $block_css ) {
                    $style  = '';
                    $style .= '<style id="block_' . (int) $post_id . '_css">';
                    $style .= function_exists( 'alpus_minify_css' ) ? alpus_minify_css( $block_css ) : $block_css;
                    $style .= '</style>';

                    /*
                     * Filters the style for elementor block.
                     *
                     * @since 1.0
                     */
                    echo apply_filters( 'alpus_elementor_block_style', alpus_filter_inline_css( $style, false ) );
                }
            }
            echo '</div>';
            /*
             * Fires after rendering elementor block content.
             *
             * @since 1.0
             */
            do_action( 'alpus_after_elementor_block_content', $the_post, ALPUS_NAME . '_template' );

            return;
        } else {
            /*
             * Fires before rendering block content.
             *
             * @since 1.0
             */
            do_action( 'alpus_before_block_content', $the_post, ALPUS_NAME . '_template' );

            if ( ! isset( $the_post->post_content ) ) {
                return;
            }
            $post_content = $the_post->post_content;

            if ( ! ( $alpus_layout && isset( $alpus_layout['used_blocks'] ) && isset( $alpus_layout['used_blocks'][ $post_id ] ) && $alpus_layout['used_blocks'][ $post_id ]['css'] ) && ! ( ( alpus_is_elementor_preview() && isset( $_REQUEST['elementor_preview'] ) && sanitize_text_field( $_REQUEST['elementor_preview'] ) == $post_id ) || ( alpus_is_wpb_preview() && isset( $_REQUEST['post_id'] ) && intval( $_REQUEST['post_id'] ) == $post_id ) ) ) {
                $block_css = '';

                if ( defined( 'WPB_VC_VERSION' ) ) {
                    $block_css .= get_post_meta( (int) $post_id, '_wpb_shortcodes_custom_css', true );
                    $block_css .= get_post_meta( (int) $post_id, '_wpb_post_custom_css', true );
                }

                $block_css .= get_post_meta( (int) $post_id, 'page_css', true );

                if ( $block_css ) {
                    ob_start();
                    echo '<style id="block_' . (int) $post_id . '_css">';
                    echo function_exists( 'alpus_minify_css' ) ? alpus_minify_css( $block_css ) : alpus_escaped( $block_css );
                    echo '</style>';
                    alpus_filter_inline_css( ob_get_clean() );
                }
            }

            if ( ! ( $alpus_layout && isset( $alpus_layout['used_blocks'] ) && isset( $alpus_layout['used_blocks'][ $post_id ] ) && $alpus_layout['used_blocks'][ $post_id ]['js'] ) && ! ( ( alpus_is_elementor_preview() && isset( $_REQUEST['elementor_preview'] ) && sanitize_text_field( $_REQUEST['elementor_preview'] ) == $post_id ) || ( alpus_is_wpb_preview() && isset( $_REQUEST['post_id'] ) && intval( $_REQUEST['post_id'] ) == $post_id ) ) ) {
                $block_js = get_post_meta( (int) $post_id, 'page_js', true );

                if ( $block_js ) {
                    $style  = '';
                    $style .= '<script id="block_' . (int) $post_id . '_js">';

                    $block_js = alpus_strip_script_tags( $block_js );

                    $style .= $block_js;
                    $style .= '</script>';

                    /*
                     * Filters the script for elementor block.
                     *
                     * @since 1.0
                     */
                    echo apply_filters( 'alpus_elementor_block_script', $style );
                }
            }

            if ( $edit_link ) {
                /* translators: %1$s represents template type, %2$s represents post title. */
                echo '<div class="alpus-edit-link d-none" data-title="' . sprintf( esc_html__( 'Edit %1$s: %2$s', 'alpus-core' ), esc_attr( str_replace( '_', ' ', $builder_type ) ), esc_attr( get_the_title( $post_id ) ) ) . '" data-link="' . esc_url( $edit_link ) . '"></div>';
            }
            echo '<div class="alpus-block" data-block-id="' . (int) $post_id . '">';

            if ( function_exists( 'has_blocks' ) && has_blocks( $the_post ) ) {
                echo do_shortcode( do_blocks( $post_content ) );
            } else {
                echo do_shortcode( $post_content );
            }
            echo '</div>';

            /*
             * Fires after rendering elementor block content.
             *
             * @since 1.0
             */
            do_action( 'alpus_after_block_content', $the_post, ALPUS_NAME . '_template' );
        }
    }
}
