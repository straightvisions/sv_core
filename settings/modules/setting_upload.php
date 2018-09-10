<?php
	
	namespace sv_core;
	
	class setting_upload extends settings{
		private $parent				= false;
		
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent			= $parent;
			add_action( 'wp_ajax_sv_core_settings_upload', 'ajax_upload' );
		}
		public function get($value,$format,$object){
			return $this->$format($value,$object);
		}
		public function ajax_upload(){
			// check ajax nonce
			check_ajax_referer( __FILE__ );
			
			if( current_user_can( 'upload_files' ) ) {
				$response = array();
				
				// handle file upload
				$id = media_handle_upload(
					'async-upload',
					0,
					array(
						'test_form' => true,
						'action' => $this->get_module_name().'_uploader'
					)
				);
				
				// send the file' url as response
				if( is_wp_error( $id ) ) {
					$response['status'] = 'error';
					$response['error'] = $id->get_error_messages();
				} else {
					$response['status'] = 'success';
					
					$src = wp_get_attachment_image_src( $id, 'thumbnail' );
					$response['attachment'] = array();
					$response['attachment']['id'] = $id;
					$response['attachment']['src'] = $src[0];
				}
				
			}
			
			echo json_encode( $response );
			exit;
		}
		public function admin_footer(){
			$uploader_options = array(
				'runtimes'          => 'html5,silverlight,flash,html4',
				'browse_button'     => 'sv_'.$this->get_module_name().'-button',
				'container'         => 'sv_'.$this->get_module_name(),
				'drop_element'      => 'sv_'.$this->get_module_name(),
				'file_data_name'    => 'async-upload',
				'multiple_queues'   => true,
				'max_file_size'     => wp_max_upload_size() . 'b',
				'url'               => admin_url( 'admin-ajax.php' ),
				'flash_swf_url'     => includes_url( 'js/plupload/plupload.flash.swf' ),
				'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
				'filters'           => array(
					array(
						'title' => __( 'Allowed Files' ),
						'extensions' => '*'
					)
				),
				'multipart'         => true,
				'urlstream_upload'  => true,
				'multi_selection'   => true,
				'multipart_params' => array(
					'_ajax_nonce' => '',
					'action'      => $this->get_module_name().'_uploader'
				)
			);
			?>
			<script type="text/javascript">
				var global_uploader_options=<?php echo json_encode( $uploader_options ); ?>;
			</script>
			<?php
		}
		public function default($value,$object){
		    if(is_admin()) {
				wp_enqueue_script($this->get_module_name(), $object->core->get_url('lib/core/settings/js/setting_upload.js'), array('jquery', 'plupload-all'));
			}
			add_action( 'admin_footer', array($this,'admin_footer') );
			
			return '
			<div class="sv_'.$this->get_module_name().' multiple">
				<input id="'.$this->get_module_name().'-button" type="button" value="'.__('Select Files').'" class="sv_setting_upload-button button">
				<span class="ajaxnonce" id="'.wp_create_nonce( __FILE__ ).'"></span>
			</div>
			';
		}
		public function widget($value,$object){
			return '
<p>
	<label for="' . $object->get_field_id($this->parent->get_ID()) . '">
		'.$this->parent->get_title().'
		<input
		class="widefat"
		id="' . $object->get_field_id($this->parent->get_ID()) . '"
		name="' . $object->get_field_name($this->parent->get_ID()) . '"
		type="text"
		value="' . esc_attr($value) . '"/>
		'.$this->parent->get_description().'
	</label>
</p>';
		}
	}