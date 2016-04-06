<?php

add_action( 'widgets_init', 'latest_tweet_widget' );
function latest_tweet_widget() {
	register_widget( 'NK_Latest_Tweets' );
}
class NK_Latest_Tweets extends WP_Widget {

	function NK_Latest_Tweets() {
		$widget_ops = array( 'classname' => 'twitter-widget'  );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'latest-tweets-widget' );
		$this->WP_Widget( 'latest-tweets-widget', 'Azkaban: Latest Tweets', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
	   
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$no_of_tweets = $instance['no_of_tweets'];
	    $cacheTime = 20;
        
		$twitter_username 		= $instance['twitter_username'];
		$consumerKey 			= $instance['consumer_key'];
		$consumerSecret			= $instance['consumer_secret'];

        if( !empty($twitter_username) && !empty($consumerKey) && !empty($consumerSecret) ){

            $token = get_option('NK_TwitterToken');
            $twitterData = get_transient('list_tweets');

            if( empty( $twitterData ) ){
		 
                // getting new auth bearer only if we don't have one
                if(!$token) {
                    // preparing credentials
                    $credentials = $consumerKey . ':' . $consumerSecret;
                    $toSend = base64_encode($credentials);
		 
                    // http post arguments
                    $args = array(
                        'method' => 'POST',
                        'httpversion' => '1.1',
                        'blocking' => true,
                        'headers' => array(
                            'Authorization' => 'Basic ' . $toSend,
                            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
                        ),
                        'body' => array( 'grant_type' => 'client_credentials' )
                    );
		 
                    add_filter('https_ssl_verify', '__return_false');
				    $response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
		 
                    $keys = json_decode(wp_remote_retrieve_body($response));
		 
                    if($keys) {
                        // saving token to wp_options table
                        update_option('NK_TwitterToken', $keys->access_token);
                        $token = $keys->access_token;
                    }
                }
			
                // we have bearer token wether we obtained it from API or from options
                $args = array(
                    'httpversion' => '1.1',
                    'blocking' => true,
                    'headers' => array(
                        'Authorization' => "Bearer $token"
                    )
                );
		 
                add_filter('https_ssl_verify', '__return_false');
                $api_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$twitter_username&count=$no_of_tweets";
                $response = wp_remote_get($api_url, $args);

                if (!is_wp_error($response)) {
                    $twitterData = json_decode(wp_remote_retrieve_body($response));
                    set_transient('list_tweets', $twitterData, 60 * $cacheTime);
                } 
            }
		
            echo $before_widget;
            echo $before_title; ?>
			<?php echo $title ; ?>
            <?php echo $after_title; 
                if( is_array($twitterData)){
                    $i=0;
					$hyperlinks = true;
					$twitter_users = true;
					$update = true;
					echo '<div id="az-recenttwitterwrap" ><ul class="az-twitterupdatelist">';
		            foreach($twitterData as $item){
		                    $msg = $item->text;
		                    $permalink = 'http://twitter.com/#!/'. $twitter_username .'/status/'. $item->id_str;
		                    if($encode_utf8) $msg = utf8_encode($msg);
		                    $link = $permalink;
                            echo '<li class="az-twitteritem">';
                                if ($hyperlinks) {    $msg = $this->hyperlinks($msg); }
                                if ($twitter_users)  { $msg = $this->twitter_users($msg); }
                            echo $msg;
		                    if($update) {
                                $time = strtotime($item->created_at);
                            if ( ( abs( time() - $time) ) < 86400 )
                                $h_time = sprintf( __('%s ago', 'azkaban'), human_time_diff( $time ) );
                            else
                                $h_time = date(__('Y/m/d', 'azkaban'), $time);
                            echo sprintf( __('%s', 'azkaban'),' <small class="az-timestamp"><abbr title="' . date(__('Y/m/d H:i:s', 'azkaban'), $time) . '">' . $h_time . '</abbr></small>' );
                            }
                            echo '</li>';
		                    $i++;
		                    if ( $i >= $no_of_tweets ) break;
		            }
					echo '</ul> </div>';
            	}
				else{ 
					?> <a href="http://twitter.com/<?php echo $twitter_username  ?>"><?php echo $title ; ?></a> 
<?php			}
            ?>
		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	else{
		echo $before_widget;
		echo $before_title; ?>
			<a href="http://twitter.com/<?php echo $twitter_username  ?>"><?php echo $title ; ?></a>
		<?php echo $after_title; 
		echo $after_widget;
	}
}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['no_of_tweets'] = strip_tags( $new_instance['no_of_tweets'] );
		$instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
		$instance['consumer_key'] = strip_tags( $new_instance['consumer_key'] );
		$instance['consumer_secret'] = strip_tags( $new_instance['consumer_secret'] );
		$instance['access_token'] = strip_tags( $new_instance['access_token'] );
		$instance['access_token_secret'] = strip_tags( $new_instance['access_token_secret'] );

		delete_transient('list_tweets');
		return $instance;
	}

