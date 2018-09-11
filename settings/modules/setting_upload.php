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
		}
		public function get($value,$format,$object){
			return $this->$format($value,$object);
		}
		public function get_page_ID($settings_ID){
			$blog_page_check        = get_page_by_title($settings_ID);
			
			if(!isset($blog_page_check->ID)){
                $blog_page                 = array(
                    'post_type' => 'page',
                    'post_title' => $settings_ID,
                    'post_content' => '',
                    'post_status' => 'private',
                    'post_author' => 0,
                );
				return wp_insert_post($blog_page);
			}else{
			    return $blog_page_check->ID;
            }
        }
		public function get_attachments($settings_ID){
		    
        }
		public function ajax(){
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
						'action' => 'sv_settings_ajax'
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
					'_ajax_nonce' => wp_create_nonce('media-form'),
					'module'        => 'setting_upload',
					'action'	  => 'sv_settings_ajax'
				)
			);
			?>
			<script type="text/javascript">
				var global_uploader_options=<?php echo json_encode( $uploader_options ); ?>;
				//var wpUploaderInit=<?php echo json_encode( $uploader_options ); ?>;
                console.log(wpUploaderInit);
			</script>
			<?php
		}
		public function default($value,$object){
			if(is_admin()) {
				wp_enqueue_script('plupload-handlers');
				
				//wp_enqueue_script($this->get_module_name(), $object->core->get_url('lib/core/settings/js/setting_upload.js'), array('jquery', 'plupload-all'));
			}
			add_action( 'admin_footer', array($this,'admin_footer') );
			ob_start();
			media_upload_form();
			$form = ob_get_contents();
			ob_end_clean();
			
			return '
	        <form enctype="multipart/form-data" method="post" action="'.admin_url('media-new.php').'" class="media-upload-form type-form validate" id="file-form">
			<!--<div class="wp-core-ui drag-drop">
                <div id="plupload-upload-ui" class="'.$this->get_prefix('uploader').' multiple">
                    <div id="drag-drop-area">
                        <div class="drag-drop-inside">
                            <p class="drag-drop-info">Dateien hierher ziehen</p>
                            <p>oder</p>
                            <p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="'.__('Select Files').'" class="button"/></p>
                            <span class="ajaxnonce" id="'.wp_create_nonce( __FILE__ ).'"></span>
                        </div>
                    </div>
                </div>
			</div>-->
			'.$form.'
			<div id="media-items" class="hide-if-no-js"></div>
            <script>
            var post_id = '.$this->get_page_ID($object->get_prefix().$value).', shortform = 3;
            </script>
			<input type="hidden" name="post_id" id="post_id" value="'.$this->get_page_ID($object->get_prefix().$value).'" />
			'.wp_nonce_field('media-form','_wpnonce',true,false).'
			</form>
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