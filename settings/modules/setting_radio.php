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

		$output .= ( $radio_style == 'switch'
			? '<div class="sv_radio_switch_wrapper"><label for="' . $ID . '"><div class="switch_field">'
			: '' );

		foreach( $this->get_parent()->get_options() as $o_value => $o_name ) {
			$output .= ( $radio_style == 'switch' ? '' : '<label for="' . $ID . '">' );
			$output .=
				'<input
					name="' . $name . '"
					id="' . $ID . '"
					type="radio"
					data-sv_type="sv_form_field"
					class="' . ( ( $o_value < 1 ) ? 'off' : 'on' ) . '"
					value="' . $o_value . '"
					' . $disabled . '
					' . ( ( $o_value == $value ) ? ' checked="checked" ' : '' ) . '
				/>
				<span class="name">' . $o_name . '</span>';
			$output .= ( $radio_style == 'switch' ? '' : '</label>' );
		}

		$output .= ( $radio_style == 'switch' ? '</div></label></div>' : '' );

		return $output;
	}
}