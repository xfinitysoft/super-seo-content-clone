<?php
/**
 * Plugin Name:         Super SEO Content Cloner
 * Plugin URI:          http://www.xfinitysoft.com
 * Description:         Duplicate page or any post type with find and replace functionality. Also work with Elementor 
 * Author:              Xfinity Soft
 * Author URI:          http://www.xfinitysoft.com/
 * Version:             1.0.1
 * Requires at least:   4.4.0
 * Tested up to:        6.2.0
 * Text Domain:         super-seo-content-cloner
 * Domain Path:         /languages/
 * @category            Plugin
 * @author              Xfinity Soft
 * @package             super-seo-content-cloner
 */

// check for script kiddies 
if ( ! defined( 'ABSPATH' ) ) {
	exit;   //Exit if accessed directly.
}

function xsspd_activation_hook(){
	$xsspd_default_options = array(
		'allowed_roles'					=> array('administrator'),
		'enabled_post_types' 			=> array('post', 'page'),
		'new_status'					=> 'draft',
		'redirect_to_duplicated_post'	=> 0
	);
	
	$xsspd_options = get_option('xsspd_options');
	if(empty($xsspd_options)){
		add_option('xsspd_options', $xsspd_default_options);
	}
}
register_activation_hook(__FILE__, 'xsspd_activation_hook');

if(!class_exists('XSSPD')){
	class XSSPD{
		
		//Plugin settins class object
		private $xsspd_settings;
		
		// Plugin Main Class object
		private $xsspd_main;
		
		/* 	iniate plugin functionality */
		/* 	@params  null 				*/
		/* 	return null 				*/
		public function __construct(){
			
			add_filter( 'plugin_action_links', array($this, 'plugin_action_links'), 10, 2 );
			add_action('init', array($this, 'load_translation_files'));
			add_action('init', array($this, 'define_constants'));
			add_action('init', array($this, 'load_plugins_files'));
			add_action('admin_menu', array( $this, 'admin_menu') );
		}
		/* 	Plugin Action Links		 	*/
		/* 	@params  Array, String		*/
		/* 	return array 				*/
		public function plugin_action_links($links, $file){
			if ( $file == plugin_basename( __FILE__ ) ) {
				$settings_link = '<a href="'.get_admin_url().'options-general.php?page=xsspd-settings">'.__('Settings', 'super-seo-content-cloner').'</a>';
				array_unshift( $links, $settings_link );
			}
		
			return $links;
		}
		/* 	Plugin Menu on Admin side 	*/
		/* 	@params  null 				*/
		/* 	return null 				*/
		public function admin_menu(){
			$this->xsspd_settings->admin_menu();
		}
		
		/* 	Load translation Files 		*/
		/* 	@params  null 				*/
		/* 	return null 				*/		
		public function load_translation_files(){
			load_plugin_textdomain('super-seo-content-cloner', false, basename( dirname( __FILE__ ) ) . '/languages');
		}
		
		/* 	Define Necessary Contants 		*/
		/* 	@params  null 					*/
		/* 	return null 					*/		
		public function define_constants(){
			define('XSSPD_ROOT_FILE', __FILE__);
			define('XSSPD_ROOT_PATH', dirname(__FILE__));
			define('XSSPD_ROOT_URL', plugins_url('', __FILE__));
			define('XSSPD_PLUGIN_VERSION', '0.1.0');
			define('XSSPD_PLUGIN_SLUG', basename(dirname(__FILE__)));
			define('XSSPD_PLUGIN_BASE', plugin_basename(__FILE__));
		}
		
		/* 	Load Plugin file 		*/
		/* 	@params  null 				*/
		/* 	return null 				*/		
		public function load_plugins_files(){
			include XSSPD_ROOT_PATH.'/includes/class.xsspd.admin-settings.php';
			include XSSPD_ROOT_PATH.'/includes/class.xsspd-main.php';
			$this->xsspd_settings = new XSSPD_SETTINGS();
			$this->xsspd_main = new XSSPD_MAIN();
		}
		
		/* 	Get Plugin Settings		 		*/
		/* 	@params  null 					*/
		/* 	return Object XSSPD_SETTINGS	*/		
		public function get_xsspd_settings(){
			return $this->xsspd_settings;
		}
		
	}
	
	global $XSSPD;
	$XSSPD = new XSSPD();
}
?>