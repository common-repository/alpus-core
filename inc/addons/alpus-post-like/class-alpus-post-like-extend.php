<?php
/**
 * Alpus Post Like Addon
 *
 * @since 1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! class_exists( 'Alpus_Post_Like_Manager' ) ) {
    return;
}

if ( ! class_exists( 'Alpus_Post_Like_Extend' ) ) {
    class Alpus_Post_Like_Extend {

        /**
                 * Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            add_action( 'enqueue_block_editor_assets', array( $this, 'add_editor_assets' ), 999 );

            // register blocks
            add_filter(
                'alpus_core_type_builder_custom_blocks',
                function ( $blocks = array() ) {
                    $blocks['post-like'] = array(
                        'attributes' => array(
                            'content_type'       => array(
                                'type' => 'string',
                            ),
                            'content_type_value' => array(
                                'type' => 'string',
                            ),
                            'disable_action'     => array(
                                'type' => 'boolean',
                            ),
                            'icon_cls'           => array(
                                'type' => 'string',
                            ),
                            'dislike_icon_cls'   => array(
                                'type' => 'string',
                            ),
                            'icon_pos'           => array(
                                'type' => 'string',
                            ),
                            'st_icon_fs'         => array(
                                'type' => 'string',
                            ),
                            'st_icon_spacing'    => array(
                                'type' => 'integer',
                            ),
                            'alignment'          => array(
                                'type' => 'string',
                            ),
                            'font_settings'      => array(
                                'type' => 'object',
                            ),
                            'style_options'      => array(
                                'type' => 'object',
                            ),
                            'el_class'           => array(
                                'type' => 'string',
                            ),
                            'className'          => array(
                                'type' => 'string',
                            ),
                        ),
                        'path'       => ALPUS_CORE_INC . '/addons/alpus-post-like/post-like-render.php',
                    );

                    return $blocks;
                }
            );

            add_filter( 'alpus_gutenberg_block_style', array( $this, 'output_block_styles' ), 10, 4 );
        }

        /**
         * Enqueue Post Like Gutenberg block
         *
         * @since 1.0
         */
        public function add_editor_assets() {
            $screen = get_current_screen();

            if ( $screen && $screen->is_block_editor() && 'post' == $screen->base && ALPUS_NAME . '_template' == $screen->id ) {
                wp_enqueue_script( 'alpus-block-post-like', ALPUS_CORE_INC_URI . '/addons/alpus-post-like/block.min.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-data' ), ALPUS_CORE_VERSION, true );
            }
        }

        /**
         * Generate block internal styles
         *
         * @since 1.0
         */
        public function output_block_styles( $saved_css, $block_name, $atts, $selector ) {
            if ( empty( $atts ) || empty( $selector ) ) {
                return $saved_css;
            }

            $style_name = '';

            if ( ALPUS_NAME . '-tb/' . ALPUS_NAME . '-post-like' == $block_name ) {
                $style_name = 'style.php';
                $selector .= ' .alpus-tb-icon';
            }

            if ( $style_name ) {
                $atts['selector'] = $selector;
                ob_start();
                include ALPUS_CORE_INC . '/addons/alpus-post-like/' . $style_name;
                $css_part = ob_get_clean();

                if ( $css_part && false === strpos( $saved_css, $css_part ) ) {
                    $saved_css .= $css_part;
                }
            }

            return $saved_css;
        }
    }
}

new Alpus_Post_Like_Extend();
