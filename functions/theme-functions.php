<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */

/****************************************************************************/
// Page Title Output
/****************************************************************************/
function azkaban_page_title_bar( $title, $subtitle, $secondary_content ) {
?>

    <div id="az-pagetitlewrap">
    <div class="grid-container">
    <div class="grid-100 grid-parent">
        <div class="grid-50 tablet-grid-50 mobile-grid-100 grid-parent" id="az-pagetitle">
            <?php if( $title ): ?>
                <h1 class="az-sectiontitle"><span><?php echo $title; ?></span></h1>
				<?php if( $subtitle ): ?>
                    <h2><?php echo $subtitle; ?></h2>
                <?php endif; ?>
            <?php endif; ?>
        </div> <!-- end of az-pagetitle -->
        <?php echo $secondary_content; ?>
    </div>
    </div> <!-- end of grid-container -->
    </div> <!-- end of az-pagetitlewrap -->

<?php }

/****************************************************************************/
// Prepare Page Title Output
/****************************************************************************/
function azkaban_current_page_title_bar( $post_id ) {
    
	global $azkaban_options;

	ob_start();
    if( $azkaban_options['enable_breadcrumbs'] ) {
            if( function_exists('cb_custom_breadcrumbs') ) {
                echo '<div class="grid-50 tablet-grid-50 mobile-grid-100 grid-parent" id="az-breadcrumb">';
                    cb_custom_breadcrumbs();
                echo '</div>';
            }
    }
    else { 
        get_search_form();
    }
	$secondary_content = ob_get_contents();
	ob_get_clean();

	$title = '';
	$subtitle = '';
    
	if( get_post_meta( $post_id, 'nand_page_title_custom_text', true ) != '' ) {
		$title = get_post_meta( $post_id, 'nand_page_title_custom_text', true );
	}

	if( get_post_meta( $post_id, 'nand_page_title_custom_subheader', true ) != '' ) {
		$subtitle = get_post_meta( $post_id, 'nand_page_title_custom_subheader', true );
	}

	if( !$title ) {
	   
		$title = get_the_title();

		if( is_home() ) {
			$title = $azkaban_options['site_title'];
		}

		if( is_search() ) {
			$title = __('Search results for:', 'azkaban') . get_search_query();
		}

		if( is_404() ) {
			$title = __('Error 404 Page', 'azkaban');
		}

		if( is_archive() ) {
			if ( is_day() ) {
				$title = __( 'Daily Archives:', 'azkaban' ) . '<span> ' . get_the_date() . '</span>';
			} else if ( is_month() ) {
				$title = __( 'Monthly Archives:', 'azkaban' ) . '<span> ' . get_the_date( _x( 'F Y', 'monthly archives date format', 'azkaban' ) ) . '</span>';
			} elseif ( is_year() ) {
				$title = __( 'Yearly Archives:', 'azkaban' ) . '<span> ' . get_the_date( _x( 'Y', 'yearly archives date format', 'azkaban' ) ) . '</span>';
			} elseif ( is_author() ) {
				$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $_GET['author_name'] ) : get_user_by(  'id', get_the_author_meta('ID') );
				$title = $curauth->nickname;
			} else {
				$title = single_cat_title( '', false );
			}
		}

		if( class_exists( 'Woocommerce' ) && is_woocommerce() && ( is_product() || is_shop() ) && ! is_search() ) {
			if( ! is_product() ) {
				$title = woocommerce_page_title( false );
			}
		}

	}

	if ( !$subtitle ) {
		if( is_home() && !is_front_page() ) {
			$subtitle = $azkaban_options['site_subtitle'];
		}
	}
	
	if( !is_archive() && !is_search() && !( is_home() && !is_front_page() ) ) {
		if( get_post_meta( $post_id, 'nand_page_title', true ) == 'yes' ||
			( $azkaban_options['show_titlebar'] && get_post_meta( $post_id, 'nand_page_title', true ) == 'default' )
		) {

			if( get_post_meta( $post_id, 'nand_page_title_text', true ) == 'no' ) {
				$title = '';
				$subtitle = '';
			}
		
			azkaban_page_title_bar( $title, $subtitle, $secondary_content );
			
		}
	} 
    else {
		if( $azkaban_options['show_titlebar'] ) {
			azkaban_page_title_bar( $title, $subtitle, $secondary_content );
		}
	}
 
//			azkaban_page_title_bar( $title, $subtitle, $secondary_content );
}

