<?php
/**
 * Alpus Select Control
 *
 * @author     AlpusTheme
 *
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Base_Data_Control;

class Alpus_Control_Select extends Base_Data_Control {

    /**
         * Get select control type.
         *
         * Retrieve the control type, in this case `select`.
         *
         * @since 1.0.0
         *
         * @return string control type
         */
    public function get_type() {
        return 'select';
    }

    /**
     * Get select control default settings.
     *
     * Retrieve the default settings of the select control. Used to return the
     * default settings while initializing the select control.
     *
     * @since 2.0.0
     *
     * @return array control default settings
     */
    protected function get_default_settings() {
        return [
            'options' => [],
        ];
    }

    /**
     * Render select control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function content_template() {
        ?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="<?php $this->print_control_uid(); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<select id="<?php $this->print_control_uid(); ?>" data-setting="{{ data.name }}">
				<#
					var printOptions = function( options ) {
						_.each( options, function( option_title, option_value ) {
							let option_class = '';
							if ( 'undefined' != typeof data.disabledOptions && -1 != data.disabledOptions.indexOf( option_value ) ) {
								option_class = 'disabled';
							}
							#>
								<option value="{{ option_value }}" class="{{ option_class }}">{{{ option_title }}}</option>
							<# 
						} );
					};

					if ( data.groups ) {
						for ( var groupIndex in data.groups ) {
							var groupArgs = data.groups[ groupIndex ];
								if ( groupArgs.options ) { #>
									<optgroup label="{{ groupArgs.label }}">
										<# printOptions( groupArgs.options ) #>
									</optgroup>
								<# } else if ( _.isString( groupArgs ) ) {
									let option_class = '';
									if ( 'undefined' != typeof data.disabledOptions && -1 != data.disabledOptions.indexOf( option_value ) ) {
										option_class = 'disabled';
									}
									#>
									<option value="{{ groupIndex }}" class="{{ option_class }}">{{{ groupArgs }}}</option>
									<#
								}
						}
					} else {
						printOptions( data.options );
					}
				#>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
    }
}
