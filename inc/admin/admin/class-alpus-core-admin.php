<?php
/**
 * Alpus Customizer
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpus_Core_Admin' ) ) {
    class Alpus_Core_Admin extends Alpus_Base {

        /**
                 * The Constructor.
                 *
                 * @since 1.0
                 */
        public function __construct() {
            add_filter( 'alpus_admin_config', array( $this, 'admin_config' ), 10, 2 );
            add_filter( 'alpus_demo_types', array( $this, 'demo_types' ) );

            add_action( 'after_setup_theme', function () {
                if ( is_admin_bar_showing() && defined( 'ALPUS_VERSION' ) ) {
                    add_action( 'wp_before_admin_bar_render', array( $this, 'add_wp_toolbar_menu' ) );
                }
            } );
        }

        /**
         * Get demo types for import in setup wizard.
         *
         * @see framework/admin/setup-wizard/class-alpus-setup-wizard.php
         * @since 1.0
         */
        public function demo_types( $demo ) {
            return $demo;
        }

        /**
         * Get the admin config.
         *
         * @since 1.0
         */
        public function admin_config( $config, $self ) {
            unset( $config['links']['showcase'] );
            $config['admin_navs'] = array(
                'dashboard'     => array(
                    'icon'  => 'fas fa-tachometer-alt',
                    'label' => esc_html__( 'Dashboard', 'alpus-core' ),
                    'url'   => admin_url( 'admin.php?page=alpus' ),
                ),
                'management'    => array(
                    'icon'    => 'fas fa-cog',
                    'label'   => esc_html__( 'Management', 'alpus-core' ),
                    'submenu' => array(
                        'setup_wizard'    => array(
                            'label' => esc_html__( 'Setup Wizard', 'alpus-core' ),
                            'icon'  => 'fas fa-user-cog',
                            'url'   => admin_url( 'admin.php?page=alpus-setup-wizard' ),
                            'desc'  => esc_html__( 'Setup your site quickly.', 'alpus-core' ),
                        ),
                        'optimize_wizard' => array(
                            'label' => esc_html__( 'Optimize Wizard', 'alpus-core' ),
                            'icon'  => 'fas fa-rocket',
                            'url'   => admin_url( 'admin.php?page=alpus-optimize-wizard' ),
                            'desc'  => esc_html__( 'Enhance your site spped.', 'alpus-core' ),
                        ),
                        'tools'           => array(
                            'label' => esc_html__( 'Tools', 'alpus-core' ),
                            'icon'  => 'fas fa-puzzle-piece',
                            'url'   => admin_url( 'admin.php?page=alpus-tools' ),
                            'desc'  => esc_html__( 'Keep your site healthy.', 'alpus-core' ),
                        ),
                    ),
                ),
                'layouts'       => array(
                    'icon'    => 'fas fa-layer-group',
                    'label'   => esc_html__( 'Layouts', 'alpus-core' ),
                    'submenu' => array(
                        'layout_builder' => array(
                            'label' => esc_html__( 'Layout Builder', 'alpus-core' ),
                            'icon'  => 'fas fa-object-group',
                            'url'   => admin_url( 'admin.php?page=alpus-layout-builder' ),
                            'desc'  => esc_html__( 'Edit your site layouts.', 'alpus-core' ),
                        ),
                    ),
                ),
                'theme_options' => array(
                    'icon'  => 'fas fa-users-cog',
                    'label' => esc_html__( 'Theme Options', 'alpus-core' ),
                    'url'   => admin_url( 'customize.php' ),
                ),
            );

            if ( $self->is_registered() ) {
                $config['admin_navs']['management']['submenu']['patcher'] = array(
                    'label' => esc_html__( 'Patcher', 'alpus-core' ),
                    'icon'  => 'fas fa-tools',
                    'url'   => admin_url( 'admin.php?page=alpus-patcher' ),
                    'desc'  => esc_html__( 'Keep up-to-date.', 'alpus-core' ),
                );
                $config['admin_navs']['management']['submenu']['rollback'] = array(
                    'label' => esc_html__( 'Rollback', 'alpus-core' ),
                    'icon'  => 'fas fa-arrow-alt-circle-down',
                    'url'   => admin_url( 'admin.php?page=alpus-rollback' ),
                    'desc'  => esc_html__( 'Rollback to previous versions.', 'alpus-core' ),
                );

                if ( alpus_get_option( 'resource_critical_css' ) && class_exists( 'Alpus_Critical' ) ) {
                    $config['admin_navs']['management']['submenu']['critical_css'] = array(
                        'label' => esc_html__( 'Critical CSS', 'alpus-core' ),
                        'icon'  => 'fab fa-critical-role',
                        'url'   => admin_url( 'admin.php?page=alpus-critical' ),
                        'desc'  => esc_html__( 'Generate Critical CSS.', 'alpus-core' ),
                    );
                }
            }

            if ( class_exists( 'Alpus_Builders' ) ) {
                $config['admin_navs']['layouts']['submenu']['templates_builder'] = array(
                    'label' => esc_html__( 'Templates', 'alpus-core' ),
                    'icon'  => 'fas fa-pencil-ruler',
                    'url'   => admin_url( 'edit.php?post_type=' . ALPUS_NAME . '_template' ),
                    'desc'  => esc_html__( 'Create specific site templates.', 'alpus-core' ),
                );
            }

            if ( class_exists( 'Alpus_Sidebar_Builder' ) ) {
                $config['admin_navs']['layouts']['submenu']['sidebars_builder'] = array(
                    'label'  => esc_html__( 'Sidebars', 'alpus-core' ),
                    'icon'   => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 70 70" enable-background="new 0 0 70 70" xml:space="preserve">
						<rect x="3.5" y="3.5" width="17.5" height="63"/>
						<rect x="27.8" y="49.6" width="38.7" height="16.9"/>
						<rect x="27.8" y="3.5" width="38.7" height="39.5"/>
					</svg>',
                    'is_svg' => true,
                    'url'    => admin_url( 'admin.php?page=alpus-sidebar' ),
                    'desc'   => esc_html__( 'Create unlimited sidebars.', 'alpus-core' ),
                );
            }

            return $config;
        }

        /**
         * Add Alpus menu items to WordPress admin menu.
         *
         * @since 1.0
         */
        public function add_wp_toolbar_menu() {
            $target = is_admin() ? '_self' : '_blank';

            if ( current_user_can( 'edit_theme_options' ) ) {
                $title = sprintf( esc_html__( '%s', 'alpus-core' ), alpus_get_option( 'white_label_title' ) ); //phpcs:ignore
                $icon  = sprintf( esc_html__( '%s', 'alpus-core' ), alpus_get_option( 'white_label_icon' ) ); //phpcs:ignore
                $this->add_wp_toolbar_menu_item(
                    '<span class="ab-icon dashicons ' . ( ! $icon ? 'dashicons-alpus-logo">' : 'custom-mini-logo" style="background-image: url(' . esc_attr( $icon ) . ') !important; background-size: 20px 20px; background-repeat: no-repeat; background-position: center; width: 20px; height: 20px;">' ) . '</span><span class="ab-label">' . ( $title ? esc_html( $title ) : ALPUS_DISPLAY_NAME ) . '</span>',
                    false,
                    esc_url( admin_url( 'admin.php?page=alpus' ) ),
                    array(
                        'class'  => 'alpus-menu',
                        'target' => $target,
                    ),
                    'alpus'
                );

                // License

                $this->add_wp_toolbar_menu_item(
                    esc_html__( 'Dashboard', 'alpus-core' ),
                    'alpus',
                    esc_url( admin_url( 'admin.php?page=alpus' ) ),
                    array(
                        'target' => $target,
                    )
                );

                // Theme Options

                $this->add_wp_toolbar_menu_item(
                    esc_html__( 'Theme Options', 'alpus-core' ),
                    'alpus',
                    esc_url( admin_url( 'customize.php' ) ),
                    array(
                        'target' => $target,
                    )
                );

                // Management Submenu

                if ( class_exists( 'Alpus_Setup_Wizard' ) ) {
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Setup Wizard', 'alpus-core' ),
                        'alpus',
                        esc_url( admin_url( 'admin.php?page=alpus-setup-wizard' ) ),
                        array(
                            'target' => $target,
                        ),
                        'alpus_setup'
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Status', 'alpus-core' ),
                        'alpus_setup',
                        esc_url( admin_url( 'admin.php?page=alpus-setup-wizard' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Child Theme', 'alpus-core' ),
                        'alpus_setup',
                        esc_url( admin_url( 'admin.php?page=alpus-setup-wizard&step=customize' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Plugins', 'alpus-core' ),
                        'alpus_setup',
                        esc_url( admin_url( 'admin.php?page=alpus-setup-wizard&step=default_plugins' ) ),
                        array(
                            'target' => $target,
                        ),
                        'alpus_setup_plugins'
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Demo', 'alpus-core' ),
                        'alpus_setup',
                        esc_url( admin_url( 'admin.php?page=alpus-setup-wizard&step=demo_content' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Ready', 'alpus-core' ),
                        'alpus_setup',
                        esc_url( admin_url( 'admin.php?page=alpus-setup-wizard&step=ready' ) ),
                        array(
                            'target' => $target,
                        ),
                        'alpus_setup_ready'
                    );
                }

                // Optimize Wizard
                if ( class_exists( 'Alpus_Optimize_Wizard' ) ) {
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Optimize Wizard', 'alpus-core' ),
                        'alpus',
                        esc_url( admin_url( 'admin.php?page=alpus-optimize-wizard' ) ),
                        array(
                            'target' => $target,
                        ),
                        'alpus_optimize'
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Resources', 'alpus-core' ),
                        'alpus_optimize',
                        esc_url( admin_url( 'admin.php?page=alpus-optimize-wizard' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Lazyload', 'alpus-core' ),
                        'alpus_optimize',
                        esc_url( admin_url( 'admin.php?page=alpus-optimize-wizard&step=lazyload' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Performance', 'alpus-core' ),
                        'alpus_optimize',
                        esc_url( admin_url( 'admin.php?page=alpus-optimize-wizard&step=performance' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Plugins', 'alpus-core' ),
                        'alpus_optimize',
                        esc_url( admin_url( 'admin.php?page=alpus-optimize-wizard&step=plugins' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Ready', 'alpus-core' ),
                        'alpus_optimize',
                        esc_url( admin_url( 'admin.php?page=alpus-optimize-wizard&step=ready' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                }

                // Layouts
                $this->add_wp_toolbar_menu_item(
                    esc_html__( 'Layouts', 'alpus-core' ),
                    'alpus',
                    esc_url( admin_url( 'admin.php?page=alpus-layout-builder' ) ),
                    array(
                        'target' => $target,
                    ),
                    'alpus_layouts'
                );

                $this->add_wp_toolbar_menu_item(
                    esc_html__( 'Layout Builder', 'alpus-core' ),
                    'alpus_layouts',
                    esc_url( admin_url( 'admin.php?page=alpus-layout-builder' ) ),
                    array(
                        'target' => $target,
                    )
                );

                if ( class_exists( 'Alpus_Builders' ) ) {
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'All Templates', 'alpus-core' ),
                        'alpus_layouts',
                        esc_url( admin_url( 'edit.php?post_type=' . ALPUS_NAME . '_template' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                }

                if ( class_exists( 'Alpus_Sidebar_Builder' ) ) {
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Sidebars', 'alpus-core' ),
                        'alpus_layouts',
                        esc_url( admin_url( 'admin.php?page=alpus-sidebar' ) ),
                        array(
                            'target' => $target,
                        )
                    );
                }

                if ( class_exists( 'Alpus_Builders' ) ) {
                    global $alpus_layout;

                    if ( ! empty( $alpus_layout['used_blocks'] ) && count( $alpus_layout['used_blocks'] ) ) {
                        $used_templates = $alpus_layout['used_blocks'];

                        foreach ( $used_templates as $template_id => $data ) {
                            $template_type = get_post_meta( $template_id, ALPUS_NAME . '_template_type', true );

                            if ( ! $template_type ) {
                                $template_type = 'block';
                            }

                            $template = get_post( $template_id );

                            if ( alpus_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $template_id, '_elementor_edit_mode', true ) ) {
                                $edit_link = admin_url( 'post.php?post=' . $template_id . '&action=elementor' );
                            } else {
                                $edit_link = admin_url( 'post.php?post=' . $template_id . '&action=edit' );
                            }

                            if ( $template ) {
                                $this->add_wp_toolbar_menu_item(
                                    // translators: %s represents template title.
                                    '<span class="alpus-ab-template-title">' . sprintf( esc_html__( 'Edit %s', 'alpus-core' ), $template->post_title ) . '</span><span class="alpus-ab-template-type">' . sprintf( esc_html__( '%s', 'alpus-core' ), str_replace( '_', ' ', $template_type ) ) . '</span>', //phpcs:ignore
                                    'edit',
                                    esc_url( $edit_link ),
                                    array(
                                        'target' => $target,
                                    ),
                                    'edit_' . ALPUS_NAME . '_template_' . $template_id
                                );
                            }
                        }
                    }
                }

                // Tools
                if ( class_exists( 'Alpus_Tools' ) ) {
                    $this->add_wp_toolbar_menu_item(
                        esc_html__( 'Tools', 'alpus-core' ),
                        'alpus',
                        esc_url( admin_url( 'admin.php?page=alpus-tools' ) ),
                        array(
                            'target' => $target,
                        ),
                        'alpus_tools'
                    );

                    if ( class_exists( 'Alpus_Admin' ) && Alpus_Admin::get_instance()->is_registered() ) {
                        $this->add_wp_toolbar_menu_item(
                            esc_html__( 'Tools', 'alpus-core' ),
                            'alpus_tools',
                            esc_url( admin_url( 'admin.php?page=alpus-tools' ) ),
                            array(
                                'target' => $target,
                            ),
                        );
                    }
                }

                if ( class_exists( 'Alpus_Admin' ) && Alpus_Admin::get_instance()->is_registered() ) {
                    if ( class_exists( 'Alpus_Critical' ) && alpus_get_option( 'resource_critical_css' ) ) {
                        $this->add_wp_toolbar_menu_item(
                            esc_html__( 'Critical CSS', 'alpus-core' ),
                            'alpus_tools',
                            esc_url( admin_url( 'admin.php?page=alpus-critical' ) ),
                            array(
                                'target' => $target,
                            )
                        );
                    }

                    if ( class_exists( 'Alpus_Patcher' ) ) {
                        $this->add_wp_toolbar_menu_item(
                            esc_html__( 'Patcher', 'alpus-core' ),
                            'alpus_tools',
                            esc_url( admin_url( 'admin.php?page=alpus-patcher' ) ),
                            array(
                                'target' => $target,
                            )
                        );
                    }

                    if ( class_exists( 'Alpus_Rollback' ) ) {
                        $this->add_wp_toolbar_menu_item(
                            esc_html__( 'Rollback', 'alpus-core' ),
                            'alpus_tools',
                            esc_url( admin_url( 'admin.php?page=alpus-rollback' ) ),
                            array(
                                'target' => $target,
                            )
                        );
                    }
                }

                /*
                 * Fires after add toolbar menu.
                 *
                 * @since 1.0
                 */
                do_action( 'alpus_add_wp_toolbar_menu', $this );
            }
        }

        /**
         * Add Alpus menu items to WordPress admin menu.
         *
         * @param string $title       Title of menu item
         * @param string $parent      Parent Menu id
         * @param string $href        Link of menu item
         * @param array  $custom_meta Metadata for link
         * @param string $custom_id   Menu id
         *
         * @since 1.0
         */
        public function add_wp_toolbar_menu_item( $title, $parent = false, $href = '', $custom_meta = array(), $custom_id = '' ) {
            global $wp_admin_bar;

            if ( current_user_can( 'edit_theme_options' ) ) {
                if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
                    return;
                }

                // Set custom ID
                if ( $custom_id ) {
                    $id = $custom_id;
                } else { // Generate ID based on $title
                    $id = strtolower( str_replace( ' ', '-', $title ) );
                }
                // links from the current host will open in the current window
                $meta = strpos( $href, home_url() ) !== false ? array() : array( 'target' => '_blank' ); // external links open in new $targetw

                $meta = array_merge( $meta, $custom_meta );
                $wp_admin_bar->add_node(
                    array(
                        'parent' => $parent,
                        'id'     => $id,
                        'title'  => $title,
                        'href'   => $href,
                        'meta'   => $meta,
                    )
                );
            }
        }

        /**
         * Show activation notice.
         *
         * @since 1.0
         */
        public function show_activation_notice() {
            if ( ! $this->is_registered() ) {
                if ( ( 'themes.php' == $GLOBALS['pagenow'] && isset( $_GET['page'] ) ) ||
                    empty( $_COOKIE['alpus_dismiss_activate_msg'] ) ||
                    version_compare( sanitize_text_field( $_COOKIE['alpus_dismiss_activate_msg'] ), ALPUS_VERSION, '<' )
                ) {
                    add_action( 'admin_notices', array( $this, 'activation_notices' ) );
                } elseif ( 'themes.php' == $GLOBALS['pagenow'] ) {
                    add_action( 'admin_footer', array( $this, 'activation_message' ) );
                }
            }
        }

        /**
         * Show activation message.
         *
         * @since 1.0
         */
        public function activation_message() {
            ?>
			<script>
				(function($){
					$(window).on( 'load', function() {
						<?php /* translators: $1 and $2 are opening and closing anchor tags respectively */ ?>
						$('.themes .theme.active .theme-screenshot').after('<div class="notice update-message notice-error notice-alt"><p><?php printf( esc_html__( 'Please %1$sverify purchase%2$s to get updates!', 'alpus-core' ), '<a href="admin.php?page=alpus" class="button-link">', '</a>' ); ?></p></div>');
					});
				})(window.jQuery);
			</script>
			<?php
        }

        /**
         * Show activation notices.
         *
         * @since 1.0
         */
        public function activation_notices() {
            ?>
			<div class="notice error notice-error is-dismissible">
				<?php /* translators: $1, $2 and $3 opening and closing strong tags respectively */ ?>
				<p><?php printf( esc_html__( 'Please %1$sregister%2$s %3$s theme to get access to pre-built demo websites and auto updates.', 'alpus-core' ), '<a href="admin.php?page=alpus">', '</a>', ALPUS_DISPLAY_NAME ); ?></p>
				<?php /* translators: $1 and $2 opening and closing strong tags respectively, and $3 and $4 are opening and closing anchor tags respectively */ ?>
				<p><?php printf( esc_html__( '%1$s Important! %2$s One %3$s standard license %4$s is valid for only %1$s1 website%2$s. Running multiple websites on a single license is a copyright violation.', 'alpus-core' ), '<strong>', '</strong>', '<a target="_blank" href="https://themeforest.net/licenses/standard">', '</a>' ); ?></p>
				<button type="button" class="notice-dismiss alpus-notice-dismiss"><span class="screen-reader-text"><?php esc_html__( 'Dismiss this notice.', 'alpus-core' ); ?></span></button>
			</div>
			<script>
				(function($) {
					var setCookie = function (name, value, exdays) {
						var exdate = new Date();
						exdate.setDate(exdate.getDate() + exdays);
						var val = encodeURIComponent(value) + ((null === exdays) ? "" : "; expires=" + exdate.toUTCString());
						document.cookie = name + "=" + val;
					};
					$(document).on('click.alpus-notice-dismiss', '.alpus-notice-dismiss', function(e) {
						e.preventDefault();
						var $el = $(this).closest('.notice');
						$el.fadeTo( 100, 0, function() {
							$el.slideUp( 100, function() {
								$el.remove();
							});
						});
						setCookie('alpus_dismiss_activate_msg', '<?php echo ALPUS_VERSION; ?>', 30);
					});
				})(window.jQuery);
			</script>
			<?php
        }
    }
}

Alpus_Core_Admin::get_instance();
