<?php
	
	namespace sv_core;
	
	class setting_select_page extends settings{
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
			$output = '<label for="' . $ID . '">';
			
			$args		= array(
				'echo'					=> 0,
				'selected'				=> $value,
				'name'					=> $name,
				'class'					=> 'sv_form_field',
				'show_option_none'		=> __('No Page selected',$this->get_module_name())
			);
			$output	.= wp_dropdown_pages($args);

			$output .= '<div class="sv_tooltip">?</div><div class="sv_tooltip_description">' . $description . '</div></label>';
			
			return $output;
		}
	}