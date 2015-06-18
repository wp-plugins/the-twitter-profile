<?php

// Twitter OAuth by Alobaidi http://j.mp/1HVBgA6
function WPTime_Twitter_OAuth_For_Twitter_Profile_Plugin(){
	
	if( !get_option('wpt_twitter_profile_access_token') ) {
	
		$consumer_key		=	get_option('wpt_tp_consumer_key');
		$consumer_secret	=	get_option('wpt_tp_consumer_secret');
		$base64				=	base64_encode($consumer_key.':'.$consumer_secret);
	
		if( !get_option('wpt_tp_consumer_key') or !get_option('wpt_tp_consumer_secret') ){
			return false;
		}
	
		$oauth_args 		= array(
	
									"headers"	=>		array(
															"Authorization"		=>	"Basic $base64",
															"Content-Type"		=>	"application/x-www-form-urlencoded;charset=UTF-8",
															"Accept-Encoding"	=>	"gzip",
									),
							
									"body"		=>		array( "grant_type"		=>	"client_credentials" ),
			
								);
	
		$response 	= 	wp_remote_post('https://api.twitter.com/oauth2/token', $oauth_args);
		$result = json_decode( wp_remote_retrieve_body($response), true );
	
		if( !empty($result['access_token']) ){
			$access_token = $result['access_token'];
		}else{
			$access_token = null;
		}
	
		update_option('wpt_twitter_profile_access_token', $access_token);
	
	}
	
}

WPTime_Twitter_OAuth_For_Twitter_Profile_Plugin();

?>