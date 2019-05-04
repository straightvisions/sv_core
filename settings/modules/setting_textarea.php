<?php

	namespace sv_core;

	class setting_textarea extends settings{
		private $parent				= false;

		/**
		 * @desc			initialize
		 * @author			Matthias Bathke
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent			= $parent;
		}
		public function html(string $ID, string $title, string $description, string $name, $value, string $required, string $disabled, $placeholder, $maxlength, $minlength, $max, $mix, $radio_style, $code_editor ) {
			if ( $code_editor ) {
				if ( empty( $code_editor ) ) {
					$code_editor = 'css';
				}
				
				wp_enqueue_code_editor( array( 'type' => 'text/' . $code_editor ) );
				
				echo '<script>jQuery( document ).ready( function() {
					wp.codeEditor.initialize( jQuery( "#' . $ID . '" ), { mode: "' . $code_editor . '" } );
				});
				</script>';
			}
			
			return '
				<h4>' . $title . '</h4>
				<div class="description">' . $description . '</div>
				<label for="' . $ID . '">
					<textarea style="height:200px;"
					class="sv_form_field sv_input"
					id="' . $ID . '"
					name="' . $name . '"
					' . $required . '
					' . $disabled . '">' . esc_textarea($value) . '</textarea>
				</label>';
		}
	}