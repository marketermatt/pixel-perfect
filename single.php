<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */

get_header();

global $azkaban_options;
$post_id = get_queried_object_id();
$showSidebar = true;

if( $azkaban_options['single_layout'] == 1 ) {
	$content_position = 'grid-100 grid-parent';
    $showSidebar = false;
}

if( $showSidebar == true ) {
	$content_position = ( $azkaban_options['single_layout'] == 3 ) ? 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent push-30' : 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent';

    if( get_post_meta( $post_id, 'nand_sidebar_position', true ) != 'default' ) {
        $content_position = ( get_post_meta( $post_id, 'nand_sidebar_position', true ) == 'left' ) ? 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent push-30' : 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent';
    }
}


?>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

<div class="<?php echo $content_position; ?>" id="az-maincontent" role="main">

	<?php get_template_part( 'content', get_post_format() ); ?>

	<div class="grid-100 grid-parent" id="az-comments">
        <?php if (comments_open() || get_comments_number()) { comments_template(); } ?>
    </div> <!-- end of az-comments -->

</div> <!-- end of az-maincontent -->

<?php endwhile; ?><?php endif; ?>
<?php wp_reset_query(); ?>

<?php 
if( $showSidebar == true ) {

    $sidebar_position = ( $azkaban_options['single_layout'] == 3 ) ? 'grid-30 tablet-grid-100 mobile-grid-100 pull-70' : 'grid-30 tablet-grid-100 mobile-grid-100';

    if( get_post_meta( $post_id, 'nand_sidebar_position', true ) != 'default' ) {
        $sidebar_position = ( get_post_meta( $post_id, 'nand_sidebar_position', true ) == 'left' ) ? 'grid-30 tablet-grid-100 mobile-grid-100 pull-70' : 'grid-30 tablet-grid-100 mobile-grid-100';
    }
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
