<?php
/*
Plugin Name: Social Links Widget
Plugin URI: http://themeout.com
Description: A simple Facebook Fan Page widget
Version: 1.1
Author: ThemeOut.com
Author URI: http://themeout.com
*/

class socialicons_widget extends WP_Widget {
 
    /** constructor -- name this the same as the class above */
    function socialicons_widget() {
        parent::WP_Widget(false, $name = 'Azkaban: Social Icons');	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {
        
        global $azkaban_options;
        
        extract( $args );
        $title 		= apply_filters('widget_title', $instance['title']);
        ?>

		<?php echo $before_widget; ?>
        	<?php if ( $title )
            	echo $before_title . $title . $after_title; ?>

		<div style="float:left; width:100%;">
			<div style="float:left; width:40px; padding:10px 0px;">
				<a href="<?php home_url(); ?>/rss/" target="_blank"><img src="<?php echo  get_template_directory_uri(); ?>/includes/widgets/images/social_rss.png" title="Subscribe To Our Rss" alt="Subscribe To Our Rss" width="32" /></a>
			</div>
			<div style="float:left;">
				<h5><a href="<?php home_url(); ?>/rss/" target="_blank">Subscribe To Rss</a></h5>
			</div>
		</div>

		<?php
//			if ($twitterUrl !== '' ) {
            if ( !empty($azkaban_options['twitter_url']) && $azkaban_options['twitter_url'] != ' ') {
		?>
		<div style="float:left; width:100%;">
			<div style="float:left; width:40px; padding:10px 0px;">
				<a href="<?php echo $azkaban_options['twitter_url']; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/includes/widgets/images/social_twitter.png" title="Follow Us On Twitter" alt="Follow Us On Twitter" width="32" /></a>
			</div>
			<div style="float:left;">
				<h5><a href="<?php echo $azkaban_options['twitter_url']; ?>" target="_blank">Follow Us On Twitter</a></h5>
			</div>
		</div>


		<?php } 
//			if ($facebookUrl !== '') {
            if ( !empty($azkaban_options['facebook_url']) && $azkaban_options['facebook_url'] != ' ' ) {
		?>
		<div style="float:left; width:100%;">
			<div style="float:left; width:40px; padding:10px 0px;">
				<a href="<?php echo $azkaban_options['facebook_url']; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/includes/widgets/images/social_facebook.png" title="Find Us On Facebook" alt="Find Us On Facebook" width="32" /></a>
			</div>
			<div style="float:left;">
				<h5><a href="<?php echo $azkaban_options['facebook_url']; ?>" target="_blank">Find Us On Facebook</a></h5>
			</div>
		</div>
		
		<?php } ?>

		<?php echo $after_widget; ?>
        <?php
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
 
        $title 		= esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'azkaban'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php 
    }
 
 
} // end class fans_widget
add_action('widgets_init', create_function('', 'return register_widget("socialicons_widget");'));
?>
