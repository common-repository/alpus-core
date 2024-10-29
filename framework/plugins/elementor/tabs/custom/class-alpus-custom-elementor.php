<?php
/**
 * Alpus Elementor Custom Css & Js
 *
 * @author     D-THEMES
 *
 * @version    1.2.0
 */
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

if ( ! class_exists( 'Alpus_Custom_Elementor' ) ) {
    /**
     * Alpus Elementor Custom Css & Js
     *
     * @since 1.2.0
     */
    class Alpus_Custom_Elementor extends Alpus_Base {

        /**
                 * The Constructor.
                 *
                 * @since 1.2.0
                 */
        public function __construct() {
            // Add controls to addon tab
            add_action( 'alpus_elementor_addon_controls', array( $this, 'add_controls' ), 99, 2 );
        }

        /**
         * Add controls to addon tab.
         *
         * @since 1.2.0
         */
        public function add_controls( $self, $source = '' ) {
            if ( 'banner' != $source ) {
                $self->start_controls_section(
                    '_alpus_section_custom_css',
                    array(
                        'label' => esc_html__( 'Custom Page CSS', 'alpus-core' ),
                        'tab'   => Alpus_Widget_Advanced_Tabs::TAB_CUSTOM,
                    )
                );

                $self->add_control(
                    '_alpus_custom_css',
                    array(
                        'type' => Controls_Manager::TEXTAREA,
                        'rows' => 40,
                    )
                );

                $self->end_controls_section();

                $self->start_controls_section(
                    '_alpus_section_custom_js',
                    array(
                        'label' => esc_html__( 'Custom Page JS', 'alpus-core' ),
                        'tab'   => Alpus_Widget_Advanced_Tabs::TAB_CUSTOM,
                    )
                );

                $self->add_control(
                    '_alpus_custom_js',
                    array(
                        'type' => Controls_Manager::TEXTAREA,
                        'rows' => 40,
                    )
                );

                $self->end_controls_section();
            }
        }
    }
    Alpus_Custom_Elementor::get_instance();
}
