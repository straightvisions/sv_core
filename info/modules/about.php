<?php
	
	namespace sv_core;
	
	class about extends info{
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct(){
		
		}
		public function init(){
			add_action('admin_menu', array($this, 'menu'), 1);
		}
		public function menu(){
			add_submenu_page(
				$this->get_parent()->get_relative_prefix(),										// parent slug
				__('About', $this->get_module_name()),											// page title
				__('About', $this->get_module_name()),											// menu title
				'manage_options',																// capability
				$this->get_parent()->get_relative_prefix(),										// menu slug
				function(){ require_once($this->get_path_lib_core('info/tpl/backend_about.php')); }		// callable function
			);
		}
	}