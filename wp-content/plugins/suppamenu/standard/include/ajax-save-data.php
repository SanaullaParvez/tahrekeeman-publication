<?php

/**
 * Save Items Data
**/
function ajax_suppaSaveItemsData(){

    check_ajax_referer( 'suppa_nonce', 'nonce' );

    foreach ( $_POST['data'] as $option ) {

        $id = explode('[', $option['name'] );
        $name = '_'.$id[0];
        $id = str_replace(']', '', $id[1]);

        update_post_meta( $id, $name, $option['value'] );
    }

    exit();
}
add_action('wp_ajax_suppaSaveItemsData','ajax_suppaSaveItemsData');
