<?php
	
	namespace sv_core;
	
	class setting_number extends settings{
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
		public function sanitize($meta_value, $meta_key, $object_type){
			return intval($meta_value);
		}
		public function html($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, $max, $min){
			return '
				<h4>' . $title . '</h4>
				<label for="' . $ID . '">
					<input
					data-sv_type="sv_form_field"
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '"
					type="number"
					placeholder="'.$placeholder.'"
					value="' . esc_attr($value) . '"
					max="' . $max . '"
					min="' . $min . '"
					' . ($maxlength ? 'maxlength="'.$maxlength.'"' :  ''). '"
					' . $minlength . '
					' . $required . '
					' . $disabled . '/>
				</label>
				<div class="description">' . $description . '</div>';
		}
	}