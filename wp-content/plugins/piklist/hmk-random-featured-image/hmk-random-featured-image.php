<?php
/*
Plugin Name: Random Featured Images
Plugin URI: https://wordpress.org/
Description: Tired of no post thumbnails ? or Single default thumbnail for all posts having no featured image sets ? Here is an awesome plugin for Default Random Featured Images.
Plugin URI: https://wordpress.org/
Author: David
Version: 1.2
Author URI: https://wordpress.org/
License: 
Text Domain: hmk-random-featured-image
Domain Path: /languages/
*/
if ( ! defined( 'ABSPATH' ) ) exit;
define('hmk_url', plugin_dir_url(__FILE__));
define('hmk_path', plugin_dir_path(__FILE__));

if (is_admin() && ! function_exists( 'optionsframework_init' ) ) {
    require_once hmk_path . 'options-framework/options-framework.php';
}

function hmk_get_random_featured_img ($cat_id) {

    $hmk_random_featured_image = '';
    $hmk_arr = get_option('hmk_admin_options');
    if (sizeof($hmk_arr)>0){
        $image_list = array();
        global $wpdb;
         
        foreach ($hmk_arr as $set_num => $hmr_options) {
            $cat_num = $cat_id.'_'.$set_num;
            $hmr_opts = is_array($hmr_options[$set_num]) ? $hmr_options[$set_num] : $hmr_options;
            if (array_key_exists($cat_num, $hmr_opts)){
                $num = 1;
                $id = 'hmk_number_images_'.$set_num;
                foreach ($hmr_opts as $key => $hmr_option) {
                    if (strpos($key,'defult_featured_image_') !==false){
                        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$hmr_option' and post_type='attachment'";
                        $image_post_id = $wpdb->get_var($query);                        
                        if ($image_post_id)
                            array_push($image_list, $hmr_option);
                        if ($num >= $hmr_opts[$id])
                            break;
                        $num++;
                    }
                }
                break;
            }
        }
    }
    $hmk_num = count($image_list);
    $hmk_rand = wp_rand(0, $hmk_num)-1;
    $hmk_rand = $hmk_rand >= 0 ? $hmk_rand : 0;
    $hmk_random_featured_image =  $image_list[$hmk_rand];
    return $hmk_random_featured_image;
}

function hmk_get_random_attachment_id($category_id) {
 
	global $wpdb;
	$random_image_url = hmk_get_random_featured_img($category_id);

	$random_attachment_id = false;
 
	if ( '' == $random_image_url )
		return;
 
	$upload_dir_paths = wp_upload_dir();
 
	if ( false !== strpos( $random_image_url, $upload_dir_paths['baseurl'] ) ) {
 
		$random_image_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $random_image_url );
 
		$random_image_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $random_image_url );
 
		$random_attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $random_image_url ) );
 
	} 
	return $random_attachment_id;
} 

add_action( 'publish_post', 'hmk_set_random_thumbnail' ); 
add_action( 'edit_post', 'hmk_set_random_thumbnail' ); 
add_action( 'transition_post_status', 'hmk_set_random_thumbnail' );

function hmk_set_random_thumbnail( $post_id, $image_check=true) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }

    $result = null;

    if ( !wp_is_post_revision( $post_id ) ) {
        $post_thumbnail = get_post_meta( $post_id, $key = '_thumbnail_id', $single = true );

        if ( !empty( $post_thumbnail ) && $image_check) return $result;

        $post_category = get_the_category($post_id);
        if (sizeof($post_category)>0){
            foreach ($post_category as $post_cat) {
                $hmk_random_attachment_id = hmk_get_random_attachment_id($post_cat->cat_ID);

                if ($hmk_random_attachment_id) break;
            }
            if ($hmk_random_attachment_id)
                $result = update_post_meta( $post_id, $meta_key = '_thumbnail_id', $meta_value = $hmk_random_attachment_id );  
        }
    }  
    return $result;
}
/**
 * Adds a new item into the Bulk Actions dropdown.
 */
function register_hmk_bulk_actions( $bulk_actions ) {
    $bulk_actions['featured_image'] = __( 'Reapply New Featured Image', 'hmk_random_featured_image' );
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-post', 'register_hmk_bulk_actions' );

/**
 * Handles the bulk action.
 */
function hmk_bulk_action_handler( $redirect_to, $action, $post_ids ) {
    if ( $action !== 'featured_image' ) {
        return $redirect_to;
    }
    $post_num = 0;
    foreach ( $post_ids as $post_id ) {
        $update_post = hmk_set_random_thumbnail($post_id, false);
        if ($update_post)
            $post_num++;
    }
    $redirect_to = add_query_arg( 'bulk_featured_image', $post_num, $redirect_to );
    return $redirect_to;
}
add_filter( 'handle_bulk_actions-edit-post', 'hmk_bulk_action_handler', 10, 3 );

/**
 * Shows a notice in the admin once the bulk action is completed.
 */
function hmk_bulk_action_admin_notice() {
    if ( ! empty( $_REQUEST['bulk_featured_image'] ) ) {
        $image_count = intval( $_REQUEST['bulk_featured_image'] );
        printf(
            '<div id="message" class="bulk_message updated fade">' .
            _n( '%s posts are reapplied new featured images.', '%s posts are reapplied new featured images.', $image_count, 'hmk_random_featured_image' )
            . '</div>',
            $image_count
        );
    }
}
add_action( 'admin_notices', 'hmk_bulk_action_admin_notice' );
