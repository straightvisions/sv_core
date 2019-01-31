<?php

	namespace sv_core;

	class setting_upload extends settings{
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
		public function html($ID, $title, $description, $name, $value, $required, $disabled, $placeholder){
			if(!empty($description)) {
				$tooltip = '<div class="sv_tooltip">?</div>
				<div class="sv_tooltip_description">' . $description . '</div>';
			} else {
				$tooltip = '';
			}
			return '
				<h4>' . $title . '</h4>
				<div>' . wp_get_attachment_link($value, 'medium', false, true) . '</div>
				<label for="' . $ID . '">
					<input
					class="sv_form_field sv_file"
					id="' . $ID . '"
					name="' . $name . '"
					type="file"
					placeholder="'.$placeholder.'"
					' . $disabled . '
					/>
				</label>' . $tooltip;
		}
		public function field_callback($input){
			if(intval($_FILES[$this->get_parent()->get_prefix($this->get_parent()->get_ID())]['size']) > 0) {
				// remove old attachment
				wp_delete_attachment($this->get_data(), true);
				
				$file		= wp_handle_upload($_FILES[$this->get_parent()->get_prefix($this->get_parent()->get_ID())], array( 'test_form' => false ));
				
				$input				= wp_insert_attachment(array(
					'guid'           => wp_upload_dir()['url'] . '/' . basename( $file['file'] ),
					'post_mime_type' => $file['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file['file'] ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				),
					$file['file']);
				
				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $input, $file['file'] );
				wp_update_attachment_metadata( $input, $attach_data );
				
				return $input;
			}
			
			return $this->get_data();
		}
	}