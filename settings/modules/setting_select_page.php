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
			$output = '
				<label for="' . $ID . '">
					<div class="title">'.$title.' <span class="description" title="'.$description.'">(?)</span></div>';
			
			$args		= array(
				'echo'		=> 0,
				'selected'	=> $value,
				'name'		=> $name
			);
			$output	.= wp_dropdown_pages($args);

			$output .= '</label>';
			
			return $output;
		}
	}