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
			$child					= static::create($this->get_parent());
			$this->children[]		= $child;
			return $child;
		}
		public function get_children(){
			return $this->children;
		}
		public function get_ID($i=0){
			return '['.$i.']['.($this->get_parent()->get_ID()).']';
		}
		protected function html($ID,$title,$description,$name,$value){
			$output					= array(
				$this->html_field()
			);

			return implode('',$output);
		}
		private function html_field($i=0){
			$output					= array();

			if($this->get_children()){
				$output[]				= '<div class="'.$this->get_prefix($this->get_type()).'">';
				$output[]				= '<h4>#'.$i.'</h4>';
				foreach($this->get_children() as $child) {
					$output[]			= '<div class="'.$this->get_prefix($this->get_type()).'_item">';
					$output[]			= $child->run_type()->form(true);
					$output[]			= '</div>';
				}
				$output[]				= '</div>';
			}

			return implode('',$output);
		}
		public function get_data(){
			return $this->children;
		}
	}