<?php

namespace sv_core;

class info extends sv_abstract{
	/**
	 * @desc			initialize
	 * @author			Matthias Reuter
	 * @since			1.0
	 * @ignore
	 */
	public function __construct($ID=false){
	
	}
	/**
	 * @desc			Load's requested libraries dynamicly
	 * @param	string	$name library-name
	 * @return			class object of the requested library
	 * @author			Matthias Reuter
	 * @since			1.0
	 * @ignore
	 */
	public function __get(string $name){
		if($this->get_path_lib_core($this->get_module_name().'/modules/'.$name.'.php', true)){ // look for class file in modules directory
			if(!class_exists( __NAMESPACE__.'\\'.$name)) {
				require_once($this->get_path_lib_core($this->get_module_name() . '/modules/' . $name . '.php'));
			}
			
			$class_name							= __NAMESPACE__.'\\'.$name;
			
			$this->$name						= new $class_name();
			$this->$name->set_root($this->get_root());
			$this->$name->set_parent($this);
			
			return $this->$name;
		}else{
			throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_path_lib_core($this->get_module_name().'/modules/'.$name.'.php').')');
		}
	}
	public function init(){
		add_action('admin_menu', array($this, 'menu'), 10);
	}
	public function menu(){
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
}