<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://restezconnectes.fr
 * @since      4.0.0
 *
 * @package    Wpcf7_Color
 * @subpackage Wpcf7_Color/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      4.0.0
 * @package    Wpcf7_Color
 * @subpackage Wpcf7_Color/includes
 * @author     Florent Maillefaud <florent@restezconnectes.fr>
 */
class Wpcf7_Color_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    4.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'color-input-for-cf7',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
