<?php

// direct load is not allowed
defined( 'ABSPATH' ) || die;

class Alpus_Posts_Nav_Sidebar_Widget extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname'   => 'widget-posts-nav',
            'description' => esc_html__( 'Display posts navigation.', 'alpus-core' ),
        );

        $control_ops = array( 'id_base' => 'posts-nav-widget' );

        parent::__construct( 'posts-nav-widget', ALPUS_DISPLAY_NAME . esc_html__( ' - Posts Navigation', 'alpus-core' ), $widget_ops, $control_ops );
    }

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
        } else {
            echo alpus_strip_script_tags( $before_title ) . get_the_category_list( ' , ' ) . alpus_strip_script_tags( $after_title );
        }
        $post_id = get_the_ID();

        $args = array(
            'post_type'           => 'post',
            'posts_per_page'      => isset( $instance['count'] ) ? (int) $instance['count'] : 6,
            'orderby'             => isset( $instance['orderby'] ) ? $instance['orderby'] : '',
            'order'               => isset( $instance['orderway'] ) ? $instance['orderway'] : 'ASC',
            'ignore_sticky_posts' => 0,
            'category__in'        => wp_get_post_categories( $post_id ),
            'no_found_rows'       => true,
        );

        $posts = new WP_Query( $args );

        $count = count( $posts->posts );

        echo '<ul class="posts-nav">';

        if ( $posts->have_posts() ) {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                echo '<li' . ( get_the_ID() == $post_id ? ' class="active"' : '' ) . '><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></li>';
            }
        }
        echo '</ul>';

        echo alpus_strip_script_tags( $after_widget );
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']    = $new_instance['title'];
        $instance['orderby']  = $new_instance['orderby'];
        $instance['orderway'] = $new_instance['orderway'];
        $instance['count']    = $new_instance['count'];

        return $instance;
    }

    public function form( $instance ) {
        $defaults = array(
            'title'    => '',
            'orderby'  => '',
            'orderway' => 'ASC',
            'count'    => '20',
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<strong><?php esc_html_e( 'Title', 'alpus-core' ); ?>:</strong>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo isset( $instance['title'] ) ? esc_attr( sanitize_text_field( $instance['title'] ) ) : ''; ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
				<strong><?php esc_html_e( 'Order By', 'alpus-core' ); ?>:</strong>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" value="<?php echo isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : ''; ?>">
					<?php

                    echo '<option value=""' . selected( $instance['orderby'], '' ) . '>' . esc_html__( 'Default', 'alpus-core' );
        echo '<option value="ID"' . selected( $instance['orderby'], 'ID' ) . '>' . esc_html__( 'ID', 'alpus-core' );
        echo '<option value="title"' . selected( $instance['orderby'], 'title' ) . '>' . esc_html__( 'Title', 'alpus-core' );
        echo '<option value="date"' . selected( $instance['orderby'], 'date' ) . '>' . esc_html__( 'Date', 'alpus-core' );
        echo '<option value="modified"' . selected( $instance['orderby'], 'modified' ) . '>' . esc_html__( 'Modified', 'alpus-core' );
        echo '<option value="author"' . selected( $instance['orderby'], 'author' ) . '>' . esc_html__( 'Author', 'alpus-core' );
        echo '<option value="comment_count"' . selected( $instance['orderby'], 'comment_count' ) . '>' . esc_html__( 'Comment count', 'alpus-core' );

        ?>
				</select>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderway' ) ); ?>">
				<strong><?php esc_html_e( 'Order Way', 'alpus-core' ); ?>:</strong>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderway' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderway' ) ); ?>" value="<?php echo isset( $instance['orderway'] ) ? esc_attr( $instance['orderway'] ) : ''; ?>">
					<?php
        echo '<option value="ASC"' . selected( $instance['orderway'], 'ASC' ) . '>' . esc_html__( 'Ascending', 'alpus-core' ) . '</option>';
        echo '<option value="DESC"' . selected( $instance['orderway'], 'DESC' ) . '>' . esc_html__( 'Descending', 'alpus-core' ) . '</option>';
        ?>
				</select>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
				<strong><?php esc_html_e( 'Total Count', 'alpus-core' ); ?>:</strong>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $instance['count'] ); ?>" />
			</label>
		</p>
		<?php
    }
}
