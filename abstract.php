<?php
	namespace sv_core;
	
	abstract class sv_abstract {
		const version_core					= 5000;
		
		protected $name						= false;
		protected $module_name				= false;
		protected $basename					= false;
		protected $path						= false;
		protected $url						= false;
		protected $version					= false;
		private $parent						= false;
		private $root						= false;
		protected $s						= array(); // settings object array
		protected $m						= array(); // metabox object array
		protected static $wpdb				= false;
		private static $instances			= array();
		protected static $instances_active	= array();
		protected $loaded			        = array();
		protected static $active_core       = false;
		protected static $path_core			= false;
		protected static $url_core			= false;
		protected $sections					= array();
		protected $section_types			= array();
		protected $section_template_path	= '';
		protected $section_title			= false;
		protected $section_order			= false;
		protected $section_desc				= false;
		protected $section_privacy			= false;
		protected $section_type				= '';
		protected $scripts_queue			= array();
		protected static $expert_mode		= false;
		public static $breakpoints			= false;

		protected $module_css_cache					= false; // default false, set true in a module to opt in for CSS Caching
		protected $module_css_cache_invalidated	= true;
		
		/**
		 * @desc			initialize plugin
		 * @author			Matthias Bathke
		 * @since			1.0
		 * @ignore
		 */
		public function load_translation() {
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
			// @deprecated: move module files into own subdirectory
			// @todo: remove
			if ( is_file($this->get_path( 'lib/modules/'.$name . '.php' )) ) {
				require_once( $this->get_path( 'lib/modules/'.$name . '.php' ) );
				
				$class_name	    = $this->get_root()->get_name() . '\\' . $name;
				$this->$name    = new $class_name();
				$this->$name->set_root( $this->get_root() );
				$this->$name->set_parent( $this );
				
				return $this->$name;
			}
			
			if ( is_file($this->get_root()->get_path( 'lib/modules/'.$name . '/'.$name . '.php' )) ) {
				require_once( $this->get_root()->get_path( 'lib/modules/'.$name . '/'.$name . '.php' ) );
				
				$class_name	    = $this->get_root()->get_name() . '\\' . $name;
				$this->$name    = new $class_name();
				$this->$name->set_root( $this->get_root() );
				$this->$name->set_parent( $this );
				$this->$name->set_path( $this->get_root()->get_path( 'lib/modules/'.$name . '/' ) );
				$this->$name->set_url( $this->get_root()->get_url( 'lib/modules/'.$name . '/' ) );
				
				return $this->$name;
			}
			
			throw new \Exception( __( 'Class', 'sv_core' ) . ' '
                . $name
                . ' ' . __( 'could not be loaded (tried to load class-file', 'sv_core' ) . ' '
                . $this->get_path()
                . 'lib/modules/'.$name . '.php)' );
		}

		public function wordpress_version_check(string $min_version = '5.0.0'){
            $wp_version = '1.0.0'; // declare default even it's re-declared by an included file
			// Get unmodified $wp_version.
			include ABSPATH . WPINC . '/version.php';
			// Strip '-src' from the version string. Messes up version_compare().
            $wp_version = str_replace( '-src', '', $wp_version );
			if ( version_compare( $wp_version, $min_version, '<' ) ) {
				add_action( 'admin_notices', array($this, 'wordpress_version_notice') );
			}
		}

		/*
		 * @todo include a return in the parent function when the child function should have a return
		 */
		public function wordpress_version_notice(){
			// extend in children when needed.

		}

		public function set_is_expert_mode(bool $yes = true){
			static::$expert_mode = $yes;
		}

		public function get_is_expert_mode(): bool {
			return static::$expert_mode;
		}

		public function set_parent( $parent ) {
			$this->parent = $parent;

			return $this;
		}
		
		public function get_parent() {
			return $this->parent ? $this->parent : $this;
		}
		
		public function get_root() {
			return $this->root ? $this->root : $this;
		}
		
		public function set_root( $root ) {
			$this->root = $root;

			return $this;
		}

		// Returns the current core object, that is in use
		public function get_active_core(): core {
			return $this::$active_core;
		}
		
		public function get_previous_version(): int{
			return intval( get_option( $this->get_prefix( 'version') ) );
		}
		
		public function get_version( bool $formatted = false ): string{
		    $output = '0';

			if ( defined( get_called_class() . '::version' ) ) {
				if ( $formatted === true ) {
                    $output =  number_format( get_called_class()::version, 0, ',', '.' );
				} else {
                    $output =  get_called_class()::version;
				}
			} else {
				if ( $formatted ) {
					$output = __( 'not defined', 'sv_core' );
				}
			}

			return $output;
		}
		
		public function get_version_core_match( bool $formatted = false ): string {
            $output = '0';

			if ( defined( get_called_class() . '::version_core_match' ) ) {
				if ( $formatted === true ) {
                    $output = number_format( get_called_class()::version_core_match, 0, ',', '.' );
				} else {
                    $output = get_called_class()::version_core_match;
				}
			} else {
				if ( $formatted ) {
                    $output = __( 'not defined', 'sv_core' );
				}
			}

            return $output;
		}
		
		public function get_version_core( bool $formatted = false ): string {
		    $output = '0';

			if ( defined( get_called_class() . '::version_core' ) ) {
				if ( $formatted === true) {
                    $output = number_format( get_called_class()::version_core, 0, ',', '.' );
				} else {
                    $output = get_called_class()::version_core;
				}
			} else {
				if ( $formatted ) {
                    $output = __( 'not defined', 'sv_core' );
				}
			}

			return $output;
		}
		
		public function find_parent( $class_name, bool $qualified = false ){
		    /*
		     * @todo shouldn't we return an empty object or better NULL as default here?
		     * same for find_parent_by_name or other object returning functions
		     * a change to null or empty object could be a breaking change
		     */
		    $output = false;

			if ( $this->get_parent() != $this->get_root() ) {
				if ( $qualified === false ) {
					if ( $this->get_parent()->get_module_name() == $class_name ) {
                        $output = $this->get_parent();
					} else {
                        $output = $this->get_parent()->find_parent( $class_name, $qualified );
					}
				} else {
					if ( get_class( $this->get_parent() ) == $class_name ) {
                        $output = $this->get_parent();
					} else {
                        $output = $this->get_parent()->find_parent( $class_name, $qualified );
					}
				}
			}
			
			return $output;
		}
		
		public function find_parent_by_name( string $name ) {
		    $output = false;

			if ( $this->get_parent() != $this->get_root() ) {
				if ( $this->get_parent()->get_name() == $name ) {
					$output = $this->get_parent();
				} else {
                    $output = $this->get_parent()->find_parent_by_name( $name );
				}
			}
			
			return $output;
		}
		
		public function is_theme_instance(): bool{
			return get_class( $this->get_root() ) == 'sv100\init' ? true : false;
		}

		protected function setup( string $name, $file ): bool {
		    $output = false;
			// make sure to init only once
			//$namespace = strstr(get_class($this->get_root()), '\\', true);
			//if(isset($this->get_instances()[$namespace])){
			if( isset($this->get_instances()[$name]) === false) {
				$this->set_section_types();
				
                global $wpdb;

                self::$wpdb     = $wpdb;
                $this->name     = $name;

                if ( $this->is_theme_instance() === true ) {
                    $this->path = trailingslashit(get_template_directory());
                    $this->url  = trailingslashit(get_template_directory_uri());
                } else {
                    $this->path = plugin_dir_path($file);
                    $this->url  = trailingslashit(plugins_url('', $this->get_path() . $this->get_name()));
                    $this->plugins_loaded();
                }

                if (!isset(self::$instances[$name])) {
                    $output = $this->setup_core($this->path, $name);
                }else{
                    $output = true;
                }

                self::$instances[$name] = $this;
            }

            return $output;
		}
		
		protected function set_section_types(): sv_abstract {
			$this->section_types = array(
				'settings'	=> __( 'Configuration &amp; Settings', 'sv_core' ),
				'tools'		=> __( 'Helpful tools &amp; helper', 'sv_core' )
			);
			
			return $this;
		}
		
		public static function get_instances(): array {
			return self::$instances;
		}

		public static function get_instance(string $name) {
		    $output = false;

		    if( isset(self::$instances[$name]) === true ){
		        $output = self::$instances[$name];
            }

			return $output;
		}
		
		public static function get_instances_active(): array {
			return self::$instances_active;
		}
		
		public static function is_instance_active( string $name ): bool{
			return isset( self::$instances_active[ $name ] );
		}

		/*
		 * @todo function name is not explicit
		 */
		public function plugins_loaded() {
			if( $this->is_theme_instance() === false ) {
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

		public function get_name(): string {
		    $output = 'sv';

			if ( $this->name ) { // if name is set, use it
				$output = $this->name;
			} else if ( $this != $this->get_parent() ) { // if there's a parent, go a step higher
				$output =  $this->get_parent()->get_name() . '_' . $this->get_module_name();
			}

			return $output;
		}
		
		public function get_module_name() {
			if(!$this->module_name){
				$this->module_name = ( new \ReflectionClass( get_called_class() ) )->getShortName();
			}
			return $this->module_name;
		}
		
		public function get_prefix( string $append = '' ) {
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
		
		public function get_relative_prefix( string $append = '' ): string {
			if( strlen( $append ) > 0 ) {
				$append = '_' . $append;
			}

			$prefix = str_replace( $this->get_root()->get_name(), 'sv_common', $this->get_name() );
			
			return  $prefix . $append;
		}
		
		public function get_settings(): array {
			if(count($this->s) === 0){
				$this->load_settings();
			}
			
			return $this->s;
		}
		
		public function get_setting(string $setting = ''): settings {
			if( strlen($setting) === 0 || isset($this->s[$setting]) === false ){
                // create empty setting if not set
                $this->s[$setting] = static::$settings->create( $this )->set_ID($setting);
			}

			return $this->s[$setting];
		}

		public function get_breakpoints(): array {
			if(!self::$breakpoints){
				self::$breakpoints	= apply_filters($this->get_root()->get_prefix('breakpoints'), array( // number = min width
					'mobile'						=> 0,		// mobile first!
					'mobile_landscape'				=> 0,
					'tablet'						=> 768,
					'tablet_landscape'				=> 992,
					'tablet_pro'					=> 1024,
					'tablet_pro_landscape'			=> 1366,
					'desktop'						=> 1600,
				));
			}

			return self::$breakpoints;
		}

		public function get_metabox(): metabox {
			if( isset($this->m[$this->get_prefix()]) === false ){
				// create empty setting if not set
				$this->m[$this->get_prefix()] = static::$metabox->create( $this );
			}

			return $this->m[$this->get_prefix()];
		}

		public function get_script(string $script = ''): scripts {
            if( strlen($script) === 0 || isset($this->scripts_queue[$script]) === false ){
                // create empty setting if not set
                $this->scripts_queue[$script] = static::$scripts->create( $this )->set_ID($script);
            }

            return $this->scripts_queue[$script];
		}
		public function get_scripts(): array {
			return $this->scripts_queue;
		}
		
		public function set_path(string $path) {
			$this->path	= $path;

			return $this;
		}

		public function get_path( string $suffix = ''): string {
			if ( $this->path ) {
				$path	= $this->path;
			} else if ( $this != $this->get_parent() ) { // if there's a parent, go a step higher
				$path	= $this->get_parent()->get_path();
			} else { // fallback
				$path	= trailingslashit( dirname( __FILE__ ) );
			}
			
			$this->set_path($path);

			return $this->path . $suffix;
			//return apply_filters('get_path', $this->path . $suffix, $path, $suffix, $this->get_prefix(), $this);
		}
		
		public function set_url(string $url) {
			$this->url	= $url;

			return $this;
		}

		public function get_url( string $suffix = ''): string {
		    $url = '';
			if ( $this->url ) { // if url is set, use it
				$url	= $this->url;
			} else if ( $this != $this->get_parent() ) { // if there's a parent, go a step higher
				$url	= $this->get_parent()->get_url();
			}
			
			$this->set_url($url);

			return $this->url . $suffix;
			//return apply_filters('get_url', $this->url . $suffix, $this->url, $suffix, $this->get_prefix(), $this);
		}

		public function get_path_core(string $suffix = ''): string{
			return self::$path_core . $suffix;
		}

		public function get_url_core(string $suffix = ''): string{
			return self::$url_core . $suffix;
		}

		public function get_current_url(): string {
            $protocol = 'http';

            // check if set and not 'off' for support ISAPI under IIS
            if( isset($_SERVER['HTTPS']) === true && $_SERVER['HTTPS'] !== 'off' ){
                $protocol = 'https';
            }

			return $protocol . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
		}
		
		public function get_current_path(): string {
			return $_SERVER[ 'REQUEST_URI' ];
		}

		public function is_valid_url(string $url): bool{
		    $output = true;
		    if( filter_var($url, FILTER_VALIDATE_URL) === false ){
		        $output = false;
            }
			return $output;
		}
		
		public function acp_style( bool $hook = false ) {
			if ( !$hook || $hook == 'sv-100_page_' . $this->get_module_name() ) {
				if(is_file($this->get_path_core('../assets/admin_inline.css'))) { // file exists only when core_plugin is loaded, so if only theme is loaded, don't load this asset
					wp_enqueue_style($this->get_prefix(), $this->get_url_core('../assets/admin.css'), array('wp-editor'), filemtime($this->get_path_core('../assets/admin.css')));
					ob_start();
					require_once($this->get_path_core('../assets/admin_inline.css'));
					$css = ob_get_clean();
					wp_add_inline_style($this->get_prefix(), $css);
				}
			}
		}
		
		public function add_section( $object ) {
		    $output = null;
            // @todo: remove the following line once sv_bb_dashboard is upgraded
			if ( is_object( $object ) && !empty( $object->get_section_type() ) ) {
				$this->sections[ $object->get_prefix() ] = array(
					'object'	=> $object,
					'type'		=> $this->section_types[ $object->get_section_type() ],
				);
				
				$output = $object;
			} else {
				$output = $this; // @todo Notification forS SV Notices that the section_type is missing.
			}

			return $output;
		}
		
		public function get_sections(): array {
			return $this->sections;
		}

		public function get_section_single(string $section_title = '') {
			$output  	= false; // should be null
			$sections 	= $this->get_sections_sorted_by_prefix();

			foreach($sections as $key => $section){
				if($key == $section_title){
					$output = $section;
					break;
				}
			}

			return $output;
		}

		public function get_sections_sorted_by_prefix(): array {
			$sections = array();

			if ( empty( $this->sections ) === false ) {
				foreach($this->sections as $section){
					$sections[$section['object']->get_prefix()] = $section;
				}

				ksort($sections);
			}

			return $sections;
		}

		public function get_sections_sorted_by_title(): array {
			$sections = array();
			
			if ( empty( $this->sections ) === false ) {
				foreach($this->sections as $section){
					$sections[$section['object']->get_section_title()] = $section;
				}
				
				ksort($sections);
			}
			return $sections;
		}

		public function get_sections_sorted_by_order(): array {
			$sections	= array();

			if ( empty( $this->sections ) === false ) {
				foreach($this->sections as $section){
					$order_num = $section['object']->get_section_order();

					if($order_num === 0){
						$order_num = 777; // move them to the bottom
					}

					while( isset($sections[$order_num]) ){
						$order_num++;
					}

					$sections[$order_num] = $section;
				}

			}

			ksort($sections);

			return $sections;
		}
		
		public function get_section_template_path(): string {
			return $this->section_template_path;
		}
		
		public function set_section_template_path( string $path = 'lib/tpl/settings/init.php' ) {
			$this->section_template_path = $this->get_path($path);

			return $this;
		}

		public function has_section_template_path(): bool{
		    $output = false;

		    if( strlen($this->get_section_template_path()) > 0 && is_file($this->get_section_template_path()) ){
		        $output = true;
		    }

			return $output;
		}
		
		public function set_section_title( string $title ) {
			$this->section_title = $title;
			
			return $this;
		}
		
		public function get_section_title(): string {
			return $this->section_title ? $this->section_title : __( 'No Title defined.', 'sv_core' );
		}

		public function set_section_order( int $num ) {
			$this->section_order = $num;

			return $this;
		}

		public function get_section_order(): int {
			return $this->section_order ? $this->section_order : 0;
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
            $path = $this->get_path_core( 'backend/tpl/about.php' );

			$this->get_root()->acp_style();
			
			require_once( $this->get_path_core( 'backend/tpl/header.php' ) );

            if( strlen( $custom_about_path ) > 0 ){
                $path = $custom_about_path;
            }
            
            require_once( $path );
			
			// $this->load_section_html();
			
			require_once( $this->get_path_core( 'backend/tpl/legal.php' ) );
			require_once( $this->get_path_core( 'backend/tpl/footer.php' ) );
		}
		
		public function load_section_menu() {
			foreach ( $this->get_sections_sorted_by_order() as $section ) {
				$section_name = $section['object']->get_prefix();
				echo '<div data-sv_admin_menu_target="#section_'
                    . $section_name
                    . '" class="sv_admin_menu_item section_'
                    . $section[ 'object' ]->get_section_type()
                    . '"><h4>'
                    .  $section[ 'object' ]->get_section_title()
                    . '</h4><span>' . $section[ 'object' ]->get_section_desc()
                    . '</span></div>';
			}
		}
		
		public function load_section_html() {
			foreach( $this->get_sections_sorted_by_title() as $section ) {
				$section_name = $section['object']->get_prefix(); // var will be used in included file
				$path = $this->get_path_core( 'backend/tpl/section_' . $section[ 'object' ]->get_section_type() . '.php' );
				require( $path );
			}
		}
		
		public function plugin_action_links( array $actions ): array {
			$links						= array(
				'settings'				=> '<a href="admin.php?page=' . $this->get_root()->get_prefix() . '">'
                                            .__('Settings', 'sv_core')
                                            .'</a>',
				'straightvisions'		=> '<a href="https://straightvisions.com" target="_blank">straightvisions GmbH</a>',
			);
			
			$actions			        = array_merge( $links, $actions );
			
			return $actions;
		}
		public function has_block_frontend(string $block_name): bool{
			if( ! is_admin() ) {
				$post = apply_filters('sv_core_has_block_frontend_queried_object', get_queried_object());

				if(!$post || get_class($post) != 'WP_Post'){
					return false;
				}

				if ( !$this->has_block( $block_name, $post->ID )) {
					return false;
				}
			}
			return true;
		}
		public function has_block( string $block_name, int $id = 0 ): bool{
			$id = (!$id) ? get_the_ID() : $id;

			if(has_block($block_name, $id)){
				return true;
			}
			if($this->has_reusable_block($block_name, $id)){
				return true;
			}
			return false;
		}
		public function has_reusable_block( string $block_name, int $id = 0 ): bool{
			$id = (!$id) ? get_the_ID() : $id;

			if( $id ){
				if ( has_block( 'block', $id ) ){
					// Check reusable blocks
					$content = get_post_field( 'post_content', $id );
					$blocks = parse_blocks( $content );

					if ( ! is_array( $blocks ) || empty( $blocks ) ) {
						return false;
					}

					foreach ( $blocks as $block ) {
						$block = $this->flatten_inner_blocks($block); // search for child blocks

						if ( $block['blockName'] === 'core/block' && ! empty( $block['attrs']['ref'] ) ) {
							if( has_block( $block_name, $block['attrs']['ref'] ) ){
								return true;
							}
						}

						if ( $block['blockName'] === 'core/block' && ! empty( $block['attrs']['ref'] ) ) {
							if( has_block( $block_name, $block['attrs']['ref'] ) ){
								return true;
							}
						}
					}

				}
			}

			return false;
		}
		protected function flatten_inner_blocks(array $block): array{
			if(isset($block['innerBlocks'])){
				$inner_blocks = $block['innerBlocks'];
				unset($block['innerBlocks']);
				foreach($inner_blocks as $inner_block) {
					$block = array_merge($block, $this->flatten_inner_blocks($inner_block));
				}

			}

			return $block;
		}

		public function get_css_cache_active(){
			return $this->module_css_cache;
		}
		public function set_css_cache_active(bool $active = true){
			$this->module_css_cache = $active;

			return $this;
		}

		public function get_css_cache_invalidated(){
			return $this->module_css_cache_invalidated;
		}
		public function set_css_cache_invalidated(bool $invalidated = true){
			$this->module_css_cache_invalidated = $invalidated;

			return $this;
		}
	}
