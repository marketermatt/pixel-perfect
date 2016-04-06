<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product, $virtue;
if(isset($virtue['product_simg_resize']) && $virtue['product_simg_resize'] == 0) {
	$presizeimage = 0;
} else {
	$presizeimage = 1;
	$productimgwidth = 458;
	$productimgheight = 458;
}

?>
<div class="az-ProductImage">

    <?php
		if( has_post_thumbnail() ) {
            cb_get_thumb('square-thumbnail', 'az-thumbnail');
		}
        else {
            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ), $post->ID );
		}
	?>

</div> <!-- end of az-ProductImage -->
<?php do_action( 'woocommerce_product_thumbnails' ); ?>
