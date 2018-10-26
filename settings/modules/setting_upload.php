<?php

	namespace sv_core;

	class setting_upload extends settings{
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
			if(!empty($description)) {
				$tooltip = '<div class="sv_tooltip">?</div>
				<div class="sv_tooltip_description">' . $description . '</div>';
			} else {
				$tooltip = '';
			}
			return '
				<h4>' . $title . '</h4>
				<label for="' . $ID . '">
					<input
					class="sv_file"
					id="' . $ID . '"
					name="' . $name . '"
					type="file"
					placeholder="'.$placeholder.'"
					/>
					' . esc_attr($value) . '
				</label>' . $tooltip;
		}
	}