<?php
/**
 * @version    $Id$
 * @package    IG Pagebuilder
 * @author     InnoGearsTeam <support@TI.com>
 * @copyright  Copyright (C) 2012 TI.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.TI.com
 * Technical Support:  Feedback - http://www.TI.com
 */
if ( ! class_exists( 'IG_Item_Carousel' ) ) {

	class IG_Item_Carousel extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => esc_html__( 'Carousel Item', 'plumtree' )
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => esc_html__( 'Image File', 'plumtree' ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => esc_html__( 'Select background image for item', 'plumtree' )
					),
					array(
                        'name'    => esc_html__( 'Image Size', 'plumtree' ),
                        'id'      => 'image_size',
                        'type'    => 'select',
                        'std'     => 'medium',
                        'options' => array(
							'thumbnail' => esc_html__( 'Thumbnail', 'plumtree' ),
							'carousel-medium' => esc_html__( 'Medium', 'plumtree' ),
							'carousel-large' => esc_html__( 'Large', 'plumtree' ),
						),
                    ),
					array(
						'name'  => esc_html__( 'Heading', 'plumtree' ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
						'role'  => 'title',
                        'tooltip' => esc_html__( 'Enter heading text for item', 'plumtree' ),
					),
					array(
						'name'  => esc_html__( 'Short Description', 'plumtree' ),
						'id'    => 'description',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
                        'tooltip' => esc_html__( 'Enter description text for item', 'plumtree' ),
					),
					array(
						'name'       => esc_html__( 'URL for detailed view', 'plumtree' ),
						'id'         => 'url',
						'type'       => 'text_field',
						'class'      => 'input-sm',
						'std'        => 'http://',
						'tooltip'    => esc_html__( 'Url of link for detailed view', 'plumtree' ),
					),
					array(
						'name'       => esc_html__( 'Use as banners rotator?',  'plumtree' ),
						'id'         => 'rotator',
						'type'       => 'radio',
						'std'        => 'no',
						'options'    => array( 'yes' => esc_html__( 'Yes',  'plumtree' ), 'no' => esc_html__( 'No',  'plumtree' ) ),
                        'tooltip' => esc_html__( 'Whether to show carousel as banner rotator with buttons',  'plumtree' ),
					),
					array(
						'name'  => esc_html__( 'Button Text (shown with banner rotator)',  'plumtree' ),
						'id'    => 'btn_txt',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
                        'tooltip' => esc_html__( 'Enter text for button',  'plumtree' ),
					),
				)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
			extract( shortcode_atts( $this->config['params'], $atts ) );

			global $wpdb;

			$link = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_file);
			$id = $wpdb->get_var( $wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE BINARY guid = %s",
				$link
			) );

			$show_rotator = false;
			if ( $rotator == 'yes' )
				$show_rotator = true;

			$html_output = '';

			if (! empty( $image_file )) {
				$image_attributes = wp_get_attachment_image_src( $id, $image_size );
				if( $image_attributes ) {
					$img_alt = get_post_meta($id, '_wp_attachment_image_alt', true);
		 			$img = '<img class="lazyload" alt="'.$img_alt.'" data-src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" src="#">';
				} 
			} else {
				$img = '';
			}
			
			$img = ! empty( $image_file ) ? wp_get_attachment_image( $id, $image_size ) : '';
			$header = ! empty( $heading ) ? '<h3>'.$heading.'</h3>' : '';
			$text = ! empty( $description ) ? '<span>'.$description.'</span>' : '';
			$quick_view = '<a href="'.$image_file.'" title="'.__('Quick View', 'plumtree').'" rel="nofollow" data-magnific="link"><i class="fa fa-search"></i></a>';
			$button = ! empty( $url ) ? '<a href="'.$url.'" title="'.__('Learn More', 'plumtree').'" rel="bookmark"><i class="fa fa-link"></i></a>' : '';

			if ($show_rotator) {
				$html_output .= '<div class="item-wrapper rotator"><figure><figcaption>';
				$html_output .= $header.$text.'</figcaption>';
				$html_output .= $img;
				$html_output .= '<a href="'.$url.'" title="'.__('Learn More', 'plumtree').'" rel="bookmark">'.$btn_txt.'</a>';
				$html_output .= '</figure></div><!--separate-->';
			} else {
				$html_output .= '<div class="item-wrapper"><figure>';
				$html_output .= $img;
				$html_output .= '<figcaption>';
				$html_output .= '<div class="caption-wrapper">'.$header.$text.'<div class="btn-wrapper">'.$quick_view.$button.'</div></div>';
				$html_output .= '<div class="vertical-helper"></div></figcaption></figure></div><!--separate-->';
			}

			return $html_output;
		}

	}

}
