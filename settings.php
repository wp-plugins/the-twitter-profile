<?php

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	function WPTime_twitter_profile_settings() {
		add_plugins_page( 'Twitter Profile Settings', 'Twitter Profile', 'update_core', 'WPTime_twitter_profile_settings', 'WPTime_twitter_profile_page');
	}
	add_action( 'admin_menu', 'WPTime_twitter_profile_settings' );
	
	function WPTime_twitter_profile_register_settings() {
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_consumer_key' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_consumer_secret' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_cache_time' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_disable_emoji' );
        register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_disable_font' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_tweets_t' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_following_t' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_followers_t' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_k_t' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_m_t' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_joined_t' );
		register_setting( 'WPTime_twitter_profile_settings_fields', 'wpt_tp_opentweet_t' );
	}
	add_action( 'admin_init', 'WPTime_twitter_profile_register_settings' );
		
	function WPTime_twitter_profile_page(){ // page function
		?>
			<div class="wrap">
            
				<h2>Twitter Profile Settings</h2>
                
				<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ){ ?>
					<div id="message" class="updated notice is-dismissible"> 
						<p><strong>Settings saved.</strong></p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
				<?php } ?>
                
            	<form method="post" action="options.php">
                	<?php settings_fields( 'WPTime_twitter_profile_settings_fields' ); ?>
                    
                    <h3>Twitter API</h3>
                    <table class="form-table">
                    	<tbody>
                        
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_consumer_key">Consumer Key</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_consumer_key" type="text" id="wpt_tp_consumer_key" value="<?php echo esc_attr( get_option('wpt_tp_consumer_key') ); ?>">
                                    <p class="description">Enter your consumer key, <a href="http://wp-time.com/twitter-profile-wordpress-widget/" target="_blank">Get it</a>.</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_consumer_secret">Consumer Secret</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_consumer_secret" type="text" id="wpt_tp_consumer_secret" value="<?php echo esc_attr( get_option('wpt_tp_consumer_secret') ); ?>">
                                    <p class="description">Enter your consumer secret, <a href="http://wp-time.com/twitter-profile-wordpress-widget/" target="_blank">Get it</a>.</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_cache_time">Cache Time</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_cache_time" type="text" id="wpt_tp_cache_time" value="<?php echo esc_attr( get_option('wpt_tp_cache_time') ); ?>">
                                    <p class="description">Your profile, tweets, will be refreshed after every 1 hour, enter custom cache time, numbers only, default is 1 (1 = one hour).</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_disable_emoji">Disable Emoji</label></th>
                            	<td>
                                	<fieldset>
                                		<legend class="screen-reader-text"><span>Disable Emoji</span></legend>
                                    	<label for="wpt_tp_disable_emoji">
                                    		<input name="wpt_tp_disable_emoji" type="checkbox" id="wpt_tp_disable_emoji" value="1" <?php checked( get_option('wpt_tp_disable_emoji'), 1, true ); ?>>Disable emoji icons from your twitter profile and recent tweets.
                                    	</label>
                                	</fieldset>
								</td>
                        	</tr>

                            <tr>
                                <th scope="row"><label for="wpt_tp_disable_font">Disable Font Style</label></th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span>Disable Font Style</span></legend>
                                        <label for="wpt_tp_disable_font">
                                            <input name="wpt_tp_disable_font" type="checkbox" id="wpt_tp_disable_font" value="1" <?php checked( get_option('wpt_tp_disable_font'), 1, true ); ?>>Disable font style, will be display the widget using your theme font.
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                            
                            <?php
                        	$wpt_tp_buy_extension_set  = '<tr>';
                            $wpt_tp_buy_extension_set .= '<th scope="row">Recent Tweets Extension</th>';
                            $wpt_tp_buy_extension_set .= '<td>';
                            $wpt_tp_buy_extension_set .= '<a href="http://j.mp/WPTime_Buy_TP_RTE" target="_blank"><img src="'.plugins_url( '/images/buy_button.png', __FILE__ ).'"></a>';
                            $wpt_tp_buy_extension_set .= '<p class="description">Recent tweets will be working after buying the extension, <a href="http://j.mp/WPTime_Buy_TP_RTE" target="_blank">Buy Extension</a> for $0.99 only.</p>';
                            $wpt_tp_buy_extension_set .= '</td>';
                            $wpt_tp_buy_extension_set .= '</tr>';
                            echo apply_filters('wpt_tp_buy_extension_set', $wpt_tp_buy_extension_set);
							?>
                            
                        </tbody>
                    </table>
                    
                    <h3>Texts Translate</h3>
                	<table class="form-table">
                		<tbody>
                        
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_tweets_t">Tweets Text</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_tweets_t" type="text" id="wpt_tp_tweets_t" value="<?php echo esc_attr( get_option('wpt_tp_tweets_t') ); ?>">
                                    <p class="description">Default text is Tweets.</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_following_t">Following Text</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_following_t" type="text" id="wpt_tp_following_t" value="<?php echo esc_attr( get_option('wpt_tp_following_t') ); ?>">
                                    <p class="description">Default text is Following.</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_followers_t">Followers Text</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_followers_t" type="text" id="wpt_tp_followers_t" value="<?php echo esc_attr( get_option('wpt_tp_followers_t') ); ?>">
                                    <p class="description">Default text is Followers.</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_k_t">K Character</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_k_t" type="text" id="wpt_tp_k_t" value="<?php echo esc_attr( get_option('wpt_tp_k_t') ); ?>">
                                    <p class="description">Default character is K.</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_m_t">M Character</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_m_t" type="text" id="wpt_tp_m_t" value="<?php echo esc_attr( get_option('wpt_tp_m_t') ); ?>">
                                    <p class="description">Default character is M.</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_joined_t">Joined Text</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_joined_t" type="text" id="wpt_tp_joined_t" value="<?php echo esc_attr( get_option('wpt_tp_joined_t') ); ?>">
                                    <p class="description">Default text is Joined.</p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_tp_opentweet_t">Open Tweet Text</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_tp_opentweet_t" type="text" id="wpt_tp_opentweet_t" value="<?php echo esc_attr( get_option('wpt_tp_opentweet_t') ); ?>">
                                    <p class="description">Default text is Open Tweet.</p>
								</td>
                        	</tr>

                    	</tbody>
                    </table>
                    
                    <p class="submit"><input id="submit" class="button button-primary" type="submit" name="submit" value="Save Changes"></p>
                </form>
                
            	<div class="tool-box">
					<h3 class="title">Recommended Links</h3>
					<p>Get collection of 87 WordPress themes for $69 only, a lot of features and free support! <a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Get it now</a>.</p>
					<p>See also:</p>
						<ul>
							<li><a href="http://j.mp/CM_WPTime" target="_blank">Premium WordPress themes on CreativeMarket.</a></li>
							<li><a href="http://j.mp/TF_WPTime" target="_blank">Premium WordPress themes on Themeforest.</a></li>
							<li><a href="http://j.mp/CC_WPTime" target="_blank">Premium WordPress plugins on Codecanyon.</a></li>
						</ul>
					<p><a href="http://j.mp/ET_WPTime_ref_pl" target="_blank"><img src="<?php echo plugins_url( '/banner/570x100.jpg', __FILE__ ); ?>"></a></p>
				</div>
                
            </div>
        <?php
	} // page function

?>