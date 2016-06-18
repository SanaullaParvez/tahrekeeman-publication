<?php
/**
 * Submenu Type Mega Posts Style
 *
 * @package 	CTFramework
 * @author		Sabri Taieb ( codezag )
 * @copyright	Copyright (c) Sabri Taieb
 * @link		http://vamospace.com
 * @since		Version 1.1
 *
 */
echo '<h3 class="ctf_page_title">' . __('Submenu Mega Posts Style','suppa_menu') . '</h3>';


// Categories Link Padding
echo 	'<div class="ctf_option_container suppa_all_op_container">
			<span class="ctf_option_title">'.__( 'Categories Link Padding' , 'suppa_menu' ).'</span>';

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_padding_top',
					'value'				=> '15px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_padding_right',
					'value'				=> '0px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_padding_bottom',
					'value'				=> '15px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_padding_left',
					'value'				=> '15px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			echo '<div class="clearfix"></div><span class="ctf_option_desc">'.__( 'Set the categories link padding ( Top , Right , Bottom , Left )' , 'suppa_menu' ).'</span>

		</div>';


// Post Link Font
$this->add_font(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'submenu-megaposts-cat_link_font',
		'title'				=> __( 'Categories Link Typography' , 'suppa_menu' ),
		'desc'				=> __( 'Set the categories link typography' , 'suppa_menu' ),
		'font_size'			=> 14,	// Font Size
		'font_type'			=> 'px',	// Font Size Type
		'font_family'		=> "'Arial', 'Verdana' sans-serif",	// Font Family
		'font_style'		=> 'normal',	// Font Style
		'font_color'		=> '#f1f1f1',	// Font Color
	),
	'suppa_all_op_container'
);


// Post Link Background
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'submenu-megaposts-cat_link_bg_color',
		'value'				=> 'transparent',
		'title'				=> __( 'Categories Link Background Color' , 'suppa_menu' ),
		'desc'				=> __( 'Set the categories link background color ' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);


// Post Link color ( Hover )
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'submenu-megaposts-cat_link_color_hover',
		'value'				=> '#ffffff',
		'title'				=> __( 'Categories Link Color ( Hover )' , 'suppa_menu' ),
		'desc'				=> __( 'Set the categories link color when you hover over' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);


$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'submenu-megaposts-cat_link_bg_color_hover',
		'value'				=> '#0b1b26',
		'title'				=> __( 'Categories Link background Color ( Hover )' , 'suppa_menu' ),
		'desc'				=> __( 'Set the categories link background color when you hover over' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Arrow Style
echo 	'<div class="ctf_option_container suppa_all_op_container">
			<span class="ctf_option_title">'.__( 'Category Link Arrow Style' , 'suppa_menu' ).'</span>';

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_arrow_width',
					'value'				=> '14px',
				),
				'ctf_option_no_border'
			);

			$this->add_colorpicker(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_arrow_color',
					'value'				=> '#f1f1f1',
				),
				'ctf_option_no_border'
			);

			$this->add_colorpicker(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_arrow_color_hover',
					'value'				=> '#ffffff',
				),
				'ctf_option_no_border'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_arrow_position_right',
					'value'				=> '5px',
				),
				'ctf_option_no_border'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-cat_link_arrow_position_top',
					'value'				=> '13px',
				),
				'ctf_option_no_border'
			);

			echo '<div class="clearfix"></div><span class="ctf_option_desc">'.__( 'Set the arrow style ( Size , Color , Color[Hover] , Margin Right , Margin Top )' , 'suppa_menu' ).'</span>

		</div>';

echo '<br/><br/><br/>';

// Post Thumbnail Width
$this->add_text_input(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'submenu-megaposts-post_width',
		'value'				=> '200px',
		'title'				=> __( '[Mega Posts] Thumbnail Width' , 'suppa_menu' ),
		'desc'				=> __( 'Set the mega posts menu type Thumbnail width' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Post Thumbnail Height
$this->add_text_input(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'submenu-megaposts-post_height',
		'value'				=> '160px',
		'title'				=> __( '[Mega Posts] Thumbnail Height' , 'suppa_menu' ),
		'desc'				=> __( 'Set the mega posts menu type Thumbnail height' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Post Margin
echo 	'<div class="ctf_option_container suppa_all_op_container">
			<span class="ctf_option_title">'.__( 'Post Margin' , 'suppa_menu' ).'</span>';

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-post_margin_top',
					'value'				=> '15px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-post_margin_right',
					'value'				=> '0px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-post_margin_bottom',
					'value'				=> '15px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-post_margin_left',
					'value'				=> '15px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			echo '<div class="clearfix"></div><span class="ctf_option_desc">'.__( 'Set the post margin ( Top , Right , Bottom , Left )' , 'suppa_menu' ).'</span>

		</div>';

echo '<br/><br/><br/>';

// Post Link Font
$this->add_font(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'submenu-megaposts-post_link_font',
		'title'				=> __( 'Post Link Typography' , 'suppa_menu' ),
		'desc'				=> __( 'Set the post link typography' , 'suppa_menu' ),
		'font_size'			=> 14,	// Font Size
		'font_type'			=> 'px',	// Font Size Type
		'font_family'		=> "'Arial', 'Verdana' sans-serif",	// Font Family
		'font_style'		=> 'normal',	// Font Style
		'font_color'		=> '#f1f1f1',	// Font Color
	),
	'suppa_all_op_container'
);

// Post Link Background color ( Hover )
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'mega_posts_link_bg_color',
		'value'				=> '#313131',
		'title'				=> __( 'Post Link Background Color' , 'suppa_menu' ),
		'desc'				=> __( 'Set the post link background color ' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);


// Post Link color ( Hover )
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'submenu-megaposts-post_link_color_hover',
		'value'				=> '#ffffff',
		'title'				=> __( 'Post Link Color ( Hover )' , 'suppa_menu' ),
		'desc'				=> __( 'Set the post link color when you hover over' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Post Link Padding
echo 	'<div class="ctf_option_container suppa_all_op_container">
			<span class="ctf_option_title">'.__( 'Post Link Padding' , 'suppa_menu' ).'</span>';

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-post_link_padding_top',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-post_link_padding_right',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-post_link_padding_bottom',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'submenu-megaposts-post_link_padding_left',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			echo '<div class="clearfix"></div><span class="ctf_option_desc">'.__( 'Set the post link padding ( Top , Right , Bottom , Left )' , 'suppa_menu' ).'</span>

		</div>';