function azkaban_slider_name( $name ) {
    $type = '';
    
	switch( $name ) {
		case 'layer':
			$type = 'slider';
			break;
		case 'flex':
			$type = 'wooslider';
			break;
		case 'rev':
			$type = 'revslider';
			break;
		case 'elastic':
			$type = 'elasticslider';
			break;
	}

	return $type;
}

function azkaban_get_slider_type( $post_id ) {
	$get_slider_type = get_post_meta($post_id, 'nand_slider_type', true);

	return $get_slider_type;
}

function azkaban_get_slider( $post_id, $type ) {
    
	$type = azkaban_slider_name( $type );

	if( $type ) {
		$get_slider = get_post_meta( $post_id, 'nand_' . $type, true );

		return $get_slider;
	} else {
		return false;
	}
}

function azkaban_slider( $post_id ) {
	$slider_type = azkaban_get_slider_type( $post_id );
	$slider = azkaban_get_slider( $post_id, $slider_type );

	if( $slider ) {
		$slider_name = azkaban_slider_name( $slider_type );

		if( $slider_name == 'slider' ) {
			$slider_name = 'layerslider';
		}

		$function = 'azkaban_' . $slider_name;

		$function( $slider );
	}
}


function revslider( $id ) {
	global $wpdb;

	// Get slider
	$ls_table_name = $wpdb->prefix . "revslider";
	$ls_slider = $wpdb->get_row("SELECT * FROM $ls_table_name WHERE id = " . (int) $id . " ORDER BY date_c DESC LIMIT 1" , ARRAY_A);
	$ls_slider = json_decode($ls_slider['data'], true);
	?>
	<style type="text/css">
		#layerslider-container{width:100%;}
	</style>
	<div id="layerslider-container">
		<div id="layerslider-wrapper">
			<?php if($ls_slider['properties']['skin'] == 'azkaban'): ?>
				<div class="ls-shadow-top"></div>
			<?php endif; ?>
			<?php echo do_shortcode('[rev_slider alias="' . $id . '"]'); ?>
			<?php if($ls_slider['properties']['skin'] == 'azkaban'): ?>
				<div class="ls-shadow-bottom"></div>
			<?php endif; ?>
		</div>
	</div>
<?php
}


/****************************************************************************/
// Capture the output of "the_author_posts_link()" function into a local variable and return it.
// This function must be used within 'The Loop'
/****************************************************************************/
if ( !function_exists('azkaban_get_author_page_link') ) {
    function azkaban_get_author_page_link() {
        ob_start();
        the_author_posts_link();
        $the_author_link = ob_get_contents();
        ob_end_clean();
        return $the_author_link;
    }
}

/****************************************************************************/
// Remove image titles
/****************************************************************************/
add_filter('the_content', 'remove_img_titles', 1000);
add_filter('post_thumbnail_html', 'remove_img_titles', 1000);
add_filter('wp_get_attachment_image', 'remove_img_titles', 1000);

function remove_img_titles($text) {

    // Get all title="..." tags from the html.
    $result = array();
    preg_match_all('|title="[^"]*"|U', $text, $result);

    // Replace all occurances with an empty string.
    foreach ($result[0] as $img_tag) {
        $text = str_replace($img_tag, '', $text);
    }

    return $text;
}

/****************************************************************************/
// Add social media links to the user page
/****************************************************************************/
function add_social_contactmethod($contactmethods) {

    // Add Networks
    $contactmethods['googleplus'] = 'Google+ URL';
    $contactmethods['twitter'] = 'Twitter URL';
    $contactmethods['facebook'] = 'Facebook URL';
    $contactmethods['linkedin'] = 'Linkedin URL';
    $contactmethods['youtube'] = 'YouTube URL';
    $contactmethods['pinterest'] = 'Pinterest URL';

    return $contactmethods;
}
add_filter('user_contactmethods', 'add_social_contactmethod', 10, 1);

/****************************************************************************/
// List Categories For Select
/****************************************************************************/
function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
}

/****************************************************************************/
/* Shortcode: "Read More ->" Link.
/* Usage: [read_more text="Read more" 
            title="Read More..." 
            url="http://www.some-url-goes-here.com/" 
            align="left" 
            target="_blank"] */
