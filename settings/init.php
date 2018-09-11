<?php

namespace sv_core;

class settings extends sv_abstract{
	// properties
	private $ID									= false;
	private $source								= false;
	private $type								= false;
	private $title								= false;
	private $description						= false;
	private $prefix								= 'sv_';
	
	/**
	 * @desc			initialize
	 * @author			Matthias Reuter
	 * @since			1.0
	 * @ignore
	 */
	public function __construct(){
		add_action( 'wp_ajax_'.$this->get_prefix('ajax'), array($this,'ajax') );
	}
	public function ajax(){
		if(isset($_REQUEST['module'])){
			$module			= $_REQUEST['module'];
			$this->$module->ajax();
		}
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
		if('modules/'.$name.'.php'){ // look for class file in modules directory
			require_once('modules/'.$name.'.php');
			$class_name							= __NAMESPACE__.'\\'.$name;
			
			$this->$name						= new $class_name($this);
			return $this->$name;
		}else{
			throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_module_name().'/modules/'.$name.'.php'.')');
		}
	}
	// OBJECT METHODS
	public static function create($parent){
		$new									= new self();
		$new->prefix							= $parent->get_prefix().'_';
		
		return $new;
	}
	public function set_ID($ID){
		$this->ID								= $ID;
	}
	public function get_ID(){
		return $this->ID;
	}
	/*
	 * 	@param: $source		for loading database setting values, e.g. wp_options, post_meta, user_meta
	 */
	public function set_source($source){
		$this->source						= $source;
	}
	public function get_source(){
		return $this->source;
	}
	/*
	 * 	@param: $source		set a type for form field
	 */
	public function set_type($type){
		$this->type							= $type;
	}
	public function get_type(){
		return $this->type;
	}
	public function set_title($title){
		$this->title							= $title;
	}
	public function get_title(){
		return $this->title;
	}
	public function set_description($description){
		$this->description						= $description;
	}
	public function get_description(){
		return $this->description;
	}
	public function get_form_field($value='',$format,$object){
		$type									= 'setting_'.$this->get_type();
		
		if(is_object($this->$type)){
			// @todo: proper error notice
			return $this->$type->get($value,$format,$object);
		}else{
			return false;
		}
	}
	public function get_value(){
		// similar to get_form_field
	}
}