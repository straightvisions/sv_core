<?php
	
	namespace sv_core;
	
	class setting_url extends settings{
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
		public function html($ID, $title, $description, $name, $value,  $required, $disabled, $placeholder, $multiple, $maxlength, $minlength){
			return '
				<h4>' . $title . '</h4>
				<div class="description">' . $description . '</div>
				<label for="' . $ID . '">
					<input
					id="' . $ID . '"
					name="' . $name . '"
					type="url"
					placeholder="'.$placeholder.'"
					value="' . esc_attr($value) . '"
					' . ($maxlength ? 'maxlength="'.$maxlength.'"' :  ''). '"
					' . $minlength . '
					' . $required . '
					' . $disabled . '/>
				</label>';
		}
	}