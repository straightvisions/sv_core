<?php
	
	namespace sv_core;
	
	class about extends info{
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent){
		
		}
		protected function init(){
			add_submenu_page(
				$this->get_prefix().'menu',														// parent slug
				__('About', $this->get_module_name()),											// page title
				__('About', $this->get_module_name()),											// menu title
				'manage_options',																// capability
				$this->get_prefix().'menu',														// menu slug
				function(){ require_once($this->get_path('lib/tpl/backend.php')); }		// callable function
			);
		}
	}