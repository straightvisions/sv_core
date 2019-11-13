<?php

	namespace sv_core;

	class setting_hidden extends settings{
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
		public function html($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength) {
			return '
				<label for="' . $ID . '">
					<input
					data-sv_type="sv_form_field"
					id="' . $ID . '"
					name="' . $name . '"
					type="hidden"
					value="' . esc_attr($value) . '"/>';
		}
	}