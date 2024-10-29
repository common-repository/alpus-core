<?php
/**
 * Alpus WooCommerce plugin compatibility.
 *
 * @author     D-THEMES
 *
 * @since      1.2.0
 */

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die;

class Alpus_Core_WooCommerce extends Alpus_Base {

    /**
         * Constructor
         *
         * @since 1.2.0
         */
    public function __construct() {
        add_filter( 'alpus_dynamic_tags', array( $this, 'woo_add_tags' ) );
        add_filter( 'alpus_dynamic_field_object', array( $this, 'woo_add_object' ) );
        add_filter( 'alpus_dynamic_extra_fields_content', array( $this, 'woo_render' ), 10, 3 );
        add_action( 'alpus_dynamic_extra_fields', array( $this, 'woo_add_control' ), 10, 3 );
    }

    /**
     * Render WOO Field
     *
     * @since 1.2.0
     */
    public function woo_render( $result, $settings, $widget = 'field' ) {
        if ( 'woo' == $settings['dynamic_field_source'] ) {
            $widget = 'dynamic_woo_' . $widget;
            $key    = isset( $settings[ $widget ] ) ? $settings[ $widget ] : false;

            $product = wc_get_product();

            if ( ! $product ) {
                return $result;
            }

            if ( 'sales' == $key ) {
                $result = $product->get_total_sales();
            } elseif ( 'excerpt' == $key ) {
                $result = $product->get_short_description();
            } elseif ( 'sku' == $key ) {
                $result = esc_html( $product->get_sku() );
            } elseif ( 'stock' == $key ) {
                $result = $product->get_stock_quantity();
            }
        }

        return $result;
    }

    /**
     * Add Dynamic Woo Tags
     *
     * @since 1.2.0
     */
    public function woo_add_tags( $tags ) {
        if ( ! alpus_is_elementor_preview() || ( ALPUS_NAME . '_template' == get_post_type() && 'product_layout' == get_post_meta( get_the_ID(), ALPUS_NAME . '_template_type', true ) ) ) {
            array_push( $tags, 'Alpus_Core_Custom_Field_Woo_Tag' );
        }

        return $tags;
    }

    /**
     * Add Woo object to Dynamic Field
     *
     * @since 1.2.0
     */
    public function woo_add_object( $objects ) {
        $objects['woo'] = esc_html__( 'WooCommerce', 'alpus-core' );

        return $objects;
    }

    /**
     * Add control for WOO object
     *
     * @since 1.2.0
     */
    public function woo_add_control( $object, $widget = 'field', $plugin = 'woo' ) {
        if ( 'woo' == $plugin ) {
            $control_key = 'dynamic_woo_' . $widget;
            $object->add_control(
                $control_key,
                array(
                    'label'   => esc_html__( 'WOO Field', 'alpus-core' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'sku',
                    'groups'  => $this->get_woo_fields( $widget ),
                )
            );
        }
    }

    /**
     * Retrieve WOO fields for each group
     *
     * @since 1.2.0
     */
    public function get_woo_fields( $widget ) {
        $fields = array(
            'excerpt' => esc_html__( 'Product Short Description', 'alpus-core' ),
            'sku'     => esc_html__( 'Product SKU', 'alpus-core' ),
            'sales'   => esc_html__( 'Product Sales', 'alpus-core' ),
            'stock'   => esc_html__( 'Product Stock', 'alpus-core' ),
        );

        return $fields;
    }
}

Alpus_Core_WooCommerce::get_instance();
