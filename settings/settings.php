<?php

namespace sv_core;

class settings extends sv_abstract{
	// properties
	private $parent								= false;
	private $ID									= false;
	private $section							= false;
	private $section_group						= '';
	private $section_name						= '';
	private $section_description				= '';
	private $type								= false;
	private $title								= '';
	private $description						= '';
	private $options							= array('No Options defined!');
	private $placeholder						= '';
	private $maxlength						    = false;
	private $minlength						    = false;
	private $max	    					    = false;
	private $min    						    = false;
	private $required  						    = false;
	private $disabled  						    = false;
	private $callback							= array();
	private $filter								= array();
	private $prefix								= 'sv_';
	private $data								= false;
	private $default_value						= false;
	private $radio_style                        = 'radio';
	private $code_editor						= '';
	private $is_label						    = false;
	protected static $new						= array();

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
		if(file_exists($this->get_path_core('settings/modules/'.$name.'.php'))){ // look for class file in modules directory
			require_once($this->get_path_core('settings/modules/'.$name.'.php'));
			$class_name							= __NAMESPACE__.'\\'.$name;

			$this->$name						= new $class_name($this);
			$this->$name->set_root($this->get_root());
			$this->$name->set_parent($this);

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
    public function set_is_label(bool $check): settings{
        $this->is_label							= $check;

        return $this;
    }
    public function get_is_label(): bool{
        return $this->is_label;
    }
	public function set_default_value($default_value): settings{
		$this->default_value							= $default_value;

		return $this;
	}
	public function get_default_value(){
		return $this->get_parent()->default_value;
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
		return ( $this->placeholder ? $this->placeholder : $this->title );
	}
	public function set_maxlength( int $maxlength ) {
		$this->maxlength						= $maxlength;

		return $this;
	}
	public function get_maxlength(): int {
		return $this->maxlength;
	}
	public function set_minlength( int $minlength ) {
		$this->minlength						= 'pattern=".{' . $minlength .',}" title="' . __( "You need at least ", $this->get_name() ) . $minlength . ' characters."'; //@todo Add translation for this message

		return $this;
	}
	public function get_minlength(): string {
		return $this->minlength;
	}
	public function set_max( string $max ) {
		$this->max						= $max;

		return $this;
	}
	public function get_max(): string {
		return $this->max;
	}
	public function set_min( string $min ) {
		$this->min						= $min;

		return $this;
	}
	public function get_min(): string {
		return $this->min;
	}
	public function set_required( bool $required ) {
		if( $required == true ) {
			$this->required						= 'required';
		} else {
			$this->required						= '';
		}

		return $this;
	}
	public function get_required(): string {
		return $this->required;
	}
	public function set_disabled( bool $disabled ) {
		if( $disabled == true ) {
			$this->disabled					= 'disabled';
		} else {
			$this->disabled						= '';
		}

		return $this;
	}
	public function get_disabled(): string {
		return $this->disabled;
	}

	public function get_radio_style(): string {
		return $this->radio_style;
	}

	public function set_radio_style( string $style ) {
		$this->radio_style            = $style;

		return $this;
	}
	
	public function get_code_editor(): string {
		return $this->code_editor;
	}
	
	public function set_code_editor( string $code_editor ) {
		$this->code_editor           = $code_editor;
		
		return $this;
	}

	public function get_data(){
		if($this->data){
			return $this->data;
		}else {
			return (get_option($this->get_field_id()) !== false) ? get_option($this->get_field_id()) : $this->get_default_value();
		}
	}
	// set data value from external source
	public function set_data($data){
		$this->data		= $data;

		return $this;
	}
	public function save_option(): bool{
		return update_option($this->get_field_id(), $this->get_data());

		return $this;
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
	public static function get_module_settings_form($module): string{
		ob_start();
		echo '<form id="' . $module->get_name() . '" method="post" action="options.php" enctype="multipart/form-data">';
		\settings_fields($module->get_name()); // $option_group from register_settings()
		if($module->get_section_template_path()){
			require_once($module->get_section_template_path());
		}else {
			\do_settings_sections($module->get_name()); // $page from add_settings_section()
		}
		\submit_button();
		echo '</form>';
		$form			= ob_get_contents();
		ob_end_clean();
		return $form;
	}
	public function section_callback(){
		echo '<div class="sv_section_description">'.$this->get_section_description().'</div>';
	}

	/* methods for inheritance */
	public function default(bool $title = false): string{
		if($this->get_parent()->get_callback()){
			return $this->get_parent()->run_callback($this);
		}else{
			return $this->form($title);
		}
	}
	private function init_wp_setting($setting){
		if(is_admin()) {
			require_once(ABSPATH . '/wp-admin/includes/template.php');

			$section = $this->get_section();
			$section_group = $setting->get_parent()->get_section_group();
			$section_name = '';

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
				$setting->get_field_id(),            // $option_name, The name of an option to sanitize and save.
				array('sanitize_callback'	=> array($setting,'field_callback'))
			);
		}
	}
	public function field_callback($input){
		return $input;
	}
	protected function get_field_id(): string{
		return $this->get_parent()->get_prefix($this->get_parent()->get_ID());
	}
	public function widget(string $value, $object): string{
		return $this->html(
				$object->get_field_id($this->get_parent()->get_ID()),
				$this->get_parent()->get_title(),
				$this->get_parent()->get_description(),
				$object->get_field_name($this->get_parent()->get_ID()),
				$value,
				$this->get_parent()->get_required(),
				$this->get_parent()->get_disabled(),
				$this->get_parent()->get_placeholder(),
				$this->get_parent()->get_maxlength(),
				$this->get_parent()->get_minlength(),
				$this->get_parent()->get_max(),
				$this->get_parent()->get_min(),
				$this->get_parent()->get_radio_style(),
				$this->get_parent()->get_code_editor()
			);
	}
	public function form(bool $title=false): string{
		return '<div class="sv_setting">'.$this->html(
				$this->get_field_id(),
				$this->get_parent()->get_title(),
				$this->get_parent()->get_description(),
				$this->get_field_id(),
				$this->get_data(),
				$this->get_parent()->get_required(),
				$this->get_parent()->get_disabled(),
				$this->get_parent()->get_placeholder(),
				$this->get_parent()->get_maxlength(),
				$this->get_parent()->get_minlength(),
				$this->get_parent()->get_max(),
				$this->get_parent()->get_min(),
				$this->get_parent()->get_radio_style(),
				$this->get_parent()->get_code_editor()
			).'</div>';
	}

	public function delete() :settings {
		if ( $this->get_type() == 'setting_upload' ) {
			wp_delete_attachment( $this->run_type()->get_data(), true );
		}

		delete_option( $this->run_type()->get_field_id() );

		return $this;
	}
}