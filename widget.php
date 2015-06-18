<?php

// Twitter Profile Widget
class WPTimeTwitterProfileWidget extends WP_Widget {
	function WPTimeTwitterProfileWidget() {
		parent::__construct( false, 'Twitter Profile', array('description' => 'Display your twitter profile.') );
	}
	
	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', esc_attr( $instance['title'] ) );
		global $wptime_twitter_profile_global_username;
		$wptime_twitter_profile_global_username = preg_replace( "/\s+|(@)|\n/", '', esc_attr( $instance['username'] ) );
		$wptime_twitter_profile_global_tweets_count = esc_attr( $instance['tweets_count'] );
		
		if( !empty($wptime_twitter_profile_global_tweets_count) and !preg_match( '/^[0-9]+$/', $wptime_twitter_profile_global_tweets_count ) ){
			global $wptime_twitter_profile_global_tweets_count;
			$wptime_twitter_profile_global_tweets_count = 0;
		}
		elseif( $wptime_twitter_profile_global_tweets_count > 200 ){
			global $wptime_twitter_profile_global_tweets_count;
			$wptime_twitter_profile_global_tweets_count = 1;
		}
		else{
			global $wptime_twitter_profile_global_tweets_count;
			$wptime_twitter_profile_global_tweets_count = esc_attr( $instance['tweets_count'] );
		}

		?>
			<?php echo $args['before_widget']; ?>
				<?php
					if( !empty($title) ){
						echo $args['before_title'].$title.$args['after_title'];
					}
				?>
                <?php
				
					if( !get_option('wpt_tp_consumer_key') or !get_option('wpt_tp_consumer_secret') ){
						echo '<p>Please enter your Consumer Key and Consumer Secret.</p>';
						echo  $args['after_widget'];
						return false;
					}
					
					if( empty($wptime_twitter_profile_global_username) ){
						echo '<p>Please enter twitter username.</p>';
						echo  $args['after_widget'];
						return false;
					}
					
					$transient_name = md5($this->id.$wptime_twitter_profile_global_username.$wptime_twitter_profile_global_tweets_count);
					$get_transient = get_transient( $transient_name );
					
					if( get_option('wpt_tp_cache_time') ){
						
						if( !preg_match( '/^[0-9]+$/', get_option('wpt_tp_cache_time') ) ){
							$cache_time = 1 * 3600;
						}else{
							$cache_time = get_option('wpt_tp_cache_time') * 3600;
						}
						
					}else{
						$cache_time = 1 * 3600;
					}
					
					if ( empty( $get_transient ) ){
						$transient_output = '';
						$access_token = get_option('wpt_twitter_profile_access_token');
						
						$get_args = array( 
										'headers' => array(
														'Authorization' => 'Bearer '.$access_token.'',
														'Accept-Encoding' => 'gzip' 
													)
									);
									
						$response = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$wptime_twitter_profile_global_username.'&count=1', $get_args);
						$retrieve = wp_remote_retrieve_body($response);
						$results = json_decode( $response['body'], true );
						
						if( preg_match('/(Bad Authentication data)+/', $retrieve) ){
							echo '<p>Bad Authentication data! Please enter correct Consumer Key and Consumer Secret.</p>';
							echo  $args['after_widget'];
							return false;
						}
						
						if( preg_match('/(Invalid or expired token)+/', $retrieve) ){
							echo '<p>Invalid or expired token.</p>';
							echo  $args['after_widget'];
							return false;
						}
						
						if( preg_match('/(Unable to verify your credentials)+/', $retrieve) ){
							echo '<p>Unable to verify your credentials.</p>';
							echo  $args['after_widget'];
							return false;
						}
						
						if( preg_match('/(Your credentials do not allow access to this resource)+/', $retrieve) ){
							echo '<p>Your credentials do not allow access to this resource.</p>';
							echo  $args['after_widget'];
							return false;
						}
						
						if( preg_match('/(Rate limit exceeded)+/', $retrieve) ){
							echo '<p>Rate limit exceeded.</p>';
							echo  $args['after_widget'];
							return false;
						}
						
						if( preg_match('/(Sorry, that page does not exist)+/', $retrieve) ){
							echo '<p>Error username! please enter correct username.</p>';
							echo  $args['after_widget'];
							return false;
						}
						
