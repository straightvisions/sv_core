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
			$output = '
				<label for="' . $ID . '">
					<select
					class=""
					id="' . $ID . '"
					name="' . $name . '">
				<div class="title">'.$title.' <span class="description" title="'.$description.'">(?)</span></div>
';
			
			foreach($this->get_parent()->get_options() as $o_value => $o_name){
				$output	.= '<option
				' . (($value == $o_value) ? ' selected="selected"' : '') . '
				value="'.$o_value.'">'.$o_name.'</option>';
			}
			
			$output .= '
					</select>
				</label>
			';
			
			return $output;
		}
	}