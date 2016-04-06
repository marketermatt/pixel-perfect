<?php 
/**
 * @package WordPress
 * @subpackage Azkaban
 */

/* Template Name: Contact Page Template */ 

get_header();

global $azkaban_options;

global $azkaban_options;
if( $azkaban_options['contactpage_layout'] == 1 ) {
	$content_position = 'grid-100 grid-parent';
}
else {
	$content_position = ( $azkaban_options['contactpage_layout'] == 3 ) ? 'grid-50 tablet-grid-100 mobile-grid-100 grid-parent push-50' : 'grid-50 tablet-grid-100 mobile-grid-100 grid-parent';
}

//If the form is submitted
if( isset($_POST['submit']) ) {
    // Get form vars
    $contact_name = strip_tags(trim(stripslashes($_POST['contact_name'])));
    $contact_email = trim($_POST['contact_email']);
    $contact_phone = trim($_POST["contact_phone{$NA_phone_format}"]);
    $contact_ext = trim($_POST["contact_ext{$NA_phone_format}"]);
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

    if( $NA_phone_format ) {

		if( !isPhoneNumberValid( $contact_phone ) || ( $contact_phone == '' && $contact_ext != '' ) ) {
		    $phoneError = __('phone number', 'azkaban');
		}
		if( !eregi("^[0-9]{0,5}$", $contact_ext) ) { // check if the extension consists of 1 to 5 digits, or empty
		    $extError = __('extension', 'azkaban');
		}
    }

    if( isset($phoneError) && isset($extError) ) {
		$phone_extError = sprintf(__('Please enter a valid %1$s and %2$s', 'azkaban'), $phoneError, $extError );
    } 
	else if( isset($phoneError) ) {
		$phone_extError = sprintf(__('Please enter a valid %s', 'azkaban'), $phoneError );
    }
	else if( isset($extError) ) {
		$phone_extError = sprintf(__('Please enter a valid %s', 'azkaban'), $extError );
    }

    if( $contact_message == '' ) {
		$messageError = __('Please enter your message', 'azkaban');
    }

    if( !isset($nameError) && !isset($emailError) && !isset($messageError) && !isset($rCaptcha_error) ) {

		$ext = ( $contact_ext != '' ) ? __('ext.', 'azkaban').$contact_ext : '';
		$phone = ( $contact_phone != '' ) ? __('Phone: ', 'azkaban').$contact_phone.' '.$ext."\r\n" : '';

		// Send email
		$email_address_to = get_option( 'admin_email' );
		$subject = sprintf(__('Contact Form submission from %s', 'azkaban'), get_option('blogname') );
		$message_contents = __("Sender's name: ", 'azkaban') . $contact_name . "\r\n" .
			    __('E-mail: ', 'azkaban') . $contact_email . "\r\n" .
			    $phone ."\r\n" .
			    __('Message: ', 'azkaban') . $contact_message . " \r\n";

		$header = "From: $contact_name <".$contact_email.">\r\n";
		$header .= "Reply-To: $contact_email\r\n";
		$header .= "Return-Path: $contact_email\r\n";
		$emailSent = ( @wp_mail( $email_address_to, $subject, $message_contents, $header ) ) ? true : false;

		$contact_name_thx = $contact_name;

		// Clear the form
		$contact_name = $contact_email = $contact_phone = $contact_ext = $contact_message = '';
    }
}

//Contact Information Fields from the Admin Options
$contact_field_array = array(
    array(
	'desc' => $azkaban_options['contact_field_name1'],
	'value' => $azkaban_options['contact_field_value1'] ),
    array(
	'desc' => $azkaban_options['contact_field_name2'],
	'value' => $azkaban_options['contact_field_value2'] ),
    array(
	'desc' => $azkaban_options['contact_field_name3'],
	'value' => $azkaban_options['contact_field_value3'] ),
    array(
	'desc' => $azkaban_options['contact_field_name4'],
	'value' => $azkaban_options['contact_field_value4'] ),
    array(
	'desc' => $azkaban_options['contact_field_name5'],
	'value' => $azkaban_options['contact_field_value5'] ),
    array(
	'desc' => $azkaban_options['contact_field_name6'],
	'value' => $azkaban_options['contact_field_value6'] ),
    array(
	'desc' => $azkaban_options['contact_field_name7'],
	'value' => $azkaban_options['contact_field_value7']
    )
);

