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
			return '
			<h4>' . $title . '</h4>
			<div class="description">' . $description . '</div>
			<label for="' . $ID . '" class="checkbox">
				<input
				data-sv_type="sv_form_field"
				class="sv_input"
				id="' . $ID . '"
				name="' . $name . '"
				type="checkbox"
				value="1"
				' . (($value == '1') ? ' checked="checked"' : '') . '
				' . $required . '
				' . $disabled . '
				/>
			</label>';
		}
	}