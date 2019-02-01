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
		public function html($ID, $title, $description, $name, $value){
			$output = '<h4>' . $title . '</h4><div class="description">' . $description . '</div><label for="' . $ID . '"v>';
			
			$args		= array(
				'echo'					=> 0,
				'selected'				=> $value,
				'name'					=> $name,
				'show_option_none'		=> __('No Page selected',$this->get_module_name())
			);
			$output	.= wp_dropdown_pages($args);

			$output .= '</label>';
			
			return $output;
		}
	}