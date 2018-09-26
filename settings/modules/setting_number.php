<?php
	
	namespace sv_core;
	
	class setting_number extends settings{
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
		public function html($ID,$title,$description,$name,$value,$placeholder=''){
			return '
				<label for="' . $ID . '">
					<div class="title">'.$title.' <span class="description" title="'.$description.'">(?)</span></div>
					<input
					class="sv_form_field"
					id="' . $ID . '"
					name="' . $name . '"
					type="number"
					placeholder="'.$placeholder.'"
					value="' . esc_attr($value) . '"/>
				</label>
			';
		}
	}