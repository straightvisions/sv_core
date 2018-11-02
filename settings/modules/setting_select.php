<?php
	
	namespace sv_core;
	
	class setting_select extends settings{
		private $parent				= false;
		
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent			= $parent;
		}
		public function html($ID,$title,$description,$name,$value){
			if(!empty($description)) {
				$tooltip = '<div class="sv_tooltip">?</div>
				<div class="sv_tooltip_description">' . $description . '</div>';
			} else {
				$tooltip = '';
			}
			$output = '
				<h4>' . $title . '</h4>
				<label for="' . $ID . '">
					<select
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '">';
			
			foreach($this->get_parent()->get_options() as $o_value => $o_name){
				$output	.= '<option
				' . (($value == $o_value) ? ' selected="selected"' : '') . '
				value="'.$o_value.'">'.$o_name.'</option>';
			}
			
			$output .= '
					</select>
				</label>' . $tooltip;
			
			return $output;
		}
	}