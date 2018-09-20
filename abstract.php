<?php

	namespace sv_core;

	abstract class sv_abstract{
		protected $name						= false;
		protected $module_name				= false;
		protected $basename					= false;
		protected $path						= false;
		protected $url						= false;
		protected $version					= false;
		private $parent						= false;
		private $root						= false;
		protected static $wpdb				= false;
		private static $instances			= array();

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
			$root = $this->get_root();

			if($root->get_path_lib_modules($name.'.php')){ // look for class file in modules directory
				require_once($root->get_path_lib_modules($name.'.php'));
				$class_name							= $root->get_name().'\\'.$name;
				$this->$name						= new $class_name();
				$this->$name->set_root($root);
				$this->$name->set_parent($this);
				return $this->$name;
			}else{
				throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_path_lib_modules().$name.'.php)');
			}
		}
		public function set_parent($parent){
			$this->parent							= $parent;
		}
		public function get_parent(){
			return $this->parent ? $this->parent : $this;
		}
		public function get_root(){
			return $this->root ? $this->root : $this;
		}
		public function set_root($root){
			$this->root								= $root;
		}
		public function find_parent($class_name,$qualified=false){
			if($this->get_parent() != $this->get_root()){
				if(!$qualified){
					if ($this->get_parent()->get_module_name() == $class_name) {
						return $this->get_parent();
					}else{
						return $this->get_parent()->find_parent($class_name,$qualified);
					}
				}else{
					if (get_class($this->get_parent()) == $class_name) {
						return $this->get_parent();
					}else{
						return $this->get_parent()->find_parent($class_name,$qualified);
					}
				}
			}
			return false;
		}
		protected function setup($name,$file){
			$this->name								= $name;

			if(get_class($this) == 'sv_100\init'){
				$this->path							= trailingslashit(get_template_directory());
				$this->url							= trailingslashit(get_template_directory_uri());
			}else{
				$this->path							= plugin_dir_path($file);
				$this->url							= trailingslashit(plugins_url('', $this->get_path().$this->get_name()));
			}

			global $wpdb;
			self::$wpdb								= $wpdb;

			add_action('init', array($this,'plugins_loaded'));
			$this->setup_core($this->path);

			self::$instances[$name]						= $this;
		}
		public static function get_instances(){
			return self::$instances;
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
			}elseif($this->name){
				return $this->name;
			}else{
				return 'sv';
			}
		}
		public function get_module_name(){
			return (new \ReflectionClass(get_called_class()))->getShortName();
		}
		public function get_prefix($append=''){
			return $this->get_name().'_'.$this->get_module_name().'_'.$append;
		}
		public function get_version(){
			if(isset($this->core)){
				return $this->core->version;
			}else{
				return $this->version;
			}
		}

		public function get_path($suffix='',$check_if_exists=false){
			$path						= (
					(
						isset($this->core) &&
						get_class($this->core) != 'sv_100\init'
					) ? $this->core->path : $this->path
				);

			if(file_exists($path.$suffix)){
				if($check_if_exists){
					return true;
				}else{
					return $path.$suffix;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - path not found: ' . $path . $suffix);
					return false;
				}
			}
		}
		public function get_url($suffix='',$check_if_exists=false){
			if(file_exists($this->get_path().$suffix)){
				if($check_if_exists){
					return true;
				}else {
					return $this->url . $suffix;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - path not found: ' . $suffix);
					return false;
				}
			}
		}
		public function get_path_lib($suffix='',$check_if_exists=false){
			if(file_exists($this->get_path('lib/').$suffix)){
				if($check_if_exists){
					return true;
				}else {
					return $this->get_path('lib/') . $suffix;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - path not found: ' . $suffix);
					return false;
				}
			}
		}
		public function get_url_lib($suffix=''){
			if(file_exists($this->get_path('lib/').$suffix)){
				return $this->get_url('lib/').$suffix;
			}else{
				error_log("Warning: ".__CLASS__.' - '.__FUNCTION__.' - path not found: '.$suffix);
				return false;
			}
		}
		public function get_path_lib_modules($suffix='',$check_if_exists=false){
			if(file_exists($this->get_path_lib('modules/').$suffix)){
				if($check_if_exists){
					return true;
				}else {
					return $this->get_path_lib('modules/') . $suffix;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - path not found: ' . $suffix);
					return false;
				}
			}
		}
		public function get_path_lib_core($suffix='',$check_if_exists=false){
			if(file_exists($this->get_path_lib('core/').$suffix)){
				if($check_if_exists){
					return true;
				}else {
					return $this->get_path_lib('core/') . $suffix;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - path not found: ' . $suffix);
					return false;
				}
			}
		}
		/*
			default hierarchy:
			/lib/frontend/(img|css|js|tpl)/
			/lib/backend/(img|css|js|tpl)/
		*/
		public function get_path_lib_section($section='frontend',$dir='',$suffix='',$check_if_exists=false){
			if(file_exists($this->get_path_lib(trailingslashit($section).trailingslashit($dir)).$suffix)){
				if($check_if_exists){
					return true;
				}else {
					return $this->get_path_lib(trailingslashit($section) . trailingslashit($dir)) . $suffix;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - path not found: ' . trailingslashit($section) . trailingslashit($dir) . $suffix);
					return false;
				}
			}
		}
		public function get_url_lib_section($section='frontend',$dir='',$suffix=''){
			if(file_exists($this->get_path_lib(trailingslashit($section).trailingslashit($dir)).$suffix)){
				return $this->get_url_lib(trailingslashit($section).trailingslashit($dir)).$suffix;
			}else{
				error_log("Warning: ".__CLASS__.' - '.__FUNCTION__.' - path not found: '.trailingslashit($section).trailingslashit($dir).$suffix);
				return false;
			}
		}
		public function get_current_url(){
			return (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
		public function acp_style(){
			wp_enqueue_style($this->get_module_name(), $this->get_root()->get_url_lib_section('core','assets','admin.css'));
		}
	}
