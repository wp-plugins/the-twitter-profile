<?php
/*
Plugin Name: The Twitter Profile
Plugin URI: http://wp-plugins.in/The_Twitter_Profile
Description: Display your full twitter profile in sidebar easily, responsive and retina, recent tweets and emoji icons support, RTL support and texts translate ready.
Version: 1.0.4
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2015 Alobaidi (email: wp-plugins@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// Require Once Twitter OAuth
require_once( plugin_dir_path( __FILE__ ) . '/Alobaidi.TwitterOAuth.php' );


// Include Settings Page
include( plugin_dir_path( __FILE__ ) . '/settings.php' );


// Include CSS Style An JavaScript
function WPTime_twitter_profile_include_css_and_js(){	

    if( !get_option('wpt_tp_disable_font') ){
        wp_enqueue_style( 'wpt-twitter-font-style', plugins_url( '/css/font-style.css', __FILE__ ), false, null);
    }

	wp_enqueue_style( 'wpt-twitter-profile-style', plugins_url( '/css/twitter-profile-style.css', __FILE__ ), false, null);
	wp_enqueue_style( 'wpt-twitter-profile-fontello', plugins_url( '/css/fontello.css', __FILE__ ), false, null);
	wp_enqueue_script( 'wpt-twitter-profile-script', plugins_url( '/js/twitter-profile-script.js', __FILE__ ), array('jquery'), null, false);

}
add_action('wp_enqueue_scripts', 'WPTime_twitter_profile_include_css_and_js');


// Remove Emoji CSS
function WPTime_twitter_profile_remove_emoji_css(){
	if( get_option('wpt_tp_disable_emoji') ){
		?>
        	<style type="text/css">
				.wpt-tw-profile-wrap .emoji, .wpt-tw-profile-wrap .wp-smiley{
					display:none !important;
				}
			</style>
        <?php
	}
}
add_action('wp_head', 'WPTime_twitter_profile_remove_emoji_css');


// Include Twitter Profile Widget
include( plugin_dir_path( __FILE__ ) . '/widget.php' );


// Add plugin meta links
function WPTime_twitter_profile_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'the-twitter-profile.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/The_Twitter_Profile" target="_blank">Explanation of Use</a>',
						'<a href="http://j.mp/WPTime_Buy_TP_RTE" target="_blank">Buy Recent Tweets Extension</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'WPTime_twitter_profile_plugin_row_meta', 10, 2 );


// Add settings page link in before activate/deactivate links.
function WPTime_twitter_profile_plugin_action_links( $actions, $plugin_file ){
	
	static $plugin;

	if ( !isset($plugin) ){
		$plugin = plugin_basename(__FILE__);
	}
		
	if ($plugin == $plugin_file) {
		
		if ( is_ssl() ) {
			$settings_link = '<a href="'.admin_url( 'plugins.php?page=WPTime_twitter_profile_settings', 'https' ).'">Settings</a>';
		}else{
			$settings_link = '<a href="'.admin_url( 'plugins.php?page=WPTime_twitter_profile_settings', 'http' ).'">Settings</a>';
		}
		
		$settings = array($settings_link);
		
		$actions = array_merge($settings, $actions);
			
	}
	
	return $actions;
	
}
add_filter( 'plugin_action_links', 'WPTime_twitter_profile_plugin_action_links', 10, 5 );

?>