<?php
/**
 * InfoBox Shortcode Render
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
use Elementor\Icons_Manager;

if ( method_exists( $this, 'get_settings_for_display' ) ) { // for elementor widget
    extract( // @codingStandardsIgnoreLine
        shortcode_atts(
            array(
                'icon_position'        => 'top',
                'selected_icon'        => array( 'value' => 'fas fa-star' ),
                'title_text'           => esc_html__( 'This is the heading', 'alpus-core' ),
                'description_text'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'alpus-core' ),
                'show_button'          => '',
                'button_label'         => esc_html__( 'Read More', 'alpus-core' ),
                'link'                 => '',
                'info_box_icon_type'   => '',
                'info_box_icon_hover'  => '',
                'info_box_hover'       => '',
                'info_box_icon_shape'  => '',
                'info_box_icon_shadow' => '',
                'title_html_tag'       => 'h3',
            ),
            $atts
        )
    );

    $wrapper_cls = array( 'icon-box' );

    if ( 'top' != $icon_position && 'bottom' != $icon_position ) {
        $wrapper_cls[] = 'icon-box-side';
    }
    $wrapper_cls[] = 'position-' . $icon_position;

    $wrapper_cls[] = 'icon-' . $info_box_icon_type;

    if ( 'yes' == $info_box_icon_shadow ) {
        $wrapper_cls[] = 'icon-box-icon-shadow';
    }

    if ( $info_box_icon_shape ) {
        $wrapper_cls[] = 'shape-' . $info_box_icon_shape;
    }

    if ( 'default' != $info_box_icon_type ) {
        if ( $info_box_icon_hover ) {
            $wrapper_cls[] = 'hover-overlay';
            $wrapper_cls[] = 'hover-' . $info_box_icon_type;
        }
    }

    if ( $info_box_hover ) {
        $wrapper_cls[] = $info_box_hover;
    }

    $link_attr_ary           = [];
    $link_attr_ary['href']   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $link_attr_ary['target'] = ! empty( $link['is_external'] ) ? '_blank' : '';
    $link_attr_ary['rel']    = ! empty( $link['nofollow'] ) ? 'nofollow' : '';

    if ( ! empty( $link['custom_attributes'] ) ) {
        foreach ( explode( ',', $link['custom_attributes'] ) as $attr ) {
            $key   = explode( '|', $attr )[0];
            $value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );

            if ( isset( $link_attr_ary[ $key ] ) ) {
                $link_attr_ary[ $key ] .= ' ' . $value;
            } else {
                $link_attr_ary[ $key ] = $value;
            }
        }
    }
    $link_attr = '';

    foreach ( $link_attr_ary as $key => $value ) {
        if ( ! empty( $value ) ) {
            $link_attr .= $key . '="' . esc_attr( $value ) . '" ';
        }
    }

    $link_open  = empty( $link['url'] ) ? '' : '<a class="link" ' . $link_attr . '>';
    $link_close = empty( $link['url'] ) ? '' : '</a>';

    echo '<div class="' . esc_attr( implode( ' ', $wrapper_cls ) ) . '">';

    if ( $link['url'] ) {
        echo alpus_escaped( $link_open . $link_close );
    }
    ob_start();

    echo '<div class="icon-box-feature">';

    if ( 'svg' == $selected_icon['library'] ) {
        Icons_Manager::render_icon( $selected_icon, array( 'aria-hidden' => 'true' ) );
    } else {
        echo '<i class="' . esc_attr( $selected_icon['value'] ) . '"></i>';
    }

    echo '</div>';

    $icon_html = ob_get_clean();

    if ( 'bottom' != $icon_position ) {
        echo alpus_strip_script_tags( $icon_html );
    }

    echo '<div class="icon-box-content">';

    if ( $title_text ) {
        $this->add_render_attribute( 'title_text', 'class', 'icon-box-title' );
        echo '<' . alpus_escaped( $title_html_tag ) . ' ' . $this->get_render_attribute_string( 'title_text' ) . '>' . alpus_escaped( $link_open ) . alpus_strip_script_tags( $title_text ) . alpus_escaped( $link_close ) . '</' . alpus_escaped( $title_html_tag ) . '>';
    }

    if ( $description_text ) {
        $this->add_render_attribute( 'description_text', 'class', 'icon-box-desc' );
        echo '<p ' . $this->get_render_attribute_string( 'description_text' ) . '>' . alpus_strip_script_tags( $description_text ) . '</p>';
    }

    if ( 'yes' == $show_button && $button_label ) {
        $button_label = alpus_widget_button_get_label( $atts, $this, $button_label, 'button_label' );
        $class[]      = 'btn';
        $class[]      = implode( ' ', alpus_widget_button_get_class( $atts ) );

        $this->add_inline_editing_attributes( 'button_label' );

        printf( '<a class="' . esc_attr( implode( ' ', $class ) ) . '" href="' . ( empty( $link['url'] ) ? '#' : esc_url( $link['url'] ) ) . '" ' . ( ! empty( $link['is_external'] ) ? ' target="nofollow"' : '' ) . ( ! empty( $link['nofollow'] ) ? ' rel="_blank"' : '' ) . '>%1$s</a>', alpus_strip_script_tags( $button_label ) );
    }

    echo '</div>';

    if ( 'bottom' == $icon_position ) {
        echo alpus_strip_script_tags( $icon_html );
    }

    echo '</div>';
} else { // for gutenberg block
    extract( // @codingStandardsIgnoreLine
        shortcode_atts(
            array(
                'icon'       => 'fas fa-star',
                'icon_view'  => '',
                'icon_shape' => 'icon-circle',
                'title'      => '',
                'desc'       => '',
                'link'       => '',
                'icon_pos'   => '',
                'title_tag'  => 'h3',
                'wrap_class' => '',
            ),
            $atts
        )
    );

    // dynamic content
    if ( ! empty( $atts['icon_source'] ) && ! empty( $atts['icon_dynamic_content'] ) && ! empty( $atts['icon_dynamic_content']['source'] ) ) {
        $icon = apply_filters( 'alpus_dynamic_tags_content', '', null, $atts['icon_dynamic_content'] );
    }

    if ( ! empty( $atts['title_source'] ) && ! empty( $atts['title_dynamic_content'] ) && ! empty( $atts['title_dynamic_content']['source'] ) ) {
        $title = apply_filters( 'alpus_dynamic_tags_content', '', null, $atts['title_dynamic_content'] );
    }

    if ( ! empty( $atts['desc_source'] ) && ! empty( $atts['desc_dynamic_content'] ) && ! empty( $atts['desc_dynamic_content']['source'] ) ) {
        $desc = apply_filters( 'alpus_dynamic_tags_content', '', null, $atts['desc_dynamic_content'] );
    }

    if ( ! empty( $atts['link_source'] ) && ! empty( $atts['link_dynamic_content'] ) && ! empty( $atts['link_dynamic_content']['source'] ) ) {
        $link = apply_filters( 'alpus_dynamic_tags_content', '', null, $atts['link_dynamic_content'] );
    }

    if ( ! empty( $icon_view ) ) {
        $wrap_class .= ' ' . $icon_view;
    }

    if ( ! empty( $icon_shape ) ) {
        $wrap_class .= ' ' . $icon_shape;
    }

    if ( ! empty( $icon_pos ) ) {
        $wrap_class .= ' ' . $icon_pos;
    }
    wp_enqueue_style( 'alpus-icon-box', alpus_core_framework_uri( '/widgets/icon-box/icon-box' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPUS_CORE_VERSION );

    ?>
	<div class="<?php echo esc_attr( 'icon-box ' . $wrap_class ); ?>">
		<div class="icon-box-icon">
			<?php if ( ! empty( $link ) ) { ?>
				<a href="<?php echo esc_url( $link ); ?>">
					<i class="<?php echo esc_attr( $icon ); ?>"></i>
				</a>
			<?php } else { ?>
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
			<?php } ?>
		</div>
		<div class="icon-box-content">
			<<?php echo esc_html( $title_tag ); ?> class="icon-box-title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
			<p><?php echo esc_html( $desc ); ?></p>
		</div>
	</div>
	<?php
}
