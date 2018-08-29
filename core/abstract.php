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
			static::init();
			static::start();
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
			if(static::get_path_lib_modules($name.'.php')){ // look for class file in modules directory
				require_once(static::get_path_lib_modules($name.'.php'));
				$classname							= static::get_name().'\\'.$name;
				$this->$name						= new $classname();
				return $this->$name;
			}else{
				throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.static::get_path_lib_modules($name.'.php').')');
			}
		}
		protected function setup($name){
			self::$name								= $name;
			self::$path								= trailingslashit(trailingslashit(WP_PLUGIN_DIR).static::get_name());
			self::$url								= trailingslashit(plugins_url('', self::$path.static::get_name()));

			self::$version							= 1000;
			
			global $wpdb;
			self::$wpdb								= $wpdb;

			add_action('init', array($this,'plugins_loaded'));

			$this->setup_core();
		}
		public function plugins_loaded(){
			load_plugin_textdomain(static::get_name(), false, basename(static::get_path()).'/languages');
		}
		protected function init(){
			
		}
		protected function start(){
			
		}
		public static function get_name(){
			return self::$name;
		}
		public static function get_module_name(){
			return (new \ReflectionClass(get_called_class()))->getShortName();
		}
		public static function get_version(){
			return self::$version;
		}
		
		public static function get_path($suffix=''){
			if(file_exists(self::$path.$suffix)){
				return self::$path.$suffix;
			}else{
				return false;
			}
		}
		public static function get_url($suffix=''){
			if(file_exists(self::$path.$suffix)){
				return self::$url.$suffix;
			}else{
				return false;
			}
		}
		public static function get_path_lib($suffix=''){
			if(file_exists(static::get_path('lib/').$suffix)){
				return static::get_path('lib/').$suffix;
			}else{
				return false;
			}
		}
		public static function get_url_lib($suffix=''){
			if(file_exists(static::get_path('lib/').$suffix)){
				return static::get_url('lib/').$suffix;
			}else{
				return false;
			}
		}
		public static function get_path_lib_modules($suffix=''){
			if(file_exists(static::get_path_lib('modules/').$suffix)){
				return static::get_path_lib('modules/').$suffix;
			}else{
				return false;
			}
		}
		public static function get_path_lib_core($suffix=''){
			if(file_exists(static::get_path_lib('core/').$suffix)){
				return static::get_path_lib('core/').$suffix;
			}else{
				return false;
			}
		}
		/*
			default hierarchy:
			/lib/frontend/(img|css|js|tpl)/
			/lib/backend/(img|css|js|tpl)/
		*/
		public static function get_path_lib_section($section='frontend',$dir='',$suffix=''){
			if(file_exists(static::get_path_lib(trailingslashit($section).trailingslashit($dir)).$suffix)){
				return static::get_path_lib(trailingslashit($section).trailingslashit($dir)).$suffix;
			}else{
				return false;
			}
		}
		public static function get_url_lib_section($section='frontend',$dir='',$suffix=''){
			if(file_exists(static::get_path_lib(trailingslashit($section).trailingslashit($dir)).$suffix)){
				return static::get_url_lib(trailingslashit($section).trailingslashit($dir)).$suffix;
			}else{
				return false;
			}
		}
		public static function get_current_url(){
			return (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
	}
