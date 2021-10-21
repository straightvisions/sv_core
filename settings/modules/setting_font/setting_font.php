<?php

namespace sv_core;

class setting_font extends settings{
	private $parent = false;

	public function __construct($parent = false){
		$this->parent = $parent;
	}

	public function get_css_data(string $custom_property = 'font-family', string $prefix = '', string $suffix = ''): array{
		$properties = array();

		if ($this->get_parent()->get_data()) {
			foreach ($this->get_parent()->get_data() as $breakpoint => $val) {
				$properties[$custom_property][$breakpoint] = $prefix.'"' . $val . '"'.$suffix;
			}
		}

		return $properties;
	}
}