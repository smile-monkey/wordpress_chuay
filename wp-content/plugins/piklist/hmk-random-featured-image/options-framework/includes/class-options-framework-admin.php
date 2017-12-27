<?php
/**
 * @package   Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2013 WP Theming
 */

class Options_Framework_Admin {

	/**
     * Page hook for the options screen
     *
     * @since 1.7.0
     * @type string
     */
    protected $options_screen = null;
    public $_opt_setting = null;
    /**
     * Hook in the scripts and styles
     *
     * @since 1.7.0
     */
    public function init() {

		// Gets options to load
    	$options = & Options_Framework::_optionsframework_options();

		// Checks if options are available
    	if ( $options ) {

			// Add the options page and menu item.
			add_action( 'admin_menu', array( $this, 'add_options_page' ) );

			// Add the required scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Settings need to be registered after admin_init
			add_action( 'admin_init', array( $this, 'settings_init' ) );

			// Adds options menu to the admin bar
			add_action( 'wp_before_admin_bar_render', array( $this, 'optionsframework_admin_bar' ) );

		} else {
			// Display a notice if options aren't present in the theme
			add_action( 'admin_notices', array( $this, 'options_notice' ) );
			add_action( 'admin_init', array( $this, 'options_notice_ignore' ) );
		}
		// add_action( 'wp_ajax_add_new_image_set', array( &$this, 'add_new_image_set'));
    }

	/**
     * Let's the user know that options aren't available for their theme
     */
    function options_notice() {
		global $pagenow;
        if ( !is_multisite() && ( $pagenow == 'plugins.php' || $pagenow == 'themes.php' ) ) {
			global $current_user ;
			$user_id = $current_user->ID;
			if ( ! get_user_meta($user_id, 'optionsframework_ignore_notice') ) {
				echo '<div class="updated optionsframework_setup_nag"><p>';
				printf( __('Your current theme does not have support for the Options Framework plugin.  <a href="%1$s" target="_blank">Learn More</a> | <a href="%2$s">Hide Notice</a>', 'optionsframework'), 'http://wptheming.com/options-framework-plugin', '?optionsframework_nag_ignore=0');
				echo "</p></div>";
			}
        }
	}

	/**
     * Allows the user to hide the options notice
     */
	function options_notice_ignore() {
		global $current_user;
		$user_id = $current_user->ID;
		if ( isset( $_GET['optionsframework_nag_ignore'] ) && '0' == $_GET['optionsframework_nag_ignore'] ) {
			add_user_meta( $user_id, 'optionsframework_ignore_notice', 'true', true );
		}
	}

	/**
     * Registers the settings
     *
     * @since 1.7.0
     */
    function settings_init() {

    	// Load Options Framework Settings
        $optionsframework_settings = get_option( 'optionsframework' );

		// Registers the settings fields and callback
		register_setting( 'optionsframework', $optionsframework_settings['id'],  array ( $this, 'validate_options' ) );
		$this->_opt_setting = $optionsframework_settings['id'];
		// ob_start();
			// $url = admin_url( 'admin.php?page=options-framework', 'https' );
		 //    $string = '<script type="text/javascript">';
		 //    $string .= 'window.location = "' . $url . '"';
		 //    $string .= '</script>';
		 //    echo $string;
		// ob_flush();		
		// Displays notice after options save
		add_action( 'optionsframework_after_validate', array( $this, 'save_options_notice' ) );

    }

	/*
	 * Define menu options (still limited to appearance section)
	 *
	 * Examples usage:
	 *
	 * add_filter( 'optionsframework_menu', function( $menu ) {
	 *     $menu['page_title'] = 'The Options';
	 *	   $menu['menu_title'] = 'The Options';
	 *     return $menu;
	 * });
	 *
	 * @since 1.7.0
	 *
	 */
	static function menu_settings() {

		$menu = array(
			'page_title' => __( 'Random Featured Image Settings', 'optionsframework'),
			'menu_title' => __('Random Images', 'optionsframework'),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'options-framework'
		);

		return apply_filters( 'optionsframework_menu', $menu );
	}

	/**
     * Add a subpage called "Random Image" to the appearance menu.
     *
     * @since 1.7.0
     */
	function add_options_page() {

		$menu = $this->menu_settings();
		$this->options_screen = add_menu_page( $menu['page_title'], $menu['menu_title'], $menu['capability'], $menu['menu_slug'], array( $this, 'options_page' ) );

	}