/****************************************************************************/
function read_more_func( $atts ) {    
	extract(shortcode_atts(array(
	    'text' => esc_html__('Read more', 'azkaban'),
	    'title' => '',
	    'url' => '#',
	    'align' => 'left',
	    'target' => '',
    ), $atts));

    $target = ($target == '_blank') ? ' target="_blank"' : '';
    $align_class = ( $align == 'right' ) ? '-align-right': '-align-left';
    $html = '<a class="az-readmore'.$align_class.'" href="'.$url.'" title="'.$title.'"'.$target.'><span>'.do_shortcode($text).'</span></a>';
    return $html;

}
add_shortcode('read_more', 'read_more_func');

/****************************************************************************/
// Custom Breadcrumbs
/****************************************************************************/
function cb_custom_breadcrumbs() {
  
  $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = '&raquo;'; // delimiter between crumbs
  $home = 'Home'; // text for the 'Home' link
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
  
  global $post;
  $homeLink = home_url();
  
  if (is_home() || is_front_page()) {
  
    if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
  
  } else {
  
    echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
  
    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
  
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;
  
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
  
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
  
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
  
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }
  
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
  
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
  
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
  
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
  
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
  
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page', 'azkaban') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
  
    echo '</div>';
  
  }
}

/****************************************************************************/
// Get First Image From Posts
/****************************************************************************/
function get_first_image() {

	global $post, $posts;

	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches[1][0];
	if(empty($first_img)) { //Defines a default image
		$first_img = "";
	}
	return $first_img;
}

/****************************************************************************************/
/* Get Thumbnail */
/****************************************************************************************/
function cb_get_thumb( $size = 'small-thumbnail', $class = 'nk-thumbnail' ) {
    
	global $post, $azkaban_options;

    if( $size == 'small-thumbnail' ) {$width = 120; $height = 70;}
	elseif( $size == 'medium-featured' ) { $width = 800; $height = 250;}
	elseif( $size == 'square-thumbnail' ) { $width = 460; $height = 460;}
	else { $width = 120; $height = 70;}

    $image_id = get_post_thumbnail_id($post->ID);
 
	if( $azkaban_options['use_timthumb'] ) {
        if( !empty($image_id) ) {
            $image_src = wp_get_attachment_image_src($image_id, $size);
            $image_src = $image_src[0];
        }
        else {
            $image_src = get_first_image();
        }
        if( !empty($image_src) ) {
			$image_url = '<img src='. get_template_directory_uri() .'/timthumb.php?src='.$image_src.'&amp;h='.$height.'&amp;w='.$width.'&amp;a=c&amp;q=90" alt="'.get_the_title().'" class="'.$class.'" />';
		}
        else {
            $image_url = '<img src='. get_template_directory_uri() .'/timthumb.php?src='.get_template_directory_uri().'/images/no-image.jpg&amp;h='.$height.'&amp;w='.$width.'&amp;a=c&amp;q=90" alt="'.get_the_title().'" class="'.$class.'" />';
        }
	}
    else {
 		$image_id = get_post_thumbnail_id($post->ID);
        if( !empty($image_id) ) {
		  $image_url = wp_get_attachment_image($image_id, $size , false, array( 'class' => $class, 'alt'   => get_the_title() ,'title' =>  get_the_title() ));
        }
        else {
		  $image_url = '<img src="'. get_template_directory_uri() .'/timthumb.php?src='.get_template_directory_uri().'/images/no-image.jpg&amp;h='.$height.'&amp;w='.$width.'&amp;a=c&amp;q=90" class="'.$class.'" />';
        }
    }

    echo $image_url;

}

// Create Multiple Excerpt Lengths
function az_archivelong($length) {
    return 35;
}

function az_archive($length) {
    return 25;
}

function az_archiveshort($length) {
    return 15;
}

function az_excerptmore($more) {
    return '...';
}

function az_excerpt($length_callback='', $more_callback='') {

    global $post;

    if(function_exists($length_callback)){
        add_filter('excerpt_length', $length_callback);
    }

    if(function_exists($more_callback)){
        add_filter('excerpt_more', $more_callback);
    }

    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);

    echo $output;

}

/****************************************************************************************/
/* If the menu doesn't exist */
/****************************************************************************************/
function nk_topnav_fallback(){
	echo '<div class="az-topmenualert">'.__( 'You can use WP menu builder to build menus' , 'azkaban' ).'</div>';
}

function ax_nav_fallback(){
	echo '<div class="az-menualert">'.__( 'You can use WP menu builder to build menus' , 'azkaban' ).'</div>';
}

function ax_nav_fallbackr(){
	echo '<div class="az-menualertr">'.__( 'You can use WP menu builder to build menus' , 'azkaban' ).'</div>';
}
