<?php

namespace sv_core;

class widgets extends sv_abstract{
	private $ID									= false;
	private $title								= false;
	private $description						= false;
	private $settings							= false;
	public $core								= false;

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
	}
	public function get_ID(){
		return $this->ID;
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
	public function set_settings($settings){
		$this->settings							= $settings;
	}
	public function get_settings(){
		return $this->settings;
	}
	public function set_template_path($path){
		$this->template_path					= $path;
	}
	public function get_template_path(){
		return $this->template_path;
	}
	public function get_template($instance){
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
		$widget_class = new class($this) extends \WP_Widget{
			private static $widget = false;
			
			public function __construct($widget=false){
				if($widget){
					static::$widget = $widget;
				}
				
				parent::__construct(
					static::$widget->get_ID(),			// Base ID
					static::$widget->get_title(),		// Name
					array(
						'description' => static::$widget->get_description()
					)
				);
			}
			public function form($instance){
				if (static::$widget) {
					foreach (static::$widget->get_settings() as $setting) {
						echo $setting->run_type()->widget((isset($instance[$setting->get_ID()]) ? $instance[$setting->get_ID()] : ''), $this);
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
				$title = apply_filters( 'widget_title', $instance['title'] );
				
				echo $args['before_widget'];
				if ( ! empty( $title ) ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}
				static::$widget->get_template($instance);
				echo $args['after_widget'];
			}
		};
		
		add_action('widgets_init', function ($widget_class) use ($widget_class) {
			register_widget(get_class($widget_class));
		});
	}
}