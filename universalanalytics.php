<?php
/*
Plugin Name: Universal Analytics
Plugin URI: http://wordpress.org/extend/plugins/universal-analytics/
Description: A simple method to add Google's Universal Analytics JavaScript tracking code to your WordPress website.
Version: 1.3.2
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

//when turning off analytics, delete these options from the database
function mdg_deactive_google_universal_analytics() {
  delete_option('web_property_id');
  delete_option('in_footer');
  delete_option('plugin_switch');
  delete_option('track_links');
  delete_option('enable_display');
  delete_option('anonymize_ip');
  delete_option('tracking_off_for_this_role');
  delete_option('tracking_off_for_role');
}

// This adds the options page for this plugin to the Options page
function mdg_admin_menu_google_universal_analytics() {
  global  $settings_page;
  $settings_page	=	add_options_page( 'Google Analytics', 'Google Analytics', 'manage_options', 'mdg_google_universal_analytics', 'mdg_options_page_google_universal_analytics' );
}

// Load the options page (the markup)
function mdg_options_page_google_universal_analytics() {
  include(WP_PLUGIN_DIR.'/universal-analytics/options.php');
}

// This contains the output of the tracking code
function mdg_google_universal_analytics() {
  require 'tracking-code.php';
}

function mdg_google_universal_analytics_scripts($hook){
		global  $settings_page, $settings_page1;

		if($hook != $settings_page && $hook != $settings_page1)
		return;

		// Register styles
		wp_register_style( 'bootstrap-css', plugins_url( 'universal-analytics/bootstrap/css/bootstrap.min.css' , dirname(__FILE__) ) );
		wp_register_style( 'bootstrap-switch-css', plugins_url( 'universal-analytics/bootstrap/css/bootstrap-switch.min.css' , dirname(__FILE__) ) );
		wp_register_style( 'main-css', plugins_url( 'universal-analytics/assets/gua-main.css' , dirname(__FILE__) ) );

		// Register scripts
		wp_register_script( 'bootstrap-js', plugins_url( 'universal-analytics/bootstrap/js/bootstrap.min.js' , dirname(__FILE__) ), array('jquery'), '', true );
		wp_register_script( 'bootstrap-switch-js', plugins_url( 'universal-analytics/bootstrap/js/bootstrap-switch.min.js' , dirname(__FILE__) ) , array('bootstrap-js'),'',true );
		wp_register_script( 'main-js', plugins_url( 'universal-analytics/assets/gua-main.js' , dirname(__FILE__) ) , array('jquery'),'',true );

		// Enqueue styles
		wp_enqueue_style( 'bootstrap-css' );
		wp_enqueue_style( 'bootstrap-switch-css' );
		wp_enqueue_style( 'main-css' );

		// Enqueue scripts
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'bootstrap-js' );
		wp_enqueue_script( 'bootstrap-switch-js' );
		wp_enqueue_script( 'main-js' );
}

register_deactivation_hook(__FILE__, 'mdg_deactive_google_universal_analytics');

if (is_admin()) {
  add_action('admin_enqueue_scripts', 'mdg_google_universal_analytics_scripts');
  add_action('admin_menu', 'mdg_admin_menu_google_universal_analytics');
}

add_action('init', 'mdg_display_google_universal_analytics_code');

function mdg_display_google_universal_analytics_code(){

		global $current_user;
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

	// Check here to see if admin tracking is turned on or off
	// and if the snippet is placed in the head or footer
	if(get_option('tracking_off_for_role')=='on' && strcasecmp($user_role , get_option('tracking_off_for_this_role'))!=0){
		if (!is_admin() && get_option('plugin_switch')=='on') {
			if(get_option('in_footer')=='on'){
				add_action('wp_footer', 'mdg_google_universal_analytics');
			}else{
				add_action('wp_head', 'mdg_google_universal_analytics');
			}
		}
	}elseif(is_user_logged_in() && get_option('tracking_off_for_role')!='on'){
		if (!is_admin() && get_option('plugin_switch')=='on') {
			if(get_option('in_footer')=='on'){
				add_action('wp_footer', 'mdg_google_universal_analytics');
			}else{
				add_action('wp_head', 'mdg_google_universal_analytics');
			}
		}
	}elseif(!is_user_logged_in()){
		if (!is_admin() && get_option('plugin_switch')=='on') {
			if(get_option('in_footer')=='on'){
				add_action('wp_footer', 'mdg_google_universal_analytics');
			}else{
				add_action('wp_head', 'mdg_google_universal_analytics');
			}
		}
	}
}

function mdg_save_google_universal_analytics_settings() {
	if ( !current_user_can( 'manage_options' ) ) {
		return;
	}

	$nonce = ( isset( $_REQUEST['nonce'] ) ) ? $_REQUEST['nonce'] : '';
	if ( !wp_verify_nonce( $nonce, 'mdg_save_google_universal_analytics_settings' )) {
		return;
	}

	// The $_REQUEST contains all the data sent via ajax

	if ( isset($_REQUEST) ) {
		$property_id = $_REQUEST['property_id'];
		$id_regex = 'UA-[0-9]+-[0-9]+';
		$valid_id = (preg_match($id_regex, null) === false);
		$property_id = ( $valid_id ) ? $property_id : '';
		$in_footer = $_REQUEST['in_footer'];
		$plugin_switch = $_REQUEST['plugin_switch'];
		$track_links = $_REQUEST['track_links'];
		$enable_display = $_REQUEST['enable_display'];
		$anonymize_ip = $_REQUEST['anonymize_ip'];
		$tracking_off_for_this_role = $_REQUEST['tracking_off_for_this_role'];
		$tracking_off_for_role = $_REQUEST['tracking_off_for_role'];

		update_option('web_property_id', sanitize_text_field( $property_id ) );
		update_option('in_footer', sanitize_text_field( $in_footer ) );
 		update_option('plugin_switch', sanitize_text_field( $plugin_switch ) );
		update_option('track_links', sanitize_text_field( $track_links ) );
		update_option('enable_display', sanitize_text_field( $enable_display ) );
		update_option('anonymize_ip', sanitize_text_field( $anonymize_ip ) );
		update_option('tracking_off_for_this_role', sanitize_text_field( $tracking_off_for_this_role ) );
		update_option('tracking_off_for_role', sanitize_text_field( $tracking_off_for_role ) );
	}

	// Always die in functions echoing ajax content
   die();
}

add_action( 'wp_ajax_mdg_save_google_universal_analytics_settings', 'mdg_save_google_universal_analytics_settings' );


// Alerts
// Display an error message when Universal Analytics hasn't been setup.
function mdg_google_universal_analytics_check() {
	$plugin_switch 	= get_option('plugin_switch')=='off';
	$property_id 		= get_option('web_property_id');
	
		if (!$property_id){
			echo "<div id='message' class='error'>";
			echo "<p><strong>" . __( "Almost there: Now you need add your Tracking ID to Universal Analytics.", 'mdg-universal-analytics' ) . "</strong> " . sprintf( __( "Go to your %sGoogle Analytics Settings%s and add your information.", 'mdg-universal-analytics' ), "<a href='" . admin_url( 'options-general.php?page=mdg_google_universal_analytics' ) . "'>", "</a>" ) . "</p></div>";
		}elseif ($plugin_switch) {
			echo "<div id='message' class='error'>";
			echo "<p><strong>" . __( "Whoa there partner: Universal Analytics is turned off.", 'mdg-universal-analytics' ) . "</strong> " . sprintf( __( "This means you aren't tracking your site.  Go to your %sGoogle Analytics Settings%s and change the Status to ON", 'mdg-universal-analytics' ), "<a href='" . admin_url( 'options-general.php?page=mdg_google_universal_analytics' ) . "'>", "</a>" ) . "</p></div>";
		}
}

function mdg_google_universal_analytics_admin_check() {
		mdg_google_universal_analytics_check();	
}
add_action( 'admin_head', 'mdg_google_universal_analytics_admin_check' );
?>
