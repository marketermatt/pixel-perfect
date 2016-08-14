<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $virtue;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
if ($woocommerce_loop['columns'] == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $productimgwidth = 365;} else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $productimgwidth = 268;}
// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();

$classes[] = 'grid_item';
$classes[] = 'product_item';
$classes[] = 'clearfix';
if(isset($virtue['product_img_resize']) && $virtue['product_img_resize'] == 0) {
	$resizeimage = 0;
} else {
	$resizeimage = 1;
}
?>
<div class="<?php echo $itemsize;?> grid-25 tablet-grid-100 mobile-grid-100 grid-parent az-Product4Column">
<div <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>" class="product_item_link">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			//do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
			<?php echo woocommerce_show_product_loop_sale_flash($post, $product); ?>

			<?php // echo woocommerce_template_loop_product_thumbnail($post, $product, $size); ?>
			<?php if($resizeimage == 1) { 
					if ( has_post_thumbnail() ) {
					$product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
					$product_image_url = $product_image[0]; 
					//$image_product = aq_resize($product_image_url, $productimgwidth, $productimgwidth, true);
	            	if(empty($image_product)) {$image_product = $product_image_url;} ?> 
	            	 <img width="<?php echo $productimgwidth;?>" height="<?php echo $productimgwidth;?>" src="<?php echo $image_product;?>" class="attachment-shop_catalog wp-post-image" alt="<?php the_title();?>">
	            	 <?php } elseif ( woocommerce_placeholder_img_src() ) {
		             echo woocommerce_placeholder_img( 'shop_catalog' );
		             }  
			} else { 
				echo '<div class="kad-woo-image-size">';
				echo woocommerce_template_loop_product_thumbnail();
				echo '</div>';
         }?>
    </a>
             
    <div class="az-ProductTitle">
        <a href="<?php the_permalink(); ?>" class="product_item_link"><h2><?php the_title(); ?></h2></a>
    </div>

    <div class="az-ProductDetails">
        <?php the_excerpt(); ?>
    </div>

    <?php
        /**
		 * woocommerce_after_shop_loop_item_title hook
         *
         * @hooked woocommerce_template_loop_price - 10
         */
            do_action( 'woocommerce_after_shop_loop_item_title' );
    ?>
	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</div>
</div>