						$emoji_regex = array(
							'/[\x{2300}-\x{2999}]/u',
							'/[\x{1F300}-\x{1F900}]/u',
							'/[\x{FEB0C}]/u',
							'/[\x{E022}]/u',
							'/[\x{E595}]/u',
							'/[\x{E6EC}]/u',
							'/[\x{2764}]/u'
						);
						
						foreach( $results as $result ){
							
							if( !empty($result['user']['profile_image_url']) ){
								$profile_image_url = str_replace('_normal', '', $result['user']['profile_image_url']);
							}else{
								$profile_image_url = null;
							}
							
							if( !empty($result['user']['profile_banner_url']) ){
								$profile_banner_url = $result['user']['profile_banner_url'].'/1500x500';
								$cover_style = 'url('.$profile_banner_url.') no-repeat';
							}else{
								if( !empty($result['user']['profile_background_color']) ){
									$cover_style = '#'.$result['user']['profile_background_color'];
								}else{
									$cover_style = '#3b94d9';
								}
							}
							
							if( !empty($result['user']['statuses_count']) ){
								
								$get_tweets_count = $result['user']['statuses_count'];
								
								if( get_option('wpt_tp_k_t') ){
									$k_character = get_option('wpt_tp_k_t');
								}else{
									$k_character = 'K';
								}
								
								if( get_option('wpt_tp_m_t') ){
									$m_character = get_option('wpt_tp_m_t');
								}else{
									$m_character = 'M';
								}
								
								if( strlen($get_tweets_count) == 4 or strlen($get_tweets_count) == 5 or strlen($get_tweets_count) == 6 ){
									$substr_tweets_count = substr($get_tweets_count, 0, -3).$k_character;
									$tweets_count = $substr_tweets_count;
								}
								elseif( strlen($get_tweets_count) == 7 or strlen($get_tweets_count) == 8 or strlen($get_tweets_count) == 9 ){
									$substr_tweets_count = substr($get_tweets_count, 0, -6).$m_character;
									$tweets_count = $substr_tweets_count;
								}
								else{
									$tweets_count = $get_tweets_count;
								}
								
							}else{
								$tweets_count = null;
							}
							
							if( !empty($result['user']['followers_count']) ){
								
								$get_followers_count = $result['user']['followers_count'];
								
								if( get_option('wpt_tp_k_t') ){
									$k_character = get_option('wpt_tp_k_t');
								}else{
									$k_character = 'K';
								}
								
								if( get_option('wpt_tp_m_t') ){
									$m_character = get_option('wpt_tp_m_t');
								}else{
									$m_character = 'M';
								}
								
								if( strlen($get_followers_count) == 4 or strlen($get_followers_count) == 5 or strlen($get_followers_count) == 6 ){
									$substr_followers_count = substr($get_followers_count, 0, -3).$k_character;
									$followers_count = $substr_followers_count;
								}
								elseif( strlen($get_followers_count) == 7 or strlen($get_followers_count) == 8 or strlen($get_followers_count) == 9 ){
									$substr_followers_count = substr($get_followers_count, 0, -6).$m_character;
									$followers_count = $substr_followers_count;
								}
								else{
									$followers_count = $get_followers_count;
								}
								
							}else{
								$followers_count = null;
							}
							
							if( !empty($result['user']['friends_count']) ){
								
								$get_following_count = $result['user']['friends_count'];
								
								if( get_option('wpt_tp_k_t') ){
									$k_character = get_option('wpt_tp_k_t');
								}else{
									$k_character = 'K';
								}
								
								if( get_option('wpt_tp_m_t') ){
									$m_character = get_option('wpt_tp_m_t');
								}else{
									$m_character = 'M';
								}
								
								if( strlen($get_following_count) == 4 or strlen($get_following_count) == 5 or strlen($get_following_count) == 6 ){
									$substr_following_count = substr($get_following_count, 0, -3).$k_character;
									$following_count = $substr_following_count;
								}
								elseif( strlen($get_following_count) == 7 or strlen($get_following_count) == 8 or strlen($get_following_count) == 9 ){
									$substr_following_count = substr($get_following_count, 0, -6).$m_character;
									$following_count = $substr_following_count;
								}
								else{
									$following_count = $get_following_count;
								}
								
							}else{
								$following_count = null;
							}
							
							if( !empty($result['user']['screen_name']) ){
								$profile_link = 'https://twitter.com/'.$result['user']['screen_name'].'';
								$screen_name = '<a href="'.$profile_link.'" target="_blank" rel="nofollow">@'.$result['user']['screen_name'].'</a>';
							}else{
								$profile_link = null;
								$screen_name = null;
							}
							
							if( !empty($result['user']['location']) ){
								$if_get_location 	= 	$result['user']['location'];
								
								if( get_option('wpt_tp_disable_emoji') ){
									$clean_location = preg_replace($emoji_regex, '', $result['user']['location']); // remove emoji icons from location
								}else{
									$clean_location = $result['user']['location'];
								}
								
								$location 			= 	'<li class="wpt-tp-location">'.$clean_location.'</li>';
							}else{
								$if_get_location 	= 	null;
								$location 			= 	null;
							}
							
							if( !empty($result['user']['entities']['url']['urls'][0]['expanded_url']) ){
								$if_get_url 	= 	$result['user']['entities']['url']['urls'][0]['expanded_url'];
								
								$clean_url = preg_replace($emoji_regex, '', $if_get_url); // remove emoji icons from location
								
								$url 			= 	'<li class="wpt-tp-url"><a href="'.$clean_url.'" target="_blank" rel="nofollow">'.$clean_url.'</a></li>';
							}else{
								$if_get_url 	= 	null;
								$url 			= 	null;
							}
							
							if( !empty($result['user']['created_at']) ){
								$if_get_joined	=	$result['user']['created_at'];
								$joined 		= 	substr($if_get_joined, -4, 4);
							}else{
								$if_get_joined	=	null;
								$joined 		= 	null;
							}
							
							if( !empty($result['user']['name']) ){
								
								$profile_link = 'https://twitter.com/'.$result['user']['screen_name'].''; //wpt-tp-verified-account
								
								if( $result['user']['verified'] == 'true' ){
									$verified = '<span class="wpt-tp-verified-account"></span>';
									$verified_class = ' wpt-tp-verified';
								}else{
									$verified = null;
									$verified_class = null;
								}
								
								$clean_name = preg_replace( $emoji_regex, '', $result['user']['name'] );
								$name = '<a href="'.$profile_link.'" target="_blank" rel="nofollow">'.$clean_name.$verified.'</a>';
								
							}else{
								$name = null;
								$verified_class = null;
							}
							
							$wpt_tp_recent_tweets_filter = '';

							if( !empty($result['user']['description']) ){
								
								if( get_option('wpt_tp_disable_emoji') ){
									
									$clean_bio = preg_replace($emoji_regex, '', $result['user']['description']);
									$get_bio = $clean_bio;
									
								}else{
									$get_bio = $result['user']['description'];
								}
								
							}else{
								$get_bio = null;
							}
						
							if( !empty($result['user']['entities']['description']['urls'][0]['expanded_url']) ){
								$get_bio_url = $result['user']['entities']['description']['urls'][0]['expanded_url'];
							}else{
								$get_bio_url = null;
							}
							
							if( !empty($get_bio) ){
								if( !empty($get_bio_url) ){
									$regex = '#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si';
									$get_the_bio = '<p>'.preg_replace($regex, '<a href="'.$get_bio_url.'" target="_blank" rel="nofollow">'.$get_bio_url.'</a>', $get_bio).'</p>';
								}else{
									$get_the_bio = '<p>'.$result['user']['description'].'</p>';
								}
							}else{
								$get_the_bio = null;
							}

							ob_start();
								?>
                                	<?php 
									
									if( is_rtl() ){
                                    	$rtl = ' wpt-tp-rtl';
                                    }else{
                                    	$rtl = null;
                                    }
									
									if( get_option('wpt_tp_tweets_t') ){
										$tweet_text = get_option('wpt_tp_tweets_t');
									}else{
										$tweet_text = 'TWEETS';
									}
									
									if( get_option('wpt_tp_following_t') ){
										$following_text = get_option('wpt_tp_following_t');
									}else{
										$following_text = 'FOLLOWING';
									}
									
									if( get_option('wpt_tp_followers_t') ){
										$followers_text = get_option('wpt_tp_followers_t');
									}else{
										$followers_text = 'FOLLOWERS';
									}
									
									if( get_option('wpt_tp_joined_t') ){
										$joined_text = get_option('wpt_tp_joined_t');
									}else{
										$joined_text = 'Joined';
									}
									
									if( get_option('wpt_tp_disable_emoji') ){
										$emoji_class = ' wpt_tp_emoji';
									}else{
										$emoji_class = null;
									}
                                    ?>
                            		<div id="<?php echo $this->id; ?>" class="wpt-tw-profile-wrap<?php echo $rtl.$emoji_class; ?>">
                                    	<div class="wpt-tp-cover" style="background:<?php echo $cover_style; ?>;">
                                        	<div class="wpt-tp-avatar-wrap">
                                        		<a href="<?php echo $profile_link; ?>" target="_blank" rel="nofollow"><img src="<?php echo $profile_image_url; ?>" class="wpt-tp-avatar"></a>
                                            </div>
                                            <h4 class="wpt-tp-name<?php echo $verified_class; ?>"><?php echo $name; ?></h4>
                                            <h5 class="wpt-tp-screen-name"><?php echo $screen_name; ?></h5>
                                        </div>
                                        <div class="wpt-tp-counts">
                                        	<ul>
                                            	<li><span class="wpt-tp-count-name"><?php echo $tweet_text; ?></span><span class="wpt-tp-the-count"><?php echo $tweets_count; ?></span></li>
                                                <li><span class="wpt-tp-count-name"><?php echo $following_text; ?></span><span class="wpt-tp-the-count"><?php echo $following_count; ?></span></li>
                                                <li><span class="wpt-tp-count-name"><?php echo $followers_text; ?></span><span class="wpt-tp-the-count"><?php echo $followers_count; ?></span></li>
                                            </ul>
                                        </div>
                                        <div class="wpt-tp-content-wrap">
                                        	<div class="wpt-tp-content">
                                            	<?php echo $get_the_bio; ?>
                                            	<?php if( !empty($if_get_joined) or !empty($if_get_url) or !empty($if_get_location) ) : ?>
                                            		<ul>
                                            			<?php echo $location; ?>
                                                    	<?php echo $url; ?>
                                                    	<?php echo '<li class="wpt-tp-joined">'.$joined_text.' '.$joined.'</li>'; ?>
                                            		</ul>
                                            	<?php endif; ?>
                                             </div>
                                             <?php 
											 if( !empty($wptime_twitter_profile_global_tweets_count) and $wptime_twitter_profile_global_tweets_count > 0 ){
											 	echo apply_filters( 'wpt_tp_recent_tweets_filter', $wpt_tp_recent_tweets_filter ); 
											 }
											 ?>
                                        	</div>
                                    </div>
                            	<?php

						}//end foreach
						
