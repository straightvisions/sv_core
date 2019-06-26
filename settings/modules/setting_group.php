<?php
	
	namespace sv_core;
	
	class setting_group extends settings{
		private $parent						= false;
		private $children					= array();
		public static $initialized			= false;
		
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
		public function field_callback($input){
			if($this->get_children()) {
				foreach ( $this->get_children() as $setting ) {
					if(method_exists($setting->run_type(), 'field_callback')) {
						$input = $setting->run_type()->field_callback( $input );
					}
				}
			}
			return $input;
		}
		public function admin_enqueue_scripts($hook){
			if ( !static::$initialized && strpos($hook,'straightvisions') !== false ) {
				wp_enqueue_script($this->get_prefix(), $this->get_url_core('assets/admin_setting_group.js'), array('jquery'), filemtime($this->get_path_core('assets/admin_setting_group.js')), true);
			}
			static::$initialized = true;
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
			$output[]				= '<div class="sv_'.$this->get_module_name().'_add_new_button button">'.__('Add', 'sv_core').'</div>';
			$output[]				= '<div class="sv_'.$this->get_module_name().'_new_draft" style="display:none;">'.$this->html_field().'</div>';
			$output[]				= '</div>';
			
			return implode('',$output);
		}
		protected function html($ID,$title,$description,$name,$value){
			$i						= 0;
			$output					= array();
			
			$output[]				= $this->add_group_html( $title, $description );
			$output[]				= '<div class="sv_'.$this->get_module_name().'_wrapper">';
			$output[]				= '<input type="hidden" name="'.$this->get_field_id().'" value="" />';
			
			if($this->get_children() && get_option($this->get_field_id())) {
				foreach (get_option($this->get_field_id()) as $setting_id => $setting) {
					$output[]		= $this->html_field($i,intval($setting_id));
					$i++;
				}
			}
			$output[]				= '</div>';

			return implode('',$output);
		}
		private function html_field($i=0, $setting_id = false){
			$fields					= array();

			if($this->get_children()){
				// allow custom labels for groups.
                $label = ($setting_id !== false ? __('Entry', 'sv_core') . ' #' . ($i + 1) : __('Group #', 'sv_core'));

				foreach($this->get_children() as $child) {
					$fields[]			= '<div class="'.$this->get_prefix($this->get_type()).'_item">';
					$fields[]			= '<div class="sv_'.$this->get_module_name().'_input">'.$child->run_type()->html(
							($setting_id !== false ? $child->get_field_id().'['.$i.']['.$child->get_ID().']' : $child->get_field_id().'[sv_form_field_index]['.$child->get_ID().']'),
							$child->get_title(),
							$child->get_description(),
							($setting_id !== false ? $child->get_field_id().'['.$i.']['.$child->get_ID().']' : ''),
							(
								(
								$setting_id !== false &&
								isset(get_option($child->get_field_id())[$setting_id][$child->get_ID()]) &&
								get_option($child->get_field_id())[$setting_id][$child->get_ID()]
								)
						? get_option($child->get_field_id())[$setting_id][$child->get_ID()]
						: ''),
							$child->get_required(),
							$child->get_disabled(),
							$child->get_placeholder(),
							$child->get_maxlength(),
							$child->get_minlength(),
							$child->get_max(),
							$child->get_min(),
							$child->get_radio_style(),
							$this->get_code_editor()
						);
					$fields[]			= '</div></div>';

					// overwrite child group label
					// take label from custom field
                    if( $child->get_is_label() ){

                        if ( $setting_id !== false && isset( get_option( $child->get_field_id() )[ $setting_id ][ $child->get_ID() ] )
                            && ! empty( get_option($child->get_field_id())[$setting_id][ $child->get_ID() ] ) ) {
                            $label = get_option($child->get_field_id())[$setting_id][ $child->get_ID() ];
                        }

                    }else{

                        // take label from entry label field
                        if ( $setting_id !== false && isset( get_option( $child->get_field_id() )[ $setting_id ]['entry_label'] )
                            && ! empty( get_option($child->get_field_id())[$setting_id]['entry_label'] ) ) {
                            $label = get_option($child->get_field_id())[$setting_id]['entry_label'];
                        }

                    }


				}

				$header[]				= ($setting_id !== false ? '<div class="sv_'.$this->get_module_name().'">' : '');
				$header[]				= '
					<div class="sv_'.$this->get_module_name().'_header">
						<h4 class="sv_' .$this->get_module_name() .'_title"><i class="fas fa-angle-right"></i> '.$label.'</h4> 
						<div class="sv_'.$this->get_module_name().'_delete"><i class="fas fa-trash"></i></div>
					</div>
					';

				$header[]               = '<div class="sv_' . $this->get_module_name() . '_settings_wrapper">';

				$footer[]				= ($setting_id !== false ? '</div></div>' : '</div>');

				$output					= array_merge($header, $fields, $footer);
			}


			return implode('',$output);
		}
	}