	/**
     * Loads the required stylesheets
     *
     * @since 1.7.0
     */
	function enqueue_admin_styles() {
		wp_enqueue_style( 'optionsframework', plugin_dir_url( dirname(__FILE__) ) . 'css/optionsframework.min.css', array(),  Options_Framework::VERSION );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
     * Loads the required javascript
     *
     * @since 1.7.0
     */
	function enqueue_admin_scripts( $hook ) {

		$menu = $this->menu_settings();

		//if ( 'appearance_page_' . $menu['menu_slug'] != $hook )
	       // return;

		// Enqueue custom option panel JS
		wp_enqueue_script( 'options-custom', plugin_dir_url( dirname(__FILE__) ) . 'js/options-custom.min.js', array( 'jquery','wp-color-picker' ), Options_Framework::VERSION );

		// Inline scripts from options-interface.php
		add_action( 'admin_head', array( $this, 'of_admin_head' ) );
	}

	function of_admin_head() {
		// Hook to add custom scripts
		do_action( 'optionsframework_custom_scripts' );
	}

	/**
     * Builds out the options panel.
     *
	 * If we were using the Settings API as it was intended we would use
	 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
	 * we'll call our own custom optionsframework_fields.  See options-interface.php
	 * for specifics on how each individual field is generated.
	 *
	 * Nonces are provided using the settings_fields()
	 *
     * @since 1.7.0
     */
	 function options_page() { ?>

		<div id="optionsframework-wrap" class="wrap">
			<form id="image_set_form" action="options.php" method="post">
				<input type="submit" class="button-primary" id="options_update" name="update" value="<?php esc_attr_e( 'Save Options', 'optionsframework' ); ?>" />
				<?php $menu = $this->menu_settings(); ?>
				<h2><?php echo esc_html( $menu['page_title'] ); ?></h2>

	    		<h2 class="nav-tab-wrapper">
	        		<?php echo Options_Framework_Interface::optionsframework_tabs(); ?>
	   			</h2>

	    		<?php settings_errors( 'options-framework' ); ?>

	   			<div id="optionsframework-metabox" class="metabox-holder">
	   				<div id="image_set_group">
	   					<?php 
	   						$options_setting = get_option('hmk_admin_options');
	   						if ($options_setting && sizeof($options_setting)>0){
	   							foreach ($options_setting as $option_setting) {
	   								echo $this->add_image_set($option_setting);
	   							}
	   						}else {
	   							echo $this->add_image_set();
	   						}
	   					?>
	   				</div>
					<div class="image_set"><input type="submit" class="button-primary" name="add_image_set" id="add_image_set" value="<?php esc_attr_e( 'Add New Image Set', 'addimageset' ); ?>" />
					</div>
				</div>
					<div class="save_set"><input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'optionsframework' ); ?>" />
					</div>					
				<?php do_action( 'optionsframework_after' ); ?>
			</form>
		</div> <!-- / .wrap -->

	<?php
	}

