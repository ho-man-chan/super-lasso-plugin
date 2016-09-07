<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.homanchan.com
 * @since      1.0.0
 *
 * @package    Super_Lasso
 * @subpackage Super_Lasso/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Super_Lasso
 * @subpackage Super_Lasso/admin
 * @author     Ho Man Chan <homan98@gmail.com>
 */
class Super_Lasso_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Super_Lasso_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Super_Lasso_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/super-lasso-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Super_Lasso_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Super_Lasso_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/super-lasso-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Add setting page
	 */
	public function display_admin_page() {
		add_options_page(
							__('Super Lasso', 'sl_page_title'),
							__('Super Lasso', 'sl_menu_title'),
							'manage_options',
							$this->plugin_name,
							array( $this, 'show_admin_page')
						);
	}
	
	/**
	 * Content of setting page
	 */
	public function show_admin_page() {
		$dir = plugin_dir_path(__FILE__);
		
		include_once $dir . 'partials/super-lasso-admin-display.php';
	}
	
	public function add_action_links( $links ) {
    /*
    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
    */
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
			);
		return array_merge(  $links, $settings_link );
	}

	/**
	* Register settings
	*/
	public function options_update() {
    	register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    	//register_setting($this->plugin_name, $this->plugin_name);
 	}

	/**
	* Sanitize setting inputs
	*/
	public function validate($input) {
    	// All checkboxes inputs        
    	$valid = array();

		//Cleanup
		$valid['sl_fb_appid'] = sanitize_text_field( $input[ 'sl_fb_appid' ] );
    	$valid['sl_fb_secret'] = sanitize_text_field( $input[ 'sl_fb_secret' ] );
    	$valid['sl_fb_access_token'] = sanitize_text_field( $input[ 'sl_fb_access_token' ] );
    	$valid['sl_fb_pageid'] = sanitize_text_field( $input[ 'sl_fb_pageid' ] );
    
    	return $valid;
	}

} // end admin
