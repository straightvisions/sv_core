<?php
	
	namespace sv_core;
	
	class setting extends settings{
		private $settings							= false;
		private $source								= false;
		private $type								= false;
		
		public function __get(string $name){
			if($this->get_path_lib_core($this->get_module_name().'/modules/setting_'.$name.'.php')){ // look for class file in modules directory
				require_once($this->get_path_lib_core($this->get_module_name().'/modules/setting_'.$name.'.php'));
				$classname							= __NAMESPACE__.'\\'.$name;
				
				$this->$name						= new $classname($this);
				return $this->$name;
			}else{
				throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_path_lib_core($this->get_module_name().'/modules/setting_'.$name.'.php').')');
			}
		}
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent){
			$this->settings								= $parent;
		}
		/*
		 * 	@param: $source		for loading database setting values, e.g. wp_options, post_meta, user_meta
		 */
		public function set_source($source){
			$this->source						= $source;
		}
		public function get_source(){
			return $this->source;
		}
		/*
		 * 	@param: $source		set a type for form field
		 */
		public function set_type($type){
			$this->type							= $type;
		}
		public function get_type(){
			return $this->type;
		}
		public function get_form_field(){
			$type									= $this->get_type();
			if(
				$type &&
				isset($this->$type) &&
				is_object($this->$type) &&
				is_subclass_of($type,get_class($this))
			){
				// @todo: proper error notices for each if step
				$field								= $this->$type->create();
				$field->set_ID();
				
				return ;
			}else{
				return '';
			}
		}
		public function get_value(){
			// similar to get_form_field
		}
		public static function create(){
			$new									= new self();
			
			return $new;
		}
	}