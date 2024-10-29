<?php
/**
 * Define functions using in Alpus Core Plugin
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

/*
 * The filtered term product counts
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_filtered_term_product_counts' ) ) {
    function alpus_filtered_term_product_counts( $term_ids, $taxonomy = false, $query_type = false ) {
        global $wpdb;

        if ( ! class_exists( 'WC_Query' ) ) {
            return false;
        }

        $tax_query  = WC_Query::get_main_tax_query();
        $meta_query = WC_Query::get_main_meta_query();

        if ( 'or' === $query_type ) {
            foreach ( $tax_query as $key => $query ) {
                if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
                    unset( $tax_query[ $key ] );
                }
            }
        }

        if ( 'product_brand' === $taxonomy ) {
            foreach ( $tax_query as $key => $query ) {
                if ( is_array( $query ) ) {
                    if ( 'product_brand' === $query['taxonomy'] ) {
                        unset( $tax_query[ $key ] );

                        if ( preg_match( '/pa_/', $query['taxonomy'] ) ) {
                            unset( $tax_query[ $key ] );
                        }
                    }
                }
            }
        }

        $meta_query     = new WP_Meta_Query( $meta_query );
        $tax_query      = new WP_Tax_Query( $tax_query );
        $meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

        // Generate query
        $query           = array();
        $query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
        $query['from']   = "FROM {$wpdb->posts}";
        $query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

        $query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . '
			AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')
		';

        if ( $search = WC_Query::get_main_search_query_sql() ) {
            $query['where'] .= ' AND ' . $search;
        }

        $query['group_by'] = 'GROUP BY terms.term_id';
        $query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
        $query             = implode( ' ', $query );

        // We have a query - let's see if cached results of this query already exist.
        $query_hash = md5( $query );
        $cache      = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );

        if ( true === $cache ) {
            $cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
        } else {
            $cached_counts = array();
        }

        if ( ! isset( $cached_counts[ $query_hash ] ) ) {
            $results                      = $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
            $cached_counts[ $query_hash ] = $counts;
            set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
        }

        return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
    }
}

/*
 * Get the exact parameters of each predefined layouts.
 *
 * @param int $index    The index of predefined creative layouts
 * @since 1.0
 */
if ( ! function_exists( 'alpus_creative_preset_imgs' ) ) {
    function alpus_creative_preset_imgs() {
        return apply_filters(
            'alpus_creative_preset_imgs',
            array(
                1  => 'assets/images/creative-grid/creative-1.jpg',
                2  => 'assets/images/creative-grid/creative-2.jpg',
                3  => 'assets/images/creative-grid/creative-3.jpg',
                4  => 'assets/images/creative-grid/creative-4.jpg',
                5  => 'assets/images/creative-grid/creative-5.jpg',
                6  => 'assets/images/creative-grid/creative-6.jpg',
                7  => 'assets/images/creative-grid/creative-7.jpg',
                8  => 'assets/images/creative-grid/creative-8.jpg',
                9  => 'assets/images/creative-grid/creative-9.jpg',
                10 => 'assets/images/creative-grid/creative-10.jpg',
                11 => 'assets/images/creative-grid/creative-11.jpg',
                12 => 'assets/images/creative-grid/creative-12.jpg',
            )
        );
    }
}

/*
 * Get the creative layout.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_creative_layout' ) ) {
    function alpus_creative_layout( $index ) {
        $layout = array();

        if ( 1 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-2',
                    'h'    => '1',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-2',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
            );
        } elseif ( 2 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-2',
                    'h'    => '1',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
            );
        } elseif ( 3 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-2',
                    'h'    => '1',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1',
                    'w-l'  => '1-2',
                    'w-s'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'w-s'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'w-s'  => '1',
                    'size' => 'medium',
                ),
            );
        } elseif ( 4 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-2',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
            );
        } elseif ( 5 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-2',
                    'h'    => '1',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-2',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
            );
        } elseif ( 6 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-2',
                    'h'    => '1',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-2',
                    'w-s'  => '1',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-2',
                    'w-s'  => '1',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
            );
        } elseif ( 7 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '2-3',
                    'h'    => '1',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-3',
                    'h'    => '1-3',
                    'w-s'  => '1',
                    'w-l'  => '1-3',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-3',
                    'h'    => '1-3',
                    'w-s'  => '1',
                    'w-l'  => '1-3',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-3',
                    'h'    => '1-3',
                    'w-s'  => '1',
                    'w-l'  => '1-3',
                    'size' => 'medium',
                ),
            );
        } elseif ( 8 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-2',
                    'h'    => '2-3',
                    'w-s'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-3',
                    'w-s'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '2-3',
                    'w-s'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-3',
                    'w-s'  => '1',
                    'size' => 'medium',
                ),
            );
        } elseif ( 9 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '2-3',
                    'h'    => '2-3',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-3',
                    'h'    => '2-3',
                    'w-l'  => '1-2',
                    'w-s'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-3',
                    'w-l'  => '1-2',
                    'w-s'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-3',
                    'w-l'  => '1-2',
                    'w-s'  => '1',
                    'size' => 'medium',
                ),
            );
        } elseif ( 10 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-2',
                    'h'    => '2-3',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-3',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-3',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-3',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-3',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-2',
                    'h'    => '1-3',
                    'w-l'  => '1',
                    'size' => 'large',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-3',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-3',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
            );
        } elseif ( 11 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-s'  => '1',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '5-12',
                    'h'    => '1-2',
                    'w-s'  => '1',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-3',
                    'h'    => '1',
                    'w-s'  => '1',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '5-12',
                    'h'    => '1-2',
                    'w-s'  => '1',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '1-4',
                    'h'    => '1-2',
                    'w-s'  => '1',
                    'w-l'  => '1-2',
                    'size' => 'medium',
                ),
            );
        } elseif ( 12 == (int) $index ) {
            $layout = array(
                array(
                    'w'    => '7-12',
                    'h'    => '2-3',
                    'w-l'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '5-24',
                    'h'    => '1-2',
                    'w-l'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '5-24',
                    'h'    => '1-2',
                    'w-l'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '5-12',
                    'h'    => '2-3',
                    'w-l'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '9-24',
                    'h'    => '1-2',
                    'w-l'  => '1',
                    'size' => 'medium',
                ),
                array(
                    'w'    => '5-24',
                    'h'    => '1-2',
                    'w-l'  => '1',
                    'size' => 'medium',
                ),
            );
        }

        /*
         * Filters creative layout.
         *
         * @since 1.0
         */
        return apply_filters( 'alpus_creative_layout_filter', $layout );
    }
}

