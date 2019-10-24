<?php
	namespace sv_core;

	class setting_color extends settings {
		private $parent				        = false;

		/**
		 * @desc			initialize
		 * @author			Adrian Chudzynski
		 * @since			1.0
		 * @ignore
		 */
		public function __construct( $parent = false ) {
			$this->parent			= $parent;

			add_action( 'sv_core_module_scripts_loaded', function() {
				$this->get_root()->get_script( 'sv_core_color_picker' )
					->set_localized( array_merge( 
						$this->get_root()->get_script( 'sv_core_color_picker' )->get_localized(),
						array(
							$this->get_field_id() => $this->get_data()
						)
					)
				);
			});
		}

		public function html( $ID, $title, $description, $name, $value ) {
			if ( substr( $value, 0, 1) !== '#' ) {
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