<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://restezconnectes.fr
 * @since      4.0.0
 *
 * @package    Wpcf7_Color
 * @subpackage Wpcf7_Color/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      4.0.0
 * @package    Wpcf7_Color
 * @subpackage Wpcf7_Color/includes
 * @author     Florent Maillefaud <florent@restezconnectes.fr>
 */
class Wpcf7_Color {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      Wpcf7_Color_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function __construct() {

		$this->plugin_name = WPCF7_COLOR_PLUGIN_NAME;
		$this->version = WPCF7_COLOR_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_base_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpcf7-color-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpcf7-color-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpcf7-color-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpcf7-color-public.php';

		// Dependency management
		//require_once dirname( __FILE__ ) . '/class-wpcf7-color-plugin-activation.php';

		$this->loader = new Wpcf7_Color_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpcf7_Color_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpcf7_Color_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the plugin settings
	 * of the plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function define_base_hooks() {

		// Dependencies
		$this->loader->add_action( 'tgmpa_register', $this, 'check_dependencies' );

		// Plugin upgrade process
		$this->loader->add_action( 'admin_init', $this, 'wpcf7_color_upgrade', 10 );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wpcf7_Color_Admin( $this->get_plugin_name(), $this->get_version() );

		// WPCF7
		$this->loader->add_action( 'wpcf7_admin_init', $plugin_admin, 'add_tag_generator', 60 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wpcf7_Color_Public( $this->get_plugin_name(), $this->get_version() );

		// Scripts and styles
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// WPCF7
		$this->loader->add_action( 'wpcf7_init', $plugin_public, 'add_color_shortcode_handler' );
		$this->loader->add_filter( 'wpcf7_validate_color', $plugin_public, 'color_validation', 10, 2 );
		$this->loader->add_filter( 'wpcf7_validate_color*', $plugin_public, 'color_validation', 10, 2 );

	}

    
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    4.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     4.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     4.0.0
	 * @return    Wpcf7_Color_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     4.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Get option, stored as a meta
	 *
	 * @since     4.0.0
	 * @param     string    $name       Option name
	 * @param     string    $default      Default value
	 * @return    string    The option value
	 */
	public static function get_option( $name, $default = false ) {
		$option = get_option( WPCF7_COLOR_PLUGIN_NAME );

		if ( false === $option ) {
			return $default;
		}

		if ( isset( $option[$name] ) ) {
			return $option[$name];
		} else {
			return $default;
		}
	}

	/**
	 * Store option as a meta
	 *
	 * @since     4.0.0
	 * @param     string    $name       Option name
	 * @param     string    $value      Option value
	 */
	public static function update_option( $name, $value ) {
		$option = get_option( WPCF7_COLOR_PLUGIN_NAME );
		$option = ( false === $option ) ? array() : (array) $option;
		$option = array_merge( $option, array( $name => $value ) );
		update_option( WPCF7_COLOR_PLUGIN_NAME, $option );
	}

	/**
	 * Check for plugin updates
	 *
	 * @since     4.0.0
	 */
	public function wpcf7_color_upgrade() {
		$old_ver = Wpcf7_Color::get_option( 'version', '0' );
		$new_ver = WPCF7_COLOR_VERSION;

		if ( $old_ver == $new_ver ) {
			return;
		}

		do_action( 'wpcf7_color_upgrade', $new_ver, $old_ver );

		Wpcf7_Color::update_option( 'version', $new_ver );
	}

	/**
	 * Check if CF7 is installed
	 *
	 * @since    4.0.0
	 */
	public function check_dependencies() {

		$plugins = array(

			array(
				'name'      => 'Contact Form 7',
				'slug'      => 'contact-form-7',
				'required'  => true,
				'version'	=> '4.6'
			),

		);

		$config = array(
			'id'           => 'contact-form-7-color-addon',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'manage_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => ''
		);

		tgmpa( $plugins, $config );

	}

}
