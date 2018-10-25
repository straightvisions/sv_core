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
		public function html($ID,$title,$description,$name,$value){
			return '
				<label for="' . $ID . '">
					<textarea style="height:200px;"
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '">' . esc_attr($value) . '</textarea>
				</label>
				<div class="sv_tooltip">?</div>
				<div class="sv_tooltip_description">' . $description . '</div>
			';
		}
	}