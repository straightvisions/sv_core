<?php

namespace sv_core;

if(!class_exists('\sv_core\core')) {
	
	require_once('abstract.php');
	
	class core extends sv_abstract
	{
		public static $notices		= false;
		public static $settings		= false;
		public static $widgets		= false;
		public static $info			= false;
		public static $initialized	= false;
		
		/**
		 * @desc            initialize
		 * @author            Matthias Reuter
		 * @since            1.0
		 * @ignore
		 */
		public function __construct()
		{
		
		}
		
		public function setup_core($path)
		{
			// these modules are available in all instances and should be initialized once only.
			if(!static::$initialized) {
				self::$path_core			= trailingslashit(dirname(__FILE__));
				self::$url_core				= trailingslashit(get_site_url()).str_replace(ABSPATH,'',self::$path_core);
				
				require_once('notices/notices.php');
				static::$notices = new notices;
				static::$notices->set_root($this->get_root());
				static::$notices->set_parent($this);
				
				require_once('settings/settings.php');
				static::$settings = new settings;
				static::$settings->set_root($this->get_root());
				static::$settings->set_parent($this);
				
				require_once('widgets/widgets.php');
				static::$widgets = new widgets;
				static::$widgets->set_root($this->get_root());
				static::$widgets->set_parent($this);
				
				require_once('info/info.php');
				static::$info = new info;
				static::$info->set_root($this->get_root());
				static::$info->set_parent($this);
				static::$info->init();
				
				add_action('admin_menu', array($this, 'menu'), 1);
				
				static::$initialized		= true;
			}

			if(file_exists($path.'lib/modules/modules.php')) {
				$this->modules->init();
			}
		}
		public function menu(){
			add_menu_page(
				__('straightvisions', $this->get_root()->get_prefix()),
				__('straightvisions', $this->get_root()->get_prefix()),
				'manage_options',
				$this->get_root()->get_prefix(),
				'',
				$this->get_url_lib_core('assets/logo_icon.png'),
				2
			);
			
			add_submenu_page(
				$this->get_root()->get_prefix(),										// parent slug
				'Info',														// page title
				'Info',														// menu title
				'manage_options',														// capability
				$this->get_root()->get_prefix(),										// menu slug
				array($this,'info_instance_tpl')	// callable function
			);
		}
		public function info_instance_tpl(){
			require_once($this->get_path_lib_core('info/tpl/backend_info_instance.php'));
		}
	}
}