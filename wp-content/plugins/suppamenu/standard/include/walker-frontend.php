<?php

/*
|   Suppa Back-End Menu Walker
|
*/


if( !class_exists( 'suppa_menu_walker' ) )
{

    /**
     * This walker is for the frontend
     */
    class suppa_menu_walker extends Walker {

        /**
         * @see Walker::$tree_type
         * @var string
         */
        var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
        /**
         * @see Walker::$db_fields
         * @todo Decouple this.
         * @var array
         */
        var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );


        /**
         * Suppa Menu Variables
         */
        var $menu_type                      = '';
        var $menu_key                       = '_menu-item-suppa-';
        var $top_level_counter              = 0;
        var $dropdown_first_level_conuter   = 0;
        var $dropdown_second_level_conuter  = 0;
        var $dropdown_sub_open_pos          = '';
        var $column                         = 0;
        var $dropdown_width                 = "180px";
        var $dropdown_position              = "left";
        var $links_column_width             = "180px";
        var $mega_posts_items               = array();
        var $suppa_item_id                  = 0;
        var $linksTwo_parentLink_ID         = 0;
        var $linksTwo_childsContainer       = "";
        var $megaLinksTwo_first_item        = true;

        var $thumb_sizes                    = array();
        var $skin_options                   = array();

        function __construct( $skin_options = array(), $thumb_sizes = array() ){
            $this->skin_options = $skin_options;
            $this->thumb_sizes  = $thumb_sizes;
        }

        /**
         * @see Walker::start_lvl()
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int $depth Depth of page. Used for padding.
         */
        function start_lvl(&$output, $depth = 0, $args = array())
        {
            // DropDown
            if( $this->menu_type == 'dropdown' )
            {
                if( $depth >= 1 )
                {
                    $output = str_replace('<span class="suppa_ar_arrow_right_'.$this->dropdown_first_level_conuter.'_'.$depth.'"></span>', '<span class="era_suppa_arrow_box suppa_fa_carret_right"><span aria-hidden="true" class="suppa-caret-right" /></span><span class="era_suppa_arrow_box suppa_fa_carret_left"><span aria-hidden="true" class="suppa-caret-left" /></span>' , $output );
                    $output = str_replace('<span class="suppa_ar_arrow_right_'.$this->dropdown_second_level_conuter.'_'.$depth.'"></span>', '<span class="era_suppa_arrow_box suppa_fa_carret_right"><span aria-hidden="true" class="suppa-caret-right" /></span><span class="era_suppa_arrow_box suppa_fa_carret_left"><span aria-hidden="true" class="suppa-caret-left" /></span>' , $output );
                }

                $css_left = '0px';
                if( $depth != 0 )
                    $css_left = $this->dropdown_width;

                $output .= '<div class="suppa_submenu suppa_submenu_'.$depth.' '.$this->dropdown_sub_open_pos.'" style="width:'.$this->dropdown_width.';'.$this->dropdown_position.':'.$css_left.';" >';
            }

            // mega_posts
            if( 'mega_posts' == $this->menu_type )
            {
                if( $depth == 0 )
                    $output .= '<div class="suppa_mega_posts_categories" >';
            }

        }


        /**
         * @see Walker::end_lvl()
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int $depth Depth of page. Used for padding.
         */
        function end_lvl(&$output, $depth = 0, $args = array())
        {

            // Dropdown
            if( $this->menu_type == 'dropdown' )
            {
                $output .= '</div>';
            }

            // mega_posts
            if( 'mega_posts' == $this->menu_type )
            {
                if( $depth == 0 )
                {
                    $output .= '</div><!--suppa_mega_posts_categories-->';
                }
            }

            // LinksTwo
            if( $this->menu_type == 'links_style_two' )
            {
                if( $depth == 0 )
                    $output .= '</div><!--suppa_linksTwo_mainContainer-->'.$this->linksTwo_childsContainer;
            }

        }

        /**
         * @see Walker::start_el()
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param int $current_page Menu item ID.
         * @param object $args
         */
        function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {

            global $wp_query;

            // Link Meta
            $item_meta = get_post_meta( $item->ID );

            // Link attributes
            $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

            // Link Icon
            $link_title = $item->title;
            $icon_type  = @$item_meta['_menu-item-suppa-link_icon_type'][0];
            $icon       = @$item_meta['_menu-item-suppa-link_icon_image'][0];
            $icon_hover = @$item_meta['_menu-item-suppa-link_icon_image_hover'][0];
            $icon_only  = @$item_meta['_menu-item-suppa-link_icon_only'][0];
            $FA_icon    = @$item_meta['_menu-item-suppa-link_icon_fontawesome'][0];
            $link_html  = "";

            // Link Classes
            $class_names = '';
            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
            $class_names = esc_attr( $class_names );
            if( $depth === 0 )
            {
                $class_names .= ' suppa_top_level_link';
            }
            $has_children = 'suppa_no_children';
            if( strpos( $class_names, 'menu-item-has-children') !== false ) $has_children = 'suppa_has_children';


            // Item Description
            $description    = ! empty( $item->description ) ? '<span class="suppa_item_desc">'.$item->description.'</span>' : '';

            // Item Icon
            if( $icon_type == "upload" ){
                if( $icon != "" ){
                    $check_retina_icon  = ( $icon_hover != "" ) ? $icon_hover : $icon;
                    $check_icon_only    = ( $icon_only == "on" ) ? '' : '<span class="suppa_item_title">'.$link_title.$description.'</span>' ;
                    $link_html = '<img class="suppa_upload_img suppa_UP_icon" src="'.$icon.'" alt="'.$link_title.'" data-icon="'.$icon.'" data-retina="'.$check_retina_icon.'" >'.$check_icon_only;
                }
                else{
                    $link_html = '<span class="suppa_item_title">'.$link_title.$description.'</span>';
                }
            }
            else if( $icon_type == "fontawesome" ){
                if( $FA_icon != "" ){
                    $check_icon_only    = ( $icon_only == "on" ) ? '' : '<span class="suppa_item_title">'.$link_title.$description.'</span>';
                    $link_html = '<span class="ctf_suppa_fa_box suppa_FA_icon"><span aria-hidden="true" class="'.$FA_icon.'" ></span></span>'.$check_icon_only;
                }
                else{
                    $link_html = '<span class="suppa_item_title">'.$link_title.$description.'</span>';
                }
            }
            else{
                $link_html = '<span class="suppa_item_title">'.$link_title.$description.'</span>';
            }

            // If Level 0
            if( $depth === 0 ){

                $this->megaLinksTwo_first_item = true;
                $this->linksTwo_childsContainer = "";

                $this->top_level_counter += 1;
                $this->menu_type =  @$item_meta[$this->menu_key.'menu_type'][0];

                $this_item_position =  @$item_meta[$this->menu_key.'link_position'][0];
                $this_item_position_class = ' suppa_menu_position_'.$this_item_position.' ';
                $class_names .= $this_item_position_class;

                $this_item_position_css = ( $this_item_position == "right" || $this_item_position == "none" ) ? ' float:none; ' : ' float:left; ';

                // User Log in/out/both
                $is_user_logged_in = is_user_logged_in();
                $user_logged_in_out =  @$item_meta[$this->menu_key.'link_user_logged'][0];
                $display_item = ' display:none !important; ';
                if( $user_logged_in_out == 'both' )
                { $display_item = ''; }
                else if ( $user_logged_in_out == 'logged_in' && $is_user_logged_in )
                { $display_item = ''; }
                else if ( $user_logged_in_out == 'logged_out' && !$is_user_logged_in )
                { $display_item = ''; }

                // Dropdown
                if( 'dropdown' == $this->menu_type ){
                    $this->dropdown_width =  @$item_meta[$this->menu_key.'dropdown_width'][0];
                    if( @$item_meta[$this->menu_key.'dropdown_open_pos'][0] != '' ){
                        $this->dropdown_position =  'right';
                        $this->dropdown_sub_open_pos = 'suppa_submenu_pos_right';
                    }
                    else{
                        $this->dropdown_position =  'left';
                        $this->dropdown_sub_open_pos = 'suppa_submenu_pos_left';
                    }

                    $arrow = '';
                    $has_arrow = '';
                    if( in_array( 'menu-item-has-children' , $item->classes ) ){
                        $has_arrow = ' suppa_top_links_has_arrow ';
                        $arrow = '<span class="era_suppa_arrow_box ctf_suppa_fa_box_top_arrow"><span aria-hidden="true" class="suppa-caret-down"></span></span>';
                    }

                    $item_output = '<div style="'.$this_item_position_css.' '.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_dropdown suppa_menu_'.$this->top_level_counter.'" ><a '.$attributes.' class="'.$class_names.' '.$has_arrow.'" >'.$link_html.$arrow.'</a>';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }

                // Links
                else if( 'links' == $this->menu_type ){
                    // options
                    $this->links_column_width =  @$item_meta[$this->menu_key.'links_column_width'][0];
                    $links_fullwidth =  @$item_meta[$this->menu_key.'links_fullwidth'][0];
                    $container_style = 'style="width:100%; left:0px;"';
                    $top_link_style = '';

                    if( $links_fullwidth == "off" or $links_fullwidth == "" ){
                        $container_width =  @$item_meta[$this->menu_key.'links_width'][0];
                        $container_align =  @$item_meta[$this->menu_key.'links_align'][0];
                        $container_style = 'style="width:'.$container_width.';';
                        $top_link_style  = ' position:relative; ';

                        if( $container_align == 'left' ){
                            $container_style .= ' left:0px;" ';
                        }
                        else if( $container_align == 'right' ){
                            $container_style .= ' right:0px;" ';
                        }
                        else{
                            $container_style .= ' left: 50%; margin-left: -'.( ( (int) $container_width ) / 2 ).'px; "';
                        }
                    }

                    $arrow = '';
                    $has_arrow = '';
                    if( in_array( 'menu-item-has-children' , $item->classes ) )
                    {
                        $has_arrow = ' suppa_top_links_has_arrow ';
                        $arrow = '<span class="era_suppa_arrow_box ctf_suppa_fa_box_top_arrow"><span aria-hidden="true" class="suppa-caret-down"></span></span>';

                    }

                    $item_output = '<div style="'.$this_item_position_css.$top_link_style.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_links suppa_menu_'.$this->top_level_counter.'" ><a class="'.$class_names.' '.$has_arrow.'" '.$attributes.' >'.$link_html.$arrow.'</a>';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                    $output .= '<div class="suppa_submenu suppa_submenu_'.$this->top_level_counter.' suppa_submenu_columns_wrap" '.$container_style.' >';
                }


                // LinksTwo Second Links Style
                else if( 'links_style_two' == $this->menu_type ){

                    // Container Width & Align
                    $submenuWidth               =  @$item_meta['_menu-item-suppa-linksTwoSubmenuWidth'][0];
                    $submenuAlign               =  @$item_meta['_menu-item-suppa-linksTwoSubmenuAlign'][0];
                    $categoriesContainerWidth   =  @$item_meta['_menu-item-suppa-linksTwo_categoriesWidth'][0];

                    $container_style = 'style="width:'.$submenuWidth.';';
                    $categoriesContainerStyle = 'style="width:'.$categoriesContainerWidth.'";';
                    $top_link_style  = ' position:relative; ';

                    if( $submenuAlign == 'left' )
                        $container_style .= ' left:0px;" ';
                    else if( $submenuAlign == 'right' )
                        $container_style .= ' right:0px;" ';
                    else
                        $container_style .= ' left: 50%; margin-left: -'.( ( (int) $submenuWidth ) / 2 ).'px; "';

                    // Arrow
                    $arrow = '';
                    $has_arrow = '';
                    if( in_array( 'menu-item-has-children' , $item->classes ) )
                    {
                        $has_arrow = ' suppa_top_links_has_arrow ';
                        $arrow = '<span class="era_suppa_arrow_box ctf_suppa_fa_box_top_arrow"><span aria-hidden="true" class="suppa-caret-down"></span></span>';
                    }

                    // Wrap
                    $item_output = '<div style="'.$this_item_position_css.$top_link_style.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_linksTwo suppa_menu_'.$this->top_level_counter.'" >
                        <a class="'.$class_names.' '.$has_arrow.' suppa_top_level_link" '.$attributes.' >'.$link_html.$arrow.'</a>';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                    $output .= '<div class="suppa_submenu" '.$container_style.' >
                                    <div class="suppa_linksTwo_categoriesContainer" '.$categoriesContainerStyle.' >';
                }


                // Posts
                else if( 'posts' == $this->menu_type ){

                    $output .= '<div style="'.$this_item_position_css.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_posts suppa_menu_'.$this->top_level_counter.'">';

                    wp_reset_postdata();

                    // The Query : Load All Posts
                    $post_category =  @$item_meta[$this->menu_key.'posts_category'][0];
                    if( !preg_match('/^[0-9]+$/', $post_category) )
                    {
                        $args = array(
                            'suppress_filters'  => FALSE,
                            'post_type'         => $post_category,
                            'posts_per_page'    => @$item_meta[$this->menu_key.'posts_number'][0]
                        );
                    }
                    else
                    {
                        $args = array(
                            'suppress_filters'  => FALSE,
                            'post_type'         => 'any',
                            'posts_per_page'    =>  @$item_meta[$this->menu_key.'posts_number'][0],
                            'tax_query'         => array(
                                array(
                                    'taxonomy'  =>  @$item_meta[$this->menu_key.'posts_taxonomy'][0],
                                    'field'     => 'id',
                                    'terms'     =>  intval( $item_meta[$this->menu_key.'posts_category'][0] )
                                )
                            )
                        );
                    }

                    $the_query = new WP_Query( $args );

                    $posts_wrap = '';
                    $posts_view_all = '';
                    $has_arrow = ' suppa_top_links_has_arrow ';
                    $upload_dir = wp_upload_dir();

                    // The Loop
                    if( $the_query->have_posts() ) :

                    $output .= '<a class="'.$class_names.' '.$has_arrow.'" '.$attributes.' >'.$link_html.'<span class="era_suppa_arrow_box ctf_suppa_fa_box_top_arrow"><span aria-hidden="true" class="suppa-caret-down"></span></span></a>';
                    $output .= '<div class="suppa_submenu suppa_submenu_posts" >';

                    while ( $the_query->have_posts() ) :
                        $the_query->the_post();

                        $id     = get_the_ID();
                        //$thumb    = wp_get_attachment_thumb_url( get_post_thumbnail_id( $id ) );
                        $imgurl = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
                        $retina = $imgurl;

                        $resized_width  = $this->thumb_sizes['recent'][0];
                        $resized_height = $this->thumb_sizes['recent'][1];

                        // If Resize Enable
                        if( suppa_walkers::$project_settings['image_resize'] )
                        {
                            $resized_img = preg_replace("/\.[a-zA-z]{1,4}$/", "", $imgurl);
                            $resized_ext = preg_match("/\.[a-zA-z]{1,4}$/", $imgurl, $matches);

                            $path = explode('uploads',$resized_img);

                            $resized_path = "";
                            $retina_path = "";

                            if( isset($matches[0]) )
                            {
                                $tmp_resized = $resized_img;

                                $resized_img = $tmp_resized.'-'.$resized_width.'x'.$resized_height.$matches[0];
                                $retina_img  = $tmp_resized.'-'.$resized_width.'x'.$resized_height.'@2x'.$matches[0];

                                $resized_path= $upload_dir['basedir'].$path[1].'-'.$resized_width.'x'.$resized_height.$matches[0];
                                $retina_path = $upload_dir['basedir'].$path[1].'-'.$resized_width.'x'.$resized_height.'@2x'.$matches[0];
                            }

                            // Detect if image exists
                            if( file_exists($resized_path) )
                            {
                                $imgurl = $resized_img;
                            }

                            // Detect if retina image exists
                            if( file_exists($retina_path) )
                            {
                                $retina = $retina_img;
                            }

                        }
                        $post_title     = get_the_title();
                        $post_link      = get_permalink();
                        $post_date      = '<span class="suppa_post_link_date">'.get_the_date().'</span>';
                        $post_comment_n = '<span class="suppa_post_link_comments">'.get_comments_number().'</span>';

                        $posts_wrap .= '<div class="suppa_post" >';
                        $posts_wrap .= '<a href="'.$post_link.'" title="'.$post_title.'" >';
                        $posts_wrap .= '<img style="width:'.$resized_width.'px;height:'.$resized_height.'px;" class="suppa_lazy_load '. $this->thumb_sizes['hover'] .'" data-original="'.$imgurl.'" data-retina="'.$retina.'" alt="'.$post_title.'" />';
                        $posts_wrap .= '<div class="suppa_post_link_container" ><span class="suppa_post_link_title">'.$post_title.'</span></div>';
                        $posts_wrap .= '</a><div class="suppa_clearfix"></div></div><!--suppa_post-->';

                    endwhile;

                    /* Restore original Post Data
                     * NB: Because we are using new WP_Query we aren't stomping on the
                     * original $wp_query and it does not need to be reset.
                    */
                    wp_reset_postdata();

                    $posts_view_all = '<a href="'.$item->url.'" class="suppa_latest_posts_view_all">'. __('View All...','suppa_menu') .'</a>';
                    $output .= $posts_wrap.$posts_view_all.'</div>';

                    else:

                    $output .= '<a class="'.$class_names.'" '.$attributes.' >'.$link_html.'</a>';

                    endif;

                    $output .= '</div>';
                }

                // mega_posts
                if( 'mega_posts' == $this->menu_type ){

                    $arrow = '';
                    $has_arrow = '';
                    if( in_array( 'menu-item-has-children' , $item->classes ) )
                    {
                        $has_arrow = ' suppa_top_links_has_arrow ';
                        $arrow = '<span class="era_suppa_arrow_box ctf_suppa_fa_box_top_arrow"><span aria-hidden="true" class="suppa-caret-down"></span></span>';
                    }

                    $this->suppa_item_id = $item->ID;
                    $this->mega_posts_items = array();

                    $item_output = '<div style="'.$this_item_position_css.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_mega_posts suppa_menu_'.$this->top_level_counter.'" ><a class="'.$class_names.' '.$has_arrow.'" '.$attributes.' >'.$link_html.$arrow.'</a>';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                    $output .= '<div class="suppa_submenu suppa_submenu_mega_posts" >';
                }

                // HTML & Shortcodes
                else if( 'html' == $this->menu_type ){

                    // options
                    $links_fullwidth =  @$item_meta[$this->menu_key.'html_fullwidth'][0];
                    $container_style = 'style="width:100%; left:0px;"';
                    $top_link_style = '';

                    if( $links_fullwidth == "" )
                    {
                        $container_width =  @$item_meta[$this->menu_key.'html_width'][0];
                        $container_align =  @$item_meta[$this->menu_key.'html_align'][0];
                        $container_style = ' style="width:'.$container_width.'; ';
                        $top_link_style  = ' position:relative; ';

                        if( $container_align == 'left' )
                        {
                            $container_style .= ' left:0px;" ';
                        }
                        else if( $container_align == 'right' )
                        {
                            $container_style .= ' right:0px;" ';
                        }
                        else
                        {
                            $container_style .= ' left: 50%; margin-left: -'.( ( (int) $container_width ) / 2 ).'px; "';
                        }
                    }

                    $content_and_shortcodes =  @$item_meta[$this->menu_key.'html_content'][0];
                    $content_and_shortcodes = do_shortcode($content_and_shortcodes);

                    $output .= '<div style="'.$this_item_position_css.$top_link_style.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_html suppa_menu_'.$this->top_level_counter.'" >';

                    $has_arrow = ' suppa_top_links_has_arrow ';
                    if( $content_and_shortcodes != '' )
                    {
                        $output .= '<a class="'.$class_names.' '.$has_arrow.'" '.$attributes.' >'.$link_html.'<span class="era_suppa_arrow_box ctf_suppa_fa_box_top_arrow"><span aria-hidden="true" class="suppa-caret-down"></span></span></a>';
                        $output .= '<div class="suppa_submenu suppa_submenu_html" '.$container_style.' >'.$content_and_shortcodes.'</div>';
                    }
                    else
                    {
                        $output .= '<a class="'.$class_names.'" '.$attributes.' >'.$link_html.'</a>';
                    }

                    $output .= '</div>';
                }


                // Search Form
                else if( 'search' == $this->menu_type ){

                    $search_text =  @$item_meta[$this->menu_key.'search_text'][0];

                    // Normal
                    if( $this->skin_options['settings_modern_search'] == 'normal' ){
                        $output .= '<div style="'.$this_item_position_css.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_search suppa_search_normal suppa_menu_'.$this->top_level_counter.'">';
                        $output .= '    <form action="'.get_bloginfo('url').'" method="get" >';
                        $output .= '        <input type="text" name="s" class="suppa_search_input" value="" placeholder="'.$search_text.'" >';
                        $output .= '        <button class="suppa_search_icon" type="submit">
                                                <span aria-hidden="true" class="suppa-search"></span>
                                            </button>
                                        </form>';
                        $output .= '</div>';
                    }

                    // Boxed
                    else if( $this->skin_options['settings_modern_search'] == 'boxed' ){
                        $output .= '<div style="'.$this_item_position_css.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_search suppa_search_boxed suppa_menu_'.$this->top_level_counter.'" >';
                        $output .= '<span aria-hidden="true" class="suppa_top_level_link suppa_search_icon suppa-search"></span>';
                        $output .= '<div class="suppa_submenu" style="'.@$item_meta[$this->menu_key.'link_position'][0].':0px !important;" >';
                        $output .= '    <form action="'.get_bloginfo('url').'" method="get" >';
                        $output .= '        <input type="text" name="s" class="suppa_search_input" value="" placeholder="'.$search_text.'" >';
                        $output .= '    </form>';
                        $output .= '</div>';
                        $output .= '</div>';
                    }

                    // Modern
                    else if( $this->skin_options['settings_modern_search'] == 'modern' ){
                        $output .= '<div style="'.$this_item_position_css.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_search suppa_search_modern suppa_menu_'.$this->top_level_counter.'" >';
                        $output .= '<span aria-hidden="true" class="suppa_top_level_link suppa_search_icon suppa-search"></span>';
                        $output .= '<div class="suppa_submenu_modern_search" >';
                        $output .= '    <form action="'.get_bloginfo('url').'" method="get" >';
                        $output .= '        <input type="text" name="s" class="suppa_search_input" value="" placeholder="'.$search_text.'" >';
                        $output .= '        <span aria-hidden="true" class="suppa_search_modern_close suppa-remove"></span>';
                        $output .= '    </form>';
                        $output .= '</div>';
                        $output .= '</div>';
                    }

                }

                // Social
                else if( 'social' == $this->menu_type ){

                    // Item Icon
                    $link_html = '';
                    if( $icon_type == "upload" )
                    {
                        $check_retina_icon = ( $icon_hover != "" ) ? $icon_hover : $icon;
                        $link_html = '<img class="suppa_upload_img suppa_UP_icon_only" src="'.$icon.'" alt="'.$link_title.'" data-icon="'.$icon.'" data-retina="'.$check_retina_icon.'" >';
                    }
                    else if( $icon_type == "fontawesome" )
                    {
                        $link_html = '<span class="ctf_suppa_fa_box suppa_FA_icon_only"><span aria-hidden="true" class="'.$FA_icon.'" ></span></span>';
                    }

                    $output .= '<div style="'.$this_item_position_css.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_social suppa_menu_'.$this->top_level_counter.'">';
                    $output .= '<a class="'.$class_names.'" '.$attributes.' >'.$link_html.'</a>';
                    $output .= '</div>';
                }


                // WooCommerce
                else if( 'woocommerce_cart' == $this->menu_type ){

                    global $woocommerce;

                    $output .= '<div style="'.$this_item_position_css.$display_item.'" class="'.$item->classes[0].' suppa_menu suppa_menu_woocommerce_cart suppa_menu_'.$this->top_level_counter.'">';
                    $output .= '<a class="'.$class_names.' '.$this_item_position_class.' cart-contents" href="'.$woocommerce->cart->get_cart_url().'" title="'.__('View your shopping cart', 'woothemes').'" ><span class="ctf_suppa_fa_box suppa_FA_icon"><span aria-hidden="true" class="suppa-shopping-cart"></span></span>';
                    $output .= '<span class="suppa_item_title">' . _n('item', 'items', $woocommerce->cart->cart_contents_count , 'suppa_menu') . ' ' . $woocommerce->cart->cart_contents_count . '</span></a>';
                    $output .= '</div>';
                }


            }


            // Dropdown
            if( 'dropdown' == $this->menu_type )
            {
                if( $depth == 1 ){

                    $this->dropdown_first_level_conuter += 1;
                    $item_output = '<div class="suppa_dropdown_item_container '.$has_children.'"><a class="'.$class_names.'" '.$attributes.' >'.$link_html.'<span class="suppa_ar_arrow_right_'.$this->dropdown_first_level_conuter.'_'.$depth.'"></span></a> ';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

                }
                else if( $depth == 2 )
                {
                    $this->dropdown_second_level_conuter += 1;
                    $item_output = '<div class="suppa_dropdown_item_container '.$has_children.'"><a class="'.$class_names.'" '.$attributes.' >'.$link_html.'<span class="suppa_ar_arrow_right_'.$this->dropdown_second_level_conuter.'_'.$depth.'"></span></a> ';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }
                else if( $depth == 3 )
                {
                    $item_output = '<div class="suppa_dropdown_item_container '.$has_children.'"><a class="'.$class_names.'" '.$attributes.' >'.$link_html.'</span></a> ';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }
            }

            // Links
            if( 'links' == $this->menu_type )
            {
                if( $depth == 1 )
                {
                    $output .= '<div class="suppa_column" style="width:'.$this->links_column_width.';">';
                    $item_output = '<a class="'.$class_names.' suppa_column_title" '.$attributes.' >'.$link_html.'</a> ';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }
                else if ( $depth >= 2 )
                {
                    $item_output = '<a class="'.$class_names.' suppa_column_link" '.$attributes.' >'.$link_html.'</a> ';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }
            }

            // links_style_two
            if( 'links_style_two' == $this->menu_type ){

                if( $depth === 1 ){

                    $first_element = '';
                    if( $this->megaLinksTwo_first_item )
                    {
                        $this->megaLinksTwo_first_item = false;
                        $first_element = ' suppa_linksTwo_categoriesContainer_current ';
                    }
                    else
                    {
                        $first_element = '';
                    }

                    // Arrow
                    $arrow = '';
                    $has_arrow = '';
                    if( in_array( 'menu-item-has-children' , $item->classes ) )
                    {
                        $has_arrow = ' suppa_top_links_has_arrow ';
                        $arrow = '<span class="era_suppa_arrow_box suppa_megaLinksTwo_mainLinkArrow"><span aria-hidden="true" class="suppa-caret-right"></span></span>';
                    }

                    $this->linksTwo_parentLink_ID = $item->ID;
                    $item_output = '<a data-preventClick="no" class="'.$class_names.$has_arrow.$first_element.'" '.$attributes.' data-targetCat="'.$item->ID.'" >'.$link_html.$arrow.'</a> ';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                    $this->linksTwo_childsContainer .= '<div class="suppa_linksTwo_cat suppa_linksTwo_cat_'.$this->linksTwo_parentLink_ID.'">';
                }
                if( $depth > 1 )
                {
                    $item_output = '<a class="'.$class_names.'" '.$attributes.' >'.$link_html.'</a> ';
                    $this->linksTwo_childsContainer .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }
            }

            // Mega Posts
            if( 'mega_posts' == $this->menu_type ){
                if( $depth === 1 ){
                    array_push( $this->mega_posts_items , $item->ID );
                    $item_output = '<a class="'.$class_names.' suppa_mega_posts_link" '.$attributes.' data-cat="' . $item->ID . '" >'.$link_html.'<span class="era_suppa_arrow_box suppa_mega_posts_arrow"><span aria-hidden="true" class="suppa-caret-right"></span></span></a> ';
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }
            }

        }

        /**
         * @see Walker::end_el()
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item Page data object. Not used.
         * @param int $depth Depth of page. Not Used.
         */
        function end_el(&$output, $object, $depth = 0, $args = array() )
        {
            global $wp_query;


            // fix for woocommerce multilangual issue !
            //$wpml_current_lang =  ICL_LANGUAGE_CODE;
            //if( defined('ICL_SITEPRESS_VERSION') ){

            //    global $sitepress;
//
            //    $default_language = $sitepress->get_default_language();
            //    $sitepress->switch_lang( $default_language );
//
            //}


            // Dropdown
            if( 'dropdown' == $this->menu_type )
            {
                $output .= '</div>';
            }

            // Links
            if( 'links' == $this->menu_type ){
                if( $depth === 0 ){
                    $output .= '</div></div><!--suppa_submenu_columns_wrap-->';
                }
                if( $depth === 1 ){
                    $output .= '</div>';
                }
            }

            // linksTwo
            if( 'links_style_two' == $this->menu_type ){

                if( $depth === 0 )
                {
                    $output .= '</div></div>';
                }
                if( $depth === 1 )
                    $this->linksTwo_childsContainer .= '</div>';
            }

            // mega_posts
            if( 'mega_posts' == $this->menu_type ){

                if( $depth === 0 ){

                    wp_reset_postdata();

                    // Loop for mega posts need to be here
                    $output .= '<div class="suppa_mega_posts_allposts">';
                    foreach ( $this->mega_posts_items as $mega_posts_item ){

                        $output .= '<div class="suppa_mega_posts_allposts_posts" data-cat="' . $mega_posts_item . '">';

                        $mega_posts_number = get_post_meta( $this->suppa_item_id , '_menu-item-suppa-mega_posts_number', true);
                        $cat_id = get_post_meta( $mega_posts_item, $this->menu_key.'mega_posts_category', true);
                        $cat_taxonomy = get_post_meta( $mega_posts_item, $this->menu_key.'mega_posts_taxonomy', true);

                        // The Query : Load All Posts
                        if( !preg_match('/^[0-9]+$/', $cat_id) )
                        {
                            $args = array(
                                'suppress_filters'  => 0,
                                'post_type'         => $cat_id,
                                'posts_per_page'    => $mega_posts_number
                            );

                        }
                        else
                        {
                            $args = array(
                                'suppress_filters'  => 0,
                                'post_type'         => 'any',
                                'posts_per_page'    => $mega_posts_number,
                                'tax_query'         => array(
                                    array(
                                        'taxonomy'  => $cat_taxonomy,
                                        'field'     => 'id',
                                        'terms'     => intval( $cat_id )
                                    )
                                )
                            );

                        }

                        $the_query = new WP_Query( $args );
                        $posts_wrap = '';
                        $upload_dir = wp_upload_dir();

                        // The Loop
                        while ( $the_query->have_posts() ) :
                            $the_query->the_post();

                            $id     = get_the_ID();
                            //$thumb    = wp_get_attachment_thumb_url( get_post_thumbnail_id( $id ) );
                            $imgurl = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
                            $retina = $imgurl;

                            $resized_width  = $this->thumb_sizes['mega'][0];
                            $resized_height = $this->thumb_sizes['mega'][1];

                            // If Resize Enable
                            if( suppa_walkers::$project_settings['image_resize'] )
                            {
                                $resized_img = preg_replace("/\.[a-zA-z]{1,4}$/", "", $imgurl);
                                $resized_ext = preg_match("/\.[a-zA-z]{1,4}$/", $imgurl, $matches);

                                $path = explode('uploads',$resized_img);

                                $resized_path = "";
                                $retina_path = "";

                                if( isset($matches[0]) )
                                {
                                    $tmp_resized = $resized_img;

                                    $resized_img = $tmp_resized.'-'.$resized_width.'x'.$resized_height.$matches[0];
                                    $retina_img  = $tmp_resized.'-'.$resized_width.'x'.$resized_height.'@2x'.$matches[0];

                                    $resized_path= $upload_dir['basedir'].$path[1].'-'.$resized_width.'x'.$resized_height.$matches[0];
                                    $retina_path = $upload_dir['basedir'].$path[1].'-'.$resized_width.'x'.$resized_height.'@2x'.$matches[0];
                                }

                                // Detect if image exists
                                if( file_exists($resized_path) )
                                {
                                    $imgurl = $resized_img;
                                }

                                // Detect if retina image exists
                                if( file_exists($retina_path) )
                                {
                                    $retina = $retina_img;
                                }

                            }
                            $post_title     = get_the_title();
                            $post_link      = get_permalink();

                            $posts_wrap .= '<a href="'.$post_link.'" title="'.$post_title.'" class="suppa_mega_posts_post_article" >';
                            $posts_wrap .= '<img style="width:'.$resized_width.'px;height:'.$resized_height.'px;" class="suppa_lazy_load '. $this->thumb_sizes['hover'] .'" data-original="'.$imgurl.'" data-retina="'.$retina.'" alt="'.$post_title.'" />';
                            $posts_wrap .= '<div class="suppa_post_link_container"><span>'.$post_title.'</span></div>';
                            $posts_wrap .= '</a><!--suppa_mega_posts_post_article-->';

                        endwhile;

                        $output .= $posts_wrap;

                        $output .= '</div><!--suppa_mega_posts_allposts_posts-->';

                    }
                    $output .= '</div> <!--suppa_mega_posts_allposts-->';


                    $output .= '</div></div><!--suppa_menu_mega_posts-->';


                    //// fix for woocommerce multilangual issue !
                    //if( defined('ICL_SITEPRESS_VERSION') ){
                    //    $sitepress->switch_lang( $wpml_current_lang );
                    //}

                    /* Restore original Post Data
                     * NB: Because we are using new WP_Query we aren't stomping on the
                     * original $wp_query and it does not need to be reset.
                    */
                    wp_reset_postdata();

                }

            }

        }// End Func


    }// End Class
}