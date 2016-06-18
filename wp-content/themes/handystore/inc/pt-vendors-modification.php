<?php

/*------- WC Vendors modifications ----------*/

/* Contents:
	- Deactivate wcv pro styles
	- Disable mini header on single product page
	- Remove link to dashboard from "my account" page
	- Custom WC Vendors "Sold by"
	- "Sold by" in product meta
	- Add extra fields to vendors settings
	- Related products by vendors
	- Add media Upload script for WC Vendors
	- Add extra info for vendors on "My Account"
	- Simple feedback form for customers
	- New Image Sizes for vendors
	- Modifying Vendor's rating tab
 */

if ( class_exists('WCV_Vendors') ) {

	// Deactivate wcv pro styles
	if ( class_exists('WCVendors_Pro') ) {
		add_action( 'wp_print_styles', 'pt_deregister_styles', 100 );
		function pt_deregister_styles() {
			wp_deregister_style( 'wcv-pro-store-style' );
		}
	}
	// Disable mini header on single product page
	if (WC_Vendors::$pv_options->get_option( 'shop_headers_enabled' ) ) {
		remove_action( 'woocommerce_before_single_product', array('WCV_Vendor_Shop', 'vendor_mini_header'));
	}

	// Remove link to dashboard from "my account" page
	remove_action( 'woocommerce_before_my_account', array($wcvendors_pro->wcvendors_pro_vendor_controller, 'pro_dashboard_link_myaccount') );


	// Custom WC Vendors "Sold by"
	function pt_template_loop_sold_by($product_id) {
		$vendor_id     = WCV_Vendors::get_vendor_from_product( $product_id );
		$store_title   = WCV_Vendors::get_vendor_sold_by( $vendor_id );
		if ( WCV_Vendors::is_vendor( $vendor_id ) ) {
			if ( class_exists('WCVendors_Pro') ) {
				$url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id );
				// Store logo
				$store_icon_src = wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), 'pt-vendor-logo-icon' );
				$store_icon = '';
				if ( is_array( $store_icon_src ) ) {
					$store_icon = '<img src="'. $store_icon_src[0].'" alt="vendor logo" class="store-icon" />';
				}
				echo '<div class="sold-by-container">';
				if ( $store_icon !='' ) {
					echo '<a href="'.$url.'" title="'.__('Sold by ', 'plumtree').$store_title.'">'.$store_icon.'</a>';
				} else {
					echo '<span>'.__('Sold by ', 'plumtree').'</span><br /><a href="'.$url.'">'.$store_title.'</a>';
				}
				echo "</div>";
			} else {
				$logo_src = get_user_meta( $vendor_id, 'pv_logo_image', true );
				$store_icon = '';
				if ( $logo_src && $logo_src != '') {
					global $wpdb;
					$id = $wpdb->get_var( $wpdb->prepare(
						"SELECT ID FROM $wpdb->posts WHERE BINARY guid = %s",
						$logo_src
					) );
					$store_icon_src = wp_get_attachment_image_src( $id, 'pt-vendor-logo-icon' );
					if ( is_array( $store_icon_src ) ) {
						$store_icon = '<img src="'. $store_icon_src[0].'" alt="vendor logo" class="store-icon" />';
					}
				}
				$link = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $logo_src);
				$url = WCV_Vendors::get_vendor_shop_page( $vendor_id );
				echo '<div class="sold-by-container">';
				if ( $store_icon != '' ) {
					echo '<a href="'.$url.'" title="'.__('Sold by ', 'plumtree').$store_title.'">'.$store_icon.'</a>';
				} else {
					echo '<span>'.__('Sold by ', 'plumtree').'</span><br /><a href="'.$url.'">'.$store_title.'</a>';
				}
				echo "</div>";
			}
		}
	}

	remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );
	if ( class_exists('WCVendors_Pro') ) {
		remove_action( 'woocommerce_after_shop_loop_item', array( $wcvendors_pro->wcvendors_pro_store_controller, 'loop_sold_by'), 8 );
	}
	if ( handy_get_option('show_wcv_loop_sold_by')=='on' ) {
		add_action( 'woocommerce_after_shop_loop_item', 'pt_template_loop_sold_by', 15 );
	}


	// "Sold by" in product meta
	function dash_sold_by_wrapper_start() {
		echo '<span class="sold-by-wrapper">';
	}
	function dash_sold_by_wrapper_end() {
		echo '</span>';
	}
	function dash_sold_by_meta_custom_message($message) {
		$message = WC_Vendors::$pv_options->get_option( 'sold_by_label' ).': ';
    return $message;
	}
	add_filter( 'wcvendors_cart_sold_by_meta', 'dash_sold_by_meta_custom_message', 10, 1);
	add_action( 'woocommerce_product_meta_start', 'dash_sold_by_wrapper_start', 9 );
	add_action( 'woocommerce_product_meta_start', 'dash_sold_by_wrapper_end', 11 );


	// Add extra fields to vendors settings
	// Fields for WC Vendors Free
	if ( !class_exists('WCVendors_Pro') ) {
		// front end
		add_action( 'wcvendors_settings_before_paypal_frontend', 'pt_add_frontend_vendor_fields' );
		// Save data from new fields
		add_action( 'wcvendors_shop_settings_saved', 'pt_save_new_vendor_fields' );
		add_action( 'wcvendors_update_admin_user', 'pt_save_new_vendor_fields' );
		// Add new fields to user profile (for admin)
		add_action( 'show_user_profile', 'pt_add_backend_vendor_fields' );
		add_action( 'edit_user_profile', 'pt_add_backend_vendor_fields' );
		// Save data from user profile (for admin)
		add_action( 'personal_options_update', 'pt_save_new_vendor_fields' );
		add_action( 'edit_user_profile_update', 'pt_save_new_vendor_fields' );
	}

	// back end
	function pt_add_backend_vendor_fields($user) { ?>

	  <h3><?php esc_html_e( 'Extra Vendor Options (Handy Store Modifications)', 'plumtree' ); ?></h3>

	  <table class="form-table">
	  	<tbody>
	  	  <?php $user_id = $user->ID; ?>

		  <tr>
		    <th><?php esc_html_e( 'Upload Logo Image', 'plumtree' ); ?></th>
		    <td>
		    	<input name="pv_logo_image" id="pv_logo_image" type="text" value="<?php echo esc_url( get_user_meta( $user_id, 'pv_logo_image', true ) ); ?>" />
				<span id="pv_logo_image_button" class="button pt_upload_image_button"><?php esc_html_e( 'Upload', 'plumtree' ); ?></span>
			</td>
		  </tr>

		  <tr>
		    <th><?php esc_html_e( 'Logo Position', 'plumtree' ); ?></th>
		    <td>
		    <?php $value = get_user_meta( $user_id,'pv_logo_position', true );
		    	  if ( $value == '' ) $value = 'left'; ?>
			    <input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_left" value="left" <?php checked( $value, 'left'); ?>/><label for="logo_position_left"><?php esc_html_e( ' Left', 'plumtree' ); ?></label><br />
				<input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_center" value="center" <?php checked( $value, 'center'); ?>/><label for="logo_position_center"><?php esc_html_e( ' Center', 'plumtree' ); ?></label><br />
				<input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_right" value="right" <?php checked( $value, 'right'); ?>/><label for="logo_position_right"><?php esc_html_e( ' Right', 'plumtree' ); ?></label>
			</td>
		  </tr>

		  <tr>
		    <th><?php esc_html_e( 'Products Carousel', 'plumtree' ); ?></th>
		    <td>
		    <?php $value = get_user_meta( $user_id,'pv_featured_carousel', true ); ?>
		    	<label for="pv_featured_carousel">
		    		<input type="checkbox" name="pv_featured_carousel" id="pv_featured_carousel" <?php checked( $value, 'on' ); ?> />
		    		<?php esc_html_e( 'Check if you want to add carousel with featured products to your shop page', 'plumtree' ) ?>
		    	</label>
			</td>
		  </tr>

		  <tr>
		    <th><?php esc_html_e( 'Vendor question form', 'plumtree' ); ?></th>
		    <td>
		    <?php $value = get_user_meta( $user_id,'pv_question_form', true ); ?>
		    	<label for="pv_question_form">
		    		<input type="checkbox" name="pv_question_form" id="pv_question_form" <?php checked( $value, 'on' ); ?> />
		    		<?php esc_html_e( 'Check if you want to add "Ask a question about this product" form to "Seller Tab" on each of your products', 'plumtree' ) ?>
		    	</label>
			</td>
		  </tr>

		</tbody>
	  </table>

	<?php
	}
	// front end
	function pt_add_frontend_vendor_fields() { ?>

	  <?php $user_id = get_current_user_id(); ?>

	  <div class="pv_logo_image_container">
	    <p><strong><?php esc_html_e( 'Upload Logo Image', 'plumtree' ); ?></strong><br/><br/>
		    <input name="pv_logo_image" id="pv_logo_image" type="text" value="<?php echo esc_url( get_user_meta( $user_id, 'pv_logo_image', true ) ); ?>" />
			<span id="pv_logo_image_button" class="button pt_upload_image_button"><?php esc_html_e( 'Upload', 'plumtree' ); ?></span>
		</p>
	  </div>

	  <div class="pv_logo_position_container">
	    <p><strong><?php esc_html_e( 'Logo Position', 'plumtree' ); ?></strong></p>
	    <?php $value = get_user_meta( $user_id,'pv_logo_position', true );
	    	  if ( $value == '' ) $value = 'left'; ?>
	    <p>
		    <input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_left" value="left" <?php checked( $value, 'left'); ?>/><label for="logo_position_left"><?php esc_html_e( ' Left', 'plumtree' ); ?></label><br />
			<input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_center" value="center" <?php checked( $value, 'center'); ?>/><label for="logo_position_center"><?php esc_html_e( ' Center', 'plumtree' ); ?></label><br />
			<input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_right" value="right" <?php checked( $value, 'right'); ?>/><label for="logo_position_right"><?php esc_html_e( ' Right', 'plumtree' ); ?></label>
		</p>
	  </div>

	  <div class="pv_featured_carousel_container">
	    <p><strong><?php esc_html_e( 'Products Carousel', 'plumtree' ); ?></strong></p>
	    <?php $value = get_user_meta( $user_id,'pv_featured_carousel', true ); ?>
	    <p>
	    	<input type="checkbox" class="input-checkbox" name="pv_featured_carousel" id="pv_featured_carousel" <?php checked( $value, 'on' ); ?> /><label class="checkbox" for="pv_featured_carousel"><?php esc_html_e( 'Check if you want to add carousel with featured products to your shop page', 'plumtree' ) ?></label>
		</p>
	  </div>

	  <div class="pv_question_form_container">
	    <p><strong><?php esc_html_e( 'Vendor question form', 'plumtree' ); ?></strong></p>
	    <?php $value = get_user_meta( $user_id,'pv_question_form', true ); ?>
	    <p>
	    	<input type="checkbox" class="input-checkbox" name="pv_question_form" id="pv_question_form" <?php checked( $value, 'on' ); ?> /><label class="checkbox" for="pv_question_form"><?php esc_html_e( 'Check if you want to add "Ask a question about this product" form to "Seller Tab" on each of your products', 'plumtree' ) ?></label>
		</p>
	  </div>

	<?php }
	// Save new fields
	function pt_save_new_vendor_fields($user_id) {
		if ( isset( $_POST['pv_logo_image'] ) ) {
			update_user_meta( $user_id, 'pv_logo_image', $_POST['pv_logo_image'] );
		}
		if ( isset( $_POST['pv_logo_position'] ) ) {
			update_user_meta( $user_id, 'pv_logo_position', $_POST['pv_logo_position'] );
		}
		if ( isset( $_POST['pv_featured_carousel'] ) ) {
		    update_user_meta( $user_id, 'pv_featured_carousel', $_POST['pv_featured_carousel'] );
		} else {
		  	update_user_meta( $user_id, 'pv_featured_carousel', 'off' );
		}
		if ( isset( $_POST['pv_question_form'] ) ) {
		    update_user_meta( $user_id, 'pv_question_form', $_POST['pv_question_form'] );
		} else {
		  	update_user_meta( $user_id, 'pv_question_form', 'off' );
		}
	}

	// Fields for WC Vendors Pro
	if ( class_exists('WCVendors_Pro') ) {
		// front end
		add_action( 'wcvendors_settings_after_seller_info', 'pt_add_frontend_vendor_pro_fields' );
		add_action( 'wcv_after_variations_tab', 'pt_add_frontend_product_vendor_pro_fields' );
		// Save data from new fields
		add_action( 'wcv_pro_store_settings_saved', 'pt_save_new_vendor_pro_fields' );
		add_action( 'wcv_save_product_meta', 'pt_save_new_vendor_pro_product_fields' );
	}
	// front end
	function pt_add_frontend_vendor_pro_fields() {
	$user_id = get_current_user_id(); ?>

	  <div class="pv_featured_carousel_container">
	    <p><strong><?php esc_html_e( 'Products Carousel', 'plumtree' ); ?></strong></p>
	    <?php $value = get_user_meta( $user_id,'pv_featured_carousel', true ); ?>
	    <p>
	    	<input type="checkbox" class="input-checkbox" name="pv_featured_carousel" id="pv_featured_carousel" <?php checked( $value, 'on' ); ?> /><label class="checkbox" for="pv_featured_carousel"><?php esc_html_e( 'Check if you want to add carousel with featured products to your shop page', 'plumtree' ) ?></label>
		</p>
	  </div>

	  <div class="pv_question_form_container">
	    <p><strong><?php esc_html_e( 'Vendor question form', 'plumtree' ); ?></strong></p>
	    <?php $value = get_user_meta( $user_id,'pv_question_form', true ); ?>
	    <p>
	    	<input type="checkbox" class="input-checkbox" name="pv_question_form" id="pv_question_form" <?php checked( $value, 'on' ); ?> /><label class="checkbox" for="pv_question_form"><?php esc_html_e( 'Check if you want to add "Ask a question about this product" form to "Seller Tab" on each of your products', 'plumtree' ) ?></label>
		</p>
	  </div>

	<?php }
	// product extra fields
	function pt_add_frontend_product_vendor_pro_fields() { ?>
	<hr style="clear: both;" />
	<h2><?php _e('Handy Store extra Settings', 'plumtree'); ?></h2>
	<div class="all-100">
			<!-- Extra Gallery -->
			<?php $values = get_post_custom($object_id);
				$pt_product_extra_gallery = isset( $values['pt_product_extra_gallery'] ) ? esc_attr( $values['pt_product_extra_gallery'][0] ) : 'off';
				$pt_vendor_special_offers_carousel = isset( $values['pt_vendor_special_offers_carousel'] ) ? esc_attr( $values['pt_vendor_special_offers_carousel'][0] ) : 'off'; ?>
			<div class="product-extra-gallery">
			<input type="checkbox" class="input-checkbox" name="pt_product_extra_gallery" id="pt_product_extra_gallery" <?php checked( $pt_product_extra_gallery, 'on' ); ?> /><label class="checkbox" for="pt_product_extra_gallery"><?php _e( 'Use extra gallery for this product', 'plumtree' ) ?></label>
			<p><?php _e( 'Check the checkbox if you want to use extra gallery (appeared on hover) for this product. The first 3 images of the product gallery are going to be used for gallery.', 'plumtree'); ?></p>
		</div>
		<br />
		<div class="vendor-special-offers-carousel">
			<input type="checkbox" class="input-checkbox" name="pt_vendor_special_offers_carousel" id="pt_vendor_special_offers_carousel" <?php checked( $pt_vendor_special_offers_carousel, 'on' ); ?> /><label class="checkbox" for="pt_vendor_special_offers_carousel"><?php _e( 'Add this product to "Special Offers" carousel', 'plumtree' ) ?></label>
			<p><?php _e( 'Check the checkbox if you want to add this product to the "Special Offers" carousel on your Vendor Store Page.', 'plumtree'); ?></p>
		</div>

	</div>
<?php }

	// Save data from new fields
	function pt_save_new_vendor_pro_fields( $user_id ) {
		if ( isset( $_POST['pv_featured_carousel'] ) ) {
		    update_user_meta( $user_id, 'pv_featured_carousel', $_POST['pv_featured_carousel'] );
		} else {
		  	update_user_meta( $user_id, 'pv_featured_carousel', 'off' );
		}
		if ( isset( $_POST['pv_question_form'] ) ) {
		    update_user_meta( $user_id, 'pv_question_form', $_POST['pv_question_form'] );
		} else {
		  	update_user_meta( $user_id, 'pv_question_form', 'off' );
		}
	}

	function pt_save_new_vendor_pro_product_fields( $post_id ) {
		if ( isset( $_POST['pt_product_extra_gallery'] ) ) {
		    update_post_meta( $post_id, 'pt_product_extra_gallery', $_POST['pt_product_extra_gallery'] );
		} else {
		  	update_post_meta( $post_id, 'pt_product_extra_gallery', 'off' );
		}
		if ( isset( $_POST['pt_vendor_special_offers_carousel'] ) ) {
		    update_post_meta( $post_id, 'pt_vendor_special_offers_carousel', $_POST['pt_vendor_special_offers_carousel'] );
		} else {
		  	update_post_meta( $post_id, 'pt_vendor_special_offers_carousel', 'off' );
		}
	}

	// Related products by vendors
	if (handy_get_option('show_wcv_related_products')=='on') {
		function pt_output_vendors_related_products() {
			global $product, $woocommerce_loop;

			$vendor = get_the_author_meta('ID');
			$posts_per_page = (handy_get_option('wcv_qty') != '') ? handy_get_option('wcv_qty') : '3';
			$sold_by = WCV_Vendors::get_vendor_sold_by( $vendor );

			$args = apply_filters('woocommerce_related_products_args', array(
				'post_type'				=> 'product',
				'ignore_sticky_posts'	=> 1,
				'no_found_rows' 		=> 1,
				'posts_per_page' 		=> $posts_per_page,
				'orderby' 				=> 'name',
				'author' 				=> $vendor,
				'post__not_in'			=> array($product->id)
			) );

			$products = new WP_Query( $args );
			if ( $products->have_posts() ) : ?>

			<div class="wcv-related products">

				<h2><?php echo __( 'More Products by ', 'plumtree' ).$sold_by; ?></h2>

				<?php woocommerce_product_loop_start(); ?>

					<?php while ( $products->have_posts() ) : $products->the_post(); ?>

						<?php woocommerce_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php woocommerce_product_loop_end(); ?>

			</div>

			<?php endif;
			wp_reset_postdata();
		}
		add_action('woocommerce_after_single_product_summary', 'pt_output_vendors_related_products', 15);
	}


	// Add media Upload script for WC Vendors
	if ( !class_exists('WCVendors_Pro') ) {
		function pt_add_media_upload_scripts(){
			$mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';
	        $modes = array( 'grid', 'list' );
	        if ( isset( $_GET['mode'] ) && in_array( $_GET['mode'], $modes ) ) {
	            $mode = $_GET['mode'];
	            update_user_option( get_current_user_id(), 'media_library_mode', $mode );
	        }
	        if( ! empty ( $_SERVER['PHP_SELF'] ) && 'upload.php' === basename( $_SERVER['PHP_SELF'] ) && 'grid' !== $mode ) {
	            wp_enqueue_script( 'media' );
	        }
	        if ( ! did_action( 'wp_enqueue_media' ) ) wp_enqueue_media();
	    	wp_enqueue_script( 'upload_media_script', get_template_directory_uri() .'/js/upload-media.js', array('jquery'), true);
		}
	    add_action( 'wp_enqueue_scripts', 'pt_add_media_upload_scripts' );
	    add_action( 'admin_enqueue_scripts', 'pt_add_media_upload_scripts' );
	}


	// Add extra info for vendors on "My Account"
	function pt_add_vendors_info() {
		$user = wp_get_current_user();
		if ( in_array( 'vendor', (array) $user->roles ) ) { ?>
			<div class="account-vendor-options">
				<h2><?php _e("Vendor's Options", 'plumtree'); ?></h2>
			    <?php // Get url's for vendors pages
			    if ( class_exists('WCV_Vendors') ) {
	        		$dashboard_url = get_permalink(WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' ));
	        	}
	        	if ( class_exists('WCVendors_Pro') ) {
	            	$dashboard_url = get_permalink(WCVendors_Pro::get_option( 'dashboard_page_id' ));
	        	} ?>
	        	<p><?php _e('Follow this link to get to the vendor dashboard, where you can control your store, add products, generate reports on accomplished deals etc.', 'plumtree'); ?></p>
	        	<a class="btn btn-primary rounded" href="<?php echo esc_url($dashboard_url); ?>" title="<?php _e('Go to Vendor Dashboard', 'plumtree'); ?>" rel="nofollow" target="_self"><?php _e('Go to Vendor Dashboard', 'plumtree'); ?></a>
			</div>
		<?php } elseif ( in_array( 'pending_vendor', (array) $user->roles ) ) { ?>
			<div class="account-vendor-options">
				<h2><?php _e("Vendor's Options", 'plumtree'); ?></h2>
	        	<p><?php _e('Your account has not yet been approved to become a vendor. When it is, you will receive an email telling you that your account is approved!', 'plumtree'); ?></p>
			</div>

		<?php }
	}
	add_action( 'woocommerce_before_my_account', 'pt_add_vendors_info' );


	// Simple feedback form for customers
	/* Enqueue scripts & styles */
	function pt_vendor_feedback_scripts() {
		wp_enqueue_script( 'ajax-wcv-feedback-script', get_template_directory_uri(). '/js/ajax-wcv-feedback-script.js', array('jquery'), '1.0', true );
	    wp_localize_script( 'ajax-wcv-feedback-script', 'ajax_wcv_form_object', array(
	        'ajaxurl' => admin_url( 'admin-ajax.php' ),
	        'loadingmessage' => __('Sending e-mail, please wait...', 'plumtree')
	    ));
	}
	add_action( 'wp_ajax_nopriv_pt_ajax_send_mail_to_vendor', 'pt_deliver_mail' );
	add_action( 'wp_ajax_pt_ajax_send_mail_to_vendor', 'pt_deliver_mail' );
	add_action('init', 'pt_vendor_feedback_scripts');

	/* Add feedback form to Seller Info tab */
	add_filter( 'wcv_after_seller_info_tab', 'pt_html_form_code' );

	function pt_html_form_code() {
		$output = '<div class="vendor-feed-container">';
		$output .= '<a class="btn btn-primary rounded" role="button" data-toggle="collapse" href="#collapseFeedForm" aria-expanded="false" aria-controls="collapseExample">
  				   '.__('Ask a question about this Product', 'plumtree').'
				    </a>';
		$output .= '<div class="collapse" id="collapseFeedForm">';
		$output .= '<form id="vendor-feedback" class="about-product-question" method="post">
				   '.wp_nonce_field('ajax-vendor-feedback-nonce', 'security').
				   '<input id="vendor-mail" type="hidden" name="cf-vendor-mail" value="'.get_the_author_meta('user_email').'">';
		$output .= '<div style="width:48%; display: inline-block; float:left;">';
		$output .= '<p class="form-row form-row-wide">
					<label for="sender-name">'.__('Your Name ', 'plumtree').'<abbr title="required" class="required">*</abbr></label>
					<input required aria-required="true" id="sender-name" type="text" name="name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["name"] ) ? esc_attr( $_POST["name"] ) : '' ) . '" />
					</p>';
		$output .= '<p class="form-row form-row-wide">
					<label for="sender-email">'.__('Your Email ', 'plumtree').'<abbr title="required" class="required">*</abbr></label>
					<input required aria-required="true" id="sender-email" type="email" name="email" value="' . ( isset( $_POST["email"] ) ? esc_attr( $_POST["email"] ) : '' ) . '" />
					</p>';
		$output .= '<p class="form-row form-row-wide">
					<label for="subject">'.__('Subject ', 'plumtree').'<abbr title="required" class="required">*</abbr></label>
					<input required aria-required="true" id="subject" type="text" name="subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["subject"] ) ? esc_attr( $_POST["subject"] ) : 'Question about '.get_the_title() ) . '" />
					</p>';
		$output .= '</div>';
		$output .= '<div style="width:48%; display: inline-block; margin: 0 0 0 4%;">';
		$output .= '<p class="form-row form-row-wide">
					<label for="text-message">'.__('Your Message ', 'plumtree').'<abbr title="required" class="required">*</abbr></label>
					<textarea required aria-required="true" id="text-message" name="message">' . ( isset( $_POST["message"] ) ? esc_attr( $_POST["message"] ) : '' ) . '</textarea>
					</p>';
		$output .= '</div>';
		$output .= '<input class="submit-btn" type="submit" name="cf-submitted" value="Send">
					<p class="status"></p>';
		$output .= '</form>';
		$output .= '</div></div>';

		$vendor_id = WCV_Vendors::get_vendor_from_product( $product_id );
		$question_form = get_user_meta( $vendor_id , 'pv_question_form', true );

		if ( $question_form === 'on' ) {
			return $output;
		}
	}

	/* Delivery handle for form */
	function pt_deliver_mail() {
		// First check the nonce, if it fails the function will break
    	check_ajax_referer( 'ajax-vendor-feedback-nonce', 'security' );

		// sanitize form values
		$name    = sanitize_text_field( $_POST["sender"] );
		$email   = sanitize_email( $_POST["sender-email"] );
		$subject = sanitize_text_field( $_POST["subject"] );
		$message = esc_textarea( $_POST["text"] );
		$to      = sanitize_email( $_POST["to-email"] );

		$headers = "From: $name <$email>" . "\r\n";

		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo json_encode( array( 'message' => __('Thanks for contacting me, expect a response soon.', 'plumtree'), ) );
		} else {
			echo json_encode( array( 'message' => __('An unexpected error occurred.', 'plumtree'), ) );
		}
		die();
	}


	// New Image Sizes for vendors
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'pt-vendor-main-logo', 150, 150, false );
		add_image_size( 'pt-vendor-logo-icon', 30, 30, true );
	}


	// Modifying Vendor's rating tab
	if ( class_exists('WCVendors_Pro') ) {
		// Remove rating tab
		function remove_vendors_rating_tab($tabs) {
			if ( $tabs['vendor_ratings_tab'] ) {
				unset( $tabs['vendor_ratings_tab'] );
			}
			return $tabs;
		}
		add_filter( 'woocommerce_product_tabs', 'remove_vendors_rating_tab' );
		// Add rating to seller info tab
		function additional_vendors_info() {
			$vendor_id     = WCV_Vendors::get_vendor_from_product( get_the_ID() );
			if ( WCV_Vendors::is_vendor( $vendor_id ) ) {
				// Store logo
				$store_icon_src = wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), 'pt-vendor-main-logo' );
				$store_icon = '';
				if ( is_array( $store_icon_src ) ) {
					$store_icon = '<img src="'. $store_icon_src[0].'" alt="vendor logo" class="store-icon" />';
				}
				// Socials
				$twitter_username 	= get_user_meta( $vendor_id , '_wcv_twitter_username', true );
				$instagram_username = get_user_meta( $vendor_id , '_wcv_instagram_username', true );
				$facebook_url 		  = get_user_meta( $vendor_id , '_wcv_facebook_url', true );
				$linkedin_url 		  = get_user_meta( $vendor_id , '_wcv_linkedin_url', true );
				$youtube_url 		    = get_user_meta( $vendor_id , '_wcv_youtube_url', true );
				$googleplus_url 	  = get_user_meta( $vendor_id , '_wcv_googleplus_url', true );
				$socials = '';
				if ( $facebook_url != '') { $socials .= '<li><a href="'.$facebook_url.'" target="_blank"><i class="fa fa-facebook-square"></i></a></li>'; }
				if ( $instagram_username != '') { $socials .= '<li><a href="//instagram.com/'.$instagram_username.'" target="_blank"><i class="fa fa-instagram"></i></a></li>'; }
				if ( $twitter_username != '') { $socials .= '<li><a href="//twitter.com/'.$twitter_username.'" target="_blank"><i class="fa fa-twitter-square"></i></a></li>'; }
				if ( $googleplus_url != '') { $socials .= '<li><a href="'.$googleplus_url.'" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>'; }
				if ( $youtube_url != '') { $socials .= '<li><a href="'.$youtube_url.'" target="_blank"><i class="fa fa-youtube-square"></i></a></li>'; }
				if ( $linkedin_url != '') { $socials .= '<li><a href="'.$linkedin_url.'" target="_blank"><i class="fa fa-linkedin-square"></i></a></li>'; }
	  			// Ratings
	  			$ratings = '';
	  			if ( ! WCVendors_Pro::get_option( 'ratings_management_cap' ) ) {
	  				$average_rate = WCVendors_Pro_Ratings_Controller::get_ratings_average( $vendor_id );
	  				$rate_count = WCVendors_Pro_Ratings_Controller::get_ratings_count( $vendor_id );
	  				$url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id ) . '?ratings=all';
	  				if ( $average_rate !=0 ) {
		  				$ratings .= __('Rating: ', 'plumtree').'<span>'.$average_rate.'</span>'.__(' based on ', 'plumtree').sprintf( _n( '1 rating.', '%s ratings.', $rate_count, 'plumtree' ), $rate_count);
		  				$ratings .= '<a href="'.$url.'">'.__('View all ratings', 'plumtree').'</a>';
	  				} else {
	  					$ratings .= __("Rating: This Seller still doesn't have any ratings yet.", 'plumtree');
	  				}
	  			}

	  			// Output all info
					$store_url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id );
					$store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );
	  			$store_info = '<div class="pv_additional_seller_info">';
	  			if ($store_icon != '') {
	  				$store_info .= '<div class="store-brand">'.$store_icon.'</div>';
	  			}
			   	$store_info .= '<div class="store-info">';
			   	$store_info .= '<h3><a href="'.$store_url.'">'.$store_name.'</a></h3>';
				$store_info .= '<div class="rating-container">'.$ratings.'</div>';
				if ($socials != '') {
	  				$store_info .= '<ul class="social-icons">'.$socials.'</ul>';
	  			}
			   	$store_info .= '</div></div>';
			   	return $store_info;
			}
		}
		add_filter( 'wcv_before_seller_info_tab', 'additional_vendors_info' );
	}

	/* Removing empty label */
	function dash_remove_label_on_signup() {
		return array(
			'type' => 'hidden',
			'id' => '_wcv_vendor_application_id',
			'value'	=> get_current_user_id(),
			'show_label' => false
		);
	}
	add_filter( 'wcv_vendor_application_id', 'dash_remove_label_on_signup');

} // end of file
