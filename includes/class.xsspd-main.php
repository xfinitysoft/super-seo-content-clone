<?php
// check for script kiddies 
if ( ! defined( 'ABSPATH' ) ) {
	exit;   //Exit if accessed directly.
}


if(!class_exists('XSSPD_MAIN')){
	class XSSPD_MAIN{
		
		// plugin settings
		private $xsspd_settings;
		
		/* 	Constructer	*/		
		public function __construct(){
			// Get plugin settings
			global $XSSPD;
			$this->xsspd_settings = $XSSPD->get_xsspd_settings();
			
			// Ajax Callback for loading Duplicator Popup Model
			add_action('wp_ajax_xsspd_load_duplicator_modal', array($this, 'load_duplicator_modal') );
			add_action('wp_ajax_xsspd_load_duplicator_modal', array($this, 'load_duplicator_modal') );
			
			// Ajax Callback for loading New According for Find Replace Word in Popup
			add_action('wp_ajax_xsspd_load_new_accordin', array($this, 'load_new_accordin') );
			add_action('wp_ajax_nopriv_xsspd_load_new_accordin', array($this, 'load_new_accordin') );
			
			// Ajax Callback for loading Process Duplication
			add_action('wp_ajax_xsspd_process_duplication', array($this, 'process_duplication') );
			add_action('wp_ajax_nopriv_xsspd_process_duplication', array($this, 'process_duplication') );
			
			// Enqueue Admin Scripts
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_assets') );
			
			if($this->show_duplicate_link()){
				
				add_action( 'wp_before_admin_bar_render', array($this, 'duplicate_poat_admin_bar_link') );
				// Duplicator Meta box in single post admin side
				add_action( 'add_meta_boxes', array( $this, 'duplicator_meta_box_add' ) );
				
				// Duplicator link in Action area in Posts table Admin side
				foreach($this->xsspd_settings->enabled_post_types as $post_type){
					add_filter($post_type.'_row_actions', array($this, 'duplicate_post_link_callback'), 10, 2 );
				}
			}

			add_action( 'wp_ajax_xsspd_send_mail' ,  array( $this , 'xsspd_send_mail'));
		}
		
		
		/* 	Enqueue Admin Assets 		*/
		/* 	@params  null 				*/
		/* 	return null 				*/
		public function enqueue_admin_assets($hook){
			wp_register_style('xsspd-select2-css', XSSPD_ROOT_URL.'/assets/css/select2.min.css', array());
			wp_register_style('xsspd-admin-css', XSSPD_ROOT_URL.'/assets/css/xsspd-admin-style.css', array(), null);
			
			wp_register_script('xsspd-admin-js', XSSPD_ROOT_URL.'/assets/js/xsspd-admin-script.js', array('jquery'), null);
			wp_register_script('xsspd-select2-js', XSSPD_ROOT_URL.'/assets/js/select2.full.min.js', array('jquery'));
			wp_localize_script( 'xsspd-admin-js', 'xsspd_script_var', array( 'ajax_url'=>admin_url('admin-ajax.php') ) );
			wp_enqueue_style('xsspd-select2-css');
			wp_enqueue_style('xsspd-admin-css');
			wp_enqueue_script('xsspd-select2-js');
			wp_enqueue_script('xsspd-admin-js');
			if(isset($_GET['page']) && $_GET['page'] == 'xsspd-support'){
				wp_enqueue_style('support' ,  XSSPD_ROOT_URL.'/assets/css/xsspd-support.css' );
				wp_enqueue_script('support' , XSSPD_ROOT_URL.'/assets/js/xsspd-support.js');

			}
			//wp_register_script();
		}
		
		/* 	Action Rows Callback									 		*/
		/* 	@params  Array actions, Object Post								*/
		/* 	return Array Actions 											*/		
		public function duplicate_post_link_callback($actions, $post){
			$duplicate_link_text = !empty($this->xsspd_settings->duplicate_link_text) ? $this->xsspd_settings->duplicate_link_text : __('Duplicate', 'super-seo-content-cloner');
			$actions['xsspd_duplicate_link'] = '<span class="spinner xsspd_spinner"></span><a href="#" data-post-id="'.esc_attr($post->ID).'" class="xsspd_duplicate" id="xsspd_duplicate_'.esc_attr($post->ID).'">'.esc_html($duplicate_link_text).'</a>';
			return $actions;
		}
		
		/* 	add_meta_boxes		 		*/
		/* 	@params  null 				*/
		/* 	return null 				*/
		public function duplicator_meta_box_add(){
			foreach($this->xsspd_settings->enabled_post_types as $post_type){
				add_meta_box( 'xsspd_duplicator_box', 'Super SEO Content Cloner', array($this, 'duplicator_box_callback'), $post_type, 'side', 'high'  );
			}
		}
		
		/* 	xsspd_duplicator_meta_box callback		 		*/
		/* 	@params  Object Post  							*/
		/* 	return null 									*/
		public function duplicator_box_callback($post){
			?>
			<div id="xsspd_duplicator_box_major_actions">
				<div class="xsspd_duplicator_button_container">
					<span class="spinner xsspd_spinner"></span>
					<input type="hidden" id="xsspd_duplicator_post_id" value="<?php echo esc_attr($post->ID); ?>" />
					<input id="xsspd_duplicate_<?php echo esc_attr($post->ID); ?>" data-post-id="<?php echo esc_attr($post->ID); ?>" type="button" class="button button-primary button-large xsspd_duplicate" value="<?php echo !empty($this->xsspd_settings->duplicate_link_text) ? esc_attr($this->xsspd_settings->duplicate_link_text) : esc_attr__('Duplicate', 'super-seo-content-cloner') ?>" style="float:right;"/>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}
		
		/* 	duplicate_poat_admin_bar_link	*/
		/* 	@params  null					*/
		/* 	return null 					*/
		public function duplicate_poat_admin_bar_link(){
			global $wp_admin_bar, $post;
			$current_object = get_queried_object();
			if ( empty($current_object) )
			return;
			if ( in_array($current_object->post_type, $this->xsspd_settings->enabled_post_types) ){
				$wp_admin_bar->add_menu( array(
				'parent' 		=> 'edit',
				'id' 			=> 'xsspd_duplicate_'.$post->ID,
				'class'			=> 'xsspd_duplicate',
				'title' 		=> !empty($this->xsspd_settings->duplicate_link_text) ? $this->xsspd_settings->duplicate_link_text : __('Duplicate', 'super-seo-content-cloner'),
				'data-post-id' 	=> $post->ID,
				'href'			=> admin_url().'post.php?post='.$post->ID.'&action=edit&xsspd_duplicate=yes',	
				) );
			}
		}

		/* 	AJAX callback		 		*/
		/* 	@params  null				*/
		/* 	return null 				*/		
		public function load_new_accordin(){
			ob_start();
			include XSSPD_ROOT_PATH.'/includes/views/xsspd-accordin.php';
			ob_end_flush();
			wp_die();
		}

		/* 	AJAX callback		 		*/
		/* 	@params  null				*/
		/* 	return null 				*/		
		public function load_duplicator_modal(){
			ob_start();
			include XSSPD_ROOT_PATH.'/includes/views/xsspd-duplicateor-popup.php';
			ob_end_flush();
			wp_die();
		}
		
		/* 	AJAX callback		 		*/
		/* 	@params  null				*/
		/* 	return null 				*/		
		public function process_duplication(){
			$post_id = sanitize_text_field($_POST['duplication_data']['actual_post_id']);
			$duplicate_options = $this->sanitize_array($_POST['duplication_data']['duplication_options']);
			$find_replace_data = $this->sanitize_array($_POST['duplication_data']['find_replace_data']);
			
			do_action( 'xsspd_before_duplication_process',$this->sanitize_array($_POST), $this );
			
			$new_post_id = $this->duplicate_post($post_id, $duplicate_options);
			$new_post = get_post($new_post_id);
			
			do_action( 'xsspd_before_find_replace_process', $this->sanitize_array($_POST), $this );
			//die();
			if( is_array($find_replace_data) and !empty($find_replace_data) and $new_post_id ){
				$this->find_and_replace_process($new_post_id, $find_replace_data);
			}
			
			$response = array(
				'status' 		=> 'success',
				'new_post_id' 	=> $new_post_id,
				'message' 		=> __('Post Duplicated Successfully', 'super-seo-content-cloner'),
				'redirect_to' 	=> false
			);
			
			do_action( 'xsspd_after_find_replace_process', $this->sanitize_array($_POST), $this );
			
			if(!$new_post_id){
				$response['status'] = false;
				$response['message'] = __('Some Unknown Error Occured during post duplication', 'super-seo-content-cloner');
			}
			if($this->xsspd_settings->redirect_to_duplicated_post == 'admin'){
				$response['redirect_to'] = admin_url().'post.php?post='.$new_post_id.'&action=edit';
			}elseif($this->xsspd_settings->redirect_to_duplicated_post == 'front'){
				$response['redirect_to'] = $new_post->guid;
			}
			
			do_action( 'xsspd_after_duplication_process', $this->sanitize_array($_POST), $this );
			
			wp_send_json($response);
			//die();
		}
		
		/* 	Duplicate Post		 		*/
		/* 	@params  Int, Array			*/
		/* 	return Int 					*/
		public function duplicate_post($post_id, $duplicate_options){
			$post = get_post($post_id);
			$title_prefix = !empty($duplicate_options['title_prefix']) ? $duplicate_options['title_prefix'].' - ' : '';
			$title_suffix = !empty($duplicate_options['title_suffix']) ? ' - '.$duplicate_options['title_suffix'] : '';
			$new_post_args = array(
				'comment_status' =>	$post->comment_status,
				'ping_status'	 =>	$post->ping_status,
				'post_author'	 =>	get_current_user_id(),
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_title'	 =>	$title_prefix.$post->post_title.$title_suffix,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);
			if($this->xsspd_settings->new_post_status == 'same_as_orignal'){
				$new_post_args['post_status'] = $post->post_status;
			}else{
				$new_post_args['post_status'] = 'draft';
			}
			
			$new_post_id = wp_insert_post($new_post_args);
			
			if($duplicate_options['contents']['featured_image'] == 'yes'){			
				$this->duplicate_featured_image($post_id, $new_post_id, $duplicate_options['meta']);
			}
			
			if($duplicate_options['contents']['taxonomies'] == 'yes'){
				$this->duplicate_taxonomies($post_id, $new_post_id, $duplicate_options['taxonomies']);
			}
			
			if($duplicate_options['contents']['meta'] == 'yes'){
				$this->duplicate_meta($post_id, $new_post_id, $duplicate_options['meta']);
			}
			
			return $new_post_id;
		}

		/* 	Duplicate Featured Image		 		*/
		/* 	@params  Int, Int						*/
		/* 	return null								*/		
		private function duplicate_featured_image($post_id, $new_post_id){
			$thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true);
			if($thumbnail_id)
				update_post_meta($new_post_id, '_thumbnail_id', $thumbnail_id);
		}

		/* 	Duplicate Taxonomies		 		*/
		/* 	@params  Int, Int, Array			*/
		/* 	return null		 					*/		
		private function duplicate_taxonomies($post_id, $new_post_id, $taxonomies){
			if(!is_array($taxonomies) and $taxonomies == 'all'){
				$taxonomies = get_post_taxonomies($post_id);
			}
			foreach($taxonomies as $taxonomy){
				$terms = wp_get_post_terms( $post_id, $taxonomy);
				foreach($terms as $term){
					wp_set_object_terms( $new_post_id, $term->slug, $taxonomy, true );
				}
			}
		}

		/* 	Duplicate Meta		 		*/
		/* 	@params  Int, Int, Array	*/
		/* 	return null 					*/		
		private function duplicate_meta($post_id, $new_post_id, $meta){
			if(!is_array($meta) && $meta == 'all'){
				$post_meta = get_post_meta($post_id);
				foreach($post_meta as $key => $val){
					$value = '';
					$value = get_post_meta($post_id,$key,true);
					if($key == '_wp_old_slug' ) continue;
					if($key == '_elementor_data'){
						$value = addslashes($value);
					}
					update_post_meta($new_post_id,$key,$value);
				}
			}else{
				foreach($meta as $key){
					$value = get_post_meta($post_id, $key, true);
					update_post_meta($new_post_id, $key, $value);
				}
			}
		}

		/* 	Find and Replace Process	*/
		/* 	@params  Int, Array			*/
		/* 	return null 				*/		
		public function find_and_replace_process($new_post_id, $find_replace_data){
			global $wpdb;
			$new_post = get_post($new_post_id);
			$post_replace_args = array();
			
			$new_post_title = $new_post->post_title;
			$new_post_name = $new_post->post_name;
			$new_post_content = $new_post->post_content;
			$new_post_excerpt = $new_post->post_excerpt;
			foreach($find_replace_data as $fw_data){
				$r_word = trim($fw_data['replace']);
				$w_word = trim($fw_data['replace_with']);
				if($fw_data['replace_title'] == 'yes'){
					$new_post_title = $this->string_replace( $r_word, $w_word, $new_post_title);
				}
				
				if($fw_data['replace_slug'] == 'yes'){
					$new_post_name = $this->string_replace( $r_word, $w_word, $new_post_name);
				}
				
				if($fw_data['replace_content'] == 'yes'){
					$new_post_content = $this->string_replace( $r_word, $w_word, $new_post_content);
				}
				
				if($fw_data['replace_excerpt'] == 'yes'){
					$new_post_excerpt = $this->string_replace( $r_word, $w_word, $new_post_excerpt);
				}
			}
			$post_replace_args['ID'] = $new_post_id;
			$post_replace_args['post_title'] = $new_post_title;
			$post_replace_args['new_post_name'] = $new_post_name;
			$post_replace_args['post_content'] = $new_post_content;
			$post_replace_args['post_excerpt'] = $new_post_excerpt;
			
			wp_update_post($post_replace_args);
		}

		/* 	String Replace		 					*/
		/* 	@params  string, string, string, bool	*/
		/* 	return string							*/		
		private function string_replace($find, $replace_with, $content){
			return str_replace($find, $replace_with, $content);
		}
		
		/* 	Show Duplicate Link 	*/
		/* 	@params  null			*/
		/* 	return bool				*/		
		private function show_duplicate_link(){
			$user = wp_get_current_user();
			$user_roles = $user->roles;
			foreach($user_roles as $role){
				if(in_array($role, $this->xsspd_settings->allowed_roles))
					return true;
			}
			return false;
		}

		public function xsspd_send_mail(){
	        $data = array();
	        parse_str($this->sanitize_array($_POST['data']), $data);
	        $data['plugin_name'] = 'Super SEO Content Cloner';
	        $data['version'] = 'Free Version';
	        $data['website'] = sanitize_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST']);
	        $to = 'xfinitysoft@gmail.com';
	        switch ($data['type']) {
	            case 'report':
	                $subject = 'Report a bug';
	                break;
	            case 'hire':
	                $subject = 'Hire us';
	                break;
	            
	            default:
	                $subject = 'Request a Feature';
	                break;
	        }
	        
	        $body = '<html><body><table>';
	        $body .='<tbody>';
	        $body .='<tr><th>User Name</th><td>'.$data['xsspd_name'].'</td></tr>';
	        $body .='<tr><th>User email</th><td>'.$data['xsspd_email'].'</td></tr>';
	        $body .='<tr><th>Plugin Name</th><td>'.$data['plugin_name'].'</td></tr>';
	        $body .='<tr><th>Version</th><td>'.$data['version'].'</td></tr>';
	        $body .='<tr><th>Website</th><td><a href="'.$data['website'].'">'.$data['website'].'</a></td></tr>';
	        $body .='<tr><th>Message</th><td>'.$data['xsspd_message'].'</td></tr>';
	        $body .='</tbody>';
	        $body .='</table></body></html>';
	        $admin_email = get_option( 'admin_email' );
        	$headers = array( 'From: '.$data['xsspd_name'].' <'.$admin_email.'>', 'Reply-To: '.$data['xsspd_name'].' <'.$data['xsspd_email'].'>','Content-Type: text/html; charset=UTF-8' );
	        $params ="name=".$data['xsspd_name'];
	        $params.="&email=".$data['xsspd_email'];
	        $params.="&site=".$data['website'];
	        $params.="&version=".$data['version'];
	        $params.="&plugin_name=".$data['plugin_name'];
	        $params.="&type=".$data['type'];
	        $params.="&message=".$data['xsspd_message'];
	        $sever_response = wp_remote_post("https://xfinitysoft.com/wp-json/plugin/v1/quote/save/?".$params);
	        $se_api_response = json_decode( wp_remote_retrieve_body( $sever_response ), true );
	        
	        if($se_api_response['status']){
	            $mail = wp_mail( $to, $subject, $body, $headers );
	            wp_send_json(array('status'=>true));
	        }else{
	            wp_send_json(array('status'=>false));
	        }
	        wp_die();
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