<?php
	
	namespace sv_core;
	
	class setting_checkbox extends settings{
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
		public function html($ID, $title, $description, $name, $value, $required, $disabled){
			if(!empty($description)) {
				$tooltip = '<div class="sv_tooltip dashicons dashicons-info"></div>
				<div class="sv_tooltip_description">' . $description . '</div>';
			} else {
				$tooltip = '';
			}
			return '
			<div class="sv_checkbox-wrapper">
				<label for="' . $ID . '" class="sv_checkbox">
					' . (  $title ? '<h4>' .  $title . '</h4>' : '') . '
					<input
					class="sv_form_field"
					id="' . $ID . '"
					name="' . $name . '"
					type="checkbox"
					value="1"
					' . (($value == '1') ? ' checked="checked"' : '') . '
					' . $required . '
					' . $disabled . '
					/>
				</label>
				' . $tooltip . '
			</div>';
		}
	}