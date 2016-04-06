<?php 
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * @package WordPress
 * @subpackage Azkaban
 */

get_header();

global $azkaban_options;
if( $azkaban_options['archive_layout'] == 1 ) {
	$content_position = 'grid-100 grid-parent';
}
else {
	$content_position = ( $azkaban_options['archive_layout'] == 3 ) ? 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent push-30' : 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent';
}
?>

<div class="<?php echo $content_position; ?>" id="az-maincontent" role="main">

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
            else :
        ?>
            <div class="alignleft"><?php previous_posts_link() ?></div>
            <div class="alignright"><?php next_posts_link() ?></div>
        <?php
            endif;
        ?>
    </div> <!-- end of az-pagination -->

</div> <!-- end of az-maincontent -->

<?php
if( $azkaban_options['archive_layout'] != 1 ) {
    
    $sidebar_position = ( $azkaban_options['archive_layout'] != 2 ) ? 'grid-30 tablet-grid-100 mobile-grid-100 pull-70' : 'grid-30 tablet-grid-100 mobile-grid-100';
?>
    <div class="<?php echo $sidebar_position; ?>" id="az-sidebar" role="complementary">

   	<?php if( $azkaban_options['enable_sidebar_top_ad'] ) { ?>
	<?php if( $azkaban_options['sidebar_top_ad_code'] != "" ) { ?>
    <div class="az-sidebarsection">
		<center><?php echo $azkaban_options['sidebar_top_ad_code']; ?></center>
	</div>
	<?php } ?>
	<?php } ?>

    <?php generated_dynamic_sidebar(); ?>

   	<?php if( $azkaban_options['enable_sidebar_bottom_ad'] ) { ?>
	<?php if( $azkaban_options['sidebar_bottom_ad_code'] != "" ) { ?>
    <div class="az-sidebarsection">
		<center><?php echo $azkaban_options['sidebar_bottom_ad_code']; ?></center>
	</div>
	<?php } ?>
	<?php } ?>

    </div>
<?php    
}
?>

<?php get_footer(); ?>
