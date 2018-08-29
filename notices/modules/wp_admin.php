<?php
	
	namespace sv_core;
	
	class wp_admin extends notices{
		private $notice								= false;
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent){
			$this->notice								= $parent;
		}
		private function output(){
			if($this->state() == 1){
				$class = 'notice-success';
			}elseif($this->state() == 2){
				$class = 'notice-info';
			}elseif($this->state() == 3){
				$class = 'notice-warning';
			}elseif($this->state() == 4){
				$class = 'notice-error';
			}elseif($this->state() == 5){
				$class = 'notice-error';
			}else{
				$class = 'notice-info';
			}
			
			echo '<div class="notice '.esc_attr($class).'">'.esc_html($this->notice->output_full()).'</div>';
		}
		public function admin_notice(){
			if(did_action('admin_notices')){
				$this->output();
			}else{
				add_action('admin_notices', function () {
					$this->output();
				});
			}
		}
	}