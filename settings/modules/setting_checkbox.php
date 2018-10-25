<?php
	
	namespace sv_core;
	
	class setting_checkbox extends settings{
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
		public function get($value,$format,$object){
			return $this->$format($value,$object);
		}
		public function html($ID,$title,$description,$name,$value){
			return '
				<label for="' . $ID . '" class="sv_checkbox">
					<input
					class="sv_form_field"
					id="' . $ID . '"
					name="' . $name . '"
					type="checkbox"
					value="1"
					' . (($value == '1') ? ' checked="checked"' : '') . '
					/>
					<div class="sv_tooltip">?</div>
					<div class="sv_tooltip_description">' . $description . '</div>
				</label>
			';
		}
	}