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
				add_action('admin_menu', array($this, 'build_sections'), 100);
				
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
				'straightvisions',
				'',
				$this->get_url_lib_core('assets/logo_icon.png'),
				2
			);
			
			add_submenu_page(
				'straightvisions',										// parent slug
				'Info',														// page title
				'Info',														// menu title
				'manage_options',														// capability
				'straightvisions',										// menu slug
				function(){
					$this->load_page($this->get_path_lib_core('info/backend/tpl/about.php'));
				}	// callable function
			);
		}
		public function build_sections(){
			foreach($this->get_instances() as $name => $instance){
				$this->get_root()->add_section($instance, $this->get_path_lib_core('info/backend/tpl/instance.php'));
				
				add_submenu_page(
					'straightvisions',										// parent slug
					$instance->get_section_title(),														// page title
					$instance->get_section_title(),														// menu title
					'manage_options',														// capability
					$instance->get_prefix(),										// menu slug
					function() use($instance){
						$instance->load_page($instance->get_path_lib_core('backend/tpl/about.php'));
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
		public function load_page(string $path){
			if(file_exists($path)){
				$this->get_root()->acp_style();
				
				require_once($path);
			}else{
				// @todo: trigger notice
			}
		}
	}
}