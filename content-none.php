<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage azkaban
 */
?>

<article id="post-<?php the_ID(); ?>" class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent az-postwrapper <?php post_class(); ?>">


    <header class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent az-posttitle">
        <h1><?php _e( 'Nothing Found', 'azkaban' ); ?></h1>
    </header>

    <div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent az-postcontent">

        <?php if( is_home() && current_user_can('publish_posts') ) : ?>

            <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'azkaban' ), admin_url( 'post-new.php' ) ); ?></p>

        <?php elseif( is_search() ) : ?>

            <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'azkaban' ); ?></p>
            <?php get_search_form(); ?>

        <?php else : ?>

            <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'azkaban' ); ?></p>
            <?php get_search_form(); ?>

        <?php endif; ?>
        
    </div>

</article>
