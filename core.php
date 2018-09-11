<?php

namespace sv_core;

if(!class_exists('\sv_core\core')) {
	
	require_once('abstract.php');
	
	class core extends sv_abstract
	{
		public static $notices = false;
		public static $settings = false;
		public static $widgets = false;
		
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
			require_once('notices/init.php');
			static::$notices = new notices;
			
			require_once('settings/init.php');
			static::$settings = new settings;
			
			require_once('widgets/init.php');
			static::$widgets = new widgets;

			if(file_exists($path.'lib/modules/modules.php')) {
				$this->modules->init();
			}
		}
	}
}