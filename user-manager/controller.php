<?php
/**
 * Controller Class
 *
 */
define('JM_URL', plugins_url('', __FILE__));
define('JM_DIR', plugin_dir_path(__FILE__));


class Controller{
	
	private $model;
	private $rows_per_page;
	private $csv;
	private $slug;
	private $url;
	
	/**
     * Constructor - sets table variables and slugs
     *
     */
	public function __construct() {
		$role = get_role( 'contributor' );
		$role->add_cap( 'manage_options' ); // capability

		// read settings
		$settings = parse_ini_file(FILE_INI);
		// API settings
		$this->csv['api_key'] = $settings['api_key'];
		$this->csv['api_url']  = $settings['api_url'];
		$this->csv['api_enable']  = $settings['api_enable'];
		
		// database
		$this->model = new Model($settings['table_name']);

		// slugs & menu
		$this->slug['setting']     = $settings['base_slug'] . '_setting';
		$this->slug['update_settings']     = $settings['base_slug'] . '_update_settings';
		
	//	add_action('init', array($this, 'export_csv'));
		add_action('admin_menu', array($this, 'add_menu'));
		add_action('admin_init', array($this, 'load_script_css'));
		
		$this->url['setting']     = admin_url('admin.php?page=' . $this->slug['setting']);
		$this->url['update_settings']      = admin_url('admin.php?page=' . $this->slug['update_settings']);
		
		add_action( 'user_register', $this->runAPICreateUsers, 10, 1 );
		add_action( 'profile_update', $this->runAPIUpdateUsers, 10, 2 );
	}
	
	
	
	function load_script_css() {
		//echo  plugin_dir_url(__FILE__);
            //wp_enqueue_script('jquery');
           // wp_enqueue_script('jquery-ui-sortable');
           // wp_enqueue_script('sorderjs', plugin_dir_url(__FILE__) . '/style/jmorder.js', array('jquery'), null, true);
			//wp_enqueue_style('sorder', plugin_dir_url(__FILE__) . '/style/jmorder.css', array(), null);
			
          //  wp_enqueue_script('datetimepickerjs', plugin_dir_url(__FILE__) . '/style/datetimepicker.js', array('jquery'), null, true);
		//	wp_enqueue_style('datetimepicker', plugin_dir_url(__FILE__) . '/style/datetimepicker.css', array(), null);
			
   }
	

	/**
     * Adds menus to left-hand sidebar
     *
     */

	public function add_menu() {
	//echo current_user_can( 'manage_options' );

	add_submenu_page(
        $this->slug['setting'],                 // parent slug
        'API SETTING',             // page title
        'API SETTING',             // sub-menu title
        'edit_posts',                 // capability
        $this->url['setting'] // your menu menu slug
    );
	
	add_menu_page('User Managment', 'User Managment', 'manage_options', $this->slug['setting'], array($this, 'settings'));
	add_submenu_page(null, 'Update', 'Update', 'manage_options', $this->slug['update_settings'], array($this, 'update_settings'));
	
	}

	/**
     * Configuration page
     *
     */
	public function settings($message = "",$status = "") {
		// read settings
		$settings = parse_ini_file(FILE_INI);
		// API settings
		$this->csv['api_key'] = $settings['api_key'];
		$this->csv['api_url']  = $settings['api_url'];
		$this->csv['api_enable']  = $settings['api_enable'];
		include(FILE_VIEW_SETTINGS);
	}
	
	public function update_settings()
	{
		// read settings file
		$settings = parse_ini_file(FILE_INI);
		$status = "";
		$message = "";
		
		// update ini file
		if (isset($_POST['apply'])) 
		{
			
			// check  validity
			if ( ! isset( $_POST['update'] ) 
			|| ! wp_verify_nonce( $_POST['update'], 'settings' ) 
		) {
		   print 'Sorry, your nonce did not verify.';
		   exit;
		} else {
				// gather new setting params
				$settings['api_url'] = $_POST['api_url'];
				$settings['api_key'] = $_POST['api_key'];
				$settings['api_enable'] = $_POST['api_enable']=="" ? 0 : 1;
				
				// update ini file
				$fp = fopen(FILE_INI, 'w');
				foreach ($settings as $k => $v){
					if (false == fputs($fp, "$k = $v" . NEW_LINE)) {
						$status = "error";
					}
				}
				fclose($fp);
				
				$status = "success";
				$message = "Settings successfully changed";
			}
		// restore ini file with default settings
		} else if (isset($_POST['restore'])) {
			
			if (file_exists(FILE_INI_DEFAULT) ) {
				copy(FILE_INI_DEFAULT, FILE_INI);
				$status = "success";
				$message = "Default settings successfully restored";

			} else {
				$status = "error";
				$message = "Error: default config file not found";
			}
		}
		$this->settings($message,$status);
	}
	
	/**
     * Configuration page
     *
     */
	public function runAPICreateUsers($user_id)
	{
		
		
		$settings = parse_ini_file(FILE_INI);
		// API settings
		$api_key = $settings['api_key'];
		$api_url  = $settings['api_url'];
		$api_enable  = $settings['api_enable'];
		
		$user = get_user_by( 'id', $user_id );
		
		$userData =array(
			'ID' => $user_id,
			"user_login" => $user->user_login,
			"user_pass" => $user->user_pass,
			"user_nicename" => $user->user_nicename,
			'user_email' => $user->user_email,
			'user_url' => $user->user_url,
			'user_registered' => $user->user_registered,
			'user_activation_key' => $user->user_activation_key,
			'user_status' => $user->user_status,
			'display_name' => $user->display_name
			);
	  
		//var_dump($userData);
		
		if($api_enable)
		{
			$response = wp_remote_post( $api_url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array('content-type' => 'application/json','token' => $api_key),
			'body' => $userData,
			'cookies' => array()
			)
			);
	   
			if ( is_wp_error( $response ) ) {
			   $error_message = $response->get_error_message();
			   echo "Something went wrong: $error_message";
			} else {
			   echo 'Response:<pre>';
			   print_r( $response );
			   echo '</pre>';
			}
			
			}
	}
	
	public function runAPIUpdateUsers($user_id, $old_user_data)
	{
		$old_user_email = $old_user_data->data->user_email;
		$user = get_userdata( $user_id );
		$new_user_email = $user->user_email;
		 
		if ( $new_user_email !== $old_user_email ) {
				// Do something if old and new email aren't the same
			}else{
				
			$userData =array(
			'ID' => $user_id,
			"user_login" => $user->user_login,
			"user_pass" => $user->user_pass,
			"user_nicename" => $user->user_nicename,
			'user_email' => $user->user_email,
			'user_url' => $user->user_url,
			'user_registered' => $user->user_registered,
			'user_activation_key' => $user->user_activation_key,
			'user_status' => $user->user_status,
			'display_name' => $user->display_name
			);
	  
		//var_dump($userData);
		
		if($api_enable)
		{
			$response = wp_remote_post( $api_url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array('content-type' => 'application/json','token' => $api_key),
			'body' => $userData,
			'cookies' => array()
			)
			);
	   
			if ( is_wp_error( $response ) ) {
			   $error_message = $response->get_error_message();
			   echo "Something went wrong: $error_message";
			} else {
			   echo 'Response:<pre>';
			   print_r( $response );
			   echo '</pre>';
			}
			
			}
			
			
			}
	}
	
}
?>