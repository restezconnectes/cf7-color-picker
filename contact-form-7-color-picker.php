<?php
/**
 * Contact Form 7 Color Picker
 *
 * @link              https://restezconnectes.fr
 * @since             4.0.0
 * @package           Wpcf7_Color
 *
 * @wordpress-plugin
 * Plugin Name: Contact Form 7 Color Picker
 * Plugin URI: https://restezconnectes.fr
 * Description: Easily add a color field to your CF7 forms. This plugin depends on Contact Form 7.
 * Version:     0.1
 * Author:      Florent Maillefaud
 * Author URI:  https://restezconnectes.fr
 * License:     GPL3 or later
 * Domain Path: /languages
 * Text Domain: cf7-color-picker
*/

define( 'WPCF7_COLOR_VERSION', '0.1.1' );
define( 'WPCF7_COLOR_PLUGIN_NAME', 'cf7-color-picker' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpcf7-color.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    4.0.0
 */
function run_wpcf7_color() {

	$plugin = new Wpcf7_Color();
	$plugin->run();

}
run_wpcf7_color();

// Enable localization
add_action( 'init', '_cf7color_load_translation' );
function _cf7color_load_translation() {
    load_plugin_textdomain( 'cf7-color-picker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}