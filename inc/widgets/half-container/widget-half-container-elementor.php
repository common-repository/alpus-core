<?php
defined( 'ABSPATH' ) || die;

/*
 * Half Container
 *
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;

if ( ! class_exists( 'Alpus_Half_Container_Elementor_Widget_Addon' ) ) {
    class Alpus_Half_Container_Elementor_Widget_Addon extends Alpus_Base {

        /**
                 * Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            // Add half column controls
            add_filter( 'alpus_elementor_column_addons', array( $this, 'register_half_column_addon' ) );
            add_action( 'alpus_elementor_column_addon_controls', array( $this, 'add_half_column_controls' ), 10, 2 );
            add_action( 'alpus_elementor_column_addon_content_template', array( $this, 'half_column_addon_content_template' ) );
            add_action( 'alpus_elementor_column_render', array( $this, 'half_column_addon_render' ), 10, 4 );
        }

        /**
         * Register half content addon to column element
         *
         * @since 1.0
         */
        public function register_half_column_addon( $addons ) {
            $addons['half_content'] = esc_html__( 'Half Container', 'alpus-core' );

            return $addons;
        }

        /**
         * Add half controls to column element
         *
         * @since 1.0
         */
        public function add_half_column_controls( $self, $condition_value ) {
            $self->start_controls_section(
                'column_half',
                array(
                    'label'     => alpus_elementor_panel_heading( esc_html__( 'Half Container', 'alpus-core' ) ),
                    'tab'       => Controls_Manager::TAB_LAYOUT,
                    'condition' => array(
                        $condition_value => 'half_content',
                    ),
                )
            );
            $self->add_control(
                'is_half_right',
                array(
                    'type'        => Controls_Manager::SWITCHER,
                    'label'       => esc_html__( 'Is Right Aligned?', 'alpus-core' ),
                    'description' => esc_html__( 'Make the column alignment to right.', 'alpus-core' ),
                )
            );

            $self->add_control(
                'full_breakpoint',
                array(
                    'type'        => Controls_Manager::SELECT,
                    'label'       => esc_html__( 'Full Container Under', 'alpus-core' ),
                    'description' => esc_html__( 'Make the column\'s width as normal container\'s on selected device.', 'alpus-core' ),
                    'default'     => 'tablet',
                    'options'     => array(
                        'desktop' => esc_html__( 'Desktop', 'alpus-core' ),
                        'tablet'  => esc_html__( 'Tablet', 'alpus-core' ),
                        'mobile'  => esc_html__( 'Mobile', 'alpus-core' ),
                    ),
                )
            );

            $self->end_controls_section();
        }

        /**
         * Print half container in elementor column content template function
         *
         * @since 1.0
         */
        public function half_column_addon_content_template( $self ) {
            ?>
		<#
			if ( 'half_content' == settings.use_as ) {
				let wrapper_element = '';
				wrapper_class += ' col-half-section';
				if (settings.is_half_right) {
					wrapper_class += ' col-half-section-right';
				}
				if (settings.full_breakpoint) {
					wrapper_class += ' col-half-section-' + settings.full_breakpoint;
				}
				<?php
                if ( ! alpus_elementor_if_dom_optimization() ) {
                    ?>
					wrapper_element = 'column';
					addon_html += '<div class="elementor-' + wrapper_element + '-wrap' + wrapper_class + '" ' + wrapper_attrs + '>';
					addon_html += '<div class="elementor-background-overlay"></div>';
					addon_html += '<div class="elementor-widget-wrap' + extra_class + '" ' + extra_attrs + '></div></div>';
					<?php
                } else {
                    ?>
					extra_class  = '';
					wrapper_element = 'widget';
					addon_html += '<div class="elementor-' + wrapper_element + '-wrap' + wrapper_class + extra_class + '" ' + wrapper_attrs + ' ' + extra_attrs + '>';
					addon_html += '<div class="elementor-background-overlay"></div>';
					<?php
                }
            ?>
				<?php if ( alpus_elementor_if_dom_optimization() ) { ?>
					addon_html += '</div>';
				<?php } ?>
			}
		#>
			<?php
        }

        /**
         * Render half container HTML
         *
         * @since 1.0
         */
        public function half_column_addon_render( $self, $settings, $has_background_overlay, $is_legacy_mode_active ) {
            if ( 'half_content' == $settings['use_as'] ) {
                ?>
			<!-- Start .elementor-column -->
			<<?php echo  $self->get_html_tag() . ' ' . $self->get_render_attribute_string( '_wrapper' ); ?>>
				<?php
                /**
                 * Fires after rendering effect addons such as duplex and ribbon.
                 *
                 * @since 1.0
                 */
                do_action( 'alpus_elementor_addon_render', $settings, $self->get_ID() );
                $half_class = 'col-half-section ' . esc_attr( 'yes' == $settings['is_half_right'] ? 'col-half-section-right ' : '' ) . ' col-half-section-' . esc_attr( $settings['full_breakpoint'] );
                $self->add_render_attribute( '_inner_wrapper', 'class', $half_class );

                ?>
			<!-- Start .elementor-column-wrap(optimize mode => .elementor-widget-wrap) -->
			<div <?php $self->print_render_attribute_string( '_inner_wrapper' ); ?>>
				<?php if ( $has_background_overlay ) { ?>
					<div <?php $self->print_render_attribute_string( '_background_overlay' ); ?>></div>
				<?php } ?>
				<?php if ( $is_legacy_mode_active ) { ?>
					<!-- Start .elementor-widget-wrap -->
					<div <?php $self->print_render_attribute_string( '_widget_wrapper' ); ?>>
				<?php } ?>
				<?php
            }
        }
    }
}

/*
 * Create instance
 *
 * @since 1.0
 */
Alpus_Half_Container_Elementor_Widget_Addon::get_instance();
