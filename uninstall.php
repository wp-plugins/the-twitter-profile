<?php

/* Uninstall Plugin */

// if not uninstalled plugin
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit(); // out!


/*esle:
	if uninstalled plugin, this options will be deleted
*/
delete_option('wpt_twitter_profile_access_token');
delete_option('wpt_tp_consumer_key');
delete_option('wpt_tp_consumer_secret');
delete_option('wpt_tp_cache_time');
delete_option('wpt_tp_disable_emoji');
delete_option('wpt_tp_tweets_t');
delete_option('wpt_tp_following_t');
delete_option('wpt_tp_followers_t');
delete_option('wpt_tp_k_t');
delete_option('wpt_tp_m_t');
delete_option('wpt_tp_joined_t');
delete_option('wpt_tp_opentweet_t');

?>