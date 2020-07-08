<?php
	
namespace sv_core;

class setting_select extends settings{
	private $parent				= false;

	/**
	 * @desc			initialize
	 * @author			Matthias Bathke
	 * @since			1.0
	 * @ignore
	 */
	public function __construct( $parent = false ) {
		$this->parent			= $parent;
	}
	public function get_css_data(string $custom_property = '', string $prefix = '', string $suffix = ''): array{
		$property				= ((strlen($custom_property) > 0) ? $custom_property : false);
		$properties				= array();

		if($property == 'font-family'){
			return $this->get_css_font_data();
		}

		// this input field is generic, so custom property is required
		if($property && $this->get_parent()->get_data()) {
			$properties[$property]		= $this->prepare_css_property_responsive($this->get_parent()->get_data(),$prefix,$suffix);
		}

		return $properties;
	}
	// @todo: maybe outsource this into specialized font setting type
	public function get_css_font_data(){
		$properties				= array();
		$font_family			= false;
		$font_weight			= false;

		if($this->get_parent()->get_data()) {
			foreach ($this->get_parent()->get_data() as $breakpoint => $val) {
				if ($val) {
					$f = $this->get_parent()->get_parent()->get_module('sv_webfontloader')->get_font_by_label($val);
					$font_family[$breakpoint] = $f['family'];
					$font_weight[$breakpoint] = $f['weight'];
				} else {
					$font_family[$breakpoint] = false;
					$font_weight[$breakpoint] = false;
				}
			}
		}

		if($font_family && is_array($font_family) && (count(array_unique($font_family)) > 1 || array_unique($font_family)['mobile'] !== false)){
			$properties['font-family']	= $this->prepare_css_property_responsive($font_family,'',', sans-serif');
			$properties['font-weight']	= $this->prepare_css_property_responsive($font_weight,'','');
		}

		return $properties;
	}
}