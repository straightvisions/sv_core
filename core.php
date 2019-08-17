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

		public function setup_core( $path, $name ): bool{
		    $output = false;

			if( $this->core_validation() === true ){
				parent::$instances_active[ $name ]    = $this;

                // these modules are available in all instances and should be initialized once only.
                if ( static::$initialized === false ) {

                    self::$path_core			= trailingslashit( dirname( __FILE__ ) );
                    self::$url_core				= trailingslashit( get_site_url() )
                                                . str_replace( ABSPATH,'', self::$path_core );

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
                }


				// run setup scripts
				$this->setup_scripts();

				// initialize sub core
				$this->init_subcore();

				// run setup modules
				$this->setup_modules($path);

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

        private function set_core_update_warning(string $name){
            /*
            * @todo remove html from php class - use included tpl file instead
            */

            add_action('admin_init', function(){
                ?>
                <div class="update-nag">
                    <?php echo 'You need to update to run ' . $this->name; ?> <br/>
                    <?php echo 'SV Core was loaded from <em>' . $this->get_path_core() . '</em>  with version:'; ?>
                    <strong><?php echo $this->get_version_core(); ?></strong>, but software requires version
                    <strong><?php echo $this->get_root()->get_version_core_match(); ?></strong>
                </div>
                <?php
            });

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

		private function setup_credits() {
			add_filter('wp_headers', function($headers){

			    $headers['X-straightvisions'] = 'Website enhanced by straightvisions.com';
			    return $headers;

			});

			add_action('wp_footer', function(){

			    echo "\n\n".'<!-- Website enhanced by straightvisions.com -->'."\n\n";

			    },
                999999
            );

			add_filter('rocket_buffer', function($buffer){

			    return $buffer."\n\n".'<!-- Website enhanced by straightvisions.com -->'."\n\n";

			    },
                999999
            );

		}

		private function setup_settings(){
            require_once( 'settings/settings.php' );

            static::$settings = new settings;
            static::$settings->set_root( $this->get_root() );
            static::$settings->set_parent( $this );

        }

        private function setup_remote_get(){
            require_once( 'remote_get/remote_get.php' );

            static::$remote_get = new remote_get();
            static::$remote_get->set_root( $this->get_root() );
            static::$remote_get->set_parent( $this );

        }

        private function setup_widgets(){
            require_once( 'widgets/widgets.php' );

            static::$widgets = new widgets;
            static::$widgets->set_root( $this->get_root() );
            static::$widgets->set_parent( $this );

        }

        private function setup_info(){
            require_once( 'info/info.php' );

            static::$info = new info;
            static::$info->set_root( $this->get_root() );
            static::$info->set_parent( $this );
            static::$info->init();

        }

        private function setup_metabox(){
            require_once( 'metabox/metabox.php' );

            static::$metabox = new metabox;
            static::$metabox->set_root( $this->get_root() );
            static::$metabox->set_parent( $this );
            static::$metabox->init();

        }

        private function setup_ajax_fragmented_requests(){
            require_once( 'ajax_fragmented_requests/ajax_fragmented_requests.php' );

            $this->ajax_fragmented_requests = new ajax_fragmented_requests;
            $this->ajax_fragmented_requests->set_root( $this->get_root() );
            $this->ajax_fragmented_requests->set_parent( $this );
            $this->ajax_fragmented_requests->init();

        }

        private function setup_wp_actions(){
		    // setup action for expert mode
            add_action('plugins_loaded', function(){

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

            // setup update routine
            add_action( 'shutdown', array( $this, 'update_routine' ) );

            // setup init action
            add_action( 'init', function () {
                static::$scripts->create( $this )
                    ->set_ID( 'sv_core_admin' )
                    ->set_path( $this->get_url_core( '../assets/admin.js' ) )
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
                    ) );
            } );

        }

        private function setup_wp_filters(string $path){

            add_filter( 'plugin_action_links_' . plugin_basename( $path ) . '/' . plugin_basename( $path ) . '.php', array( $this, 'plugin_action_links' ), 10, 5 );

        }

        private function setup_scripts(){

            require_once( 'scripts/scripts.php' );

            static::$scripts = new scripts;
            static::$scripts->set_root( $this->get_root() );
            static::$scripts->set_parent( $this );
            static::$scripts->init();

        }

        private function setup_modules(string $path){

            if( file_exists( $path . 'lib/modules/modules.php' ) ) {
                $this->modules->init();
            }

        }

	}
}