<?php

namespace sv_core;

class notices extends sv_abstract{
	protected static $notices					= array();
	protected static $init_once					= false;
	// notice properties
	private $ID									= false;
	private $title								= false;
	private $desc_public						= false;
	private $desc_admin							= false;
	private $state								= false; // 1 = success, 2 = info, 3 = warning, 4 = error, 5 = critical
	private $terms								= array();

	/**
	 * @desc			initialize
	 * @author			Matthias Reuter
	 * @since			1.0
	 * @ignore
	 */
	public function __construct($ID=false){
		$this->ID								= $ID;

		if(!static::$init_once){
			add_action('init', array($this, 'create_post_type'));
			static::$init_once					= true;
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
		if($this->get_path_lib_core('notices/modules/'.$name.'.php',true)){ // look for class file in modules directory
			require_once($this->get_path_lib_core('notices/modules/'.$name.'.php'));
			$class_name							= __NAMESPACE__.'\\'.$name;

			$this->$name						= new $class_name($this);
			return $this->$name;
		}else{
			throw new \Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->get_module_name().'/modules/'.$name.'.php'.')');
		}
	}
	public function create_post_type(){
		register_post_type('sv_notices',
			array(
				'labels'						=> array(
					'name'						=> __('SV Notices', $this->get_name()),
					'singular_name'				=> __('SV Notice', $this->get_name()),
				),
				'public'						=> false,
				'exclude_from_search'			=> true,
				'publicly_queryable'			=> false,
				'show_ui'						=> true,
				'has_archive'					=> false,
				'menu_icon'						=> $this->get_url_lib_core('assets/logo_icon.png'),
				'supports'						=> array('custom-fields'),
				'delete_with_user'				=> false,
				'rewrite'						=> array(
					'slug'						=> 'sv_notices'
				),
				'taxonomies'					=> array('sv_notices_group')
			)
		);
		register_taxonomy(
			'sv_notices_group',
			'sv_notices',
			array(
				'label'							=> __('SV Notices Groups', $this->get_name()),
				'labels'						=> array(
					'name'						=> __('SV Notices Groups', $this->get_name()),
					'singular_name'				=> __('SV Notices Group', $this->get_name()),
				),
				'hierarchical'					=> false,
				'show_ui'						=> true,
				'show_admin_column'				=> true,
				'public'						=> false,
				'exclude_from_search'			=> true,
				'publicly_queryable'			=> false,
			)
		);
	}
	// GETTER / SETTER METHODS
	private function set_ID($ID){
		$this->ID								= $ID;
	}
	public function get_ID(){
		return $this->ID;
	}
	private function get_meta($field,$refresh=false){
		if($refresh || !$this->$field){
			$this->$field						= get_post_meta($this->get_ID(), $field, true);
		}

		return $this->$field;
	}
	private function set_meta($field,$value){
		$this->$field							= $value;
		update_post_meta($this->get_ID(), $field, $value);
	}
	public function set_title($title){
		$this->set_meta('title', $title);
		wp_update_post(array(
			'ID'								=> $this->get_ID(),
			'post_title'						=> $this->get_name().': '.$title
		));
	}
	public function get_title($refresh=false){
		return $this->get_meta('title',$refresh);
	}
	public function set_desc_public($desc_public){
		$this->set_meta('desc_public', $desc_public);
	}
	public function get_desc_public($refresh=false){
		return $this->get_meta('desc_public',$refresh);
	}
	public function set_desc_admin($desc_admin){
		$this->set_meta('desc_admin', $desc_admin);
	}
	public function get_desc_admin($refresh=false){
		return $this->get_meta('desc_admin',$refresh);
	}
	public function set_state(int $state){
		$this->set_term('state', $this->get_state_title($state));
		$this->set_meta('state', $state);
	}
	public function get_state($refresh=false): int{
		return intval($this->get_meta('state',$refresh));
	}
	public function get_state_title($number){
		$titles									= array(
			1									=> __('Success',$this->get_name()),
			2									=> __('Info',$this->get_name()),
			3									=> __('Warning',$this->get_name()),
			4									=> __('Error',$this->get_name()),
			5									=> __('Critical',$this->get_name()),
		);

		return isset($titles[$number]) ? $titles[$number] : $number;
	}
	private function set_term($type,$name){
		$this->terms[$type]						= $name;
		wp_set_post_terms($this->get_ID(), $this->get_terms(), 'sv_notices_group');
	}
	private function get_terms(){
		return $this->terms;
	}

	// OBJECT METHODS
	public static function create($group='no_group_specified'){
		$new_notice								= new self();
		$ID										= wp_insert_post(array(
			'post_title'						=> $group,
			'post_type'							=> 'sv_notices',
			'post_status'						=> 'publish'
		));
		$new_notice->set_ID($ID);
		$new_notice->set_term('group', $group);

		// @todo: implement called class, line info etc.

		return $new_notice;
	}
	public static function get($ID){
		if(isset(static::$notices[$ID])){
			return static::$notices[$ID];
		}

		return new self($ID);
	}
	public static function get_list(){
		if(count(static::$notices) > 0){
			return static::$notices;
		}else{
			return false;
		}
	}
	public static function has(){
		if(count(static::$notices) > 0){
			return true;
		}else{
			return false;
		}
	}
	public static function number(){
		return count(static::$notices);
	}
	// output methods
	public function output_public(){
		return
			'<p><strong>'.$this->get_title().'</strong></p>'.
			'<p>'.$this->get_desc_public().'</p>';
	}
	public function output_admin(){
		return
			'<p>'.$this->get_desc_admin().'</p>';
	}
	public function output_full(){
		return $this->output_public().$this->output_admin();
	}
}