						$transient_output .= ob_get_clean();
						echo $transient_output;
						set_transient($transient_name, $transient_output, $cache_time);
					}
					
					else{
						echo $get_transient;
					}
				?>
			<?php echo  $args['after_widget']; ?>
        <?php
	}//widget
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['tweets_count'] = strip_tags($new_instance['tweets_count']);
		return $instance;
	}//update
	
	function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance
		);
		
		$defaults = array(
			'title' => 'My Profile On Twitter',
			'username' => '',
			'tweets_count' => '',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		$username = $instance['username'];
		$tweets_count = $instance['tweets_count'];
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
            
			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>">Username:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
			</p>
            
			<p>
				<label for="<?php echo $this->get_field_id('tweets_count'); ?>">Recent Tweets Count:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id('tweets_count'); ?>" name="<?php echo $this->get_field_name('tweets_count'); ?>" type="text" value="<?php echo $tweets_count; ?>" <?php $wpt_tp_disabled_input = 'disabled'; echo apply_filters('wpt_tp_disabled_input', $wpt_tp_disabled_input); ?>/>
			</p>
            <?php 
				$wpt_tp_buy_extension_wid = '<p>Recent tweets will be working after buying the extension, <a href="http://j.mp/WPTime_Buy_TP_RTE" target="_blank">Buy Extension</a> for $0.99 only.</p>';
				echo apply_filters('wpt_tp_buy_extension_wid', $wpt_tp_buy_extension_wid);
			?>
        <?php
		
	}//form
	
}
add_action('widgets_init', create_function('', 'return register_widget("WPTimeTwitterProfileWidget");') );

?>