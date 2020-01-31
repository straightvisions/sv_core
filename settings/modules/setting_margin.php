<?php

namespace sv_core;

class setting_margin extends settings{
	private $parent				= false;

	public function __construct($parent=false){
		$this->parent			= $parent;
	}
	public function html($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength) {
		ob_start();
		require($this->get_path_core('settings/tpl/'.basename(__FILE__)));
		$setting = ob_get_contents();
		ob_end_clean();
		return $setting;
	}
	protected function print_sub_field($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, string $sub){
		require($this->get_path_core('settings/tpl/setting_margin_field.php'));
	}
}