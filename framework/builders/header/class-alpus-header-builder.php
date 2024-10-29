<?php
/**
 * Alpus Builder Header class
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;
define( 'ALPUS_HEADER_BUILDER', ALPUS_BUILDERS . '/header' );

class Alpus_Header_Builder extends Alpus_Base {

    /**
         * Header builder widgets.
         *
         * @var array
         *
         * @since 1.0
         */
    public $widgets = array();

    /**
     * Constructor
     *
     * @since 1.0
     */
    public function __construct() {
        $this->widgets = array(
            'account'           => true,
            'language_switcher' => true,
            'currency_switcher' => true,
            'mmenu_toggle'      => true,
            'v_divider'         => true,
        );

        if ( class_exists( 'WooCommerce' ) ) {
            $this->widgets = array_merge(
                $this->widgets,
                array(
                    'cart'     => true,
                    'wishlist' => true,
                    'compare'  => true,
                )
            );
        }

        $this->widgets = apply_filters( 'alpus_header_widget', $this->widgets );
        array_multisort( array_keys( $this->widgets ), SORT_ASC, $this->widgets );

        // @start feature: fs_pb_elementor
        if ( alpus_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) ) {
            add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
            add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
        }
        // @end feature: fs_pb_elementor
    }

    /**
     * Register elementor category.
     *
     * @since 1.0
     */
    public function register_elementor_category( $self ) {
        global $post, $alpus_layout;

        $register = false;

        if ( is_admin() ) {
            if ( ! alpus_is_elementor_preview() || $post ) {
                $register = true;
            }
        } else {
            if ( ! empty( $alpus_layout['header'] ) && 'hide' != $alpus_layout['header'] ) {
                $register = true;
            }
        }

        if ( $register ) {
            $self->add_category(
                'alpus_header_widget',
                array(
                    'title'  => sprintf( esc_html__( '%1$s %2$s', 'alpus-core' ), ALPUS_DISPLAY_NAME, esc_html__( 'Header', 'alpus-core' ) ),
                    'active' => true,
                )
            );
        }
    }

    /**
     * Register elementor widgets.
     *
     * @since 1.0
     */
    public function register_elementor_widgets( $self ) {
        global $post, $alpus_layout;

        $register = $post;

        if ( ! $register ) {
            if ( is_admin() ) {
                if ( ! alpus_is_elementor_preview() ) {
                    $register = true;
                }
            } elseif ( ! empty( $alpus_layout['header'] ) && 'hide' != $alpus_layout['header'] ) {
                $register = true;
            }
        }

        $compare = false;

        if ( function_exists( 'alpus_get_option' ) && alpus_get_option( 'compare_available' ) ) {
            $compare = true;
        }

        if ( $register ) {
            foreach ( $this->widgets as $widget => $usable ) {
                if ( 'compare' == $widget && ! $compare ) {
                    continue;
                }

                if ( $usable ) {
                    require_once alpus_core_framework_path( ALPUS_BUILDERS . '/header/widgets/' . str_replace( '_', '-', $widget ) . '/widget-' . str_replace( '_', '-', $widget ) . '-elementor.php' );
                    $class_name = 'Alpus_Header_' . ucwords( $widget, '_' ) . '_Elementor_Widget';
                    $self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
                }
            }
        }
    }
}

Alpus_Header_Builder::get_instance();
