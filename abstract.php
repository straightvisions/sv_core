<?php

	namespace sv_core;

	abstract class sv_abstract{
		protected $name						= false;
		protected $module_name				= false;
		protected $basename					= false;
		protected $path						= false;
		protected $url						= false;
		protected $version					= false;
		protected static $wpdb				= false;

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
			$core = isset($this->core) ? $this->core : $this;
			
			if($core->get_path_lib_modules($name.'.php')){ // look for class file in modules directory
				require_once($core->get_path_lib_modules($name.'.php'));
				$class_name							= $core->get_name().'\\'.$name;
				$this->$name						= new $class_name();
				$this->$name->core					= isset($this->core) ? $this->core : $this;
				return $this->$name;
			}else{
				throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_path_lib_modules().$name.'.php)');
			}
		}
		protected function setup($name,$file){
			$this->name								= $name;
			$this->path								= plugin_dir_path($file);
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
			if(isset($this->core)){
				return $this->core->name;
			}else{
				return $this->name;
			}
		}
		public function get_module_name(){
			return (new \ReflectionClass(get_called_class()))->getShortName();
		}
		public function get_version(){
			if(isset($this->core)){
				return $this->core->version;
			}else{
				return $this->version;
			}
		}
		
		public function get_path($suffix=''){
			$path						= (isset($this->core) ? $this->core->path : $this->path);
			if(file_exists($path.$suffix)){
				return $path.$suffix;
			}else{
				return false;
			}
		}
		public function get_url($suffix=''){
			if(file_exists($this->get_path().$suffix)){
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
