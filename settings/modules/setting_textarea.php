<?php

	namespace sv_core;

	class setting_textarea extends settings{
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
		public function html($ID, $title, $description, $name, $value, $required, $disabled) {
			if(!empty($description)) {
				$tooltip = '<div class="sv_tooltip dashicons dashicons-info"></div>
				<div class="sv_tooltip_description">' . $description . '</div>';
			} else {
				$tooltip = '';
			}
			return '
				<h4>' . $title . '</h4>
				<label for="' . $ID . '">
					<textarea style="height:200px;"
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '
					' . $required . '
					' . $disabled . '">' . esc_attr($value) . '</textarea>
				</label>' . $tooltip;
		}
	}