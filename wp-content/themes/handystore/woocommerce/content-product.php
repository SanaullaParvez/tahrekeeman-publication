<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $post;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Fix columns qty if on vendor store page
$vendor_shop = null;
if ( class_exists('WCVendors') ) {
	$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
}
if ($vendor_shop && $vendor_shop!='' && pt_show_layout()=='layout-one-col') {
	$woocommerce_loop['columns'] = 4;
}
if ($vendor_shop && $vendor_shop!='' && ( pt_show_layout()=='layout-two-col-left' || pt_show_layout()=='layout-two-col-right' ) ) {
	$woocommerce_loop['columns'] = 3;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 === $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}

// Adding extra gallery if turned on
$attachment_ids = $product->get_gallery_attachment_ids();
$show_gallery = get_post_meta( $post->ID, 'pt_product_extra_gallery' );

if ( $attachment_ids && ($show_gallery[0] == 'on') ) {
	$gallery_images = array();
	$count = 0;

	foreach ($attachment_ids as $attachment_id) {
		if ($count > 2 ) {
			continue;
		}
		$thumb = wp_get_attachment_image( $attachment_id, 'product-extra-gallery-thumb' );
		$link = wp_get_attachment_image_src( $attachment_id, 'shop_catalog' );
		$gallery_images[] = array(
			'thumb' => $thumb,
			'link' => $link[0],
		);
		$count++;
	}
}

// Adding extra classes for responsive view
if ( handy_get_option('store_columns')=='3' ) {
	if ( pt_show_layout()!='layout-one-col' ) {
		$responsive_class = " col-xs-12 col-md-4 col-sm-6";
	} else {
		$responsive_class = " col-xs-12 col-md-4 col-sm-3";
	}
} elseif ( handy_get_option('store_columns')=='4' ) {
	if ( pt_show_layout()!='layout-one-col' ) {
		$responsive_class = " col-xs-12 col-md-3 col-sm-6";
	} else {
		$responsive_class = " col-xs-12 col-md-3 col-sm-4";
	}
}
$classes[] = $responsive_class;

// Adding extra class if list view
if ( handy_get_option('default_list_type')=='list' && ( is_shop() || is_product_category() || is_product_tag() ) ) {
	$classes[] = 'list-view';
}

// Extra class for lazyload
if ( handy_get_option('catalog_lazyload')=='on' ) {
	$classes[] = 'lazyload';
}
?>
<li <?php post_class( $classes ); ?> data-expand="-100">

	<div class="inner-product-content fade-hover">

		<?php /**
		 * woocommerce_before_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<div class="product-img-wrapper">

			<div class="pt-extra-gallery-img images">
				<a href="<?php the_permalink(); ?>" title="<?php _e('View details', 'plumtree');?>">
					<?php
						/**
						 * woocommerce_before_shop_loop_item_title hook
						 *
						 * @hooked woocommerce_show_product_loop_sale_flash - 10
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						do_action( 'woocommerce_before_shop_loop_item_title' );
					?>
				</a>
			</div>

			<?php if ( !empty($gallery_images) ) :
					echo '<ul class="pt-extra-gallery-thumbs">';
					foreach ($gallery_images as $gallery_image) {
						echo '<li><a href="'.$gallery_image['link'].'">'.$gallery_image['thumb'].'</a></li>';
					}
					echo '</ul>';
				endif; ?>

		</div>

		<div class="product-description-wrapper">

			<?php /**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' ); ?>

			<?php if ( $post->post_excerpt ) : ?>
				<div class="short-description">
					<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
				</div>
			<?php endif; ?>

				<?php
					/**
					 * woocommerce_after_shop_loop_item_title hook
					 *
					 * @hooked woocommerce_template_loop_rating - 5
					 * @hooked woocommerce_template_loop_price - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );
				?>

		</div>

		<div class="additional-buttons">

			<?php /**
			 * woocommerce_after_shop_loop_item hook.
			 *
			 * @hooked woocommerce_template_loop_product_link_close - 5
			 * @hooked woocommerce_template_loop_add_to_cart - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item' ); ?>

			<?php // add to wishlist button
				if ( ( class_exists( 'YITH_WCWL_Shortcode' ) ) && ( get_option('yith_wcwl_enabled') == true ) ) {
					$atts = array(
				        'per_page' => 10,
				        'pagination' => 'no',
				    );
					echo YITH_WCWL_Shortcode::add_to_wishlist($atts);
				} ?>

		</div>

	</div>

</li>
