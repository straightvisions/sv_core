<?php

namespace sv_core;

class info extends sv_abstract{
	/**
	 * @desc			initialize
	 * @author			Matthias Bathke
	 * @since			1.0
	 * @ignore
	 */
	public function __construct($ID=false){
		$this->set_section_title('SV Core');
		$this->set_section_desc('Our core framework is included in all recent plugins and themes by straightvisions.');
		$this->set_section_privacy('<p>
				'.$this->get_section_title().' does not collect or share any data from clients or visitors.<br />
			</p>');
	}
	/**
	 * @desc			Load's requested libraries dynamicly
	 * @param	string	$name library-name
	 * @return			class object of the requested library
	 * @author			Matthias Bathke
	 * @since			1.0
	 * @ignore
	 */
	public function __get(string $name){
		if(file_exists($this->get_path_core($this->get_module_name().'/modules/'.$name.'.php'))){ // look for class file in modules directory
			if(!class_exists( __NAMESPACE__.'\\'.$name)) {
				require_once($this->get_path_core($this->get_module_name() . '/modules/' . $name . '.php'));
			}
			
			$class_name							= __NAMESPACE__.'\\'.$name;
			
			$this->$name						= new $class_name();
			$this->$name->set_root($this->get_root());
			$this->$name->set_parent($this);
			
			return $this->$name;
		}else{
			throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_path_core($this->get_module_name().'/modules/'.$name.'.php').')');
		}
	}
}