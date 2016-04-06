<?php

// Link to seperated functions
require_once (TEMPLATEPATH . '/functions/theme-functions.php');
require_once (TEMPLATEPATH . '/functions/kreisi_pagination.php');
require_once (TEMPLATEPATH . '/functions/custom-woocommerce.php');
require_once (TEMPLATEPATH . '/functions/custom-functions.php');
// Metaboxes
include_once get_template_directory() . '/includes/metaboxes.php' ;

// Must-use Plugins
include_once get_template_directory() . '/lib/plugins/multiple_sidebars.php';
include("includes/plugin-notifier.php");

if( ! isset( $content_width ) ) $content_width = 780;

$args = array(
	'width'         => 1180,
	'height'        => 100,
	'default-image' => '',
);
add_theme_support( 'custom-header', $args );
$args = array(
	'default-color' => 'ffffff',
	'default-image' => '',
);
add_theme_support( 'custom-background', $args );

// Register the script first.
wp_register_script( 'azkaban_jsvars', get_template_directory_uri() . '/assets/javascripts/jsvar.js' );

// Now we can localize the script with our data.
$var_array = array(
        'theme_url' 					=> get_template_directory_uri(),
        'dropdown_goto' 				=> __('Go to...', 'azkaban'),
        'mobile_nav_cart' 				=> __('Shopping Cart', 'azkaban'),
        'testimonials_speed' 			=> '4000',
    );
wp_localize_script( 'azkaban_jsvars', 'js_local_vars', $var_array );

// The script can be enqueued now or later.
wp_enqueue_script( 'azkaban_jsvars' );

/****************************************************************************/
/* Ratina Display */
/****************************************************************************/
add_filter( 'wp_generate_attachment_metadata', 'retina_support_attachment_meta', 10, 2 );
/**
 * Retina images
 * This function is attached to the 'wp_generate_attachment_metadata' filter hook.
 */
function retina_support_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }
 
    return $metadata;
}
/**
 * Create retina-ready images
 *
 * Referenced via retina_support_attachment_meta().
 */
function retina_support_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
 
            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );
 
            $info = $resized_file->get_size();
 
            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}
add_filter( 'delete_attachment', 'delete_retina_support_images' );
/**
 * Delete retina-ready images
 *
 * This function is attached to the 'delete_attachment' filter hook.
 */
function delete_retina_support_images( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
    $path = pathinfo( $meta['file'] );
    foreach ( $meta as $key => $value ) {
        if ( 'sizes' === $key ) {
            foreach ( $value as $sizes => $size ) {
                $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
                $retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
                if ( file_exists( $retina_filename ) )
                    unlink( $retina_filename );
            }
        }
    }
}


/****************************************************************************/
/* Load Custon Styles and Java Scripts*/
/****************************************************************************/
function load_cb_scripts() {

    if (is_admin()) return;

	// My Scripts
    wp_enqueue_script('jquery');
    wp_register_script('jqueryui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.5.3/jquery-ui.min.js');
    wp_enqueue_script('jqueryui');

    wp_deregister_script( 'modernizr' );
    wp_register_script( 'modernizr', get_template_directory_uri() . '/assets/javascripts/modernizr-min.js');
    wp_enqueue_script( 'modernizr' );
    wp_register_script( 'flexslider', get_template_directory_uri() . '/assets/javascripts/jquery.flexslider-min.js');
    wp_enqueue_script('flexslider');
    wp_deregister_script( 'jquery.carouFredSel' );
	wp_register_script( 'jquery.carouFredSel', get_template_directory_uri() . '/assets/javascripts/jquery.carouFredSel-6.2.1-min.js');
	wp_enqueue_script( 'jquery.carouFredSel' );
    wp_register_script('scripts', get_template_directory_uri() . '/assets/javascripts/script.js');
    wp_enqueue_script('scripts');

	// My Styles
    wp_enqueue_style('widget-style', get_template_directory_uri() . '/assets/stylesheets/widget-styles.css');
    wp_enqueue_style('form-style', get_template_directory_uri() . '/assets/stylesheets/form-styles.css');
    wp_enqueue_style('mobile-menu', get_template_directory_uri() . '/assets/stylesheets/mobile-menu.css');
    wp_enqueue_style('fontawesome-style', get_template_directory_uri() . '/fonts/fontawesome/font-awesome.css');
    wp_enqueue_style('slider-style', get_template_directory_uri() . '/assets/stylesheets/slider-styles.css');
    wp_enqueue_style('woocommerce-style', get_template_directory_uri() . '/assets/stylesheets/woocommerce-style.css');

}
add_action('init', 'load_cb_scripts');


/****************************************************************************/
/* Thumbnail Support */
/****************************************************************************/
add_theme_support('post-thumbnails');
if ( function_exists( 'add_theme_support' ) ) { add_theme_support( 'post-thumbnails', array( 'post' , 'page', 'portfolio', 'product', 'team', 'portfolio' ) ); }
add_image_size( 'large-featured', 1180, 450, true );
add_image_size( 'medium-featured', 800, 250, true );
add_image_size( 'woo-featured', 340, 190, true );
add_image_size( 'small-thumbnail', 120, 70, true  );
add_image_size( 'square-thumbnail', 460, 460, true  );

/****************************************************************************/
/* Post Format Support */
/****************************************************************************/
add_theme_support('post-formats', array('aside', 'gallery', 'video', 'link', 'quote', 'image', 'audio'));
add_theme_support('automatic-feed-links');
add_post_type_support('post', 'post-formats');

/****************************************************************************/
// Allows shortcodes to be displayed in sidebar widgets
/****************************************************************************/
add_filter('widget_text', 'do_shortcode');

/****************************************************************************/
/* Register Menu */
/****************************************************************************/
function register_my_menus() {
	register_nav_menu('top-navleft', 'Top Navigation Left');
	register_nav_menu('top-navright', 'Top Navigation Right');
	register_nav_menu('site-nav', 'Main Navigation');
	register_nav_menu('mobile-nav', 'Mobile Navigation');
}
add_action( 'init', 'register_my_menus' );


/**
 * Add Redux Framework & extras
 */
require get_template_directory() . '/themeoptions/admin-init.php';


// Link to Widgets
require_once (TEMPLATEPATH . '/includes/widgets/widget_areas.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-video.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-facebook-fans.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-recent-posts.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-random-posts.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-flickr.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-googleMap.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-social-icons.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-twitter.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-ads.php');
require_once (TEMPLATEPATH . '/includes/widgets/widget-contacts.php');


add_action('init','of_options');

if (!function_exists('of_options')) {
      function of_options()
      {
        //Register sidebar options for blog/portfolio/woocommerce category/archive pages
        global $wp_registered_sidebars;
        $sidebar_options[] = 'None';
        for($i=0;$i<1;$i++){
            $sidebars = $wp_registered_sidebars;// sidebar_generator::get_sidebars();
            //var_dump($sidebars);
            if(is_array($sidebars) && !empty($sidebars)){
                foreach($sidebars as $sidebar){
                    $sidebar_options[] = $sidebar['name'];
                }
            }
            $sidebars = sidebar_generator::get_sidebars();
            if(is_array($sidebars) && !empty($sidebars)){
                foreach($sidebars as $key => $value){
                    $sidebar_options[] = $value;
                }
            }
        }
    }
}


?>