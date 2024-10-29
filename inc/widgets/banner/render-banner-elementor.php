<?php
defined( 'ABSPATH' ) || die;

/*
 * Alpus Banner Widget Render
 *
 * @author     AlpusTheme
 * @package    Alpus Core
 * @subpackage Core
 * @since      1.0
 */

extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'stretch_height'             => '',
            'banner_item_list'           => array(),
            'banner_origin'              => '',
            'banner_wrap'                => '',
            '_content_animation'         => '',
            'content_animation_duration' => '',
            '_content_animation_delay'   => '',

            // For elementor inline editing
            'self'                       => null,
        ),
        $atts
    )
);

$banner_class         = array( 'banner', 'banner-fixed' );
$banner_overlay_class = array( 'overlay-effect' );

if ( $atts['overlay'] ) {
    if ( 'effect-' === substr( $atts['overlay'], 0, -1 ) ) {
        $banner_overlay_class[] = alpus_get_overlay_class( $atts['overlay'] );
        $banner_class[]         = 'overlay-wrapper';
    } else {
        $banner_class[] = alpus_get_overlay_class( $atts['overlay'] );
    }
}

$wrapper_class = array( 'banner-content' );

// Banner Origin
$wrapper_class[] = $banner_origin;

if ( 'yes' == $stretch_height ) {
    $banner_class[] = 'banner-stretch';
}

echo  '<div class="' . esc_attr( implode( ' ', $banner_class ) ) . '">';

if ( count( $banner_overlay_class ) > 1 ) {
    echo '<div class="' . esc_attr( implode( ' ', $banner_overlay_class ) ) . '"></div>';
}

/* Image */

if ( isset( $atts['banner_background_image']['id'] ) && $atts['banner_background_image']['id'] ) {
    $banner_img_id = $atts['banner_background_image']['id'];
    ?>
	<figure class="banner-img">
		<?php
        $attr = array();

    if ( $atts['banner_background_color'] ) {
        $attr['style'] = 'background-color:' . $atts['banner_background_color'];
    }
    // Display full image for wide banner (width > height * 3).
    $image = wp_get_attachment_image_src( $banner_img_id, 'full' );

    if ( ! empty( $image[1] ) && ! empty( $image[2] ) && $image[2] && $image[1] / $image[2] > 3 ) {
        $attr['srcset'] = $image[0];
    }
    echo wp_get_attachment_image( $banner_img_id, 'full', false, $attr );
    ?>
	</figure>
	<?php
} elseif ( isset( $atts['banner_background_image']['url'] ) && $atts['banner_background_image']['url'] ) {
    ?>
	<figure class="banner-img">
		<?php echo '<img src="' . esc_url( $atts['banner_background_image']['url'] ) . '" alt="' . esc_html__( 'Default Image', 'alpus-core' ) . '" width="1400" height="753">'; ?>
	</figure>
	<?php
}

if ( $banner_wrap ) {
    echo '<div class="' . esc_attr( $banner_wrap ) . '">'; // Start banner-wrap: container, container-fluid
}

/* Showing Items */
echo '<div class="' . esc_attr( implode( ' ', $wrapper_class ) ) . '">'; // Start banner-content

/* Content Animation */
$settings = array( '' );

if ( $_content_animation ) {
    $settings = array(
        '_animation'       => $_content_animation,
        '_animation_delay' => $_content_animation_delay ? $_content_animation_delay : 0,
    );
    $settings = " data-settings='" . esc_attr( wp_json_encode( $settings ) ) . "'";
    echo '<div class="appear-animate' . ( empty( $content_animation_duration ) ? '' : ' animated-' . esc_attr( $content_animation_duration ) ) . '" ' . alpus_escaped( $settings ) . '>';
}

