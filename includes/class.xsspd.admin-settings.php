<?php

// check for script kiddies 
if ( ! defined( 'ABSPATH' ) ) {
	exit;   //Exit if accessed directly.
}
if(!class_exists('XSSPD_SETTINGS')){
	class XSSPD_SETTINGS{
		
		/*	Class Properties			 			*/
		/*	All the configuration options provided	*/
		/*	to access globaly throughout the plugin */
		public $enabled_post_types;
		public $new_post_status;
		public $redirect_to_duplicated_post;
		public $allowed_roles;
		public $duplicate_link_text;
			
		/* Construstor */
		public function __construct(){
			$this->get_settings();
		}

		/* 	Admin side Menu 			*/
		/* 	@params  null 				*/
		/* 	return null 				*/
		public function admin_menu(){ 
			add_menu_page( __('Super SEO Content Cloner', 'super-seo-content-cloner'), __('Super SEO Content Cloner', 'super-seo-content-cloner'), 'manage_options', 'xsspd-settings', array($this, 'xsspd_menu_callback'), 'dashicons-admin-page' );
			add_submenu_page('xsspd-settings', __('Support', 'super-seo-content-cloner'),__('Support', 'super-seo-content-cloner'),'manage_options','xsspd-support',array($this,'xsspd_support'));
		}
		
		/* 	Admin Menu Callback 		*/
		/* 	@params  null 				*/
		/* 	return null 				*/
		public function xsspd_menu_callback(){
			if( isset($_REQUEST['xsspd_save_nonce']) and wp_verify_nonce($_REQUEST['xsspd_save_nonce'], 'save') and isset($_POST['save_settings']) ){
				$this->save_settings();
			}
			$this->get_settings();
			include XSSPD_ROOT_PATH.'/includes/views/xsspd-settings.php';
		}
		public function xsspd_support(){
			include XSSPD_ROOT_PATH.'/includes/views/xsspd-support.php';
		}		
		/* 	Save Settings 				*/
		/* 	@params  null 				*/
		/* 	return null 				*/
		private function save_settings(){			
			$new_settings = array();
			if(isset($_POST['allowed_roles'])){
				$new_settings['allowed_roles'] = $this->sanitize_array($_POST['allowed_roles']);
			}else{
				$new_settings['allowed_roles'] = array();
			}
			
			if(isset($_POST['enabled_post_types'])){
				$new_settings['enabled_post_types'] = $this->sanitize_array($_POST['enabled_post_types']);
			}else{
				$new_settings['enabled_post_types'] = array();
			}
			
			$new_settings['new_status'] = sanitize_text_field($_POST['new_status']);
			$new_settings['redirect_to_duplicated_post'] = sanitize_text_field($_POST['redirect_to_duplicated_post']);
			$new_settings['duplicate_link_text'] = sanitize_text_field($_POST['duplicate_link_text']);
			update_option('xsspd_options', $new_settings);
		}
		
		/* 	Get Settings 				*/
		/* 	@params  null 				*/
		/* 	return null 				*/
		private function get_settings(){
			$xsspd_options = get_option('xsspd_options');
			$this->enabled_post_types = isset($xsspd_options['enabled_post_types']) ? $xsspd_options['enabled_post_types'] : array('page', 'post');
			$this->new_post_status = isset($xsspd_options['new_status']) ? $xsspd_options['new_status'] : 'draft';
			$this->redirect_to_duplicated_post = isset($xsspd_options['redirect_to_duplicated_post']) ? $xsspd_options['redirect_to_duplicated_post'] : 'admin';
			$this->allowed_roles = isset($xsspd_options['allowed_roles']) ? $xsspd_options['allowed_roles'] : array('administrator');
			$this->duplicate_link_text = isset($xsspd_options['duplicate_link_text']) ? $xsspd_options['duplicate_link_text'] : 'Duplicate This';
		}

		public function sanitize_array(&$array) {
			foreach ($array as &$value) {	
				if( !is_array($value) )	{
					// sanitize if value is not an array
					$value = sanitize_text_field( $value );
			
				}  else {
				// go inside this function again
					$this->sanitize_array($value);
				}
			}
			return $array;
		}
	}
}
?>