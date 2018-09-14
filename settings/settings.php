<?php

namespace sv_core;

class settings extends sv_abstract{
	// properties
	private $parent								= false;
	private $ID									= false;
	private $section							= false;
	private $section_group						= false;
	private $section_name						= false;
	private $section_description				= false;
	private $type								= false;
	private $title								= false;
	private $description						= false;
	private $options							= array('No Options defined!');
	private $callback							= false;
	private $filter								= false;
	private $prefix								= 'sv_';
	protected static $new							= array();
	
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
		$new									= new static();
		
		$new->prefix							= $parent->get_prefix().'_';
		$new->set_root($parent->get_root());
		$new->set_parent($parent);
		
		return $new;
	}
	public function set_ID($ID){
		$this->ID								= $ID;
	}
	public function get_ID(){
		return $this->ID;
	}
	public function set_section($section){
		$this->section							= $section;
	}
	public function get_section(){
		if(!$this->section){
			return $this->get_parent()->get_module_name();
		}else{
			return $this->section;
		}
	}
	public function set_section_group($section_group){
		$this->section_group							= $section_group;
	}
	public function get_section_group(){
		if(!$this->section_group){
			return $this->get_section();
		}else{
			return $this->section_group;
		}
	}
	public function set_section_name($section_name){
		$this->section_name						= $section_name;
	}
	public function get_section_name(){
		if(!$this->section_name){
			return $this->get_section();
		}else{
			return $this->section_name;
		}
	}
	public function set_section_description($description){
		$this->section_description						= $description;
	}
	public function get_section_description(){
		return $this->section_description;
	}
	/*
	 * 	@param: $source		set a type for form field
	 */
	public function load_type($type){
		$type		= $this->type				= 'setting_'.$type;
		
		if(is_object($this->$type)){
			$this->$type						= $this->$type->create($this->get_parent());
			$this->$type->set_root($this->get_root());
			$this->$type->set_parent($this);
			
			static::init_wp_setting($this->$type);
			
			return true;
		}else{
			// @todo: proper error notice
			return false;
		}
	}
	public function get_type(){
		return $this->type;
	}
	public function run_type(){
		$type				= $this->get_type();
		
		return $this->$type;
	}
	public function get_form_field(){
		return $this->run_type()->default();
	}
	public function print_form_field(){
		echo $this->get_form_field();
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
	public function set_options($options){
		$this->options						= $options;
	}
	public function get_options(){
		return $this->options;
	}
	public function get_data(){
		// similar to get_form_field
	}
	public function set_callback(array $callback){
		$this->callback							= $callback;
	}
	public function get_callback(){
		return $this->callback;
	}
	public function run_callback($setting){
		if($this->callback) {
			if (method_exists($this->callback[0], $this->callback[1])) {
				$class = $this->callback[0];
				$method = $this->callback[1];
				
				return $class->$method($setting);
			} else {
				// @todo: proper error notice
				return false;
			}
		}
	}
	public function set_filter($filter){
		$this->filter							= $filter;
	}
	public function get_filter(){
		return $this->filter;
	}
	public static function get_module_settings_form($module){
		ob_start();
		echo '<form method="post" action="options.php" enctype="multipart/form-data">';
		\settings_fields($module->get_module_name()); // $option_group from register_settings()
		\do_settings_sections($module->get_module_name()); // $page from add_settings_section()
		\submit_button();
		echo '</form>';
		$form			= ob_get_contents();
		ob_end_clean();
		return $form;
	}
	public function section_callback(){
		echo '<p>'.$this->get_section_description().'</p>';
	}
	
	/* methods for inheritance */
	public function default(){
		if($this->get_parent()->get_callback()){
			return $this->get_parent()->run_callback($this);
		}else{
			return $this->form();
		}
	}
	private static function init_wp_setting($setting){
		$section								= $setting->get_parent()->get_section();
		$section_group							= $setting->get_parent()->get_section_group();
		$section_name							= $setting->get_parent()->get_section_name();
		
		\add_settings_section(
			$section_group,											// $id, String for use in the 'id' attribute of tags.
			$section_name,											// $title, Title of the section.
			array($setting->get_parent(), 'section_callback'),	// $callback, Function that fills the section with the desired content. The function should echo its output.
			$section											// $page, the menu page on which to display this section
		);
		
		\add_settings_field(
			$setting->get_parent()->get_prefix().$setting->get_parent()->get_ID(),											// $id, Slug-name to identify the field. Used in the 'id' attribute of tags.
			$setting->get_parent()->get_title(),								// $title, Formatted title of the field. Shown as the label for the field during output.
			array($setting->get_parent(), 'print_form_field'),					// $callback, Function that fills the field with the desired form inputs. The function should echo its output.
			$section,											// $page, The slug-name of the settings page on which to show the section (general, reading, writing, ...).
			$section_group,											// $section, The slug-name of the section of the settings page in which to show the box.
			array(														// $args, Extra arguments used when outputting the field.
				'description'					=> $setting->get_parent()->get_description(),
				'setting_id'					=> $setting->get_parent()->get_prefix().$setting->get_parent()->get_ID()
			)
		);
		
		\register_setting(
			$section,											// $option_group, A settings group name.
			$setting->get_parent()->get_prefix().$setting->get_parent()->get_ID()			// $option_name, The name of an option to sanitize and save.
		);
	}
}