	function form( $instance ) {
	   
		$defaults = array( 'title' =>__('Recent Tweets' , 'azkaban') , 'no_of_tweets' => '5' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		$twitter_username 		= isset($instance['twitter_username']) ? esc_attr($instance['twitter_username']) : '';
		$consumer_key 			= isset($instance['consumer_key']) ? esc_attr($instance['consumer_key']) : '';
		$consumer_secret		= isset($instance['consumer_secret']) ? esc_attr($instance['consumer_secret']) : '';
		$access_token 			= isset($instance['access_token']) ? esc_attr($instance['access_token']) : '';
		$access_token_secret 	= isset($instance['access_token_secret']) ? esc_attr($instance['access_token_secret']) : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'no_of_tweets' ); ?>">Number of Tweets to show: </label>
			<input id="<?php echo $this->get_field_id( 'no_of_tweets' ); ?>" name="<?php echo $this->get_field_name( 'no_of_tweets' ); ?>" value="<?php echo $instance['no_of_tweets']; ?>" type="text" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_username' ); ?>">Twitter Username: </label>
			<input id="<?php echo $this->get_field_id( 'twitter_username' ); ?>" name="<?php echo $this->get_field_name( 'twitter_username' ); ?>" value="<?php echo $instance['twitter_username']; ?>" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'consumer_key' ); ?>">Consumer Key: </label>
			<input id="<?php echo $this->get_field_id( 'consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'consumer_key' ); ?>" value="<?php echo $instance['consumer_key']; ?>" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'consumer_secret' ); ?>">Consumer Secret: </label>
			<input id="<?php echo $this->get_field_id( 'consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'consumer_secret' ); ?>" value="<?php echo $instance['consumer_secret']; ?>" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'access_token' ); ?>">Access Token: </label>
			<input id="<?php echo $this->get_field_id( 'access_token' ); ?>" name="<?php echo $this->get_field_name( 'access_token' ); ?>" value="<?php echo $instance['access_token']; ?>" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'access_token_secret' ); ?>">Access Token Secret: </label>
			<input id="<?php echo $this->get_field_id( 'access_token_secret' ); ?>" name="<?php echo $this->get_field_name( 'access_token_secret' ); ?>" value="<?php echo $instance['access_token_secret']; ?>" type="text" />
		</p>

	<?php
	}
	
		/**
	 * Find links and create the hyperlinks
	 */
	private function hyperlinks($text) {
	    $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);
	    // match name@address
	    $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
	        //mach #trendingtopics. Props to Michael Voigt
	    $text = preg_replace('/([\.|\,|\:|\?|\?|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $text);
	    return $text;
	}
	/**
	 * Find twitter usernames and link to them
	 */
	private function twitter_users($text) {
	       $text = preg_replace('/([\.|\,|\:|\?|\?|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
	       return $text;
	}
	
}
?>