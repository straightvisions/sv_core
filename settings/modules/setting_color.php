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
			// Checks if the setting group got entries
			if ( $this->get_parent()->get_data() && is_array( $this->get_parent()->get_data() ) ) {

				// Loops through the entries
				foreach ( $this->get_parent()->get_data() as $key => $setting ) {

					// Loops through settings of the entry
					foreach ( $this->get_parent()->get_parent()->run_type()->get_children() as $child ) {

						// Checks if the setting is a color setting
						if ( $child->get_type() === 'setting_color' ) {
							$ID     = $child->get_field_id() . '[' . $key . '][' . $child->get_ID() . ']';
							$data   = get_option( $child->get_field_id() )[ $key ][ $child->get_ID() ]
								? get_option( $child->get_field_id() )[ $key ][ $child->get_ID() ]
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
			if (
				method_exists( $this->get_parent()->get_parent(), 'get_type' )
				&& $this->get_parent()->get_parent()->get_type() === 'setting_group'
			) {
				$this->load_child_setting_color_picker();
			}

			// Normal setting
			else {
				$this->localize_script( $this->get_field_id(), $this->get_data() );
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

		public function html( $ID, $title, $description, $name, $value ) {
			$this->localize_script( $this->get_field_id(), $this->get_data() );
			
			$color_value = esc_attr( $this->get_rgb( $value ) );
			$value = ! empty( $value ) ? 'value="' . esc_attr( $this->get_hex( $value ) ). '"' : '';

			return '
				<div class="sv_setting_header">
					<h4 title="' . __( 'Toggle Color Picker', 'sv_core' ) . '">' . $title . '</h4>
					<div
						class="sv_setting_color_display"
						title="' . __( 'Toggle Color Picker', 'sv_core' ) . '"
					>
						<div
							class="sv_setting_color_value"
							style="background-color:rgba(' . $color_value . ')"></div>
						
					</div>
				</div>
				<label for="' . $ID . '" class="sv_input_label_color sv_hidden">
					<input
					data-sv_type="sv_form_field"
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '"
					type="color"
					' . $value . '
					/>
				</label>
				<div class="description">' . $description . '</div>';
		}
	}