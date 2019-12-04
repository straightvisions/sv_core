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
		public function sanitize($meta_value, $meta_key, $object_type){
			return intval($meta_value);
		}
		public function get($value,$format,$object){
			return $this->$format($value,$object);
		}
		public function html($ID, $title, $description, $name, $value, $required, $disabled){
			$output = '
			<h4>' . $title . '</h4>';
			
			if($this->get_parent()->has_options()) {
				foreach ( $this->get_parent()->get_options() as $o_value => $o_name ) {
					
					$new_ID = $new_name = $name.'['.$o_value.']';
					
					$output .= $this->field($new_ID, $new_name, $value[$o_value], $required, $disabled, $o_name);
				}
			}else{
				$output .= $this->field($ID, $name, $value, $required, $disabled);
			}

			$output .= '<div class="description">' . $description . '</div>';

			return $output;
		}
		public function field($ID, $name, $value, $required, $disabled, $title=''){
			$classes = 'sv_setting_checkbox'.($disabled ? ' sv_disabled' : '');
			
			if(strlen($title) > 0){
				$title = '<div class="sv_setting_checkbox_title">'.$title.'</div>';
			}
			
			return 	'<div class="' . $classes . '">
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
				'.$title.'
			</div>
			  ';
		}
	}