/*
 * The creative layout style.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_creative_layout_style' ) ) {
    function alpus_creative_layout_style( $wrapper, $layout, $height = 600, $ratio = 75 ) {
        $hs    = array( 'h-1', 'h-1-2', 'h-1-3', 'h-2-3', 'h-1-4', 'h-3-4' );
        $deno  = array();
        $numer = array();
        $ws    = array(
            'w'   => array(),
            'w-w' => array(),
            'w-g' => array(),
            'w-x' => array(),
            'w-l' => array(),
            'w-m' => array(),
            'w-s' => array(),
        );
        $hs    = array(
            'h'   => array(),
            'h-w' => array(),
            'h-g' => array(),
            'h-x' => array(),
            'h-l' => array(),
            'h-m' => array(),
            'h-s' => array(),
        );

        $breakpoints = alpus_get_breakpoints();
        ob_start();
        echo '<style>';

        foreach ( $layout as $grid_item ) {
            foreach ( $grid_item as $key => $value ) {
                if ( 'size' == $key ) {
                    continue;
                }
                $num = explode( '-', $value );

                if ( isset( $num[1] ) && ! in_array( $num[1], $deno ) ) {
                    $deno[] = $num[1];
                }

                if ( ! in_array( $num[0], $numer ) ) {
                    $numer[] = $num[0];
                }

                if ( ( 'w' == $key || 'w-w' == $key || 'w-g' == $key || 'w-x' == $key || 'w-l' == $key || 'w-m' == $key || 'w-s' == $key ) && ! in_array( $value, $ws[ $key ] ) ) {
                    $ws[ $key ][] = $value;
                }

                if ( ( 'h' == $key || 'h-w' == $key || 'h-g' == $key || 'h-x' == $key || 'h-l' == $key || 'h-m' == $key || 'h-s' == $key ) && ! in_array( $value, $hs[ $key ] ) ) {
                    $hs[ $key ][] = $value;
                }
            }
        }

        foreach ( $ws as $key => $value ) {
            if ( empty( $value ) ) {
                continue;
            }

            if ( 'w-l' == $key ) {
                echo '@media (max-width: ' . ( $breakpoints['lg'] - 1 ) . 'px) {';
            } elseif ( 'w-m' == $key ) {
                echo '@media (max-width: ' . ( $breakpoints['md'] - 1 ) . 'px) {';
            } elseif ( 'w-s' == $key ) {
                echo '@media (max-width: ' . ( $breakpoints['sm'] - 1 ) . 'px) {';
            } elseif ( 'w-x' == $key ) { // xl
                echo '@media (max-width: ' . ( $breakpoints['xl'] - 1 ) . 'px) {';
            } elseif ( 'w-g' == $key ) { // xlg
                echo '@media (max-width: ' . ( $breakpoints['xlg'] - 1 ) . 'px) {';
            } elseif ( 'w-w' == $key ) { // xxl or widescreen
                echo '@media (max-width: ' . ( $breakpoints['xxl'] - 1 ) . 'px) {';
            }

            foreach ( $value as $item ) {
                $opts  = explode( '-', $item );
                $width = ( ! isset( $opts[1] ) ? 100 : round( 100 * $opts[0] / $opts[1], 4 ) );
                echo esc_attr( $wrapper ) . ' .grid-item.' . $key . '-' . $item . '{flex:0 0 ' . $width . '%;width:' . $width . '%}';
            }

            if ( 'w-w' == $key || 'w-g' == $key || 'w-x' == $key || 'w-l' == $key || 'w-m' == $key || 'w-s' == $key ) {
                echo '}';
            }
        }

        foreach ( $hs as $key => $value ) {
            if ( empty( $value ) ) {
                continue;
            }

            foreach ( $value as $item ) {
                $opts = explode( '-', $item );

                if ( isset( $opts[1] ) ) {
                    $h = $height * $opts[0] / $opts[1];
                } else {
                    $h = $height;
                }

                if ( 'h' == $key ) {
                    echo esc_attr( $wrapper ) . ' .h-' . $item . '{height:' . round( $h, 2 ) . 'px}';
                    echo '@media (max-width: ' . ( $breakpoints['md'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
                    echo '}';
                } elseif ( 'h-w' == $key ) { // xxl
                    echo '@media (max-width: ' . ( $breakpoints['xxl'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-w-' . $item . '{height:' . round( $h, 2 ) . 'px}';
                    echo '}';
                    echo '@media (max-width: ' . ( $breakpoints['xlg'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-w-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
                    echo '}';
                } elseif ( 'h-g' == $key ) { // xlg
                    echo '@media (max-width: ' . ( $breakpoints['xlg'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-g-' . $item . '{height:' . round( $h, 2 ) . 'px}';
                    echo '}';
                    echo '@media (max-width: ' . ( $breakpoints['xl'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-g-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
                    echo '}';
                } elseif ( 'h-x' == $key ) { // xl
                    echo '@media (max-width: ' . ( $breakpoints['xl'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-x-' . $item . '{height:' . round( $h, 2 ) . 'px}';
                    echo '}';
                    echo '@media (max-width: ' . ( $breakpoints['lg'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-x-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
                    echo '}';
                } elseif ( 'h-l' == $key ) {
                    echo '@media (max-width: ' . ( $breakpoints['lg'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-l-' . $item . '{height:' . round( $h, 2 ) . 'px}';
                    echo '}';
                    echo '@media (max-width: ' . ( $breakpoints['md'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-l-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
                    echo '}';
                } elseif ( 'h-m' == $key ) {
                    echo '@media (max-width: ' . ( $breakpoints['md'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-m-' . $item . '{height:' . round( $h, 2 ) . 'px}';
                    echo '}';
                    echo '@media (max-width: ' . ( $breakpoints['sm'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-m-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
                    echo '}';
                } elseif ( 'h-s' == $key ) {
                    echo '@media (max-width: ' . ( $breakpoints['sm'] - 1 ) . 'px) {';
                    echo esc_attr( $wrapper ) . ' .h-s-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
                    echo '}';
                }
            }
        }
        $lcm = 1;

        foreach ( $deno as $value ) {
            $lcm = $lcm * $value / alpus_get_gcd( $lcm, $value );
        }
        $gcd = $numer[0];

        foreach ( $numer as $value ) {
            $gcd = alpus_get_gcd( $gcd, $value );
        }
        $sizer          = floor( 100 * $gcd / $lcm * 10000 ) / 10000;
        $space_selector = ' .grid>.grid-space';

        if ( false !== strpos( $wrapper, 'wpb_' ) ) {
            $space_selector = '>.grid-space';
        }
        echo esc_attr( $wrapper ) . $space_selector . '{flex: 0 0 ' . ( $sizer < 0.01 ? 100 : $sizer ) . '%;width:' . ( $sizer < 0.01 ? 100 : $sizer ) . '%}';
        echo '</style>';
        alpus_filter_inline_css( ob_get_clean() );
    }
}

/*
 * Get creative image sizes.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_get_creative_image_sizes' ) ) {
    function alpus_get_creative_image_sizes( $mode, $idx ) {
        if ( 1 == $mode && 0 == $idx % 7 ) {
            return 'large';
        }

        if ( 2 == $mode && 1 == $idx % 5 ) {
            return 'large';
        }

        if ( 3 == $mode && 0 == $idx % 5 ) {
            return 'large';
        }

        if ( 4 == $mode && 2 == $idx % 5 ) {
            return 'large';
        }

        if ( 5 == $mode && ( 0 == $idx % 4 || 1 == $idx % 4 ) ) {
            return 'large';
        }

        if ( 6 == $mode && ( 0 == $idx % 4 || 2 == $idx % 4 ) ) {
            return 'large';
        }

        if ( 7 == $mode && ( 0 == $idx % 4 || 1 == $idx % 4 ) ) {
            return 'large';
        }

        if ( 8 == $mode && ( 0 == $idx % 4 || 1 == $idx % 4 ) ) {
            return 'large';
        }

        if ( 9 == $mode && 0 == $idx % 10 ) {
            return 'large';
        }

        return '';
    }
}

/**
 * Get gcd of two numbers.
 *
 * @since 1.0
 */
