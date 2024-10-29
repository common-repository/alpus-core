<?php
/**
 * General function
 *
 * You can override hooks function and general function.
 * And changes functions that are attached hooks.
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */

/*
 * Include template part
 *
 * @since 1.0
 * @param string $slug
 * @param string $name
 * @param array $args
 * @return string $template
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
function alpus_get_review_pagination() {
    global $wp_query;
    $page = $wp_query->get( 'cpage' );

    $args = apply_filters(
        'woocommerce_comment_pagination_args',
        array(
            'echo'      => false,
            'prev_text' => '<i class="' . ALPUS_ICON_PREFIX . '-icon-long-arrow-left"></i>',
            'next_text' => '<i class="' . ALPUS_ICON_PREFIX . '-icon-long-arrow-right"></i>',
        )
    );

    $pagination = paginate_comments_links( $args );

    if ( $pagination ) {
        if ( 1 == $page ) {
            $pagination = sprintf(
                '<span class="prev page-numbers disabled">%s</span>',
                $args['prev_text']
            ) . $pagination;
        } elseif ( get_comment_pages_count() == $page ) {
            $pagination .= sprintf(
                '<span class="next page-numbers disabled">%s</span>',
                $args['next_text']
            );
        }
    }

    return $pagination;
}

if ( ! function_exists( 'wc_dropdown_variation_attribute_options' ) ) {
    /**
     * Output a list of variation attributes for use in the cart forms.
     *
     * @param array $args arguments
     *
     * @since 2.4.0
     */
    function wc_dropdown_variation_attribute_options( $args = array() ) {
        $args = wp_parse_args(
            apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ),
            array(
                'options'          => false,
                'attribute'        => false,
                'product'          => false,
                'selected'         => false,
                'name'             => '',
                'id'               => '',
                'class'            => '',
                'show_option_none' => __( 'Choose an option', 'alpus-core' ),
                'type'             => '',
            )
        );

        // Get selected value.
        if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
            $selected_key = 'attribute_' . sanitize_title( $args['attribute'] );
            // phpcs:disable WordPress.Security.NonceVerification.Recommended
            $args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] );
            // phpcs:enable WordPress.Security.NonceVerification.Recommended
        }

        $options               = $args['options'];
        $product               = $args['product'];
        $attribute             = $args['attribute'];
        $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
        $id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
        $class                 = $args['class'];
        $show_option_none      = (bool) $args['show_option_none'];
        $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'alpus-core' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.
        $type                  = $args['type'];

        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }

        $html = '';

        if ( 'select' === $type ) {
            $html .= '<div class="select-box">';
        }

        $html .= '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
        $html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

        if ( ! empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms(
                    $product->get_id(),
                    $attribute,
                    array(
                        'fields' => 'all',
                    )
                );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options, true ) ) {
                        $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) ) . '</option>';
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                    $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) ) . '</option>';
                }
            }
        }

        $html .= '</select>';

        if ( 'select' === $type ) {
            $html .= '</div>';
        }

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args );
    }
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
        } elseif ( 'md' == $col_sp ) {
            return 20;
        } elseif ( 'lg' == $col_sp ) {
            return 30;
        } elseif ( 'xs' == $col_sp ) {
            return 2;
        } else {
            return 30;
        }
    }
}

