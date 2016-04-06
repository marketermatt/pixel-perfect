<?php
global $azkaban_options;
$leftCategoryName = get_cat_name($azkaban_options['layout2-categoriesleft']);
$rightCategoryName = get_cat_name($azkaban_options['layout2-categoriesright']);
?>

<div class="grid-50 tablet-grid-50 mobile-grid-100 grid-parent" id="az-layout2-columnleft">
<div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent">
<h2 class="az-category-title"><span><?php echo $leftCategoryName; ?></span></h2>
</div>
<?php
$r = new WP_Query( array(   'showposts' => 5,
                            'nopaging' => 0,
                            'cat' => $azkaban_options['layout2-categoriesleft'],
                            'post_status' => 'publish',
                            'ignore_sticky_posts' => 1
                    ));
if( $r->have_posts() ) :
while ($r->have_posts()) : $r->the_post();
?>
<div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent">
<a href="<?php the_permalink();?>">
<?php cb_get_thumb('small-thumbnail', 'az-SmallThumbnail'); ?>
</a>
<h3><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></h3>
<p><?php //nk_excerpt('nk_archivelong', 'nk_excerptmore'); ?></p>
</div>
<?php
endwhile;
endif;
wp_reset_query();
?>
</div>
<div class="grid-50 tablet-grid-50 mobile-grid-100 grid-parent" id="az-layout2-columnright">
<div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent">
<h2 class="az-category-title"><span><?php echo $rightCategoryName; ?></span></h2>
</div>
<?php
$r = new WP_Query( array(   'showposts' => 5,
                            'nopaging' => 0,
                            'cat' => $azkaban_options['layout2-categoriesright'],
                            'post_status' => 'publish',
                            'ignore_sticky_posts' => 1
                    ));
if( $r->have_posts() ) :
while ($r->have_posts()) : $r->the_post();
?>
<div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent">
<a href="<?php the_permalink();?>">
<?php cb_get_thumb('small-thumbnail', 'az-SmallThumbnail'); ?>
</a>
<h3><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></h3>
<p><?php //nk_excerpt('nk_archivelong', 'nk_excerptmore'); ?></p>
</div>
<?php
endwhile;
endif;
wp_reset_query();
?>
</div>


<div class="grid-100 tablet-grid-100  mobile-grid-100 grid-parent">
    <div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent">
        <h2 class="az-category-title"><span>Recent Posts</span></h2>
    </div>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <?php get_template_part( 'content', get_post_format() ); ?>

    <?php endwhile; ?>
    <?php else : ?>

        <?php get_template_part( 'content', 'none' ); ?>

    <?php endif; ?>
    <?php wp_reset_query(); ?>

    <div class="grid-100" id="az-pagination">
        <?php
            if(function_exists('kriesi_pagination')) :
                kriesi_pagination();
            endif;
        ?>
    </div> <!-- end of az-pagination -->
</div>
