<?php

namespace sv_core;

if ( !class_exists( '\sv_core\core' ) ) {
	require_once('abstract.php');
	
	class core extends sv_abstract {
		public static $log                  = false;
		public static $notices				= false;
		public static $settings				= false;
		public static $curl	    			= false;
		public static $widgets				= false;
		public static $info					= false;
		public static $metabox				= false;
		public $ajax_fragmented_requests	= false;
		public static $initialized			= false;
		
		/**
		 * @desc        initialize
		 * @author      Matthias Bathke
		 * @since       1.0
		 * @ignore
		 */
		public function __construct() {
		
		}
		
		public function setup_core( $path ) {
			// these modules are available in all instances and should be initialized once only.
			if ( !static::$initialized ) {
				self::$path_core			= trailingslashit( dirname( __FILE__ ) );
				self::$url_core				= trailingslashit( get_site_url() ) . str_replace( ABSPATH,'', self::$path_core );

				require_once( 'settings/settings.php' );

				static::$settings = new settings;
				static::$settings->set_root( $this->get_root() );
				static::$settings->set_parent( $this );

				require_once( 'log/log.php' );

				static::$log = new log;
				static::$log->set_root( $this->get_root() );
				static::$log->set_parent( $this );
				
				require_once( 'notices/notices.php' );
				
				static::$notices = new notices;
				static::$notices->set_root( $this->get_root() );
				static::$notices->set_parent( $this );
				
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
				
				add_action( 'admin_menu', array( $this, 'menu' ), 1 );
				add_action( 'admin_menu', array( $this , 'build_sections' ), 100 );

				add_filter( 'plugin_action_links_' . plugin_basename( $path ) . '/' . plugin_basename( $path ) . '.php', array( $this, 'plugin_action_links' ), 10, 5 );

				add_action( 'shutdown', array( $this, 'update_routine' ) );

				static::$initialized = true;
			}

			if( file_exists( $path . 'lib/modules/modules.php' ) ) {
				$this->modules->init();
			}
		}

		public function menu() {
			add_menu_page(
				__( 'straightvisions', $this->get_root()->get_prefix() ),
				__( 'straightvisions', $this->get_root()->get_prefix() ),
				'manage_options',
				'straightvisions',
				'',
				$this->get_url_lib_core( 'assets/logo_icon.png' ),
				100
			);
		}
	}
}