<?php

namespace sv_core;

class widgets extends sv_abstract{
	private $ID									= false;
	private $title								= false;
	private $description						= false;
	private $settings							= false;
	public $core								= false;
	public static $scripts_loaded		        = false;
	private $widget_class_name					= false;

	/**
	 * @desc			initialize
	 * @author			Matthias Bathke
	 * @since			1.0
	 * @ignore
	 */
	public function __construct(){

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
		if($this->get_path_lib_core($this->get_module_name().'/modules/'.$name.'.php')){ // look for class file in modules directory
			require_once($this->get_path_lib_core($this->get_module_name().'/modules/'.$name.'.php'));
			$classname							= __NAMESPACE__.'\\'.$name;
			
			$this->$name						= new $classname($this);
			return $this->$name;
		}else{
			throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_path_lib_core($this->get_module_name().'/modules/'.$name.'.php').')');
		}
	}
	public function set_ID($ID){
		$this->ID								= $ID;
		return $this;
	}
	public function get_ID(){
		return $this->ID;
	}
	public function set_title($title){
		$this->title							= $title;
		return $this;
	}
	public function get_title(){
		return $this->title;
	}
	public function set_description($description){
		$this->description						= $description;
		return $this;
	}
	public function get_description(){
		return $this->description;
	}
	public function set_widget_settings($settings){
		$this->settings							= $settings;
		return $this;
	}
	public function get_widget_settings(): array{
		return $this->settings;
	}
	public function set_template_path($path){
		$this->template_path					= $path;
		return $this;
	}
	public function get_template_path(){
		return $this->template_path;
	}
	public function get_template(){
		include($this->get_template_path());
	}

	// OBJECT METHODS
	public function create($parent){
		$new									= new self();
		$new->core								= isset($parent->core) ? $parent->core : $parent;
		
		$new->init();
		return $new;
	}
	public function load(){
		add_action('widgets_init', function () {
			register_widget($this->get_widget_class_name());
		});
	}
	public function set_widget_class_name(string $name){
		$this->widget_class_name				= $name;
		return $this;
	}
	public function get_widget_class_name(): string{
		return $this->widget_class_name;
	}
}

class sv_widget extends \WP_Widget{
	protected static $widget		= false;
	
	public function set_class($widget){
		static::$widget		 = $widget;
	}
	public function get_class(){
		return static::$widget;
	}
	public function __construct($widget=false){
		if($widget){
			$this->set_class($widget);
		}elseif($this->get_class()){
			parent::__construct(
				$this->get_class()->get_ID(),			// Base ID
				$this->get_class()->get_title(),		// Name
				array(
					'description' => $this->get_class()->get_description()
				)
			);
		}else{
			die('class not defined.'); // @todo: proper error handling
		}
	}
	public function form( $instance ) {
		if ($this->get_class()) {
			if(!$this->get_class()::$scripts_loaded) {
				$this->get_class()->get_parent()->admin_enqueue_scripts('toplevel_page_straightvisions');
				$this->get_class()->get_root()->acp_style();
				$this->get_class()::$scripts_loaded		= true;
			}
			
			foreach ($this->get_class()->get_widget_settings() as $setting) {
				echo $setting->run_type()->widget( ( isset ( $instance[ $setting->get_ID() ] ) ? $instance[ $setting->get_ID() ] : $setting->run_type()->get_default_value() ), $this );
			}
		}
	}
	public function update($new_instance, $old_instance){
		$instance = array();
		
		foreach ($new_instance as $name => $field) {
			$instance[$name] = (!empty($new_instance[$name])) ? strip_tags($new_instance[$name]) : '';
		}
		
		return $instance;
	}
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', isset($instance['title']) ? $instance['title'] : '' );
		
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$this->get_class()->get_template();
		echo $args['after_widget'];
	}
}