<?php

defined( 'ABSPATH' ) || die;

/*
 * Alpus Image_Choose Control
 *
 * @author     D-THEMES
 * @package    WP Alpus Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Base_Data_Control;

if ( ! class_exists( 'Alpus_Control_Description' ) ) {
    class Alpus_Control_Description extends Base_Data_Control {

        public function get_type() {
            return 'description';
        }

        public function content_template() {
            $control_uid = $this->get_control_uid( '{{value}}' );
            ?>
		<div class="elementor-control-field">
			<p class="elementor-control-description">{{{ data.description }}}</p>
		</div>
			<?php
        }

        protected function get_default_settings() {
            return array(
                'options' => array(),
                'toggle'  => true,
            );
        }
    }
}