function alpus_get_gcd( $a, $b ) {
    while ( $b ) {
        $r = $a % $b;
        $a = $b;
        $b = $r;
    }

    return $a;
}

/*
 * Get grid space classes.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_get_grid_space_class' ) ) {
    function alpus_get_grid_space_class( $settings ) {
        if ( empty( $settings['col_sp'] ) ) {
            return '';
        } else {
            return ' gutter-' . $settings['col_sp'];
        }
    }
}

/*
 * Check Units
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_check_units' ) ) {
    function alpus_check_units( $value ) {
        if ( ! preg_match( '/((^\d+(.\d+){0,1})|((-){0,1}.\d+))(px|%|em|rem|pt){0,1}$/', $value ) ) {
            if ( 'auto' == $value || 'inherit' == $value || 'initial' == $value || 'unset' == $value ) {
                return $value;
            }

            return false;
        } elseif ( is_numeric( $value ) ) {
            $value .= 'px';
        }

        return $value;
    }
}

/*
 * Print Alpus Block
 *
 * @param string $block_name The block name to print.
 * @param bool   $is_menu    Determines whether block is menu or not.
 * @since 1.0
 */
if ( ! function_exists( 'alpus_print_template' ) ) {
    function alpus_print_template( $block_name, $is_menu = false ) {
        if ( $block_name ) {
            $atts = array(
                'name'    => $block_name,
                'is_menu' => $is_menu,
            );
            require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/block/render-block.php' );
        }
    }
}

