<?php
/**
 * Main Custom admin functions area
 *
 * @since SparklewpThemes
 *
 * @param SparkleStore
 *
*/
if( !function_exists('sparklestore_file_directory') ){

    function sparklestore_file_directory( $file_path ){
        if( file_exists( trailingslashit( get_stylesheet_directory() ) . $file_path) ) {
            return trailingslashit( get_stylesheet_directory() ) . $file_path;
        }
        else{
            return trailingslashit( get_template_directory() ) . $file_path;
        }
    }
}

/**
 * Load Custom Admin functions that act independently of the theme functions.
*/
require sparklestore_file_directory('sparklethemes/functions.php');

/**
 * Implement the Custom Header feature.
*/
require sparklestore_file_directory('sparklethemes/core/custom-header.php');

/**
 * Custom template tags for this theme.
*/
require sparklestore_file_directory('sparklethemes/core/template-tags.php');

/**
 * Custom functions that act independently of the theme templates.
*/
require sparklestore_file_directory('sparklethemes/core/extras.php');

/**
 * Customizer additions.
*/
require sparklestore_file_directory('sparklethemes/customizer/customizer.php');

/**
 * Load Jetpack compatibility file.
*/
require sparklestore_file_directory('sparklethemes/core/jetpack.php');

/**
 * Load Sparklestore Suggest Plugins Activation file.
*/
require sparklestore_file_directory('sparklethemes/core/sparklestore-plugin-activation.php');

/**
 * Load widget compatibility field file.
*/
require sparklestore_file_directory('sparklethemes/sparkle-widgets/widgets-fields.php');

/**
 * Load header hooks file.
*/
require sparklestore_file_directory('sparklethemes/hooks/header.php');

/**
 * Load footer hooks file.
*/
require sparklestore_file_directory('sparklethemes/hooks/footer.php');

/**
 * Load woocommerce hooks file.
*/
require sparklestore_file_directory('sparklethemes/hooks/woocommerce.php');

/**
 * Load theme about page
*/
require sparklestore_file_directory('sparklethemes/admin/about-theme/sparklestore-about.php');