<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */
function optionsframework_option_name() {

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = 'hmk_admin_options';
	update_option( 'optionsframework', $optionsframework_settings );
}

function optionsframework_options() {
	$options = array();
	$set_num = time();
	$options[] = array(
		'name' => __('Number of Images', 'options_framework_theme'),
		'desc' => __('Number of random featured Images.', 'options_framework_theme'),
		'id' => 'hmk_number_images_'.$set_num,
		'set_num'=> $set_num,
		'cat_ids'=> null,
		'std' => 3,
		'class' => 'mini',
		'type' => 'text');

	$hmk_num = 3;//default:3
	for ( $i=1 ; $i <=$hmk_num; $i++) {
		$options[] = array(
			'name' => __('Default Featured Image '.$i, 'options_framework_theme'),
			'id' => 'defult_featured_image_'.$i.'_'.$set_num,
			'type' => 'upload');
	}
	return $options;
}