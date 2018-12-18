<?php

namespace sv_core;

class create extends log {
	private $object                                 = false;
	private $hash                                   = false;
	private $ID                                     = false;
	private $state                                  = false;
	private $title                                  = false;
	private $desc_public                            = false;
	private $desc_admin                             = false;
	private $file_path                              = false;

	public function __construct() {

	}

	public function init() {
		$this->post_type();
	}
	public function is_active(): bool{
		if( isset( $this->get_root()::$log->log_settings->get_settings()['log_active'] ) ) {
			return boolval( $this->get_root()::$log->log_settings->get_settings()['log_active']->run_type()->get_data() );
		} else {
			return false;
		}
	}
	// Creation Methods
	public function log( $object, $file_path ): create {
		$log								        = new self();
		$log->object                                = $object;
		$log->file_path                             = $file_path;
		$log->set_parent($this);
		$log->set_root($this->get_root());

		return $log;
	}

	public function post_type() {
		register_post_type( 'sv_log',
			array(
				'labels'						    => array(
					'name'						    => __( 'SV Log', $this->get_prefix() ),
					'singular_name'				    => __( 'SV Log', $this->get_prefix() ),
				),
				'public'						    => false,
				'exclude_from_search'			    => true,
				'publicly_queryable'			    => false,
				'show_ui'						    => false,
				'has_archive'					    => false,
				'menu_icon'						    => $this->get_url_lib_core( 'assets/logo_icon.png' ),
				'supports'					    	=> array( 'custom-fields' ),
				'delete_with_user'			    	=> false,
				'rewrite'					    	=> array(
					'slug'					    	=> 'sv_log'
				),
				'taxonomies'				    	=> array( 'sv_log_group', 'sv_log_state' )
			)
		);
		register_taxonomy(
			'sv_log_group',
			'sv_log',
			array(
				'label'							    => __( 'Group', $this->get_prefix() ),
				'labels'					    	=> array(
					'name'					    	=> __( 'Group', $this->get_prefix() ),
					'singular_name'			    	=> __( 'Group', $this->get_prefix() ),
				),
				'hierarchical'				    	=> false,
				'show_ui'					    	=> false,
				'show_admin_column'			    	=> true,
				'public'					    	=> false,
				'exclude_from_search'		    	=> true,
				'publicly_queryable'		    	=> false,
			)
		);
		register_taxonomy(
			'sv_log_state',
			'sv_log',
			array(
				'label'							    => __( 'State', $this->get_prefix() ),
				'labels'					    	=> array(
					'name'					    	=> __( 'State', $this->get_prefix() ),
					'singular_name'			    	=> __( 'State', $this->get_prefix() ),
				),
				'hierarchical'				    	=> false,
				'show_ui'					    	=> false,
				'show_admin_column'			    	=> true,
				'public'					    	=> false,
				'exclude_from_search'		    	=> true,
				'publicly_queryable'		    	=> false,
			)
		);
	}

	// Setter Methods
	public function set_state( string $state ): create {
		switch( $state ) {
			case 'success':
				$this->state                                = 1;
				break;
			case 'info':
				$this->state                                = 2;
				break;
			case 'warning':
				$this->state                                = 3;
				break;
			case 'error':
				$this->state                                = 4;
				break;
			case 'critical':
				$this->state                                = 5;
				break;
		}

		$this->add();

		return $this;
	}

	public function set_title( string $title ): create {
		$this->title                                = $title;
		$this->add();

		return $this;
	}

	public function set_desc( string $msg,  string $level = 'public' ): create {
		if( $level == 'public' ) {
			$this->desc_public                          = $msg;
		} else if( $level == 'admin' ) {
			$this->desc_admin                           = $msg;
		}
		$this->add();

		return $this;
	}

	private function set_hash(): create {
		$this->hash                                 = md5( $this->object->get_prefix() . $this->file_path . $this->title . $this->desc_admin . $this->desc_public .  $this->state );

		return $this;
	}

	// Create & Update Methods
	private function add() {
		if( is_admin() && !did_action( 'admin_init' )) {
			add_action( 'admin_init', array( $this, 'add' ) );
		} elseif( !is_admin() && !did_action( 'init' )) {
			add_action( 'init', array( $this, 'add' ) );
		} else {
			if($this->is_active()) {
				if ( $this->state && $this->title && $this->file_path && $this->desc_public && $this->desc_admin && $this->object ) {
					$this->set_hash();

					if ( ! empty( $this->get_logs() ) ) {
						$existing_log = false;

						foreach ( $this->get_logs() as $log ) {
							if ( get_post_meta( $log->ID, 'hash', true ) == $this->hash ) {
								$existing_log = $log;
							}
						}

						if ( $existing_log ) {
							$this->update( $existing_log );
						} else {
							$this->create();
						}
					} else {
						$this->create();
					}
				}
			}
		}
	}

	private function update( $log ) {
		$this->set_post( $log->ID, 'post_modified_gmt', date( 'Y:m:d H:i:s' ) );
		$this->set_meta( $log->ID, 'calls', $this->get_meta( $log->ID, 'calls', true ) + 1 );
	}

	private function create() {
		wp_insert_post(
			array(
				'post_title'            => $this->title,
				'post_type'             => 'sv_log',
				'post_status'           => 'publish',
				'tax_input'             => array(
					'sv_log_group'      => $this->object->get_root()->get_prefix(),
					'sv_log_state'      => $this->get_state_title( $this->state ),
				),
				'meta_input'            => array(
					'hash'              => $this->hash,
					'calls'             => 1,
					'desc_public'       => $this->desc_public,
					'desc_admin'        => $this->desc_admin,
					'file_path'         => $this->file_path,
				)
			)
		);
	}
}