/*
 * Is elementor page builder preview?
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_is_elementor_preview' ) ) {
    function alpus_is_elementor_preview() {
        return defined( 'ELEMENTOR_VERSION' ) && (
            ( isset( $_REQUEST['action'] ) && ( 'elementor' == sanitize_text_field( $_REQUEST['action'] ) || 'elementor_ajax' == sanitize_text_field( $_REQUEST['action'] ) ) ) ||
            isset( $_REQUEST['elementor-preview'] )
        );
    }
}

/*
 * Is wpbakery page builder preview?
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_is_wpb_preview' ) ) {
    function alpus_is_wpb_preview() {
        if ( defined( 'WPB_VC_VERSION' ) ) {
            if ( alpus_is_wpb_backend() || vc_is_inline() ) {
                return true;
            }
        }

        return false;
    }
}

/*
 * Is page builder preview?
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_is_preview' ) ) {
    function alpus_is_preview() {
        return alpus_is_elementor_preview() || alpus_is_wpb_preview();
    }
}

/*
 * Is wpb backend editor ?
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_is_wpb_backend' ) ) {
    function alpus_is_wpb_backend() {
        if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] ) && defined( 'WPB_VC_VERSION' ) ) {
            return true;
        }

        return false;
    }
}

/*
 * Returns true when viewing the compare page.
 *
 * @return bool
 * @since 1.2.0
 */

if ( ! function_exists( 'alpus_is_compare' ) ) {
    function alpus_is_compare() {
        $page_id = wc_get_page_id( 'compare' );

        return ( $page_id && is_page( $page_id ) ) && class_exists( 'WooCommerce' ) && class_exists( 'Alpus_Product_Compare' );
    }
}

/*
 * Remove all admin notices.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_remove_all_admin_notices' ) ) {
    function alpus_remove_all_admin_notices() {
        add_action(
            'network_admin_notices',
            function () {
                remove_all_actions( 'network_admin_notices' );
            },
            1
        );
        add_action(
            'user_admin_notices',
            function () {
                remove_all_actions( 'user_admin_notices' );
            },
            1
        );
        add_action(
            'admin_notices',
            function () {
                remove_all_actions( 'admin_notices' );
            },
            1
        );
        add_action(
            'all_admin_notices',
            function () {
                remove_all_actions( 'all_admin_notices' );
            },
            1
        );
    }
}

if ( ! function_exists( 'alpus_get_grid_space' ) ) {
    /**
     * Get columns' gutter size value from size string
     *
     * @since 1.0
     *
     * @param string $col_sp Columns gutter size string
     *
     * @return int Gutter size value
     */
    function alpus_get_grid_space( $col_sp ) {
        if ( 'no' == $col_sp ) {
            return 0;
        } elseif ( 'sm' == $col_sp ) {
            return 10;
        } elseif ( 'lg' == $col_sp ) {
            return 30;
        } elseif ( 'xs' == $col_sp ) {
            return 2;
        } else {
            return 20;
        }
    }
}

/*
 * Get image sizes.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_get_image_sizes' ) ) {
    function alpus_get_image_sizes() {
        global $_wp_additional_image_sizes;

        $sizes = array(
            esc_html__( 'Default', 'alpus-core' ) => '',
            esc_html__( 'Full', 'alpus-core' )    => 'full',
        );

        foreach ( get_intermediate_image_sizes() as $_size ) {
            if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
                $sizes[ $_size . ' ( ' . get_option( "{$_size}_size_w" ) . 'x' . get_option( "{$_size}_size_h" ) . ( get_option( "{$_size}_crop" ) ? '' : ', false' ) . ' )' ] = $_size;
            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                $sizes[ $_size . ' ( ' . $_wp_additional_image_sizes[ $_size ]['width'] . 'x' . $_wp_additional_image_sizes[ $_size ]['height'] . ( $_wp_additional_image_sizes[ $_size ]['crop'] ? '' : ', false' ) . ' )' ] = $_size;
            }
        }

        return $sizes;
    }
}

/*******************************************
 ********* Render Core Functions ***********
 *******************************************/

