<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
?>
<?php
// Related products extra class
$new_class = '';
if ( is_product() ) {
	$upsells_qty = (handy_get_option('upsells_qty') != '') ? handy_get_option('upsells_qty') : '2';
	$related_qty = (handy_get_option('related_products_qty') != '') ? handy_get_option('related_products_qty') : '4';
	$new_class = ' related-cols-'.$related_qty.' upsells-cols-'.$upsells_qty;
	if (class_exists('WCV_Vendors')) {
		$wcv_related_qty = (handy_get_option('wcv_qty') != '') ? handy_get_option('wcv_qty') : '4';
		$new_class .= ' wcv-cols-'.$wcv_related_qty;
	}
	echo '<ul class="products'.esc_attr($new_class).'">';
} elseif ( is_archive() || is_tax() ) {
	echo '<ul class="products" ';
	if ( handy_get_option('store_filters')=='isotope' ) {
		echo 'data-isotope="container" data-isotope-layout="fitrows" data-isotope-elements="product"';
	} else {
		echo 'data-filters="container"';
	} echo '>';
} else {
	echo '<ul class="products">';
} ?>
