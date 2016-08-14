<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
get_header( 'shop' );
?>
<div class="grid-100 grid-parent" id="az-maincontent">

    <div class="grid-100 grid-parent az-ProductHeader">
    <?php
        $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
        if(!empty($terms)) {
            $main_term = $terms[0];
        } else {
            $main_term = "";
        }
        if( $main_term ) {
            echo '<div class="cat_back_btn headerfont"><i class="icon-arrow-left"></i> '.__('Back to', 'virtue').' <a href="'.get_term_link($main_term->slug, 'product_cat').'">'.$main_term->name.'</a></div>';
        } 
        else {
            echo '<div class="cat_back_btn headerfont"><i class="icon-arrow-left"></i> '.__('Back to', 'virtue').' <a href="'.get_permalink( woocommerce_get_page_id( 'shop' ) ).'">'.__('Shop','virtue').'</a></div>';
        }
    ?>
    </div>
    
    <?php while ( have_posts() ) : the_post(); ?>
        <?php woocommerce_get_template_part( 'content', 'single-product' ); ?>
    <?php endwhile; // end of the loop. ?>

</div>
<?php
get_footer( 'shop' );
?>
