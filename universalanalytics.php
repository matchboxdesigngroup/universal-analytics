<?php

/*

Plugin Name: Universal Analytics

Plugin URI: http://wordpress.org/extend/plugins/universal-analytics/

Description: Adds <a href="http://www.google.com/analytics/">Google's Universal Analytics</a> tracking code to all pages.

Version: 0.1

Author: Matchbox Design Group

Author URI: http://matchboxdesigngroup.com/

*/



if (!defined('WP_CONTENT_URL'))

      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');

if (!defined('WP_CONTENT_DIR'))

      define('WP_CONTENT_DIR', ABSPATH.'wp-content');

if (!defined('WP_PLUGIN_URL'))

      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');

if (!defined('WP_PLUGIN_DIR'))

      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');



function deactive_google_universal_analytics() {

  delete_option('web_property_id');

  delete_option('in_footer');

  delete_option('plugin_switch');

  delete_option('track_links');
  
  delete_option('enable_display');
  
  delete_option('anonymize_ip');

  delete_option('tracking_off_for_this_role');

  delete_option('tracking_off_for_role');

}



/**
	 * This adds the options page for this plugin to the Options page
	 */
function admin_menu_google_universal_analytics() {
  global  $settings_page;
  $settings_page	=	add_options_page( 'Google Analytics', 'Google Analytics', 'manage_options', 'google_universal_analytics', 'options_page_google_universal_analytics' );
}



function options_page_google_universal_analytics() {

  include(WP_PLUGIN_DIR.'/wp-universal-analytics/options.php');  

}



function google_universal_analytics() {

  require 'tracking-code.php';

}






function google_universal_analytics_scripts($hook){

		global  $settings_page, $settings_page1;

		

		if($hook != $settings_page && $hook != $settings_page1)

		return;

		

		//register styles

		wp_register_style( 'bootstrap-css', plugins_url( 'wp-universal-analytics/bootstrap/css/bootstrap.min.css' , dirname(__FILE__) ) );

		wp_register_style( 'bootstrap-switch-css', plugins_url( 'wp-universal-analytics/bootstrap/css/bootstrap-switch.min.css' , dirname(__FILE__) ) );

		wp_register_style( 'main-css', plugins_url( 'wp-universal-analytics/assets/gua-main.css' , dirname(__FILE__) ) );

		

		//register scripts

		wp_register_script( 'google-js', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', array(), '', true );

		wp_register_script( 'bootstrap-js', plugins_url( 'wp-universal-analytics/bootstrap/js/bootstrap.min.js' , dirname(__FILE__) ), array('google-js'), '', true );

		wp_register_script( 'bootstrap-switch-js', plugins_url( 'wp-universal-analytics/bootstrap/js/bootstrap-switch.min.js' , dirname(__FILE__) ) , array('bootstrap-js'),'',true );

		wp_register_script( 'main-js', plugins_url( 'wp-universal-analytics/assets/gua-main.js' , dirname(__FILE__) ) , array('google-js'),'',true );

		

		

		//enqueue styles

		wp_enqueue_style( 'bootstrap-css' );

		wp_enqueue_style( 'bootstrap-switch-css' );

		wp_enqueue_style( 'main-css' );

		

		//enqueue scripts

		wp_enqueue_script( 'google-js' );

		wp_enqueue_script( 'bootstrap-js' );

		wp_enqueue_script( 'bootstrap-switch-js' );

		wp_enqueue_script( 'main-js' );

}





register_deactivation_hook(__FILE__, 'deactive_google_universal_analytics');



if (is_admin()) {

  add_action('admin_enqueue_scripts', 'google_universal_analytics_scripts');		

  add_action('admin_menu', 'admin_menu_google_universal_analytics');

}



add_action('init', 'display_google_universal_analytics_code');

function display_google_universal_analytics_code(){

		global $current_user;

		$user_roles = $current_user->roles;

		$user_role = array_shift($user_roles);

	

	

	if(get_option('tracking_off_for_role')=='on' && strcasecmp($user_role , get_option('tracking_off_for_this_role'))!=0){

		if (!is_admin() && get_option('plugin_switch')=='on') {

			if(get_option('in_footer')=='on'){

				add_action('wp_footer', 'google_universal_analytics');

			}else{

				add_action('wp_head', 'google_universal_analytics');

			}

		}

	}elseif(is_user_logged_in() && get_option('tracking_off_for_role')!='on'){

		if (!is_admin() && get_option('plugin_switch')=='on') {

			if(get_option('in_footer')=='on'){

				add_action('wp_footer', 'google_universal_analytics');

			}else{

				add_action('wp_head', 'google_universal_analytics');

			}

		}



	}elseif(!is_user_logged_in()){

		if (!is_admin() && get_option('plugin_switch')=='on') {

			if(get_option('in_footer')=='on'){

				add_action('wp_footer', 'google_universal_analytics');

			}else{

				add_action('wp_head', 'google_universal_analytics');

			}

		}



	}
}







function save_google_universal_analytics_settings() {

	// The $_REQUEST contains all the data sent via ajax

	if ( isset($_REQUEST) ) {

		$property_id = $_REQUEST['property_id'];

		$in_footer = $_REQUEST['in_footer'];

		$plugin_switch = $_REQUEST['plugin_switch'];

		$track_links = $_REQUEST['track_links'];
		
		$enable_display = $_REQUEST['enable_display'];
		
		$anonymize_ip = $_REQUEST['anonymize_ip'];

		$tracking_off_for_this_role = $_REQUEST['tracking_off_for_this_role'];

		$tracking_off_for_role = $_REQUEST['tracking_off_for_role'];

		

		

		update_option('web_property_id', $property_id);

  		update_option('in_footer', $in_footer);

 		update_option('plugin_switch', $plugin_switch);

		update_option('track_links', $track_links);
		
		update_option('enable_display', $enable_display);
		
		update_option('anonymize_ip', $anonymize_ip);

		update_option('tracking_off_for_this_role', $tracking_off_for_this_role);

		update_option('tracking_off_for_role', $tracking_off_for_role);

	}

	// Always die in functions echoing ajax content

   die();

}




add_action( 'wp_ajax_save_google_universal_analytics_settings', 'save_google_universal_analytics_settings' );






?>