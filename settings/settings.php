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
		private $placeholder						= false;
		private $callback							= array();
		private $filter								= false;
		private $loop								= false; // true = unlimited (dynamic) entries, int = amount of entries, false = no loop (default).
		private $prefix								= 'sv_';
		protected static $new							= array();
		
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
			if($this->get_path_lib_core('settings/modules/'.$name.'.php',true)){ // look for class file in modules directory
				require_once($this->get_path_lib_core('settings/modules/'.$name.'.php'));
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
		public function set_ID(string $ID): settings{
			$this->ID								= $ID;

			return $this;
		}
		public function get_ID(): string{
			return $this->ID;
		}
		public function set_section(string $section): settings{
			$this->section							= $section;

			return $this;
		}
		public function get_section(): string{
			if(!$this->section){
				return $this->get_parent()->get_prefix();
			}else{
				return $this->section;
			}
		}
		public function set_section_group(string $section_group): settings{
			$this->section_group							= $section_group;

			return $this;
		}
		public function get_section_group(): string{
			if(!$this->section_group){
				return $this->get_section();
			}else{
				return $this->section_group;
			}
		}
		public function set_section_name(string $section_name): settings{
			$this->section_name						= $section_name;

			return $this;
		}
		public function get_section_name(): string{
			if(!$this->section_name){
				return $this->get_parent()->get_section_title();
			}else{
				return $this->section_name;
			}
		}
		public function set_section_description(string $description): settings{
			$this->section_description						= $description;

			return $this;
		}
		public function get_section_description(): string{
			if(!$this->section_description){
				return $this->get_parent()->get_section_desc();
			}else{
				return $this->section_description;
			}
		}
		/*
		 * 	@param: $source		set a type for form field
		 */
		public function load_type(string $type){
			$type		= $this->type				= 'setting_'.$type;
			
			if(is_object($this->$type)){
				$this->$type						= $this->$type->create($this->get_parent());
				$this->$type->set_root($this->get_root());
				$this->$type->set_parent($this);
				
				$this->init_wp_setting($this->$type);
			}else{
				// @todo: proper error notice
			}
			return $this;
		}
		public function get_type(){
			return $this->type;
		}
		public function run_type(){
			$type				= $this->get_type();
			
			return $this->$type;
		}
		public function get_form_field(): string{
			return $this->run_type()->default();
		}
		public function print_form_field(){
			echo $this->get_form_field();
		}
		public function set_title(string $title): settings{
			$this->title							= $title;

			return $this;
		}
		public function get_title(): string{
			return $this->title;
		}
		public function set_description(string $description){
			$this->description						= $description;

			return $this;
		}
		public function get_description(): string{
			return $this->description;
		}
		public function set_options(array $options): settings{
			$this->options						= $options;

			return $this;
		}
		public function get_options(): array{
			return $this->options;
		}
		public function set_placeholder(string $placeholder){
			$this->placeholder						= $placeholder;

			return $this;
		}
		public function get_placeholder(): string{
			return $this->placeholder;
		}
		public function get_data(){
			return get_option($this->get_field_id());
		}
		public function set_callback(array $callback): settings{
			$this->callback							= $callback;

			return $this;
		}
		public function get_callback(): array{
			return $this->callback;
		}
		public function run_callback($setting){
			if(count($this->get_callback()) == 2) {
				if (method_exists($this->get_callback()[0], $this->get_callback()[1])) {
					$class = $this->get_callback()[0];
					$method = $this->get_callback()[1];
					
					return $class->$method($setting);
				} else {
					// @todo: proper error notice
					return false;
				}
			}
		}
		public function set_filter(array $filter): settings{
			$this->filter							= $filter;

			return $this;
		}
		public function get_filter(): array{
			return $this->filter;
		}
		public function set_loop(int $loop): settings{
			$this->loop								= $loop;

			return $this;
		}
		public function get_loop(): int{
			return $this->loop;
		}
		public static function get_module_settings_form($module): string{
			ob_start();
			echo '<form method="post" action="options.php" enctype="multipart/form-data">';
			\settings_fields($module->get_name()); // $option_group from register_settings()
			\do_settings_sections($module->get_name()); // $page from add_settings_section()
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
		public function default(): string{
			if($this->get_parent()->get_callback()){
				return $this->get_parent()->run_callback($this);
			}else{
				return $this->form();
			}
		}
		private function init_wp_setting($setting){
			if(is_admin() && did_action('admin_init')) {
				$section = $this->get_section();
				$section_group = $setting->get_parent()->get_section_group();
				$section_name = $setting->get_parent()->get_section_name();
				
				\add_settings_section(
					$section_group,                                            // $id, String for use in the 'id' attribute of tags.
					$section_name,                                            // $title, Title of the section.
					array($setting->get_parent(), 'section_callback'),    // $callback, Function that fills the section with the desired content. The function should echo its output.
					$section                                            // $page, the menu page on which to display this section
				);
				
				\add_settings_field(
					$setting->get_field_id(),                                            // $id, Slug-name to identify the field. Used in the 'id' attribute of tags.
					$setting->get_parent()->get_title(),                                // $title, Formatted title of the field. Shown as the label for the field during output.
					array($setting->get_parent(), 'print_form_field'),                    // $callback, Function that fills the field with the desired form inputs. The function should echo its output.
					$section,                                            // $page, The slug-name of the settings page on which to show the section (general, reading, writing, ...).
					$section_group,                                            // $section, The slug-name of the section of the settings page in which to show the box.
					array(                                                        // $args, Extra arguments used when outputting the field.
						'description' => $setting->get_parent()->get_description(),
						'setting_id' => $setting->get_field_id()
					)
				);
				
				\register_setting(
					$section,                                            // $option_group, A settings group name.
					$setting->get_field_id()            // $option_name, The name of an option to sanitize and save.
				);
			}
		}
		protected function get_field_id(): string{
			return $this->get_parent()->get_prefix($this->get_parent()->get_ID());
		}
		public function widget(string $value, $object): string{
			return '<p>'.$this->html(
					$object->get_field_id($this->get_parent()->get_ID()),
					$this->get_parent()->get_title(),
					$this->get_parent()->get_description(),
					$object->get_field_name($this->get_parent()->get_ID()),
					$value
				).'</p>';
		}
		public function form(bool $title=false): string{
			return '<div>'.$this->html(
					$this->get_field_id(),
					$title ? $this->get_parent()->get_title() : '',
					$this->get_parent()->get_description(),
					$this->get_field_id(),
					get_option($this->get_field_id()),
					$this->get_parent()->get_placeholder()
				).'</div>';
		}
	}