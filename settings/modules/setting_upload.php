<?php

namespace sv_core;

class setting_upload extends settings{
	private $parent						= false;
	protected static $updated			= array();
	private $allowed_filetypes			= array();

	/**
	 * @desc			initialize
	 * @author			Matthias Bathke
	 * @since			1.0
	 * @ignore
	 */
	public function __construct($parent=false){
		$this->parent			= $parent;
	}
	public function set_allowed_filetypes(array $filetypes): setting_upload{
		$this->allowed_filetypes			= $filetypes;
		
		return $this;
	}
	public function get_allowed_filetypes(): array{
		return $this->allowed_filetypes;
	}
	public function html($ID, $title, $description, $name, $value, $required, $disabled, $placeholder){
		$output = '
			<h4>'. $title . '</h4>
			<div>' . ($value ? wp_get_attachment_link($value, 'full', false, true) : '') . '</div>
			<div><a href="/wp-admin/post.php?post='.$value.'&action=edit" target="_blank">'.get_the_title($value).'</a></div>
			<div class="description">' . $description . '</div>
			<label for="' . $ID . '">
				<input
					data-sv_type="sv_form_field"
					class="sv_file"
				id="' . $ID . '"
				name="' . $name . '"
				type="file"
				'.((count($this->get_allowed_filetypes()) > 0) ? 'accept="'.implode(',',$this->get_allowed_filetypes()).'"' : '').'
				placeholder="'.$placeholder.'"
				' . $disabled . '
				/>
			</label>
			';
		
		if($value){
			$output .= '
			<label for="' . $ID . '_delete" style="justify-content: flex-end;">
			<input
					data-sv_type="sv_form_field"
				id="' . $ID . '_delete"
				name="' . $name . '_delete"
				value="1"
				type="checkbox"
				' . $disabled . '
				style="margin-right:16px;"
				/>
				
				'.__('Delete File', $this->get_prefix()).'
				</label>
			';
			}
			return $output;
	}
	public function field_callback($input){
		//@todo This method get's triggered twice, that's why a second upload is attempted
		if(isset(static::$updated[$this->get_parent()->get_prefix($this->get_parent()->get_ID())])){
			return $input;
		}
		static::$updated[$this->get_parent()->get_prefix($this->get_parent()->get_ID())] = true;
		
		if(isset($_POST[$this->get_parent()->get_prefix($this->get_parent()->get_ID()).'_delete'])){
			// delete checked
			wp_delete_attachment( $this->get_data(), true );
			delete_option($this->get_parent()->get_prefix($this->get_parent()->get_ID()));
		}elseif(intval($_FILES[$this->get_parent()->get_prefix($this->get_parent()->get_ID())]['size']) > 0 ) {
			
			// single setting field
			$data = $_FILES[ $this->get_parent()->get_prefix( $this->get_parent()->get_ID() ) ];
			
			if($data['name'] != '' &&
			   $data['type'] != '' &&
			   $data['tmp_name'] != '' &&
			   file_exists($data['tmp_name'])) {
				
				$input = $this->handle_file_upload(
					wp_handle_upload( $data, array( 'test_form' => false ) )
				);
			}

			return $input;
		}elseif(method_exists($this->get_parent()->get_parent(), 'get_ID') && isset($_FILES[$this->get_parent()->get_parent()->get_prefix($this->get_parent()->get_parent()->get_ID())])){
			// Group Settings
			$data_new		= array();
			$data			= $_FILES[$this->get_parent()->get_parent()->get_prefix($this->get_parent()->get_parent()->get_ID())];
			
			// reformat data to convert group uploads to single uploads
			foreach($data as $type => $values){
				$i = 0;
				foreach($values[0] as $value){
					$data_new[$i][$type]			= $value;
					$i++;
				}
			}
			
			foreach($data_new as $i => $data) {
				// do not attempt empty upload fields
				if($data['name'] != '' &&
				$data['type'] != '' &&
				$data['tmp_name'] != '' &&
				file_exists($data['tmp_name'])) {
					$input[$i][$this->get_parent()->get_ID()] = $this->handle_file_upload(
						wp_handle_upload( $data, array( 'test_form' => false ) )
					);
				}
			}

			return $input;
		}

		return $this->get_data();
	}
	private function handle_file_upload(array $file){
		// remove old attachment
		wp_delete_attachment( $this->get_data(), true );
		
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
}