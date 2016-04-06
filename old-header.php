<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php global $azkaban_options; ?>
<?php
$favicon_url = ( $azkaban_options['custom_favicon_img']['url'] ) ? $azkaban_options['custom_favicon_img']['url'] : get_stylesheet_directory_uri().'/images/favicon.ico';
?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo $favicon_url; ?>" />
<link rel="icon" type="image/x-icon" href="<?php echo $favicon_url; ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS FEED" href="<?php bloginfo('rss2_url'); ?>" />

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="robots" content="follow, all" />
<meta content="True" name="HandheldFriendly" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans|Oswald:300,400,700|PT+Sans|Antic+Slab' rel='stylesheet' type='text/css' />

<!--[if lt IE 9]>
  <script src="<?php echo get_template_directory_uri(); ?>/assets/javascripts/html5.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/reset.css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/text.css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/unsemantic-grid-base.css" />
<noscript>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/unsemantic-grid-mobile.css" />
</noscript>
<script type="text/javascript">
  var ADAPT_CONFIG = {path: '<?php echo get_template_directory_uri(); ?>/assets/stylesheets/',
    dynamic: true,
    range: [
      '0 to 360px = unsemantic-grid-mobile.css',
      '361 to 801px = unsemantic-grid-tablet.css',
      '802px = unsemantic-grid-desktop.css'
    ]
  };
</script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/javascripts/adapt.min.js"></script>
<style type="text/css" media="screen">
<!-- @import url( <?php bloginfo('stylesheet_url'); ?> ); -->
</style>