/*
 * Get button widget class
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_widget_button_get_class' ) ) {
    function alpus_widget_button_get_class( $settings, $prefix = '' ) {
        $class = array();

        if ( ! empty( $prefix ) ) {
            $class[] = 'btn-' . $prefix;
        }

        if ( isset( $settings[ $prefix . 'button_type' ] ) && $settings[ $prefix . 'button_type' ] ) {
            $class[] = $settings[ $prefix . 'button_type' ];
        }

        if ( isset( $settings[ $prefix . 'link_hover_type' ] ) && $settings[ $prefix . 'link_hover_type' ] ) {
            $class[] = $settings[ $prefix . 'link_hover_type' ];

            if ( isset( $settings[ $prefix . 'show_underline' ] ) && 'yes' == $settings[ $prefix . 'show_underline' ] ) {
                $class[] = 'btn-underline-show';
            }
        }

        if ( isset( $settings[ $prefix . 'button_size' ] ) && $settings[ $prefix . 'button_size' ] ) {
            $class[] = $settings[ $prefix . 'button_size' ];
        }

        if ( ! empty( $settings[ $prefix . 'text_hover_effect' ] ) ) {
            $class[] = 'btn-text-hover-effect ' . $settings[ $prefix . 'text_hover_effect' ];
        }

        if ( isset( $settings[ $prefix . 'shadow' ] ) && $settings[ $prefix . 'shadow' ] ) {
            $class[] = $settings[ $prefix . 'shadow' ];
        }

        if ( isset( $settings[ $prefix . 'button_border' ] ) && $settings[ $prefix . 'button_border' ] ) {
            $class[] = $settings[ $prefix . 'button_border' ];
        }

        if ( ( ! isset( $settings[ $prefix . 'button_type' ] ) || 'btn-gradient' != $settings[ $prefix . 'button_type' ] ) && isset( $settings[ $prefix . 'button_skin' ] ) && $settings[ $prefix . 'button_skin' ] ) {
            $class[] = $settings[ $prefix . 'button_skin' ];
        }

        if ( isset( $settings[ $prefix . 'button_type' ] ) && 'btn-gradient' == $settings[ $prefix . 'button_type' ] && isset( $settings[ $prefix . 'button_gradient_skin' ] ) && $settings[ $prefix . 'button_gradient_skin' ] ) {
            $class[] = $settings[ $prefix . 'button_gradient_skin' ];
        }

        if ( ! empty( $settings[ $prefix . 'btn_class' ] ) ) {
            $class[] = $settings[ $prefix . 'btn_class' ];
        }

        if ( isset( $settings[ $prefix . 'icon_hover_effect_infinite' ] ) && 'yes' == $settings[ $prefix . 'icon_hover_effect_infinite' ] ) {
            $class[] = 'btn-infinite';
        }

        if ( ! empty( $settings[ $prefix . 'icon' ] ) ) {
            if ( empty( $settings['self'] ) || is_array( $settings[ $prefix . 'icon' ] ) && $settings[ $prefix . 'icon' ]['value'] ) {
                if ( 'before' === $settings[ $prefix . 'icon_pos' ] ) {
                    $class[] = 'btn-icon-left';

                    if ( ! empty( $settings[ $prefix . 'icon_hover_effect' ] ) && 'btn-reveal' == $settings[ $prefix . 'icon_hover_effect' ] ) {
                        $class[] = 'btn-reveal-left';
                    }
                } else {
                    $class[] = 'btn-icon-right';

                    if ( ! empty( $settings[ $prefix . 'icon_hover_effect' ] ) && 'btn-reveal' == $settings[ $prefix . 'icon_hover_effect' ] ) {
                        $class[] = 'btn-reveal-right';
                    }
                }

                if ( ! empty( $settings[ $prefix . 'icon_hover_effect' ] ) && 'btn-reveal' != $settings[ $prefix . 'icon_hover_effect' ] ) {
                    $class[] = $settings[ $prefix . 'icon_hover_effect' ];
                }
            }
        }

        return $class;
    }
}

/*
 * Get button widget label
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_widget_button_get_label' ) ) {
    function alpus_widget_button_get_label( $settings, $self, $label, $inline_key = '', $prefix = '' ) {
        $label = sprintf( '<span %1$s%2$s>%3$s</span>', $inline_key ? $self->get_render_attribute_string( $inline_key ) : '', ! empty( $settings[ $prefix . 'text_hover_effect' ] ) ? ' data-text="' . esc_attr( $label ) . '"' : '', esc_html( $label ) );

        if ( isset( $settings[ $prefix . 'icon' ]['library'] ) && 'svg' == $settings[ $prefix . 'icon' ]['library'] ) {
            ob_start();
            \ELEMENTOR\Icons_Manager::render_icon(
                array(
                    'library' => 'svg',
                    'value'   => array( 'id' => absint( isset( $settings[ $prefix . 'icon' ]['value']['id'] ) ? $settings[ $prefix . 'icon' ]['value']['id'] : 0 ) ),
                ),
                array( 'aria-hidden' => 'true' )
            );
            $svg = ob_get_clean();
        }

        if ( isset( $settings[ $prefix . 'icon' ] ) && is_array( $settings[ $prefix . 'icon' ] ) && $settings[ $prefix . 'icon' ]['value'] ) {
            if ( 'before' == $settings[ $prefix . 'icon_pos' ] ) {
                if ( isset( $svg ) ) {
                    $label = $svg . $label;
                } else {
                    $label = '<i class="' . $settings[ $prefix . 'icon' ]['value'] . '"></i>' . $label;
                }
            } else {
                if ( isset( $svg ) ) {
                    $label .= $svg;
                } else {
                    $label .= '<i class="' . $settings[ $prefix . 'icon' ]['value'] . '"></i>';
                }
            }
        }

        return $label;
    }
}

/*
 * The elementor loadmore render html.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_loadmore_render_html' ) ) {
    function alpus_elementor_loadmore_render_html( $query, $atts ) {
        if ( $query->max_num_pages > 1 ) {
            if ( 'button' == $atts['loadmore_type'] ) {
                echo '<button class="btn btn-load btn-primary">';
                echo empty( $atts['loadmore_label'] ) ? esc_html__( 'Load More', 'alpus-core' ) : esc_html( $atts['loadmore_label'] );
                echo '</button>';
            } elseif ( 'page' == $atts['loadmore_type'] || '' == $atts['loadmore_type'] ) {
                echo alpus_get_pagination( $query, 'pagination-load' );
            }
        }
    }
}

/*
 * Get the grid col cnt for elementor page builder.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_elementor_grid_col_cnt' ) ) {
    function alpus_elementor_grid_col_cnt( $settings ) {
        $col_cnt = array(
            'xxl' => isset( $settings['col_cnt_widescreen'] ) ? (int) $settings['col_cnt_widescreen'] : 0,
            'xlg' => isset( $settings['col_cnt'] ) ? (int) $settings['col_cnt'] : 0,
            'xl'  => isset( $settings['col_cnt_laptop'] ) ? (int) $settings['col_cnt_laptop'] : 0,
            'lg'  => isset( $settings['col_cnt_tablet_extra'] ) ? (int) $settings['col_cnt_tablet_extra'] : 0,
            'md'  => isset( $settings['col_cnt_tablet'] ) ? (int) $settings['col_cnt_tablet'] : 0,
            'sm'  => isset( $settings['col_cnt_mobile_extra'] ) ? (int) $settings['col_cnt_mobile_extra'] : 0,
            'min' => isset( $settings['col_cnt_mobile'] ) ? (int) $settings['col_cnt_mobile'] : 0,
        );

        return function_exists( 'alpus_get_responsive_cols' ) ? alpus_get_responsive_cols( $col_cnt ) : $col_cnt;
    }
}

/*
 * Get framework title of elementor panel title.
 *
 * @since 1.2.0
 */
