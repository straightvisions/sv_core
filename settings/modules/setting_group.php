<?php
	
	namespace sv_core;
	
	class setting_group extends settings{
		private $parent				= false;
		private $children			= array();
		
		/**
		 * @desc			initialize
		 * @author			Matthias Bathke
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent			= $parent;

			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		}
		public function admin_enqueue_scripts($hook){
			if ( strpos($hook,'straightvisions') !== false ) {
				wp_enqueue_script($this->get_prefix(), $this->get_url_core('assets/admin_setting_group.js'), array('jquery'), filemtime($this->get_path_core('assets/admin_setting_group.js')), true);
			}
		}
		public function add_child(){
			$child					= static::create($this->get_parent());
			$this->children[]		= $child;
			return $child;
		}
		public function get_children(){
			return $this->children;
		}
		private function add_group_html( $title, $description ){
			$output					= array();
			$output[]				= '<div class="sv_'.$this->get_module_name().'_add_new">';
			$output[]               = '<h4>' . $title . '</h4><div class="description">' . $description . '</div>';
			$output[]				= '<div class="sv_'.$this->get_module_name().'_add_new_button button">'.__('Add',$this->get_module_name()).'</div>';
			$output[]				= '<div class="sv_'.$this->get_module_name().'_new_draft" style="display:none;">'.$this->html_field().'</div>';
			$output[]				= '</div>';
			
			return implode('',$output);
		}
		protected function html($ID,$title,$description,$name,$value){
			$i						= 0;
			$output					= array();
			
			$output[]				= $this->add_group_html( $title, $description );
			
			$output[]				= '<div class="sv_'.$this->get_module_name().'_wrapper" data-sv_form_field_index="'.count((array)get_option($this->get_field_id())).'">';
			$output[]				= '<input type="hidden" name="'.$this->get_field_id().'" value="" />';
			
			if($this->get_children() && get_option($this->get_field_id())) {
				foreach (get_option($this->get_field_id()) as $setting_id => $setting) {
					$output[]		= $this->html_field($i,$setting_id);
					$i++;
				}
			}
			$output[]				= '</div>';

			return implode('',$output);
		}
		private function html_field($i=0, $setting_id = false){
			$output					= array();

			if($this->get_children()){
				$output[]				= ($setting_id !== false ? '<div class="sv_'.$this->get_module_name().'">' : '');
				$output[]				= '
					<div class="sv_'.$this->get_module_name().'_header">
						<h4>'.($setting_id !== false ? __('Entry',$this->get_module_name()).' #'.($i+1) : __('Group #',$this->get_module_name())).'</h4> 
						<div class="sv_'.$this->get_module_name().'_delete"><i class="fas fa-trash"></i></div>
					</div>
					';

				$run = 0;
				foreach($this->get_children() as $child) {
					$output[]			= '<div class="'.$this->get_prefix($this->get_type()).'_item">';
					$output[]			= '<div class="sv_'.$this->get_module_name().'_input">'.$child->run_type()->html(
							($setting_id !== false ? $child->get_field_id().'['.$i.']['.$child->get_ID().']' : $child->get_field_id().'[sv_form_field_index]['.$child->get_ID().']'),
							$child->get_title(),
							$child->get_description(),
							($setting_id !== false ? $child->get_field_id().'['.$i.']['.$child->get_ID().']' : ''),
							($setting_id !== false ? get_option($child->get_field_id())[$setting_id][$child->get_ID()] : ''),
							$child->get_required(),
							$child->get_disabled(),
							$child->get_placeholder(),
							$child->get_maxlength(),
							$child->get_minlength(),
							$child->get_max(),
							$child->get_min(),
							$child->get_radio_style()
						);
					$output[]			= '</div></div>';
				}
				$output[]				= ($setting_id !== false ? '</div>' : '');
			}

			return implode('',$output);
		}
	}