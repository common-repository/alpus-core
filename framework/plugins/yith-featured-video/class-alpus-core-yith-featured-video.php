<?php
/**
 * Yith Woocommerce Featured Video Compatibility
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if ( ! class_exists( 'Alpus_Core_Featured_Audio_Video' ) ) {
    /**
     * Alpus Featured Video Audio Class
     */
    class Alpus_Core_Featured_Audio_Video extends Alpus_Base {

        protected $counter;

        /**
         * Constructor
         *
         * @since 1.0
         */
        public function __construct() {
            $this->counter = 0;
            remove_filter( 'woocommerce_single_product_image_thumbnail_html', array( YITH_Featured_Audio_Video_Frontend::get_instance(), 'get_video_audio_content' ), 10 );
            add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'get_video_audio_content' ), 10, 2 );

            add_action( 'wp_footer', array( $this, 'enqueue_style' ), 19 );
            add_filter( 'yith_woocommerce_featured_video_type', array( $this, 'get_featured_video_type' ), 10, 2 );
        }

        /**
         * Custom style for Yith Featured Video
         *
         * @since 1.0
         */
        public function enqueue_style() {
            wp_enqueue_style( 'alpus-featured-video-style', alpus_core_framework_uri( '/plugins/yith-featured-video/yith-featured-video' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpus-theme' ), ALPUS_CORE_VERSION );
        }

        /**
         * Add the video in the Woocommerce gallery
         *
         * @since 1.0
         *
         * @param string $html              the current html
         * @param int    $post_thumbnail_id the thumbnail id
         *
         * @return string
         */
        public function get_video_audio_content( $html, $post_thumbnail_id ) {
            global $product;

            if ( 0 == $this->counter && $post_thumbnail_id === $product->get_image_id() ) {
                $video_args = YITH_Featured_Video_Manager()->get_featured_video_args( $product );

                if ( ! empty( $video_args ) ) {
                    ob_start();
                    wc_get_template( 'template_video.php', $video_args );
                    $html = ob_get_contents();
                    ob_end_clean();

                    $this->counter ++;
                }
            }

            return $html;
        }

        /**
         * Get featured video type
         *
         * @since 1.0
         *
         * @param string $type Viode type
         * @param string $url  Url of featured video
         */
        public function get_featured_video_type( $type, $url ) {
            $site_url = parse_url( site_url() );
            $parsed   = parse_url( esc_url( $url ) );

            if ( $site_url['host'] === $parsed['host'] ) {
                return $site_url['host'] . ':' . $parsed['path'];
            }

            return false;
        }
    }
}

Alpus_Core_Featured_Audio_Video::get_instance();
