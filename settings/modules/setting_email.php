<?php
	
	namespace sv_core;
	
	class setting_email extends settings{
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
		protected function html($ID,$title,$description,$name,$value){
			return '
				<label for="' . $ID . '">
					<div class="title">'.$title.' <span class="description" title="'.$description.'">(?)</span></div>
					<input
					class="widefat"
					id="' . $ID . '"
					name="' . $name . '"
					type="email"
					value="' . esc_attr($value) . '"/>
				</label>
			';
		}
	}