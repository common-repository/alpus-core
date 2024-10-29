<?php

defined( 'ABSPATH' ) || die;

/*
 * Alpus Block Widget
 *
 * Alpus Widget to display custom block.
 *
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

use Elementor\Alpus_Controls_Manager;

class Alpus_Block_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_block';
    }

    public function get_title() {
        return esc_html__( 'Block', 'alpus-core' );
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'block' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-block';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_block',
            array(
                'label' => esc_html__( 'Block', 'alpus-core' ),
            )
        );

        $this->add_control(
            'name',
            array(
                'label'       => esc_html__( 'Select a Block', 'alpus-core' ),
                'description' => esc_html__( 'Choose your favourite block from pre-built blocks.', 'alpus-core' ),
                'type'        => Alpus_Controls_Manager::AJAXSELECT2,
                'options'     => 'block',
                'label_block' => true,
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $atts = $this->get_settings_for_display();
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/block/render-block.php' );
    }

    protected function content_template() {
    }
}
