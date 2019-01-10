<?php

	namespace sv_core;

	class setting_text extends settings{
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
		public function html($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $multiple, $maxlength, $minlength) {
			if(!empty($description)) {
				$tooltip = '<div class="sv_tooltip dashicons dashicons-info"></div>
				<div class="sv_tooltip_description">' . $description . '</div>';
			} else {
				$tooltip = '';
			}

			return '
				<h4>' . $title . '</h4>
				<label for="' . $ID . '">
					<input
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '"
					type="text"
					placeholder="'.$placeholder.'"
					value="' . esc_attr($value) . '"
					' . ($maxlength ? 'maxlength="'.$maxlength.'"' :  ''). '"
					' . $minlength . '
					' . $required . '
					' . $disabled . '/>
				</label>' . $tooltip;
		}
	}