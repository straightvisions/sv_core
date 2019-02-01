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

	public function html( string $ID, string $title, string $description, string $name, $value, string $required, string $disabled, $placeholder, string $multiple ) {
		$output = '
			<h4>' . $title . '</h4>
			<div class="description">' . $description . '</div>
			<label for="' . $ID . '">
				<select
				id="' . $ID . '"
				name="' . $name . '"
				' . $multiple . '
				' . $required . '
				' . $disabled . '>';

		foreach( $this->get_parent()->get_options() as $o_value => $o_name ) {
			$output	.= '<option
			' . ( ( $value == $o_value ) ? ' selected="selected"' : '' ) . '
			value="' . $o_value . '">' . $o_name . '</option>';
		}

		$output .= '
				</select>
				'  . $tooltip . '
			</label>';

		return $output;
	}
}