if ( ! function_exists( 'alpus_elementor_panel_heading' ) ) {
    function alpus_elementor_panel_heading( $title ) {
        return $title . '<i class="alpus-elementor-widget-icon alpus-widget-icon-animate" aria-hidden="true"></i>';
    }
}

/*
 * Get post id by name.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_get_post_id_by_name' ) ) {
    function alpus_get_post_id_by_name( $post_type, $name ) {
        global $wpdb;

        return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_name = %s", $post_type, $name ) );
    }
}

/*
 * The Wc product dropdown brands.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_wc_product_dropdown_brands' ) ) {
    function alpus_wc_product_dropdown_brands( $args = array() ) {
        global $wp_query;

        $args = wp_parse_args(
            $args,
            array(
                'pad_counts'         => 1,
                'show_count'         => 1,
                'hierarchical'       => 1,
                'hide_empty'         => 1,
                'show_uncategorized' => 1,
                'orderby'            => 'name',
                'selected'           => isset( $wp_query->query_vars['product_brand'] ) ? $wp_query->query_vars['product_brand'] : '',
                'show_option_none'   => __( 'Select a category', 'alpus-core' ),
                'option_none_value'  => '',
                'value_field'        => 'slug',
                'taxonomy'           => 'product_brand',
                'name'               => 'product_brand',
                'class'              => 'dropdown_product_brand',
            )
        );

        if ( 'order' === $args['orderby'] ) {
            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = 'order'; // phpcs:ignore
        }

        wp_dropdown_categories( $args );
    }
}

/*
 * Get animations.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_get_animations' ) ) {
    function alpus_get_animations( $type = '' ) {
        $animations_in = array(
            'none'               => esc_html__( 'Default Animation', 'alpus-core' ),
            'bounce'             => esc_html__( 'Bounce', 'alpus-core' ),
            'flash'              => esc_html__( 'Flash', 'alpus-core' ),
            'pulse'              => esc_html__( 'Pulse', 'alpus-core' ),
            'rubberBand'         => esc_html__( 'RubberBand', 'alpus-core' ),
            'shake'              => esc_html__( 'Shake', 'alpus-core' ),
            'headShake'          => esc_html__( 'HeadShake', 'alpus-core' ),
            'swing'              => esc_html__( 'Swing', 'alpus-core' ),
            'tada'               => esc_html__( 'Tada', 'alpus-core' ),
            'wobble'             => esc_html__( 'Wobble', 'alpus-core' ),
            'jello'              => esc_html__( 'Jello', 'alpus-core' ),
            'heartBeat'          => esc_html__( 'HearBeat', 'alpus-core' ),
            'blurIn'             => esc_html__( 'BlurIn', 'alpus-core' ),
            'bounceIn'           => esc_html__( 'BounceIn', 'alpus-core' ),
            'bounceInUp'         => esc_html__( 'BounceInUp', 'alpus-core' ),
            'bounceInDown'       => esc_html__( 'BounceInDown', 'alpus-core' ),
            'bounceInLeft'       => esc_html__( 'BounceInLeft', 'alpus-core' ),
            'bounceInRight'      => esc_html__( 'BounceInRight', 'alpus-core' ),
            'fadeIn'             => esc_html__( 'FadeIn', 'alpus-core' ),
            'fadeInUp'           => esc_html__( 'FadeInUp', 'alpus-core' ),
            'fadeInUpBig'        => esc_html__( 'FadeInUpBig', 'alpus-core' ),
            'fadeInUpShorter'    => esc_html__( 'FadeInUpShort', 'alpus-core' ),
            'fadeInDown'         => esc_html__( 'FadeInDown', 'alpus-core' ),
            'fadeInDownBig'      => esc_html__( 'FadeInDownBig', 'alpus-core' ),
            'fadeInDownShorter'  => esc_html__( 'FadeInDownShort', 'alpus-core' ),
            'fadeInLeft'         => esc_html__( 'FadeInLeft', 'alpus-core' ),
            'fadeInLeftBig'      => esc_html__( 'FadeInLeftBig', 'alpus-core' ),
            'fadeInLeftShorter'  => esc_html__( 'FadeInLeftShort', 'alpus-core' ),
            'fadeInRight'        => esc_html__( 'FadeInRight', 'alpus-core' ),
            'fadeInRightBig'     => esc_html__( 'FadeInRightBig', 'alpus-core' ),
            'fadeInRightShorter' => esc_html__( 'FadeInRightShort', 'alpus-core' ),
            'flip'               => esc_html__( 'Flip', 'alpus-core' ),
            'flipInX'            => esc_html__( 'FlipInX', 'alpus-core' ),
            'flipInY'            => esc_html__( 'FlipInY', 'alpus-core' ),
            'lightSpeedIn'       => esc_html__( 'LightSpeedIn', 'alpus-core' ),
            'rotateIn'           => esc_html__( 'RotateIn', 'alpus-core' ),
            'rotateInUpLeft'     => esc_html__( 'RotateInUpLeft', 'alpus-core' ),
            'rotateInUpRight'    => esc_html__( 'RotateInUpRight', 'alpus-core' ),
            'rotateInDownLeft'   => esc_html__( 'RotateInDownLeft', 'alpus-core' ),
            'rotateInDownRight'  => esc_html__( 'RotateInDownRight', 'alpus-core' ),
            'hinge'              => esc_html__( 'Hinge', 'alpus-core' ),
            'jackInTheBox'       => esc_html__( 'JackInTheBox', 'alpus-core' ),
            'rollIn'             => esc_html__( 'RollIn', 'alpus-core' ),
            'zoomIn'             => esc_html__( 'ZoomIn', 'alpus-core' ),
            'zoomInUp'           => esc_html__( 'ZoomInUp', 'alpus-core' ),
            'zoomInDown'         => esc_html__( 'ZoomInDown', 'alpus-core' ),
            'zoomInLeft'         => esc_html__( 'ZoomInLeft', 'alpus-core' ),
            'zoomInRight'        => esc_html__( 'ZoomInRight', 'alpus-core' ),
            'slideInUp'          => esc_html__( 'SlideInUp', 'alpus-core' ),
            'slideInDown'        => esc_html__( 'SlideInDown', 'alpus-core' ),
            'slideInLeft'        => esc_html__( 'SlideInLeft', 'alpus-core' ),
            'slideInRight'       => esc_html__( 'SlideInRight', 'alpus-core' ),
            'blurIn'             => esc_html__( 'BlurIn', 'alpus-core' ),
        );

        $animations_out = array(
            'default'            => esc_html__( 'Default Animation', 'alpus-core' ),
            'blurOut'            => esc_html__( 'BlurOut', 'alpus-core' ),
            'bounceOut'          => esc_html__( 'BounceOut', 'alpus-core' ),
            'bounceOutUp'        => esc_html__( 'BounceOutUp', 'alpus-core' ),
            'bounceOutDown'      => esc_html__( 'BounceOutDown', 'alpus-core' ),
            'bounceOutLeft'      => esc_html__( 'BounceOutLeft', 'alpus-core' ),
            'bounceOutRight'     => esc_html__( 'BounceOutRight', 'alpus-core' ),
            'fadeOut'            => esc_html__( 'FadeOut', 'alpus-core' ),
            'fadeOutUp'          => esc_html__( 'FadeOutUp', 'alpus-core' ),
            'fadeOutUpBig'       => esc_html__( 'FadeOutUpBig', 'alpus-core' ),
            'fadeOutDown'        => esc_html__( 'FadeOutDown', 'alpus-core' ),
            'fadeOutDownBig'     => esc_html__( 'FadeOutDownBig', 'alpus-core' ),
            'fadeOutLeft'        => esc_html__( 'FadeOutLeft', 'alpus-core' ),
            'fadeOutLeftBig'     => esc_html__( 'FadeOutLeftBig', 'alpus-core' ),
            'fadeOutRight'       => esc_html__( 'FadeOutRight', 'alpus-core' ),
            'fadeOutRightBig'    => esc_html__( 'FadeOutRightBig', 'alpus-core' ),
            'flipOutX'           => esc_html__( 'FlipOutX', 'alpus-core' ),
            'flipOutY'           => esc_html__( 'FlipOutY', 'alpus-core' ),
            'lightSpeedOut'      => esc_html__( 'LightSpeedOut', 'alpus-core' ),
            'rotateOutUpLeft'    => esc_html__( 'RotateOutUpLeft', 'alpus-core' ),
            'rotateOutRight'     => esc_html__( 'RotateOutUpRight', 'alpus-core' ),
            'rotateOutDownLeft'  => esc_html__( 'RotateOutDownLeft', 'alpus-core' ),
            'rotateOutDownRight' => esc_html__( 'RotateOutDownRight', 'alpus-core' ),
            'rollOut'            => esc_html__( 'RollOut', 'alpus-core' ),
            'zoomOut'            => esc_html__( 'ZoomOut', 'alpus-core' ),
            'zoomOutUp'          => esc_html__( 'ZoomOutUp', 'alpus-core' ),
            'zoomOutDown'        => esc_html__( 'ZoomOutDown', 'alpus-core' ),
            'zoomOutLeft'        => esc_html__( 'ZoomOutLeft', 'alpus-core' ),
            'zoomOutRight'       => esc_html__( 'ZoomOutRight', 'alpus-core' ),
            'slideOutUp'         => esc_html__( 'SlideOutUp', 'alpus-core' ),
            'slideOutDown'       => esc_html__( 'SlideOutDown', 'alpus-core' ),
            'slideOutLeft'       => esc_html__( 'SlideOutLeft', 'alpus-core' ),
            'slideOutRight'      => esc_html__( 'SlideOutRight', 'alpus-core' ),
        );

        $animations_appear = apply_filters(
            'alpus_animation_appear',
            array(
                ALPUS_DISPLAY_NAME . ' Fading' => array(
                    'fadeInDownShorter'  => esc_html__( 'Fade In Down Shorter', 'alpus-core' ),
                    'fadeInLeftShorter'  => esc_html__( 'Fade In Left Shorter', 'alpus-core' ),
                    'fadeInRightShorter' => esc_html__( 'Fade In Right Shorter', 'alpus-core' ),
                    'fadeInUpShorter'    => esc_html__( 'Fade In Up Shorter', 'alpus-core' ),
                ),
                'Blur'                         => array(
                    'blurIn' => esc_html__( 'BlurIn', 'alpus-core' ),
                ),
            )
        );

        if ( 'appear' == $type ) {
            return $animations_appear;
        } elseif ( 'in' == $type ) {
            return $animations_in;
        } elseif ( 'out' == $type ) {
            return $animations_out;
        }

        return array(
            'sliderIn'  => $animations_in,
            'sliderOut' => $animations_out,
            'appear'    => $animations_appear,
        );
    }
}

/*
 * Remove filter callbacks
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_clean_filter' ) ) {
    function alpus_clean_filter( $hook, $callback, $priority = 10 ) {
        remove_filter( $hook, $callback, $priority );
    }
}

/*
 * alpus_get_elementor_addon_options
 *
 * gets floating options
 *
 * @return array options of floating effect
 *
 * @since 1.0
 */