foreach ( $banner_item_list as $key => $item ) {
    $class = array( 'banner-item' );

    extract( // @codingStandardsIgnoreLine
        shortcode_atts(
            array(
                // Global Options
                '_id'                 => '',
                'banner_item_display' => '',
                'banner_item_aclass'  => '',
                '_animation'          => '',
                'animation_duration'  => '',
                '_animation_delay'    => '',

                // Text Options
                'banner_item_type'    => '',
                'banner_text_tag'     => 'h2',
                'banner_text_content' => '',

                // Image Options
                'banner_image'        => '',
                'banner_image_size'   => 'full',
                'img_link'            => esc_html__( 'https://your-link.com', 'alpus-core' ),

                // Button Options
                'banner_btn_text'     => '',
                'banner_btn_link'     => '',
                'banner_btn_aclass'   => '',
            ),
            $item
        )
    );
    $class[] = 'elementor-repeater-item-' . $_id;

    // Custom Class
    if ( $banner_item_aclass ) {
        $class[] = $banner_item_aclass;
    }

    // Animation
    $settings = '';

    if ( $_animation ) {
        $class[] = 'appear-animate';

        if ( ! empty( $animation_duration ) ) {
            $class[] = 'animated-' . $animation_duration;
        }
        $settings = array(
            '_animation'       => $_animation,
            '_animation_delay' => $_animation_delay ? $_animation_delay : 0,
        );
        $settings = " data-settings='" . esc_attr( wp_json_encode( $settings ) ) . "'";
    }

    // Item display type
    if ( 'yes' != $banner_item_display ) {
        $class[] = 'item-block';
    } else {
        $class[] = 'item-inline';
    }

    if ( 'text' == $banner_item_type ) { // Text
        $class[] = 'text';

        if ( $self ) {
            $repeater_setting_key = $self->get_repeater_setting_key( 'banner_text_content', 'banner_item_list', $key );
            $self->add_render_attribute( $repeater_setting_key, 'class', $class );

            if ( ALPUS_NAME . '_widget_banner' == $self->get_name() ) {
                $self->add_inline_editing_attributes( $repeater_setting_key );
            }
        }

        printf(
            '<%1$s ' . ( $self ? $self->get_render_attribute_string( $repeater_setting_key ) : '' ) . $settings . '>%2$s</%1$s>',
            esc_attr( $banner_text_tag ),
            do_shortcode( alpus_strip_script_tags( $banner_text_content ) )
        );
    } elseif ( 'image' == $banner_item_type ) { // Image
        echo '<div class="' . esc_attr( implode( ' ', $class ) ) . ' image" ' . alpus_escaped( $settings ) . '>';

        if ( ! empty( $img_link['url'] ) ) {
            $attrs           = [];
            $attrs['href']   = ! empty( $img_link['url'] ) ? esc_url( $img_link['url'] ) : '#';
            $attrs['target'] = ! empty( $img_link['is_external'] ) ? '_blank' : '';
            $attrs['rel']    = ! empty( $img_link['nofollow'] ) ? 'nofollow' : '';

            if ( ! empty( $img_link['custom_attributes'] ) ) {
                foreach ( explode( ',', $img_link['custom_attributes'] ) as $attr ) {
                    $key   = explode( '|', $attr )[0];
                    $value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );

                    if ( isset( $attrs[ $key ] ) ) {
                        $attrs[ $key ] .= ' ' . $value;
                    } else {
                        $attrs[ $key ] = $value;
                    }
                }
            }
            $link_attrs = '';

            foreach ( $attrs as $key => $value ) {
                if ( ! empty( $value ) ) {
                    $link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
                }
            }

            echo '<a ' . alpus_escaped( $link_attrs ) . '>';
        }

        if ( empty( $banner_image['id'] ) ) {
            echo '<img src="' . esc_url( $banner_image['url'] ) . '" alt="' . esc_html__( 'Default Image', 'alpus-core' ) . '" width="1200" height="800">';
        } else {
            echo wp_get_attachment_image(
                $banner_image['id'],
                $banner_image_size,
                false,
                ''
            );
        }

        if ( ! empty( $img_link['url'] ) ) {
            echo '</a>';
        }
        echo '</div>';
    } elseif ( 'button' == $banner_item_type ) { // Button
        $class[] = ' btn';

        if ( $banner_btn_aclass ) {
            $class[] = $banner_btn_aclass;
        }

        if ( ! $banner_btn_text ) {
            $banner_btn_text = esc_html__( 'Click here', 'alpus-core' );
        }

        if ( $self ) {
            $repeater_setting_key = $self->get_repeater_setting_key( 'banner_btn_text', 'banner_item_list', $key );

            if ( ALPUS_NAME . '_widget_banner' == $self->get_name() ) {
                $self->add_inline_editing_attributes( $repeater_setting_key );
            }
            $banner_btn_text = alpus_widget_button_get_label( $item, $self, $banner_btn_text, $repeater_setting_key );
        }

        $class[] = implode( ' ', alpus_widget_button_get_class( $item ) );

        $attrs           = [];
        $attrs['href']   = ! empty( $banner_btn_link['url'] ) ? esc_url( $banner_btn_link['url'] ) : '#';
        $attrs['target'] = ! empty( $banner_btn_link['is_external'] ) ? '_blank' : '';
        $attrs['rel']    = ! empty( $banner_btn_link['nofollow'] ) ? 'nofollow' : '';

        if ( ! empty( $banner_btn_link['custom_attributes'] ) ) {
            foreach ( explode( ',', $banner_btn_link['custom_attributes'] ) as $attr ) {
                $key   = explode( '|', $attr )[0];
                $value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );

                if ( isset( $attrs[ $key ] ) ) {
                    $attrs[ $key ] .= ' ' . $value;
                } else {
                    $attrs[ $key ] = $value;
                }
            }
        }
        $link_attrs = '';

        foreach ( $attrs as $key => $value ) {
            if ( ! empty( $value ) ) {
                $link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
            }
        }

        printf( '<a class="' . esc_attr( implode( ' ', $class ) ) . '" ' . $link_attrs . $settings . '>%1$s</a>', alpus_strip_script_tags( $banner_btn_text ) );
    } else {
        $class[] = 'divider-wrap';
        echo '<div class="' . esc_attr( implode( ' ', $class ) ) . '" ' . alpus_escaped( $settings ) . '><hr class="divider" /></div>';
    }
}

if ( $_content_animation ) {
    echo '</div>';
}
echo '</div>'; // End banner-content

if ( $banner_wrap ) {
    echo '</div>'; // End banner-wrap
}

echo  '</div>';
