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

			add_action( 'after_setup_theme', array( $this, 'load_color_picker' ) );
		}

		public function load_color_picker() {
			$this->color_palette =
				get_theme_support( 'editor-color-palette' )
				? get_theme_support( 'editor-color-palette' )[0]
				: false;

			add_action( 'sv_core_module_scripts_loaded', function() {
				// This setting is a child of a setting group
				if (
					method_exists( $this->get_parent()->get_parent(), 'get_type' )
					&& $this->get_parent()->get_parent()->get_type() === 'setting_group'
					&& is_array( $this->get_parent()->get_data() )
				) {
					$ID = $this->get_parent()->get_field_id() . '[sv_form_field_index][' . $this->get_parent()->get_ID() . ']';
					$this->localize_script( $ID, '0,0,0,1' );

					foreach ( $this->get_parent()->get_data() as $key => $setting ) {
						foreach ( $this->get_parent()->get_parent()->run_type()->get_children() as $child ) {
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

				// Normal setting
				else {
					$this->localize_script( $this->get_field_id(), $this->get_data() );
				}
			});
		}

		protected function localize_script( $ID, $data ) {
			$this->get_active_core()
				->get_script('sv_core_color_picker')
				->set_localized(
					array_merge(
						$this->get_active_core()->get_script('sv_core_color_picker')->get_localized(),
						array(
							'color_palette' => $this->color_palette,
							$ID             => $data,
						)
					)
				);
		}

		public function html( $ID, $title, $description, $name, $value ) {
			if ( $value && substr( $value, 0, 1) !== '#' ) {
				$value = $this->rgb_to_hex( $value );
			}

			$value = ! empty( $value ) ? 'value="' . esc_attr( $value ) . '"' : '';

			return '
				<h4>' . $title . '</h4>
				<div class="description">' . $description . '</div>
				<label for="' . $ID . '" class="sv_input_label_color">
					<input
					data-sv_type="sv_form_field"
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '"
					type="color"
					' . $value . '
					/>
				</label>';
		}
	}