<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */

get_header();
?>
<div class="content">
<!--<img src="<?php //echo get_stylesheet_directory_uri();?>/images/slide.jpg" />-->
<?php
if( $azkaban_options['show_slider'] && is_front_page() ) {
    echo '<div class="slider">';
	switch ($azkaban_options['slider_type']) {
		case "1":
			get_template_part('templates/slider');
			break;
		case "2":
			layerslider($azkaban_options['layerslider_id']);
			break;
		case "3":
			revslider($azkaban_options['revolutionslider_id']);
			break;
		default:
			get_template_part('templates/slider');
	}
    echo '</div>';
}
?>
<!--slider-->
<div class="portfolio text">
<h1><?php esc_html_e( 'Portfolio', 'azkaban'); ?></h1>
<p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.', 'azkaban' ); ?></p>
<ul>
	<?php 	$args = array( 'posts_per_page'   => 8, 'post_type' => 'portfolio' );
			$posts_array = get_posts( $args );
			$myposts = get_posts( $args );
			foreach ( $myposts as $post ) : setup_postdata( $post );
			$featured_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	?>
				<li><img src="<?php echo $featured_img[0];?>" width="177" height="186"/></li>
	<?php 
			endforeach; 
			wp_reset_postdata();?>
</ul>
</div><!--portfolio-->
<div class="about text">
<h1><?php esc_html_e( 'About Us', 'azkaban' ); ?></h1>
<p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.', 'azkaban' ); ?></p>
	
	<?php 	$args = array( 'posts_per_page'   => 3, 'post_type' => 'team' );
			$posts_array = get_posts( $args );
			$myposts = get_posts( $args );
			foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
				<div class="agents">
				<?php 
				$profile_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				echo "<img src='".$profile_img[0]."' width='220' height='227' />"; ?>
				<h2><?php the_title();?></h2>
				<h4><?php echo get_post_meta($post->ID,'designation',true);?></h4>
				<p><?php the_content();?></p>
				</div><!--agents-->
	<?php 
			endforeach; 
			wp_reset_postdata();?>
			
</div><!--about-->
<div class="contact text">
<h1>Contact Us</h1>
<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
<div class="contact-left">
<?php
//If the form is submitted
if( isset($_POST['submit']) ) {
    // Get form vars
    $contact_name = strip_tags(trim(stripslashes($_POST['contact_name'])));
    $contact_email = trim($_POST['contact_email']);
    $contact_message = strip_tags(trim(stripslashes($_POST['contact_message'])));

    // Error checking if JS is turned off
    if( $contact_name == '' ) { //Check to make sure that the name field is not empty
		$nameError = __('Please enter a name', 'azkaban');
    } else if( strlen($contact_name) < 2 ) {
		$nameError = __('Your name must consist of at least 2 characters', 'azkaban');
    }

    if( $contact_email == '' ) {
		$emailError = __('Please enter a valid email address', 'azkaban');
    } else if( !is_email( $contact_email ) ) {
		$emailError = __('Please enter a valid email address', 'azkaban');
    }

    if( $contact_message == '' ) {
		$messageError = __('Please enter your message', 'azkaban');
    }

    if( !isset($nameError) && !isset($emailError) && !isset($messageError) ) {

		// Send email
		$email_address_to = get_option( 'admin_email' );
		$subject = sprintf(__('Contact Form submission from %s', 'azkaban'), get_option('blogname') );
		$message_contents = __("Sender's name: ", 'azkaban') . $contact_name . "\r\n" .
			    __('E-mail: ', 'azkaban') . $contact_email . "\r\n" .
			    __('Message: ', 'azkaban') . $contact_message . " \r\n";

		$header = "From: $contact_name <".$contact_email.">\r\n";
		$header .= "Reply-To: $contact_email\r\n";
		$header .= "Return-Path: $contact_email\r\n";
		$emailSent = ( @wp_mail( $email_address_to, $subject, $message_contents, $header ) ) ? true : false;

		$contact_name_thx = $contact_name;

		// Clear the form
		$contact_name = $contact_email = $contact_message = '';
    }
}
?>
	<div class="grid-100 grid-parent" id="az-contactwrapper">
		<?php
		// Message Area.  It shows a message upon successful email submission
	    if( isset( $emailSent ) && $emailSent == true ) : ?>
		<div class="success">
		    <div class="msg-box-icon">
				<strong><?php esc_html_e('Email Successfully Sent!', 'azkaban'); ?></strong><br />
				<?php printf(__('Thank you <strong>%s</strong> for using our contact form! Your email was successfully sent and we will be in touch with you shortly.', 'azkaban'), $contact_name_thx) ?>
		    </div>
		</div>
		<?php
		elseif ( isset( $emailSent ) && $emailSent == false ) : ?>
		<div class="erroneous">
			<div class="msg-box-icon">
				<?php esc_html_e('Failed to connect to mailserver!', 'azkaban'); ?>
			</div>
		</div>
		<?php endif; ?>
	<form method="post" action="<?php echo home_url(); ?>#az-contactwrapper">
		
		<input type="text" id="contact_name" name="contact_name" size="30" class="required<?php if(isset($nameError)) echo ' error'; ?>" minlength="2" value="<?php echo esc_attr($contact_name); ?>" placeholder="Your name" />
		    <input type="hidden" id="rules_contact_message" value="<?php esc_html_e( 'required', 'azkaban' ); ?>" />
		    <input type="hidden" id="contact_name_required" value="<?php esc_html_e( 'Please enter a name', 'azkaban' ); ?>" />
		    <input type="hidden" id="contact_name_min_length" value="<?php esc_html_e( 'Your name must consist of at least 2 characters', 'azkaban' ); ?>" />
		<?php if(isset($nameError)) echo '<label class="error" for="contact_name" generated="true">'.$nameError.'</label>'; ?>
		
		    <input type="email" id="contact_email" name="contact_email" size="30"  class="required email<?php if(isset($emailError)) echo ' error'; ?>" value="<?php echo esc_attr($contact_email); ?>" placeholder="Your email" />
		    <input type="hidden" id="messages_contact_email" value="<?php esc_html_e( 'Please enter a valid email address', 'azkaban' ); ?>" />
		<?php if(isset($emailError)) echo '<label class="error" for="contact_email" generated="true">'.$emailError.'</label>'; ?>

		<textarea id="contact_message" name="contact_message" cols="40" rows="7" class="required<?php if(isset($messageError)) echo ' error'; ?>" placeholder="Your message"><?php echo esc_attr($contact_message); ?></textarea>
		    <input type="hidden" id="messages_contact_message" value="<?php esc_html_e( '<br />Please enter your message', 'azkaban' ); ?>" />
		<?php if(isset($messageError)) echo '<br /><label class="error" for="contact_message" generated="true">'.$messageError.'</label>'; ?>

		<input name="submit" class="submit" type="submit" value="<?php esc_attr_e('Submit', 'azkaban'); ?>"/>
	
	</form>
	</div> <!-- end of az-contactwrapper -->
</div>
<div class="contact-right">
<div class="inform">
<h5>INFORMATION</h5>
<p><i class="fa fa-map-marker"></i><?php echo $azkaban_options['contact_address'];?></p>
<p><i class="fa fa-phone-square"></i><?php echo $azkaban_options['contact_phone'];?></p>
<p><i class="fa fa-print"></i><?php echo $azkaban_options['contact_fax'];?></p>
<p><i class="fa fa-envelope-o"></i><?php echo $azkaban_options['contact_email'];?></p>
</div>
</div>
</div><!--contact-->
</div><!--content-->
<?php get_footer(); ?>
