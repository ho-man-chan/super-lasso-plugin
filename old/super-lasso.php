<?php
/*
Plugin Name: Super-Lasso
Description: Super-Lasso retrieves content from social media to wordpress.
Version: 0.1
Author: Ho Man Chan
Author URI: http:///www.homanchan.com
Text Domain: super-lasso
License: GPLv2 or later
*/

require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

//Begin Super_Lasso Class
class Super_Lasso {

	//variables
	private static $instance = null;
	public static $fb;
	
	/**
	 * Creates or return an instance of this class
	 * @return Super_Lasso a single instance of this class
	 */
	public static function get_instance() {
		if( null == self::$instance ) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	/*
		Get all facebook events
	*/
	public static function get_all_facebook_events() {
		$path = '/' . get_option( 'sl_fb_page_id' ) . '/events';
		try {
			$response = $fb->get($path);
			$userNode = $response->getGraphUser();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			//When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			log_me( array( 'Graph returned an error: ' => $e->getMessage() ) );
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
  			// When validation fails or other local issues
  			log_me( array( 'Facebook SDK returned an error: ' => $e->getMessage() ) );
  			exit;
		}
		return $userNode;
	}
	
	/*
		Checks if the facebook event exist in wordpress.
		@return boolean Returns true if facebook event exist in wordpress
	*/
	public static function has_facebook_event($id) {
	
	}
	
	/*
		Create a new wordpress event.
		@param facebook event
	*/
	public static function create_event($event=null) {
		//event details
		$event_args = array(
						'ID' 					=> '123',
						'post_title' 			=> 'Event Title',
						'post_content'			=> 'Event Content',
						'EventStartDate'		=> '',
						'EventEndDate'			=> '',
						'EventStartHour'		=> '01',
						'EventStartMinute'		=> '01',
						'EventStartMeridian'	=> 'am',
						'EventEndHour'			=> '01',
						'EventEndMinute'		=> '01',
						'EventEndMeridian'		=> 'am',
						'EventShowMapLink'		=> true,
						'EventShowMap'			=> true,
						'EventCost'				=> '0'
						// 'Venue'					=> array(),
// 						'Organizer'				=> array()
		);
		
		//create event
		tribe_create_event($event_args);
	}
	
	//Update wordpress event if Facebook event is updated.
	public static function update_event($event) {
	
	}
	
	//Update all wordpress event
	public static function update_all_events() {
		$eventNode = getAllFacebookEvents();
		// for($eventNode['data'] as $key => $value) {
// 		
// 		}
	}
	
	//Add menu page for inputs (appid, secret)
	public static function add_menu() {
		add_options_page(
							__('Super Lasso', 'sl_page_title'),
							__('Super Lasso', 'sl_menu_title'),
							'manage_options',
							'superlassossettings',
							array( 'Super_Lasso', 'menu_page')
						);
	}
	
	//Custom cron interval
	public function add_cron_interval_minute( $schedules ) {
    	$schedules['minute'] = array(
    	    'interval' => 60,
    	    'display'  => esc_html__( 'Every minute' ),
    	    );
 
    	return $schedules;
	}
	
	
	
	//Cron jobs
	public function cron_task() {
		//create_event();
		log_me(__METHOD__.'Cron job launched!');
		//wp_mail( 'homan98@gmail.com', 'Automatic email', 'Automatic scheduled email from WordPress.');
	}

	//Content of admin menu
	public static function menu_page() {
		
		//Permission check
		if(!current_user_can('manage_options')) {
			wp_die(__('Oups, you need more power. Please contact the administrator'));
		}
		
		//Update settings
		if( isset($_POST['submit']) ) {
			update_option('sl_fb_appid',$_POST['sl_fb_appid']);
			update_option('sl_fb_secret',$_POST['sl_fb_secret']);
			update_option('sl_fb_access_token',$_POST['sl_fb_access_token']);
			update_option('sl_fb_pageid',$_POST['sl_fb_pageid']);
			echo '<p>' . __('Settings Saved!', 'sl_setting_saved') . '</p>';
		}
		
		//Display form
		echo '<div class="wrap">';
		echo '<h1>' . __( ' Super Lasso Settings' , 'sl_setting_title') . '</h1>';
		echo '<form name="setting_form" method="post" action="">';
			echo '<h3>Facebook</h3>';
			echo '<p>';
				echo 'In order for the plugin to obtain facebook data, it requires an app id, a secret key, and an access token.<br/>';
				echo 'For more information, consult the following link: <a href="https://developers.facebook.com/docs/apps/register#app-id" target="_blank">app id</a>';
			echo '</p>';
			echo '<table class="form-table">';
				echo '<tr>';
					echo '<th>App Id</th>';
					echo '<td><input type="text" name="sl_fb_appid" value="' . get_option('sl_fb_appid') . '" size="40"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th>Secret</th>';
					echo '<td><input type="text" name="sl_fb_secret" value="' . get_option('sl_fb_secret') . '" size="40"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th>Access Token</th>';
					echo '<td><input type="text" name="sl_fb_access_token" value="' . get_option('sl_fb_access_token') . '" size="40"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th>Page Id</th>';
					echo '<td><input type="text" name="sl_fb_pageid" value="' . get_option('sl_fb_pageid') . '" size="40"></td>';
				echo '</tr>';
			echo '</table>';
			echo '<p class="submit"><input type="submit" name="submit" value="' . __('Save Changes', 'sl_setting_submit_button') . '" class="button button-primary"></p>';
		echo '</form>';
		echo '</div>';
	}
	
	//Create credentials field in database
	public static function install_plugin() {
		//Create DB for facebook sdk credentials
		add_option('sl_fb_appid', '', '', 'yes');
		add_option('sl_fb_secret', '', '', 'yes');
		add_option('sl_fb_access_token', '', '', 'yes');
		add_option('sl_fb_pageid', '', '', 'yes');
		//End create DB for facebook sdk credentials
		
	}
	
	//Remove facebook appid, secret in database
	public static function remove_plugin() {
		delete_option('sl_fb_appid');
		delete_option('sl_fb_secret');
		delete_option('sl_fb_access_token');
		delete_option('sl_fb_pageid');
	}

	//Debug logger
	public static function log_me($message) {
    	if (WP_DEBUG === true) {
			if (is_array($message) || is_object($message)) {
				error_log(print_r($message, true));
			} else {
				 error_log($message);
				}
		}
	}
	
	//initialize facebook
	private function __construct() {
		
		//Hooks activate, deactivate plugin
		register_activation_hook( __FILE__, array( 'Super_Lasso' , 'install-plugin' ) );
		register_deactivation_hook( __FILE__, array( 'Super_Lasso' , 'remove-plugin' ) );

		//Admin menu
		if ( is_admin() ) {
		add_action( 'admin_menu', array( 'Super_Lasso', 'add_menu' ) );
		}
		
		//Schedule task
		add_action( 'cron_task_hook', 'cron_task' );
		
		//Custom Cron interval
		add_filter( 'cron_schedules', 'add_cron_interval_minute' ); 
		add_filter( 'cron_schedules', 'example_add_cron_interval' );
 
		function example_add_cron_interval( $schedules ) {
    		$schedules['five_seconds'] = array(
				'interval' => 5,
				'display'  => esc_html__( 'Every Five Seconds' ),
			);
 			return $schedules;
		}
		
		//Hook cron task
		if ( ! wp_next_scheduled( 'cron_task_hook' ) ) {
			wp_schedule_event( time(), 'five_seconds', 'cron_task_hook' );
		}
		//End Hook cron task
		
		//Establish facebook credentials
		$fb = new Facebook\Facebook([
  			'app_id' => get_option( 'sl_fb_appid' ),
  			'app_secret' => get_option( 'sl_fb_secret' ),
  			'default_graph_version' => 'v2.5',
			]);
		$fb->setDefaultAccessToken( get_option( 'sl_fb_access_token' ) );

		//View cron jobs
		$cron_jobs = get_option( 'cron' );
		//var_dump($cron_jobs);
		
		//create_event();
	// 	tribe_create_event(
// 			array(
// 					'post_title'=> 'test',
// 					'post_content' => 'content'
// 		
// 		));
	}
}
//End Super_Lasso class

//Initiate class Super_Lasso with singleton design pattern
$Super_Lasso = Super_Lasso::get_instance();
