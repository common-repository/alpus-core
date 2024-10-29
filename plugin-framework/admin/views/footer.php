<?php
/**
 * Alpus Plugin Header
 *
 * @author AlpusTheme
 *
 * @version 1.0.0
 */
defined( 'ABSPATH' ) || die;

?>

<nav class="alpus-plugin-footer">
	<?php
    foreach ( $this->plugin_config['admin']['links'] as $key => $item ) {
        $url       = isset( $item['url'] ) ? $item['url'] : '#';
        $label     = isset( $item['label'] ) ? $item['label'] : '';
        $desc      = isset( $item['description'] ) ? $item['description'] : '';
        $button    = isset( $item['button'] ) ? $item['button'] : '#';
        $icon      = isset( $item['icon'] ) ? $item['icon'] : '';
        $icon_html = ! isset( $item['is_svg'] ) ? '<i class="' . esc_attr( $icon ) . '"></i>' : esc_html( $icon );

        echo '<div class="alpus-external-link">';
        echo '<div class="external-link-box">';
        echo '<h3>' . alpus_escaped( $icon_html ) . esc_html( $label ) . '</h3>';
        echo '<p>' . alpus_escaped( $desc ) . '</p>';
        echo '<a href="' . esc_url( $url ) . '" class="button-primary" target="_blank">' . alpus_escaped( $button ) . '</a>';
        echo '</div>';
        echo '</div>';
    }
?>
</nav>
