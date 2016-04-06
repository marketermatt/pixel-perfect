<?php
// Our Team custom post type function
function create_posttype_teams() {

	register_post_type( 'team',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Teams' ),
				'singular_name' => __( 'Team' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'team'),
			'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		)
	);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype_teams' );

/**
 * Add cafe custom fields
 */
function add_cafe_meta_boxes() {
	add_meta_box("cafe_contact_meta", "Members Info", "add_contact_details_cafe_meta_box", "team", "normal", "low");
}
function add_contact_details_cafe_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
 
	?>
	<style>.width99 {width:99%;}</style>
	<p>
		<label>Designation:</label><br />
		<input type="text" name="designation" value="<?= @$custom["designation"][0] ?>" class="width99" />
	</p>
	<?php
}
/**
 * Save custom field data when creating/updating posts
 */
function save_cafe_custom_fields(){
  global $post;
 
  if ( $post )
  {
    update_post_meta($post->ID, "designation", @$_POST["designation"]);
  }
}
add_action( 'admin_init', 'add_cafe_meta_boxes' );
add_action( 'save_post', 'save_cafe_custom_fields' );

// Our custom post type function
function create_posttype_portfolio() {

	register_post_type( 'portfolio',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Portfolios' ),
				'singular_name' => __( 'Portfolio' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'portfolio'),
			'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		)
	);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype_portfolio' );

/* Select Blog Function */
function selected_pagetype(){
	global $azkaban_options;
	if($azkaban_options['select_blog_page'] == ""){
		the_content(__('Read more', 'azkaban'));
	}else{
		echo "<ul class=\"blog_cat\">";
		$select_cat = implode(",", $azkaban_options['select_blog_categories']);
		$the_query = new WP_Query(
			array( 'cat' => $select_cat,

			'posts_per_page' => -1

			)
		);
		//print_r($the_query);
		while ( $the_query->have_posts() ) : 

		$the_query->the_post();
?>
			<li>
			<div class="blog_featured"><a href="<?php the_permalink();?>"><img src="<?php the_post_thumbnail_url('thumbnail'); ?>" /></a></div>
			<div class="blog_content">
			<h3><?php the_title();?></h3>
			<p><?php the_content(__('Read more', 'azkaban'));?></p>
			</div>
			</li>
			<div class="clear"></div>
			<p>&nbsp;</p>
<?php
		endwhile; 

		wp_reset_postdata();
		echo "</ul>";
	}
}
?>