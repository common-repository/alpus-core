<?php
/**
 * Config function
 *
 * You can override config function.
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
/**
 * For theme developers
 *
 * You can override framework file by helping this function.
 * If you want to override framework/init.php, create 'inc' directory just inside of theme
 * and here you create init.php too. As a result inc/init.php is called by below function.
 *
 * @param string $path full path of php, js, css file which is required
 *
 * @return string returns filtered path if $path exists in inc directory, raw path otherwise
 */
function alpus_core_framework_path( $path ) {
    if ( defined( 'ALPUS_PRO_VERSION' ) ) {
        if ( file_exists( str_replace( ALPUS_CORE_PATH . '/framework/', ALPUS_PRO_PATH . '/inc/', $path ) ) ) {
            return str_replace( ALPUS_CORE_PATH . '/framework/', ALPUS_PRO_PATH . '/inc/', $path );
        }

        if ( false !== strpos( ALPUS_CORE_PATH . '/inc/', $path ) && file_exists( str_replace( ALPUS_CORE_PATH . '/inc/', ALPUS_PRO_PATH . '/inc/', $path ) ) ) {
            return str_replace( ALPUS_CORE_PATH . '/inc/', ALPUS_PRO_PATH . '/inc/', $path );
        }
    }

    return file_exists( str_replace( '/framework/', '/inc/', $path ) ) ? str_replace( '/framework/', '/inc/', $path ) : $path;
}

/**
 * For theme developers
 *
 * You can override framework file by helping this function.
 * If you want to override framework/admin/admin.css, create 'inc' directory just inside of theme
 * and here you create admin/admin.css too. As a result inc/admin/admin.css is called by below function.
 *
 * @param string $short_path path in framework folder
 *
 * @return string returns filtered uri if path exists in inc directory, raw uri otherwise
 */
function alpus_core_framework_uri( $short_path ) {
    if ( defined( 'ALPUS_PRO_VERSION' ) && file_exists( ALPUS_PRO_PATH . '/inc' . $short_path ) ) {
        return ALPUS_PRO_URI . '/inc' . $short_path;
    }

    return file_exists( ALPUS_CORE_PATH . '/inc' . $short_path ) ? ALPUS_CORE_URI . '/inc' . $short_path : ALPUS_CORE_FRAMEWORK_URI . $short_path;
}
