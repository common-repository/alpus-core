<?php
defined( 'ABSPATH' ) || die;

/*
 * Alpus Scroll Section
 *
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;

if ( ! class_exists( 'Alpus_Scroll_Section_Elementor_Widget_Addon' ) ) {
    class Alpus_Scroll_Section_Elementor_Widget_Addon extends Alpus_Base {

        /**
                 * Constructor
                 *
                 * @since 1.0
                 */
        public function __construct() {
            // Enqueue component css
            add_action( 'alpus_before_enqueue_custom_css', array( $this, 'enqueue_scripts' ) );
            add_filter( 'alpus_elementor_section_addons', array( $this, 'register_scroll_section' ) );
            add_action( 'alpus_elementor_section_addon_controls', array( $this, 'add_scroll_section_controls' ), 10, 2 );
            add_action( 'alpus_elementor_section_addon_content_template', array( $this, 'section_addon_content_template' ) );
            add_action( 'alpus_elementor_section_render', array( $this, 'section_addon_render' ), 10, 2 );
            add_filter( 'alpus_elementor_section_addon_render_attributes', array( $this, 'section_addon_attributes' ), 10, 3 );
        }

        /**
         * Enqueue component css
         *
         * @since 1.0
         */
        public function enqueue_scripts() {
            if ( alpus_is_elementor_preview() ) {
                wp_enqueue_style( 'alpus-scroll-section', alpus_core_framework_uri( '/widgets/scroll-section/scroll-section' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );
            }
        }

        /**
         * Register scroll section addon to section element
         *
         * @since 1.0
         */
        public function register_scroll_section( $addons ) {
            $addons['scroll_section'] = esc_html__( 'Scroll Section', 'alpus-core' );

            return $addons;
        }

        /**
         * Add banner controls to section element
         *
         * @since 1.0
         */
        public function add_scroll_section_controls( $self, $condition_value ) {
            $self->start_controls_section(
                'scroll_section',
                array(
                    'label'     => alpus_elementor_panel_heading( esc_html__( 'Scroll Section', 'alpus-core' ) ),
                    'tab'       => Controls_Manager::TAB_LAYOUT,
                    'condition' => array(
                        $condition_value => 'scroll_section',
                    ),
                )
            );

            $self->add_control(
                'max_height',
                array(
                    'type'      => Controls_Manager::NUMBER,
                    'label'     => __( 'Max Height(px)', 'alpus-core' ),
                    'default'   => 250,
                    'condition' => array(
                        $condition_value => 'scroll_section',
                    ),
                    'selectors' => array(
                        '.elementor-element-{{ID}}.elementor-section .elementor-container' => 'max-height: {{VALUE}}px;',
                    ),
                )
            );

            $self->add_control(
                'scrollbar_handle_color',
                array(
                    'label'       => esc_html__( 'Scrollbar Handle Color', 'alpus-core' ),
                    'description' => esc_html__( 'Set background color of scrollbar handle.', 'alpus-core' ),
                    'type'        => Controls_Manager::COLOR,
                    'condition'   => array(
                        $condition_value => 'scroll_section',
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}} .scrollable:hover::-webkit-scrollbar-thumb' => 'background: {{VALUE}};',
                    ),
                )
            );

            $self->add_control(
                'scrollbar_background_color',
                array(
                    'label'       => esc_html__( 'Scrollbar Background Color', 'alpus-core' ),
                    'description' => esc_html__( 'Set background color of scrollbar.', 'alpus-core' ),
                    'type'        => Controls_Manager::COLOR,
                    'condition'   => array(
                        $condition_value => 'scroll_section',
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}} .scrollable:hover::-webkit-scrollbar' => 'background: {{VALUE}};',
                    ),
                )
            );

            $self->add_control(
                'overlay_color',
                array(
                    'label'       => esc_html__( 'Overlay Color', 'alpus-core' ),
                    'description' => esc_html__( 'Set the overlay color of the scroll section.', 'alpus-core' ),
                    'type'        => Controls_Manager::COLOR,
                    'condition'   => array(
                        $condition_value => 'scroll_section',
                    ),
                    'selectors'   => array(
                        '.elementor-element-{{ID}}.scroll-overlay-section:after' => 'background-image: linear-gradient(180deg, transparent 0%, {{VALUE}} 159%);',
                    ),
                )
            );

            $self->end_controls_section();
        }

        /**
         * Print scroll section content in elementor section content template function
         *
         * @since 1.0
         */
        public function section_addon_content_template( $self ) {
            ?>
			<#
			if ( 'scroll_section' == settings.use_as ) {
				extra_class = ' scroll-section scrollable';
			#>

			<?php if ( $self->legacy_mode ) { ?>
				<#
				addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no" data-scrollable="true">';
				#>
			<?php } else { ?>
				<#
				addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no ' + extra_class + '" data-scrollable="true">';
				#>
			<?php } ?>

				<?php if ( $self->legacy_mode ) { ?>
					<#
					addon_html += '<!-- Begin .elementor-row --><div class="elementor-row' + extra_class + '" data-scrollable="true"	></div><!-- End .elementor-row -->';
					#>
				<?php } ?>

			<#
			addon_html += '</div>';
			}
			#>
				<?php
        }

        /**
         * Add render attributes for scroll section
         *
         * @since 1.0
         */
        public function section_addon_attributes( $options, $self, $settings ) {
            if ( 'scroll_section' == $settings['use_as'] ) {
                $options['class'] = 'scroll-overlay-section';
            }

            return $options;
        }

        /**
         * Render scroll section HTML
         *
         * @since 1.0
         */
        public function section_addon_render( $self, $settings ) {
            if ( 'scroll_section' === $settings['use_as'] ) {
                wp_enqueue_style( 'alpus-scroll-section', alpus_core_framework_uri( '/widgets/scroll-section/scroll-section' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

                $extra_class = ' scroll-section scrollable';
                /*
                 * Fires after rendering effect addons such as duplex and ribbon.
                 *
                 * @since 1.0
                 */
                do_action( 'alpus_elementor_addon_render', $settings, $self->get_ID() );

                if ( $self->legacy_mode ) {
                    ?>
				<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no">
				<?php } else { ?>
				<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no<?php echo esc_attr( $extra_class ); ?>">
				<?php } ?>
				<?php if ( $self->legacy_mode ) { ?>
				<div class="elementor-row <?php echo esc_attr( $extra_class ); ?>">
					<?php
				}
            }
        }
    }
}

/*
 * Create instance
 *
 * @since 1.0
 */
Alpus_Scroll_Section_Elementor_Widget_Addon::get_instance();
