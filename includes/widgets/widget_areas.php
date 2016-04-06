<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */

global $azkaban_options;

/* Register Default Widget Areas */
if ( function_exists('register_sidebar') ) {   
	register_sidebar(array(	
		'name' => 'Default Sidebar',	
        'id' => 'azkaban-default-sidebar',
		'before_widget' => '<div id="%1$s" class="az-sidebarsection %2$s">',	
		'after_widget' => '</div>',	
        'before_title' => '<h2 class="az-sidebarsectiontitle"><span>',
        'after_title' => '</span></h2>',
	));
}


/* Register Contact Page Widget Areas */
if ( function_exists('register_sidebar') ) {   
	register_sidebar(array(	
		'name' => 'Contact Sidebar',	
        'id' => 'azkaban-contact-sidebar',
		'before_widget' => '<div id="%1$s" class="az-sidebarsection %2$s">',	
		'after_widget' => '</div>',	
        'before_title' => '<h2 class="az-sidebarsectiontitle"><span>',
        'after_title' => '</span></h2>',
	));
}

if ( function_exists('register_sidebar') ) {   
	register_sidebar(array(	
		'name' => 'Footer Column 1',	
		'before_widget' => '<div id="%1$s" class="az-footerwidgetsection %2$s">',	
		'after_widget' => '</div>',	
        'before_title' => '<h2 class="widget_title"><span>',
        'after_title' => '</span></h2>',
	));
}

if ( function_exists('register_sidebar') ) {   
	register_sidebar(array(	
		'name' => 'Footer Column 2',	
		'before_widget' => '<div id="%1$s" class="az-footerwidgetsection %2$s">',	
		'after_widget' => '</div>',	
        'before_title' => '<h2 class="widget_title"><span>',
        'after_title' => '</span></h2>',
	));

}

if ( function_exists('register_sidebar') ) {   
	register_sidebar(array(	
		'name' => 'Footer Column 3',	
		'before_widget' => '<div id="%1$s" class="az-footerwidgetsection %2$s">',	
		'after_widget' => '</div>',	
        'before_title' => '<h2 class="widget_title"><span>',
        'after_title' => '</span></h2>',
	));
}

if( $azkaban_options['footer_widgets_layout'] == 1 OR $azkaban_options['footer_widgets_layout'] == 2 ) {
if ( function_exists('register_sidebar') ) {   
	register_sidebar(array(	
		'name' => 'Footer Column 4',	
		'before_widget' => '<div id="%1$s" class="az-footerwidgetsection %2$s">',	
		'after_widget' => '</div>',	
        'before_title' => '<h2 class="widget_title"><span>',
        'after_title' => '</span></h2>',
	));
}
}