	/**
	 * Validate Options.
	 *
	 * This runs after the submit/reset button has been clicked and
	 * validates the inputs.
	 *
	 * @uses $_POST['reset'] to restore default options
	 */
	function validate_options( $input ) {

		/*
		 * Restore Defaults.
		 *
		 * In the event that the user clicked the "Restore Defaults"
		 * button, the options defined in the theme's options.php
		 * file will be added to the option for the active theme.
		 */

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'options-framework', 'restore_defaults', __( 'Default options restored.', 'optionsframework' ), 'updated fade' );
			return $this->get_default_values($input);
		}
		if ( isset( $_POST['add_image_set'] ) ) {
			$new_image_set = array();
			$options = & Options_Framework::_optionsframework_options();
			foreach ($options as $option) {
				$id = $option['id'];
				if ($option['type'] =='text') {
					$input[$id] = $option['std'];
				} else if($option['type'] == 'upload') {
					$input[$id] = '';
				}
			}
		}
		/*
		 * Update Settings
		 *
		 * This used to check for $_POST['update'], but has been updated
		 * to be compatible with the theme customizer introduced in WordPress 3.4
		 */
		$clean = array();

		// $options_setting = array();
		if (sizeof($input)>0){
			foreach ($input as $key => $value) {
				if (!$value) continue;
				$key_arr = explode('_', $key);
				$index = $key_arr[count($key_arr)-1];
				$options_setting[$index][$key] = $value;
				if ($key_arr[0] == 'defult')
					$type = 'upload';
				else
					$type = 'text';
				// For a value to be submitted to database it must pass through a sanitization filter
				if ( has_filter( 'of_sanitize_' . $type ) ) {
					$clean[$index][$key] = apply_filters( 'of_sanitize_' . $type, $value, $input );
				}				
			}

		}

		// Hook to run after validation
		do_action( 'optionsframework_after_validate', $clean );

		return $options_setting;
	}

	/**
	 * Display message when options have been saved
	 */

	function save_options_notice() {
		add_settings_error( 'options-framework', 'save_options', __( 'Options saved.', 'optionsframework' ), 'updated fade' );
	}

	/**
	 * Get the default values for all the Random Image
	 *
	 * Get an array of all default values as set in
	 * options.php. The 'id','std' and 'type' keys need
	 * to be defined in the configuration array. In the
	 * event that these keys are not present the option
	 * will not be included in this function's output.
	 *
	 * @return array Re-keyed options configuration array.
	 *
	 */

	function get_default_values($input) {
		$output = array();
		return $output;
	}

	/**
	 * Add options menu item to admin bar
	 */

	function optionsframework_admin_bar() {

		$menu = $this->menu_settings();
		global $wp_admin_bar;

		$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => 'of_theme_options',
			'title' => __( 'Random Image', 'optionsframework' ),
			'href' => admin_url( 'themes.php?page=' . $menu['menu_slug'] )
		) );
	}

	/**
	 * Add a "Random Featuered Image Set" when user click "Add New Image Set" button
	 */

	function add_image_set($option_setting=array()) {

		if (sizeof($option_setting)>0){
			$options = $this->optionsframework_options($option_setting);
		}else{
			$options = & Options_Framework::_optionsframework_options();
		}

		$image_set_num = $options[0]['set_num'];
		$cat_ids = $options[0]['cat_ids'];
	
		$contents = '
			<div class="optionsframework postbox" id="image_set_'.$image_set_num.'">
				<div class="options_title">
					<h2>Random Featuered Image Set</h2>
					<h2 class="nav-tab-wrapper"></h2>
				</div>
				<div class="category_list">';
					$args = array(
						'orderby'                  => 'parent',
						'order'                    => 'ASC',
						'hide_empty'               => 0);
				  	$categories = get_categories($args);
				  	if (sizeof($categories)>0){
				  		$contents .= $this->get_category_tree($categories,$cat_ids, $image_set_num);
				  	}
				$contents .= '</div>';
				settings_fields( 'optionsframework' );
				$contents .= Options_Framework_Interface::optionsframework_fields($options);
				$contents .= '<div class="options_title">';
				// $contents .= '<input type="submit" class="reset-button button-secondary" name="reset" value="Restore Defaults"/>';
				$contents .= '<input type="button" class="remove_btn button-secondary" id="'.$image_set_num.'" value="Remove Image Set"/>
					<div class="clear">
					</div>
				</div>
			</div> <!-- / #container -->';
		return $contents;
	}

	// function add_new_image_set() {
	// 	echo $this->add_image_set();
	// 	die();
	// }

	function optionsframework_options($option_setting){
		
		$options = $cat_ids = array();
		$set_num = $hmk_num = '';
		foreach ($option_setting as $set_key => $opts) {
			if (is_array($opts)){
				$set_num = $set_key; 
				foreach ($opts as $key => $value) {
					if ($value == 'on'){
						array_push($cat_ids, $key);
						continue;
					}
					if (strpos($key,'defult_featured_image_') !==false){
						$options[$key] = $value;
					}
				}
				$hmk_num = $option_setting[$set_num]['hmk_number_images_'.$set_num];
				break;
			}
			if (!$set_num) {
				$key_arr = explode('_', $set_key);
				$set_num = $key_arr[count($key_arr)-1];
				$hmk_num = $option_setting['hmk_number_images_'.$set_num];
			}
			if ($opts == 'on') {
				array_push($cat_ids, $set_key);
				continue;
			}
			if (strpos($set_key,'defult_featured_image_') !==false){
				$options[$set_key] = $opts;
			}			
		}

		$options[] = array(
			'name' => __('Number of Images', 'options_framework_theme'),
			'desc' => __('Number of random featured Images.', 'options_framework_theme'),
			'id' => 'hmk_number_images_'.$set_num,
			'set_num'=> $set_num,
			'cat_ids'=> $cat_ids,
			'std' => $hmk_num,
			'class' => 'mini',
			'type' => 'text');

		for ( $i=1 ; $i <=$hmk_num; $i++) {
			$options[] = array(
				'name' => __('Default Featured Image '.$i, 'options_framework_theme'),
				'id' => 'defult_featured_image_'.$i.'_'.$set_num,
				'type' => 'upload');
		}
		return $options;
	}

	function get_category_tree($categories, $cat_ids, $image_set_num, $pid=0) {
  		$contents = "<ul class='category_left'>";
	  	foreach ($categories as $category) {
	  		if ($category->parent != $pid) continue;
	  		$category_id = $category->cat_ID."_".$image_set_num;
	  		$checked = '';
	  		if (is_array($cat_ids))
	  			$checked = in_array($category_id, $cat_ids) ? 'checked' : '';
	  		$contents .= "<li><label class='selectit'>";
	  		$contents .= "<input type='checkbox' name='" .esc_attr( $this->_opt_setting.'['.$category_id.']' )."'".$checked." id='chk_".$category->cat_ID."'/>";
	  		$contents .= $category->cat_name;
	  		$contents .= "</label>";
	  		$contents .= $this->get_category_tree($categories, $cat_ids, $image_set_num, $category->cat_ID);
	  		$contents .= "</li>";
	  	}
  		$contents .= "</ul>";
  		return $contents;
	}
}