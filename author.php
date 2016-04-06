<?php
/**
 * The template for displaying Author archive pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
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

    <?php if ( have_posts() ) : ?>
    <div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent" id="az-authorbio">
        <div class="az-avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 96 ); ?></div>
        <p class="az-authorname"><?php echo azkaban_get_author_page_link(); ?></p>
        <?php if( get_the_author_meta('description') ) : ?>
            <p class="az-authorbio"><?php echo the_author_meta('description'); ?></p>
        <?php endif; ?>
	</div> <!-- end of az-authorbio -->

    <div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent" id="az-autorpagetitle">
        <h1 class="az-sectiontitle">
            <?php printf( __( 'All posts by %s', 'azkaban' ), get_the_author() ); ?>
        </h1>
	</div> <!-- end of az-autorpagetitle -->

    <?php
        /*
         * Since we called the_post() above, we need to rewind
         * the loop back to the beginning that way we can run
         * the loop properly, in full.
         */
        rewind_posts();

        // Start the Loop.
        while ( have_posts() ) : the_post();
            get_template_part( 'content', get_post_format() );
        endwhile;
        else :
            get_template_part( 'content', 'none' );
        endif;
    ?>

    <div class="grid-100" id="az-pagination">
        <?php
            if(function_exists('kriesi_pagination')) :
                kriesi_pagination();
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
