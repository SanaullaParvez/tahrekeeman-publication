<?php
echo '<h3 class="ctf_page_title">' . __('Sticky settings','suppa_menu') . '</h3>';


// Enable Scrolling Menu
$this->add_checkbox(
	array(
		'group_id'			=> 'settings', 
		'option_id'			=> 'settings-sticky_enable', 
		'title'				=> __( 'Enable Scrolling Menu' , 'suppa_menu' ), 	
		'desc'				=> __( 'Enable scrolling menu , and watch your menu scrolling ( very nice )' , 'suppa_menu' ), 	
		'value'				=> 'off', 	
		'fetch'				=> 'yes',
	)
);

// Enable Scrolling Menu
$this->add_checkbox(
	array(
		'group_id'			=> 'settings', 
		'option_id'			=> 'settings-sticky_mobile_enable', 
		'title'				=> __( 'Enable Scrolling Menu on Mobile' , 'suppa_menu' ), 	
		'desc'				=> __( 'Enable scrolling menu smartphones and tablets' , 'suppa_menu' ), 	
		'value'				=> 'off', 	
		'fetch'				=> 'yes',
	)
);
