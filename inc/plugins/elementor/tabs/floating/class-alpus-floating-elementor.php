<?php
/**
 * Alpus Elementor Floating Addon
 *
 * @author     AlpusTheme
 *
 * @version    1.0.0
 */
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

if ( ! class_exists( 'Alpus_Floating_Elementor' ) ) {
    /**
     * Alpus Elementor Floating Addon
     *
     * @since 1.0.0
     */
    class Alpus_Floating_Elementor extends Alpus_Base {

        /**
                 * The Constructor.
                 *
                 * @since 1.0.0
                 */
        public function __construct() {
            // Add controls to addon tab
            add_action( 'alpus_elementor_addon_controls', array( $this, 'add_controls' ), 10, 2 );
        }

        /**
         * Add controls to addon tab.
         *
         * @since 1.0.0
         */
        public function add_controls( $self, $source = '' ) {
            if ( 'banner' != $source ) {
                $self->start_controls_section(
                    '_alpus_section_motion_effect',
                    array(
                        'label' => esc_html__( 'Motion Effects', 'alpus-core' ),
                        'tab'   => Alpus_Widget_Advanced_Tabs::TAB_CUSTOM,
                    )
                );

                Alpus_Core_Elementor_Extend::purchase_elementor_addon_notice( $self, Controls_Manager::RAW_HTML, '', 'alpus_motion', array() );

                $self->end_controls_section();
            }
        }
    }
    Alpus_Floating_Elementor::get_instance();
}
