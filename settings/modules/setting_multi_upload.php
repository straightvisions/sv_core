<?php
	
	namespace sv_core;
	
	class setting_multi_upload extends settings{
		private $parent				= false;
		private static $init_once	= false;
		
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent			= $parent;
		}
		public function get_page_ID(){
			$blog_page_check        = get_page_by_title($this->get_field_id());
			
			if(!isset($blog_page_check->ID)){
				$blog_page                 = array(
					'post_type' => 'page',
					'post_title' => $this->get_field_id(),
					'post_content' => '',
					'post_status' => 'private',
					'post_author' => 0,
				);
				return wp_insert_post($blog_page);
			}else{
				return $blog_page_check->ID;
			}
		}
		public function form(bool $title=false): string{
			if(is_admin() && !self::$init_once) {
				self::$init_once		= true;
				$post_id                = $this->get_page_ID();
				wp_enqueue_script('plupload-handlers');
				
				ob_start();
				media_upload_form();
				$form					= str_replace('"post_id":0', '"post_id":'.$post_id, ob_get_contents());
				ob_end_clean();
				
				if($this->get_parent()->get_filter()) {
					$allowed_extensions = ' mime_types : [{ title : "Allowed files", extensions : "'.implode(',',$this->get_parent()->get_filter()).'" }],';
					$form = str_replace('"max_file_size"', $allowed_extensions.'"max_file_size"', $form);
					$form = str_replace('<input type="file"', '<input type="file" accept=".'.implode(',.',$this->get_parent()->get_filter()).'"', $form);
				}
				
				return '
                <form enctype="multipart/form-data" method="post" action="'.admin_url('media-new.php').'" class="media-upload-form type-form validate" id="file-form">
                '.$form.'
                <p>'.__('Allowed Filetypes:',$this->get_module_name()).' '.
					(
					$this->get_parent()->get_filter() ?
						'.'.implode(',.',$this->get_parent()->get_filter()) :
						__('all', $this->get_module_name())
					).'</p>
                <script>
                var post_id = '.$post_id.', shortform = 3;
                </script>
                <input type="hidden" name="post_id" id="post_id" value="'.$post_id.'" />
                '.wp_nonce_field('media-form','_wpnonce',true,false).'
                <div id="media-items" class="hide-if-no-js"></div>
                </form>
                ';
			}else{
				return __('Multi Upload cannot be implemented more than once on a page.',$this->get_module_name());
			}
		}
		public function get_data(){
			$post_id                = $this->get_page_ID();
			$children               = get_children( array('post_parent' => $post_id) );
			
			return $children;
		}
		public function widget(string $value, $object): string{
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
            </p>
            ';
		}
	}