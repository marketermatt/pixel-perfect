<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */

/* Template Name: Full Width Page */ 

get_header();

?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="grid-100 grid-parent" id="az-maincontent">

    <div class="grid-100 grid-parent az-pagewrapper">
        <?php the_content(__('Read more', 'azkaban'));?>
    </div> <!-- end of az-postwrapper -->

	<?php if( $azkaban_options['show_comments_on_pages'] == 'yes' ) { ?>
		<div class="grid-100 grid-parent" id="az-comments">
            <?php if (comments_open() || get_comments_number()) { comments_template(); } ?>
		</div> <!-- end of az-comments -->
	<?php } ?>

</div> <!-- end of az-maincontent -->

<?php endwhile; ?><?php endif; ?>
<?php wp_reset_query(); ?>

<?php get_footer(); ?>
