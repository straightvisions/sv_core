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
			if ( !static::$initialized && ( strpos( $hook,'straightvisions' ) !== false || strpos( $hook,'appearance_page_sv100' ) !== false ) ) {
				wp_enqueue_script($this->get_prefix(), $this->get_url_core('../assets/admin_setting_group.js'), array('jquery'), filemtime($this->get_path_core('../assets/admin_setting_group.js')), true);
			}
			static::$initialized = true;
		}
		public function add_child(){
			$child					= static::create($this);
			$this->children[]		= $child;
			return $child;
		}
		public function get_children(){
			return $this->children;
		}
		protected function add_group_html( $title, $description, string $field_id = '' ){
			$output					= array();
			$output[]				= '<div class="sv_'.$this->get_module_name().'_add_new">';
			$output[]               = '<h4>' . $title . '</h4><div class="description">' . $description . '</div>';
			$output[]				= '<div class="sv_'.$this->get_module_name().'_add_new_button button">'.__('Add', 'sv_core').'</div>';
			$output[]				= '<div class="sv_'.$this->get_module_name().'_new_draft" style="display:none;">'.$this->html_field(false,false,$field_id).'</div>';
			$output[]				= '</div>';

			return implode('',$output);
		}
		protected function html_field($i=0, $setting_id = false, string $field_id = ''){
			$fields					= array();
			$output					= array();

			if($this->get_children()){
				// allow custom labels for groups.
                $label = ($setting_id !== false ? __('Entry', 'sv_core') . ' #' . ($i + 1) : __('Group #', 'sv_core'));

				foreach($this->get_children() as $child) {
					$probs				= array(
						'ID'			=> ($setting_id !== false ? $field_id.'['.$setting_id.']['.$child->get_ID().']' : $field_id.'[sv_form_field_index]['.$child->get_ID().']'),
						'title'			=> $child->get_title(),
						'description'	=> $child->get_description(),
						'name'			=> ($setting_id !== false ? $field_id.'['.$setting_id.']['.$child->get_ID().']' : ''),
						'value'			=> (
						(
							$setting_id !== false &&
							get_option($field_id)[$setting_id][$child->get_ID()]
						)
							? get_option($field_id)[$setting_id][$child->get_ID()]
							: $child->run_type()->get_data()),
						'required'		=> $child->get_required(),
						'disabled'		=> $child->get_disabled(),
						'placeholder'	=> $child->get_placeholder(),
						'maxlength'		=> $child->get_maxlength(),
						'minlength'		=> $child->get_minlength(),
						'max'			=> $child->get_max(),
						'min'			=> $child->get_min(),
						'radio_style'	=> $child->get_radio_style(),
						'code_editor'	=> $this->get_code_editor()
					);

					$fields[]			= '<div class="'.$this->get_prefix($this->get_type()).'_item">';
					$fields[]			= '<div class="sv_'.$this->get_module_name().'_input" data-sv_input_name="'.$field_id.'[sv_form_field_index]['.$child->get_ID().']'.'">'.$child->form($probs);
					$fields[]			= '</div></div>';

					// overwrite child group label
					// take label from custom field
                    if( $child->get_is_label() ){

                        if ( $setting_id !== false && isset( get_option( $field_id )[ $setting_id ][ $child->get_ID() ] )
                            && ! empty( get_option($field_id)[$setting_id][ $child->get_ID() ] ) ) {
                            $label = get_option($field_id)[$setting_id][ $child->get_ID() ];
                        }

                    }else{

                        // take label from entry label field
                        if ( $setting_id !== false && isset( get_option( $field_id )[ $setting_id ]['entry_label'] )
                            && ! empty( get_option($field_id)[$setting_id]['entry_label'] ) ) {
                            $label = get_option($field_id)[$setting_id]['entry_label'];
                        }

                    }


				}

				$header[]				= ($setting_id !== false ? '<div class="sv_'.$this->get_module_name().'" sv_'.$this->get_module_name().'_entry_id="'.$setting_id.'">' : '');
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