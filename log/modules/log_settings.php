<?php
namespace sv_core;

class log_settings extends log {
	public function __construct() {
		$this->get_root()->set_section_title( 'Log Settings' );
		$this->get_root()->set_section_desc( 'General settings for the Log.' );
		$this->get_root()->set_section_type( 'settings' );
	}
	/**
	 * @desc			initialize actions and filters
	 * @return	void
	 * @author			Matthias Bathke
	 * @since			1.0
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		if( !is_admin() ){
			$this->load_settings();
		}
	}
	private function load_settings() {
		$this->get_parent()->add_section( $this );

		$this->s['log_active']	= $this->get_root()::$settings->create( $this )
			->set_ID( 'log_active' )
			->set_title( 'Activate Log?' )
			->set_description( 'Enable or disable the Log.' )
			->load_type( 'checkbox' );
	}
	public function admin_init() {
		$this->load_settings();
	}
}