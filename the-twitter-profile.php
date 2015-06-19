<?php
/*
Plugin Name: The Twitter Profile
Plugin URI: http://j.mp/The_Twitter_Profile
Description: Display your full twitter profile in sidebar easily, responsive and retina, recent tweets and emoji icons support, RTL support and texts translate ready.
Version: 1.0.1
Author: Alobaidi
Author URI: http://j.mp/1HVBgA6
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


// Require Once Twitter OAuth
require_once( plugin_dir_path( __FILE__ ) . '/Alobaidi.TwitterOAuth.php' );


// Include Settings Page
include( plugin_dir_path( __FILE__ ) . '/settings.php' );


// Include CSS Style An JavaScript
function WPTime_twitter_profile_include_css_and_js(){		
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

?>