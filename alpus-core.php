<?php
/*
Plugin Name: Alpus Core
Plugin URI: https://wordpress.org/plugins/alpus-core/
Description: Adds functionality such as Post Types, Widgets and Page Builders to Alpus Theme
Version: 0.2.0
Author: AlpusTheme
Author URI: https://alpustheme.com/
Text Domain: alpus-core
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
*/

// Direct load is not allowed
defined( 'ABSPATH' ) || die;

/* Define Constants                   */

defined( 'ALPUS_NAME' ) || define( 'ALPUS_NAME', 'alpha' );
defined( 'ALPUS_DISPLAY_NAME' ) || define( 'ALPUS_DISPLAY_NAME', 'Alpus' );
defined( 'ALPUS_ICON_PREFIX' ) || define( 'ALPUS_ICON_PREFIX', 'a' );
define( 'ALPUS_CORE_URI', untrailingslashit( plugin_dir_url( __FILE__ ) ) );               // Plugin directory uri
define( 'ALPUS_CORE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );             // Plugin directory path
define( 'ALPUS_CORE_FILE', __FILE__ );                                                     // Plugin file path
define( 'ALPUS_CORE_VERSION', '0.2.0' );                                                   // Plugin Version

defined( 'ALPUS_SERVER_URI' ) || define( 'ALPUS_SERVER_URI', 'https://alpustheme.com/' );                    // Server uri

// Defines core name and slug if not defined.
defined( 'ALPUS_CORE_NAME' ) || define( 'ALPUS_CORE_NAME', 'Alpus Core' );
defined( 'ALPUS_CORE_SLUG' ) || define( 'ALPUS_CORE_SLUG', 'alpus-core' );
defined( 'ALPUS_CORE_PLUGIN_URI' ) || define( 'ALPUS_CORE_PLUGIN_URI', 'alpus-core/alpus-core.php' );

// Define script debug
defined( 'ALPUS_JS_SUFFIX' ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? define( 'ALPUS_JS_SUFFIX', '.js' ) : define( 'ALPUS_JS_SUFFIX', '.min.js' ) );

if ( ! class_exists( 'Alpus_Base' ) ) {
    require_once ALPUS_CORE_PATH . '/framework/class-alpus-base.php';
}

require_once ALPUS_CORE_PATH . '/inc/config-function.php';

require_once ALPUS_CORE_PATH . '/framework/config.php';

if ( ! function_exists( 'alpus_plugin_framework_loader' ) ) {
    require_once ALPUS_CORE_PATH . '/plugin-framework/init.php';
}
alpus_plugin_framework_loader( ALPUS_CORE_PATH . '/' );

/**
 * Alpus Core Plugin Class
 *
 * @since 1.0
 */
class Alpus_Core {

    /**
         * Constructor
         *
         * @since 1.0
         */
    public function __construct() {
        // Load plugin
        add_action( 'plugins_loaded', array( $this, 'load' ) );
    }

    /**
     * Load required files
     *
     * @since 1.0
     *
     * @return void
     */
    public function load() {
        // Load text domain
        load_plugin_textdomain( 'alpus-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        // Check if theme activated
        if ( 'alpus' != get_template() && 'alpus-pro' != get_template() ) {
            return;
        }

        require_once ALPUS_CORE_PATH . '/inc/core-setup.php';
        require_once alpus_core_framework_path( ALPUS_CORE_FRAMEWORK_PATH . '/init.php' );
    }

    /**
     * Show in WP Dashboard notice about the alpus theme is not activated.
     *
     * @since 1.0
     *
     * @return void
     */
    public function theme_required() {
        $message  = '<p>' . sprintf( esc_html__( '%1$sAlpus Core%2$s requires Alpus to be your active theme. Please install & activate to continue.', 'alpus-core' ), '<strong>', '</strong>' ) . '</p>';
        echo '<div class="error">' . alpus_escaped( $message ) . '</div>';
    }
}

new Alpus_Core();
