<?php

namespace sv_core;

if ( !class_exists( '\sv_core\core' ) ) {
	require_once('abstract.php');
	
	class core extends sv_abstract {
		public static $notices				= false;
		public static $settings				= false;
		public static $remote_get	    	= false;
		public static $widgets				= false;
		public static $info					= false;
		public static $metabox				= false;
		public static $scripts				= false;
		public $ajax_fragmented_requests	= false;
		public static $initialized			= false;

		// beta
		public static $scripts_localized_collection = array();

		public function setup_core( $path, $name ): bool{
		    $output = false;

			if( $this->core_validation() === true ){
				parent::$instances_active[ $name ]    = $this;

                // these modules are available in all instances and should be initialized once only.
                if ( static::$initialized === false ) {

                    self::$path_core			= trailingslashit( str_replace('\\','/', dirname( __FILE__ )) );
                    self::$url_core				= trailingslashit( str_replace('\\','/',get_site_url()) )
                                                . str_replace( str_replace('\\','/',ABSPATH),'', str_replace('\\','/', self::$path_core) );
	                parent::$active_core        = $this;

                    // run setup functions
                    $this->setup_settings();

                    $this->setup_remote_get();

                    $this->setup_widgets();

                    $this->setup_info();

                    $this->setup_metabox();

                    $this->setup_ajax_fragmented_requests();

                    // run setup actions
                    $this->setup_wp_actions();

                    // run setup filters
                    $this->setup_wp_filters($path);

                    // run setup credits
                    $this->setup_credits();
	
					add_action( 'wp_ajax_sv_core_gutenberg_save_post_update_metaboxes' , function(){
                            echo json_encode(apply_filters('sv_core_gutenberg_save_post_update_metaboxes', array()));
                            die();
                    }, 100 );
                }

				// run setup scripts
				$this->setup_scripts();

				// initialize sub core
				$this->init_subcore();

				// run setup modules
				$this->setup_modules($path);

				// run setup localized per plugin
				$this->setup_wp_plugin_localized();

                static::$initialized = true;
                $output              = true;

            }else{

			    $this->set_core_update_warning();

			}

			return $output;

		}
		private function core_validation(){
		    $output = false;

		    if(
                $this->get_root()->get_version_core_match() == $this->get_version_core()
                || ( defined('WP_DEBUG') && WP_DEBUG === true )
            ){

                $output = true;

            }

            return $output;

        }

        private function set_core_update_warning(){
            /*
            * @todo remove html from php class - use included tpl file instead
            */

            add_action('admin_init', function(){
                ?>
                <div class="update-nag">
                    <?php _e( 'Please check that all SV products are up to date. You may need to update ', 'sv_core' ); ?><strong><?php echo $this->get_name(); ?>.</strong><br/>
                    <?php _e( 'SV Core was loaded by', 'sv_core' ); ?>
					<em><?php echo $this->get_path_core(); ?></em>
					<?php _e( 'with version:', 'sv_core' ); ?>
                    <strong><?php echo $this->get_version_core(); ?></strong>, <?php echo sprintf(__( 'but %s v%s requires core-version', 'sv_core' ), $this->get_name(), $this->get_version(true)); ?>
                    <strong><?php echo $this->get_root()->get_version_core_match(); ?></strong>
                </div>
                <?php
            });

        }

        public function ajax_get_section(){
            $output = null;

			if( $_REQUEST['nonce'] &&
				$_REQUEST['section'] &&
				$_REQUEST['page'] &&
                wp_verify_nonce( $_REQUEST['nonce'], 'sv_admin_ajax_'.$_REQUEST['page'] ) !== false
                && $this->get_instances()[ $_REQUEST['page'] ]
            ) {

                $section = str_replace('#', '', $_REQUEST['section']);
				$section = $this->get_instances()[ $_REQUEST['page'] ]->get_root()->get_section_single($section);

                if( $section ){
					$section_name = $section['object']->get_prefix(); // var will be used in included file
					$path = $this->get_root()->get_path_core( 'backend/tpl/section_' . $section[ 'object' ]->get_section_type() . '.php' );
					ob_start();
					include( $path );
					$output = ob_get_contents();
					ob_end_clean();
                }

			}

            if($output){
				$this->send_response('success', '', base64_encode(utf8_decode($output))); // magic
            }else{
				$this->send_response('error', 'Section not found!');
            }


        }
		
		public function ajax_expert_mode(){
			if( empty($_POST) || isset($_POST) === false ){

				$this->ajaxStatus('error', __('Nothing to update.', 'sv_core'));

			}else{

				if( wp_verify_nonce( $_POST['nonce'], 'sv_expert_mode' ) !== false ) {

					if( get_current_user_id() ){

						update_user_meta(get_current_user_id(), 'sv_core_expert_mode', intval($_POST['state']));
						$this->ajaxStatus('success', __('Setting saved', 'sv_core'));

					}else{

						$this->ajaxStatus('error', __('You are unauthorized to perform this action.', 'sv_core'));

					}

				}else{

					$this->ajaxStatus( 'error', __('Nonce check cannot fail.', 'sv_core') );

				}

			}

        }

        public function ajaxStatus($status, $message, $data = NULL) {
            $response = array (
                'status'        => $status,
                'message'       => $message,
                'data'          => $data
            );

            $output = json_encode($response);

            exit($output);

        }

        private function send_response(string $status, string $message, $data = ''){
			$response = array (
				'status'        => $status,
				'message'       => $message,
				'data'          => $data
			);

			wp_send_json($response);
        }

		private function setup_credits() {
			add_filter('wp_headers', function($headers){

			    $headers['X-straightvisions'] = __( 'Website enhanced by straightvisions.com', 'sv_core' );
			    return $headers;

			});

			add_action('wp_footer', function(){

			    echo "\n\n".'<!--' . __( 'Website enhanced by straightvisions.com', 'sv_core' ). '-->'."\n\n";

			    },
                999999
            );

			add_filter('rocket_buffer', function($buffer){

			    return $buffer."\n\n".'<!--' . __( 'Website enhanced by straightvisions.com', 'sv_core' ). '-->'."\n\n";

			    },
                999999
            );

		}
		
		protected function setup_settings(){
            require_once( 'settings/settings.php' );

            static::$settings = new settings;
            static::$settings->set_root( $this->get_root() );
            static::$settings->set_parent( $this );

        }

        protected function setup_remote_get(){
            require_once( 'remote_get/remote_get.php' );

            static::$remote_get = new remote_get;
            static::$remote_get->set_root( $this->get_root() );
            static::$remote_get->set_parent( $this );

        }
		
		protected function setup_widgets(){
            require_once( 'widgets/widgets.php' );

            static::$widgets = new widgets;
            static::$widgets->set_root( $this->get_root() );
            static::$widgets->set_parent( $this );

        }
		
		protected function setup_info(){
            require_once( 'info/info.php' );

            static::$info = new info;
            static::$info->set_root( $this->get_root() );
            static::$info->set_parent( $this );
            static::$info->init();

        }
		
		protected function setup_metabox(){
            require_once( 'metabox/metabox.php' );

            static::$metabox = new metabox;
            static::$metabox->set_root( $this->get_root() );
            static::$metabox->set_parent( $this );
            static::$metabox->init();

        }
		
		protected function setup_ajax_fragmented_requests(){
            require_once( 'ajax_fragmented_requests/ajax_fragmented_requests.php' );

            $this->ajax_fragmented_requests = new ajax_fragmented_requests;
            $this->ajax_fragmented_requests->set_root( $this->get_root() );
            $this->ajax_fragmented_requests->set_parent( $this );
            $this->ajax_fragmented_requests->init();

        }
		
		protected function setup_wp_actions(){
		    // setup action for expert mode
            add_action('plugins_loaded', function(){
				//@todo Add description to describe what the expert mode does
                $this->get_root()->set_is_expert_mode(
                        $this->get_setting()
                            ->set_ID('sv_expert_mode')
                            ->set_title( __('Expert Mode', 'sv_core') )
                            ->set_is_no_prefix()
                            ->load_type('checkbox')
                            ->run_type()
                            ->set_data( intval( get_user_meta( get_current_user_id(), 'sv_core_expert_mode', true ) ) )
                            ->get_data()
                );

           });

            add_action( 'wp_ajax_sv_core_expert_mode', array($this, 'ajax_expert_mode'));

			add_action( 'wp_ajax_sv_ajax_get_section', array($this, 'ajax_get_section'));

            // setup update routine
            add_action( 'shutdown', array( $this, 'update_routine' ) );

            // setup init action
            add_action( 'init', array( $this, 'load_core_scripts' ), 100 );
        }

        // Loads all required core scripts
        public function load_core_scripts() {

            $this->get_root()->get_script( 'sv_core_admin' )
                ->set_path( $this->get_path_core( '../assets/admin.js' ), true, $this->get_url_core( '../assets/admin.js' ) )
                ->set_is_gutenberg()
				->set_is_backend()
                ->set_is_enqueued()
                ->set_is_no_prefix()
                ->set_type( 'js' )
                ->set_deps( array( 'jquery' ) )
                ->set_is_required()
                ->set_localized( array(
                    'ajaxurl'           => admin_url( 'admin-ajax.php' ),
                    'nonce_expert_mode' => \wp_create_nonce( 'sv_expert_mode' ),
                    'settings_saved'    => __('Setting saved', 'sv_core')
                ) )
                ->set_localized(static::$scripts_localized_collection['sv_core_admin'])
                ;

			$this->get_root()->get_script( 'sv_core_admin_sections' )
				->set_path( $this->get_path_core( '../assets/admin_sections.js' ), true, $this->get_url_core( '../assets/admin_sections.js' ) )
				->set_is_backend()
				->set_is_enqueued()
				->set_is_no_prefix()
				->set_type( 'js' )
				->set_deps( array( 'sv_core_admin', 'jquery' ) )
				->set_is_required();

            $this->get_root()->get_script( 'sv_core_color_picker' )
                ->set_is_no_prefix()
                ->set_path( $this->get_path_core( 'settings/js/sv_color_picker_min/sv_color_picker.min.js' ), true, $this->get_url_core( 'settings/js/sv_color_picker_min/sv_color_picker.min.js' ) )
                ->set_type( 'js' )
                ->set_deps( array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ) )
                ->set_is_backend()
                ->set_is_gutenberg()
                ->set_is_enqueued();

            // Creates an action when all required core scripts are loaded
            do_action( 'sv_core_module_scripts_loaded' );
        }

        public function setup_wp_plugin_localized(){
		    if(!did_action('init') || static::$initialized === false){
		        // wait for init
				add_action( 'init', array( $this, 'setup_wp_plugin_localized' ), 50 );
            }else{
		        // both are needed, don't know why
                // plugins
				$this->get_root()->get_script( 'sv_core_admin' )
                    ->set_localized(array(
						'nonce_sv_admin_ajax_'.$this->get_name() =>  \wp_create_nonce( 'sv_admin_ajax_'.$this->get_name() )
					));
				// theme
				static::$scripts_localized_collection['sv_core_admin'] = array(
				        'nonce_sv_admin_ajax_'.$this->get_name() =>  \wp_create_nonce( 'sv_admin_ajax_'.$this->get_name() )
				        );

            }

        }
		
		protected function setup_wp_filters(string $path){
            add_filter( 'plugin_action_links_' . plugin_basename( $path ) . '/' . plugin_basename( $path ) . '.php', array( $this, 'plugin_action_links' ), 10, 5 );
        }
		
		protected function setup_scripts(){
            require_once( 'scripts/scripts.php' );

            static::$scripts = new scripts;
            static::$scripts->set_root( $this->get_root() );
            static::$scripts->set_parent( $this );
            static::$scripts->init();
        }
		
		protected function setup_modules(string $path){
            if( file_exists( $path . 'lib/modules/modules.php' ) ) {
                $this->modules->init();
            }

        }

	}
}