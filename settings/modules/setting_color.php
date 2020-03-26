<?php
	namespace sv_core;

	class setting_color extends settings {
		private $parent				        = false;
		private $color_palette              = false;

		/**
		 * @desc			initialize
		 * @author			Adrian Chudzynski
		 * @since			1.0
		 * @ignore
		 */
		public function __construct( $parent = false ) {
			$this->parent			= $parent;

			add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		}
		// Returns the color pallete
		protected function get_color_palette() {
			return $this->color_palette;
		}

		// Sets the color palette, if available
		protected function set_color_palette() {
			if ( get_theme_support( 'editor-color-palette' ) ) {
				$this->color_palette = get_theme_support( 'editor-color-palette' )[0];
			}

			return $this;
		}

		// Runs through a settings groups entries, checks if they have
		// a color input inside and replaces them with the react-color picker
		protected function load_child_setting_color_picker() {
			$data = $this->get_parent()->get_parent()->get_data();

			// Checks if the setting group got entries
			if ( $data && is_array( $data ) ) {
				// Loops through the entries
				foreach ( $data as $key => $setting ) {
					$children = $this->get_parent()->get_children();

					// Loops through settings of the entry
					foreach ( $children as $child ) {
						// Checks if the setting is a color setting
						if ( $child->get_type() === 'setting_color' ) {
							$field_id = $child->get_parent()->get_parent()->get_field_id();
							$ID     = $field_id . '[' . $key . '][' . $child->get_ID() . ']';
							$data   = get_option( $field_id )[ $key ][ $child->get_ID() ]
								? get_option( $field_id )[ $key ][ $child->get_ID() ]
								: '';

							$this->localize_script( $ID, $data );
						}
					}
				}
			}
		}

		// Replaces the default color input, with the react-color picker
		public function load_color_picker() {
			// This setting is a child of a setting group
			if ( $this->get_parent()->get_module_name() === 'setting_group' ) {
				$this->load_child_setting_color_picker();
				//var_dump($this->get_parent()->get_name());
			}

			// Normal setting
			else {
				$this->localize_script( $this->get_parent()->get_field_id(), $this->get_parent()->get_data() );
			}
		}

		public function after_setup_theme() {
			$this->set_color_palette();
		
			add_action( 'sv_core_module_scripts_loaded', array( $this, 'load_color_picker' ) );
		}

		public function localize_script( $ID, $data ) {
			$this->get_active_core()
				->get_script('sv_core_color_picker')
				->set_localized(
					array_merge(
						$this->get_active_core()->get_script('sv_core_color_picker')->get_localized(),
						array(
							'color_palette' => $this->get_color_palette(),
							$ID => $data,
						)
					)
				);
		}
	}