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
		public function widget($value,$object){

		}
		public function form(){
			return '
			<div>
				<label for="' . $this->get_field_id() . '">
					<input
					class=""
					id="' . $this->get_field_id() . '"
					name="' . $this->get_field_id() . '"
					type="checkbox"
					value="1"
					' . ((get_option($this->get_field_id()) == '1') ? ' checked="checked"' : '') . '
					/>
					<p>'.$this->get_parent()->get_description().'</p>
				</label>
			</div>
			';
		}
	}