<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://restezconnectes.fr
 * @since      4.0.0
 *
 * @package    Wpcf7_Color
 * @subpackage Wpcf7_Color/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Wpcf7_Color
 * @subpackage Wpcf7_Color/public
 * @author     Florent Maillefaud <florent@restezconnectes.fr>
 */
class Wpcf7_Color_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name.'-color', plugin_dir_url( __FILE__ ) . 'js/jscolor.min.js', array(), $this->version, false );

	}

	/**
	 * Add shortcode handler to CF7
	 *
	 * @since    4.0.0
	 */
	public function add_color_shortcode_handler() {
		if (function_exists('wpcf7_add_form_tag')){
			wpcf7_add_form_tag(
				array( 'color', 'color*' ),
				array($this, 'color_shortcode_handler'), true 
			);
		}
	}

	/**
	 * Color Shortcode handler
	 *
	 * @since    4.0.0
	 */
	public function color_shortcode_handler($tag) {

		$tag = new WPCF7_FormTag( $tag );

		if ( empty( $tag->name ) )
			return '';

		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-color' );

		if ( $validation_error )
			$class .= ' wpcf7-not-valid';

		$atts = array();

		$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

		if ( $tag->has_option( 'readonly' ) )
			$atts['readonly'] = 'readonly';

		if ( $tag->is_required() )
			$atts['aria-required'] = 'true';

		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

		$value = (string) reset( $tag->values );

		if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
			$atts['placeholder'] = $value;
			$value = '';
		} elseif ( '' === $value ) {
			$value = $tag->get_default_option();
		}

		if ( wpcf7_is_posted() && isset( $_POST[$tag->name] ) )
			$value = wp_unslash( $_POST[$tag->name] );

		/* Input attributes */
        $atts['class'] = $tag->get_class_option($class);
        $atts['id'] = $tag->get_option('id', 'id', true);
        $atts['name'] = $tag->name;
        $atts['type'] = $tag->type;
        $atts['defaultcolor'] = $tag->get_option('defaultcolor');
        $atts['validation_error'] = $validation_error;
        $inputid = !empty($atts['id']) ? 'id="' . $atts['id'] . '" ' : '';

        $html = '<div id="color-div-general">';
        $html .= '<div id="color-div-field">
                    <input type="text" name="' . $atts['name'] . '" id="color_value" value="'.$atts['defaultcolor'][0].'" class="jscolor {valueElement: \'color_value\'} ' . $atts['class'] . '">
                </div>';
        $html .= "<div id='color-div-button'>
                    <!--<button class=\"jscolor {valueElement: 'color_value' }\">".esc_html(__('Pick a color', 'cf7-color-picker'))."</button>-->
                  </div>";
        $html .= "<div class='clear'></div></div>";
        $html .= $validation_error;
        // Hook for filtering finished color form element.
        return apply_filters('wpcf7_color_html_output', $html, $atts);
	}

	/**
	 * Color validation
	 *
	 * @since    4.0.0
	 */
	public function color_validation( $result, $tag ) {
		$tag = new WPCF7_FormTag( $tag );

		$name = $tag->name;

		$value = isset( $_POST[$name] )
			? trim( wp_unslash( strtr( (string) $_POST[$name], "\n", " " ) ) )
			: '';

		if ( 'color*' == $tag->type ) {
			if ( '' == $value ) {
				if (method_exists($result,"invalidate")){
					$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
					return $result;
				}else{
					$result['valid'] = false;
					$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
				}
			}
		}

		if ( isset( $result['reason'][$name] ) && $id = $tag->get_id_option() ) {
			$result['idref'][$name] = $id;
		}

		return $result;
	}

}
