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

			if(file_exists($path.'lib/modules/modules.php')) {
				$this->modules->init();
			}
		}
	}
}