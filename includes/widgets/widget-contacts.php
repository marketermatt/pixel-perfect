<?php
/*
Plugin Name: Contacts Widget
Plugin URI: http://themeout.com
Description: A simple contact widget
Version: 1.1
Author: ThemeOut.com
Author URI: http://themeout.com
*/

class contacts_widget extends WP_Widget {
 
     /** constructor -- name this the same as the class above */
    function contacts_widget() {
        parent::WP_Widget(false, $name = 'Azkaban: Contacts');	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $name       = esc_attr($instance['name']);
        $address    = esc_attr($instance['address']);
        $telephone  = esc_attr($instance['telephone']);
        $email      = esc_attr($instance['email']);
        $website	= esc_attr($instance['website']);
        
        ?>
        <?php echo $before_widget; ?>
        <?php if( $title ) echo $before_title . $title . $after_title; ?>
        <div id="az-contacts">
            <div class="az-ctitle"><?php echo $name; ?></div>
            <div class="az-caddress"><i class="fa fa-map-marker"></i><?php echo $address; ?></div>
            <div class="az-ctelephone"><i class="fa fa-phone"></i><?php echo $telephone; ?></div>
            <div class="az-cemail"><i class="fa fa-envelope-o"></i><?php echo $email; ?></div>
            <div class="az-cwebsite"><i class="fa fa-external-link"></i><a href="<?php echo $website; ?>" target="_blank"><?php echo $website; ?></a></div>
        </div>
        <?php echo $after_widget; ?>
        <?php
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['name'] = strip_tags($new_instance['name']);
		$instance['address'] = $new_instance['address'];
		$instance['telephone'] = $new_instance['telephone'];
		$instance['email'] = $new_instance['email'];
		$instance['website'] = $new_instance['website'];
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
 
        $title      = esc_attr($instance['title']);
        $name       = esc_attr($instance['name']);
        $address    = esc_attr($instance['address']);
        $telephone  = esc_attr($instance['telephone']);
        $email      = esc_attr($instance['email']);
        $website	= esc_attr($instance['website']);
        
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'azkaban'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('name'); ?>">Name:</label> 
          <input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo $name; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('address'); ?>">Address:</label> 
          <input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo $address; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('telephone'); ?>">Telephone:</label> 
          <input class="widefat" id="<?php echo $this->get_field_id('telephone'); ?>" name="<?php echo $this->get_field_name('telephone'); ?>" type="text" value="<?php echo $telephone; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('email'); ?>">Email:</label> 
          <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('website'); ?>">Website:</label> 
          <input class="widefat" id="<?php echo $this->get_field_id('website'); ?>" name="<?php echo $this->get_field_name('website'); ?>" type="text" value="<?php echo $website; ?>" />
        </p>
        <?php 
    }
 
 
} // end class fans_widget
add_action('widgets_init', create_function('', 'return register_widget("contacts_widget");'));
?>
