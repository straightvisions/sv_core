<?php

namespace sv_core;

if ( !class_exists( '\sv_core\core' ) ) {
	require_once('abstract.php');
	
	class core extends sv_abstract {
		public static $notices				= false;
		public static $settings				= false;
		public static $curl	    			= false;
		public static $widgets				= false;
		public static $info					= false;
		public static $metabox				= false;
		public static $scripts				= false;
		public $ajax_fragmented_requests	= false;
		public static $initialized			= false;
		
		public function setup_core( $path, $name ) {
			if(
			($this->get_root()->get_version_core_match() == $this->get_version_core())
			|| (defined('WP_DEBUG') && WP_DEBUG === true)
			) {
				parent::$instances_active[ $name ]    = $this;
			}else{
				add_action('admin_init', function() {
					?>
					<div class="update-nag">
						<?php echo 'You need to update to run ' . $name; ?> <br/>
						<?php echo 'SV Core was loaded from <em>' . $this->get_path_core() . '</em>  with version:'; ?>
						<strong><?php echo $this->get_version_core(); ?></strong>, but software requires version
						<strong><?php echo $this->get_root()->get_version_core_match(); ?></strong>
					</div>
					<?php
				});
				return false;
			}
			
			// these modules are available in all instances and should be initialized once only.
			if ( !static::$initialized ) {
				$this->credits();

				self::$path_core			= trailingslashit( dirname( __FILE__ ) );
				self::$url_core				= trailingslashit( get_site_url() ) . str_replace( ABSPATH,'', self::$path_core );

				require_once( 'settings/settings.php' );

				static::$settings = new settings;
				static::$settings->set_root( $this->get_root() );
				static::$settings->set_parent( $this );
				
				require_once( 'curl/curl.php' );

				static::$curl = new curl;
				static::$curl->set_root( $this->get_root() );
				static::$curl->set_parent( $this );
				
				require_once( 'widgets/widgets.php' );

				static::$widgets = new widgets;
				static::$widgets->set_root( $this->get_root() );
				static::$widgets->set_parent( $this );
				
				require_once( 'info/info.php' );

				static::$info = new info;
				static::$info->set_root( $this->get_root() );
				static::$info->set_parent( $this );
				static::$info->init();
				
				require_once( 'metabox/metabox.php' );

				static::$metabox = new metabox;
				static::$metabox->set_root( $this->get_root() );
				static::$metabox->set_parent( $this );
				static::$metabox->init();

				require_once( 'ajax_fragmented_requests/ajax_fragmented_requests.php' );

				$this->ajax_fragmented_requests = new ajax_fragmented_requests;
				$this->ajax_fragmented_requests->set_root( $this->get_root() );
				$this->ajax_fragmented_requests->set_parent( $this );
				$this->ajax_fragmented_requests->init();
				
				add_filter( 'plugin_action_links_' . plugin_basename( $path ) . '/' . plugin_basename( $path ) . '.php', array( $this, 'plugin_action_links' ), 10, 5 );
				add_action( 'shutdown', array( $this, 'update_routine' ) );
			}

			require_once( 'scripts/scripts.php' );

			static::$scripts = new scripts;
			static::$scripts->set_root( $this->get_root() );
			static::$scripts->set_parent( $this );
			static::$scripts->init();

			static::$scripts->create($this)
							->set_ID('admin')
							->set_path($this->get_url_core('assets/admin.js'))
							->set_is_backend()
							->set_is_enqueued()
							->set_type('js')
							->set_deps(array('jquery'))
							->set_is_required();
			
            if( file_exists( $path . 'lib/modules/modules.php' ) ) {
                $this->modules->init();
            }
			
			static::$initialized = true;
            
            $this->init_subcore();
				
			return true;
		}
		public function credits() {
			add_filter('wp_headers', function($headers){ $headers['X-straightvisions'] = 'Website enhanced by straightvisions.com'; return $headers; });
			add_action('wp_footer', function(){ echo "\n\n".'<!-- Website enhanced by straightvisions.com -->'."\n\n"; }, 999999);
			add_filter('rocket_buffer', function($buffer){ return $buffer."\n\n".'<!-- Website enhanced by straightvisions.com -->'."\n\n"; }, 999999);
			define('WP_ROCKET_WHITE_LABEL_FOOTPRINT', true);
		}
	}
}