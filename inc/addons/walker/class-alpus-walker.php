<?php
/**
 * Alpus Menu Walker
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

class Alpus_Walker extends Alpus_Base {

    /**
         * Get the blocks.
         *
         * @since 1.0
         */
    private $blocks = array();

    /**
     * Constructor
     *
     * @since 1.0
     */
    public function __construct() {
        add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_walker_meta' ), 10, 1 );
        add_action( 'alpus_add_custom_fields', array( $this, 'add_custom_fields' ), 10, 4 );
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker' ), 10, 2 );
        add_action( 'wp_update_nav_menu_item', array( $this, 'update_walker' ), 10, 3 );

        if ( isset( $GLOBALS['pagenow'] ) && 'nav-menus.php' == $GLOBALS['pagenow'] ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        }

        $this->get_blocks();
    }

    /**
     * Get blocks
     *
     * @since 1.0
     */
    private function get_blocks() {
        $posts = get_posts(
            array(
                'post_type'   => ALPUS_NAME . '_template',
                'meta_key'    => ALPUS_NAME . '_template_type',
                'meta_value'  => 'block',
                'numberposts' => -1,
            )
        );

        sort( $posts );

        foreach ( $posts as $post ) {
            $this->blocks[ $post->ID ] = $post->post_title;
        }
    }

    /**
     * Decorates a menu item object with the shared navigation menu item properties.
     *
     * @param array $menu_item the menu item to modify
     *
     * @since 1.0
     */
    public function setup_walker_meta( $menu_item ) {
        $menu_item->nolink         = get_post_meta( $menu_item->ID, '_menu_item_nolink', true );
        $menu_item->megamenu       = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
        $menu_item->megamenu_width = get_post_meta( $menu_item->ID, '_menu_item_megamenu_width', true );
        $menu_item->megamenu_pos   = get_post_meta( $menu_item->ID, '_menu_item_megamenu_pos', true );
        $menu_item->image          = get_post_meta( $menu_item->ID, '_menu_item_image', true );
        $menu_item->block          = get_post_meta( $menu_item->ID, '_menu_item_block', true );
        $menu_item->icon           = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
        $menu_item->label_name     = get_post_meta( $menu_item->ID, '_menu_item_label_name', true );

        return $menu_item;
    }

    /**
     * Add custom fields
     *
     * @since 1.0
     */
    public function add_custom_fields( $id, $item, $depth, $args ) {
        ?>
		<p class="field-nolink description description-wide">
			<label for="edit-menu-item-nolink-<?php echo esc_attr( $item->ID ); ?>">
				<input type="checkbox" id="edit-menu-item-nolink-<?php echo esc_attr( $item->ID ); ?>" value="nolink" name="menu-item-nolink[<?php echo esc_attr( $item->ID ); ?>]"<?php checked( $item->nolink, 'nolink' ); ?> />
					<?php esc_html_e( 'No Link', 'alpus-core' ); ?>
			</label>
		</p>
		<p class="field-depth-warning field-warning-column description description-wide">
			<?php esc_html_e( 'The first child of the mega menu item doesn\'t appear in the menu.  It is only used for splitting it into columns.', 'alpus-core' ); ?>
		</p>
		<p class="field-depth-warning field-warning-heading description description-wide">
			<?php esc_html_e( 'It plays the role of a column(the parent of this item) heading.', 'alpus-core' ); ?>
		</p>
		<p class="field-megamenu description description-wide">
			<label for="edit-menu-item-megamenu-<?php echo esc_attr( $item->ID ); ?>">
				<input type="checkbox" id="edit-menu-item-megamenu-<?php echo esc_attr( $item->ID ); ?>" value="megamenu" name="menu-item-megamenu[<?php echo esc_attr( $item->ID ); ?>]"<?php checked( $item->megamenu, 'megamenu' ); ?> />
					<?php esc_html_e( 'Megamenu', 'alpus-core' ); ?>
			</label>
		</p>

		<p class="field-megamenu_width description description-wide">
			<label for="edit-menu-item-megamenu_width-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Megamenu Width (px)', 'alpus-core' ); ?>
				<input type="text" id="edit-menu-item-megamenu_width-<?php echo esc_attr( $item->ID ); ?>" class="widefat" value="<?php echo (int) '' !== $item->megamenu_width ? esc_attr( $item->megamenu_width ) : 618; ?>" name="menu-item-megamenu_width[<?php echo esc_attr( $item->ID ); ?>]" />
				<?php printf( esc_html( 'Input %1$szero%2$s to make full width megamenu', 'alpus-core' ), '<b style="color: #000;">', '</b>' ); ?>
			</label>
		</p>

		<p class="field-megamenu_pos description description-wide">
			<label for="edit-menu-item-megamenu_pos-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Megamenu Position', 'alpus-core' ); ?>
				<select id="edit-menu-item-megamenu_pos-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-custom" name="menu-item-megamenu_pos[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo intval( $item->megamenu_pos ); ?>">
					<option value="left" <?php selected( 'left', $item->megamenu_pos ); ?>><?php esc_html_e( 'Left', 'alpus-core' ); ?></option>
					<option value="center" <?php selected( 'center', $item->megamenu_pos ); ?>><?php esc_html_e( 'Center', 'alpus-core' ); ?></option>
					<option value="right" <?php selected( 'right', $item->megamenu_pos ); ?>><?php esc_html_e( 'Right', 'alpus-core' ); ?></option>
				</select>
			</label>
		</p>

		<p class="field-image description description-wide">
			<label for="edit-menu-item-image-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Flyout Menu Background', 'alpus-core' ); ?><br />
				<input type="text" id="edit-menu-item-image-<?php echo esc_attr( $item->ID ); ?>" class="widefat edit-menu-item-image"
					<?php if ( $item->image ) { ?>
						name="menu-item-image[<?php echo esc_attr( $item->ID ); ?>]"
					<?php } ?>
						data-name="menu-item-image[<?php echo esc_attr( $item->ID ); ?>]"
						value="<?php echo esc_attr( $item->image ); ?>" />
				<br/>
				<input class="btn_upload_img button" id="edit-menu-item-image-<?php echo esc_attr( $item->ID ); ?>" type="button" value="Upload Image" />&nbsp;
				<input class="btn_remove_img button" id="edit-menu-item-image-<?php echo esc_attr( $item->ID ); ?>" type="button" value="Remove Image" />
			</label>
			<?php esc_html_e( 'In the flyout menu, this image will be shown full screen when hovering the corresponding menu item.', 'alpus-core' ); ?>
		</p>

		<p class="field-block description description-wide">
			<label for="edit-menu-item-block-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Use Block', 'alpus-core' ); ?><br />
				<select id="edit-menu-item-block-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-custom" name="menu-item-block[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo intval( $item->block ); ?>">
					<option value=""><?php esc_html_e( 'Select A Block', 'alpus-core' ); ?></option>
					<?php
                    if ( $this->blocks ) {
                        foreach ( $this->blocks as $key => $value ) {
                            ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $item->block, $key ); ?>><?php echo esc_html( $value ); ?></option>
							<?php
                        }
                    }
        ?>
				</select>
			</label>
		</p>

		<p class="field-icon description description-wide">
			<label for="edit-menu-item-icon-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Prefix Icon Class', 'alpus-core' ); ?>
				<input type="text" id="edit-menu-item-icon-<?php echo esc_attr( $item->ID ); ?>" class="widefat" value="<?php echo esc_attr( $item->icon ); ?>" name="menu-item-icon[<?php echo esc_attr( $item->ID ); ?>]" />
				<?php echo ALPUS_DISPLAY_NAME . esc_html__( ' Icon Store might be useful for you to select icons.', 'alpus-core' ); ?>
			</label>
		</p>

		<p class="field-label_name description description-wide">
			<label for="edit-menu-item-label_name-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Add Label', 'alpus-core' ); ?><br />
				<select id="edit-menu-item-label_name-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-custom" name="menu-item-label_name[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo intval( $item->label_name ); ?>">
					<option value=""><?php esc_html_e( 'Select A Label', 'alpus-core' ); ?></option>
					<?php

        $labels = json_decode( alpus_get_option( 'menu_labels' ), true );

        if ( $labels ) {
            foreach ( $labels as $key => $value ) {
                ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $item->label_name, $key ); ?>><?php echo esc_html( $key ); ?></option>
							<?php
            }
        }
        ?>
				</select>
			</label>
		</p>
		<?php
    }

    /**
     * Filters the Walker class used when adding nav menu items
     *
     * @since 1.0
     */
    public function edit_walker() {
        require_once alpus_core_framework_path( ALPUS_CORE_ADDONS . '/walker/class-alpus-walker-nav-menu-edit.php' );

        return 'Alpus_Walker_Nav_Menu_Edit';
    }

    /**
     * Save the properties of a menu item or create a new one.
     *
     * @param int   $menu_id         The ID of the menu. Required. If "0", makes the menu item a draft orphan.
     * @param int   $menu_item_db_id The ID of the menu item. If "0", creates a new menu item.
     * @param array $args            the menu item's data
     *
     * @since 1.0
     */
    public function update_walker( $menu_id, $menu_item_db_id, $args ) {
        $customs = array( 'nolink', 'megamenu', 'megamenu_width', 'megamenu_pos', 'image', 'icon', 'label_name', 'block' );

        foreach ( $customs as $key ) {
            if ( ! isset( $_POST[ 'menu-item-' . $key ][ $menu_item_db_id ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                if ( ! isset( $args[ 'menu-item-' . $key ] ) ) {
                    $value = '';
                } else {
                    $value = $args[ 'menu-item-' . $key ];
                }
            } else {
                $value = sanitize_text_field( $_POST[ 'menu-item-' . $key ][ $menu_item_db_id ] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

                if ( 'block' == $key ) {
                }
            }

            if ( '' !== $value ) {
                update_post_meta( $menu_item_db_id, '_menu_item_' . $key, $value );
            } else {
                delete_post_meta( $menu_item_db_id, '_menu_item_' . $key );
            }
        }
    }

    /**
     * Enqueue script and style for Walker.
     *
     * @since 1.0
     */
    public function enqueue_scripts() {
        wp_enqueue_media();
        wp_enqueue_style( 'alpus-walker-admin', alpus_core_framework_uri( '/addons/walker/walker-admin.min.css' ), null, ALPUS_CORE_VERSION, 'all' );
        wp_enqueue_style( 'alpus-walker-admin-extend', alpus_core_framework_uri( '/addons/walker/walker-admin-extend.min.css' ), null, ALPUS_CORE_VERSION, 'all' );
        wp_enqueue_script( 'alpus-walker-admin', alpus_core_framework_uri( '/addons/walker/walker-admin' . ALPUS_JS_SUFFIX ), array( 'jquery-core' ), ALPUS_CORE_VERSION, true );
    }
}

Alpus_Walker::get_instance();
