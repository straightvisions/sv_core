<?php
	
	namespace sv_core;
	
	class metabox extends sv_abstract{
		static $scripts_loaded		= false;
		private $title				= '';
		
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct(){
		
		}
		/**
		 * @desc			Load's requested libraries dynamicly
		 * @param	string	$name library-name
		 * @return			class object of the requested library
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __get(string $name){
			if($this->get_path_lib_core('settings/modules/'.$name.'.php',true)){ // look for class file in modules directory
				require_once($this->get_path_lib_core('settings/modules/'.$name.'.php'));
				$class_name							= __NAMESPACE__.'\\'.$name;
				
				$this->$name						= new $class_name($this);
				return $this->$name;
			}else{
				throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_module_name().'/modules/'.$name.'.php'.')');
			}
		}
		// OBJECT METHODS
		public static function create($parent){
			$new									= new static();
			
			$new->prefix							= $parent->get_prefix().'_';
			$new->set_root($parent->get_root());
			$new->set_parent($parent);
			$new->run();
			
			return $new;
		}
		public function set_title(string $title){
			$this->title							= $title;
			return $this;
		}
		public function get_title(): string{
			return $this->title;
		}
		public function run(){
			add_action('load-post.php', array($this,'post_meta_boxes_setup'));
			add_action('load-post-new.php', array($this,'post_meta_boxes_setup'));
		}
		public function post_meta_boxes_setup(){
			add_action('add_meta_boxes', array($this,'add_meta_boxes'));
			add_action('save_post', array($this,'save_post'), 10, 2);
		}
		public function add_meta_boxes(){
			add_meta_box(
				$this->get_module_name(),								// Unique ID
				$this->get_title(),		// Title
				array($this,'post_class_meta_box'),				// Callback function
				NULL,											// Admin page (or post type)
				'side',											// Context
				'default'										// Priority
			);
		}
		public function post_class_meta_box($post){
			if(!static::$scripts_loaded) {
				$this->get_parent()->admin_enqueue_scripts('toplevel_page_straightvisions');
				$this->get_root()->acp_style();
				static::$scripts_loaded		= true;
			}
			wp_nonce_field($this->get_prefix(), $this->get_prefix('nonce'));
			
			foreach($this->get_parent()->s as $setting){
				$meta_field					= $setting->get_prefix($setting->get_ID());
				$setting->run_type()->set_data(get_post_meta($post->ID, $meta_field, true));
				
				echo $setting->run_type()->default(true);
			}
		}
		public function save_post($post_id, $post){
			// Verify the nonce before proceeding.
			if(!isset($_POST[$this->get_prefix('nonce')]) || !wp_verify_nonce($_POST[$this->get_prefix('nonce')], $this->get_prefix())){
				return $post_id;
			}
			
			// Get the post type object.
			$post_type											= get_post_type_object($post->post_type);
			
			// Check if the current user has permission to edit the post.
			if(!current_user_can($post_type->cap->edit_post, $post_id)){
				return $post_id;
			}
			
			foreach($this->get_parent()->s as $setting){
				$field_id											= $setting->get_prefix($setting->get_ID());
				// Get the posted data and sanitize it for use as an HTML class.
				$new_meta_value										= (isset($_POST[$field_id]) ? $_POST[$field_id] : '');
				
				// Get the meta value of the custom field key.
				$meta_value											= get_post_meta($post_id, $field_id, true);
				
				// If a new meta value was added and there was no previous value, add it.
				if($new_meta_value && '' == $meta_value){
					add_post_meta($post_id, $field_id, $new_meta_value, true);
				}elseif($new_meta_value && $new_meta_value != $meta_value){
					// If the new meta value does not match the old value, update it.
					update_post_meta($post_id, $field_id, $new_meta_value);
				}elseif('' == $new_meta_value && $meta_value){
					// If there is no new meta value but an old value exists, delete it.
					delete_post_meta($post_id, $field_id, $meta_value);
				}
			}
			return $post_id;
		}
	}