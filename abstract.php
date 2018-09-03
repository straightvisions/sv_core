<?php

	namespace sv_core;

	abstract class sv_abstract{
		protected static $name						= false;
		protected static $module_name				= false;
		protected static $basename					= false;
		protected static $path						= false;
		protected static $url						= false;
		protected static $version					= false;
		protected static $wpdb						= false;

		/**
		 * @desc			initialize plugin
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct(){
			$this->init();
			$this->start();
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
			if($this->get_path_lib_modules($name.'.php')){ // look for class file in modules directory
				require_once($this->get_path_lib_modules($name.'.php'));
				$classname							= $this->get_name().'\\'.$name;
				$this->$name						= new $classname();
				return $this->$name;
			}else{
				throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_path_lib_modules($name.'.php').')');
			}
		}
		protected function setup($name){
			$this->name								= $name;
			$this->path							= trailingslashit(trailingslashit(WP_PLUGIN_DIR).$this->get_name());
			$this->url								= trailingslashit(plugins_url('', $this->get_path().$this->get_name()));
			
			global $wpdb;
			self::$wpdb								= $wpdb;

			add_action('init', array($this,'plugins_loaded'));

			$this->setup_core();
		}
		public function plugins_loaded(){
			load_plugin_textdomain($this->get_name(), false, basename($this->get_path()).'/languages');
		}
		protected function init(){
			
		}
		protected function start(){
			
		}
		public function get_name(){
			return $this->name;
		}
		public function get_module_name(){
			return (new \ReflectionClass(get_called_class()))->getShortName();
		}
		public function get_version(){
			return $this->version;
		}
		
		public function get_path($suffix=''){
			if(file_exists($this->path.$suffix)){
				return $this->path.$suffix;
			}else{
				return false;
			}
		}
		public function get_url($suffix=''){
			if(file_exists($this->path.$suffix)){
				return $this->url.$suffix;
			}else{
				return false;
			}
		}
		public function get_path_lib($suffix=''){
			if(file_exists($this->get_path('lib/').$suffix)){
				return $this->get_path('lib/').$suffix;
			}else{
				return false;
			}
		}
		public function get_url_lib($suffix=''){
			if(file_exists($this->get_path('lib/').$suffix)){
				return $this->get_url('lib/').$suffix;
			}else{
				return false;
			}
		}
		public function get_path_lib_modules($suffix=''){
			if(file_exists($this->get_path_lib('modules/').$suffix)){
				return $this->get_path_lib('modules/').$suffix;
			}else{
				return false;
			}
		}
		public function get_path_lib_core($suffix=''){
			if(file_exists($this->get_path_lib('core/').$suffix)){
				return $this->get_path_lib('core/').$suffix;
			}else{
				return false;
			}
		}
		/*
			default hierarchy:
			/lib/frontend/(img|css|js|tpl)/
			/lib/backend/(img|css|js|tpl)/
		*/
		public function get_path_lib_section($section='frontend',$dir='',$suffix=''){
			if(file_exists($this->get_path_lib(trailingslashit($section).trailingslashit($dir)).$suffix)){
				return $this->get_path_lib(trailingslashit($section).trailingslashit($dir)).$suffix;
			}else{
				return false;
			}
		}
		public function get_url_lib_section($section='frontend',$dir='',$suffix=''){
			if(file_exists($this->get_path_lib(trailingslashit($section).trailingslashit($dir)).$suffix)){
				return $this->get_url_lib(trailingslashit($section).trailingslashit($dir)).$suffix;
			}else{
				return false;
			}
		}
		public function get_current_url(){
			return (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
	}