if ( ! function_exists( 'alpus_get_elementor_addon_options' ) ) {
    function alpus_get_elementor_addon_options( $settings, $options = array() ) {
        /*
         * Filters elementor addon options such as duplex and ribbon.
         *
         * @since 1.0
         */
        return apply_filters( 'alpus_elementor_addon_options', $options, $settings );
    }
}

/*
 * Convert RGBA 8 hex values color to RGBA function color
 *
 * @param  string $color
 * @return array  options of floating effect
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_rgba_hex_2_rgba_func' ) ) {
    function alpus_rgba_hex_2_rgba_func( $color ) {
        $output = $color;

        if ( empty( $color ) ) {
            return $output;
        }

        if ( '#' == $color[0] ) {
            $color = substr( $color, 1 );
        }

        if ( strlen( $color ) == 8 ) { //ARGB
            $output  = 'rgba(0,0,0,1)';
            $opacity = round( hexdec( substr( $color, 6, 2 ) ) / 255, 2 );
            $hex     = array( substr( $color, 0, 2 ), substr( $color, 2, 2 ), substr( $color, 4, 2 ) );
            $rgb     = array_map( 'hexdec', $hex );
            $output  = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
        }

        return $output;
    }
}

/*
 *
 * Add alpus add script tag.
 *
 * @since 1.0
 */
add_filter( 'alpus_script_tag', 'alpus_add_script_tag' );

if ( ! function_exists( 'alpus_add_script_tag' ) ) {
    function alpus_add_script_tag( $sc = true ) {
        if ( $sc ) {
            return 'type="text/template"';
        }

        return 'type="application/ld+json"';
    }
}
