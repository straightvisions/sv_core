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

			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		}

		public function html( $ID, $title, $description, $name, $value ) {
			return '
				<h4>' . $title . '</h4>
				<div class="description">' . $description . '</div>
				<label for="' . $ID . '">
					<input
					data-sv_type="sv_form_field"
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '"
					type="color"
					value="' . esc_attr($value) . '"
					/>
				</label>';
		}
	}