if ( ! function_exists( 'alpus_get_slider_attrs' ) ) {
    /**
     * Get slider data attribute from settings array
     *
     * @since 1.0
     *
     * @param array  $settings slider settings array from elementor widget
     * @param array  $col_cnt  Columns count
     * @param string $id       Hash string for element
     *
     * @return string slider data attribute
     */
    function alpus_get_slider_attrs( $settings, $col_cnt, $id = '' ) {
        $max_breakpoints = alpus_get_breakpoints();

        if ( defined( 'ELEMENTOR_VERSION' ) ) {
            $kit = get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 );

            if ( $kit ) {
                $site_settings = get_post_meta( get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 ), '_elementor_page_settings', true );
            }
        }

        $extra_options = array();

        if ( ! empty( $settings['slide_effect'] ) ) {
            $extra_options['effect'] = $settings['slide_effect'];
        }

        $extra_options['spaceBetween'] = ! empty( $settings['col_sp'] ) ? alpus_get_grid_space( $settings['col_sp'] ) : ( ! empty( $settings['col_sp_custom']['size'] ) ? $settings['col_sp_custom']['size'] : 20 );

        if ( ! empty( $settings['col_sp'] ) ) {
            $extra_options['spaceBetween'] = alpus_get_grid_space( $settings['col_sp'] );
        } elseif ( ! empty( $settings['col_sp_custom']['size'] ) ) {
            $extra_options['spaceBetween'] = $settings['col_sp_custom']['size'];
        } elseif ( ! empty( $settings['col_sp_custom_laptop']['size'] ) ) {
            $extra_options['spaceBetween'] = $settings['col_sp_custom_laptop']['size'];
        } elseif ( ! empty( $settings['col_sp_custom_tablet_extra']['size'] ) ) {
            $extra_options['spaceBetween'] = $settings['col_sp_custom_tablet_extra']['size'];
        } elseif ( ! empty( $site_settings['gutter_space']['size'] ) ) {
            $extra_options['spaceBetween'] = $site_settings['gutter_space']['size'];
        } elseif ( ! empty( $site_settings['gutter_space_laptop']['size'] ) ) {
            $extra_options['spaceBetween'] = $site_settings['gutter_space_laptop']['size'];
        } elseif ( ! empty( $site_settings['gutter_space_tablet_extra']['size'] ) ) {
            $extra_options['spaceBetween'] = $site_settings['gutter_space_tablet_extra']['size'];
        } else {
            $extra_options['spaceBetween'] = 20;
        }

        if ( isset( $settings['centered'] ) && 'yes' == $settings['centered'] ) { // default is false
            $extra_options['centeredSlides'] = true;
        }

        if ( isset( $settings['loop'] ) && 'yes' == $settings['loop'] ) { // default is false
            $extra_options['loop'] = true;
        }

        // Auto play
        if ( isset( $settings['autoplay'] ) && 'yes' == $settings['autoplay'] ) { // default is false
            if ( isset( $settings['autoplay_timeout'] ) ) { // default is 5000
                $extra_options['autoplay'] = array(
                    'delay'                => (int) $settings['autoplay_timeout'],
                    'disableOnInteraction' => false,
                );
            }
        }

        if ( ! empty( $settings['show_dots'] ) && ! empty( $settings['dots_type'] ) && $id ) {
            if ( 'thumb' == $settings['dots_type'] ) {
                $extra_options['dotsContainer'] = '.slider-thumb-dots-' . $id;
            } else {
                $extra_options['dotsContainer'] = '.slider-custom-html-dots-' . $id;
            }
        }

        if ( ! empty( $settings['show_nav'] ) ) {
            $extra_options['navigation'] = true;
        }

        if ( ! empty( $settings['show_dots'] ) ) {
            $extra_options['pagination'] = true;
        }

        if ( isset( $settings['autoheight'] ) && 'yes' == $settings['autoheight'] ) {
            $extra_options['autoHeight'] = true;
        }

        // Disable Mouse Drag
        if ( isset( $settings['disable_mouse_drag'] ) && 'yes' == $settings['disable_mouse_drag'] ) {
            $extra_options['allowTouchMove'] = false;
        }

        // Effect
        if ( isset( $settings['effect'] ) ) {
            $extra_options['effect'] = $settings['effect'];
        }

        if ( ! empty( $settings['speed'] ) ) {
            $extra_options['speed'] = $settings['speed'];
        }

        $responsive = array();
        $w          = array(
            'min' => 'mobile',
            'sm'  => 'mobile_extra',
            'md'  => 'tablet',
            'lg'  => 'tablet_extra',
            'xl'  => 'laptop',
            'xlg' => '',
            'xxl' => 'widescreen',
        );

        $col_cnt = function_exists( 'alpus_get_responsive_cols' ) ? alpus_get_responsive_cols( $col_cnt ) : $col_cnt;

        $parent_sp_custom = $extra_options['spaceBetween'];
        $parent_sp_global = $extra_options['spaceBetween'];

        foreach ( array_reverse( $w ) as $key => $device ) {
            if ( $device ) {
                $device = '_' . $device;
            }

            if ( ! empty( $col_cnt[ $key ] ) ) {
                $responsive[ $max_breakpoints[ $key ] ] = array(
                    'slidesPerView' => $col_cnt[ $key ],
                );
            }

            if ( empty( $settings['col_sp'] ) ) {
                if ( ! empty( $settings[ 'col_sp_custom' . $device ]['size'] ) ) {
                    if ( ! isset( $responsive[ $max_breakpoints[ $key ] ] ) ) {
                        $responsive[ $max_breakpoints[ $key ] ] = array();
                    }
                    $parent_sp_custom                                       = $settings[ 'col_sp_custom' . $device ]['size'];
                    $responsive[ $max_breakpoints[ $key ] ]['spaceBetween'] = $settings[ 'col_sp_custom' . $device ]['size'];
                } elseif ( $parent_sp_custom != $extra_options['spaceBetween'] ) {
                    if ( ! isset( $responsive[ $max_breakpoints[ $key ] ] ) ) {
                        $responsive[ $max_breakpoints[ $key ] ] = array();
                    }
                    $responsive[ $max_breakpoints[ $key ] ]['spaceBetween'] = $parent_sp_custom;
                } elseif ( ! empty( $site_settings[ 'gutter_space' . $device ]['size'] ) ) {
                    if ( ! isset( $responsive[ $max_breakpoints[ $key ] ] ) ) {
                        $responsive[ $max_breakpoints[ $key ] ] = array();
                    }
                    $parent_sp_global                                       = $site_settings[ 'gutter_space' . $device ]['size'];
                    $responsive[ $max_breakpoints[ $key ] ]['spaceBetween'] = $site_settings[ 'gutter_space' . $device ]['size'];
                } elseif ( $parent_sp_global != $extra_options['spaceBetween'] ) {
                    if ( ! isset( $responsive[ $max_breakpoints[ $key ] ] ) ) {
                        $responsive[ $max_breakpoints[ $key ] ] = array();
                    }
                    $responsive[ $max_breakpoints[ $key ] ]['spaceBetween'] = $parent_sp_global;
                }
            }
        }

        if ( isset( $col_cnt['xlg'] ) ) {
            $extra_options['slidesPerView'] = $col_cnt['xlg'];
        } elseif ( isset( $col_cnt['xl'] ) ) {
            $extra_options['slidesPerView'] = $col_cnt['xl'];
        } elseif ( isset( $col_cnt['lg'] ) ) {
            $extra_options['slidesPerView'] = $col_cnt['lg'];
        }

        if ( ! empty( $settings['dots_type'] ) && $id ) {
            $extra_options['pagination'] = false;

            foreach ( $responsive as $w => $c ) {
                $responsive[ $w ]['pagination'] = false;
            }
        }

        $extra_options['breakpoints'] = $responsive;

        $extra_options['statusClass'] = trim( ( empty( $settings['status_class'] ) ? '' : $settings['status_class'] ) . alpus_get_slider_status_class( $settings ) );

        return $extra_options;
    }
}

