<?php
	
	namespace sv_core;
	
	class setting_group extends settings{
		private $parent				= false;
		private $children			= array();
		
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent			= $parent;
		}
		public function add_child(){
			$child					= static::$settings->create($this->get_parent());
			$this->children[]		= $child;
			return $child;
		}
		public function get_children(){
			return $this->children;
		}
		protected function html($ID,$title,$description,$name,$value){
			$output					= array();
			if($value && is_array($value) && count($value) > 0){
				foreach($value as $sub_count => $sub){
					foreach($sub as $sub_name => $sub_value){
					
					}
					$this->
					$output[]			= '
					<label for="' . $ID . '">
						'.$title.'
						<input
						class="widefat"
						id="' . $ID . '"
						name="' . $name . '['.$sub_count.']['.$sub_name.']"
						type="text"
						value="' . esc_attr($sub) . '"/>
						<p>'.$description.'</p>
					</label>
					';
				}
				return implode('',$output);
			}else{
				return '';
			}
		}
		public function get_data(){
			return $this->children;
		}
	}