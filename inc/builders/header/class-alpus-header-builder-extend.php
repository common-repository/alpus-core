<?php
/**
 * Alpus Builder Header class
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Alpus_Controls_Manager;
use Elementor\Controls_Manager;

class Alpus_Header_Builder_Extend extends Alpus_Base {

    /**
         * Constructor
         *
         * @since 1.0
         */
    public function __construct() {
        // Register Document Controls.
        add_action( 'elementor/documents/register_controls', array( $this, 'register_document_controls' ) );

        add_action( 'init', function () {
            if ( function_exists( 'alpus_get_option' ) && alpus_get_option( 'compare_available' ) && ! defined( 'ALPUS_PRODUCT_COMPARE_VERSION' ) ) {
                set_theme_mod( 'compare_available', false );
            }
        } );

        // Add controls to section.
        // add_action( 'alpus_elementor_section_addon_controls', array( $this, 'add_section_controls' ), 10, 1 );
    }

    /**
     * Add Side Header option
     *
     * @since 1.0
     */
    public function register_document_controls( $document ) {
        if ( ! $document instanceof Elementor\Core\DocumentTypes\PageBase && ! $document instanceof Elementor\Modules\Library\Documents\Page ) {
            return;
        }

        // Add Template Builder Controls
        $id = (int) $document->get_main_id();

        if ( ALPUS_NAME . '_template' == get_post_type( $id ) ) {
            if ( $id && 'header' == get_post_meta( $id, ALPUS_NAME . '_template_type', true ) ) {
                $document->start_controls_section(
                    'header_settings',
                    array(
                        'label' => alpus_elementor_panel_heading( esc_html__( 'Header Settings', 'alpus-core' ) ),
                        'tab'   => Elementor\Controls_Manager::TAB_SETTINGS,
                    )
                );

                $document->add_control(
                    'header_bg',
                    array(
                        'label'       => esc_html__( 'Background Color', 'alpus-core' ),
                        'description' => esc_html__( 'Controls the background color of header.', 'alpus-core' ),
                        'type'        => Elementor\Controls_Manager::COLOR,
                        'selectors'   => array(
                            'body:not(.side-header) .header' => 'background-color: {{VALUE}};',
                            '.side-header .header-area'      => 'background-color: {{VALUE}};',
                        ),
                    )
                );
                $document->add_control(
                    'header_pos',
                    array(
                        'label'           => esc_html__( 'Position', 'alpus-core' ),
                        'type'            => Alpus_Controls_Manager::SELECT,
                        'options'         => array(
                            ''     => esc_html__( 'Top', 'alpus-core' ),
                            'side' => esc_html__( 'Side', 'alpus-core' ),
                        ),
                        'disabledOptions' => array( 'side' ),
                    )
                );

                Alpus_Core_Elementor_Extend::upgrade_pro_notice( $document, Controls_Manager::RAW_HTML, '', 'header_pos', array( 'header_pos' => 'side' ) );

                $document->end_controls_section();
            }
        }
    }

    /**
     * Add sticky effect controls to section element
     *
     * @since 1.0
     */
    public function add_section_controls( $self ) {
        // Add Sticky Effects for Header Controls.
        global $post;

        if ( ! empty( $post ) && ALPUS_NAME . '_template' == get_post_type( $post ) && 'header' == get_post_meta( $post->ID, ALPUS_NAME . '_template_type', true ) ) {
            $self->start_controls_section(
                'section_content_sticky_effects',
                array(
                    'label'     => alpus_elementor_panel_heading( esc_html__( 'Sticky Header Effects', 'alpus-core' ) ),
                    'tab'       => Controls_Manager::TAB_LAYOUT,
                    'condition' => array(
                        'section_content_sticky' => 'fix-top',
                    ),
                )
            );

            Alpus_Core_Elementor_Extend::upgrade_pro_notice( $self, Controls_Manager::RAW_HTML, '', 'sticky_effects', array() );

            $self->end_controls_section();
        }
    }
}

Alpus_Header_Builder_Extend::get_instance();
