<?php
/**
 * Alpus block sidebar widget
 *
 * @author     D-THEMES
 *
 * @since      1.0.0
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

class Alpus_Block_Sidebar_Widget extends WP_Widget {

    /**
         * Constructor
         *
         * @since 1.0
         */
    public function __construct() {
        $widget_ops = array(
            'classname'   => 'widget-block',
            'description' => sprintf( esc_html__( 'Display %s Block built by template block bilder', 'alpus-core' ), ALPUS_DISPLAY_NAME ),
        );

        $control_ops = array( 'id_base' => 'block-widget' );

        parent::__construct( 'block-widget', ALPUS_DISPLAY_NAME . esc_html__( ' - Block', 'alpus-core' ), $widget_ops, $control_ops );
    }

    /**
     * Output widget.
     *
     * @see WP_Widget
     *
     * @param array $args     the Arguments
     * @param array $instance the Widget instance
     */
    public function widget( $args, $instance ) {
        extract( $args ); // @codingStandardsIgnoreLine

        $title = '';

        if ( isset( $instance['title'] ) ) {
            $title = apply_filters( 'widget_title', $instance['title'] );
        }

        $output = '';
        echo alpus_strip_script_tags( $before_widget );

        if ( $title ) {
            echo alpus_strip_script_tags( $before_title . $title . $after_title );
        }

        if ( isset( $instance['id'] ) ) {
            echo do_shortcode( '[' . ALPUS_NAME . '_block name="' . $instance['id'] . '"]' );
        }

        echo alpus_strip_script_tags( $after_widget );
    }

    /**
     * Updates a particular instance of a widget.
     *
     * @see   WP_Widget->form
     *
     * @param array $new_instance the New Instance
     * @param array $old_instance the Old Instance
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = $new_instance['title'];
        $instance['id']    = $new_instance['id'];

        return $instance;
    }

    /**
     * Outputs the settings update form.
     *
     * @see WP_Widget->form
     *
     * @param array $instance instance
     */
    public function form( $instance ) {
        $defaults = array();
        $instance = wp_parse_args( (array) $instance, $defaults );

        $blocks = get_posts(
            array(
                'post_type'   => ALPUS_NAME . '_template',
                'meta_key'    => ALPUS_NAME . '_template_type',
                'meta_value'  => 'block',
                'numberposts' => -1,
            )
        );

        sort( $blocks );
        ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<strong><?php esc_html_e( 'Title', 'alpus-core' ); ?>:</strong>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo isset( $instance['title'] ) ? esc_attr( sanitize_text_field( $instance['title'] ) ) : ''; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>">
				<strong><?php esc_html_e( 'Select Block', 'alpus-core' ); ?>:</strong>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>" value="<?php echo isset( $instance['id'] ) ? esc_attr( $instance['id'] ) : ''; ?>">
					<?php
                    echo '<option value=""' . selected( ( isset( $instance['id'] ) ? $instance['id'] : '' ), '' ) . '>' . esc_attr( 'Select block to use', 'alpus-core' ) . '</option>';

        if ( ! empty( $blocks ) ) {
            foreach ( $blocks as $block ) {
                echo '<option value="' . esc_attr( $block->ID ) . '" ' . selected( ( isset( $instance['id'] ) ? $instance['id'] : '' ), $block->ID ) . '>' . esc_html( $block->post_title ) . '</option>';
            }
        }
        ?>
				</select>
			</label>
		</p>
		<?php
    }
}
