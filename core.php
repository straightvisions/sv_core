<?php

namespace sv_core;

if(!class_exists('core')) {
	
	require_once('abstract.php');
	
	class core extends sv_abstract
	{
		public static $notices = false;
		
		/**
		 * @desc            initialize
		 * @author            Matthias Reuter
		 * @since            1.0
		 * @ignore
		 */
		public function __construct()
		{
		
		}
		
		public function setup_core()
		{
			require_once('notices/init.php');
			static::$notices = new notices;
			
			$this->modules->init();
		}
	}
}