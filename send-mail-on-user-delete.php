<?php
/**
 * Plugin Name: Send Mail on User Delete
 * Plugin URI: http://hussainarsh.blogspot.in
 * Description: This plugin is used to send email notification to those users whose account has been deleted by admin.
 * Author: Arshad Hussain
 * Version: 1.0.0
 * Author URI: http://hussainarsh.blogspot.in
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// define some constant for plugin
define('SMOUD_URL', plugin_dir_url(__FILE__));
define('SMOUD_PAGES_DIR', plugin_dir_path(__FILE__).'pages/');

// add menu for plugin
add_action('admin_menu', 'smoud_menu_setup');

function smoud_menu_setup(){
	add_menu_page('SMOUD', 'SMOUD', 'manage_options', 'smoud-settings', 'open_smoud_screen', 'dashicons-email-alt');
}

function open_smoud_screen(){
	$settings = SMOUD_PAGES_DIR.$_GET['page'].'.php';
	include($settings);
}

function smoud_send_mail($user_id) {
	global $wpdb;
	
	// Get information of user who is being deleted
	$user_obj 		=  	get_userdata( $user_id );
	$user_email 	=  	$user_obj->user_email;
	$user_name 	    =  	$user_obj->user_login;
	
	// Get site name with site url
	$site_name		=   get_bloginfo('name');
	$site_url		=   get_bloginfo('url');
	$site_info 		=   '<a href="'.$site_url.'">'.$site_name.'</a>';
	
	// Get information of admin
	$to = get_option('admin_email');
	
	// Get dynamic content set by admin for mailing admin
	$subject 	= 	get_option('smoud_subject');
	$email_txt 	= 	get_option('add_new_email_txt');
	
	// Get dynamic content set by admin for deleted user
	$subject_du 	          = 	 get_option('smoud_subject_du');
	$email_txt_du    	      = 	 get_option('add_new_email_txt_du');
	$email_txt_du             =      str_replace("%sitename%", $site_info, $email_txt_du);
	$final_email_txt_du       =      str_replace("%username%", $user_name, $email_txt_du);
	$final_email_txt_du       =      str_replace("%useremail%", $user_email, $final_email_txt_du);
	
	if(empty($subject)) {
		$subject = "User Deleted";
	} 
	
	if(empty($email_txt)) {
		$email_txt = "You have deleted a user";
	}
	
	if(empty($subject_du)) {
		$subject_du = "Your Account Has Been Deleted";
	} 
	
	if(empty($final_email_txt_du)) {
		$final_email_txt_du = "Your account at " . get_bloginfo('name') . " has been deleted.";
	}
	
	wp_mail($to, $subject, $email_txt);
	
	$headers  = 'From: ' . get_bloginfo( "name" ) . ' <' . get_bloginfo( "admin_email" ) . '>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
 	wp_mail( $user_email, $subject_du, $final_email_txt_du, $headers );
	
}
add_action( 'delete_user', 'smoud_send_mail' );