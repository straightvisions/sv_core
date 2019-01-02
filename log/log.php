<?php

namespace sv_core;

class log extends sv_abstract {
	protected static $init_once					    = false;
	protected static $logs                          = array();
	protected static $temp_logs                     = array();
	protected static $state_titles                  = array();

	public function __construct() {
		if( !static::$init_once ) {
			add_action( 'init', array( $this, 'init' ) );

			static::$init_once					= true;
		}

		add_action( 'init', array( $this, 'init' ) );
	}

	public function __get( string $name ) {
		if( $this->get_path_lib_core( 'log/modules/' . $name . '.php',true ) ) {
			require_once( $this->get_path_lib_core('log/modules/' . $name . '.php' ) );
			$class_name							    = __NAMESPACE__ . '\\' . $name;

			$this->$name						    = new $class_name( $this );
			$this->$name->set_root($this->get_root());
			$this->$name->set_parent($this);

			return $this->$name;
		} else {
			throw new \Exception('Class ' . $name . ' could not be loaded (tried to load class-file ' . $this->get_module_name() . '/modules/' . $name . '.php' . ')' );
		}
	}

	public function init() {
		$this->logs();
		$this->state_titles();
		$this->log_settings->init();
		$this->create->init();
		$this->log_list->init();
		$this->log_docs->init();

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	// Init Methods
	private function logs() {
		$logs                                       = get_posts(
			array(
				'post_type'                         => 'sv_log',
				'post_status'                       => 'publish',
				'posts_per_page'                    => -1
			)
		);

		if( !empty( $logs ) ) {
			foreach ( $logs as $log ) {
				static::$logs[ $log->ID ]           = $log;
			}
		}
	}
	private function state_titles() {
		static::$state_titles                       = array(
			1                                       => __( 'Success',   $this->get_prefix() ),
			2                                       => __( 'Info',      $this->get_prefix() ),
			3                                       => __( 'Warning',   $this->get_prefix() ),
			4                                       => __( 'Error',     $this->get_prefix() ),
			5                                       => __( 'Critical',  $this->get_prefix() ),
		);
	}

	public function admin_menu() {
		if(defined('WP_DEBUG') && WP_DEBUG === true) {
			add_submenu_page(
				'straightvisions',                        // parent slug
				'Log',                                    // page title
				'Log',                                    // menu title
				'manage_options',                        // capability
				'log',                                    // menu slug
				function () {                            // callable function
					$this->load_page( $this->get_path_lib_core( 'log/backend/tpl/about.php' ) );
				}
			);
		}
	}

	// Getter Methods
	public function get_logs(): array {
		return static::$logs;
	}

	public function get_meta( int $ID, string $field, bool $single ) {
		return get_post_meta( $ID, $field, $single );
	}

	public function get_state_title( int $state ): string {
		return static::$state_titles[ $state ];
	}

	public function get_group( int $ID ): string {
		return get_the_terms( $ID, 'sv_log_group' )[0]->name;
	}

	public function get_state( int $ID ):string {
		return get_the_terms( $ID, 'sv_log_state' )[0]->name;
	}

	// Setter Methods
	public function set_post( int $ID, string $field, $value ): log {
		wp_update_post(
			array(
				'ID'                                => $ID,
				$field                              => $value
			)
		);

		return $this;
	}
	public function set_meta( int $ID, string $field, $value ): log {
		update_post_meta( $ID, $field, $value );

		return $this;
	}

	// Delete Method
	public function delete_logs( array $logs ) {
		foreach ( $logs as $ID ) {
			wp_trash_post( $ID );

			if( isset( static::$logs[ $ID ] ) ) {
				unset( static::$logs[ $ID ] );
			}
		}
	}
}