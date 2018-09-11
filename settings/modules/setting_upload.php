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
			add_action( 'wp_ajax_'.$this->get_prefix(), array($this,'ajax_upload') );
		}
		public function get($value,$format,$object){
			return $this->$format($value,$object);
		}
		public function ajax_upload(){
		    echo 'jo'; die('end');
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
						'action' => $this->prefix()
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
				'runtimes'		  => 'html5,silverlight,flash,html4',
				'browse_button'	 => 'plupload-browse-button',
				'container'		 => 'plupload-upload-ui',
				'drop_element'	  => 'drag-drop-area',
				'file_data_name'	=> 'async-upload',
				'multiple_queues'   => true,
				'max_file_size'	 => wp_max_upload_size() . 'b',
				'url'			   => admin_url( 'admin-ajax.php' ),
				'flash_swf_url'	 => includes_url( 'js/plupload/plupload.flash.swf' ),
				'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
				'filters'		   => array(
					array(
						'title' => __( 'Allowed Files' ),
						'extensions' => '*'
					)
				),
				'multipart'		 => true,
				'urlstream_upload'  => true,
				'multi_selection'   => true,
				'multipart_params' => array(
					'_ajax_nonce' => '',
					'action'	  => $this->get_prefix()
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
			<div class="wp-core-ui drag-drop">
                <div id="plupload-upload-ui" class="'.$this->get_prefix().' multiple">
                    <div id="drag-drop-area">
                        <div class="drag-drop-inside">
                            <p class="drag-drop-info">Dateien hierher ziehen</p>
                            <p>oder</p>
                            <p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="'.__('Select Files').'" class="button"/></p>
                            <span class="ajaxnonce" id="'.wp_create_nonce( __FILE__ ).'"></span>
                        </div>
                    </div>
                </div>
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