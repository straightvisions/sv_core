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
		public function get($value,$format,$object){
			return $this->$format($value,$object);
		}
		public function widget($value,$object){

		}
		public function form(){
			$output = '
			<div>
				<label for="' . $this->get_field_id() . '">
					<select
					class=""
					id="' . $this->get_field_id() . '"
					name="' . $this->get_field_id() . '">
			';
			
			foreach($this->get_parent()->get_options() as $value => $name){
				$output	.= '<option
				' . ((get_option($this->get_field_id()) == $value) ? ' selected="selected"' : '') . '
				value="'.$value.'">'.$name.'</option>';
			}
			
			$output .= '
					</select>
					<p>'.$this->get_parent()->get_description().'</p>
				</label>
			</div>
			';
			
			return $output;
		}
	}