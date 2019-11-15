<?php
	
	namespace sv_core;
	
	class setting_select_page extends settings{
		private $parent				= false;
		
		/**
		 * @desc			initialize
		 * @author			Matthias Bathke
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent			= $parent;
		}
		public function sanitize($meta_value, $meta_key, $object_type){
			return intval($meta_value);
		}
		public function html($ID, $title, $description, $name, $value){
			$output = '<h4>' . $title . '</h4>
						<label for="' . $ID . '">';

			$args		= array(
				'echo'					=> 0,
				'selected'				=> $value,
				'name'					=> $name,
				'id'                    => $ID,
				'class'					=> 'data_sv_type_sv_form_field sv_input',
				'show_option_none'		=> __('No Page selected', 'sv_core')
			);
			$output	.= wp_dropdown_pages($args);

			$output .= '</label>
			<div class="description">' . $description . '</div>';
			
			return $output;
		}
	}