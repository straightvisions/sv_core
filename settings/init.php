<?php

namespace sv_core;

class settings extends sv_abstract{
	private $ID									= false;

	/**
	 * @desc			initialize
	 * @author			Matthias Reuter
	 * @since			1.0
	 * @ignore
	 */
	public function __construct(){
	
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
		if(static::get_path_lib_core(static::get_module_name().'/modules/'.$name.'.php')){ // look for class file in modules directory
			require_once(static::get_path_lib_core(static::get_module_name().'/modules/'.$name.'.php'));
			$classname							= __NAMESPACE__.'\\'.$name;
			
			$this->$name						= new $classname($this);
			return $this->$name;
		}else{
			throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.static::get_path_lib_core(static::get_module_name().'/modules/'.$name.'.php').')');
		}
	}
	public function set_ID($ID){
		$this->ID								= $ID;
	}
	public function get_ID(){
		return $this->ID;
	}
	// OBJECT METHODS
	public static function create(){
		$new									= new self();

		return $new;
	}
}