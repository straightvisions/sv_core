<?php
namespace sv_core;

class scripts extends sv_abstract {
	private static $scripts						= array();
	private static $scripts_enqueued			= array();
	private static $scripts_active				= array();

	// properties
	private $is_enqueued						= false;
	private $ID									= false;
	private $type								= 'css';
	private $script_url							= '';
	private $script_path						= '';
	private $deps								= array();
	private $no_prefix							= false;
	private static $is_loaded					= array(
		'css'									=> array(),
		'js'									=> array()
	);
	private $is_backend                         = false;
	private $is_gutenberg						= false;
	private $is_external						= false;
	private $is_required						= false;
	private $is_consent_required				= false;
	private $custom_attributes					= '';

	// CSS specific
	private $media								= 'all';
	private $inline								= false;
	
	// JS specific
	private $localized							= array();

	public function __construct() {

	}

	public function init(){
		// Section Info
		$this->set_section_title( __('Scripts', 'sv_core') );
		$this->set_section_desc( __( 'Override Scripts Loading.', 'sv_core' ) );
		$this->set_section_type( 'settings' );

		add_action( 'init', array( $this, 'register_scripts' ), 10 );
		
		add_action( 'wp_footer', array( $this, 'wp_footer' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 99999);
		add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_scripts' ));

		// Loads Settings
		if(!is_admin()) {
			add_action( 'wp_footer', array( $this, 'load_settings' ), 1 );
		}else{
			add_action( 'init', array( $this, 'load_settings' ));
		}

		$this->load_settings();
	}

	public function load_settings() {
		if(count($this->get_scripts()) > 0) {
			if($this->get_is_expert_mode()) {
				$this->get_root()->add_section( $this );
			}
			
			$this->s[ 'disable_all_css' ] = $this->get_parent()::$settings->create( $this )
																		   ->set_ID( 'disable_all_css' )
																		   ->set_title( __('Disable all CSS per Default', 'sv_core') )
																		   ->set_description( __('CSS enqueued will be disabled by default - you may override this later down below.', 'sv_core') )
																		   ->load_type( 'checkbox' );
			
			$this->s[ 'disable_all_js' ] = $this->get_parent()::$settings->create( $this )
																		  ->set_ID( 'disable_all_js' )
																		  ->set_title( __('Disable all JS per Default', 'sv_core') )
																		  ->set_description( __('JS enqueued will be disabled by default - you may override this later down below.', 'sv_core') )
																		  ->load_type( 'checkbox' );
			
			foreach ( $this->get_scripts() as $script ) {
				$this->s[ $script->get_UID() ] = $this->get_parent()::$settings->create( $this )
																		   ->set_ID( $script->get_UID() )
																		   ->set_default_value( 'default' )
																		   ->set_title( '<div class="fab fa-' . ( $script->get_type() == 'css' ? 'css3' : 'js' ) . '" style="font-size:24px;margin-right:12px;"></div>' . $script->get_handle() )
																		   ->set_description( $script->get_url() )
																		   ->load_type( 'select' )
																		   ->set_disabled( $script->get_is_required() ? true : false );
				
				if(
					($this->s[ 'disable_all_css' ]->run_type()->get_data() == 1 && $script->get_type() == 'css') ||
					($this->s[ 'disable_all_js' ]->run_type()->get_data() == 1 && $script->get_type() == 'js')
				){
					
					$default_label											=  __( 'Disabled', 'sv_core' );
				}else{
					$default_label											= $script->get_inline() ? __( 'Inline', 'sv_core' ) : __( 'Attached', 'sv_core' );
				}
				
				
				$options = array(
					'default'  => __( 'Default', 'sv_core' ) . ': ' . $default_label,
					'inline'   => __( 'Inline', 'sv_core' ),
					'attached' => __( 'Attached', 'sv_core' ),
					'disable'  => __( 'Disabled', 'sv_core' )
				);
				
				$this->s[ $script->get_UID() ]->set_options( $options );

			}
		}
	}

	public function get_scripts(): array {
		return isset( self::$scripts[ $this->get_root()->get_name() ] ) ? self::$scripts[ $this->get_root()->get_name() ] : array();
	}
	public function get_enqueued_scripts(): array{
		return self::$scripts_enqueued;
	}
	public function get_active_scripts(): array {
		return self::$scripts_active;
	}

	public function wp_footer() {
		// we need to register an attached style to be allowed to add inline styles with WP function
		wp_register_style('sv_core_init_style', $this->get_url_core('frontend/css/style.css'));
		
		foreach ( $this->get_scripts() as $script ) {
			if(!$script->get_is_backend()) {
				$this->add_script($script);
			}
		}
		
		// inline styles are printed
		wp_enqueue_style('sv_core_init_style');

		ob_start();
		// now remove the attached style
		add_action('wp_print_footer_scripts', function(){
			$html = ob_get_contents();
			ob_end_clean();
			$html = preg_replace("/<link(.*)sv_core_init_style-css(.*)\/>/", '', $html);

			$html = $this->replace_type_attr($html);


			echo $html;
		});
	}
	public function replace_type_attr($input){
		foreach ( $this->get_scripts() as $script ) {
			if($script->get_consent_required()) {
				$input = str_replace(
					array(
						"type='text/javascript' src='".$script->get_url(),
						'type="text/javascript" src=\''.$script->get_url()
					),
					'type="text/plain"'.$script->get_custom_attributes().' src=\''.$script->get_url(),
					$input);
			}
		}

		return $input;
	}
	public function admin_scripts($hook){
		if ( is_admin() && ( strpos( $hook,'straightvisions' ) !== false || strpos( $hook,'appearance_page_sv100' ) !== false ) ) {
			foreach ( $this->get_scripts() as $script ) {
				if ( $script->get_is_backend() ) {
					$this->add_script( $script );
				}
			}
		}
	}
	public function gutenberg_scripts(){
		wp_register_style('sv_core_gutenberg_style', $this->get_url_core('backend/css/gutenberg_style.css'));
		
		foreach ( $this->get_scripts() as $script ) {
			if ( $script->get_is_gutenberg() ) {
				$this->add_script( $script );
			}
		}
		
		wp_enqueue_style('sv_core_gutenberg_style');
	}
	public function register_scripts(){
		foreach ( $this->get_scripts() as $script ) {
			if($script->get_type() == 'js'){
				wp_register_script(
					$script->get_handle(),
					$script->get_url(),
					$script->get_deps(),
					($this->is_external() ? md5($script->get_url()) : filemtime($script->get_path()))
				);
			}else{
				wp_register_style(
					$script->get_handle(),
					$script->get_url(),
					$script->get_deps(),
					($this->is_external() ? md5($script->get_url()) : filemtime($script->get_path()))
				);
			}
		}
	}

	private function check_for_enqueue(scripts $script): bool{
		if($script->get_is_loaded()){ // always load scripts once only
			return false;
		}
		
		if($script->get_is_required()){ // always load required scripts
			return true;
		}
		
		if($this->s[$script->get_UID()]->run_type()->get_data() == 'disable'){ // don't load disabled scripts
			return false;
		}
		
		if( // if script has no user load settings
			$this->s[$script->get_UID()]->run_type()->get_data() == '' ||
			$this->s[$script->get_UID()]->run_type()->get_data() == 'default'
		){
			if( // make sure they are not globally disabled
				($this->s[ 'disable_all_css' ]->run_type()->get_data() == 1 && $script->get_type() == 'css') ||
				($this->s[ 'disable_all_js' ]->run_type()->get_data() == 1 && $script->get_type() == 'js')
			){
				return false;
			}
		}
		
		return true;
	}
	private function add_script( scripts $script ) {
		// run all registered scripts

		// check if script is enqueued
		if($script->get_is_enqueued()) {
			// check is script isn't loaded already and not disabled
			if ($this->check_for_enqueue($script)) {
				// set as loaded
				$script->set_is_loaded();
				
				// CSS or JS
				switch ($script->get_type()) {
					case 'css':
						// check if inline per settings (higher prio) or per parameter (lower prio)
						if (
							(
								$this->s[$script->get_UID()]->run_type()->get_data() === 'inline'
								|| (
									$this->s[$script->get_UID()]->run_type()->get_data() === 'default'
									&& $script->get_inline()
								)
							)
							&& ! $script->get_is_backend()
						) {
							if ( $script->get_is_gutenberg() ) {
								ob_start();
								require_once( $script->get_path() );
								$css		= ob_get_clean();
								wp_add_inline_style( 'sv_core_gutenberg_style', $css );
							} else {
								ob_start();
								require_once($script->get_path());
								$css		= ob_get_clean();
								wp_add_inline_style( 'sv_core_init_style', $css );
							}
						} else {
							wp_enqueue_style(
								$script->get_handle(),                          // script handle
								$script->get_url(),                            // script url
								$script->get_deps(),                            // script dependencies
								( $this->is_external() ? md5( $script->get_url() ) : filemtime( $script->get_path() ) ),     // script version, generated by last filechange time
								$script->get_media()                            // The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
							);
						}
						break;
					case 'js':
						wp_enqueue_script(
							$script->get_handle(),                              // script handle
							$script->get_url(),                              // script url
							$script->get_deps(),                                // script dependencies
							($this->is_external() ? md5($script->get_url()) : filemtime($script->get_path())),         // script version, generated by last filechange time
							true                                       // print in footer
						);

						if ($script->is_localized()) {
							wp_localize_script($script->get_handle(), $script->get_uid(), $script->get_localized());
						}
						break;
				}
				self::$scripts_active[]						= $script;
			}
		}
	}

	// OBJECT METHODS
	public static function create( $parent ) {
		$new									= new static();

		$new->prefix							= $parent->get_prefix() . '_';
		$new->set_root( $parent->get_root() );
		$new->set_parent( $parent );

		self::$scripts[ $parent->get_root()->get_name() ][]						= $new;

		return $new;
	}
	public function set_consent_required( bool $is_consent_required = true ): scripts {
		$this->is_consent_required						= $is_consent_required;

		return $this;
	}
	public function get_consent_required(): bool {
		return $this->is_consent_required;
	}
	public function set_custom_attributes( string $string = '' ): scripts {
		$this->custom_attributes						= $string;

		return $this;
	}
	public function get_custom_attributes(): string {
		return $this->custom_attributes;
	}
	public function set_is_required( bool $is_required = true ): scripts {
		$this->is_required						= $is_required;
		
		return $this;
	}
	public function get_is_required(): bool {
		return $this->is_required;
	}
	public function set_is_no_prefix( bool $no_prefix = true ): scripts {
		$this->no_prefix						= $no_prefix;

		return $this;
	}
	public function get_is_no_prefix(): bool {
		return $this->no_prefix;
	}

	public function set_is_enqueued( bool $is_enqueued = true ): scripts {
		$this->is_enqueued						= $is_enqueued;

		return $this;
	}
	public function get_is_enqueued(): bool {
		return $this->is_enqueued;
	}

	public function get_handle(): string {
		if ( $this->get_is_no_prefix() ) {
			return $this->get_ID();
		} else {
			return $this->get_prefix( $this->get_ID() );
		}
	}
	public function get_UID(): string {
		if ( $this->get_is_no_prefix() ) {
			return $this->get_ID();
		} else {
			return $this->get_type() . '_' . $this->get_prefix( $this->get_ID() );
		}
	}

	public function set_ID( string $ID ): scripts {
		$this->ID								= $ID;

		return $this;
	}
	public function get_ID(): string {
		return $this->ID;
	}
	
	public function set_localized(array $settings): scripts{
		$this->localized						= $settings;
		
		return $this;
	}
	public function get_localized(): array{
		return $this->localized;
	}
	public function is_localized(): bool{
		return boolval(count($this->get_localized()));
	}

	public function set_is_loaded(): scripts {
		static::$is_loaded[$this->get_type()][$this->get_handle()]	= true;

		return $this;
	}

	public function get_is_loaded(): bool {
		return isset(static::$is_loaded[$this->get_type()][$this->get_handle()]);
	}

	public function set_type( string $type ): scripts {
		$this->type								= $type;

		return $this;
	}

	public function get_type(): string {
		return $this->type;
	}

	public function set_is_backend(): scripts {
		$this->is_backend						= true;

		return $this;
	}
	public function get_is_backend(): bool {
		return $this->is_backend;
	}
	
	public function set_is_gutenberg(): scripts {
		$this->is_gutenberg						= true;
		
		return $this;
	}
	public function get_is_gutenberg(): bool {
		return $this->is_gutenberg;
	}
	
	public function set_path(string $path, bool $full = false, string $url = ''): scripts {
		if($this->is_valid_url($path)){
			$this->script_url					= $path;
			$this->is_external					= true;
			
			return $this;
		}
		
		if(!$full){
			$this->script_url					= $this->get_parent()->get_url($path);
			if(file_exists($this->script_path = $this->get_parent()->get_parent()->get_path($path))){
				$this->script_path				= $this->get_parent()->get_parent()->get_path($path);
			}elseif(file_exists($this->get_parent()->get_path($path))){
				$this->script_path				= $this->get_parent()->get_path($path);
			}
		}else{
			$this->script_path					= $path;
			$this->script_url					= $url;
		}
		
		return $this;
	}
	public function get_path(string $suffix = ''): string {
		return $this->script_path;
	}
	public function get_url(string $suffix = ''): string {
		return $this->script_url;
	}
	public function is_external(): bool{
		return $this->is_external;
	}

	public function set_deps( array $deps ): scripts {
		$this->deps								= $deps;

		return $this;
	}

	public function get_deps(): array {
		return $this->deps;
	}

	// CSS specific
	public function set_media( string $media ): scripts{
		$this->media							= $media;

		return $this;
	}

	public function get_media(): string {
		return $this->media;
	}

	public function set_inline( bool $inline ): scripts {
		$this->inline							= $inline;

		return $this;
	}

	public function get_inline(): bool {
		return $this->inline;
	}
}