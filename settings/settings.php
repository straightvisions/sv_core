<?php

namespace sv_core;

class settings extends sv_abstract{
	// properties
	private $parent					= false;
	private $no_prefix				= false;
	private $ID						= false;
	private $section				= false;
	private $section_group			= '';
	private $section_name			= '';
	private $section_description	= '';
	private $type					= false;
	private $title					= '';
	private $description			= '';
	private $options				= array();
	private $placeholder			= '';
	private $maxlength				= false;
	private $minlength				= false;
	private $max	    			= false;
	private $min    				= false;
	private $is_units 				= false;
	private $units					= array( 'px', 'em', 'rem', '%', 'vh', 'vw' );
	private $required  				= false;
	private $disabled  				= false;
	private $callback				= array();
	private $filter					= array();
	private $prefix					= 'sv_';
	private $data					= false;
	private $default_value			= false;
	private $responsive             = false;
	private $radio_style            = 'radio';
	private $code_editor			= '';
	private $is_label				= false;
	protected static $new			= array();

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
		$this->ID								= sanitize_key($ID);

		return $this;
	}
	public function get_ID(): string{
		return sanitize_key($this->ID);
	}
	public function set_is_no_prefix(bool $no = true): settings{
		$this->no_prefix								= $no;
		
		return $this;
	}
	public function get_is_no_prefix(): bool{
		return $this->no_prefix;
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

	// Creates duplicates of the setting for every responsive breakpoint
	public function create_responsive_settings() {
		$breakpoints        = array( 'mobile', 'mobile_landscape', 'tablet', 'tablet_landscape', 'desktop' );
		$ID					= $this->get_ID();
		$title				= $this->get_title();
		$description		= $this->get_description();
		$required			= $this->get_required();
		$disabled			= $this->get_disabled();
		$placeholder		= $this->get_placeholder();
		$maxlength			= intval( $this->get_maxlength() );
		$minlength			= intval( $this->get_minlength() );
		$max				= intval( $this->get_max() );
		$min				= intval( $this->get_min() );
		$radio_style		= $this->get_radio_style();
		$code_editor		= $this->get_code_editor();
		$default_value      = $this->get_default_value();
		$type               = str_replace( 'setting_', '', $this->get_type() );
		$data               = array();

		foreach ( $this->get_breakpoints() as $suffix ) {
			$new_setting = $this->get_parent()
				->get_setting( $ID . '_' . $suffix )
				->set_title( $title )
				->set_description( $description )
				->set_required( $required )
				->set_disabled( $disabled )
				->set_placeholder( $placeholder )
				->set_maxlength( $maxlength )
				->set_minlength( $minlength )
				->set_max( $max )
				->set_min( $min )
				->set_radio_style( $radio_style )
				->set_code_editor( $code_editor )
				->set_default_value( $default_value )
				->load_type( $type );

			// Pushes the new responsive setting in the data array of the original (parent) setting
			$data[ $suffix ] = $new_setting;
		}

		$this->set_data( $data );
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
		}//else{
			// @todo: proper error notice
		//}
		return $this;
	}
	public function get_type(){
		return $this->type;
	}
	public function run_type(){
		$type				= $this->get_type();

		if(isset($this->$type)){
			return $this->$type;
		}else{
			return $this;
		}
	}
	public function get_form_field(): string{
		return $this->default();
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
		return $this->default_value;
	}
	public function set_responsive(bool $check): settings{
		$this->responsive = $check;

		return $this;
	}
	public function get_responsive(): bool {
		return $this->responsive;
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
		if ( count( $this->options ) === 0 ) {
			$this->options = array( __( 'No Options defined!', 'sv_core' ) );
		}
		
		return $this->options;
	}
	public function has_options(): bool{
		if ( count( $this->options ) === 0 ) {
			return false;
		}else{
			return true;
		}
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
		$this->minlength						= 'pattern=".{' . $minlength .',}" title="' . __( 'You need at least', 'sv_core' ) . ' ' . $minlength . ' ' . __( 'characters.', 'sv_core' ) .  '"';

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
	public function set_is_units() {
		$this->is_units = true;

		return $this;
	}
	public function get_is_units(): bool {
		return $this->is_units;
	}
	public function set_units( array $units ) {
		$this->units = $units;

		return $this;
	}
	public function get_units(): array {
		return $this->units;
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
		$data = $this->data;

		if($data !== false && $data !== ''){
			return $data;
		}else {
			return (get_option($this->get_field_id()) !== false && get_option($this->get_field_id()) !== '') ? get_option($this->get_field_id()) : $this->get_default_value();
		}
	}
	// set data value from external source
	public function set_data($data){
		$this->data		= $data;

		return $this;
	}
	public function save_option(): bool{
		return update_option($this->get_field_id(), $this->get_data());
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

	// Helper Methods
	
	// Returns a value in the rgb format, with alpha value
	// Example Output: 255,0,255,1
	function get_rgb( string $val, string $opacity = '1' ): string {
		// Value is a hex color
		if ( preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $val ) && hexdec( $val ) ) {
			list( $r, $g, $b ) = sscanf( $val, "#%02x%02x%02x" );
			
			return $r . ',' . $g . ',' . $b . ','.$opacity;
		}
		
		// Value is a rgb color
		elseif ( preg_match( '/(\d{1,3}),(\d{1,3}),(\d{1,3})/ix', str_replace( ' ', '', $val ) ) ) {
			return str_replace( ' ', '', $val );
		}
		
		// Couldn't detect format
		return $val;
	}
	
	// Returns a value in the hex format
	// Example Output: #ff00ff
	function get_hex( string $val ): string {
		// Value is a hex color
		if ( preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $val ) && hexdec( $val ) ) {
			return $val;
		}
		
		// Value is a rgb color
		elseif ( preg_match( '/(\d{1,3}),(\d{1,3}),(\d{1,3})/ix', str_replace( ' ', '', $val ) ) ) {
			$rgb = explode( ',', str_replace( ' ', '', $val ) );
			
			return sprintf( "#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2] );
		}
		
		// Couldn't detect format
		return $val;
	}

	/* methods for inheritance */
	public function default(bool $title = false): string{
		if($this->get_callback()){
			return $this->run_callback($this);
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
				$setting->get_parent()->get_field_id(),                                            // $id, Slug-name to identify the field. Used in the 'id' attribute of tags.
				$setting->get_parent()->get_title(),                                // $title, Formatted title of the field. Shown as the label for the field during output.
				array($setting->get_parent(), 'print_form_field'),                    // $callback, Function that fills the field with the desired form inputs. The function should echo its output.
				$section,                                            // $page, The slug-name of the settings page on which to show the section (general, reading, writing, ...).
				$section_group,                                            // $section, The slug-name of the section of the settings page in which to show the box.
				array(                                                        // $args, Extra arguments used when outputting the field.
					'description' => $setting->get_parent()->get_description(),
					'setting_id' => $setting->get_parent()->get_field_id()
				)
			);

			\register_setting(
				$section,                                            // $option_group, A settings group name.
				$setting->get_parent()->get_field_id(),            // $option_name, The name of an option to sanitize and save.
				array('sanitize_callback'	=> array($setting,'field_callback'))
			);
		}
	}
	public function field_callback($input){
		return $input;
	}
	public function get_field_id(): string{
		return $this->get_is_no_prefix() ? $this->get_ID() : $this->get_prefix($this->get_ID());
	}
	public function widget(string $value, $object): string{
		$ID					= $object->get_field_id($this->get_parent()->get_ID());
		$title				= $this->get_parent()->get_title();
		$description		= $this->get_parent()->get_description();
		$name				= $object->get_field_name($this->get_parent()->get_ID());

		$required			= $this->get_parent()->get_required();
		$disabled			= $this->get_parent()->get_disabled();
		$placeholder		= $this->get_parent()->get_placeholder();
		$maxlength			= $this->get_parent()->get_maxlength();
		$minlength			= $this->get_parent()->get_minlength();
		$max				= $this->get_parent()->get_max();
		$min				= $this->get_parent()->get_min();
		$radio_style		= $this->get_parent()->get_radio_style();
		$code_editor		= $this->get_parent()->get_code_editor();

		return $this->form($ID,$title,$description,$name,$value,$required,$disabled,$placeholder,$maxlength,$minlength,$max,$min,$radio_style,$code_editor);
	}
	public function form($ID = false, $title = false, $description = false, $name = false, $value = false, $required = false, $disabled = false, $placeholder = false, $maxlength = false, $minlength = false, $max = false, $min = false, $radio_style = false, $code_editor = false): string{
		$ID					= $ID ? $ID : $this->get_field_id();
		$title				= $title ? $title : $this->get_title();
		$description		= $description ? $description : $this->get_description();
		$name				= $name ? $name : $this->get_field_id();
		$value				= $value ? $value : $this->get_data();
		$required			= $required ? $required : $this->get_required();
		$disabled			= $disabled ? $disabled : $this->get_disabled();
		$placeholder		= $placeholder ? $placeholder : $this->get_placeholder();
		$maxlength			= $maxlength ? $maxlength : $this->get_maxlength();
		$minlength			= $minlength ? $minlength : $this->get_minlength();
		$max				= $max ? $max : $this->get_max();
		$min				= $min ? $min : $this->get_min();
		$radio_style		= $radio_style ? $radio_style : $this->get_radio_style();
		$code_editor		= $code_editor ? $code_editor : $this->get_code_editor();

		ob_start();
		if(file_exists($this->get_path_core('settings/tpl/'.$this->run_type()->get_module_name().'.php'))) {
			require($this->get_path_core('settings/tpl/' . $this->run_type()->get_module_name() . '.php'));
		}else{
			echo __('Settings Template not found: ', 'sv_core').$this->get_path_core('settings/tpl/'.$this->run_type()->get_module_name().'.php');
		}
		$setting = ob_get_contents();
		ob_end_clean();
		return '<div class="sv_setting" data-sv_prefix="'.$this->get_parent()->get_parent()->get_prefix().'" data-sv_field_id="'.$this->get_field_id().'">'.$setting.'</div>';
	}

	public function delete() :settings {
		if ( $this->get_type() == 'setting_upload' ) {
			wp_delete_attachment( $this->run_type()->get_data(), true );
		}

		delete_option( $this->run_type()->get_field_id() );

		return $this;
	}
	public function sanitize($meta_value, $meta_key, $object_type){
		return sanitize_text_field($meta_value);
	}
	protected function print_sub_field($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, string $sub){
		if(file_exists($this->get_path_core('settings/tpl/'.$this->run_type()->get_module_name().'_field.php'))) {
			require($this->get_path_core('settings/tpl/' . $this->run_type()->get_module_name() . '_field.php'));
		}else{
			echo __('Settings Template not found: ', 'sv_core').$this->get_path_core('settings/tpl/'.$this->run_type()->get_module_name().'_field.php');
		}
	}
}