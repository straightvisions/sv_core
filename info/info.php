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
		add_action('admin_menu', array($this, 'menu'), 1);
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
		$root = $this->get_root();
		if($this->get_root()->get_path_lib_core($this->get_module_name().'/modules/'.$name.'.php')){ // look for class file in modules directory
			require_once($this->get_root()->get_path_lib_core($this->get_module_name().'/modules/'.$name.'.php'));
			$classname							= __NAMESPACE__.'\\'.$name;
			
			$this->$name						= new $classname($this);
			$this->$name->set_root($root);
			$this->$name->set_parent($this);
			
			return $this->$name;
		}else{
			throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_root()->get_path_lib_core($this->get_module_name().'/modules/'.$name.'.php').')');
		}
	}
	public function menu(){
		add_menu_page(
			__('SV Info',$this->get_name()),
			__('SV Info',$this->get_name()),
			'manage_options',
			$this->get_prefix().'menu',
			'',
			$this->get_root()->get_url_lib_section('core','assets','logo_icon.png'),
			2
		);
		
		//$this->about->init();
	}
}