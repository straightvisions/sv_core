<?php

	namespace sv_core;

	abstract class sv_abstract{
		const version_core					= 1004;
		
		protected $name						= false;
		protected $module_name				= false;
		protected $basename					= false;
		protected $path						= false;
		protected $url						= false;
		protected $version					= false;
		private $parent						= false;
		private $root						= false;
		protected $s						= array(); // settings object array
		protected static $wpdb				= false;
		private static $instances			= array();
		private static $instances_active	= array();
		protected static $path_core			= false;
		protected static $url_core			= false;
		protected $sections					= array();
		protected $section_types			= array(
			'instance'						=> 'Instance Core Methods',
			'settings'						=> 'Configuration &amp; Settings',
			'tools'							=> 'Helpful tools &amp; helper',
			'docs'							=> 'Complete Documentation'
		);
		protected $section_template_path	= '';
		protected $section_title			= false;
		protected $section_desc				= false;


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
		public function get_version(bool $formatted=false){
			if(defined(get_called_class().'::version')){
				if($formatted){
					return number_format(get_called_class()::version,0,',','.');
				}else{
					return get_called_class()::version;
				}
			}else{
				if($formatted){
					return __('not defined', $this->get_module_name());
				}else{
					return 0;
				}
			}
		}
		public function get_version_core_match($formatted=false){
			if(defined(get_called_class().'::version_core_match')){
				if($formatted){
					return number_format(get_called_class()::version_core_match,0,',','.');
				}else{
					return get_called_class()::version_core_match;
				}
			}else{
				if($formatted){
					return __('not defined', $this->get_module_name());
				}else{
					return 0;
				}
			}
		}
		public function get_version_core($formatted=false){
			if(defined(get_called_class().'::version_core')){
				if($formatted){
					return number_format(get_called_class()::version_core,0,',','.');
				}else{
					return get_called_class()::version_core;
				}
			}else{
				if($formatted){
					return __('not defined', $this->get_module_name());
				}else{
					return 0;
				}
			}
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
		public function find_parent_by_name($name){
			if($this->get_parent() != $this->get_root()){
				if ($this->get_parent()->get_name() == $name) {
					return $this->get_parent();
				}else{
					return $this->get_parent()->find_parent_by_name($name);
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

			if($this->get_root()->get_version_core_match() == $this->get_version_core()){
				self::$instances_active[$name]			= $this;
			}
			self::$instances[$name]						= $this;
		}
		public static function get_instances(): array{
			return self::$instances;
		}
		public static function get_instances_active(): array{
			return self::$instances_active;
		}
		public static function is_instance_active(string $name): bool{
			return isset(self::$instances_active[$name]);
		}
		public function plugins_loaded(){
			load_plugin_textdomain($this->get_name(), false, basename($this->get_path()).'/languages');
		}
		protected function init(){

		}
		protected function start(){

		}
		public function get_name(){
			if(property_exists($this,'core')){ // todo: check if core is still needed
				return $this->core->name;
			}elseif($this->name){ // if name is set, use it
				return $this->name;
			}elseif($this != $this->get_parent()){ // if there's a parent, go a step higher
				return $this->get_parent()->get_name().'_'.$this->get_module_name();
			}else{ // nothing set? use fallback-name
				return 'sv';
			}
		}
		public function get_module_name(){
			return (new \ReflectionClass(get_called_class()))->getShortName();
		}
		public function get_prefix($append=''){
			if(strlen($append) > 0){
				$append			= '_'.$append;
			}
			return $this->get_name().$append;
		}
		public function get_relative_prefix($append=''){
			if(strlen($append) > 0){
				$append			= '_'.$append;
			}
			return str_replace($this->get_root()->get_name(),'sv_common',$this->get_name()).$append;
		}
		public function get_path($suffix='',$check_if_exists=false){
			if(property_exists($this,'core') &&
				get_class($this->core) != 'sv_100\init'){ // todo: check if core is still needed
				$path					= $this->core->path;
			}elseif($this->path){ // if path is set, use it
				$path					= $this->path;
			}elseif($this != $this->get_parent()){ // if there's a parent, go a step higher
				$path					= $this->get_parent()->get_path();
			}else{ // nothing set? use fallback-path
				$path					= trailingslashit(dirname(__FILE__));
			}
			
			$this->path					= $path;
			
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
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' called by '.(new \ReflectionClass(get_called_class()))->getName().' - path not found: ' . $path . $suffix);
					return false;
				}
			}
		}
		public function get_url($suffix='',$check_if_exists=false){
			if($this->url){ // if url is set, use it
				$url					= $this->url;
			}elseif($this != $this->get_parent()){ // if there's a parent, go a step higher
				$url					= $this->get_parent()->get_url();
			}
			
			$this->url					= $url;
			
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
		public function get_url_lib($suffix='',$check_if_exists=false){
			if(file_exists($this->get_path('lib/').$suffix)){
				if($check_if_exists){
					return true;
				}else {
					return $this->get_url('lib/') . $suffix;
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
			if(file_exists(self::$path_core.$suffix)){
				if($check_if_exists){
					return true;
				}else {
					return self::$path_core . $suffix;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - path not found: '.self::$path_core . $suffix);
					return false;
				}
			}
		}
		public function get_url_lib_core($suffix='',$check_if_exists=false){
			if(file_exists(self::$path_core.$suffix)){
				if($check_if_exists){
					return true;
				}else {
					return self::$url_core . $suffix;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - url not found: '.self::$url_core . $suffix);
					return false;
				}
			}
		}
		/*
			default hierarchy:
			/lib/frontend/(img|css|js|tpl)/
			/lib/backend/(img|css|js|tpl)/
		*/
		public function get_path_lib_section($section=false,$dir=false,$suffix='',$check_if_exists=false){
			$path			= $this->get_path_lib(
						($section ? trailingslashit($section) : '').
							($dir ? trailingslashit($dir) : '')).
							$suffix;
			
			if(file_exists($path)
			){
				if($check_if_exists){
					return true;
				}else {
					return $path;
				}
			}else{
				if($check_if_exists){
					return false;
				}else {
					error_log("Warning: " . __CLASS__ . ' - ' . __FUNCTION__ . ' - path not found: '.$path);
					return false;
				}
			}
		}
		public function get_url_lib_section($section=false,$dir=false,$suffix='',$check_if_exists=false){
			$path			= $this->get_path_lib(
					($section ? trailingslashit($section) : '').
					($dir ? trailingslashit($dir) : '')).
				$suffix;
			
			if(file_exists($path)){
				return $this->get_url_lib(($section ? trailingslashit($section) : '').($dir ? trailingslashit($dir) : '')).$suffix;
			}else{
				error_log("Warning: ".__CLASS__.' - '.__FUNCTION__.' - path not found: '.$path);
				return false;
			}
		}
		public function get_current_url(){
			return (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
		public function get_current_path(){
			return $_SERVER['REQUEST_URI'];
		}
		public function acp_style($hook=false){
			if(!$hook || $hook == 'sv-100_page_'.$this->get_module_name()) {
				echo '<style>';
				require_once($this->get_path_lib_core('assets/admin.css'));
				echo '</style>';
			}
		}
		public function add_section($object, string $type = 'docs'){
			if(is_object($object)) { // @todo: remove this line once sv_bb_dashboard is upgraded
				$this->sections[$object->get_prefix()] = array(
					'object'	=> $object,
					'type'		=> isset($this->section_types[$type]) ? $type : 'docs'
				);
				return $object;
			}else{
				return $this;
			}
		}
		public function get_sections(): array{
			return $this->sections;
		}
		public function get_section_template_path(): string{
			return $this->section_template_path;
		}
		public function set_section_template_path(string $path){
			$this->section_template_path		= $path;

			return $this;
		}
		public function set_section_title(string $title){
			$this->section_title = $title;
		}
		public function get_section_title(): string{
			return $this->section_title ? $this->section_title : __('No Title defined.', $this->get_root()->get_prefix());
		}
		public function set_section_desc(string $desc){
			$this->section_desc = $desc;
		}
		public function get_section_desc(): string{
			return $this->section_desc ? $this->section_desc : __('No description defined.', $this->get_root()->get_prefix());
		}
		public function get_constant(string $constant_name){
			return constant(get_class($this).'::'.$constant_name);
		}
		public function constant_exists(string $constant_name){
			return defined(get_class($this).'::'.$constant_name);
		}
		public function build_sections(){
			foreach($this->get_instances() as $name => $instance){
				$instance->add_section($instance, 'instance');
				
				add_submenu_page(
					'straightvisions',										// parent slug
					$instance->get_section_title(),														// page title
					$instance->get_section_title(),														// menu title
					'manage_options',														// capability
					$instance->get_prefix(),										// menu slug
					function() use($instance){
						$instance->load_page();
					}	// callable function
				);
				
				add_action('admin_enqueue_scripts', array($instance,'admin_enqueue_scripts'));
			}
		}
		public function admin_enqueue_scripts($hook){
			if($hook == 'straightvisions_page_'.$this->get_prefix() || $hook == 'toplevel_page_straightvisions') {
				wp_enqueue_script($this->get_prefix(), $this->get_url_lib_core('assets/admin.js'), array('jquery'), filemtime($this->get_path_lib_core('assets/admin.js')), true);
			}
		}
		public function load_page(string $custom_about_path = ''){
			$this->get_root()->acp_style();
			require_once($this->get_path_lib_core('backend/tpl/header.php'));
			require_once(strlen($custom_about_path) > 0 ? $custom_about_path : $this->get_path_lib_core('backend/tpl/about.php'));
			$this->load_section_html();
			require_once($this->get_path_lib_core('backend/tpl/legal.php'));
			require_once($this->get_path_lib_core('backend/tpl/footer.php'));
		}
		public function load_section_menu(){
			foreach($this->get_sections() as $section_name => $section) {
				echo '<div data-target="#section_' . $section_name . '" class="sv_admin_menu_item section_' . $section['type'] . '"><h4>' . ($section['type'] == 'instance' ? 'Core Docs' : $section['object']->get_section_title()) . '</h4><span>' . $this->section_types[$section['type']] . '</span></div>';
			}
		}
		public function load_section_html(){
			foreach( $this->get_sections() as $section_name => $section ) {
				require( $this->get_path_lib_core('backend/tpl/section_'.$section['type'].'.php') );
			}
		}
		public function plugin_action_links($actions) {
			$links						= array(
				'settings'				=> '<a href="admin.php?page=' . $this->get_root()->get_prefix() . '">Settings</a>',
				'straightvisions'		=> '<a href="https://straightvisions.com" target="_blank">straightvisions.com</a>',
			);

			$actions			        = array_merge($links, $actions);

			return $actions;
		}
	}
