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
							<!--<input
				data-sv_type="sv_form_field"
				class="sv_input"
				id="' . $ID . '_on"
				name="' . $name . '"
				type="checkbox"
				value="1"
				' . (($value == '1') ? ' checked="checked"' : '') . '
				' . $required . '
				' . $disabled . '
				/>-->
			<div class="sv_setting_checkbox">
				<input
				data-sv_type="sv_form_field"
				class="sv_input sv_input_off"
				id="' . $ID . '_off"
				name="' . $name . '"
				type="radio"
				value="0"
				' . (($value == '' || $value == '0') ? ' checked="checked"' : '') . '
				' . $required . '
				' . $disabled . '
				/>
				<label for="' . $ID . '_off" class="button"><i class="fa fa-times"></i></label>
				<input
				data-sv_type="sv_form_field"
				class="button sv_input sv_input_on"
				id="' . $ID . '_on"
				name="' . $name . '"
				type="radio"
				value="1"
				' . (($value == '1') ? ' checked="checked"' : '') . '
				' . $required . '
				' . $disabled . '
				/>
				<label for="' . $ID . '_on" class="button"><i class="fa fa-check"></i></label>
			</div>
			';
		}
	}