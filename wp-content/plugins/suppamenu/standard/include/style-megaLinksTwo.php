<?php
/**
 * Submenu Type Links Style
 *
 * @package 	CTFramework
 * @author		Sabri Taieb ( codezag )
 * @copyright	Copyright (c) Sabri Taieb
 * @link		http://vamospace.com
 * @since		Version 1.0
 *
 */

echo '<h3 class="ctf_page_title">' . __('Submenu Mega Links Two Style','suppa_menu') . '</h3>';

// Main Links
$this->add_font(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'megaLinksTwo_mainLinks_font',
		'title'				=> __( 'Main Links Typography' , 'suppa_menu' ),
		'desc'				=> __( 'Set the main links typography' , 'suppa_menu' ),
		'font_size'			=> 18,	// Font Size
		'font_type'			=> 'px',	// Font Size Type
		'font_family'		=> "'Arial', 'Verdana' sans-serif",	// Font Family
		'font_style'		=> 'normal',	// Font Style
		'font_color'		=> '#c9c9c9',	// Font Color
	),
	'suppa_all_op_container'
);

// Main Links color ( Hover )
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'megaLinksTwo_mainLinks_colorHover',
		'value'				=> '#ffffff',
		'title'				=> __( 'Main Links Color ( Hover )' , 'suppa_menu' ),
		'desc'				=> __( 'Set the main links color when you hover over' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Main Links  Bottom Border color
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'megaLinksTwo_mainLinks_bgColorHover',
		'value'				=> '#1d3c4d',
		'title'				=> __( 'Main Links Background Color ( Hover )' , 'suppa_menu' ),
		'desc'				=> __( 'Set the main links background color when you hover over' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Main Links  Bottom Border color
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'megaLinksTwo_mainLinks_borderColor',
		'value'				=> '#1d3c4d',
		'title'				=> __( 'Main Links Bottom Border Color' , 'suppa_menu' ),
		'desc'				=> __( 'Set the main links bottom border color' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Title Padding
echo 	'<div class="ctf_option_container suppa_all_op_container">
			<span class="ctf_option_title">'.__( 'Main Links Padding' , 'suppa_menu' ).'</span>';

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_padding_top',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_padding_right',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_padding_bottom',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_padding_left',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			echo '<div class="clearfix"></div><span class="ctf_option_desc">'.__( 'Set the main links padding ( Top , Right , Bottom , Left )' , 'suppa_menu' ).'</span>

		</div>';


// Arrow Style
echo 	'<div class="ctf_option_container suppa_all_op_container">
			<span class="ctf_option_title">'.__( 'Main Links Arrow Style' , 'suppa_menu' ).'</span>';

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_arrow_width',
					'value'				=> '14px',
				),
				'ctf_option_no_border'
			);

			$this->add_colorpicker(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_arrow_color',
					'value'				=> '#f1f1f1',
				),
				'ctf_option_no_border'
			);

			$this->add_colorpicker(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_arrow_color_hover',
					'value'				=> '#ffffff',
				),
				'ctf_option_no_border'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_arrow_position_right',
					'value'				=> '5px',
				),
				'ctf_option_no_border'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_mainLinks_arrow_position_top',
					'value'				=> '13px',
				),
				'ctf_option_no_border'
			);

			echo '<div class="clearfix"></div><span class="ctf_option_desc">'.__( 'Set the arrow style ( Size , Color , Color[Hover] , Margin Right , Margin Top )' , 'suppa_menu' ).'</span>

		</div>';

echo '<br/><br/><br/>';

// SubMenu Type Mega Links
// Links Font
$this->add_font(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'megaLinksTwo_links_font',
		'title'				=> __( 'Links Typography' , 'suppa_menu' ),
		'desc'				=> __( 'Set the links typography' , 'suppa_menu' ),
		'font_size'			=> 14,	// Font Size
		'font_type'			=> 'px',	// Font Size Type
		'font_family'		=> "'Arial', 'Verdana' sans-serif",	// Font Family
		'font_style'		=> 'normal',	// Font Style
		'font_color'		=> '#c9c9c9',	// Font Color
	),
	'suppa_all_op_container'
);

// Links color ( Hover )
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'megaLinksTwo_links_color_hover',
		'value'				=> '#ffffff',
		'title'				=> __( 'Links Color ( Hover )' , 'suppa_menu' ),
		'desc'				=> __( 'Set the Links color when you hover over' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Links Padding
echo 	'<div class="ctf_option_container suppa_all_op_container">
			<span class="ctf_option_title">'.__( 'Links Padding' , 'suppa_menu' ).'</span>';

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_links_padding_top',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_links_padding_right',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_links_padding_bottom',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			$this->add_text_input(
				array(
					'group_id'			=> 'style',
					'option_id'			=> 'megaLinksTwo_links_padding_left',
					'value'				=> '10px',
					'class'				=> 'ctf_option_border_radius'
				),
				'ctf_container_border_radius'
			);

			echo '<div class="clearfix"></div><span class="ctf_option_desc">'.__( 'Set the links padding ( Top , Right , Bottom , Left )' , 'suppa_menu' ).'</span>

		</div>';


echo '<br/><br/><br/>';

/* Description Style */
$this->add_font(
	array(
		'group_id'			=> 'style', 		// Group to save this option on
		'option_id'			=> 'megaLinksTwo_desc_font', 		// Option ID
		'title'				=> __( 'Description Typography' , 'suppa_menu' ), 		// Title
		'desc'				=> __( 'Set the Description typography' , 'suppa_menu' ), 		// Description or Help
		'font_size'			=> 12,	// Font Size
		'font_type'			=> 'px',	// Font Size Type
		'font_family'		=> "'Arial', 'Verdana' sans-serif",	// Font Family
		'font_style'		=> 'normal',	// Font Style
		'font_color'		=> '#c9c9c9',	// Font Color
		'fetch'				=> 'yes',	// Fetch Database
	),
	'suppa_all_op_container'
);

// Color ( Hover )
$this->add_colorpicker(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'megaLinksTwo_desc_color_hover',
		'value'				=> '#ffffff',
		'title'				=> __( 'Color ( Hover )' , 'suppa_menu' ),
		'desc'				=> __( 'Set the description color when you hover over' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);

// Padding
$this->add_text_input(
	array(
		'group_id'			=> 'style',
		'option_id'			=> 'megaLinksTwo_desc_padding_top',
		'value'				=> '5px',
		'title'				=> __( 'Top Padding ( Hover )' , 'suppa_menu' ),
		'desc'				=> __( 'Set the description top padding' , 'suppa_menu' )
	),
	'suppa_all_op_container'
);