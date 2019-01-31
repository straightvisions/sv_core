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

	public function html( string $ID, string $title, string $description, string $name, $value, string $required, string $disabled, $placeholder, string $multiple, $maxlength, $minlength, $max, $min, $radio_style ) {
		if(!empty($description)) {
			$tooltip = '<div class="sv_tooltip dashicons dashicons-info"></div>
				<div class="sv_tooltip_description">' . $description . '</div>';
		} else {
			$tooltip = '';
		}

		if ( $radio_style == 'checkbox' ) {
			$output = (  $title ? '<h4>' .  $title . '</h4>' : '') . '<div class="sv_radio-checkbox-wrapper">';

			foreach( $this->get_parent()->get_options() as $o_value => $o_name ) {
				$output .=
					'<label for="' . $ID . '" class="sv_radio checkbox">
					<div class="wrapper">
						<input
							name="' . $name . '"
							type="radio"
							class="' . ( ( $o_value < 1 ) ? 'off' : 'on' ) . '"
							value="' . $o_value . '"
							' . $disabled . '
							' . ( ( $o_value == $value ) ? ' checked="checked" ' : '' ) . '
						/>
						<span class="name">' . $o_name . '</span>
					</div>
				</label>';
			}

			$output .= $tooltip . '</div>';
		} else {
			$output = (  $title ? '<h4>' .  $title . '</h4>' : '');

			foreach( $this->get_parent()->get_options() as $o_value => $o_name ) {
				$output .=
				'<div class="sv_radio-wrapper">
					<label for="' . $ID . '" class="sv_radio">
						<input
							name="' . $name . '"
							type="radio"
							class="' . ( ( $o_value < 1 ) ? 'off' : 'on' ) . '"
							value="' . $o_value . '"
							' . $disabled . '
							' . ( ( $o_value == $value ) ? ' checked="checked" ' : '' ) . '
						/>
						<span class="name">' . $o_name . '</span>
					</label>
					' . $tooltip . '
				</div>';
			}
		}

		return $output;
	}
}