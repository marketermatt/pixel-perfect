<?php 
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage azkaban
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

<div class="<?php echo $content_position; ?>" id="az-maincontent">

    <div class="grid-100 grid-parent az-postwrapper">
	
        <h2 class="center"><?php esc_html_e('Not Found', 'azkaban'); ?></h2>
        <p class="center"><?php esc_html_e("Sorry, but you are looking for something that isn't here.", 'azkaban'); ?></p>
        <?php get_search_form(); ?>

    </div> <!-- end of az-postwrapper -->

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
