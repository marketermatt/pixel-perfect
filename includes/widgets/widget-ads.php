<?php

##ADS 125*125 ------------------------------------------ #
add_action( 'widgets_init', 'ads125_widget_box' );
function ads125_widget_box() {
	register_widget( 'ads125_widget' );
}
class ads125_widget extends WP_Widget {
	function ads125_widget() {
		$widget_ops = array( 'classname' => 'ads125-widget', 'description' => ''  );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'ads125-widget' );
		$this->WP_Widget( 'ads125-widget', 'Azkaban: Ads 125*125', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$tran_bg = $instance['tran_bg'];
		$new_window = $instance['new_window'];
		$one_column = $instance['one_column'];
		$nofollow = $instance['nofollow'];
		
		
		if( $new_window ) $new_window =' target="_blank" ';
		else $new_window ='';
		
		if( $one_column ) $one_column =' ads-one';
		else $one_column ='';
		
		if( $nofollow ) $nofollow='rel="nofollow"';
		else $nofollow ='';
		
		if( !$tran_bg ){
			echo $before_widget;
			echo $before_title;
			echo $title ; 
			echo $after_title;
		}?>
		<div <?php if( $tran_bg ) echo 'id="'.$args['widget_id'].'"'; ?> class="az-ads125<?php echo $one_column ?>">
		<?php for($i=1 ; $i<11 ; $i++ ){ ?>
			<?php if( !empty($instance[ 'ads'.$i.'_code' ])  ){ ?>
			<div class="az-adcell">
				<?php echo do_shortcode( $instance[ 'ads'.$i.'_code' ] ); ?>

			</div>
		<?php } elseif( !empty($instance[ 'ads'.$i.'_img' ])  ){ ?>
			<div class="az-adcell">
				<?php if( $instance[ 'ads'.$i.'_url' ] ){ ?><a href="<?php echo $instance[ 'ads'.$i.'_url' ] ?>" <?php echo $new_window ?> <?php echo $nofollow ?>><?php } ?>
					<img src=" <?php echo $instance[ 'ads'.$i.'_img' ] ?> " alt="" />
				<?php if( $instance[ 'ads'.$i.'_url' ] ){ ?></a><?php } ?>
			</div>
		<?php
			}
		} ?>
		</div>
	<?php 
		if( !$tran_bg )
			echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['tran_bg'] = strip_tags( $new_instance['tran_bg'] );
		$instance['new_window'] = strip_tags( $new_instance['new_window'] );
		$instance['one_column'] = strip_tags( $new_instance['one_column'] );
		$instance['nofollow'] = strip_tags( $new_instance['nofollow'] );

		for($i=1 ; $i<11 ; $i++ ){ 
			$instance['ads'.$i.'_img'] = strip_tags( $new_instance['ads'.$i.'_img'] );
			$instance['ads'.$i.'_url'] = strip_tags( $new_instance['ads'.$i.'_url'] );
			$instance['ads'.$i.'_code'] =  $new_instance['ads'.$i.'_code'] ;
		}
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => __(' Advertisement', 'azkaban') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty( $instance['title'] ) ) echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tran_bg' ); ?>">Transparent Background :</label>
			<input id="<?php echo $this->get_field_id( 'tran_bg' ); ?>" name="<?php echo $this->get_field_name( 'tran_bg' ); ?>" value="true" <?php if( !empty( $instance['tran_bg'] ) )  echo 'checked="checked"'; ?> type="checkbox" />
			<br /><small>if this active the title will disappear</small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'new_window' ); ?>">Open links in a new window:</label>
			<input id="<?php echo $this->get_field_id( 'new_window' ); ?>" name="<?php echo $this->get_field_name( 'new_window' ); ?>" value="true" <?php if( !empty( $instance['new_window'] ) ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'nofollow' ); ?>">Nofollow</label>
			<input id="<?php echo $this->get_field_id( 'nofollow' ); ?>" name="<?php echo $this->get_field_name( 'nofollow' ); ?>" value="true" <?php if( !empty( $instance['nofollow'] ) )  echo 'checked="checked"'; ?> type="checkbox" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'one_column' ); ?>">One column:</label>
			<input id="<?php echo $this->get_field_id( 'one_column' ); ?>" name="<?php echo $this->get_field_name( 'one_column' ); ?>" value="true" <?php if( !empty( $instance['one_column'] ) )  echo 'checked="checked"'; ?> type="checkbox" />
		</p>
		<?php 
		for($i=1 ; $i<11 ; $i++ ){ ?>
		<em style="display:block; border-bottom:1px solid #CCC; margin:20px 0 5px; font-weight:bold">ADS <?php echo $i; ?> :</em>
		<p>
			<label for="<?php echo $this->get_field_id( 'ads'.$i.'_img' ); ?>">Ads <?php echo $i; ?> image path : </label>
			<input id="<?php echo $this->get_field_id( 'ads'.$i.'_img' ); ?>" name="<?php echo $this->get_field_name( 'ads'.$i.'_img' ); ?>" value="<?php if( !empty( $instance['ads'.$i.'_img'] ) ) echo $instance['ads'.$i.'_img']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'ads'.$i.'_url' ); ?>">Ads <?php echo $i; ?> link : </label>
			<input id="<?php echo $this->get_field_id( 'ads'.$i.'_url' ); ?>" name="<?php echo $this->get_field_name( 'ads'.$i.'_url' ); ?>" value="<?php if( !empty( $instance['ads'.$i.'_url'] ) ) echo $instance['ads'.$i.'_url']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'ads'.$i.'_code' ); ?>"><strong> OR : </strong> Ads <?php echo $i; ?> adsense code </label>
			<textarea id="<?php echo $this->get_field_id( 'ads'.$i.'_code' ); ?>" name="<?php echo $this->get_field_name( 'ads'.$i.'_code' ); ?>" class="widefat" ><?php if( !empty( $instance['ads'.$i.'_code'] ) ) echo $instance['ads'.$i.'_code']; ?></textarea>
		</p>
		<?php } ?>
	<?php
	}
}

?>