/*
 * Echo or Return inline css.
 * This function only uses for composed by style tag.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_filter_inline_css' ) ) {
    function alpus_filter_inline_css( $inline_css, $is_echo = true ) {
        if ( ! class_exists( 'Alpus_Optimize_Stylesheets' ) ) {
            if ( $is_echo ) {
                echo alpus_escaped( $inline_css );

                return;
            } else {
                return $inline_css;
            }
        }

        if ( empty( Alpus_Optimize_Stylesheets::get_instance()->is_merged ) ) { // not merge
            if ( $is_echo ) {
                echo alpus_escaped( $inline_css );
            } else {
                return $inline_css;
            }
        } else {
            if ( 'no' == Alpus_Optimize_Stylesheets::get_instance()->has_merged_css() ) {
                global $alpus_body_merged_css;

                if ( isset( $alpus_body_merged_css ) ) {
                    $inline_css             = str_replace( PHP_EOL, '', $inline_css );
                    $inline_css             = preg_replace( '/<style.*?>/s', '', $inline_css ) ?: $inline_css;
                    $inline_css             = preg_replace( '/<\/style.*?>/s', '', $inline_css ) ?: $inline_css;
                    $alpus_body_merged_css .= $inline_css;
                }
            }

            return '';
        }
    }
}

/*
 * Returns true when viewing the compare page.
 *
 * @return bool
 * @since 1.0
 */

if ( ! function_exists( 'alpus_is_compare' ) ) {
    function alpus_is_compare() {
        $page_id = wc_get_page_id( 'compare' );

        return ( $page_id && is_page( $page_id ) ) && class_exists( 'WooCommerce' ) && class_exists( 'Alpus_Product_Compare' );
    }
}

/*
 * Doing ajax
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpus_doing_ajax' ) ) {
    function alpus_doing_ajax() {
        // WordPress ajax
        if ( wp_doing_ajax() ) {
            return true;
        }

        /*
         * Filters ajax doing.
         *
         * @since 1.0
         */
        return apply_filters( 'alpus_core_filter_doing_ajax', false );
    }
}
