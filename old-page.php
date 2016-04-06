<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */

get_header();

global $azkaban_options;
$page_id = get_queried_object_id();
$showSidebar = true;

$content_position = ( $azkaban_options['page_layout'] == 2 ) ? 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent push-30' : 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent';

if( get_post_meta( $page_id, 'nand_sidebar_position', true ) != 'default' ) {
    $content_position = ( get_post_meta( $page_id, 'nand_sidebar_position', true ) == 'left' ) ? 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent push-30' : 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent';
}

if( class_exists('Woocommerce') ) {
    if( is_cart() || is_checkout() || is_account_page() || ( get_option('woocommerce_thanks_page_id') && is_page(get_option('woocommerce_thanks_page_id'))) ) {
        $content_position = 'grid-100 grid-parent';
        $showSidebar = false;
    }
}
    
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="<?php echo $content_position; ?>" id="az-maincontent">

    <div class="grid-100 grid-parent az-pagewrapper">
        <?php the_content(__('Read more', 'azkaban'));?>
    </div> <!-- end of az-postwrapper -->

    <?php if( $azkaban_options['show_comments_on_pages'] ) { ?>
        <?php if (comments_open() || get_comments_number()) { ?>
            <div class="grid-100 grid-parent" id="az-comments">
                <?php comments_template(); ?>
            </div> <!-- end of az-comments -->
        <?php } ?>
    <?php } ?>

</div> <!-- end of az-maincontent -->
<?php endwhile; ?><?php endif; ?>
<?php wp_reset_query(); ?>

<?php
if( $showSidebar ) { 
    
    $sidebar_position = ( $azkaban_options['page_layout'] == 2 ) ? 'grid-30 tablet-grid-100 mobile-grid-100 pull-70' : 'grid-30 tablet-grid-100 mobile-grid-100';

    if( get_post_meta( $page_id, 'nand_sidebar_position', true ) != 'default' ) {
        $sidebar_position = ( get_post_meta( $page_id, 'nand_sidebar_position', true ) == 'left' ) ? 'grid-30 tablet-grid-100 mobile-grid-100 pull-70' : 'grid-30 tablet-grid-100 mobile-grid-100';
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