?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="<?php echo $content_position; ?>" id="az-maincontent">

    <div class="grid-100 grid-parent az-pagewrapper">

        <?php the_content(__('Read more', 'azkaban'));?>

		<?php	
			// Contact Fields...
			if ( $azkaban_options['show_contact_fields'] ) : ?>
	    		<div class="grid-100 grid-parent" id="az-contactinfo ">
				<h2>Contact Details</h2>
 
				<?php		
					foreach( $contact_field_array as $field_array ) :
		    			if( $field_array['value'] != '' ) : ?>
							<div class="grid-30 tablet-grid-30 mobile-grid-30 az-contactfielddesc"><?php echo $field_array['desc']; ?>&nbsp;</div>
							<div class="grid-70 tablet-grid-70 mobile-grid-70 az-contactfieldvalue"><?php echo $field_array['value']; ?></div>
							<div class="clear"></div>
				<?php
					endif;
					endforeach;
				?>
	    		</div>
	    		<div class="clear"></div>
		<?php endif; ?>

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

		<form method="post" action="<?php echo the_permalink(); ?>#az-contactwrapper">
		<h3>Please use the form below to send us an email:</h3>
		<div>
		    <label for="contact_name"><?php esc_html_e('Name*', 'azkaban'); ?> </label>
		    <input type="text" id="contact_name" name="contact_name" size="30" class="required<?php if(isset($nameError)) echo ' error'; ?>" minlength="2" value="<?php echo esc_attr($contact_name); ?>" />
		    <input type="hidden" id="rules_contact_message" value="<?php esc_html_e( 'required', 'azkaban' ); ?>" />
		    <input type="hidden" id="contact_name_required" value="<?php esc_html_e( 'Please enter a name', 'azkaban' ); ?>" />
		    <input type="hidden" id="contact_name_min_length" value="<?php esc_html_e( 'Your name must consist of at least 2 characters', 'azkaban' ); ?>" />
<?php		    if(isset($nameError)) echo '<label class="error" for="contact_name" generated="true">'.$nameError.'</label>'; ?>
		</div>
		<div>
		    <label for="contact_email"><?php esc_html_e('E-Mail*', 'azkaban'); ?> </label>
		    <input type="email" id="contact_email" name="contact_email" size="30"  class="required email<?php if(isset($emailError)) echo ' error'; ?>" value="<?php echo esc_attr($contact_email); ?>" />
		    <input type="hidden" id="messages_contact_email" value="<?php esc_html_e( 'Please enter a valid email address', 'azkaban' ); ?>" />
<?php		    if(isset($emailError)) echo '<label class="error" for="contact_email" generated="true">'.$emailError.'</label>'; ?>
		</div>
		<div>
		    <label for="contact_phone"><?php esc_html_e('Phone', 'azkaban'); ?> </label>
		    <input type="text" id="contact_phone<?php echo $NA_phone_format; ?>" name="contact_phone<?php echo $NA_phone_format; ?>" size="14" class="phone<?php if(isset($phoneError)) echo ' error'; ?>" value="<?php echo esc_attr($contact_phone); ?>" maxlength="14" />
		    <label for="contact_ext"><?php esc_html_e('ext.', 'azkaban'); ?> </label>
		    <input type="text" id="contact_ext<?php echo $NA_phone_format; ?>" name="contact_ext<?php echo $NA_phone_format; ?>" size="5" class="ext<?php if(isset($extError)) echo ' error'; ?>" value="<?php echo esc_attr($contact_ext); ?>" maxlength="5" />
<?php		    if(isset($phone_extError)) echo '<label class="error" for="contact_phone" generated="true">'.$phone_extError.'</label>'; ?>
		</div>
		<div>
		    <label for="contact_message"><?php esc_html_e('Your Message*', 'azkaban'); ?> </label>
		    <textarea id="contact_message" name="contact_message" cols="40" rows="7" class="required<?php if(isset($messageError)) echo ' error'; ?>"><?php echo esc_attr($contact_message); ?></textarea>
		    <input type="hidden" id="messages_contact_message" value="<?php esc_html_e( '<br />Please enter your message', 'azkaban' ); ?>" />
<?php		    if(isset($messageError)) echo '<br /><label class="error" for="contact_message" generated="true">'.$messageError.'</label>'; ?>
		</div>

		<div>
			<input name="submit" class="submit" type="submit" value="<?php esc_attr_e('Submit', 'azkaban'); ?>"/><br />
		</div>
	    </form>

		</div> <!-- end of az-contactwrapper -->

    </div> <!-- end of az-pagewrapper -->

</div> <!-- end of az-maincontent -->
<?php endwhile; ?><?php endif; ?>
<?php wp_reset_query(); ?>

<?php
if( $azkaban_options['contactpage_layout'] != 1 ) {
    
    $sidebar_position = ( $azkaban_options['contactpage_layout'] != 2 ) ? 'grid-50 tablet-grid-100 mobile-grid-100 pull-50' : 'grid-50 tablet-grid-100 mobile-grid-100';
?>
    <div class="<?php echo $sidebar_position; ?>" id="az-sidebar" role="complementary">

   	<?php if( $azkaban_options['enable_sidebar_top_ad'] ) { ?>
	<?php if( $azkaban_options['sidebar_top_ad_code'] != "" ) { ?>
    <div class="az-sidebarsection">
		<center><?php echo $azkaban_options['sidebar_top_ad_code']; ?></center>
	</div>
	<?php } ?>
	<?php } ?>

    <?php get_sidebar('ContactSidebar'); ?>

   	<?php if( $azkaban_options['enable_sidebar_top_ad'] ) { ?>
	<?php if( $azkaban_options['sidebar_top_ad_code'] != "" ) { ?>
    <div class="az-sidebarsection">
		<center><?php echo $azkaban_options['sidebar_top_ad_code']; ?></center>
	</div>
	<?php } ?>
	<?php } ?>

    </div>
<?php    
}
?>

<?php get_footer(); ?>
