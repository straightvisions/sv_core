<?php
	namespace sv_core;
	
	abstract class sv_abstract {
		const version_core					= 4012;
		
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
		protected static $instances_active	= array();
		protected $loaded			        = array();
		protected static $path_core			= false;
		protected static $url_core			= false;
		protected $curl_handler             = false;
		protected $sections					= array();
		protected $section_types			= array(
			'settings'						=> 'Configuration &amp; Settings',
			'tools'							=> 'Helpful tools &amp; helper',
			'docs'							=> 'Complete Documentation'
		);
		protected $section_template_path	= '';
		protected $section_title			= false;
		protected $section_desc				= false;
		protected $section_privacy			= false;
		protected $section_type				= '';
		protected $scripts_queue			= array();
		protected static $expert_mode         = false;
		
		/**
		 * @desc			initialize plugin
		 * @author			Matthias Bathke
		 * @since			1.0
		 * @ignore
		 */
		public function load_translation(){
			$locale = apply_filters( 'plugin_locale', determine_locale(), 'sv_core' );
			load_textdomain( 'sv_core', dirname( __FILE__ ) . '/languages/sv_core-'.$locale.'.mo' );
		}
		/**
		 * @desc			Load's requested libraries dynamicly
		 * @param	string	$name library-name
		 * @return			class object of the requested library
		 * @author			Matthias Bathke
		 * @since			1.0
		 * @ignore
		 */
		public function __get( string $name ) {
			// look for class file in modules directory
			if ( file_exists($this->get_root()->get_path( 'lib/modules/'.$name . '.php' )) ) {
				require_once( $this->get_root()->get_path( 'lib/modules/'.$name . '.php' ) );
				
				$class_name	    = $this->get_root()->get_name() . '\\' . $name;
				$this->$name    = new $class_name();
				$this->$name->set_root( $this->get_root() );
				$this->$name->set_parent( $this );
				
				return $this->$name;
			}
			
			if ( file_exists($this->get_root()->get_path( 'lib/modules/'.$name . '/'.$name . '.php' )) ) {
				require_once( $this->get_root()->get_path( 'lib/modules/'.$name . '/'.$name . '.php' ) );
				
				$class_name	    = $this->get_root()->get_name() . '\\' . $name;
				$this->$name    = new $class_name();
				$this->$name->set_root( $this->get_root() );
				$this->$name->set_parent( $this );
				
				return $this->$name;
			}
			
			throw new \Exception( 'Class ' . $name . ' could not be loaded (tried to load class-file ' . $this->get_path() . 'lib/modules/'.$name . '.php)' );
		}
		public function wordpress_version_check($min_version = '5.0.0'){
			// Get unmodified $wp_version.
			include ABSPATH . WPINC . '/version.php';
			// Strip '-src' from the version string. Messes up version_compare().
			$version = str_replace( '-src', '', $wp_version );
			if ( version_compare( $version, $min_version, '<' ) ) {
				add_action( 'admin_notices', array($this, 'wordpress_version_notice') );
				return;
			}
		}
		public function wordpress_version_notice(){
			// extend in childs when needed.
		}
		public function set_is_expert_mode(bool $yes = true){
			static::$expert_mode = $yes;
		}
		public function get_is_expert_mode(): bool{
			return static::$expert_mode;
		}
		public function set_parent( $parent ) {
			$this->parent = $parent;
		}
		
		public function get_parent() {
			return $this->parent ? $this->parent : $this;
		}
		
		public function get_root() {
			return $this->root ? $this->root : $this;
		}
		
		public function set_root( $root ) {
			$this->root = $root;
		}
		
		public function get_previous_version(): int {
			return intval( get_option( $this->get_prefix( 'version') ) );
		}
		
		public function get_version( bool $formatted = false ) {
			if ( defined( get_called_class() . '::version' ) ) {
				if ( $formatted ) {
					return number_format( get_called_class()::version, 0, ',', '.' );
				} else {
					return get_called_class()::version;
				}
			} else {
				if ( $formatted ) {
					return __( 'not defined', 'sv_core' );
				} else {
					return 0;
				}
			}
		}
		
		public function get_version_core_match( $formatted = false ) {
			if ( defined( get_called_class() . '::version_core_match' ) ) {
				if ( $formatted ) {
					return number_format( get_called_class()::version_core_match, 0, ',', '.' );
				} else {
					return get_called_class()::version_core_match;
				}
			} else {
				if ( $formatted ) {
					return __( 'not defined', 'sv_core' );
				} else {
					return 0;
				}
			}
		}
		
		public function get_version_core( $formatted = false ) {
			if ( defined( get_called_class() . '::version_core' ) ) {
				if ( $formatted ) {
					return number_format( get_called_class()::version_core, 0, ',', '.' );
				} else {
					return get_called_class()::version_core;
				}
			} else {
				if ( $formatted ) {
					return __( 'not defined', 'sv_core' );
				} else {
					return 0;
				}
			}
		}
		
		public function find_parent( $class_name, $qualified = false ) {
			if ( $this->get_parent() != $this->get_root() ) {
				if ( !$qualified ) {
					if ( $this->get_parent()->get_module_name() == $class_name ) {
						return $this->get_parent();
					} else {
						return $this->get_parent()->find_parent( $class_name, $qualified );
					}
				} else {
					if ( get_class( $this->get_parent() ) == $class_name ) {
						return $this->get_parent();
					} else {
						return $this->get_parent()->find_parent( $class_name, $qualified );
					}
				}
			}
			
			return false;
		}
		
		public function find_parent_by_name( $name ) {
			if ( $this->get_parent() != $this->get_root() ) {
				if ( $this->get_parent()->get_name() == $name ) {
					return $this->get_parent();
				} else {
					return $this->get_parent()->find_parent_by_name( $name );
				}
			}
			
			return false;
		}
		
		public function is_theme_instance(){
			return get_class( $this->get_root() ) == 'sv100\init' ? true : false;
		}
		protected function setup( $name, $file ) {
			// make sure to init only once
			//$namespace = strstr(get_class($this->get_root()), '\\', true);
			//if(isset($this->get_instances()[$namespace])){
			if(isset($this->get_instances()[$name])){
				return;
			}
			
			$this->name								= $name;
			
			if($this->is_theme_instance()) {
				$this->path							= trailingslashit( get_template_directory() );
				$this->url							= trailingslashit( get_template_directory_uri() );
			} else {
				$this->path							= plugin_dir_path( $file );
				$this->url							= trailingslashit( plugins_url( '', $this->get_path() . $this->get_name() ) );
				$this->plugins_loaded();
			}
			
			global $wpdb;
			self::$wpdb								= $wpdb;
			
			$state                                  = true;
			if(!isset(self::$instances[ $name ])){
				$state                              = $this->setup_core( $this->path, $name );
			}
			
			self::$instances[ $name ]				= $this;
			
			return $state;
		}
		
		public static function get_instances(): array {
			return self::$instances;
		}
		
		public static function get_instances_active(): array {
			return self::$instances_active;
		}
		
		public static function is_instance_active( string $name ): bool {
			return isset( self::$instances_active[ $name ] );
		}
		
		public function plugins_loaded() {
			if(!$this->is_theme_instance()) {
				load_plugin_textdomain( $this->get_root()->get_prefix(), false, basename( $this->get_path() ) . '/languages' );
			}
		}
		
		public function update_routine() {
			update_option( $this->get_prefix( 'version' ), $this->get_version() );
		}
		
		public function init() {
			if(did_action('plugins_loaded')){
				$this->load_translation();
			}else{
				add_action('plugins_loaded', array($this, 'load_translation'));
			}
		}
		
		public function set_name(string $name) {
			$this->name		= $name;
			
			return $this;
		}
		public function get_name() {
			if ( $this->name ) { // if name is set, use it
				return $this->name;
			} else if ( $this != $this->get_parent() ) { // if there's a parent, go a step higher
				return $this->get_parent()->get_name() . '_' . $this->get_module_name();
			} else { // nothing set? use fallback-name
				return 'sv';
			}
		}
		
		public function get_module_name() {
			return ( new \ReflectionClass( get_called_class() ) )->getShortName();
		}
		
		public function get_prefix( $append = '' ) {
			if( strlen( $append ) > 0 ) {
				$append = '_' . $append;
			}
			
			return $this->get_name() . $append;
		}
		public function get_prefix_gutenberg( $append = '' ) {
			if( strlen( $append ) > 0 ) {
				$append = '/' . $append;
			}
			
			return str_replace('_', '-', $this->get_name() . $append);
		}
		
		public function get_relative_prefix( $append = '' ) {
			if( strlen( $append ) > 0 ) {
				$append = '_' . $append;
			}
			
			return str_replace( $this->get_root()->get_name(), 'sv_common', $this->get_name() ) . $append;
		}
		
		public function get_settings(): array {
			if(count($this->s) === 0){
				$this->load_settings();
			}
			
			return $this->s;
		}
		
		public function get_setting(string $setting = ''): settings {
			if(strlen($setting) > 0 && isset($this->s[$setting])){
				if(!isset($this->s[$setting]) === 0){
					$this->load_settings();
				}
				
				if(!isset($this->s[$setting]) === 0){
					$this->s[$setting] = static::$settings->create( $this )->set_ID($setting);
					return $this->s[$setting]; // return empty setting if not exist
				}
				
				return $this->s[$setting];
			}
			
			$this->s[$setting] = static::$settings->create( $this )->set_ID($setting);
			return $this->s[$setting]; // return empty setting if not exist
		}
		public function get_script(string $script = ''): scripts {
			if(strlen($script) > 0 && isset($this->scripts_queue[$script])){
				if(!isset($this->scripts_queue[$script]) === 0){
					$this->load_settings();
				}
				
				if(!isset($this->scripts_queue[$script]) === 0){
					$this->scripts_queue[$script]		= static::$scripts->create( $this )->set_ID($script);
					return $this->scripts_queue[$script]; // return empty setting if not exist
				}
				
				return $this->scripts_queue[$script];
			}
			$this->scripts_queue[$script]		= static::$scripts->create( $this )->set_ID($script);
			return $this->scripts_queue[$script]; // return empty setting if not exist
		}
		
		public function set_path(string $path) {
			$this->path	= $path;
		}
		public function get_path( $suffix = ''): string {
			if ( $this->path ) { // if path is set, use it
				$path	= $this->path;
			} else if ( $this != $this->get_parent() ) { // if there's a parent, go a step higher
				$path	= $this->get_parent()->get_path();
			} else { // nothing set? use fallback-path
				$path	= trailingslashit( dirname( __FILE__ ) );
			}
			
			$this->path	= $path;
			
			return $path . $suffix;
		}
		
		public function set_url(string $url) {
			$this->url	= $url;
		}
		public function get_url( $suffix = ''): string {
			if ( $this->url ) { // if url is set, use it
				$url	= $this->url;
			} else if ( $this != $this->get_parent() ) { // if there's a parent, go a step higher
				$url	= $this->get_parent()->get_url();
			}
			
			$this->url  = $url;
			
			return $this->url . $suffix;
		}
		public function get_path_core($suffix = ''): string{
			return self::$path_core.$suffix;
		}
		public function get_url_core($suffix = ''): string{
			return self::$url_core.$suffix;
		}
		public function get_current_url() {
			return ( isset( $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http' ) . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
		}
		
		public function get_current_path() {
			return $_SERVER[ 'REQUEST_URI' ];
		}
		public function is_valid_url(string $url): bool{
			return filter_var($url, FILTER_VALIDATE_URL);
		}
		
		public function acp_style( $hook = false ) {
			if ( !$hook || $hook == 'sv-100_page_' . $this->get_module_name() ) {
				wp_enqueue_style($this->get_prefix(), $this->get_url_core('assets/admin.css'));
				ob_start();
				include_once($this->get_path_core('assets/admin_inline.css'));
				$css = ob_get_clean();
				wp_add_inline_style($this->get_prefix(), $css);
			}
		}
		
		public function add_section( $object ) {
			if ( is_object( $object ) && !empty( $object->get_section_type() ) ) { // @todo: remove this line once sv_bb_dashboard is upgraded
				$this->sections[ $object->get_prefix() ] = array(
					'object'	=> $object,
					'type'		=> $this->section_types[ $object->get_section_type() ],
				);
				
				return $object;
			} else {
				return $this; // @todo Notification forS SV Notices that the section_type is missing.
			}
		}
		
		public function get_sections(): array {
			return $this->sections;
		}
		public function get_sections_sorted_by_title(): array {
			$sections = array();
			
			if ( ! empty( $this->sections ) ) {
				foreach($this->sections as $section){
					$sections[$section['object']->get_section_title()] = $section;
				}
				
				ksort($sections);
			}
			
			return $sections;
		}
		
		public function get_section_template_path(): string {
			return $this->section_template_path;
		}
		
		public function set_section_template_path( string $path ) {
			$this->section_template_path = $path;
			
			return $this;
		}
		public function has_section_template_path(): bool{
			return (strlen($this->get_section_template_path()) > 0 && file_exists($this->get_section_template_path())) ? true : false;
		}
		
		public function set_section_title( string $title ) {
			$this->section_title = $title;
			
			return $this;
		}
		
		public function get_section_title(): string {
			return $this->section_title ? $this->section_title : __( 'No Title defined.', 'sv_core' );
		}
		
		public function set_section_desc( string $desc ) {
			$this->section_desc = $desc;
			
			return $this;
		}
		
		public function get_section_desc(): string {
			return $this->section_desc ? $this->section_desc : __( 'No description defined.', 'sv_core' );
		}
		public function set_section_privacy( string $section_privacy ) {
			$this->section_privacy = $section_privacy;
			
			return $this;
		}
		
		public function get_section_privacy(): string {
			return $this->section_privacy ? $this->section_privacy : __( 'No privacy statement defined.', 'sv_core' );
		}
		
		public function set_section_type( string $type ) {
			$this->section_type = $type;
			
			return $this;
		}
		
		public function get_section_type(): string {
			return $this->section_type;
		}
		public function load_page( string $custom_about_path = '' ) {
			$this->get_root()->acp_style();
			
			require_once( $this->get_path_core( 'backend/tpl/header.php' ) );
			require_once( strlen( $custom_about_path ) > 0 ? $custom_about_path : $this->get_path_core( 'backend/tpl/about.php' ) );
			
			if(defined('WP_DEBUG') && WP_DEBUG === true) {
				require_once( $this->get_path_core( 'backend/tpl/core_docs.php' ) );
			}
			
			$this->load_section_html();
			
			require_once( $this->get_path_core( 'backend/tpl/legal.php' ) );
			require_once( $this->get_path_core( 'backend/tpl/footer.php' ) );
		}
		
		public function load_section_menu() {
			foreach ( $this->get_sections_sorted_by_title() as $section ) {
				$section_name = $section['object']->get_prefix();
				echo '<div data-target="#section_' . $section_name . '" class="sv_admin_menu_item section_' . $section[ 'object' ]->get_section_type() . '"><h4>' .  $section[ 'object' ]->get_section_title() . '</h4><span>' . $section[ 'object' ]->get_section_desc() . '</span></div>';
			}
		}
		
		public function load_section_html() {
			foreach( $this->get_sections_sorted_by_title() as $section ) {
				$section_name = $section['object']->get_prefix();
				require( $this->get_path_core( 'backend/tpl/section_' . $section[ 'object' ]->get_section_type() . '.php' ) );
			}
		}
		
		public function plugin_action_links( $actions ) {
			$links						= array(
				'settings'				=> '<a href="admin.php?page=' . $this->get_root()->get_prefix() . '">'.__('Settings', 'sv_core').'</a>',
				'straightvisions'		=> '<a href="https://straightvisions.com" target="_blank">straightvisions GmbH</a>',
			);
			
			$actions			        = array_merge( $links, $actions );
			
			return $actions;
		}
	}
