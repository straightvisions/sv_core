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
					<input
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '"
					type="number"
					placeholder="'.$placeholder.'"
					value="' . esc_attr($value) . '"/>
					<div class="sv_tooltip">?</div>
					<div class="sv_tooltip_description">' . $description . '</div>
				</label>
			';
		}
	}