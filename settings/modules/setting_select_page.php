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
			if(!empty($description)) {
				$tooltip = '<div class="sv_tooltip">?</div>
				<div class="sv_tooltip_description">' . $description . '</div>';
			} else {
				$tooltip = '';
			}
			$output = '<h4>' . $title . '</h4><label for="' . $ID . '">';
			
			$args		= array(
				'echo'					=> 0,
				'selected'				=> $value,
				'name'					=> $name,
				'class'					=> 'sv_input',
				'show_option_none'		=> __('No Page selected',$this->get_module_name())
			);
			$output	.= wp_dropdown_pages($args);

			$output .= '</label>' . $tooltip;
			
			return $output;
		}
	}