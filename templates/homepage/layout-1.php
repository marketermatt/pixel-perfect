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
