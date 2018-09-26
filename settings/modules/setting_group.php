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
			
			if(is_admin()) {
				wp_enqueue_script($this->get_prefix(), $this->get_url_lib_core('assets/admin_setting_group.js'), array('jquery'), filemtime($this->get_path_lib_core('assets/admin_setting_group.js')), true);
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
		protected function html($ID,$title,$description,$name,$value){
			$i						= 0;
			$output					= array();
			$output[]				= '<div class="sv_'.$this->get_module_name().'_wrapper" data-sv_form_field_index="'.count(get_option($this->get_field_id())).'">';
			$output[]				= '<input type="hidden" name="'.$this->get_field_id().'" value="" />';
			if($this->get_children() && get_option($this->get_field_id())) {
				foreach (get_option($this->get_field_id()) as $setting_id => $setting) {
					$output[]		= $this->html_field($i,$setting_id);
					$i++;
				}
			}
			$output[]				= '<div class="sv_'.$this->get_module_name().'_add_new">';
			$output[]				= '<div class="sv_'.$this->get_module_name().'_new_entries"></div>';
			$output[]				= '<div class="sv_'.$this->get_module_name().'_add_new_button">'.__('Add new Entry',$this->get_module_name()).'</div>';
			$output[]				= '<div class="sv_'.$this->get_module_name().'_new_draft" style="display:none;">'.$this->html_field($i).'</div>';
			$output[]				= '</div>';
			$output[]				= '</div>';

			return implode('',$output);
		}
		private function html_field($i=0, $setting_id = false){
			$output					= array();

			if($this->get_children()){
				$output[]				= ($setting_id !== false ? '<div class="sv_'.$this->get_module_name().'">' : '');
				$output[]				= '
					<div class="sv_'.$this->get_module_name().'_header">
						<h4>'.($setting_id !== false ? __('Entry',$this->get_module_name()).' #'.($i+1) : __('New Entry',$this->get_module_name())).'</h4>
						<div class="sv_'.$this->get_module_name().'_delete">'.__('Delete Entry', $this->get_module_name()).'</div>
					</div>
					';
				foreach($this->get_children() as $child) {
					$output[]			= '<div class="'.$this->get_prefix($this->get_type()).'_item">';
					
					$output[]			= '<div>'.$child->run_type()->html(
							($setting_id !== false ? $child->get_field_id().'['.$i.']['.$child->get_ID().']' : $child->get_field_id().'[sv_form_field_index]['.$child->get_ID().']'),
							$child->get_title(),
							$child->get_description(),
							($setting_id !== false ? $child->get_field_id().'['.$i.']['.$child->get_ID().']' : ''),
							($setting_id !== false ? get_option($child->get_field_id())[$setting_id][$child->get_ID()] : ''),
							$child->get_placeholder()
						).'</div>';
					
					$output[]			= '</div>';
				}
				$output[]				= ($setting_id !== false ? '</div>' : '');
			}

			return implode('',$output);
		}
	}