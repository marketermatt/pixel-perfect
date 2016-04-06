<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */

get_header();

global $azkaban_options;
$showSidebar = true;
if( $azkaban_options['home_sidebar'] == 1 ) {
	$content_position = 'grid-100 grid-parent';
    $showSidebar = false;
}

if( $showSidebar == true ) {
	$content_position = ( $azkaban_options['home_sidebar'] == 3 ) ? 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent push-30' : 'grid-70 tablet-grid-100 mobile-grid-100 grid-parent';
}
?>
<div class="<?php echo $content_position; ?>" id="az-maincontent">

    <?php get_template_part('templates/homepage/layout', $azkaban_options['home_layout']); ?>
        
</div> <!-- end of az-maincontent -->

<?php 
if( $showSidebar == true ) {

    $sidebar_position = ( $azkaban_options['home_sidebar'] == 3 ) ? 'grid-30 tablet-grid-100 mobile-grid-100 pull-70' : 'grid-30 tablet-grid-100 mobile-grid-100';
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
