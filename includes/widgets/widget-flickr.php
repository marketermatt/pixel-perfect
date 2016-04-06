<?php
/*
Plugin Name: Flickr Photo Stream Widget
Plugin URI: http://themeout.com
Description: A simple Facebook Fan Page widget
Version: 1.1
Author: ThemeOut.com
Author URI: http://themeout.com
*/

function azkaban_flickr_jquery() {
	wp_enqueue_script('jquery');
}

add_action('widgets_init','azkaban_flickr_jquery');

class azkaban_Flickr extends WP_Widget {

	// constructor...
	function azkaban_Flickr() {
		$widget_ops = array( 	'classname' => 'azkaban-flickr', 
								'description' => __('A simple Flickr photo stream widget', 'azkaban')
		);

		$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'azkaban-flickr-widget' );
		$this->WP_Widget('azkaban-flickr-widget', __('Azkaban: Flickr Widget', 'azkaban'), $widget_ops, $control_ops);

}

	// displays/outputs the widgety goodness...
	function widget( $args, $instance ) {

 		extract($args);

		echo $args['before_widget'];

		$title = apply_filters('widget_title', $instance['title']);
		$flickrid = $instance['flickrid'];

		if ( $title )
			echo $before_title . $title . $after_title;

			// let's get into the javascript...
			?>
			<div id="az-flickrwrap">
			<ul id="flickr">&nbsp;</ul>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
				$.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?ids=<?php print $flickrid;?>&lang=en-us&format=json&jsoncallback=?", function(data){
					$.each(data.items, function(index, item) {
						if( index<=11) {
							$("<img/>").attr("src", item.media.m).appendTo("#flickr").wrap("<li><a href='" + item.link + "'></a></li>");
						}
					});
				});
			});
			</script>
			</div>
<?php  

		echo $args['after_widget'];
}


	// Upating the widget...	
	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title']);
		$instance['flickrid'] = strip_tags( $new_instance['flickrid']);
	
		return $instance;
	}	

	function form( $instance ) {
	?>
		<!-- widget title -->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'azkaban'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('flickrid'); ?>"><?php _e('Your Flickr User ID:', 'azkaban'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('flickrid'); ?>" name="<?php echo $this->get_field_name('flickrid'); ?>" value="<?php echo $instance['flickrid']; ?>" />
	 		<small>Don't know your ID? Head on over to <a href="http://idgettr.com">idgettr</a> to find it.</small>
	 </p>
		<?php
	}
}

add_action('widgets_init','load_azkaban_flickr');

function load_azkaban_flickr() {	
	register_widget('azkaban_Flickr');
}

?>