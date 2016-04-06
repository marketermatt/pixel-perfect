<?php
global $azkaban_options;
global $post;
$orig_post = $post;

$size = 'large-featured' ;
$fea_tags = $sep = '';
$number = $azkaban_options['slider_number'];
$slider_query = $azkaban_options['slider_query_type'];
$caption_length = 100;
    
if( empty($caption_length) || $caption_length == ' ' || !is_numeric($caption_length)) $caption_length = 100;

if( $slider_query  == 'tag') {
    $tags = explode (' , ' , $azkaban_options['slider_tags']);
    foreach ($tags as $tag){
        $theTagId = get_term_by( 'name', $tag, 'post_tag' );
        if( !empty($fea_tags) ) $sep = ' , ';
        $fea_tags .=  $sep . $theTagId->slug;
    }
    $args= array('posts_per_page'=> $number , 'tag' => $fea_tags, 'no_found_rows' => 1 );
}
elseif( $slider_query  == 'category') {
    $args= array('posts_per_page'=> $number , 'category__in' => $azkaban_options['slider_categories'], 'no_found_rows' => 1 );
}
elseif( $slider_query  == 'post') {
    $posts_var = explode (',' , $azkaban_options['slider_posts']);
    $args= array('posts_per_page'=> $number , 'post_type' => 'post', 'post__in' => $posts_var, 'no_found_rows' => 1 );
}
elseif( $slider_query  == 'page') {
    $pages_var = explode (',' , $azkaban_options['slider_pages']);
    $args= array('posts_per_page'=> $number , 'post_type' => 'page', 'post__in' => $pages_var, 'no_found_rows' => 1 );
}
	
$featured_query = new wp_query( $args );

if( $azkaban_options['slider_type'] == 'elastic' ):

	$effect = $azkaban_options['elastic_animation'];
	$autoplay = $azkaban_options['elastic_autoplay'];
	$speed = $azkaban_options['elastic_slideshow_speed'];
	$interval = $azkaban_options['elastic_animation_speed'];
	
	if( !$speed || $speed == ' ' || !is_numeric($speed)) $speed = 800 ;
	if( !$interval || $interval == ' ' || !is_numeric($interval)) $interval = 3000;
	
	if( $effect == 'sides' ) $effect = 'sides';
	else $effect = 'center';

	if( $autoplay ) $autoplay = 'true';
	else $autoplay = 'false';
?>

<?php if( $featured_query->have_posts() ) : ?>
<div id="ei-slider" class="ei-slider">
    <ul class="ei-slider-large">
	<?php 
        $i= 0;
        while ( $featured_query->have_posts() ) : $featured_query->the_post(); $i++;
    ?>
    <li>
        <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>"><?php cb_get_thumb( $size ); ?></a>
        <?php endif; ?>
        <div class="ei-title">
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php if( $azkaban_options['slider_caption'] ) : ?><h3><?php echo nk_excerpt('nk_slider', 'nk_excerptmore') ?></h3><?php endif; ?>
        </div>
    </li>
	<?php endwhile;?>
    </ul>
    <ul class="ei-slider-thumbs">
        <li class="ei-slider-element">Current</li>
        <?php
            $i= 0;
            while ( $featured_query->have_posts() ) : $featured_query->the_post(); $i++;
        ?>
            <li><a href="#">Slide <?php echo $i; ?><?php cb_get_thumb( 'small-thumbnail' ); ?></a></li>
        <?php endwhile;?>
    </ul><!-- ei-slider-thumbs -->
</div>
<?php endif; ?>
<script type="text/javascript">
    jQuery(function() {
        jQuery('#ei-slider').eislideshow({
            animation			: '<?php echo $effect ?>',
            autoplay			: <?php echo $autoplay ?>,
            slideshow_interval	: <?php echo $interval ?>,
            speed          		: <?php echo $speed ?>,
            titlesFactor		: 0.60,
            titlespeed          : 1000,
            thumbMaxWidth       : 100
        });
    });
</script>
<?php

else:
	
	$effect = $azkaban_options['flexi_animation'];
	$speed = $azkaban_options['flexi_slideshow_speed'];
	$time = $azkaban_options['flexi_animation_speed'];
	
	if( !$speed || $speed == ' ' || !is_numeric($speed))	$speed = 7000 ;
	if( !$time || $time == ' ' || !is_numeric($time))	$time = 600;
	
	if( $effect == 'slideV' )
			$effect = 'animation: "slide",
					  direction: "vertical",';
	elseif( $effect == 'slideH' )
				$effect = 'animation: "slide",';
	else
		$effect = 'animation: "fade",';
?>
<?php if( $featured_query->have_posts() ) : ?>
<div id="az-flexslider" class="flexslider">
    <ul class="slides">
	<?php while ( $featured_query->have_posts() ) : $featured_query->the_post()?>
        <li>
            <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
            <a href="<?php the_permalink(); ?>"><?php cb_get_thumb( $size ); ?></a>
			<?php endif; ?>
            <div class="slider-caption">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php if($azkaban_options['slider_caption']) : ?><p><?php echo az_excerpt('az_archiveshort', 'az_excerptmore') ?></p><?php endif; ?>
            </div>
        </li>
    <?php endwhile;?>
    </ul>
</div>
<?php endif; ?>

<script>
jQuery(window).load(function() {
    jQuery('#az-flexslider').flexslider({
        animation: "slide",
        slideshowSpeed: <?php echo $speed ?>,
        animationSpeed: <?php echo $time ?>,
        randomize: false,
        pauseOnHover: true,
        directionNav: true,
        prevText: "",
        nextText: "",
        after: function(slider) {
		jQuery('#az-flexslider .slider-caption').animate({bottom:12,}, 400)
	},
	before: function(slider) {
        jQuery('#az-flexslider .slider-caption').animate({ bottom:-300,}, 400)
	},	
	start: function(slider) {
       	var slide_control_width = 100/<?php echo $number; ?>;
    	jQuery('#az-flexslider .flex-control-nav li').css('width', slide_control_width+'%');
		jQuery('#az-flexslider .slider-caption').animate({ bottom:12,}, 400)
	}
  });
});
</script>

<?php
endif;
$post = $orig_post;
wp_reset_query();
?>
