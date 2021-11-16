<?php
/*
Plugin Name: User Manager
Description: Wordpress plugin to connect to an external API and send the user details (whenever a new user is created or an existing user is updated).
Version: 1.0
Author: Manjeet Singh
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


define('FILE_INI', dirname(__FILE__) . '/config/settings.ini');
define('FILE_INI_DEFAULT', dirname(__FILE__) . '/config/settings.ini.default');
define('FILE_CSS',  plugin_dir_url(__FILE__) . "/style/style-admin.css");
define('FILE_VIEW_SETTINGS',  dirname(__FILE__) . "/view/settings.tpl");
define('DELMITER', ',');
define('NEW_LINE', "\r\n");

register_activation_hook(__FILE__, 'install');
register_deactivation_hook(__FILE__, 'uninstall');
register_uninstall_hook(__FILE__, 'uninstall');		





function install() {
   	global $wpdb;
  	global $your_db_name;
	$charset_collate = $wpdb->get_charset_collate();
	$your_db_name = "";
	// create the ECPT metabox database table
	/*
	if($wpdb->get_var("show tables like '$your_db_name'") != $your_db_name) 
		{
			$sql = "";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}	
		*/
	}
	
function uninstall() {
	global $wpdb;
     $table_name = '';
	 /*
	 $sql = "DROP TABLE IF EXISTS $table_name";
	 $wpdb->query($sql);
	 delete_option("my_plugin_db_version");
	 */
	}
	
/*
 function listing_webpage($atts = array() )
	{
		ob_start();
		global $wpdb;
		$soupsType="";
		$HTML = '';
		echo $HTML;
		$myvariable = ob_get_clean();
    	return $myvariable;
	}
add_shortcode( 'WD_LISTINGS', 'listing_webpage' );
*/	

	
require_once("controller.php");
require_once("model.php");
$control = new Controller();
?>