<?php
if( $azkaban_options['header_embed_codes'] != "" ) {
	echo $azkaban_options['header_embed_codes'];
}
?>
<?php if( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
<?php
$object_id = get_queried_object_id();
if( (get_option('show_on_front') && get_option('page_for_posts') && is_home()) ||
    (get_option('page_for_posts') && is_archive() && !is_post_type_archive()) && !(is_tax('product_cat') || is_tax('product_tag')) || (get_option('page_for_posts') && is_search())) {
    $c_pageID = get_option('page_for_posts');
}
else {
    if(isset($object_id)) {
        $c_pageID = $object_id;
    }
    
    if( class_exists('Woocommerce') ) {
        if(is_shop() || is_tax('product_cat') || is_tax('product_tag')) {
            $c_pageID = get_option('woocommerce_shop_page_id');
        }
    }
}
?>
<?php
    $logo_img_url = ( $azkaban_options['custom_logo_img']['url'] ) ? $azkaban_options['custom_logo_img']['url'] : get_stylesheet_directory_uri().'/images/logo.png';
    $customHeaderPadding = $azkaban_options['header_padding_top'] + $azkaban_options['header_padding_bottom'];
?>
<style type="text/css">
	<?php
		$custom_logo_type = $azkaban_options['custom_logo_type'];
		 
		if($custom_logo_type=='logo_img')
		{
	?>
    #az-logo h1 a { background:transparent url( <?php echo esc_url($logo_img_url); ?> ) no-repeat left center; }
    #az-fulllogo h1 a { background:transparent url( <?php echo esc_url($logo_img_url); ?> ) no-repeat center center; }
	<?php } ?>
    #az-logo {
        padding-top: <?php echo $azkaban_options['header_padding_bottom']; ?>px;
        padding-bottom: <?php echo $azkaban_options['header_padding_bottom']; ?>px;
    }
    #az-navigationright {
        min-height: <?php echo ($customHeaderPadding+100); ?>px;
    }
    .az-sitenavr a {
        line-height: <?php echo ($customHeaderPadding+100); ?>px;
    }
    .az-menualertr {
        line-height: <?php echo ($customHeaderPadding+100); ?>px;
    }
    .az-sitenavr {
	   height: <?php echo ($customHeaderPadding+100); ?>px;
    }
    #az-headertagline {
	   line-height: <?php echo ($customHeaderPadding+100); ?>px;
    }
    #az-headerads {
	   padding-top: <?php echo ($azkaban_options['header_padding_top']+20); ?>px;
    }
	<?php if( get_post_meta($c_pageID, 'nand_page_title_height', true) ) : ?>
    #az-pagetitle {
		height: <?php echo get_post_meta($c_pageID, 'nand_page_title_height', true); ?>;
	}
	<?php elseif($azkaban_options['page_title_height']): ?>
	#az-pagetitle {
		height: <?php echo $azkaban_options['page_titlebar_height']; ?>;
	}
	<?php endif; ?>
    <?php if( get_post_meta($c_pageID, 'nand_page_title_bar_bg', true) ) : ?>
    #az-pagetitlewrap {
        background-image: url(<?php echo get_post_meta($c_pageID, 'nand_page_title_bar_bg', true); ?>);
    }
	<?php elseif( $azkaban_options['page_title_bg'] ): ?>
	#az-pagetitlewrap {
		background-image: url(<?php echo $azkaban_options['page_title_bg']; ?>);
	}
	<?php endif; ?>
	<?php if( get_post_meta($c_pageID, 'nand_page_title_bar_bg_color', true) ): ?>
	#az-pagetitlewrap {
		background-color: <?php echo get_post_meta($c_pageID, 'nand_page_title_bar_bg_color', true); ?>;
	}
	<?php elseif( $azkaban_options['page_title_bg_color'] ): ?>
	#az-pagetitlewrap {
		background-color: <?php echo $azkaban_options['page_title_bg_color']; ?>;
	}
	<?php endif; ?>
    <?php if(get_post_meta($c_pageID, 'nand_main_top_padding', true)): ?>
	#az-container {
		padding-top: <?php echo get_post_meta($c_pageID, 'nand_main_top_padding', true); ?>;
    }
	<?php endif; ?>
	<?php if(get_post_meta($c_pageID, 'nand_main_bottom_padding', true)): ?>
	#az-container {
		padding-bottom: <?php echo get_post_meta($c_pageID, 'nand_main_bottom_padding', true); ?>;
    }
	<?php endif; ?>
	
	<?php if($custom_logo_type=='logo_img')
		{
	?>
    @media screen and (max-width: 801px) {  
	   #az-logo h1 a { background:transparent url( <?php echo esc_url($logo_img_url); ?> ) no-repeat center center; background-size: 240px 60px; }
        #az-headertagline {
            line-height: <?php echo ($customHeaderPadding+40); ?>px;
        }
    }
    @media only screen and (max-width: 550px) {
        #az-logo h1 a { background:transparent url( <?php echo esc_url($logo_img_url); ?> ) no-repeat center center; background-size: 240px 60px; }
        #az-headertagline {
            line-height: <?php echo ($customHeaderPadding+40); ?>px;
        }
    }
    @media only screen and (max-width : 480px) {
        #az-logo h1 a { background:transparent url( <?php echo esc_url($logo_img_url); ?> ) no-repeat center center; background-size: 240px 60px; }
        #az-headertagline {
            line-height: <?php echo ($customHeaderPadding+40); ?>px;
        }
    }  
    @media only screen and (max-width: 320px) {
        #az-logo h1 a { background:transparent url( <?php echo esc_url($logo_img_url); ?> ) no-repeat center center; background-size: 240px 60px; }
        #az-headertagline {
            line-height: <?php echo ($customHeaderPadding+40); ?>px;
        }
    }
	<?php } // if closed ?>
	<?php 
	// if box width option is on
	if( $azkaban_options['box_width']) {
	?>
	#az-headerwrap {
		background-color: <?php echo $azkaban_options['header_box_background'];?>;
		border-bottom: none;
		float: left;
		width: 100%;
	}
	
	#az-headerwrap .grid-container
	{
		background-color: #fff;
	}
	
	#az-containerwrap {
		background-color: <?php echo $azkaban_options['body_box_background'];?>;
		float: left;
		width: 100%; 
	}
	
	#az-containerwrap .grid-container
	{
		background-color: #fff;
	}
	
	#az-navigationwrap
	{
		float: none;
		margin-left: 74.5px;
		width: 89%;
		background-color: <?php echo $azkaban_options['header_box_background'];?>;
	}	
	
	#az-navigationwrap .grid-container
	{
		background-color: #fff;
	}	
	
	@media only screen and (max-width: 1338px) {
        #az-navigationwrap
		{
			float: none;
			margin-left: 0;
			width: 100%;
		}
		
		#az-navigationwrap
		{
			border-bottom:none;
			float: left;
			width: 100%;
		}
    }
	
	@media only screen and (max-width: 1300px) {
        #az-navigationwrap
		{
			border-bottom:none;
			float: left;
			width: 100%;
		}
    }
	
	body
	{
		background: <?php echo $azkaban_options['header_box_background'];?> none repeat scroll 0 0;
		font-family: "Open Sans",sans-serif;
		font-size: 13px;
		line-height: 20px;
	}
	
	<?php } ?>
    <?php if( $azkaban_options['custom_css_embed'] != '' ) echo $azkaban_options['custom_css_embed']; ?>
</style>
</head>

<body <?php body_class($class); ?>>
<?php
//var_dump($azkaban_options);
?>
<?php get_template_part('templates/topbar'); ?>

<?php get_template_part('templates/headers'); ?>

<?php
if( $azkaban_options['show_slider'] && is_front_page() ) {
    echo '<div id="az-sliderwrap">';
    echo '<div class="grid-container">';
    echo '<div class="grid-100 grid-parent" id="az-slider">';
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
   /*  if( $azkaban_options['slider_type'] == '1' ) {
        get_template_part('templates/slider');
    }
    else {
        // Show Layer Slider
        layerslider($azkaban_options['layerslider_id']);
    } */
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>

<?php azkaban_current_page_title_bar( $c_pageID ); ?>

<div id="az-containerwrap">
<div class="grid-container">
<div class="grid-100 grid-parent" id="az-container">
