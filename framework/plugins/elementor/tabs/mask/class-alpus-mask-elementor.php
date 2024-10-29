<?php
/**
 * Alpus Elementor Mask Addon
 *
 * @author     D-THEMES
 *
 * @version    1.3.0
 */
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

if ( ! class_exists( 'Alpus_Mask_Elementor' ) ) {
    /**
     * Alpus Elementor Mask Addon
     *
     * @since 1.3.0
     */
    class Alpus_Mask_Elementor extends Alpus_Base {

        /**
                 * Gets a string of CSS rules to apply, and returns an array of selectors with those rules.
                 * This function has been created in order to deal with masking for image widget.
                 * For most of the widgets the mask is being applied to the wrapper itself, but in the case of an image widget,
                 * the `img` tag should be masked directly. So instead of writing a lot of selectors every time,
                 * this function builds both of those selectors easily.
                 *
                 * @param $rules string The CSS rules to apply
                 *
                 * @return array selectors with the rules applied
                 */
        private function get_mask_selectors( $src, $rules ) {
            if ( 'section' == $src || 'column' == $src ) {
                $mask_selectors = array(
                    'wrappper' => '{{WRAPPER}}',
                );

                return array(
                    $mask_selectors['wrappper'] => $rules,
                );
            }

            $mask_selectors = array(
                'default' => '{{WRAPPER}}:not( .elementor-widget-image ) .elementor-widget-container',
                'image'   => '{{WRAPPER}}.elementor-widget-image .elementor-widget-container img',
            );

            return array(
                $mask_selectors['default'] => $rules,
                $mask_selectors['image']   => $rules,
            );
        }

        /**
         * Return a translated user-friendly list of the available masking shapes.
         *
         * @param bool $add_custom determine if the output should contain `Custom` options
         *
         * @return array array of shapes with their URL as key
         */
        private function get_shapes( $add_custom = true ) {
            $shapes = array(
                'circle'   => esc_html__( 'Circle', 'alpus-core' ),
                'flower'   => esc_html__( 'Flower', 'alpus-core' ),
                'sketch'   => esc_html__( 'Sketch', 'alpus-core' ),
                'triangle' => esc_html__( 'Triangle', 'alpus-core' ),
                'blob'     => esc_html__( 'Blob', 'alpus-core' ),
                'hexagon'  => esc_html__( 'Hexagon', 'alpus-core' ),
            );

            if ( $add_custom ) {
                $shapes['custom'] = esc_html__( 'Custom', 'alpus-core' );
            }

            return $shapes;
        }

        /**
         * The Constructor.
         *
         * @since 1.3.0
         */
        public function __construct() {
            // Add controls to addon tab
            add_action( 'alpus_elementor_addon_controls', array( $this, 'add_controls' ), 10, 2 );
        }

        /**
         * Add controls to addon tab.
         *
         * @since 1.3.0
         */
        public function add_controls( $self, $source = '' ) {
            $left  = is_rtl() ? 'right' : 'left';
            $right = 'left' == $left ? 'right' : 'left';

            if ( 'banner' != $source ) {
                $self->start_controls_section(
                    '_alpus_section_mask',
                    array(
                        'label' => esc_html__( 'Mask', 'alpus-core' ),
                        'tab'   => Alpus_Widget_Advanced_Tabs::TAB_CUSTOM,
                    )
                );

                $self->add_control(
                    '_alpus_mask_switch',
                    array(
                        'label'     => esc_html__( 'Mask', 'alpus-core' ),
                        'type'      => Controls_Manager::SWITCHER,
                        'label_on'  => esc_html__( 'On', 'alpus-core' ),
                        'label_off' => esc_html__( 'Off', 'alpus-core' ),
                        'default'   => '',
                    )
                );

                $self->add_control(
                    '_alpus_mask_shape',
                    array(
                        'label'     => esc_html__( 'Shape', 'alpus-core' ),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->get_shapes(),
                        'default'   => 'circle',
                        'selectors' => $this->get_mask_selectors( $source, '-webkit-mask-image: url( ' . ELEMENTOR_ASSETS_URL . '/mask-shapes/{{VALUE}}.svg );' ),
                        'condition' => array(
                            '_alpus_mask_switch!' => '',
                        ),
                    )
                );

                $self->add_control(
                    '_alpus_mask_image',
                    array(
                        'label'                            => esc_html__( 'Image', 'alpus-core' ),
                        'type'                             => Controls_Manager::MEDIA,
                        'media_type'                       => 'image',
                        'should_include_svg_inline_option' => true,
                        'library_type'                     => 'image/svg+xml',
                        'dynamic'                          => array(
                            'active' => true,
                        ),
                        'selectors'    => $this->get_mask_selectors( $source, '-webkit-mask-image: url( {{URL}} );' ),
                        'condition'    => array(
                            '_alpus_mask_switch!' => '',
                            '_alpus_mask_shape'   => 'custom',
                        ),
                    )
                );

                $self->add_responsive_control(
                    '_alpus_mask_size',
                    array(
                        'label'     => esc_html__( 'Size', 'alpus-core' ),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => array(
                            'contain' => esc_html__( 'Fit', 'alpus-core' ),
                            'cover'   => esc_html__( 'Fill', 'alpus-core' ),
                            'custom'  => esc_html__( 'Custom', 'alpus-core' ),
                        ),
                        'default'   => 'contain',
                        'selectors' => $this->get_mask_selectors( $source, '-webkit-mask-size: {{VALUE}};' ),
                        'condition' => array(
                            '_alpus_mask_switch!' => '',
                        ),
                    )
                );

                $self->add_responsive_control(
                    '_alpus_mask_size_scale',
                    array(
                        'label'      => esc_html__( 'Scale', 'alpus-core' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => array( 'px', 'em', '%', 'vw' ),
                        'range'      => array(
                            'px' => array(
                                'min' => 0,
                                'max' => 500,
                            ),
                            'em' => array(
                                'min' => 0,
                                'max' => 100,
                            ),
                            '%'  => array(
                                'min' => 0,
                                'max' => 200,
                            ),
                            'vw' => array(
                                'min' => 0,
                                'max' => 100,
                            ),
                        ),
                        'default'    => array(
                            'unit' => '%',
                            'size' => 100,
                        ),
                        'selectors'  => $this->get_mask_selectors( $source, '-webkit-mask-size: {{SIZE}}{{UNIT}};' ),
                        'condition'  => array(
                            '_alpus_mask_switch!' => '',
                            '_alpus_mask_size'    => 'custom',
                        ),
                    )
                );

                $self->add_responsive_control(
                    '_alpus_mask_position',
                    array(
                        'label'     => esc_html__( 'Position', 'alpus-core' ),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => array(
                            'center center' => esc_html__( 'Center Center', 'alpus-core' ),
                            'center left'   => esc_html__( 'Center Left', 'alpus-core' ),
                            'center right'  => esc_html__( 'Center Right', 'alpus-core' ),
                            'top center'    => esc_html__( 'Top Center', 'alpus-core' ),
                            'top left'      => esc_html__( 'Top Left', 'alpus-core' ),
                            'top right'     => esc_html__( 'Top Right', 'alpus-core' ),
                            'bottom center' => esc_html__( 'Bottom Center', 'alpus-core' ),
                            'bottom left'   => esc_html__( 'Bottom Left', 'alpus-core' ),
                            'bottom right'  => esc_html__( 'Bottom Right', 'alpus-core' ),
                            'custom'        => esc_html__( 'Custom', 'alpus-core' ),
                        ),
                        'default'   => 'center center',
                        'selectors' => $this->get_mask_selectors( $source, '-webkit-mask-position: {{VALUE}};' ),
                        'condition' => array(
                            '_alpus_mask_switch!' => '',
                        ),
                    )
                );

                $self->add_responsive_control(
                    '_alpus_mask_position_x',
                    array(
                        'label'      => esc_html__( 'X Position', 'alpus-core' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => array( 'px', 'em', '%', 'vw' ),
                        'range'      => array(
                            'px' => array(
                                'min' => -500,
                                'max' => 500,
                            ),
                            'em' => array(
                                'min' => -100,
                                'max' => 100,
                            ),
                            '%'  => array(
                                'min' => -100,
                                'max' => 100,
                            ),
                            'vw' => array(
                                'min' => -100,
                                'max' => 100,
                            ),
                        ),
                        'default'    => array(
                            'unit' => '%',
                            'size' => 0,
                        ),
                        'selectors'  => $this->get_mask_selectors( $source, '-webkit-mask-position-x: {{SIZE}}{{UNIT}};' ),
                        'condition'  => array(
                            '_alpus_mask_switch!'  => '',
                            '_alpus_mask_position' => 'custom',
                        ),
                    )
                );

                $self->add_responsive_control(
                    '_alpus_mask_position_y',
                    array(
                        'label'      => esc_html__( 'Y Position', 'alpus-core' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => array( 'px', 'em', '%', 'vw' ),
                        'range'      => array(
                            'px' => array(
                                'min' => -500,
                                'max' => 500,
                            ),
                            'em' => array(
                                'min' => -100,
                                'max' => 100,
                            ),
                            '%'  => array(
                                'min' => -100,
                                'max' => 100,
                            ),
                            'vw' => array(
                                'min' => -100,
                                'max' => 100,
                            ),
                        ),
                        'default'    => array(
                            'unit' => '%',
                            'size' => 0,
                        ),
                        'selectors'  => $this->get_mask_selectors( $source, '-webkit-mask-position-y: {{SIZE}}{{UNIT}};' ),
                        'condition'  => array(
                            '_alpus_mask_switch!'  => '',
                            '_alpus_mask_position' => 'custom',
                        ),
                    )
                );

                $self->add_responsive_control(
                    '_alpus_mask_repeat',
                    array(
                        'label'     => esc_html__( 'Repeat', 'alpus-core' ),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => array(
                            'no-repeat' => esc_html__( 'No-Repeat', 'alpus-core' ),
                            'repeat'    => esc_html__( 'Repeat', 'alpus-core' ),
                            'repeat-x'  => esc_html__( 'Repeat-X', 'alpus-core' ),
                            'repeat-Y'  => esc_html__( 'Repeat-Y', 'alpus-core' ),
                            'round'     => esc_html__( 'Round', 'alpus-core' ),
                            'space'     => esc_html__( 'Space', 'alpus-core' ),
                        ),
                        'default'   => 'no-repeat',
                        'selectors' => $this->get_mask_selectors( $source, '-webkit-mask-repeat: {{VALUE}};' ),
                        'condition' => array(
                            '_alpus_mask_switch!' => '',
                            '_alpus_mask_size!'   => 'cover',
                        ),
                    )
                );

                $self->end_controls_section();
            }
        }
    }
    Alpus_Mask_Elementor::get_instance();
}
