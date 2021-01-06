<?php

namespace sv_core;

class setting_border extends settings {
	private $parent	= false;

	public function __construct( $parent = false ){
		$this->parent = $parent;
	}
	public function get_css_data(string $custom_property = '', string $prefix = '', string $suffix = ''): array{
		$properties			= array();

		if($this->get_parent()->get_data()) {
			foreach ($this->get_parent()->get_data() as $breakpoint => $query) {

				if (isset($query['top_width'])) {
					$val = $query['top_width'] . ' ' . $query['top_style'] . ' rgba(' . $query['color'] . ')';
					$properties['border-top'][$breakpoint] = $val;
				}

				if (isset($query['right_width'])) {
					$val = $query['right_width'] . ' ' . $query['right_style'] . ' rgba(' . $query['color'] . ')';
					$properties['border-right'][$breakpoint] = $val;
				}

				if (isset($query['bottom_width'])) {
					$val = $query['bottom_width'] . ' ' . $query['bottom_style'] . ' rgba(' . $query['color'] . ')';
					$properties['border-bottom'][$breakpoint] = $val;
				}

				if (isset($query['left_width'])) {
					$val = $query['left_width'] . ' ' . $query['left_style'] . ' rgba(' . $query['color'] . ')';
					$properties['border-left'][$breakpoint] = $val;
				}

				$top_left_radius = (empty($query['top_left_radius'])) ? 0 : (int)$query['top_left_radius'];
				$top_right_radius = (empty($query['top_right_radius'])) ? 0 : (int)$query['top_right_radius'];
				$bottom_right_radius = (empty($query['bottom_right_radius'])) ? 0 : (int)$query['bottom_right_radius'];
				$bottom_left_radius = (empty($query['bottom_left_radius'])) ? 0 : (int)$query['bottom_left_radius'];

				if ($top_left_radius + $top_right_radius + $bottom_right_radius + $bottom_left_radius > 0) {
					// @todo: implement unit settings here
					//$query_radius = $top_left_radius . ' ' . $top_right_radius . ' ' . $bottom_right_radius . ' ' . $bottom_left_radius;
					$query_radius = $top_left_radius . 'px ' . $top_right_radius . 'px ' . $bottom_right_radius . 'px ' . $bottom_left_radius . 'px';
					$properties['border-radius'][$breakpoint] = $query_radius;
				}
			}
		}

		return $properties;
	}
}