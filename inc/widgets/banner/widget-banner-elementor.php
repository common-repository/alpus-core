<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpus Banner Widget
 *
 * Alpus Widget to display banner.
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
class Alpus_Banner_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return ALPUS_NAME . '_widget_banner';
    }

    public function get_title() {
        return esc_html__( 'Banner', 'alpus-core' );
    }

    public function get_icon() {
        return 'alpus-elementor-widget-icon alpus-widget-icon-banner';
    }

    public function get_categories() {
        return array( 'alpus_widget' );
    }

    public function get_keywords() {
        return array( 'banner' );
    }

    /**
     * Get the style depends.
     *
     * @since 1.0
     */
    public function get_style_depends() {
        wp_register_style( 'alpus-banner', alpus_core_framework_uri( '/widgets/banner/banner' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

        return array( 'alpus-banner' );
    }

    protected function register_controls() {
        alpus_elementor_banner_controls( $this );
    }

    public function get_repeater_setting_key( $setting_key, $repeater_key, $repeater_item_index ) {
        return parent::get_repeater_setting_key( $setting_key, $repeater_key, $repeater_item_index );
    }

    public function add_inline_editing_attributes( $key, $toolbar = 'basic' ) {
        parent::add_inline_editing_attributes( $key, $toolbar );
    }

    protected function render() {
        $atts         = $this->get_settings_for_display();
        $atts['self'] = $this;
        require alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/widgets/banner/render-banner-elementor.php' );
    }

    public function before_render() {
        $atts = $this->get_settings_for_display();

        if ( 'yes' == $atts['stretch_height'] ) {
            $this->add_render_attribute( '_wrapper', 'class', 'elementor-widget-alpus_banner_stretch' );
        }
        ?>
		<div <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
		<?php
    }

    protected function content_template() {
        ?>
		<#

		let effectClass = '';

		view.addRenderAttribute( 'banner_wrapper', 'class', 'banner banner-fixed' );
		view.addRenderAttribute( 'banner_content', 'class', 'banner-content' );
		view.addRenderAttribute( 'banner_content', 'class', settings.banner_origin );

		// Overlay
		if ( settings.overlay ) {

			let overlayClass = '';

			if ( 'light' == settings.overlay || 'dark' == settings.overlay || 'zoom' == settings.overlay ) {
				overlayClass = 'overlay-' + settings.overlay;
			} else if ( 'zoom_light' == settings.overlay ) {
				overlayClass = 'overlay-zoom overlay-light';
			} else if ( 'zoom_dark' == settings.overlay ) {
				overlayClass = 'overlay-zoom overlay-dark';
			} else if ( '' !== settings.overlay ) {
				overlayClass = 'overlay-wrapper';
				effectClass = 'overlay-' + settings.overlay;
			}

			view.addRenderAttribute( 'banner_wrapper', 'class', overlayClass );
		}

		// Stretch Height
		if ( 'yes' == settings.stretch_height ) {
			view.addRenderAttribute( 'banner_wrapper', 'class', 'banner-stretch' );
		}

		#><div {{{ view.getRenderAttributeString( 'banner_wrapper' ) }}}><#

		if( '' != effectClass ) {
			effectClass += ' overlay-effect';
			#>
			<div class="{{ effectClass }}"></div>
			<#
		}
		
		if ( settings.banner_background_image.url ) {
			#>
			<figure class="banner-img">
				<img src="{{ settings.banner_background_image.url }}" />
			</figure>
			<#
		}

		if ( settings.banner_wrap ) {
			#><div class="{{ settings.banner_wrap }}"><#
		}

		// Showing Items
		#><div {{{ view.getRenderAttributeString( 'banner_content' ) }}}><#

		if ( settings._content_animation ) {
			view.addRenderAttribute( 'banner_content_inner', 'class', 'appear-animate' );
			if( settings.content_animation_duration ) {
				view.addRenderAttribute( 'banner_content_inner', 'class', 'animated-' + settings.content_animation_duration );
			}
			let contentSettings       = {
				'_animation'       : settings._content_animation,
				'_animation_delay' : settings._content_animation_delay ? settings._content_animation_delay : 0,
			};
			view.addRenderAttribute( 'banner_content_inner', 'data-settings', JSON.stringify( contentSettings ) );
			#><div {{{ view.getRenderAttributeString( 'banner_content_inner' ) }}}><#
		}

		_.each( settings.banner_item_list, function( item, index ) {

			let item_key = 'banner_item';
			if ( item.banner_item_type == 'text' ) { // Text
				item_key = view.getRepeaterSettingKey( 'banner_text_content', 'banner_item_list', index );
			}

			view.renderAttributes[item_key] = {};
			view.addRenderAttribute( item_key, 'class', 'banner-item' );
			view.addRenderAttribute( item_key, 'class', 'elementor-repeater-item-' + item._id );

			// Custom Class
			if ( item.banner_item_aclass ) {
				view.addRenderAttribute( item_key, 'class', item.banner_item_aclass );
			}

			// Animation
			let itemSettings = '';
			if ( item._animation ) {
				view.addRenderAttribute( item_key, 'class', 'appear-animate' );
				if( settings.animation_duration ) {
					view.addRenderAttribute( item_key, 'class', 'animated-' + settings.animation_duration );
				}
				let itemSettings = {
					'_animation'       : settings._animation,
					'_animation_delay' : settings._animation_delay ? settings._animation_delay : 0,
				};
				view.addRenderAttribute( item_key, 'data-settings', JSON.stringify( itemSettings ) );
			}

			// Item display type
			if ( 'yes' != item.banner_item_display ) {
				view.addRenderAttribute( item_key, 'class', 'item-block' );
			} else {
				view.addRenderAttribute( item_key, 'class', 'item-inline' );
			}

			if ( item.banner_item_type == 'text' ) { // Text

				view.addRenderAttribute( item_key, 'class', 'elementor-banner-item-text text' );

				view.addInlineEditingAttributes( item_key );

				#><{{item.banner_text_tag}} {{{ view.getRenderAttributeString( item_key ) }}}>{{{ item.banner_text_content }}}</{{item.banner_text_tag}}><#

			} else if ( item.banner_item_type == 'button' ) { // Button

				btn_class = [];
				if ( item.button_type ) {
					btn_class.push(item.button_type);
				}
				if ( 'btn-link' == item.button_type && item.link_hover_type ) {
					btn_class.push(item.link_hover_type);
					
					if ( 'yes' ==  item.show_underline ) {
						btn_class.push('btn-underline-show');
					}
				}
				if ( item.button_size ) {
					btn_class.push(item.button_size);
				}
				if ( item.shadow ) {
					btn_class.push(item.shadow);
				}
				if ( item.button_border ) {
					btn_class.push(item.button_border);
				}
				if ( 'btn-gradient' != item.button_type && item.button_skin ) {
					btn_class.push(item.button_skin);
				}
				if( 'btn-gradient' == item.button_type && item.button_gradient_skin ) {
					btn_class.push(item.button_gradient_skin);
				}
				if ( item.btn_class ) {
					btn_class.push(item.btn_class);
				}
				if ( 'yes' == item.icon_hover_effect_infinite ) {
					btn_class.push('btn-infinite');
				}

				if ( 'yes' == item.show_icon && item.icon && item.icon.value ) {
					if ( 'before' == item.icon_pos ) {
						btn_class.push('btn-icon-left');
						if ( item.icon_hover_effect && item.icon_hover_effect == 'btn-reveal' ) {
							btn_class.push('btn-reveal-left');
						}
					} else {
						btn_class.push('btn-icon-right');
						if ( item.icon_hover_effect && item.icon_hover_effect == 'btn-reveal' ) {
							btn_class.push('btn-reveal-right');
						}
					}
					if ( item.icon_hover_effect && item.icon_hover_effect != 'btn-reveal' ) {
						btn_class.push(item.icon_hover_effect);
					}
				}
				if( item.banner_btn_link.url && item.banner_btn_link.url != '' ) {
					view.addRenderAttribute( item_key, 'href', item.banner_btn_link.url );

					if( item.banner_btn_link.is_external ) {
						view.addRenderAttribute( item_key, 'target', '_blank' );
					}
					if( item.banner_btn_link.nofollow ) {
						view.addRenderAttribute( item_key, 'rel', 'nofollow' );
					}
				}
				view.addRenderAttribute( item_key, 'class', 'btn' );
				if ( item.banner_btn_aclass ) {
					view.addRenderAttribute( item_key, 'class', item.banner_btn_aclass );
				}
				view.addRenderAttribute( item_key, 'class', btn_class );
					#>
				<a {{{ view.getRenderAttributeString( item_key ) }}}>
					<#
					let btn_text_key = view.getRepeaterSettingKey( 'banner_btn_text', 'banner_item_list', index );

					view.addRenderAttribute( btn_text_key, 'class', 'elementor-banner-item-text' );
					view.addInlineEditingAttributes( btn_text_key );

					let btn_text = '';

					btn_text = item.banner_btn_text;
					if ( item.icon && item.icon.value && 'yes' == item.show_icon ) {
						var btnIconHtml = '';
						if( item.icon.library == 'svg' ) {
							btnIconHtml = elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': true } ).value;
						} else {
							btnIconHtml = '<i class="' + item.icon.value + '"></i>';
						}
						if ( 'before' == item.icon_pos ) {
							#>
							{{{ btnIconHtml }}}
							<span {{{ view.getRenderAttributeString( btn_text_key ) }}}>{{{ btn_text }}}</span>
							<#
						} else {
							#>
							<span {{{ view.getRenderAttributeString( btn_text_key ) }}}>{{{ btn_text }}}</span>
							{{{ btnIconHtml }}}
							<#
						}
					} else {
						#>
					<span {{{ view.getRenderAttributeString( btn_text_key ) }}}>{{{ btn_text }}}</span>
						<#
					}
					#>
				</a>
					<#
			} else if (item.banner_item_type == 'image') { // Image
				let image = {
					id: item.banner_image.id,
					url: item.banner_image.url,
					size: item.banner_image_size,
					dimension: item.banner_image_custom_dimension,
					model: view.getEditModel()
				};
				let image_url = elementor.imagesManager.getImageUrl( image );
				view.addRenderAttribute( item_key, 'class', 'image' );
				<!-- view.addRenderAttribute( item_key, 'src', image_url ); -->
				if( item.img_link && item.img_link.url ) {
					view.renderAttributes['image_link'] = {};
					view.addRenderAttribute( 'image_link', 'href', item.img_link.url );
					if( item.img_link.is_external ) {
						view.addRenderAttribute( 'image_link', 'target', '_blank' );
					}
					if( item.img_link.nofollow ) {
						view.addRenderAttribute( 'image_link', 'rel', 'nofollow' );
					}
				}
				#>
				<div {{{ view.getRenderAttributeString( item_key ) }}}>
					<#
					if( item.img_link && item.img_link.url ) {
					#>
						<a {{{ view.getRenderAttributeString( 'image_link' ) }}} >
					<#
						}
					#>
					<img src="{{{ image_url }}}" />
					<#
					if( item.img_link && item.img_link.url ) {
					#>
						</a>
					<#
						}
					#>
				</div>
				<#
			} else { // Divider
				view.addRenderAttribute( item_key, 'class', 'divider-wrap' );
				#><div {{{ view.getRenderAttributeString( item_key ) }}}><hr class="divider"></div><#
			}
		} );
		if ( settings._content_animation ) {
			#></div><#
		}
		#></div><#
		if ( settings.banner_wrap ) {
			#></div><#
		}
		#></div><#
		#>
		<?php
    }
}
