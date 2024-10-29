<?php
/**
 * Alpus IconList Widget Render
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

$settings          = $this->get_settings_for_display();
$fallback_defaults = array(
    ALPUS_ICON_PREFIX . '-icon-heart',
    ALPUS_ICON_PREFIX . '-icon-star',
    ALPUS_ICON_PREFIX . '-icon-cog',
);

$this->add_render_attribute( 'icon_list', 'class', 'elementor-icon-list-items' );
$this->add_render_attribute( 'list_item', 'class', 'elementor-icon-list-item' );

if ( 'inline' === $settings['view'] ) {
    $this->add_render_attribute( 'icon_list', 'class', 'elementor-inline-items' );
    $this->add_render_attribute( 'list_item', 'class', 'elementor-inline-item' );
}
?>
<ul <?php $this->print_render_attribute_string( 'icon_list' ); ?>>
	<?php
    foreach ( $settings['icon_list'] as $index => $item ) {
        $repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $index );

        $this->add_render_attribute( $repeater_setting_key, 'class', 'elementor-icon-list-text' );

        $this->add_inline_editing_attributes( $repeater_setting_key );

        $migration_allowed = Elementor\Icons_Manager::is_migration_allowed();
        ?>
		<li <?php $this->print_render_attribute_string( 'list_item' ); ?>>
			<?php
            if ( ! empty( $item['link']['url'] ) ) {
                $link_key = 'link_' . $index;

                $this->add_link_attributes( $link_key, $item['link'] );

                echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
            }

            // add old default
            if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
                $item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : ALPUS_ICON_PREFIX . '-icon-check';
            }

            $migrated = isset( $item['__fa4_migrated']['selected_icon'] );
        $is_new       = ! isset( $item['icon'] ) && $migration_allowed;

        if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) {
            ?>
				<span class="elementor-icon-list-icon">
				<?php
            if ( $is_new || $migrated ) {
                Elementor\Icons_Manager::render_icon( $item['selected_icon'], array( 'aria-hidden' => 'true' ) );
            } else {
                ?>
					<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
					<?php
            }
            ?>
				</span>
			<?php } ?>
			<span <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>><?php echo alpus_strip_script_tags( $item['text'] ); ?></span>
			<?php if ( ! empty( $item['link']['url'] ) ) { ?>
				</a>
			<?php } ?>
		</li>
		<?php
    }
?>
</ul>
