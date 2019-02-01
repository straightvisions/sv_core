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
			return '
				<h4>' . $title . '</h4>
				<div class="description">' . $description . '</div>
				<label for="' . $ID . '">
					<textarea style="height:200px;"
					id="' . $ID . '"
					name="' . $name . '
					' . $required . '
					' . $disabled . '">' . esc_attr($value) . '</textarea>
					'  . $tooltip . '
				</label>';
		}
	}