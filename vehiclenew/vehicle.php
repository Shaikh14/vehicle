<?php
/*
Plugin Name: Vehicle
Plugin URI: #
Description:  Vehicle
Version: 1.0.0
Author: Ancineha
Author URI: #
*/
$label = "Vehicle";
if (!defined('ABSPATH'))
    exit;

// Path and URL
if (!defined('WP_DASHBOARD_PLUGIN_DIR'))
    define('WP_DASHBOARD_PLUGIN_DIR', WP_PLUGIN_DIR . '/vehicle');

global $wpdbb_content_dir;
if(!function_exists('wp_get_current_user')){
  include(ABSPATH."wp-includes/pluggable.php") ; // Include pluggable.php for current user
}

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'vehicle_booking';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		  `ID` int(11) NOT NULL,
		  `first_name` varchar(100) NOT NULL,
		  `last_name` varchar(50) NOT NULL,
		  `email` varchar(100) NOT NULL,
                  `phone` varchar(100) NOT NULL,
		  `vehicle_type` varchar(100)  NOT NULL,
		  `vehicle` varchar(100)  NOT NULL,
		  `vehicle_price` varchar(100)  NOT NULL,
		  `status` tinyint(1) NOT NULL,
		  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}
register_activation_hook( __FILE__, 'jal_install' );

//insert post type
require_once(WP_DASHBOARD_PLUGIN_DIR . '/include/post_type.php');

// include the user list table
require_once(WP_DASHBOARD_PLUGIN_DIR . '/include/function.php');
