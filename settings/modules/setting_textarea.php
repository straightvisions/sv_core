<?php

	namespace sv_core;

	class setting_textarea extends settings{
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
					<textarea style="height:200px;"
					class="widefat"
					id="' . $ID . '"
					name="' . $name . '">' . esc_attr($value) . '</textarea>
					<p>'.$description.'</p>
				</label>
			';
		}
	}