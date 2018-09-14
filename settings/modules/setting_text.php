<?php

	namespace sv_core;

	class setting_text extends settings{
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
					'.$title.'
					<input
					class="widefat"
					id="' . $ID . '"
					name="' . $name . '"
					type="text"
					value="' . esc_attr($value) . '"/>
					<p>'.$description.'</p>
				</label>
			';
		}
	}