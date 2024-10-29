<?php
/**
 * Alpus Restful API
 *
 * @author     D-THEMES
 *
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

class Alpus_Rest_Api {

    /**
         * Ajax Request
         *
         * @since 1.0
         */
    protected $request = array();

    /**
     * Post types
     *
     * @since 1.0
     */
    public $post_types = array();

    /**
     * Taxonomies
     *
     * @since 1.0
     */
    public $taxonomies = array();

    /**
     * Constructor
     *
     * @since 1.0
     */
    public function __construct() {
        /*
         * Filters the selected post types.
         *
         * @since 1.0
         */
        $this->post_types = apply_filters( 'alpus_select_post_types', array( 'post', 'page', 'product', 'wpcf7_contact_form', 'wpforms', 'block', 'type', 'portfolio', 'service', 'member', 'popup' ) );

        /*
         * Filters the selected taxonomies.
         */
        $this->taxonomies = apply_filters( 'alpus_select_taxonomies', array( 'category', 'product_cat', 'product_brand' ) );
    }

    /**
     * Get method from request and implement it
     *
     * @since 1.0
     */
    public function get_action( $request ) {
        if ( isset( $request['method'] ) ) {
            $this->request = $request;

            if ( in_array( $request['method'], $this->post_types ) ) {
                return $this->get_archives( $request['method'] );
            } elseif ( in_array( $request['method'], $this->taxonomies ) ) {
                return $this->get_taxonomies( $request['method'] );
            } elseif ( false !== strpos( $request['method'], '_alltax' ) ) {
                return $this->get_all_taxonomies_by_condition();
            } elseif ( false !== strpos( $request['method'], '_allterm' ) ) {
                return $this->get_all_terms_by_condition();
            } elseif ( false !== strpos( $request['method'], '_particularpage' ) ) { // conditional rendering
                if ( ! empty( $request['condition'] ) ) {
                    $this->request['count'] = 'all';

                    return $this->get_archives( $request['condition'] );
                } elseif ( ! empty( $request['ids'] ) ) {
                    return $this->get_archives( 'any' );
                } else {
                    return array( 'results' => array() );
                }
            } else {
                return $this->get_vendors();
            }
        }
    }

    /**
     * Get vendor list
     *
     * @since 1.0
     */
    public function get_vendors() {
        $query_args = array();

        if ( isset( $this->request['ids'] ) ) {
            $ids                   = $this->request['ids'];
            $query_args['include'] = $ids;
            $query_args['orderby'] = 'include';

            if ( '' == $this->request['ids'] ) {
                return array( 'results' => array() );
            }
        }

        if ( isset( $this->request['s'] ) ) {
            $query_args['s'] = $this->request['s'];
        }

        $options = function_exists( 'alpus_get_vendors' ) ? alpus_get_vendors( $query_args ) : array();

        return array( 'results' => $options );
        wp_reset_postdata();
    }

    /**
     * Get Archives
     *
     * @since 1.0
     */
    public function get_archives( $post_type = 'post' ) {
        if ( 'block' == $post_type ) {
            $query_args = array(
                'post_type'      => ALPUS_NAME . '_template',
                'post_status'    => 'publish',
                'meta_key'       => ALPUS_NAME . '_template_type',
                'meta_value'     => $post_type,
                'posts_per_page' => 15,
            );
        } elseif ( 'type' == $post_type ) {
            $query_args = array(
                'post_type'      => ALPUS_NAME . '_template',
                'post_status'    => 'publish',
                'meta_key'       => ALPUS_NAME . '_template_type',
                'meta_value'     => 'type',
                'posts_per_page' => 15,
            );
        } elseif ( 'popup' === $post_type ) {
            $builders_array = json_decode( wp_unslash( alpus_get_option( 'resource_template_builders' ) ), true );

            if ( empty( $builders_array['popup'] ) ) {
                $query_args = array(
                    'post_type'      => ALPUS_NAME . '_template',
                    'post_status'    => 'publish',
                    'meta_key'       => ALPUS_NAME . '_template_type',
                    'meta_value'     => 'popup',
                    'posts_per_page' => 15,
                );
            }
        } else {
            $query_args = array(
                'post_type'      => $post_type,
                'post_status'    => 'publish',
                'posts_per_page' => 15,
            );
        }

        if ( ! empty( $this->request['count'] ) && 'all' == $this->request['count'] ) {
            $query_args['posts_per_page'] = -1;
            $query_args['fields']         = 'ids';
        }

        if ( isset( $this->request['ids'] ) ) {
            $ids                    = explode( ',', $this->request['ids'] );
            $query_args['post__in'] = $ids;
            $query_args['orderby']  = 'post__in';

            if ( '' == $this->request['ids'] ) {
                return array(
                    'results' => array(),
                );
            }
        }

        if ( isset( $this->request['s'] ) ) {
            $query_args['s'] = $this->request['s'];
        }

        /**
         * Filters archives filtered by query.
         *
         * @since 1.0
         */
        $query = new WP_Query( apply_filters( 'alpus_get_archives', $query_args ) );

        $options = array();

        if ( isset( $this->request['add_default'] ) ) {
            $options[] = array(
                'id'   => '',
                'text' => esc_html__( 'Default', 'alpus-core' ),
            );
        }

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                global $post;

                if ( empty( $query_args['fields'] ) ) {
                    $options[] = array(
                        'id'   => $post->ID,
                        'text' => $post->post_title,
                    );
                } else {
                    $options[] = array(
                        'id'   => $post,
                        'text' => get_the_title( $post ),
                    );
                }
            }
        }

        return array( 'results' => $options );
        wp_reset_postdata();
    }

    /**
     * Get Taxonomies
     *
     * @since 1.0
     */
    public function get_taxonomies( $taxonomy = 'category' ) {
        $query_args = array(
            'taxonomy'   => array( $taxonomy ),
            'hide_empty' => false,
        );

        if ( isset( $this->request['ids'] ) ) {
            $ids                   = explode( ',', $this->request['ids'] );
            $query_args['include'] = $ids;
            $query_args['orderby'] = 'include';

            if ( '' == $this->request['ids'] ) {
                return array( 'results' => array() );
            }
        }

        if ( isset( $this->request['s'] ) ) {
            $query_args['name__like'] = $this->request['s'];
        }

        /**
         * Filters taxonomies filtered by query.
         *
         * @since 1.0
         */
        $terms = get_terms( apply_filters( 'alpus_get_taxonomies', $query_args ) );

        $options = array();
        $count   = count( $terms );

        if ( $count > 0 ) {
            foreach ( $terms as $term ) {
                $options[] = array(
                    'id'   => $term->term_id,
                    'text' => htmlspecialchars_decode( $term->name ),
                );
            }
        }

        return array( 'results' => $options );
    }

    /**
     * Get all taxonomies by condition
     *
     * @since 1.2.0
     */
    protected function get_all_taxonomies_by_condition() {
        $options = array();

        if ( ! empty( $this->request['condition'] ) ) {
            $new_taxonomies = get_object_taxonomies( $this->request['condition'], 'objects' );

            foreach ( $new_taxonomies as $new_taxonomy ) {
                if ( in_array( $new_taxonomy->name, array( 'post_format', 'product_visibility' ) ) ) {
                    continue;
                }
                $options[] = array(
                    'id'   => esc_html( $new_taxonomy->name ),
                    'text' => esc_html( $new_taxonomy->label ),
                );
            }
        } elseif ( isset( $this->request['ids'] ) ) {
            $tax = get_taxonomy( $this->request['ids'] );

            if ( $tax && ! is_wp_error( $tax ) ) {
                $options[] = array(
                    'id'   => esc_html( $tax->name ),
                    'text' => esc_html( $tax->label ),
                );
            }
        }

        return array( 'results' => $options );
    }

    /**
     * Get all terms by condition
     *
     * @since 1.2.0
     */
    protected function get_all_terms_by_condition() {
        $options = array();

        if ( ! empty( $this->request['condition'] ) ) {
            $args = array(
                'taxonomy'   => sanitize_text_field( $this->request['condition'] ), // taxonomy name
                'hide_empty' => false,
                'fields'     => 'id=>name',
            );

            if ( isset( $this->request['s'] ) ) {
                $args['name__like'] = sanitize_text_field( $this->request['s'] );
            }
            $terms = get_terms( $args );

            if ( isset( $this->request['add_default'] ) ) {
                $options[] = array(
                    'id'   => '',
                    'text' => esc_html__( 'Default', 'alpus-core' ),
                );
            }

            foreach ( $terms as $term_id => $term_name ) {
                $options[] = array(
                    'id'   => esc_html( $term_id ),
                    'text' => esc_html( $term_name ),
                );
            }
        } elseif ( ! empty( trim( $this->request['ids'] ) ) ) {
            $ids = explode( ',', sanitize_text_field( trim( $this->request['ids'] ) ) );

            foreach ( $ids as $term_id ) {
                $term = get_term( $term_id );

                if ( $term && ! is_wp_error( $term ) ) {
                    $options[] = array(
                        'id'   => esc_html( $term_id ),
                        'text' => esc_html( $term->name ),
                    );
                }
            }
        }

        return array( 'results' => $options );
    }
}

/**
 * Get an instance of Alpus_Rest_Api and
 * call an action
 *
 * @since 1.0
 */
function alpus_ajax_select_api( WP_REST_Request $request ) {
    $api = new Alpus_Rest_Api();

    return $api->get_action( $request );
}

add_action(
    'rest_api_init',
    function () {
        register_rest_route(
            'ajaxselect2/v1',
            '/(?P<method>\w+)/',
            array(
                'methods'             => 'GET',
                'callback'            => 'alpus_ajax_select_api',
                'permission_callback' => '__return_true',
            )
        );
    }
);
