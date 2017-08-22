<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://restezconnectes.fr
 * @since      4.0.0
 *
 * @package    Wpcf7_Color
 * @subpackage Wpcf7_Color/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Wpcf7_Color
 * @subpackage Wpcf7_Color/admin
 * @author     Florent Maillefaud <florent@restezconnectes.fr>
 */
class Wpcf7_Color_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        
        add_action( 'admin_menu',  array( $this, 'wpcf7_color_load_admin_scripts' ) );

	}

    function wpcf7_color_load_admin_scripts( ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'my-script-handle', plugin_dir_url( __FILE__ ) . 'js/js-color-options.js', array( 'wp-color-picker' ), false, true );
    }
    
    
	/**
	 * Add a tag generator for the color field type
	 *
	 * @since    4.0.0
	 */
	public function add_tag_generator() {

		if (class_exists('WPCF7_TagGenerator')) {
			$tag_generator = WPCF7_TagGenerator::get_instance();
			$tag_generator->add( 'color', __( 'color', 'cf7-color-picker' ),array($this,'tag_generator_color') );
		}
		
	}

	/**
	 * Tag generator form
	 *
	 * @since    4.0.0
	 */
	public function tag_generator_color( $contact_form, $args = '' ) {

		$args = wp_parse_args( $args, array() );
		$type = 'color';

		$description = __( "Generate a form-tag for a color field.", 'contact-form-7' );
		?>
		<div class="control-box">
            <fieldset>
                <!--<legend><?php //echo sprintf(esc_html($description), $desc_link); ?></legend>-->

                <table class="form-table"><tbody>
                    <tr>
                        <th scope="row"><?php 
                        echo esc_html(__('Field type', 'contact-form-7'));
                        ?>
                    </th>
                        <td>
                            <fieldset>
                            <legend class="screen-reader-text"><?php echo esc_html(__('Field type', 'contact-form-7')); ?>
                    </legend>
                            <label><input type="checkbox" name="required" /> <?php echo esc_html(__('Required field', 'contact-form-7')); ?>
                    </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="<?php echo esc_attr($args['content'] . '-defaultcolor'); ?>"><?php echo esc_html(__('Default value (optional)', 'cf7-color-picker')); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="defaultcolor" class="defaultcolorvalue oneline option color-field" id="<?php echo esc_attr($args['content'] . '-defaultcolor'); ?>" value="#dd3333" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="<?php echo esc_attr($args['content'] . '-name'); ?>
                            "><?php echo esc_html(__('Name', 'cf7-color-picker'));?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr($args['content'] . '-name'); ?>
                            " /><br><em><?php echo esc_html(__('For better security, change "color" to something less bot-recognizable.', 'cf7-color-picker')); ?></em>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="<?php  echo esc_attr($args['content'] . '-id'); ?>"><?php echo esc_html(__('ID (optional)', 'cf7-color-picker')); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr($args['content'] . '-id'); ?>" />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
                        <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                    </tr>

                    <tr><td></td></tr>

                </tbody></table>
            </fieldset>
        </div>

        <div class="insert-box">
            <input type="text" name="color" class="tag code" readonly="readonly" onfocus="this.select()" />

            <div class="submitbox">
                <input type="button" class="button button-primary insert-tag" value="<?php 
        echo esc_attr(__('Insert Tag', 'cf7-color-picker'));
        ?>
" />
            </div>

            <br class="clear" />
        </div>
		<?php
	}

	
}
