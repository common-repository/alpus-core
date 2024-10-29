<?php
/**
 * Alpus Comments Pagination
 *
 * @author     D-THEMES
 *
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Comments_Pagination' ) ) {
    class Alpus_Comments_Pagination extends Alpus_Base {

        /**
                 * Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );

            add_action( 'wp_ajax_alpus_comments_pagination', array( $this, 'get_ajax_comments_html' ), 10 );
            add_action( 'wp_ajax_nopriv_alpus_comments_pagination', array( $this, 'get_ajax_comments_html' ), 10 );

            add_action( 'alpus_before_comments', array( $this, 'ajax_handler' ), 10 );
            add_filter( 'alpus_get_comments_pagination_html', array( $this, 'get_comments_pagination_html' ), 10 );
        }

        /**
         * Custom style for comments pagination
         *
         * @since 1.0
         */
        public function enqueue_scripts() {
            if ( ! is_single() ) {
                return;
            }

            wp_enqueue_script( 'alpus-comments-pagination', alpus_core_framework_uri( '/addons/comments-pagination/comments-pagination' . ALPUS_JS_SUFFIX ), array( 'alpus-framework-async' ), ALPUS_CORE_VERSION, true );
        }

        /**
         * Html for comments pagination
         *
         * @since 1.0
         */
        public function get_comments_pagination_html() {
            $page = intval( get_query_var( 'cpage' ) );

            $args = apply_filters(
                'alpus_comments_pagination_args',
                array(
                    'echo'      => false,
                    'current'   => $page,
                    'end_size'  => 1,
                    'mid_size'  => 2,
                    'prev_text' => '<i class="' . ALPUS_ICON_PREFIX . '-icon-long-arrow-left"></i> ' . esc_html__( 'Prev', 'alpus-core' ),
                    'next_text' => esc_html__( 'Next', 'alpus-core' ) . ' <i class="' . ALPUS_ICON_PREFIX . '-icon-long-arrow-right"></i>',
                )
            );

            $links = paginate_comments_links( $args );

            if ( $links ) {
                if ( 1 == $page ) {
                    $links = sprintf(
                        '<span class="prev page-numbers disabled">%s</span>',
                        $args['prev_text']
                    ) . $links;
                } elseif ( get_comment_pages_count() == $page ) {
                    $links .= sprintf(
                        '<span class="next page-numbers disabled">%s</span>',
                        $args['next_text']
                    );
                }
            }

            return $links;
        }

        /**
         * Comments by using ajax request
         *
         * @since 1.0
         */
        public function get_ajax_comments_html() {
            // phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification
            global $wp_query;

            $post_id   = intval( $_REQUEST['post'] );
            $page      = sanitize_text_field( $_REQUEST['page'] );
            $req_posts = new WP_Query(
                array(
                    'p'         => (int) $post_id,
                    'post_type' => sanitize_text_field( $_REQUEST['post_type'] ),
                )
            );

            if ( $req_posts->have_posts() ) {
                $req_posts->the_post();
                $wp_query->is_single   = true;
                $wp_query->is_singular = true;
                $wp_query->set( 'cpage', $page );

                comments_template();
            }
            exit();
            // phpcs:enable
        }

        /**
         * Handler of ajax request
         *
         * @since 1.0
         */
        public function ajax_handler() {
            if ( alpus_doing_ajax() ) {
                // Retrive comments list for current page
                ob_start();
                wp_list_comments(
                    apply_filters(
                        'alpus_filter_comment_args',
                        array(
                            'callback'          => 'alpus_post_comment',
                            'style'             => 'ol',
                            'format'            => 'html5',
                            'short_ping'        => true,
                            'reverse_top_level' => true,
                        )
                    )
                );
                $html = ob_get_clean();

                // Retrive comments pagination for current page
                $pagination = $this->get_comments_pagination_html();

                // Send data
                wp_send_json(
                    array(
                        'html'       => $html,
                        'pagination' => $pagination,
                    )
                );
            }

            return;
        }
    }
}

Alpus_Comments_Pagination::get_instance();
