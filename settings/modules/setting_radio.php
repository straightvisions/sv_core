<?php

namespace sv_core;

class setting_radio extends settings{
	private $parent				= false;

	/**
	 * @desc			initialize
	 * @author			Matthias Bathke
	 * @since			1.0
	 * @ignore
	 */
	public function __construct($parent=false){
		$this->parent			= $parent;
	}

	public function get($value,$format,$object){
		return $this->$format($value,$object);
	}

	public function html( string $ID, string $title, string $description, string $name, $value, string $required, string $disabled, $placeholder, $maxlength, $minlength, $max, $min, $radio_style ) {
		$output = '<h4>' . $title . '</h4><div class="description">' . $description . '</div>';

		if ( $radio_style == 'switch' ) {
			$output .= '<div class="sv_radio_switch_wrapper">';
			$output .= '<label for="' . $ID . '"><div class="switch_field">';
		}

		foreach( $this->get_parent()->get_options() as $o_value => $o_name ) {
			$output .=
			'<label for="' . $ID . '">
				<input
					id="'.$ID.'"
					name="' . $name . '"
					type="radio"
					class="sv_form_field ' . ( ( $o_value < 1 ) ? 'off' : 'on' ) . '"
					value="' . $o_value . '"
					' . $disabled . '
					' . ( ( $o_value == $value ) ? ' checked="checked" ' : '' ) . '
				/>
				<span class="name">' . $o_name . '</span>
			</label>';
		}

		return $output;
	}
}