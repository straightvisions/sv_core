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
		$value = is_array($value) ? $value['file'] : $value;

		$output = '
			<h4>'. $title . '</h4>
			<div>' . ($value ? wp_get_attachment_link($value, 'full', false, true) : '') . '</div>
			<div><a href="/wp-admin/post.php?post='.$value.'&action=edit" target="_blank">'.get_the_title($value).'</a></div>
			<div class="description">' . $description . '</div>
			<label for="' . $ID . '">
				<input
					data-sv_type="sv_form_field"
					class="sv_file"
				id="' . $ID . '[file]"
				name="' . ($name ? $name.'[file]' : '') . '"
				type="file"
				'.((count($this->get_allowed_filetypes()) > 0) ? 'accept="'.implode(',',$this->get_allowed_filetypes()).'"' : '').'
				placeholder="'.$placeholder.'"
				' . $disabled . '
				/>
			</label>
			';
		
		if($value){
			$output .= '
			<label for="' . $ID . '[delete]" style="justify-content: flex-end;">
			<input
					data-sv_type="sv_form_field"
				id="' . $ID . '[delete]"
				name="' . ($name ? $name.'[delete]' : '') . '"
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
	private function delete_attachment($attachment_id){
		wp_delete_attachment( $attachment_id, true );
	}
	private function reformat_file_data(array $data): array{
		$data_new = array();
		foreach ( $data as $type => $values ) {
			foreach ( $values as $i => $value ) {
				foreach ( $value as $name => $field ) {
					$data_new[ $i ][ $name ][ $type ] = $field['file'];
				}
			}
		}
		
		return $data_new;
	}
	private function delete_group_files(array $input): array{
		$data = $_POST[$this->get_parent()->get_parent()->get_prefix( $this->get_parent()->get_parent()->get_ID() )];
		foreach($data as $group => $fields){
			//var_dump($data);
			foreach($fields as $name => $value){
				if(is_array($value) && isset($value['delete']) && $value['delete'] == 1){
					$this->delete_attachment($input[$group][$name]['file']);
					unset($input[$group][$name]);
				}
			}
		}
		//die('end');
		return $input;
	}
	private function field_single($input){
		if(isset($_POST[$this->get_parent()->get_prefix($this->get_parent()->get_ID())]['delete'])){
			// delete checked
			$this->delete_attachment($this->get_data());
			delete_option($this->get_parent()->get_prefix($this->get_parent()->get_ID()));
		}elseif(intval($_FILES[$this->get_parent()->get_prefix($this->get_parent()->get_ID())]['size']['file']) > 0 ) {
			// single setting field
			$data = array(
				'name'			=> $_FILES[ $this->get_parent()->get_prefix( $this->get_parent()->get_ID() ) ]['name']['file'],
				'file'			=> $_FILES[ $this->get_parent()->get_prefix( $this->get_parent()->get_ID() ) ]['type']['file'],
				'tmp_name'		=> $_FILES[ $this->get_parent()->get_prefix( $this->get_parent()->get_ID() ) ]['tmp_name']['file'],
				'error'			=> $_FILES[ $this->get_parent()->get_prefix( $this->get_parent()->get_ID() ) ]['error']['file'],
				'size'			=> $_FILES[ $this->get_parent()->get_prefix( $this->get_parent()->get_ID() ) ]['size']['file'],
				'type'			=> $_FILES[ $this->get_parent()->get_prefix( $this->get_parent()->get_ID() ) ]['type']['file']
			);
			
			if($data['name'] != '' &&
			   $data['type'] != '' &&
			   $data['tmp_name'] != '' &&
			   file_exists($data['tmp_name'])) {
				$input = $this->handle_file_upload(
					wp_handle_upload( $data, array( 'test_form' => false ) )
				);
				return $input;
			}
		}
		return $input ? $input : $this->get_data();
	}
	private function field_group($input){
		if ( ! empty( $this->get_parent()->get_data() ) ) {
			foreach( $input as $i => $field ) {
				if ( ! empty( $this->get_parent()->get_data()[ $i ] ) ) {
					$input[ $i ] = $this->get_parent()->get_data()[ $i ];
				}
			}
		}
		//die();
		
		// make sure it's a group field
		if(method_exists($this->get_parent()->get_parent(), 'get_ID') && isset($_FILES[$this->get_parent()->get_parent()->get_prefix($this->get_parent()->get_parent()->get_ID())])) {
			// reformat data to convert group uploads to single uploads
			//var_dump($_FILES[ $this->get_parent()->get_parent()->get_prefix( $this->get_parent()->get_parent()->get_ID() ) ]);
			$data_new					= $this->reformat_file_data($_FILES[ $this->get_parent()->get_parent()->get_prefix( $this->get_parent()->get_parent()->get_ID() ) ]);
			//var_dump($data_new);
			// new uploads
			foreach ( $data_new as $i => $data ) {
				foreach ( $data as $name => $fields ) {
					// do not attempt empty upload fields
					if ( $fields['name'] != '' &&
						 $fields['type'] != '' &&
						 $fields['tmp_name'] != '' &&
						 file_exists( $fields['tmp_name'] ) ) {
						$input[ $i ][ $name ]['file'] = $this->handle_file_upload(
							wp_handle_upload( $fields, array( 'test_form' => false ) )
						);
					// make sure existing uploads are carried
					} elseif ( !isset($input[ $i ][ $name ]['file']) && isset( $this->get_parent()->get_data()[ $i ][ $name ] ) && intval( $this->get_parent()->get_data()[ $i ][ $name ] ) > 0 ) {
						$input[ $i ][ $name ] = $this->get_parent()->get_data()[ $i ][ $name ];
					}
				}
			}
			
			// delete files if requested
			$input						= $this->delete_group_files($input);
		}
		
		//var_dump($input);
		//die('end');
		
		return $input ? $input : $this->get_data();
	}
	private function is_single_field(){
		if(isset($_FILES[$this->get_parent()->get_prefix($this->get_parent()->get_ID())]['delete'])){
			return true;
		}
		if(isset($_FILES[$this->get_parent()->get_prefix($this->get_parent()->get_ID())]['name']['file'])){
			return true;
		}
		return false;
	}
	public function field_callback($input){
		//var_dump($_POST);
		//@todo This method get's triggered twice, that's why a second upload is attempted
		if(isset(static::$updated[$this->get_parent()->get_prefix($this->get_parent()->get_ID())])){
			return $input;
		}
		static::$updated[$this->get_parent()->get_prefix($this->get_parent()->get_ID())] = true;
		
		// check if single or group field
		if($this->is_single_field()){
			return $this->field_single($input);
		}else{
			return $this->field_group($input);
		}
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